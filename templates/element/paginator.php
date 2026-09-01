<?php
/**
 * Shared pagination control: previous / page numbers / next, with the
 * record counter as a caption underneath.
 *
 * @var \App\View\AppView $this
 * @var string $prevLabel Label for the previous-page control.
 * @var string $nextLabel Label for the next-page control.
 */
$prevLabel = $prevLabel ?? 'Previous';
$nextLabel = $nextLabel ?? 'Next';

$params = $this->Paginator->params();
$pageCount = (int)($params['pageCount'] ?? 1);
$recordCount = (int)($params['count'] ?? 0);

if ($recordCount === 0) {
    return;
}
?>
<nav class="paginator<?= $pageCount <= 1 ? ' paginator--single' : '' ?>" aria-label="Pagination">
    <ul class="pagination">
        <?= $this->Paginator->prev('&larr; ' . h($prevLabel), ['escape' => false]) ?>
        <?= $this->Paginator->numbers(['modulus' => 4, 'first' => 1, 'last' => 1]) ?>
        <?= $this->Paginator->next(h($nextLabel) . ' &rarr;', ['escape' => false]) ?>
    </ul>

    <span class="paginator__counter">
        <?= $this->Paginator->counter('Showing {{start}}-{{end}} of {{count}}') ?>
    </span>
</nav>
