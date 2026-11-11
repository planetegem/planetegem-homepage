<?php

namespace Views;
use Core\SingleFileView;

class PageHead extends SingleFileView
{

    public string $title;
    public string $description;
    public string $canonical;
    public string $image;
    public bool $indexable;

    public function __construct(
        ?string $title = null,
        ?string $description = null,
        bool $indexable = true
    ) {
        $this->indexable = $indexable;

        // Seo title & description
        $this->title = $title ?? __('head.default.title');
        $this->description = $description ?? __('head.default.description');

        // Get server url (for canonical & og:image)
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
        $host = $_SERVER['HTTP_HOST'];
        $rest = strtok($_SERVER["REQUEST_URI"], '?');

        $this->canonical = $protocol . '://' . $host . $rest;
        $this->image = $protocol . '://' . $host . '/style/assets/planetegem-logo.webp';
    }
}

__halt_compiler();  
?>

<head>
    <meta http-equiv='content-type' content='text/html; charset=UTF-8'>
    <meta name="keywords"
        content="encyclopedia Belgica, culture, vleeskanon, sci-fi belgian novel, political memory, definitions, Belgium">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="<?= (__env() == 'production') ? 'index, follow' : 'noindex' ?>">
    <meta name="googlebot" content="notranslate">
    <meta name="author" content="Niels Van Damme">

    <!-- seo title -->
    <title><?= $this->title ?></title>
    <meta property="og:title" content="<?= $this->title ?>">
    <meta name="twitter:title" content="<?= $this->title ?>">

    <!-- seo description -->
    <meta name="description" content="<?= $this->description ?>">
    <meta property="og:description" content="<?= $this->description ?>">
    <meta name="twitter:description" content="<?= $this->description ?>">

    <!-- canonicals -->
    <link rel='canonical' content='<?= $this->canonical ?>'>
    <meta property='og:url' content='<?= $this->canonical ?>'>
    <meta property='og:site_name' content='<?= $this->canonical ?>'>
    <meta name='twitter:url' content='<?= $this->canonical ?>'>

    <!-- meta image -->
    <meta property="og:image" content="<?= $this->image ?>">
    <meta property="og:image:secure_url" content="<?= $this->image ?>">
    <meta name="twitter:image" content="<?= $this->image ?>">


    <link rel="stylesheet" href="/dist/main.css">
    <link rel="preload" href="/resources/fonts/Orbitron.ttf" as="font" type="font/ttf" crossorigin>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">

    <script src="/dist/main.js" type="module"></script>
</head>