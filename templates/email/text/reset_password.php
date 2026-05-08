Reset Your Password — Veloura Jewels
=====================================

Hi <?= h($first_name) ?>,

We received a request to reset the password for your Veloura Jewels account.

Click the link below to reset your password (valid for 7 days):

<?= $this->Url->build(['controller' => 'Auth', 'action' => 'resetPassword', $nonce], ['fullBase' => true]) ?>

If you didn't request a password reset, you can safely ignore this email.

— Veloura Jewels
