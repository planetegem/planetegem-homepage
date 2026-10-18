<?php

namespace Views;
use Core\View;

class Thumbnail extends View
{

    public $src;
    public $alt;
    public $thumbnail;
    public $placeholder;

    public function __construct($media)
    {
        parent::__construct();

        if ($media->files) {
            $image = $media->files[0];
            $this->src = $image->path;
            $this->alt = $image->alt;
            $this->thumbnail = $image->variants->medium ?? $this->src;
            $this->placeholder = $image->variants->placeholder ?? $this->src;
        }
    }
}

?>