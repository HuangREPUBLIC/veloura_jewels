<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Product $product
 * @var \Cake\Collection\CollectionInterface|string[] $categories
 */
?>
<?php $this->Html->css('admincontact', ['block' => true]); ?>

<div class="login-page">
    <div class="users form content login-card--wide">
        <?= $this->Html->link(__('← Back'), ['action' => 'index']) ?>

        <?= $this->Form->create($product, ['enctype' => 'multipart/form-data']) ?>
        <fieldset>
            <legend><?= __('Add Product') ?></legend>

            <?= $this->Flash->render() ?>

            <?php
            echo $this->Form->control('name',           ['label' => 'Name',           'required' => true]);
            echo $this->Form->control('purchase_price', ['label' => 'Purchase Price', 'required' => true]);
            echo $this->Form->control('sale_price',     ['label' => 'Sale Price',     'required' => true]);
            echo $this->Form->control('supplier_email', ['label' => 'Supplier Email']);
            echo $this->Form->control('categories._ids', [
                'type'     => 'select',
                'options'  => $categories,
                'empty'    => 'Select a category',
                'label'    => 'Categories',
                'required' => true,
            ]);
            echo $this->Form->control('product_images[]', [
                'type'     => 'file',
                'label'    => 'Images',
                'multiple' => true,
                'accept'   => 'image/*',
            ]);
            echo $this->Form->control('description',    ['type' => 'textarea', 'label' => 'Description', 'required' => true]);
            ?>

            <div class="input">
                <label>Size &amp; Stock</label>
                <div id="variants-container">
                    <div class="variant-row" style="display:flex;gap:1rem;margin-bottom:0.5rem;align-items:center;">
                        <select name="product_variants[0][size]">
                            <option value="">-- Size --</option>
                            <option value="Size 5">Size 5</option>
                            <option value="Size 6">Size 6</option>
                            <option value="Size 7">Size 7</option>
                            <option value="Size 8">Size 8</option>
                            <option value="Size 9">Size 9</option>
                            <option value="Size 10">Size 10</option>
                            <option value="Size 11">Size 11</option>
                            <option value="Size 12">Size 12</option>
                            <option value="One Size">One Size</option>
                        </select>
                        <input type="number" name="product_variants[0][stock]" placeholder="Stock" min="0" style="width:80px;">
                        <button type="button" onclick="this.parentNode.remove()">✕</button>
                    </div>
                </div>
                <button type="button" class="btn-add-variant" onclick="addVariantRow()">Add Size</button>
            </div>
        </fieldset>

        <?= $this->Form->button(__('Save'), ['class' => 'login-button']) ?>
        <?= $this->Form->end() ?>
    </div>
</div>

<script>
    var variantIndex = 1;
    var sizeOptions = ['Size 5','Size 6','Size 7','Size 8','Size 9','Size 10','Size 11','Size 12','One Size'];

    function addVariantRow() {
        var container = document.getElementById('variants-container');
        var row = document.createElement('div');
        row.className = 'variant-row';
        row.style = 'display:flex;gap:1rem;margin-bottom:0.5rem;align-items:center;';
        var opts = '<option value="">-- Size --</option>' + sizeOptions.map(s => '<option value="' + s + '">' + s + '</option>').join('');
        row.innerHTML = '<select name="product_variants[' + variantIndex + '][size]">' + opts + '</select>'
            + '<input type="number" name="product_variants[' + variantIndex + '][stock]" placeholder="Stock" min="0" style="width:80px;">'
            + '<button type="button" onclick="this.parentNode.remove()">✕</button>';
        container.appendChild(row);
        variantIndex++;
    }
</script>
