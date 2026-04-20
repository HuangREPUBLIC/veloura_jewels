<div class="container">
    <h1>Frequently Asked Questions</h1>

    <?php foreach ($faqs as $faq): ?>
        <div style="margin-bottom:20px;">
            <h3><?= h($faq['question']) ?></h3>
            <p><?= h($faq['answer']) ?></p>
        </div>
    <?php endforeach; ?>

</div>
