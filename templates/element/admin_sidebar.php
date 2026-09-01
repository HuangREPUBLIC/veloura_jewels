<?php
/**
 * Admin shell sidebar. Light theme: brand mark at the top, then nav groups
 * from AppView::adminNavGroups() (items flagged 'adminOnly' are hidden from
 * staff). The account/profile block lives in the topbar instead - see
 * templates/element/admin_topbar.php.
 *
 * @var \App\View\AppView $this
 */
$identity = $this->request->getAttribute('identity');
$role = $identity ? $identity->get('role') : null;
$groups = $this->adminNavGroups();
$currentController = $this->request->getParam('controller');
$currentAction = $this->request->getParam('action');
?>
<aside class="admin-sidebar" id="adminSidebar">
    <div class="admin-sidebar__brand">
        <?= $this->Html->link(
            $this->Html->image('logo.png', ['alt' => 'Veloura Jewels', 'class' => 'admin-sidebar__logo'])
            . '<span class="admin-sidebar__brand-text">Veloura Jewels</span>',
            ['controller' => 'Users', 'action' => 'dashboard'],
            ['escape' => false],
        ) ?>
    </div>

    <nav class="admin-sidebar__nav" aria-label="Admin">
        <?php foreach ($groups as $groupLabel => $items): ?>
            <?php $visibleItems = array_filter($items, fn($item) => !$item['adminOnly'] || $role === 'admin'); ?>
            <?php if (empty($visibleItems)): ?>
                <?php continue; ?>
            <?php endif; ?>
            <div class="admin-sidebar__group">
                <span class="admin-sidebar__group-label"><?= h($groupLabel) ?></span>
                <?php foreach ($visibleItems as $item): ?>
                    <?php $isActive = $item['controller'] === $currentController && in_array($currentAction, $item['matchActions'], true); ?>
                    <?= $this->Html->link(
                        '<span class="admin-sidebar__icon" aria-hidden="true">' . $this->iconSvg($item['icon']) . '</span>'
                        . '<span>' . h($item['label']) . '</span>',
                        $item['url'],
                        [
                            'class' => 'admin-sidebar__link' . ($isActive ? ' active' : ''),
                            'aria-current' => $isActive ? 'page' : null,
                            'escape' => false,
                        ],
                    ) ?>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </nav>
</aside>
<div class="admin-shell__scrim" id="adminSidebarScrim"></div>
