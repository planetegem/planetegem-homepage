<!DOCTYPE html>
<html xmlns="https://www.w3.org/1999/xhtml" lang="nl" xml:lang="nl">
<?= (new Views\PageHead())->Render() ?>

<body>
    <?= (new Views\Background())->Render() ?>
    <?= (new Views\Navigation())->Render() ?>
    <main class="inventory">
        <?php
        array_map(function ($item) {
            echo $item->Render();
        }, $props['items']);
        ?>
        <?= $props['pager']->Render() ?>
    </main>
    <?= (new Views\Footer())->Render() ?>
</body>

</html>