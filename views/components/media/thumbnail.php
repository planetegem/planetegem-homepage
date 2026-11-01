<?php

namespace Views;
use Core\SingleFileView;

// Simple thumbnail: shows small format image, click it to open large format
class Thumbnail extends SingleFileView
{

    public string $src = '';
    public string $alt = '';
    public string $thumbnail = '';
    public string $placeholder = '';

    public function __construct(array $files)
    {
        // Grab first file from array
        $image = reset($files);

        // Return early if it's empty
        if (!$image)
            return;

        // Normalize file data
        $this->src = $image->path;
        $this->alt = $image->alt;
        $this->thumbnail = $image->variants->medium ?? $this->src;
        $this->placeholder = $image->variants->placeholder ?? $this->src;
    }
}

__halt_compiler();  
?>

<expandable-thumbnail>
    <template shadowrootmode="open">
        <img class="thumbnail" data-src="<?= htmlspecialchars($this->thumbnail, ENT_QUOTES, 'UTF-8') ?>"
            alt="<?= htmlspecialchars($this->alt, ENT_QUOTES, 'UTF-8') ?>"
            data-placeholder="<?= htmlspecialchars($this->placeholder, ENT_QUOTES, 'UTF-8') ?>"
            data-expanded="<?= htmlspecialchars($this->src, ENT_QUOTES, 'UTF-8') ?>" data-priority="high" />
    </template>
</expandable-thumbnail>