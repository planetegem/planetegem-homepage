<?php

namespace Views;
use Core\SingleFileView;

class Pager extends SingleFileView
{
    public function __construct(
        public int $count,
        public int $current_page,
        public int $last_page,
        public int $per_page
    ) {
    }

    // Helper function to quickly get a current query url
    public function getPageUrl(int $page): string
    {
        $queryParams = $_GET;
        $queryParams['page'] = $page;
        return '?' . http_build_query($queryParams);
    }
}

__halt_compiler();  
?>

<nav class="pager floater hidden">
    <p class="pager-info">
        <?= __('main.inventory.pager_recap', [
            'start' => $this->count > 0 ? min(($this->current_page - 1) * $this->per_page + 1, $this->count) : 0,
            'end' => min($this->current_page * $this->per_page, $this->count),
            'total' => $this->count
        ]) ?>
    </p>
    <ul class="pager-links">
        <?php if ($this->current_page > 1): ?>
            <li>
                <a href="<?= htmlspecialchars($this->getPageUrl($this->current_page - 1), ENT_QUOTES, 'UTF-8') ?>"
                    class="pager-link">&laquo; Prev</a>
            </li>
        <?php endif; ?>

        <?php
        $startPage = max(1, $this->current_page - 2);
        $endPage = min($this->last_page, $this->current_page + 2);

        if ($startPage > 1): ?>
            <li><a href="<?= htmlspecialchars($this->getPageUrl(1), ENT_QUOTES, 'UTF-8') ?>" class="pager-link">1</a></li>
            <?php if ($startPage > 2): ?>
                <li><span class="pager-ellipsis">&hellip;</span></li>
            <?php endif; ?>
        <?php endif; ?>

        <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
            <li>
                <?php if ($i === $this->current_page): ?>
                    <span class="pager-link active"><?= $i ?></span>
                <?php else: ?>
                    <a href="<?= htmlspecialchars($this->getPageUrl($i), ENT_QUOTES, 'UTF-8') ?>"
                        class="pager-link"><?= $i ?></a>
                <?php endif; ?>
            </li>
        <?php endfor; ?>

        <?php if ($endPage < $this->last_page): ?>
            <?php if ($endPage < $this->last_page - 1): ?>
                <li><span class="pager-ellipsis">&hellip;</span></li>
            <?php endif; ?>
            <li><a href="<?= htmlspecialchars($this->getPageUrl($this->last_page), ENT_QUOTES, 'UTF-8') ?>"
                    class="pager-link"><?= $this->last_page ?></a></li>
        <?php endif; ?>

        <?php if ($this->current_page < $this->last_page): ?>
            <li>
                <a href="<?= htmlspecialchars($this->getPageUrl($this->current_page + 1), ENT_QUOTES, 'UTF-8') ?>"
                    class="pager-link">Next &raquo;</a>
            </li>
        <?php endif; ?>
    </ul>
</nav>