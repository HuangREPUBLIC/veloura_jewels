<?php
/**
 * Admin shell sidebar. Light theme: account block at the top, then the
 * brand mark, then nav groups from AppView::adminNavGroups() (items flagged
 * 'adminOnly' are hidden from staff).
 *
 * @var \App\View\AppView $this
 */
$identity = $this->request->getAttribute('identity');
$role = $identity ? $identity->get('role') : null;
$firstName = $identity ? (string)$identity->get('first_name') : '';
$lastName = $identity ? (string)$identity->get('last_name') : '';
$userName = trim($firstName . ' ' . $lastName);
$userInitials = mb_strtoupper(mb_substr($firstName, 0, 1) . mb_substr($lastName, 0, 1));
$groups = $this->adminNavGroups();
$currentController = $this->request->getParam('controller');
$currentAction = $this->request->getParam('action');
?>
<aside class="admin-sidebar" id="adminSidebar">
    <?= $this->Html->link(
        '<span class="admin-sidebar__avatar" aria-hidden="true">' . h($userInitials) . '</span>'
        . '<span class="admin-sidebar__profile-info">'
        . '<span class="admin-sidebar__profile-name">' . h($userName) . '</span>'
        . '<span class="admin-sidebar__profile-role">' . h(ucfirst((string)$role)) . '</span>'
        . '</span>'
        . '<svg class="admin-sidebar__profile-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="9 18 15 12 9 6"/></svg>',
        ['controller' => 'Profile', 'action' => 'index'],
        ['class' => 'admin-sidebar__profile', 'escape' => false, 'title' => 'My profile'],
    ) ?>

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
