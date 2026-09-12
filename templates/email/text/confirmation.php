<?php
/**
 * @var \App\View\AppView $this
 * @var string $firstName
 * @var string $userMessage
 */

echo implode("\n", [
    'WE RECEIVED YOUR MESSAGE',
    '',
    'Hi ' . $firstName . ',',
    '',
    'Thank you for contacting Veloura Jewels. We have received your message and',
    'will get back to you as soon as possible.',
    '',
    'YOUR MESSAGE',
    trim((string)$userMessage),
]);
