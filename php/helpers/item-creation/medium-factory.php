<?php

// Evaluate medium type and return correct component to render it
function medium_factory($medium)
{
    switch ($medium->type) {
            case 'image':
            case 'thumbnail':
                return new Thumbnail($medium);
                break;
            default:
                return null;
        }
}
?>