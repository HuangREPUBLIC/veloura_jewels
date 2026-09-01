<?php
/**
 * "Showing N" per-page selector for admin data tables. Purely a query-param
 * control — CakePHP's paginator already reads `limit` from the query string
 * on every paginated action, so no controller changes are needed to support
 * it. webroot/js/admin-shell.js wires the change event.
 *
 * @var \App\View\AppView $this
 * @var array<int> $options
 */
$options = $options ?? [10, 25, 50];
$current = (int)$this->request->getQuery('limit', 10);
?>
<div class="per-page-select">
    <span>Showing</span>
    <span class="per-page-select__value"><?= (int)$current ?></span>
    <svg class="per-page-select__chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
    <select class="per-page-select__input" data-per-page-select aria-label="Results per page">
        <?php foreach ($options as $n) : ?>
            <option value="<?= (int)$n ?>" <?= $n === $current ? 'selected' : '' ?>><?= (int)$n ?></option>
        <?php endforeach; ?>
    </select>
</div>
