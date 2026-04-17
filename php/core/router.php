<?php

// API PREP
// api_base_url determines where to feth, api_response will contain main content 
$api_base_url = 'https://inventory.planetegem.be/api/';

// FILTER MENU PREP
$filters = fetch_filters($api_base_url);

// DETERMINE ROUTE TO TAKE
// 1. Do some parsing
$current_url = parse_url($_SERVER['REQUEST_URI']);
$current_path = $current_url['path'];
$current_route = match (true) {
    str_starts_with($current_path, '/categories') => 'categories',
    str_starts_with($current_path, '/language') => 'languages',
    str_starts_with($current_path, '/items') => 'items',
    ($current_path === "/") || $current_path === "/inventory" => 'homepage',
    default => '404'
};
if ($debug_mode)
    txt_log("Current path is {$current_path}, taking ROUTE {$current_route}");

// 1b. preset query string logic
$query_string = $_SERVER['QUERY_STRING'] ?? "";
$deconstructed_query = query_deconstructor($query_string);

// 2. Take required route
switch ($current_route) {
    // 2a. Homepage route: list entire inventory, mind the query string for possible filters
    // example path: /?order=ascending
    case 'homepage':
        // query string logic
        $reconstructed_query = query_constructor($deconstructed_query);

        // get inventory items
        $api_response = api_fetcher($api_base_url . 'items/all' . $reconstructed_query);
        if ($debug_mode)
            txt_log("Fetched {$api_response['request_path']}; received response code {$api_response['response_code']}");
        $inventory = $api_response['response_code'] == 200 ? $api_response['response_body'] : [];

        // select page
        if ($api_response['response_code'] != 200) {
            $page = new Error404Page();
            break;
        } else if ($api_response['response_body']->count == '0') {
            $page = new NoResultsPage();
            break;
        }
        $page = new InventoryPage($inventory);
        break;

    // 2b. Category page: show only specific category (with seo properties)
    // Category needs to be identified with slug
    // example path: /categories/het-vleeskannon
    case 'categories':
        // split selected category from url
        $selected_category = explode('/', $current_path)[2];

        // get category items
        $api_response = api_fetcher($api_base_url . 'categories/slug/' . $selected_category);
        if ($debug_mode)
            txt_log("Fetched {$api_response['request_path']}; received response code {$api_response['response_code']}");
        $category = $api_response['response_code'] == 200 ? $api_response['response_body'] : [];

        // select page
        $page = $api_response['response_code'] == 404 ? new Error404Page() : new CategoryPage($category);
        break;

    // 2c. Language page: show only specific language
    case 'languages':
        // split selected category from url
        $selected_language = explode('/', $current_path)[2];

        // get language items
        $api_response = api_fetcher($api_base_url . 'languages/name/' . $selected_language);
        if ($debug_mode)
            txt_log("Fetched {$api_response['request_path']}; received response code {$api_response['response_code']}");
        $language = $api_response['response_code'] == 200 ? $api_response['response_body'] : [];

        // select page
        $page = $api_response['response_code'] == 404 ? new Error404Page() : new LanguagePage($language);
        break;

    // 2d. Item page: show one specific item, with all of its updates below
    // Item needs to be identified with slug
    // example path: /item/het-vleeskannon-van-clementijn
    case 'items':
        // split selected category from url
        $selected_item = explode('/', $current_path)[2];

        // get category items
        $api_response = api_fetcher($api_base_url . 'items/slug/' . $selected_item);
        if ($debug_mode)
            txt_log("Fetched {$api_response['request_path']}; received response code {$api_response['response_code']}");
        $item = $api_response['response_code'] == 200 ? $api_response['response_body'] : [];

        // select page
        $page = $api_response['response_code'] == 404 ? new Error404Page() : new ItemPage($item);
        break;

    default:
        $page = new Error404Page();
}

echo $page->Render();

?>