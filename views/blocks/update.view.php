<article class="item update hidden">
    <main class="update">
        <h4 class="item-header">
            <?= $props['title'] ?>
            <span class="timestamp">
                <?= __('main.timestamp', ['timestamp' => $props['timestamp']]) ?>
            </span>
        </h4>
        <section class="item-description">
            <?= $props['description'] ?>
            <ul class="item-tags">
                <?php foreach ($props['tags'] as $tag): ?>
                    <?= $tag->Render(); ?>
                <?php endforeach; ?>
            </ul>
        </section>
        <?php if ($props['medium']): ?>
            <figure class="item-figure <?= $props['medium'] ? '' : 'empty' ?>">
                <?= $props['medium'] ? $props['medium']->Render() : '' ?>
            </figure>
        <?php endif; ?>
    </main>
    <nav class="item-links">
        <?php foreach ($props['links'] as $link): ?>
            <?= $link->Render() ?>
        <?php endforeach; ?>
    </nav>
</article>