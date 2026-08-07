<?php

namespace Core;

class ApiClient
{
    // Constructor: now also prepares a cache directory to limit API calls
    private string $urlBase;
    private string $cacheDir;

    public function __construct(string $urlBase, string $cacheDir)
    {
        $this->urlBase = $urlBase;
        $this->cacheDir = $cacheDir;

        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0755, true);
        }
    }

    // FETCHER
    // Uses PHP curl to fetch data from the url
    // Now also with caching to server side file and fallback to stale data if API is down
    public function fetch(string $path, int $ttl = 0): array
    {
        $url = $this->urlBase . $path;
        $cacheKey = md5($url);
        $cacheFile = $this->cacheDir . '/' . $cacheKey . '.json';

        // If already exists in cache and file is deemed fresh: retrieve from cache instead of calling API
        if ($ttl > 0 && file_exists($cacheFile) && (time() - filemtime($cacheFile) < $ttl)) {
            __log("Serving fresh data from cache for {$path}");

            $data = json_decode(file_get_contents($cacheFile));
            return ['response_code' => 200, 'request_path' => $url, 'response_body' => $data];
        }

        // Else fetch with curl
        // 1. Start curl
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-type: application/json']);

        // 2. Safety switch: if runnign locally, turn of ssl reqs
        $isLocal = (isset($_SERVER['REMOTE_ADDR']) && in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']));
        if ($isLocal) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }

        // 3. Execute curl
        $response = curl_exec($curl);
        $info = curl_getinfo($curl);

        __log("Fetching data from API; request path is {$url}; response code is {$info['http_code']}.");

        // 4. Return associative array with response code, request path (for debugging) and response body (if applicable)
        if ($info['http_code'] === 200) {
            $data = json_decode($response);

            if ($ttl > 0) {
                file_put_contents($cacheFile, json_encode($data), LOCK_EX);
            }

            return ['response_code' => 200, 'request_path' => $url, 'response_body' => $data];
        }

        // 5. If API fails, fall back to stale cache if available
        if (file_exists($cacheFile)) {
            __log("API failed with code {$info['http_code']}. Falling back to stale cache for {$url}");

            $data = json_decode(file_get_contents($cacheFile));
            return ['response_code' => 200, 'request_path' => $url, 'response_body' => $data];
        }

        // 6. Else just report what happened
        return ['response_code' => $info['http_code'], 'request_path' => $url];
    }


}