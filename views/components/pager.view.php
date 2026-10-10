<?php
// Helper function to quickly get a current query url
function getPageUrl(int $page): string
{
    $queryParams = $_GET;
    $queryParams['page'] = $page;
    return '?' . http_build_query($queryParams);
}
?>

<nav class="pager hidden">
    <p class="pager-info">
        Showing
        <strong><?= min(($props['current_page'] - 1) * $props['per_page'] + 1, $props['count']) ?></strong>
        to
        <strong><?= min($props['current_page'] * $props['per_page'], $props['count']) ?></strong>
        of
        <strong><?= $props['count'] ?></strong> results.
    </p>
    <ul class="pager-links">
        <?php if ($props['current_page'] > 1): ?>
            <li>
                <a href="<?= getPageUrl($props['current_page'] - 1) ?>" class="pager-link">&laquo; Prev</a>
            </li>
        <?php endif; ?>

        <?php
        $startPage = max(1, $props['current_page'] - 2);
        $endPage = min($props['last_page'], $props['current_page'] + 2);

        if ($startPage > 1): ?>
            <li><a href="<?= getPageUrl(1) ?>" class="pager-link">1</a></li>
            <?php if ($startPage > 2): ?>
                <li><span class="pager-ellipsis">&hellip;</span></li>
            <?php endif; ?>
        <?php endif; ?>

        <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
            <li>
                <?php if ($i === $props['current_page']): ?>
                    <span class="pager-link active"><?= $i ?></span>
                <?php else: ?>
                    <a href="<?= getPageUrl($i) ?>" class="pager-link"><?= $i ?></a>
                <?php endif; ?>
            </li>
        <?php endfor; ?>

        <?php if ($endPage < $props['last_page']): ?>
            <?php if ($endPage < $props['last_page'] - 1): ?>
                <li><span class="pager-ellipsis">&hellip;</span></li>
            <?php endif; ?>
            <li><a href="<?= getPageUrl($props['last_page']) ?>" class="pager-link"><?= $props['last_page'] ?></a></li>
        <?php endif; ?>

        <?php if ($props['current_page'] < $props['last_page']) : ?>
            <li>
                <a href="<?= getPageUrl($props['current_page'] + 1) ?>" class="pager-link" >Next &raquo;</a>
            </li>
        <?php endif; ?>
    </ul>
</nav>