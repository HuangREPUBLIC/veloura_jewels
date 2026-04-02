<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Product> $products
 */

$this->assign('title', 'Jewelry');
echo $this->Html->css('jewelry');
?>

<div class="jewelry-page">
    <section class="jewelry-hero">
        <h1>Our Jewelry Collection</h1>
        <p>Discover timeless pieces crafted to elevate every occasion.</p>
    </section>

    <?php if ($products->isEmpty()): ?>
        <div class="empty-state">
            <p>No products are available at the moment.</p>
        </div>
    <?php else: ?>
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <div class="product-image-wrapper">
                        <?php if (!empty($product->product_images)): ?>
                            <img
                                src="<?= $this->Url->image(h($product->product_images[0]->filename)) ?>"
                                alt="<?= h($product->name) ?>"
                                class="product-image"
                            >
                        <?php else: ?>
                            <div class="product-placeholder">
                                <span>No Image</span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="product-card-body">
                        <h3 class="product-name"><?= h($product->name) ?></h3>
                        <p class="product-price">$<?= number_format((float)$product->sale_price, 2) ?></p>

                        <div class="product-actions">
                            <a href="<?= $this->Url->build('/jewelry/view/' . $product->id) ?>" class="btn-view">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
