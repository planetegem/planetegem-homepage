<?php

namespace Routes;

use Core\Route;
use Core\App;
use Views\Error404Page;
use Views\LanguagePage;

// Language page: show only specific language & all associated items
class LanguageRoute extends Route
{
    public string $path = '/language/{selectedLanguage}';

    public function go(array $params): void
    {
        $selected_language = htmlspecialchars($params['selectedLanguage'] ?? '');
        $GLOBALS['selected_language'] = $selected_language;

        // get category items
        $api_response = __api()->fetch('languages/name/' . $selected_language, APP::service('ENV')->CACHE_LONG);
        $language = $api_response['response_code'] == 200 ? $api_response['response_body']->data : [];

        // select page
        $page = $api_response['response_code'] == 404 ? new Error404Page() : new LanguagePage($language);
        echo $page->Render();
    }
}
