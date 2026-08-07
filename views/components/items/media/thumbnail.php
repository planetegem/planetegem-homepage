<?php

namespace Views;
use Core\View;

class Thumbnail extends View
{

    public $src;
    public $alt;

    public function __construct($media)
    {
        parent::__construct();

        if ($media->files) {
            $this->src = "https://inventory.planetegem.be/storage/" . $media->files[0]->path;
            $this->alt = $media->files[0]->alt;
        }

    }
}

?>