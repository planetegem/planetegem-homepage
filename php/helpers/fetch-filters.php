<?php

// Helper function: prepares possible filters (for navigation)
function fetch_filters($api_base_url)
{
    global $debug_mode;

    // FILTER MENU PREP
    // 1. Fetch available languages
    $api_response = api_fetcher($api_base_url . 'languages/all');
    if ($debug_mode)
        txt_log("Fetched {$api_response['request_path']}; received response code {$api_response['response_code']}");
    $languages = $api_response['response_code'] == 200 ? $api_response['response_body'] : [];

    // 2. Fetch available categories
    $api_response = api_fetcher($api_base_url . 'categories/all');
    if ($debug_mode)
        txt_log("Fetched {$api_response['request_path']}; received response code {$api_response['response_code']}");
    $categories = $api_response['response_code'] == 200 ? $api_response['response_body'] : [];

    return ['languages' => $languages, 'categories' => $categories];
}


?>