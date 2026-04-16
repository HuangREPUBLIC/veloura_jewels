<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Product $product
 * @var \Cake\Collection\CollectionInterface|string[] $categories
 * @var string $categoriesJson
 * @var array $types
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
            ?>

            <!-- Type must be selected first -->
            <div class="input">
                <label for="type">Type <span style="color:red">*</span></label>
                <select name="type" id="type-select" required onchange="onTypeChange(this.value)">
                    <option value="">-- Select a type --</option>
                    <?php foreach ($types as $key => $label): ?>
                        <option value="<?= h($key) ?>"><?= h($label) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Category: filtered by type -->
            <div class="input">
                <label for="categories-select">Categories <span style="color:red">*</span></label>
                <select name="categories[_ids][]" id="categories-select" required disabled>
                    <option value="">-- Select a type first --</option>
                </select>
            </div>

            <?php
            echo $this->Form->control('product_images[]', [
                'type'     => 'file',
                'label'    => 'Images',
                'multiple' => true,
                'accept'   => 'image/*',
            ]);
            echo $this->Form->control('description', ['type' => 'textarea', 'label' => 'Description', 'required' => true]);
            ?>

            <!-- Size & Stock: filtered by type -->
            <div class="input">
                <label>Size &amp; Stock</label>
                <div id="variants-container">
                    <div class="variant-row" style="display:flex;gap:1rem;margin-bottom:0.5rem;align-items:center;">
                        <select name="product_variants[0][size]" id="first-size-select">
                            <option value="">-- Select a type first --</option>
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
    var allCategories = <?= $categoriesJson ?>;
    var variantIndex  = 1;

    var sizesByType = {
        'jewelry':    ['Size 5','Size 6','Size 7','Size 8','Size 9','Size 10','Size 11','Size 12','One Size'],
        'home_decor': ['One Size']
    };

    function onTypeChange(type) {
        updateCategories(type);
        updateAllSizeSelects(type);
    }

    function updateCategories(type) {
        var select = document.getElementById('categories-select');
        select.innerHTML = '';

        if (!type) {
            select.disabled = true;
            select.innerHTML = '<option value="">-- Select a type first --</option>';
            return;
        }

        var filtered = allCategories.filter(function(c) { return c.type === type; });
        select.disabled = false;
        select.innerHTML = '<option value="">-- Select a category --</option>';
        filtered.forEach(function(c) {
            var opt = document.createElement('option');
            opt.value = c.id;
            opt.textContent = c.name;
            select.appendChild(opt);
        });
    }

    function buildSizeOptions(type, selectedValue) {
        var sizes = sizesByType[type] || [];
        var html = '<option value="">-- Size --</option>';
        sizes.forEach(function(s) {
            var sel = (s === selectedValue) ? ' selected' : '';
            html += '<option value="' + s + '"' + sel + '>' + s + '</option>';
        });
        return html;
    }

    function updateAllSizeSelects(type) {
        var selects = document.querySelectorAll('#variants-container select[name*="[size]"]');
        selects.forEach(function(sel) {
            var current = sel.value;
            sel.innerHTML = buildSizeOptions(type, current);
            // If home_decor, auto-select One Size
            if (type === 'home_decor') sel.value = 'One Size';
        });
    }

    function addVariantRow() {
        var type = document.getElementById('type-select').value;
        var container = document.getElementById('variants-container');
        var row = document.createElement('div');
        row.className = 'variant-row';
        row.style = 'display:flex;gap:1rem;margin-bottom:0.5rem;align-items:center;';

        var opts = buildSizeOptions(type, type === 'home_decor' ? 'One Size' : '');
        row.innerHTML = '<select name="product_variants[' + variantIndex + '][size]">' + opts + '</select>'
            + '<input type="number" name="product_variants[' + variantIndex + '][stock]" placeholder="Stock" min="0" style="width:80px;">'
            + '<button type="button" onclick="this.parentNode.remove()">✕</button>';
        container.appendChild(row);
        variantIndex++;
    }
</script>
