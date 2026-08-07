<!DOCTYPE html>
<html xmlns="https://www.w3.org/1999/xhtml" lang="nl" xml:lang="nl">

<?= $props['head']->Render() ?>

<body class="static">
    <?= (new Background())->Render() ?>
    <?= (new Navigation())->Render() ?>

    <main class="error hidden">
        <h2>No results</h2>
        <p>Planetegem is vast, but apparently not vast enough for you! Your combination of filters has yielded 0 results.</p>
        <?= (new ReturnButton())->Render() ?>
    </main>

</body>

</html>