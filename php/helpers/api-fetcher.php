<?php

// API FETCHER
// Uses PHP curl to fetch data from the url
function api_fetcher($url)
{
    // Start Curl
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-type: application/json']);

    // Safety switch: if runnign locally, turn of ssl reqs
    $isLocal = (isset($_SERVER['REMOTE_ADDR']) && in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']));
    if ($isLocal) {
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    }

    // Execute curl
    $response = curl_exec($curl);
    $info = curl_getinfo($curl);

    // Return associative array with response code, request path (for debugging) and response body (if applicable)
    if ($info['http_code'] === 200) {
        $data = json_decode($response);
        return ['response_code' => 200,  'request_path' => $url, 'response_body' => $data];
    }
    return ['response_code' => $info['http_code'],  'request_path' => $url];
}