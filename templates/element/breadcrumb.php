<?php
/**
 * Slim wayfinding trail for the public storefront (Home > Jewellery > Category > Product).
 * Not used in admin - the admin shell uses .back-link instead.
 *
 * @var \App\View\AppView $this
 * @var array<int, array{label: string, url?: mixed}> $crumbs Ordered list, root first.
 *      Omit 'url' (or leave it falsy) on the final/current item.
 */
$last = count($crumbs) - 1;
?>
<nav class="crumb-trail" aria-label="Breadcrumb">
    <?php foreach ($crumbs as $i => $crumb): ?>
        <?php if ($i > 0): ?><span class="crumb-sep" aria-hidden="true">/</span><?php endif; ?>
        <?php if (!empty($crumb['url']) && $i !== $last): ?>
            <?= $this->Html->link(h($crumb['label']), $crumb['url'], ['class' => 'crumb-link']) ?>
        <?php else: ?>
            <span class="crumb-current" aria-current="page"><?= h($crumb['label']) ?></span>
        <?php endif; ?>
    <?php endforeach; ?>
</nav>
