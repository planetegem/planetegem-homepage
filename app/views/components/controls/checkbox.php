<?php

namespace Views;
use Core\SingleFileView;

// Simple checkbox
class Checkbox extends SingleFileView
{
    public function __construct(
        public string $value,
        public string $name,
        public string $label,
        public bool $checked = false
    ) {
    }
}

__halt_compiler();  
?>

<label class="checkbox">
    <input type="checkbox" name="<?= htmlspecialchars($this->name, ENT_QUOTES, 'UTF-8') ?>"
        value="<?= htmlspecialchars($this->value, ENT_QUOTES, 'UTF-8') ?>"
        data-label="<?= htmlspecialchars($this->label, ENT_QUOTES, 'UTF-8') ?>" <?= $this->checked ? 'checked' : '' ?> />
    <?= htmlspecialchars($this->label, ENT_QUOTES, 'UTF-8') ?>
</label>