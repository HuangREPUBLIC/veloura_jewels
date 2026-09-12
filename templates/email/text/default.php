<?php
/**
 * Generic plain-text email body. See email/html/default.php for why $content is
 * read before the block.
 *
 * @var \App\View\AppView $this
 * @var string|null $content
 */
$body = (string)($content ?? '');
if (trim($body) === '') {
    $body = (string)$this->fetch('content');
}
echo $body;
