<?php
/**
 * @var \App\View\AppView $this
 * @var string $first_name
 * @var string $nonce
 */

$resetUrl = $this->Url->build(
    ['controller' => 'Auth', 'action' => 'resetPassword', $nonce],
    ['fullBase' => true],
);

echo implode("\n", [
    'RESET YOUR PASSWORD',
    '',
    'Hi ' . $first_name . ',',
    '',
    'We received a request to set a new password on your Veloura Jewels account.',
    'Open the link below to choose one. It works for 7 days.',
    '',
    $resetUrl,
    '',
    'Did not request this? You can safely ignore this email. Your password stays',
    'as it is until the link above is used.',
]);
