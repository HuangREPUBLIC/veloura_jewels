<?php
/**
 * @var \App\View\AppView $this
 * @var string $message
 * @var string $url
 */
use Cake\Core\Configure;

$this->setLayout('default');

if (Configure::read('debug')) :
    $this->setLayout('dev_error');
    $this->assign('title', $message);
    $this->assign('templateName', 'error400.php');
    $this->start('file');
    echo $this->element('auto_table_warning');
    $this->end();
endif;

$this->assign('title', 'Page Not Found');
?>

<div class="error-page">
    <p class="error-page__code">404</p>
    <p class="error-page__title">Page Not Found</p>
    <div class="error-page__divider"></div>
    <p class="error-page__message">
        We could not find the page you were looking for.<br>
        Please use the navigation or the button below to go back to our website.
    </p>
    <?= $this->Html->link('Back to Homepage', '/', ['class' => 'error-page__btn']) ?>
</div>
