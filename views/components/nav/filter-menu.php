<?php

namespace Views;
use Core\SingleFileView;

class FilterMenu extends SingleFileView
{

    public array $languages = [];
    public array $categories = [];
    public array $selected_categories = [];
    public array $selected_languages = [];
    public string $selected_order = 'descending';
    public array $language_checkboxes = [];
    public array $category_checkboxes = [];


    public function __construct()
    {
        $filters = __api()->fetchFilters();

        $this->languages = $filters['languages']->data ?? [];
        $this->categories = $filters['categories']->data ?? [];

        $parsed_query = parse_query_string($_SERVER['QUERY_STRING'] ?? "");

        $this->selected_order = $parsed_query['order'][0] ?? 'descending';
        $this->selected_categories = $parsed_query['category'] ?? [];
        $this->selected_languages = $parsed_query['language'] ?? [];

        global $selected_category;
        if (isset($selected_category) && !in_array($selected_category, $this->selected_categories)) {
            $this->selected_categories[] = $selected_category;
        }

        global $selected_language;
        if (isset($selected_language) && !in_array($selected_language, $this->selected_languages)) {
            $this->selected_languages[] = $selected_language;
        }

        foreach ($this->languages as $language) {
            $checked = in_array($language->short_name, $this->selected_languages, true);
            $this->language_checkboxes[] = new Checkbox($language->short_name, 'language', $language->name, $checked);
        }
        foreach ($this->categories as $category) {
            $checked = in_array($category->slug, $this->selected_categories, true);
            $this->category_checkboxes[] = new Checkbox($category->slug, 'category', $category->name, $checked);
        }

    }
}
__halt_compiler();    
?>

<h2 class="menu-header"><?= __('main.filters.title') ?></h2>
<p class="menu-subheader"><?= __('main.filters.subtitle') ?></p>
<form id="filter-form" action="/" method="get">
    <div class="form-option-group">
        <h3>
            <?= __('main.filters.languages.title') ?>
        </h3>
        <ul class="form-option-container">
            <?php foreach ($this->language_checkboxes as $language_checkbox): ?>
                <li><?= $language_checkbox ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="form-option-group">
        <h3>
            <?= __('main.filters.categories.title') ?>
        </h3>
        <ul class="form-option-container">
            <?php foreach ($this->category_checkboxes as $category_checkbox): ?>
                <li><?= $category_checkbox ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="form-dropdown-container">
        <label for="order-filter"><?= __('main.filters.order.title') ?></label>
        <div class="custom-select">
            <select name="order" id="order-filter">
                <option value="descending" <?= $this->selected_order === "descending" ? 'selected' : '' ?>>
                    <?= __('main.filters.order.descending_date') ?>
                </option>
                <option value="ascending" <?= $this->selected_order === "ascending" ? 'selected' : '' ?>>
                    <?= __('main.filters.order.ascending_date') ?>
                </option>
            </select>
        </div>
    </div>
    <div class="form-submission-container">
        <button class="submit-button">
            <?= file_get_contents('resources/icons/triangle.svg') ?>
            <span>
                <?= __('main.filters.apply') ?>
            </span>
        </button>
        <span id="filter-summary" class="filter-summary">(will show all items in descending
            order)</span>
    </div>
</form>