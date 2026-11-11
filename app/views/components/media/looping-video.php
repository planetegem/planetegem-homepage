<?php

namespace Views;
use Core\SingleFileView;

// Simple thumbnail: shows small format image, click it to open large format
class LoopingVideo extends SingleFileView
{

    public string $video = '';
    public string $poster = '';

    public function __construct(array $files)
    {
        // Grab first file from array
        $video = reset($files);

        // Return early if it's empty
        if (!$video)
            return;

        // Normalize file data
        $this->video = $video->path ?? '';
        $this->poster = $video->poster ?? '';
    }
}

__halt_compiler();    
?>

<expandable-gifv data-video="<?= htmlspecialchars($this->video, ENT_QUOTES, 'UTF-8') ?>">
    <template shadowrootmode="open">
        <figure class="gifv">
            <img class="thumbnail" data-src="<?= htmlspecialchars($this->poster, ENT_QUOTES, 'UTF-8') ?>"
                data-priority="high" />
        </figure>
    </template>
</expandable-gifv>