<?= $this->Html->css('faq') ?>

<div class="faq-page">
    <div class="faq-container">
        <h1 class="faq-title">Frequently Asked Questions</h1>

        <?php foreach ($faqs as $index => $faq): ?>
            <div class="faq-item">
                <button class="faq-question" type="button" onclick="toggleFaq(<?= $index ?>)">
                    <span><?= h($faq['question']) ?></span>
                    <span class="faq-icon" id="icon-<?= $index ?>">+</span>
                </button>

                <div class="faq-answer" id="answer-<?= $index ?>" style="display: none;">
                    <p><?= h($faq['answer']) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    function toggleFaq(index) {
        const answer = document.getElementById('answer-' + index);
        const icon = document.getElementById('icon-' + index);

        if (answer.style.display === 'none') {
            answer.style.display = 'block';
            icon.textContent = '−';
        } else {
            answer.style.display = 'none';
            icon.textContent = '+';
        }
    }
</script>
