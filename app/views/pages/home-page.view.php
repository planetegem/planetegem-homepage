<!DOCTYPE html>
<html xmlns="https://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<?= (new Views\PageHead()) ?>

<body>
    <?= (new Views\Background()) ?>
    <?= (new Views\Navigation()) ?>
    <main class="inventory">
        <?php foreach ($items as $item): ?>
            <?= $item ?>
        <?php endforeach; ?>
        <?= $pager ?>
    </main>
    <?= (new Views\Footer()) ?>
</body>

</html>