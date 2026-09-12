<?php
/**
 * Plain-text email layout: the body, then one sign-off every text email shares.
 *
 * @var \App\View\AppView $this
 */
echo rtrim((string)$this->fetch('content'));
?>


—
Veloura Jewels · Fine Jewellery & Home Décor
© <?= date('Y') ?> Veloura Jewels
