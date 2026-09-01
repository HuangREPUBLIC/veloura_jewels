<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Product> $products
 * @var string $q
 */
$this->assign('title', 'Products');
$role = $this->Identity->get('role');
?>

<div class="page-header-row">
    <div>
        <p class="cms-eyebrow">Store</p>
        <h2 class="page-title">Products</h2>
    </div>
    <div class="cms-header-right">
        <?= $this->element('per_page_select') ?>
        <?php if ($role === 'admin'): ?>
            <?= $this->Html->link(__('New product'), ['action' => 'add'], ['class' => 'btn-new-product btn-new-product--add']) ?>
        <?php endif; ?>
    </div>
</div>

<?= $this->Form->create(null, ['type' => 'get', 'class' => 'admin-search']) ?>
    <input type="text" name="q" value="<?= h($q) ?>" placeholder="Search products by name" class="admin-search__input">
    <button type="submit" class="btn-sm"><?= $this->iconSvg('search') ?></button>
<?= $this->Form->end() ?>

<div class="table-wrap">
    <table class="data-table">
        <thead>
            <tr>
                <th>Featured</th>
                <th><?= $this->Paginator->sort('name') ?></th>
                <th><?= $this->Paginator->sort('purchase_price', 'Purchase Price') ?></th>
                <th><?= $this->Paginator->sort('sale_price', 'Sale Price') ?></th>
                <th>Size & Stock</th>
                <th>Category</th>
                <th>Supplier Email</th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td>
                        <?php if ($role === 'admin'): ?>
                            <button
                                class="featured-toggle <?= !empty($product->featured) ? 'is-featured' : '' ?>"
                                data-url="<?= $this->Url->build(['action' => 'toggleFeatured', $product->id]) ?>"
                                title="Toggle featured"
                            >★</button>
                        <?php else: ?>
                            <?= !empty($product->featured) ? '★' : '☆' ?>
                        <?php endif; ?>
                    </td>
                    <td class="product-cell">
                        <span class="product-name"><?= h($product->name) ?></span>
                    </td>
                    <td><?= $this->Number->format($product->purchase_price) ?></td>
                    <td><?= $this->Number->format($product->sale_price) ?></td>
                    <td>
                        <?php
                        $lowVariants = [];
                        foreach ($product->product_variants ?? [] as $v) {
                            if ($v->stock < 5) {
                                $lowVariants[] = h($v->size) . ': ' . $v->stock;
                            }
                        }
                        echo $lowVariants ? '<span class="stock-low">' . implode(', ', $lowVariants) . '</span>' : '✓';
                        ?>
                    </td>
                    <td>
                        <?php if ($product->category): ?>
                            <span class="admin-category-text"><?= h($product->category->name) ?></span>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td><?= h($product->supplier_email) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(
                            $this->iconSvg('eye'),
                            ['action' => 'view', $product->id],
                            ['escape' => false, 'aria-label' => __('View')],
                        ) ?>
                        <?php if ($role === 'admin'): ?>
                            <?= $this->Html->link(
                                $this->iconSvg('edit'),
                                ['action' => 'edit', $product->id],
                                ['escape' => false, 'aria-label' => __('Edit')],
                            ) ?>
                            <button
                                type="button"
                                class="btn-sm btn-sm--danger"
                                aria-label="<?= h(__('Delete')) ?>"
                                data-confirm-delete
                                data-delete-url="<?= h($this->Url->build(['action' => 'delete', $product->id])) ?>"
                                data-confirm-title="<?= h(__('Delete {0}?', $product->name)) ?>"
                                data-confirm-body="<?= h(__('This cannot be undone.')) ?>"
                            ><?= $this->iconSvg('trash') ?></button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= $this->element('paginator') ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var csrfToken = document.body.dataset.csrfToken;
    document.querySelectorAll('.featured-toggle').forEach(function (btn) {
        btn.addEventListener('click', function () {
            fetch(btn.dataset.url, {
                method: 'POST',
                headers: { 'X-CSRF-Token': csrfToken },
                body: new URLSearchParams({ _csrfToken: csrfToken }),
            })
                .then(function (res) { return res.json(); })
                .then(function (data) { btn.classList.toggle('is-featured', data.featured); });
        });
    });
});
</script>
