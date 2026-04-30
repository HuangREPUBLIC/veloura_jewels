<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Product $product
 * @var string[]|\Cake\Collection\CollectionInterface $categories
 * @var string $categoriesJson
 * @var array $types
 */
$this->Html->css('login', ['block' => true]);

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

<div class="login-page">
    <div class="users form content login-card--wide">
        <?php
        $backUrl = ($from ?? '') === 'dashboard'
            ? ['controller' => 'Users', 'action' => 'dashboard']
            : ['action' => 'index'];
        ?>
        <?= $this->Html->link(__('← Back'), $backUrl) ?>

        <?= $this->Form->create($product, ['enctype' => 'multipart/form-data']) ?>
        <?= $this->Form->hidden('from', ['value' => $from ?? '']) ?>
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
            $selectedCatIds = [];
            if (!empty($product->categories)) {
                foreach ($product->categories as $cat) {
                    $selectedCatIds[] = $cat->id;
                }
            }
            $selectedCatIdsJson = json_encode($selectedCatIds);
            ?>
            <div class="input">
                <label for="categories-select">Category <span style="color:red">*</span></label>
                <select name="categories[_ids][]" id="categories-select" required
                        <?= empty($currentType) ? 'disabled' : '' ?>
                        onchange="onCategoryChange(this.value)">
                    <?php if (empty($currentType)): ?>
                        <option value="">-- Select a type first --</option>
                    <?php endif; ?>
                </select>
                <div id="new-category-input" style="display:none;margin-top:0.5rem;">
                    <input type="text" name="new_category_name" id="new-category-name"
                           placeholder="New category name"
                           style="width:100%;box-sizing:border-box;">
                </div>
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
                                    <?php
                                    $sizes = $currentType === 'home_decor'
                                        ? ['One Size']
                                        : ['Size 5','Size 6','Size 7','Size 8','Size 9','Size 10','Size 11','Size 12','One Size'];
                                ?>
                                <?php if (count($sizes) === 1): ?>
                                    <input type="hidden" name="product_variants[<?= $i ?>][size]" value="<?= h($sizes[0]) ?>">
                                    <span class="size-text-label" style="min-width:100px;"><?= h($sizes[0]) ?></span>
                                <?php else: ?>
                                    <select name="product_variants[<?= $i ?>][size]" class="size-select">
                                        <?php foreach ($sizes as $s): ?>
                                            <option value="<?= $s ?>" <?= $variant->size === $s ? 'selected' : '' ?>><?= $s ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php endif; ?>
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
        var select      = document.getElementById('categories-select');
        var newCatDiv   = document.getElementById('new-category-input');
        var newCatInput = document.getElementById('new-category-name');

        newCatDiv.style.display = 'none';
        newCatInput.required = false;
        newCatInput.value = '';
        select.innerHTML = '';

        if (!type) {
            select.disabled = true;
            select.innerHTML = '<option value="">-- Select a type first --</option>';
            return;
        }

        select.disabled = false;
        select.innerHTML = '<option value="">-- Select a category --</option>';
        var filtered = allCategories.filter(function(c) { return c.type === type; });
        filtered.forEach(function(c) {
            var opt = document.createElement('option');
            opt.value = c.id;
            opt.textContent = c.name;
            if (preselected.indexOf(c.id) !== -1) opt.selected = true;
            select.appendChild(opt);
        });

        var newOpt = document.createElement('option');
        newOpt.value = '__new__';
        newOpt.textContent = '+ Add new category...';
        select.appendChild(newOpt);
    }

    function onCategoryChange(value) {
        var newCatDiv   = document.getElementById('new-category-input');
        var newCatInput = document.getElementById('new-category-name');

        if (value === '__new__') {
            newCatDiv.style.display = 'block';
            newCatInput.required = true;
        } else {
            newCatDiv.style.display = 'none';
            newCatInput.required = false;
            newCatInput.value = '';
        }
    }

    function buildSizeOptions(type, selectedValue) {
        var sizes = sizesByType[type] || ['One Size'];
        var html = '';
        sizes.forEach(function(s) {
            var sel = (s === selectedValue) ? ' selected' : '';
            html += '<option value="' + s + '"' + sel + '>' + s + '</option>';
        });
        return html;
    }

    function buildSizeField(name, type) {
        var sizes = sizesByType[type] || ['One Size'];
        if (sizes.length === 1) {
            return '<input type="hidden" name="' + name + '" value="' + sizes[0] + '">'
                + '<span class="size-text-label" style="min-width:100px;">' + sizes[0] + '</span>';
        }
        return '<select name="' + name + '" class="size-select">' + buildSizeOptions(type, '') + '</select>';
    }

    function updateAllSizeSelects(type) {
        var sizes = sizesByType[type] || ['One Size'];
        var rows = document.querySelectorAll('#variants-container .variant-row');
        rows.forEach(function(row) {
            var sel = row.querySelector('select.size-select');
            var hid = row.querySelector('input[type="hidden"][name*="[size]"]');
            var lbl = row.querySelector('.size-text-label');

            if (sizes.length === 1) {
                if (sel) {
                    var name = sel.name;
                    var hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.name = name;
                    hidden.value = sizes[0];
                    var span = document.createElement('span');
                    span.className = 'size-text-label';
                    span.style.minWidth = '100px';
                    span.textContent = sizes[0];
                    row.insertBefore(hidden, sel);
                    row.insertBefore(span, sel);
                    sel.remove();
                } else if (hid) {
                    hid.value = sizes[0];
                    if (lbl) lbl.textContent = sizes[0];
                }
            } else {
                if (hid) {
                    var name = hid.name;
                    var select = document.createElement('select');
                    select.name = name;
                    select.className = 'size-select';
                    select.innerHTML = buildSizeOptions(type, '');
                    row.insertBefore(select, hid);
                    hid.remove();
                    if (lbl) lbl.remove();
                } else if (sel) {
                    var current = sel.value;
                    sel.innerHTML = buildSizeOptions(type, current);
                }
            }
        });
    }

    function addVariantRow() {
        var type = document.getElementById('type-select').value;
        var container = document.getElementById('variants-container');
        var row = document.createElement('div');
        row.className = 'variant-row';
        row.style.cssText = 'display:flex;gap:1rem;margin-bottom:0.5rem;align-items:center;';

        row.innerHTML = buildSizeField('product_variants[' + variantIndex + '][size]', type)
            + '<input type="number" name="product_variants[' + variantIndex + '][stock]" placeholder="Stock" min="0" style="width:80px;">'
            + '<button type="button" onclick="this.parentNode.remove()">✕</button>';
        container.appendChild(row);
        variantIndex++;
    }
</script>
