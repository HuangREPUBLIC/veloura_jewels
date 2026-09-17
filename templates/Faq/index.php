<?php
$this->Html->css('faq', ['block' => true]);
$this->assign('title', 'FAQ');
?>

<div class="faq-page">
    <div class="faq-header">
        <h1><?= h($pageContent['faq_heading'] ?? 'Frequently Asked Questions') ?></h1>
        <p><?= h($pageContent['faq_subtext'] ?? '') ?></p>
    </div>

    <div class="faq-container">
        <?php foreach ($faqs as $index => $faq): ?>
            <div class="faq-item">
                <button class="faq-question" type="button" id="question-<?= $index ?>" aria-expanded="false" aria-controls="answer-<?= $index ?>">
                    <span><?= h($faq->question) ?></span>
                    <span class="faq-icon" aria-hidden="true">+</span>
                </button>

                <div class="faq-answer" id="answer-<?= $index ?>" role="region" aria-labelledby="question-<?= $index ?>" hidden>
                    <p><?= h($faq->answer) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    document.querySelectorAll('.faq-question').forEach(function (button) {
        button.addEventListener('click', function () {
            const expanded = button.getAttribute('aria-expanded') === 'true';
            button.setAttribute('aria-expanded', String(!expanded));
            document.getElementById(button.getAttribute('aria-controls')).hidden = expanded;
        });
    });
</script>
