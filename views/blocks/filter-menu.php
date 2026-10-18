<?php

namespace Views;
use Core\View;

class FilterMenu extends View
{

    public $languages;
    public $categories;
    public $selected_categories;
    public $selected_languages;
    public $selected_order;

    public function __construct()
    {
        parent::__construct();

        $filters = __api()->fetchFilters();

        $this->languages = $filters['languages']->data;
        $this->categories = $filters['categories']->data;

        $parsed_query = parse_query_string($_SERVER['QUERY_STRING'] ?? "");

        $this->selected_order = isset($parsed_query['order']) ? $parsed_query['order'][0] : "descending";
        $this->selected_categories = $parsed_query['category'] ?? [];
        $this->selected_languages = $parsed_query['language'] ?? [];

        global $selected_category;
        if (isset($selected_category))
            array_push($this->selected_categories, $selected_category);

        global $selected_language;
        if (isset($selected_language))
            array_push($this->selected_languages, $selected_language);
        
    }
}

?>