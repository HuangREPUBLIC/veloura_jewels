<?php
/**
 * @var \App\View\AppView $this
 * @var string $first_name
 * @var string $nonce
 */
?>
Reset your Veloura Jewels password
===================================

Hi <?= h($first_name) ?>,

We received a request to reset your password. Click the link below to set a new one:

<?= $this->Url->build(['controller' => 'Auth', 'action' => 'resetPassword', $nonce], ['fullBase' => true]) ?>

This link expires in 7 days. If you didn't request this, you can safely ignore this email.

— Veloura Jewels
