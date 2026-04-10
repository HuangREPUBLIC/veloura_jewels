<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Product $product
 */
$this->assign('title', 'View Product');
?>
<?php $this->Html->css('admincontact', ['block' => true]); ?>

<div class="admin-wrapper">
    <div class="products view content">
        <h3><?= h($product->name) ?></h3>

        <div class="action-buttons">
            <?= $this->Html->link(__('← Back to Products'), ['action' => 'index']) ?>

            <?php
            $identity = $this->request->getAttribute('identity');
            $currentRole = $identity ? $identity->get('role') : null;
            ?>

            <?php if (in_array($currentRole, ['admin', 'part_time', 'full_time'])): ?>
                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $product->id]) ?>
            <?php endif; ?>

            <?php if (in_array($currentRole, ['admin', 'part_time', 'full_time'])): ?>
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

        <table>
            <tr>
                <th><?= __('Id') ?></th>
                <td><?= $this->Number->format($product->id) ?></td>
            </tr>
            <tr>
                <th><?= __('Name') ?></th>
                <td><?= h($product->name) ?></td>
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
                <th><?= __('Stock') ?></th>
                <td>
                    <?php if ($product->stock < 5): ?>
                        <span class="stock-low"><?= $product->stock ?></span>
                    <?php else: ?>
                        <?= $product->stock ?>
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
                <th><?= __('Categories') ?></th>
                <td>
                    <?php
                    if (!empty($product->categories)) {
                        echo implode(', ', collection($product->categories)->extract('name')->toList());
                    } else {
                        echo '-';
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <th><?= __('Description') ?></th>
                <td><?= !empty($product->description) ? nl2br(h($product->description)) : '-' ?></td>
            </tr>
        </table>
    </div>
</div>
