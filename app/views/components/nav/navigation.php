<?php 

namespace Views;

use Core\SingleFileView;

class Navigation extends SingleFileView 
{
    public ?FilterMenu $filter_menu = null;
    public ?HighlightsMenu $highlights_menu = null;

    public function __construct() 
    {
        $this->filter_menu = new FilterMenu();   
        $this->highlights_menu = new HighlightsMenu(); 
    }
}

__halt_compiler();
?>

<div id="page-nav-menu" class="page-header-container closed">
    <div class="wrapper">
        <header class="page-header">
            <button class="menu-button" id="menu-button" aria-label="<?= __('main.nav.open_menu') ?>">
                <?= file_get_contents('resources/icons/menu_icon.svg') ?>
            </button>
            <a class="main-logo" href="/">
                <img src="/resources/planetegem-logo-240.webp" alt="<?= __('main.header.image_alt') ?>" />
                <h1><?= __('main.header.site_title') ?><br><small><?= __('main.header.site_subtitle') ?></small></h1>
            </a>
            <div class="header-button-container">
                <a class="github-link desktop" href="https://github.com/planetegem" target="_blank" rel="noopener noreferrer">
                    <?= __('main.socials.names.github') ?>
                </a>
                <button class="menu-button" id="filter-button" aria-label="<?= __('main.nav.open_filters') ?>">
                    <?= file_get_contents('resources/icons/filter_icon.svg') ?>
                </button>
            </div>
        </header>

        <aside class="menu-container">
            <div id="menu-overlay" class="overlay"></div>
            
            <nav id="main-menu" class="menu left">
                <button class="exit-button" aria-label="<?= __('main.nav.close_menu') ?>">
                    <?= file_get_contents('resources/icons/x_icon.svg') ?>
                </button>
                <?= $this->highlights_menu ?>
            </nav>

            <nav id="filter-menu" class="menu right">
                <button class="exit-button" aria-label="<?= __('main.nav.close_menu') ?>">
                    <?= file_get_contents('resources/icons/x_icon.svg') ?>
                </button>
                <?= $this->filter_menu ?>
            </nav>
        </aside>
    </div>
</div>