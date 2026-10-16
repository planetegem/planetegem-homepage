<?php

use Views\Gallery;

// Evaluate medium type and return correct component to render it
function medium_factory($medium)
{
    switch ($medium->type) {
            case 'image':
            case 'thumbnail':
                return new Views\Thumbnail($medium);
            case 'gallery':
            case 'carousel':
                return new Views\Gallery($medium);
            default:
                return null;
        }
}
?>