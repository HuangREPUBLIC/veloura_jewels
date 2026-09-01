<?php
/**
 * Admin shell top bar: sidebar toggle + section breadcrumb on the left,
 * "View site" + sign-out grouped on the right. The signed-in user's identity
 * lives in the sidebar's top profile block instead — no need to show it
 * twice. The breadcrumb is deliberately secondary (small, muted) since the
 * page itself already carries its own heading below.
 *
 * The toggle button shows a different icon per viewport (CSS-driven, same
 * button/JS either way): a panel icon on desktop, where it collapses the
 * always-visible sidebar; a burger icon on mobile, where it opens the
 * off-canvas drawer.
 *
 * @var \App\View\AppView $this
 */
$identity = $this->request->getAttribute('identity');
$firstName = $identity ? (string)$identity->get('first_name') : '';
$lastName = $identity ? (string)$identity->get('last_name') : '';
$userName = trim($firstName . ' ' . $lastName);
$userInitials = mb_strtoupper(mb_substr($firstName, 0, 1) . mb_substr($lastName, 0, 1));
$userRole = $identity ? (string)$identity->get('role') : '';

$groups = $this->adminNavGroups();
$currentController = $this->request->getParam('controller');
$currentAction = $this->request->getParam('action');
$crumbGroup = null;
$crumbItem = null;
foreach ($groups as $groupLabel => $items) {
    foreach ($items as $item) {
        if ($item['controller'] === $currentController && in_array($currentAction, $item['matchActions'], true)) {
            $crumbGroup = $groupLabel;
            $crumbItem = $item['label'];
            break 2;
        }
    }
}
if ($crumbItem === null) {
    foreach ($groups as $groupLabel => $items) {
        foreach ($items as $item) {
            if ($item['controller'] === $currentController) {
                $crumbGroup = $groupLabel;
                $crumbItem = $item['label'];
                break 2;
            }
        }
    }
}
?>
<header class="admin-topbar">
    <div class="admin-topbar__left">
        <button
            type="button"
            class="admin-shell__menu-toggle"
            id="adminSidebarToggle"
            aria-label="Toggle sidebar"
            aria-controls="adminSidebar"
            aria-expanded="false"
        >
            <svg class="admin-shell__menu-icon admin-shell__menu-icon--panel" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="2"/><line x1="9" y1="4" x2="9" y2="20"/></svg>
            <svg class="admin-shell__menu-icon admin-shell__menu-icon--burger" width="20" height="16" viewBox="0 0 22 16" fill="none"><path d="M0 1H22M0 8H22M0 15H22" stroke="currentColor" stroke-width="1.5"/></svg>
        </button>

        <?php $crumbRecord = $this->fetch('crumbRecord'); ?>
        <?php if ($crumbItem !== null): ?>
            <nav class="admin-topbar__crumb" aria-label="Section">
                <span class="admin-topbar__crumb-group"><?= h($crumbGroup) ?></span>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="9 18 15 12 9 6"/></svg>
                <?php if ($crumbRecord !== ''): ?>
                    <?= $this->Html->link($crumbItem, $groups[$crumbGroup][array_search($crumbItem, array_column($groups[$crumbGroup], 'label'), true)]['url'] ?? '#', ['class' => 'admin-topbar__crumb-link', 'escape' => false]) ?>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="9 18 15 12 9 6"/></svg>
                    <span class="admin-topbar__crumb-current"><?= $crumbRecord ?></span>
                <?php else: ?>
                    <span class="admin-topbar__crumb-current"><?= h($crumbItem) ?></span>
                <?php endif; ?>
            </nav>
        <?php endif; ?>
    </div>

    <div class="admin-topbar__actions">
        <?= $this->Html->link(
            $this->iconSvg('external-link') . ' View site',
            '/',
            ['class' => 'admin-topbar__view-site', 'escape' => false],
        ) ?>
        <span class="admin-topbar__divider" aria-hidden="true"></span>
        <div class="admin-topbar__profile-menu">
            <button
                type="button"
                class="admin-topbar__profile"
                data-profile-trigger
                aria-haspopup="true"
                aria-expanded="false"
                title="Account"
            >
                <span class="admin-topbar__avatar" aria-hidden="true"><?= h($userInitials) ?></span>
                <span class="admin-topbar__profile-info">
                    <span class="admin-topbar__profile-name"><?= h($userName) ?></span>
                    <span class="admin-topbar__profile-role"><?= h(ucfirst($userRole)) ?></span>
                </span>
                <svg class="admin-topbar__profile-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <div class="admin-topbar__profile-dropdown" data-profile-menu hidden>
                <?= $this->Html->link('My Profile', ['controller' => 'Profile', 'action' => 'index'], ['class' => 'admin-topbar__profile-dropdown-item']) ?>
                <?= $this->Form->postLink(
                    'Sign out',
                    ['controller' => 'Auth', 'action' => 'logout'],
                    ['class' => 'admin-topbar__profile-dropdown-item admin-topbar__profile-dropdown-item--danger'],
                ) ?>
            </div>
        </div>
    </div>
</header>
