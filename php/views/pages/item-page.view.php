<!DOCTYPE html>
<html xmlns="https://www.w3.org/1999/xhtml" lang="nl" xml:lang="nl">
<?= $props['head']->Render() ?>

<body>
    <?= (new Background())->Render() ?>
    <?= (new Navigation())->Render() ?>
    
    <main class="inventory">
        <article class="main item hidden">
            <header class="item-header-container clipped-header">
                <h2 class="item-header clipped-header">
                    <?= $props['title'] ?>
                    <span class="timestamp">
                        [added on
                        <?= $props['timestamp'] ?>]
                    </span>
                </h2>
            </header>
            <div class="item-body-container">
                <main class="expanded-item-body">
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


                </main>
            </div>
        </article>
        <?php
        if (count($props['updates']) > 0) {
            echo (new UpdateHeader())->Render();
            foreach ($props['updates'] as $update) {
                echo $update->Render();
            }
        }
        ?>
    </main>
    <?= (new Footer())->Render() ?>
</body>

</html>