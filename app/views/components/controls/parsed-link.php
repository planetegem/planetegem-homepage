<?php

namespace Views;
use Core\SingleFileView;

class ParsedLink extends SingleFileView
{
    public string $url;
    public string $target;
    public string $anchor;

    public function __construct(object|array $link)
    {
        // Support both object and array inputs safely
        $this->url = is_array($link) ? ($link['url'] ?? '') : ($link->url ?? '');
        $this->anchor = is_array($link) ? ($link['anchor'] ?? '') : ($link->anchor ?? '');

        // Parse url: internal links target _self, external links open new tab
        $parsedUrl = parse_url($this->url);
        $this->target = empty($parsedUrl['host']) ? '_self' : '_blank';
    }
}

__halt_compiler();    
?>

<a class="item-link" href="<?= htmlspecialchars($this->url, ENT_QUOTES, 'UTF-8') ?>"
    target="<?= htmlspecialchars($this->target, ENT_QUOTES, 'UTF-8') ?>" <?= $this->target === '_blank' ? 'rel="noopener noreferrer"' : '' ?>>
    
    <?= file_get_contents('resources/icons/triangle.svg') ?>
    <span><?= htmlspecialchars($this->anchor, ENT_QUOTES, 'UTF-8') ?></span>
</a>