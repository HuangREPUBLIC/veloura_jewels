<?php $this->Html->css('faq', ['block' => true]); ?>

<div class="faq-page">
    <div class="faq-header">
        <h2><?= h($pageContent['faq_heading'] ?? 'Frequently Asked Questions') ?></h2>
        <p><?= h($pageContent['faq_subtext'] ?? '') ?></p>
    </div>

    <div class="faq-container">
        <?php foreach ($faqs as $index => $faq): ?>
            <div class="faq-item">
                <button class="faq-question" type="button" onclick="toggleFaq(<?= $index ?>)">
                    <span><?= h($faq->question) ?></span>
                    <span class="faq-icon" id="icon-<?= $index ?>">+</span>
                </button>

                <div class="faq-answer" id="answer-<?= $index ?>" style="display: none;">
                    <p><?= h($faq->answer) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    function toggleFaq(index) {
        const answer = document.getElementById('answer-' + index);
        const icon = document.getElementById('icon-' + index);

        if (answer.style.display === 'block') {
            answer.style.display = 'none';
            icon.textContent = '+';
        } else {
            answer.style.display = 'block';
            icon.textContent = '−';
        }
    }
</script>
