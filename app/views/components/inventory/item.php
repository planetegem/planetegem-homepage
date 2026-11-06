<?php

namespace Views;
use Core\SingleFileView;
use Traits\HasMedia;
use Traits\HasTimestamps;
use Traits\HasTags;


class Item extends SingleFileView
{
    use HasTimestamps;
    use HasMedia;
    use HasTags;

    public string $title;
    public string $timestamp;
    public string $description;
    public ?object $medium = null;
    public array $tags = [];
    public array $links = [];
    public ?object $relationship = null;
    public string $type;

    public function __construct(object|array $item, string $type = 'lister')
    {
        // Normalize item to an object (in case it was passed as an array)
        $item = (object) $item;

        // Assign main props with fallbacks
        $this->type = $type;
        $this->title = $item->title ?? '';
        $this->description = $item->description ?? '';
        $this->timestamp = isset($item->created_at) ? $this->parse_date($item->created_at) : '';

        // Initialize media
        $this->medium = isset($item->media[0]) ? $this->create_media_component($item->media[0]) : null;

        // Prepare item links & tags
        $links = $item->links ?? [];
        $this->links = array_map(fn($link) => new ParsedLink($link), $links);
        $this->tags = $this->assemble_tags($item);

        // If part of a lister, generate the link to a detail / main item page
        if ($type == "lister") {
            $relationships = $item->relationships;
            if ($relationships && !empty($relationships)) {
                foreach ($relationships as $relationship) {
                    if ($relationship->relationship->label == "child_of") {
                        $relationshipLink = new SimpleLink("view all related items", "/items/{$relationship->item->slug}");
                    }
                    if ($relationship->relationship->label == "parent_of") {
                        $relationshipLink = new SimpleLink("view all related items", "/items/{$item->slug}");
                        break;
                    }
                }
                $this->relationship = $relationshipLink;
            }
        }
    }
}

__halt_compiler();     
?>
<article class="item hidden">
    <header class="item-header-container clipped-header">
        <h2 class="item-header clipped-header">
            <?= $this->title ?>
            <span class="timestamp">
                <?= __('main.timestamp', ['timestamp' => $this->timestamp]) ?>
            </span>
        </h2>
    </header>
    <div class="item-body-container">
        <main class="item-body">
            <section class="item-description">
                <?= $this->description ?>
                <?= $this->relationship ? $this->relationship : '' ?>
            </section>
            <?php if ($this->medium): ?>
                <figure class="item-figure">
                    <?= $this->medium ?>
                </figure>
            <?php endif; ?>
            <?php if (!empty($this->links)): ?>
                <nav class="item-links">
                    <?php foreach ($this->links as $link): ?>
                        <?= $link ?>
                    <?php endforeach; ?>
                </nav>
            <?php endif; ?>
            <?php if (!empty($this->tags)): ?>
                <ul class="item-tags">
                    <?php foreach ($this->tags as $tag): ?>
                        <?= $tag ?>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </main>
    </div>
</article>