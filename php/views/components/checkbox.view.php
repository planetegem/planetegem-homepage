<label class="checkbox">
    <input 
        type="checkbox" name="<?= $props['name'] ?>" 
        value="<?= $props['value'] ?>" data-label="<?= $props['label'] ?>"
        <?= ($props['checked'] ? 'checked' : '') ?> />
    <?= $props['label'] ?>
</label>