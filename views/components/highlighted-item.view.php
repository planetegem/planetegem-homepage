<a class="highlight" href="<?= $props['url'] ?>" target="<?= $props['target'] ?>">
    <header>
        <?= file_get_contents('resources/icons/triangle.svg') ?>
        <p>
            <?= $props['title'] ?>
        </p>
    </header>
    <main>
        <p>
            <?= $props['description'] ?>
        </p>
    </main>
</a>