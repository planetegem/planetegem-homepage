<h2 class="menu-header">
    <?= __('main.highlights.header') ?>
</h2>
<ul class="highlights-container">
    <li>
        <?= (new Views\HighlightedItem("Games", "Being a premier game developer, Planetegem offers a wide variety of entertainment. Explore our games now.", "/categories/game"))->Render() ?>
    </li>
    <li>
        <?= (new Views\HighlightedItem("Mazes", "Lost your way in this labyrinthine repository? Go even deeper with our mazes!", "/categories/maze"))->Render() ?>
    </li>
    <li>
        <?= (new Views\HighlightedItem("The Meatcannon", "Follow the adventures of Clementine in this sci-fi novel. Proficiency in Dutch required.", "/items/Het-Vleeskannon-Van-Clementijn"))->Render() ?>
    </li>
</ul>
<div class="menu-divider">
    <h3>
        <?= __('main.highlights.other_sites') ?>
    </h3>
</div>
<ul class="highlights-container">
    <li>
        <?= (new Views\HighlightedItem("Pecten", "Visit pecten.be for a look at some of the finest shoes, accessories and general artworks in existence.", "https://www.pecten.be"))->Render() ?>
    </li>
    <li>
        <?= (new Views\HighlightedItem("Heetfruit", "Looking for a handcrafted gift with that special meaty flavour? Heetfruit.be is the place to be.", "https://www.heetfruit.be"))->Render() ?>
    </li>
</ul>
<div class="menu-divider">
    <h3>
        <?= __('main.highlights.social_media') ?>
    </h3>
</div>
<ul class="menu-social-links">
    <li>
        <a class="social-link" href="https://www.instagram.com/planetegem/" target="_blank">
            <?= file_get_contents(filename: 'resources/icons/instagram_icon.svg') ?>
        </a>
    </li>
    <li>
        <a class="social-link" href="https://github.com/planetegem" target="_blank">
            <?= file_get_contents(filename: 'resources/icons/github_icon.svg') ?>
        </a>
    </li>
</ul>