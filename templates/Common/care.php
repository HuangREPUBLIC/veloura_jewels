<?php
/**
 * Shared shell for the FAQ and policy pages. Sections written as
 * <section id="x"><h2>Title</h2> feed the in-page contents list.
 *
 * @var \App\View\AppView $this
 * @var array $footerLocation
 */
$this->Html->css('pages', ['block' => true]);

$content = $this->fetch('content');
preg_match_all('/<section id="([\w-]+)">\s*<h2>(.+?)<\/h2>/', $content, $sections, PREG_SET_ORDER);

$path = $this->request->getPath();
$links = [
    '/faq' => 'FAQ',
    '/shipping-policy' => 'Shipping',
    '/refund-policy' => 'Refunds',
    '/privacy-policy' => 'Privacy',
    '/terms-and-conditions' => 'Terms',
];
$phone = $footerLocation['phone'] ?? '';
?>
<div class="care">
    <header class="care-head">
        <p class="care-eyebrow">Client Care</p>
        <h1 class="care-title"><?= h($this->fetch('title')) ?></h1>
        <?php if ($this->fetch('intro')): ?>
            <p class="care-intro"><?= h($this->fetch('intro')) ?></p>
        <?php endif; ?>
        <?php if ($this->fetch('updated')): ?>
            <p class="care-updated">Last updated <?= h($this->fetch('updated')) ?></p>
        <?php endif; ?>
    </header>

    <nav class="care-nav" aria-label="Client care">
        <ul>
            <?php foreach ($links as $url => $label): ?>
                <li>
                    <a href="<?= $this->Url->build($url) ?>"<?= $url === $path ? ' aria-current="page"' : '' ?>><?= h($label) ?></a>
                    <?php if ($url === $path && $sections): ?>
                        <ol class="care-toc">
                            <?php foreach ($sections as [, $id, $heading]): ?>
                                <li><a href="#<?= $id ?>"><?= $heading ?></a></li>
                            <?php endforeach; ?>
                        </ol>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <div class="care-main prose">
        <?= $content ?>

        <aside class="care-help">
            <h2>Still have a question?</h2>
            <p>Our client care team replies within one business day.</p>
            <div class="care-help-actions">
                <?= $this->Html->link('Contact us', ['controller' => 'ContactSubmissions', 'action' => 'add'], ['class' => 'care-btn']) ?>
                <?php if ($phone): ?>
                    <a href="tel:<?= h(preg_replace('/[^\d+]/', '', $phone)) ?>">Call <?= h($phone) ?></a>
                <?php endif; ?>
            </div>
        </aside>
    </div>
</div>
