<?php

class Thumbnail extends View {

    public $src;
    public $alt;

    public function __construct($medium)
    {
        parent::__construct();

        $this->src = "https://inventory.planetegem.be/storage/" . $medium->files[0][0];
        $this->alt = $medium->alt;
    }
}

?>