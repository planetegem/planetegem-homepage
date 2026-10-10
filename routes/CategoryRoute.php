<?php

namespace Routes;

use Core\Route;
use Core\App;
use Views\Error404Page;
use Views\CategoryPage;

// Category page: show only specific category (with seo properties)
// // Category needs to be identified with slug
// example path: /categories/het-vleeskannon

class CategoryRoute extends Route
{
    public string $path = '/categories/{selectedCategory}';

    public function go(array $params): void
    {
        $selected_category = htmlspecialchars($params['selectedCategory'] ?? '');
        $GLOBALS['selected_category'] = $selected_category;

        // get category items
        $api_response = __api()->fetch('categories/slug/' . $selected_category, APP::service('ENV')->CACHE_LONG);
        $category = $api_response['response_code'] == 200 ? $api_response['response_body']->data : [];

        // select page
        $page = $api_response['response_code'] == 404 ? new Error404Page() : new CategoryPage($category);
        echo $page->Render();
    }
}
