<!DOCTYPE html>
<html xmlns="https://www.w3.org/1999/xhtml" lang="en" xml:lang="en">

<?= $head ?>

<body class="static">
    <?= (new Views\Background()) ?>
    <?= (new Views\Navigation()) ?>

    <main class="error">
        <h2><?= $title ?></h2>
        <p><?= $description ?></p>
        <?= (new Views\ReturnButton())->Render() ?>
    </main>

</body>
</html>