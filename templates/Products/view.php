<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Product $product
 */
$this->assign('title', 'View Product');
$this->assign('crumbRecord', h($product->name));

$productType = $product->category->type ?? '';
?>
<?php $this->Html->css('admincontact', ['block' => true]); ?>

        <div class="page-header-row">
            <div>
                <h3 class="page-title"><?= h($product->name) ?></h3>
            </div>

            <?php $role = $this->request->getAttribute('identity')->get('role'); ?>

            <div class="action-buttons">
                <?php if ($role === 'admin'): ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $product->id], ['class' => 'btn-sm']) ?>
                    <?= $this->Form->postLink(
                        __('Delete'),
                        ['action' => 'delete', $product->id],
                        [
                            'confirm' => __('Are you sure you want to delete # {0}?', $product->id),
                            'class'   => 'btn-sm btn-sm--danger',
                        ]
                    ) ?>
                <?php endif; ?>
            </div>
        </div>

        <table class="view-table">

            <tr>
                <th><?= __('Name') ?></th>
                <td><?= h($product->name) ?></td>
            </tr>
            <tr>
                <th><?= __('Type') ?></th>
                <td>
                    <?= $productType !== '' ? h(ucwords(str_replace('_', ' ', $productType))) : '' ?>
                </td>
            </tr>
            <tr>
                <th><?= __('Category') ?></th>
                <td>
                    <?php if (!empty($product->category)): ?>
                        <span class="admin-category-text"><?= h($product->category->name) ?></span>
                    <?php endif; ?>
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
                        <div class="admin-stock-list">
                            <?php foreach ($product->product_variants as $variant): ?>
                                <span class="admin-stock-item">
                                    <?= h($variant->size) ?>:
                                    <?php if ($variant->stock < 5): ?>
                                        <span class="stock-low"><?= $variant->stock ?></span>
                                    <?php else: ?>
                                        <?= $variant->stock ?>
                                    <?php endif; ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
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
                <td><?= !empty($product->description) ? nl2br(h($product->description)) : '' ?></td>
            </tr>
            <tr>
                <th><?= __('Story') ?></th>
                <td><?= !empty($product->story) ? nl2br(h($product->story)) : '' ?></td>
            </tr>
            <tr>
                <th><?= __('Images') ?></th>
                <td>
                    <?php if (!empty($product->product_images)): ?>
                        <div class="admin-view-images">
                            <?php foreach ($product->product_images as $img): ?>
                                <img src="<?= $this->Url->image('products/' . h($img->filename)) ?>"
                                     class="admin-view-image"
                                     alt="<?= h($product->name) ?>">
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
