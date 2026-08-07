<h2 class="menu-header"><?= __('main.filters.title') ?></h2>
<p class="menu-subheader"><?= __('main.filters.subtitle') ?></p>
<form id="filter-form" action="/" method="get">
    <ul class="form-option-container">
        <h3><?= __('main.filters.languages.title') ?></h3>
        <?php
        foreach ($props['languages'] as $language) {
            $checked = in_array($language->short, $props['selected_languages']);
            echo "<li>" . (new Views\Checkbox($language->short, 'language', $language->name, $checked))->Render() . "</li>";
        }
        ?>
    </ul>
    <ul class="form-option-container">
        <h3><?= __('main.filters.categories.title') ?></h3>
        <?php
        foreach ($props['categories'] as $category) {
            $checked = in_array($category->slug, $props['selected_categories']);
            echo "<li>" . (new Views\Checkbox($category->slug, 'category', $category->name, $checked))->Render() . "</li>";
        }
        ?>
    </ul>
    <div class="form-dropdown-container">
        <label for="order-filter"><?= __('main.filters.order.title') ?></label>
        <div class="custom-select">
            <select name="order" id="order-filter">
                <option value="descending" <?php if ($props['selected_order'] == "descending")
                    echo "selected"; ?>>
                    <?= __('main.filters.order.descending_date') ?>
                </option>
                <option value="ascending" <?php if ($props['selected_order'] == "ascending")
                    echo "selected"; ?>>
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