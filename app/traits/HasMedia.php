<?php
namespace Traits;
use Views\Thumbnail;
use Views\Gallery;

// Methods required to include media
trait HasMedia
{
    // Evaluate medium type and return correct component to render it
    function create_media_component(object|array|null $medium) : ?object
    {
        // Return early if no valid input
        if (!$medium) return null;

        // Normalize the medium if passed as array
        $medium = (object) $medium;

        // And normalize important props
        $type = $medium->type ?? null;
        $files = $medium->files ?? [];

        // Return early if no files present
        if (empty($files)) return null;

        // Create correct child component based on type
        return match ($type) {
            'image', 'thumbnail' => new Thumbnail($files),
            'gallery', 'carousel' => new Gallery($files),
            default => null,
        };
    }

}