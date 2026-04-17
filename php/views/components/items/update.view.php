<article class="update hidden">
    <main class="update">
        <h4 class="item-header">
            <?= $props['title'] ?>
            <span class="timestamp">
                [added on
                <?= $props['timestamp'] ?>]
            </span>
        </h4>
        <section class="item-description">
            <?= $props['description'] ?>
            <ul class="item-tags">
                <?php
                foreach ($props['tags'] as $tag) {
                    echo $tag->Render();
                }
                ?>
            </ul>
        </section>
        <?php
        if ($props['medium']) {
            echo '<figure class="item-figure">';
            echo $props['medium']->Render();
            echo '</figure>';
        }
        ?>
    </main>
    <nav class="item-links">
        <?php
        if (isset($props['links'])) {
            foreach ($props['links'] as $link) {
                echo $link->Render();
            }
        }
        ?>
    </nav>
</article>