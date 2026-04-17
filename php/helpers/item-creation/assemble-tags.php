<?php

// Simple function to return all tags on an item in a readable format
function assemble_tags($item)
{
    $tags = [];

    // List language as tag
    $language_name = strtolower($item->language->short_name);
    $tags["language:{$item->language->id}"] = new Tag($item->language->name, "/language/{$language_name}");

    // Go through categories as tags
    if (isset($item->categories)) {
        foreach ($item->categories as $category) {
            $tags["category:{$category->id}"] = new Tag($category->name, "/categories/{$category->slug}");
        }
    }
    return $tags;
}
?>