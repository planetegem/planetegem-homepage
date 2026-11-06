<!DOCTYPE html>
<html xmlns="https://www.w3.org/1999/xhtml" lang="nl" xml:lang="nl">
<?= $head ?>

<body>
    <?= new Views\Background() ?>
    <?= new Views\Navigation() ?>

    <main class="inventory">
        <?php foreach ($items as $item): ?>
            <?= $item ?>
        <?php endforeach; ?>
    </main>

    <?= new Views\Footer() ?>
</body>

</html>