<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Product $product
 */
$this->assign('title', 'View Product');

function catPillClass(string $name): string {
    $map = [
        'rings'     => 'rings',
        'necklaces' => 'necklaces',
        'earrings'  => 'earrings',
        'bracelets' => 'bracelets',
        'brooches'  => 'brooches',
        'candles'   => 'candles',
        'vases'     => 'vases',
        'cushions'  => 'cushions',
        'wall art'  => 'wall-art',
        'throws'    => 'throws',
    ];
    return 'cat-pill-' . ($map[strtolower($name)] ?? 'default');
}

$productType = '';
if (!empty($product->categories)) {
    $productType = $product->categories[0]->type ?? '';
}
?>
<?php $this->Html->css('admincontact', ['block' => true]); ?>
<?php $this->Html->css('login', ['block' => true]); ?>

<div class="admin-wrapper">
    <div class="products view content">
        <h3><?= h($product->name) ?></h3>

        <div class="action-buttons">
            <?= $this->Html->link(__('← Back'), ['action' => 'index']) ?>

            <?php $role = $this->request->getAttribute('identity')->get('role'); ?>

            <?php if ($role === 'admin'): ?>
                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $product->id]) ?>
            <?php endif; ?>

            <?php if ($role === 'admin'): ?>
                <?= $this->Form->postLink(
                    __('Delete'),
                    ['action' => 'delete', $product->id],
                    [
                        'confirm' => __('Are you sure you want to delete # {0}?', $product->id),
                        'class'   => 'btn-delete',
                    ]
                ) ?>
            <?php endif; ?>
        </div>

        <table class="view-table">

            <tr>
                <th><?= __('Name') ?></th>
                <td><?= h($product->name) ?></td>
            </tr>
            <tr>
                <th><?= __('Type') ?></th>
                <td>
                    <?= $productType !== '' ? h(ucwords(str_replace('_', ' ', $productType))) : '-' ?>
                </td>
            </tr>
            <tr>
                <th><?= __('Categories') ?></th>
                <td>
                    <?php
                    if (!empty($product->categories)) {
                        echo implode(', ', collection($product->categories)->extract('name')->toList());
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <th><?= __('Supplier Email') ?></th>
                <td><?= h($product->supplier_email) ?></td>
            </tr>
            <tr>
                <th><?= __('Purchase Price') ?></th>
                <td><?= $this->Number->format($product->purchase_price) ?></td>
            </tr>
            <tr>
                <th><?= __('Sale Price') ?></th>
                <td><?= $this->Number->format($product->sale_price) ?></td>
            </tr>
            <tr>
                <th><?= __('Size & Stock') ?></th>
                <td>
                    <?php if (!empty($product->product_variants)): ?>
                        <?php foreach ($product->product_variants as $variant): ?>
                            <div>
                                <?= h($variant->size) ?>:
                                <?php if ($variant->stock < 5): ?>
                                    <span class="stock-low"><?= $variant->stock ?></span>
                                <?php else: ?>
                                    <?= $variant->stock ?>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th><?= __('Created') ?></th>
                <td><?= h($product->created) ?></td>
            </tr>
            <tr>
                <th><?= __('Modified') ?></th>
                <td><?= h($product->modified) ?></td>
            </tr>
            <tr>
                <th><?= __('Product Description') ?></th>
                <td><?= !empty($product->description) ? nl2br(h($product->description)) : '-' ?></td>
            </tr>
            <tr>
                <th><?= __('Story') ?></th>
                <td><?= !empty($product->story) ? nl2br(h($product->story)) : '-' ?></td>
            </tr>
            <tr>
                <th><?= __('Images') ?></th>
                <td>
                    <?php if (!empty($product->product_images)): ?>
                        <div style="display:flex;flex-wrap:wrap;gap:0.8rem;margin-top:0.4rem;">
                            <?php foreach ($product->product_images as $img): ?>
                                <img src="<?= $this->Url->image('products/' . h($img->filename)) ?>"
                                     style="width:100px;height:100px;object-fit:cover;border-radius:8px;border:1px solid #ddd9cf;">
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </div>
</div>
