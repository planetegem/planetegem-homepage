<!DOCTYPE html>
<html xmlns="https://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<?= $head ?>

<body>
    <?= (new Views\Background()) ?>
    <?= (new Views\Navigation()) ?>
    
    <main class="inventory">
        <?= $mainItem ?>
        <?php if (count($updates) > 0) : ?>
            <?= (new Views\Subtitle(__('main.inventory.item_update_subheader'))); ?>
            <?php foreach ($updates as $update) : ?>
                <?= $update ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>
    <?= (new Views\Footer()) ?>
</body>

</html>