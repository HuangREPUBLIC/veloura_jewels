<?php
/**
 * Admin shell top bar: sidebar toggle on the left, "View site" + sign-out
 * grouped on the right. The signed-in user's identity lives in the sidebar's
 * bottom profile block instead — no need to show it twice, and the page
 * itself already carries its own heading, so this bar doesn't repeat it.
 *
 * The toggle button shows a different icon per viewport (CSS-driven, same
 * button/JS either way): a panel icon on desktop, where it collapses the
 * always-visible sidebar; a burger icon on mobile, where it opens the
 * off-canvas drawer.
 *
 * @var \App\View\AppView $this
 */
?>
<header class="admin-topbar">
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

    <div class="admin-topbar__actions">
        <?= $this->Html->link(
            $this->iconSvg('external-link') . ' View site',
            '/',
            ['class' => 'admin-topbar__view-site', 'escape' => false],
        ) ?>
        <span class="admin-topbar__divider" aria-hidden="true"></span>
        <?= $this->Form->postLink(
            $this->iconSvg('log-out'),
            ['controller' => 'Auth', 'action' => 'logout'],
            [
                'class' => 'admin-topbar__signout',
                'escape' => false,
                'title' => 'Sign out',
                'aria-label' => 'Sign out',
            ],
        ) ?>
    </div>
</header>
