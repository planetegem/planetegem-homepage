<?php

namespace Core;

use Core\ApiClient;
use Core\App;

class SysInvAdminClient extends ApiClient
{
    public function __construct()
    {
        parent::__construct(App::service('ENV')->API_URL, '../.cache');
    }

    // Add some logic to fetch method to complete query string in API calls
    public function fetch(string $path, int $ttl = 0): array
    {
        // Add query string to API url fetch
        $parsed_query = parse_query_string($_SERVER['QUERY_STRING'] ?? "");
        $params = [];

        foreach ($parsed_query['category'] ?? [] as $category) {
            $params[] = "categories[]={$category}";
        }
        foreach ($parsed_query['language'] ?? [] as $language) {
            $params[] = "languages[]={$language}";
        }
        if (isset($parsed_query['order'])) {
            $params[] = "order={$parsed_query['order'][0]}";
        }
        $assembled_query_string = $params ? '?' . implode('&', $params) : '';

        // Perform fetch with SysInvAdmin friendly query string
        return parent::fetch($path . $assembled_query_string, $ttl);
    }

    // Helper method: get all possible filters to construct filter menu
    public function fetchFilters()
    {
        // 1. Fetch available languages
        $api_response = $this->fetch('languages/all', APP::service('ENV')->CACHE_LONG);
        $languages = $api_response['response_code'] == 200 ? $api_response['response_body'] : [];

        // 2. Fetch available categories
        $api_response = $this->fetch('categories/all', APP::service('ENV')->CACHE_LONG);
        $categories = $api_response['response_code'] == 200 ? $api_response['response_body'] : [];

        return ['languages' => $languages, 'categories' => $categories];
    }
}