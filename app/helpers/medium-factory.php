<?php

use Views\Gallery;

// Evaluate medium type and return correct component to render it
function medium_factory($medium)
{
    $resulting_medium = null;
    
    switch ($medium->type) {
            case 'image':
            case 'thumbnail':
                if ($medium->files)
                    $resulting_medium = new Views\Thumbnail($medium->files);
                break;
            case 'gallery':
            case 'carousel':
                if ($medium->files)
                    $resulting_medium = new Views\Gallery($medium->files);
                break;
        }
    return $resulting_medium;
}
?>