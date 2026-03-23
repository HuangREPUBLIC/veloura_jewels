<?php
/**
 * Reset Password text email template
 *
 * @var \App\View\AppView $this
 * @var string $first_name email recipient's first name
 * @var string $last_name email recipient's last name
 * @var string $email email recipient's email address
 * @var string $nonce nonce used to reset the password
 */
?>
Reset your account password
==========

Hi <?= h($first_name) ?>,

Thank you for your request to reset the password of your account on Cake CMS/Auth Sample.

To reset your account password, use the button below to access the reset password page:
<?= $this->Url->build(['controller' => 'Users', 'action' => 'resetPassword', $nonce], ['fullBase' => true]) ?>


==========
This email is addressed to <?= $first_name ?>  <?= $last_name ?> <<?= $email ?>>
Please discard this email if it not meant for you

Copyright (c) <?= date("Y"); ?> Monash FIT Industry Experience
