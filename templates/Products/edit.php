<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Product $product
 * @var string[]|\Cake\Collection\CollectionInterface $categories
 */
?>
<?php $this->Html->css('admincontact', ['block' => true]); ?>

<div class="login-page">
    <div class="users form content login-card--wide">
        <?= $this->Html->link(__('← Back'), ['action' => 'index']) ?>

        <?= $this->Form->create($product, ['enctype' => 'multipart/form-data']) ?>
        <fieldset>
            <legend><?= __('Edit Product') ?></legend>

            <?= $this->Flash->render() ?>

            <?php
            echo $this->Form->control('name',           ['label' => 'Name',           'required' => true]);
            echo $this->Form->control('purchase_price', ['label' => 'Purchase Price', 'required' => true]);
            echo $this->Form->control('sale_price',     ['label' => 'Sale Price',     'required' => true]);
            echo $this->Form->control('supplier_email', ['label' => 'Supplier Email']);
            echo $this->Form->control('categories._ids', [
                'type'    => 'select',
                'options' => $categories,
                'empty'   => 'Select a category',
                'label'   => 'Categories',
            ]);
            ?>
            <!-- Existing images -->
            <?php if (!empty($product->product_images)): ?>
                <div class="input">
                    <label>Current Images</label>
                    <div style="display:flex;flex-wrap:wrap;gap:0.8rem;margin-top:0.4rem;">
                        <?php foreach ($product->product_images as $img): ?>
                            <div style="text-align:center;">
                                <img src="<?= $this->Url->image('products/' . h($img->filename)) ?>"
                                     style="width:80px;height:80px;object-fit:cover;border-radius:6px;border:1px solid #e0e0e0;">
                                <div style="margin-top:0.3rem;font-size:0.75rem;">
                                    <label style="cursor:pointer;color:#c0392b;">
                                        <input type="checkbox" name="delete_images[]" value="<?= $img->id ?>">
                                        Delete
                                    </label>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php
            echo $this->Form->control('product_images[]', [
                'type'     => 'file',
                'label'    => 'Add New Images',
                'multiple' => true,
                'accept'   => 'image/*',
            ]);
            echo $this->Form->control('description',    ['type' => 'textarea', 'label' => 'Description', 'required' => true]);
            ?>

            <div class="input">
                <label>Size &amp; Stock</label>
                <div id="variants-container">
                    <?php if (!empty($product->product_variants)): ?>
                        <?php foreach ($product->product_variants as $i => $variant): ?>
                            <div class="variant-row" style="display:flex;gap:1rem;margin-bottom:0.5rem;align-items:center;">
                                <select name="product_variants[<?= $i ?>][id]" style="display:none">
                                    <option value="<?= $variant->id ?>" selected></option>
                                </select>
                                <select name="product_variants[<?= $i ?>][size]">
                                    <?php
                                    $sizes = ['Size 5','Size 6','Size 7','Size 8','Size 9','Size 10','Size 11','Size 12','One Size'];
                                    foreach ($sizes as $s):
                                        ?>
                                        <option value="<?= $s ?>" <?= $variant->size === $s ? 'selected' : '' ?>><?= $s ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="number" name="product_variants[<?= $i ?>][stock]" value="<?= $variant->stock ?>" placeholder="Stock" min="0" style="width:80px;">
                                <button type="button" onclick="this.parentNode.remove()">✕</button>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <button type="button" class="btn-add-variant" onclick="addVariantRow()">Add Size</button>
            </div>
        </fieldset>

        <?= $this->Form->button(__('Save'), ['class' => 'login-button']) ?>
        <?= $this->Form->end() ?>
    </div>
</div>

<script>
    var variantIndex = <?= !empty($product->product_variants) ? count($product->product_variants) : 0 ?>;
    var sizeOptions = ['Size 5','Size 6','Size 7','Size 8','Size 9','Size 10','Size 11','Size 12','One Size'];

    function addVariantRow() {
        var container = document.getElementById('variants-container');
        var row = document.createElement('div');
        row.className = 'variant-row';
        row.style = 'display:flex;gap:1rem;margin-bottom:0.5rem;align-items:center;';
        var opts = sizeOptions.map(s => '<option value="' + s + '">' + s + '</option>').join('');
        row.innerHTML = '<select name="product_variants[' + variantIndex + '][size]">' + opts + '</select>'
            + '<input type="number" name="product_variants[' + variantIndex + '][stock]" placeholder="Stock" min="0" style="width:80px;">'
            + '<button type="button" onclick="this.parentNode.remove()">✕</button>';
        container.appendChild(row);
        variantIndex++;
    }
</script>
