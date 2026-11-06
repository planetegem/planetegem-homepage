<?php

namespace Views;
use Core\SingleFileView;

// Gallery shows multiple images, using the expandable gallery web component
class Gallery extends SingleFileView
{
    public array $images = [];
    public function __construct(array $files)
    {
        // Normalize the files to be viewed
        $this->images = array_map(fn($image) => [
            'src' => $image->path,
            'alt' => $image->alt,
            'thumbnail' => $image->variants->medium ?? $image->path,
            'placeholder' => $image->variants->placeholder ?? $image->path,
        ], $files);
    }
}

__halt_compiler();   
?>

<expandable-gallery>
    <template shadowrootmode="open">
        <figure class="gallery">
            <?php foreach ($this->images as $image): ?>
                <img class="thumbnail" data-src="<?= htmlspecialchars($image['thumbnail'], ENT_QUOTES, 'UTF-8') ?>"
                    alt="<?= htmlspecialchars($image['alt'], ENT_QUOTES, 'UTF-8') ?>"
                    data-placeholder="<?= htmlspecialchars($image['placeholder'], ENT_QUOTES, 'UTF-8') ?>"
                    data-expanded="<?= htmlspecialchars($image['src'], ENT_QUOTES, 'UTF-8') ?>" data-priority="high" />
            <?php endforeach; ?>
            <div class="nav-container">
                <button class="nav-button left">&#x1F804;</button>
                <nav class="gallery-nav"></nav>
                <button class="nav-button right">&#x1F806;</button>
            </div>
        </figure>
    </template>
</expandable-gallery>