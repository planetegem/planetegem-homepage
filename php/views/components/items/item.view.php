<article class="item hidden">
    <header class="item-header-container clipped-header">
        <h2 class="item-header clipped-header">
            <?= $props['title'] ?>
            <span class="timestamp">
                [added on <?= $props['timestamp'] ?>]
            </span>
        </h2>
    </header>
    <div class="item-body-container">
        <main class="item-body">
            <section class="item-description">
                <?= $props['description'] ?>
                <?= $props['relationship'] ? $props['relationship']->Render() : '' ?>
            </section>
            <figure class="item-figure">
                <?= $props['medium'] ? $props['medium']->Render() : '' ?>
            </figure>
            <nav class="item-links">
                <?php
                foreach ($props['links'] as $link) {
                    echo $link->Render();
                }
                ?>
            </nav>
            <ul class="item-tags">
                <?php
                foreach ($props['tags'] as $tag) {
                    echo $tag->Render();
                }
                ?>
            </ul>
        </main>

    </div>

</article>