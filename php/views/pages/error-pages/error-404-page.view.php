<!DOCTYPE html>
<html xmlns="https://www.w3.org/1999/xhtml" lang="nl" xml:lang="nl">

<?= $props['head']->Render() ?>

<body class="static">
    <?= (new Background())->Render() ?>
    <?= (new Navigation())->Render() ?>

    <main class="error hidden">
        <h2>404 - Page not found</h2>
        <p>The page you're looking for has gone missing or never existed in the first place.</p>
        <?= (new ReturnButton())->Render() ?>
    </main>

</body>

</html>