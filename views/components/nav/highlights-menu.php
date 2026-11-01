<?php

namespace Views;
use Core\SingleFileView;

class HighlightsMenu extends SingleFileView
{
    public array $internal_highlights;
    public array $external_highlights;
    public function __construct()
    {
        $this->internal_highlights = [
            new HighlightedItem("Games", "Being a premier game developer, Planetegem offers a wide variety of entertainment. Explore our games now.", "/categories/game"),
            new HighlightedItem("Mazes", "Lost your way in this labyrinthine repository? Go even deeper with our mazes!", "/categories/maze"),
            new HighlightedItem("The Meatcannon", "Follow the adventures of Clementine in this sci-fi novel. Proficiency in Dutch required.", "/items/Het-Vleeskannon-Van-Clementijn")
        ];
        $this->external_highlights = [
            new HighlightedItem("Pecten", "Visit pecten.be for a look at some of the finest shoes, accessories and general artworks in existence.", "https://www.pecten.be"),
            new HighlightedItem("Heetfruit", "Looking for a handcrafted gift with that special meaty flavour? Heetfruit.be is the place to be.", "https://www.heetfruit.be")
        ];
    }
}

__halt_compiler();    
?>

<h2 class="menu-header">
    <?= __('main.highlights.header') ?>
</h2>
<ul class="highlights-container">
    <?php foreach ($this->internal_highlights as $highlight) : ?>
        <li><?= $highlight; ?></li>
    <?php endforeach; ?>
</ul>
<div class="menu-divider">
    <h3>
        <?= __('main.highlights.other_sites') ?>
    </h3>
</div>
<ul class="highlights-container">
    <?php foreach ($this->external_highlights as $partner) : ?>
        <li><?= $partner ?>
    <?php endforeach; ?>
</ul>
<div class="menu-divider">
    <h3>
        <?= __('main.highlights.social_media') ?>
    </h3>
</div>
<ul class="menu-social-links">
    <li>
        <a class="social-link" href="https://www.instagram.com/planetegem/" target="_blank" rel="noopener noreferrer" aria-label="<?= __('main.socials.names.instagram') ?>">
            <?= file_get_contents('resources/icons/instagram_icon.svg') ?>
        </a>
    </li>
    <li>
        <a class="social-link" href="https://github.com/planetegem" target="_blank" rel="noopener noreferrer" aria-label="<?= __('main.socials.names.github') ?>">
            <?= file_get_contents('resources/icons/github_icon.svg') ?>
        </a>
    </li>
</ul>