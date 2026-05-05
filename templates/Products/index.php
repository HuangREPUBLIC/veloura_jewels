<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Product> $products
 */
$this->assign('title', 'Products');
?>
<?php $this->Html->css('admincontact', ['block' => true]); ?>

<?php $role = $this->request->getAttribute('identity')->get('role'); ?>

<div class="admin-wrapper">
    <div class="products index content">
        <?= $this->Html->link(__('← Back'), ['controller' => 'Users', 'action' => 'dashboard'], ['class' => 'back-link']) ?>

        <div class="page-header-row">
            <div>
                <h3 class="page-title"><?= __('Products') ?></h3>
            </div>

            <?php if ($role === 'admin'): ?>
                <?= $this->Html->link(__('Add New Product'), ['action' => 'add'], ['class' => 'btn-new-product']) ?>
            <?php endif; ?>
        </div>

        <div class="table-responsive" style="padding: 10px">
            <table id="productsTable" class="display">
                <thead>
                <tr>
                    <th>Featured</th>
                    <th>Name</th>
                    <th>Purchase Price</th>
                    <th>Sale Price</th>
                    <th>Size & Stock</th>
                    <th>Category</th>
                    <th>Supplier Email</th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td style="text-align:center">
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
                            <div class="product-text">
                                <span class="product-name"><?= h($product->name) ?></span>
                            </div>
                        </td>

                        <td><?= $this->Number->format($product->purchase_price) ?></td>
                        <td><?= $this->Number->format($product->sale_price) ?></td>

                        <td>
                            <?php
                            $lowVariants = [];
                            if (!empty($product->product_variants)) {
                                foreach ($product->product_variants as $v) {
                                    if ($v->stock < 5) {
                                        $lowVariants[] = h($v->size) . ': ' . $v->stock;
                                    }
                                }
                            }
                            echo !empty($lowVariants)
                                ? '<span class="stock-low">' . implode(', ', $lowVariants) . '</span>'
                                : '✓';
                            ?>
                        </td>
                        <td>
                            <?php if (!empty($product->categories)): ?>
                                <span class="admin-category-text">
                                    <?= h(implode(', ', collection($product->categories)->extract('name')->toList())) ?>
                                </span>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td class="supplier-email"><?= h($product->supplier_email) ?></td>
                        <td class="actions">
                            <?= $this->Html->link(__('View'), ['action' => 'view', $product->id], ['class' => 'btn-view']) ?>
                            <?php if ($role === 'admin'): ?>
                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $product->id], ['class' => 'btn-edit']) ?>
                            <?php endif; ?>
                            <?php if ($role === 'admin'): ?>
                                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $product->id], [
                                    'method' => 'delete',
                                    'confirm' => __('Are you sure you want to delete # {0}?', $product->id),
                                    'class' => 'btn-delete'
                                ]) ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#productsTable').DataTable({
            order: [[1, 'asc']],
            language: { lengthMenu: '_MENU_ Entries Per Page', search: 'Search:' },
            columnDefs: [
                { targets: [0], orderable: false, searchable: false },
                { targets: [1,2,3,4,5,6,7], searchable: true }
            ]
        });

        var csrfToken = '<?= $this->request->getAttribute('csrfToken') ?>';

        $('#productsTable').on('click', '.featured-toggle', function() {
            var btn = $(this);
            $.ajax({
                url: btn.data('url'),
                method: 'POST',
                data: { _csrfToken: csrfToken },
                dataType: 'json',
                success: function(res) {
                    btn.toggleClass('is-featured', res.featured);
                }
            });
        });
    });
</script>
