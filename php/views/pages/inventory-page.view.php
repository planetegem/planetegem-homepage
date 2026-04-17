<!DOCTYPE html>
<html xmlns="https://www.w3.org/1999/xhtml" lang="nl" xml:lang="nl">
<?= $props['head']->Render() ?>

<body>
    <?= (new Background())->Render() ?>
    <?= (new Navigation())->Render() ?>
    <main class="inventory">
        <?php
        array_map(function ($item) {
            echo $item->Render();
        }, $props['items']);
        ?>
    </main>
    <?= (new Footer())->Render() ?>
</body>

</html>