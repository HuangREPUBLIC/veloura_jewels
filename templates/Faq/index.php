<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\FaqItem> $faqs
 * @var array $pageContent
 */
$this->extend('/Common/care');
$this->assign('title', $pageContent['faq_heading'] ?? 'Frequently Asked Questions');
$this->assign('intro', $pageContent['faq_subtext'] ?? '');
?>
<div class="care-faq">
    <?php foreach ($faqs as $faq): ?>
        <details>
            <summary><?= h($faq->question) ?></summary>
            <p><?= h($faq->answer) ?></p>
        </details>
    <?php endforeach; ?>
</div>
