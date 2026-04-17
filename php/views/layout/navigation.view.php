<div id="page-nav-menu" class="page-header-container closed">
    <div class="wrapper">
        <header class="page-header">
            <button class="menu-button" id="menu-button">
                <?= file_get_contents(filename: 'resources/icons/menu_icon.svg') ?>
            </button>
            <a class="main-logo" href="/">
                <img src="/resources/planetegem-logo.webp" alt="A large planet overshadows the header" />
                <h1>Planetegem<br><small>creative&nbsp;repository</small></h1>
            </a>
            <div class="header-button-container">
                <a class="github-link desktop" href="https://github.com/planetegem" target="_blank">github</a>
                <button class="menu-button" id="filter-button">
                    <?= file_get_contents('resources/icons/filter_icon.svg') ?>
                </button>
            </div>
        </header>

        <aside class="menu-container">
            <div id="menu-overlay" class="overlay"></div>
            <nav id="main-menu" class="menu left">
                <button class="exit-button">
                    <?= file_get_contents('resources/icons/x_icon.svg') ?>
                </button>
                <?= $props['highlights_menu']->Render(); ?>
            </nav>

            <nav id="filter-menu" class="menu right">
                <button class="exit-button">
                    <?= file_get_contents('resources/icons/x_icon.svg') ?>
                </button>
                <?= $props['filter_menu']->Render(); ?>
            </nav>


        </aside>
    </div>
</div>