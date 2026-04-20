<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Product $product
 * @var string[]|\Cake\Collection\CollectionInterface $categories
 * @var string $categoriesJson
 * @var array $types
 */

// Determine current type from the product's existing category
$currentType = '';
if (!empty($product->categories)) {
    $currentType = $product->categories[0]->type ?? '';
}
// Also check if type was submitted (e.g. on validation fail)
if (!empty($product->type)) {
    $currentType = $product->type;
}
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
            ?>

            <!-- Type -->
            <div class="input">
                <label for="type-select">Type <span style="color:red">*</span></label>
                <select name="type" id="type-select" required onchange="onTypeChange(this.value)">
                    <option value="">-- Select a type --</option>
                    <?php foreach ($types as $key => $label): ?>
                        <option value="<?= h($key) ?>" <?= $currentType === $key ? 'selected' : '' ?>>
                            <?= h($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Category: filtered by type -->
            <?php
            // Current selected category ids
            $selectedCatIds = [];
            if (!empty($product->categories)) {
                foreach ($product->categories as $cat) {
                    $selectedCatIds[] = $cat->id;
                }
            }
            $selectedCatIdsJson = json_encode($selectedCatIds);
            ?>
            <div class="input">
                <label for="categories-select">Categories <span style="color:red">*</span></label>
                <select name="categories[_ids][]" id="categories-select" required <?= empty($currentType) ? 'disabled' : '' ?>>
                    <?php if (empty($currentType)): ?>
                        <option value="">-- Select a type first --</option>
                    <?php endif; ?>
                </select>
            </div>

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
            echo $this->Form->control('description', ['type' => 'textarea', 'label' => 'Product Description', 'required' => true]);
            echo $this->Form->control('story', ['type' => 'textarea', 'label' => 'Story', 'required' => true]);
            ?>

            <!-- Size & Stock -->
            <div class="input">
                <label>Size &amp; Stock</label>
                <div id="variants-container">
                    <?php if (!empty($product->product_variants)): ?>
                        <?php foreach ($product->product_variants as $i => $variant): ?>
                            <div class="variant-row" style="display:flex;gap:1rem;margin-bottom:0.5rem;align-items:center;">
                                <select name="product_variants[<?= $i ?>][id]" style="display:none">
                                    <option value="<?= $variant->id ?>" selected></option>
                                </select>
                                <select name="product_variants[<?= $i ?>][size]" class="size-select">
                                    <?php
                                    $sizes = $currentType === 'home_decor'
                                        ? ['One Size']
                                        : ['Size 5','Size 6','Size 7','Size 8','Size 9','Size 10','Size 11','Size 12','One Size'];
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
    var allCategories    = <?= $categoriesJson ?>;
    var selectedCatIds   = <?= $selectedCatIdsJson ?? '[]' ?>;
    var variantIndex     = <?= !empty($product->product_variants) ? count($product->product_variants) : 0 ?>;

    var sizesByType = {
        'jewelry':    ['Size 5','Size 6','Size 7','Size 8','Size 9','Size 10','Size 11','Size 12','One Size'],
        'home_decor': ['One Size']
    };

    // On page load, populate categories if type is already known
    (function init() {
        var typeSelect = document.getElementById('type-select');
        var currentType = typeSelect.value;
        if (currentType) {
            populateCategories(currentType, selectedCatIds);
        }
    })();

    function onTypeChange(type) {
        populateCategories(type, []);
        updateAllSizeSelects(type);
    }

    function populateCategories(type, preselected) {
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
            if (preselected.indexOf(c.id) !== -1) opt.selected = true;
            select.appendChild(opt);
        });
    }

    function buildSizeOptions(type, selectedValue) {
        var sizes = sizesByType[type] || [];
        var html = '';
        sizes.forEach(function(s) {
            var sel = (s === selectedValue) ? ' selected' : '';
            html += '<option value="' + s + '"' + sel + '>' + s + '</option>';
        });
        return html;
    }

    function updateAllSizeSelects(type) {
        var selects = document.querySelectorAll('#variants-container select.size-select');
        selects.forEach(function(sel) {
            var current = sel.value;
            sel.innerHTML = buildSizeOptions(type, current);
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
        row.innerHTML = '<select name="product_variants[' + variantIndex + '][size]" class="size-select">' + opts + '</select>'
            + '<input type="number" name="product_variants[' + variantIndex + '][stock]" placeholder="Stock" min="0" style="width:80px;">'
            + '<button type="button" onclick="this.parentNode.remove()">✕</button>';
        container.appendChild(row);
        variantIndex++;
    }
</script>
