<h2 class="menu-header">query repository</h2>
<p class="menu-subheader">Is Planetegem too big for you? Apply filters to create your very own safe
    space!</p>
<form id="filter-form" action="/" method="get">
    <ul class="form-option-container">
        <h3>Languages</h3>
        <?php
        foreach ($props['languages'] as $language) {
            $checked = in_array($language->short, $props['selected_languages']);
            echo "<li>" . (new Checkbox($language->short, 'language', $language->name, $checked))->Render() . "</li>";
        }
        ?>
    </ul>
    <ul class="form-option-container">
        <h3>Categories</h3>
        <?php
        foreach ($props['categories'] as $category) {
            $checked = in_array($category->slug, $props['selected_categories']);
            echo "<li>" . (new Checkbox($category->slug, 'category', $category->name, $checked))->Render() . "</li>";
        }
        ?>
    </ul>
    <div class="form-dropdown-container">
        <label for="order-filter">Order by </label>
        <div class="custom-select">
            <select name="order" id="order-filter">
                <option value="descending" <?php if($props['selected_order'] == "descending") echo "selected"; ?> >most recent first</option>
                <option value="ascending" <?php if($props['selected_order'] == "ascending") echo "selected"; ?> >oldest first</option>
            </select>
        </div>
    </div>
    <div class="form-submission-container">
        <button class="submit-button">
            <?= file_get_contents('resources/icons/triangle.svg') ?>
            <span>Apply Filters</span>
        </button>
        <span id="filter-summary" class="filter-summary">(will show all items in descending
            order)</span>
    </div>
</form>