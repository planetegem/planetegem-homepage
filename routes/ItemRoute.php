<?php

namespace Routes;

use Core\Route;
use Core\App;
use Views\Error404Page;
use Views\ItemPage;

// Item page: show one specific item, with all of its updates below
// Item needs to be identified with slug
// example path: /items/het-vleeskannon-van-clementijn

class ItemRoute extends Route
{
    public string $path = '/items/{selectedItem}';

    public function go(array $params): void
    {
        $selected_item = htmlspecialchars($params['selectedItem'] ?? '');

        // get selected items
        $api_response = __api()->fetch('items/slug/' . $selected_item, APP::service('ENV')->CACHE_LONG);
        $item = $api_response['response_code'] == 200 ? $api_response['response_body']->data : [];

        // select page
        $page = $api_response['response_code'] == 404 ? new Error404Page() : new ItemPage($item);
        echo $page->Render();
    }
}
