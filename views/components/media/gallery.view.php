<expandable-gallery>
    <template shadowrootmode="open">
        <figure class="gallery">
            <?php foreach ($props['images'] as $image): ?>
                <img class="thumbnail" data-src="<?= $image['thumbnail'] ?>" alt="<?= $image['alt'] ?>"
                    data-placeholder="<?= $image['placeholder'] ?>" data-expanded="<?= $image['src'] ?>"
                    data-priority="high" />
            <?php endforeach; ?>
            <div class="nav-container">
                <button class="nav-button left">&#x1F804;</button>
                <nav class="gallery-nav"></nav>
                <button class="nav-button right">&#x1F806;</button>
            </div>
        </figure>
    </template>
</expandable-gallery>