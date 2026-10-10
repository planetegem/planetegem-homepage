<expandable-thumbnail>
    <template shadowrootmode="open"> 
        <img class="thumbnail" data-src="<?= $props['src'] ?>" alt="<?= $props['alt'] ?>" data-priority="high" />
        <dialog class="enlarged-dialog">
            <form method="dialog">
                <button type="submit">X</button>
            </form>
            <figure>
                <img class="placeholder" data-src="<?= $props['src'] ?>" alt="<?= $props['alt'] ?>" />
                <img class="large-image" data-src="<?= $props['src'] ?>" alt="<?= $props['alt'] ?>" />
            </figure>
        <dialog>
    </template>
</expandable-thumbnail>