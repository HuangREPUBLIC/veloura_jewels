<?php
/**
 * Admin shell layout: sidebar + topbar, used only by the /dashboard page
 * (UsersController::dashboard() sets this via setLayout('admin')). Other
 * admin pages (Products, Orders, Users...) still use the public
 * templates/layout/default.php with the admin-wrapper card style.
 *
 * @var \App\View\AppView $this
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Veloura Jewels: <?= $this->fetch('title') ?></title>
    <?= $this->Html->meta('icon', '/img/' . ($siteSettings['icon_image'] ?? 'icon.png')) ?>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <?= $this->Html->css(['normalize.min', 'fonts', 'tokens', 'base', 'components', 'admincontact']) ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body class="admin-body" data-csrf-token="<?= h($this->request->getAttribute('csrfToken')) ?>">
    <div class="admin-shell">
        <?= $this->element('admin_sidebar') ?>

        <div class="admin-shell__main">
            <?= $this->element('admin_topbar') ?>

            <div class="admin-shell__main-content">
                <div class="admin-shell__flash">
                    <?= $this->Flash->render() ?>
                </div>

                <main class="admin-shell__content">
                    <?= $this->fetch('content') ?>
                </main>
            </div>
        </div>
    </div>

    <?= $this->Html->script('admin-shell') ?>
</body>
</html>
