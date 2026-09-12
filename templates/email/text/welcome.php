<?php
/**
 * @var \App\View\AppView $this
 * @var string $first_name
 */

echo implode("\n", [
    'WELCOME TO VELOURA JEWELS',
    '',
    'Hi ' . $first_name . ',',
    '',
    'Your account is ready. Browse the collection here:',
    '',
    $this->Url->build('/jewelry', ['fullBase' => true]),
]);
