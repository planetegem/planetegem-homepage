<!DOCTYPE html>
<html xmlns="https://www.w3.org/1999/xhtml" lang="nl" xml:lang="nl">
<?= $props['head']->Render() ?>

<body>
    <?= (new Views\Background())->Render() ?>
    <?= (new Views\Navigation())->Render() ?>
    
    <main class="inventory">
        <?= $props['mainItem']->Render() ?>
        <?php if (count($props['updates']) > 0) : ?>
            <?= (new Views\UpdateHeader())->Render(); ?>
            <?php foreach ($props['updates'] as $update) : ?>
                <?= $update->Render(); ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>
    <?= (new Views\Footer())->Render() ?>
</body>

</html>