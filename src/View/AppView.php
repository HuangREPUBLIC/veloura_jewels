<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.0.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\View;

use Cake\View\View;

/**
 * Application View
 *
 * Your application's default view class
 *
 * @link https://book.cakephp.org/5/en/views.html#the-app-view
 * @extends \Cake\View\View<\App\View\AppView>
 */
class AppView extends View
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like adding helpers.
     *
     * e.g. `$this->addHelper('Html');`
     *
     * @return void
     */
    public function initialize(): void
    {
        $this->loadHelper('Authentication.Identity');
    }

    /**
     * Sidebar navigation map for the admin shell (templates/layout/admin.php).
     * `controller` + `matchActions` decide the active highlight (e.g. the
     * Products item stays highlighted on its view/add/edit pages, not just
     * index) — Dashboard and Users both live on the Users controller, so
     * each needs its own explicit action list rather than a controller-only
     * match. `icon` is a key into iconSvg(); `adminOnly` hides the item from
     * staff.
     *
     * @return array<string, array<int, array{label: string, controller: string, matchActions: array<int, string>, url: array<string, string>, icon: string, adminOnly: bool}>>
     */
    public function adminNavGroups(): array
    {
        return [
            'Overview' => [
                ['label' => 'Dashboard', 'controller' => 'Users', 'matchActions' => ['dashboard'], 'url' => ['controller' => 'Users', 'action' => 'dashboard'], 'icon' => 'grid', 'adminOnly' => false],
            ],
            'Store' => [
                ['label' => 'Products', 'controller' => 'Products', 'matchActions' => ['index', 'view', 'add', 'edit'], 'url' => ['controller' => 'Products', 'action' => 'index'], 'icon' => 'tag', 'adminOnly' => false],
                ['label' => 'Orders', 'controller' => 'Orders', 'matchActions' => ['index', 'view'], 'url' => ['controller' => 'Orders', 'action' => 'index'], 'icon' => 'bag', 'adminOnly' => false],
                ['label' => 'Contact Submissions', 'controller' => 'ContactSubmissions', 'matchActions' => ['index', 'view'], 'url' => ['controller' => 'ContactSubmissions', 'action' => 'index'], 'icon' => 'mail', 'adminOnly' => false],
            ],
            'Team' => [
                ['label' => 'Staff Schedule', 'controller' => 'Schedule', 'matchActions' => ['index', 'shifts'], 'url' => ['controller' => 'Schedule', 'action' => 'index'], 'icon' => 'calendar', 'adminOnly' => false],
                ['label' => 'Users', 'controller' => 'Users', 'matchActions' => ['index', 'view', 'edit'], 'url' => ['controller' => 'Users', 'action' => 'index'], 'icon' => 'user', 'adminOnly' => true],
            ],
            'Admin' => [
                ['label' => 'Content Management', 'controller' => 'Cms', 'matchActions' => ['index'], 'url' => ['controller' => 'Cms', 'action' => 'index'], 'icon' => 'book', 'adminOnly' => true],
                ['label' => 'Activity Log', 'controller' => 'ActivityLogs', 'matchActions' => ['index'], 'url' => ['controller' => 'ActivityLogs', 'action' => 'index'], 'icon' => 'clock', 'adminOnly' => true],
            ],
        ];
    }

    /**
     * Shared line-icon set (stroke, 24x24, 1.6 width) used by the admin
     * sidebar/topbar and the Dashboard stat cards. Most paths are lifted
     * straight from the old dashboard.php card icons so the look stays
     * consistent, just recolourable via currentColor now.
     *
     * @param string $name Icon key, e.g. 'grid', 'tag'.
     * @return string Raw inline SVG markup.
     */
    public function iconSvg(string $name): string
    {
        $icons = [
            'grid' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>',
            'tag' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>',
            'bag' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>',
            'mail' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>',
            'calendar' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="17" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>',
            'user' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',
            'book' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5z"/><path d="M8 7h8"/><path d="M8 11h6"/></svg>',
            'external-link' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>',
            'log-out' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>',
            'arrow-left' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>',
            'undo' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/></svg>',
            'archive' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="5" rx="1"/><path d="M4 9v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9"/><line x1="10" y1="13" x2="14" y2="13"/></svg>',
            'eye' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z"/><circle cx="12" cy="12" r="3"/></svg>',
            'edit' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>',
            'trash' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>',
            'search' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>',
            'clock' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><polyline points="12 7 12 12 15.5 14"/></svg>',
        ];

        return $icons[$name] ?? '';
    }
}
