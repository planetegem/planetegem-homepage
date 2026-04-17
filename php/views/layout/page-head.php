<?php

class PageHead extends View
{

    public $title;
    public $description;
    public $canonical;
    public $image;

    public function __construct()
    {
        parent::__construct();

        // Seo title & description
        $this->title = "Planetegem - Belgium's creative repository";
        $this->description = 
            "Looking for flowcharts, webgames or sci-fi stories with a unique Belgian twist? 
            Planetegem is the multimedia platform to visit!";

        // Get server url (for canonical & og:image)
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
        $host = $_SERVER['HTTP_HOST'];
        $rest = strtok($_SERVER["REQUEST_URI"], '?');

        $this->canonical = $protocol . '://' . $host . $rest;
        $this->image = $protocol . '://' . $host . '/style/assets/planetegem-logo.webp';
    }
}

?>