<?php
namespace Traits;
use Views\Tag;

// Provides logic to assemble tags from an item's language and categories
trait HasTags
{
    private function assemble_tags(object|array $item)
    {
        // Normalize to an object 
        $item = (object) $item;
        $tags = [];

        // List language as a tag (if language data is present)
        if (isset($item->language)) {
            $lang = (object) $item->language;
            $langId = $lang->id ?? '';
            $langName = $lang->name ?? '';
            $langShortName = strtolower($lang->short_name ?? '');

            if ($langShortName && $langName) {
                $tags["language:{$langId}"] = new Tag($langName, "/language/{$langShortName}");
            }
        }

        // Go through categories as tags
        if (!empty($item->categories)) {
            foreach ($item->categories as $category) {
                $category = (object) $category;
                $catId = $category->id ?? null;
                $catName = $category->name ?? '';
                $catSlug = $category->slug ?? '';

                if ($catId && $catName && $catSlug) {
                    $tags["category:{$catId}"] = new Tag($catName, "/categories/{$catSlug}");
                }
            }
        }

        return $tags;
    }
}