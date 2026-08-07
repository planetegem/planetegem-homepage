<?php

namespace Routes;

use Core\Route;
use Core\App;
use Views\HomePage;
use Views\NoResultsPage;
use Views\Error404Page;

// Homepage route: list entire inventory, mind the query string for possible filters
// example path: /?order=ascending
class HomeRoute extends Route
{
    public string $path = '/';
    public string $method = 'GET';


    public function go(array $params): void
    {
        // get inventory items
        $api_response = __api()->fetch('items/all', APP::service('ENV')->CACHE_LONG);
        $inventory = $api_response['response_code'] == 200 ? $api_response['response_body'] : [];

        // select page
        if ($api_response['response_code'] != 200) {
            $page = new Error404Page();
        } else if ($api_response['response_body']->count == '0') {
            $page = new NoResultsPage();
        } else {
            $page = new HomePage($inventory);
        }
        echo $page->Render();
    }
}
