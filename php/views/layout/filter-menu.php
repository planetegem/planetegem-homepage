<?php

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

        global $filters;
        $this->languages = $filters['languages'];
        $this->categories = $filters['categories'];

        global $deconstructed_query;
        $this->selected_order = isset($deconstructed_query['order']) ? $deconstructed_query['order'][0] : "descending";
        $this->selected_categories = $deconstructed_query['category'] ?? [];
        $this->selected_languages = $deconstructed_query['language'] ?? [];

        global $selected_category;
        if (isset($selected_category))
            array_push($this->selected_categories, $selected_category);

        global $selected_language;
        if (isset($selected_language))
            array_push($this->selected_languages, $selected_language);
        
    }
}

?>