<?php

namespace Views;
use Core\View;

class Gallery extends View
{

    public $images = [];

    public function __construct($media)
    {
        parent::__construct();

        if ($media->files) {
            foreach ($media->files as $image){
                $this->images[] = [
                    'src' => $image->path,
                    'alt' => $image->alt,
                    'thumbnail' => $image->variants->medium ?? $image->path,
                    'placeholder' => $image->variants->placeholder ?? $image->path
                ];
            }
        }
    }
}

?>