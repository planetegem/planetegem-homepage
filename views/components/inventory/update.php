<?php

namespace Views;
use Core\SingleFileView;
use Traits\HasMedia;
use Traits\HasTags;
use Traits\HasTimestamps;

class Update extends SingleFileView
{
    use HasMedia;
    use HasTags;
    use HasTimestamps;
    public string $title;
    public string $timestamp;
    public string $description;
    public ?object $medium = null;
    public array $tags = [];
    public array $links = [];

    public function __construct(object|array $item)
    {
        // Normalize item to an object (in case it was passed as an array)
        $item = (object) $item;

        // Assign main props with fallbacks
        $this->title = $item->title ?? '';
        $this->description = $item->description ?? '';
        $this->timestamp = isset($item->created_at) ? $this->parse_date($item->created_at) : '';

        // Initialize media
        $this->medium = isset($item->media[0]) ? $this->create_media_component($item->media[0]) : null;

        // Prepare item links & tags
        $links = $item->links ?? [];
        $this->links = array_map(fn($link) => new ParsedLink($link), $links);
        $this->tags = $this->assemble_tags($item);
    }
}

__halt_compiler();  
?>

<article class="item update hidden">
    <main class="update">
        <h4 class="item-header">
            <?= $this->title ?>
            <span class="timestamp">
                <?= __('main.timestamp', ['timestamp' => $this->timestamp]) ?>
            </span>
        </h4>
        <section class="item-description">
            <?= $this->description ?>
            <ul class="item-tags">
                <?php foreach ($this->tags as $tag): ?>
                    <?= $tag ?>
                <?php endforeach; ?>
            </ul>
        </section>
        <?php if ($this->medium): ?>
            <figure class="item-figure">
                <?= $this->medium ?>
            </figure>
        <?php endif; ?>
    </main>
    <?php if (!empty($this->links)): ?>
        <nav class="item-links">
            <?php foreach ($this->links as $link): ?>
                <?= $link ?>
            <?php endforeach; ?>
        </nav>
    <?php endif; ?>
</article>