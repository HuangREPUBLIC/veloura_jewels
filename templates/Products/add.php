<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Product $product
 * @var \Cake\Collection\CollectionInterface|string[] $categories
 * @var string $categoriesJson
 * @var array $types
 */
$this->Html->css('login', ['block' => true]);
?>
<div class="login-page">
    <div class="users form content login-card--wide">
        <?php
        $backUrl = ($preselectedType ?? '') === 'home_decor' ? '/home-decor'
            : (($preselectedType ?? '') === 'jewelry' ? '/jewelry' : ['action' => 'index']);
        ?>
        <?= $this->Html->link(__('← Back'), $backUrl) ?>

        <?= $this->Form->create($product, ['enctype' => 'multipart/form-data', 'id' => 'product-form']) ?>
        <fieldset>
            <legend><?= __('Add Product') ?></legend>

            <?= $this->Flash->render() ?>

            <?php
            echo $this->Form->control('name',           ['label' => ['text' => 'Name <span style="color:red">*</span>',           'escape' => false], 'required' => true]);
            echo $this->Form->control('purchase_price', ['label' => ['text' => 'Purchase Price <span style="color:red">*</span>', 'escape' => false], 'required' => true]);
            echo $this->Form->control('sale_price',     ['label' => ['text' => 'Sale Price <span style="color:red">*</span>',     'escape' => false], 'required' => true]);
            echo $this->Form->control('supplier_email', ['label' => 'Supplier Email']);
            ?>

            <!-- Type must be selected first -->
            <div class="input">
                <label for="type-select">Type <span style="color:red">*</span></label>
                <select name="type" id="type-select" required onchange="onTypeChange(this.value)">
                    <option value="">-- Select a type --</option>
                    <?php foreach ($types as $key => $label): ?>
                        <option value="<?= h($key) ?>"><?= h($label) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Category: filtered by type -->
            <div class="input">
                <label for="categories-select">Category <span style="color:red">*</span></label>
                <select name="category_id" id="categories-select" required disabled
                        onchange="onCategoryChange(this.value)">
                    <option value="">-- Select a type first --</option>
                </select>
                <div id="new-category-input" style="display:none;margin-top:0.5rem;">
                    <input type="text" name="new_category_name" id="new-category-name"
                           placeholder="New category name"
                           style="width:100%;box-sizing:border-box;">
                </div>
            </div>

            <!-- Images: drag to reorder, click or drop to add -->
            <div class="input">
                <label for="Images">Images <span style="color:red">*</span></label>
                <div class="img-upload-zone" id="img-upload-zone">
                    <input type="file" id="real-file-input" name="product_images[]"
                           multiple accept="image/*" style="display:none">
                    <div class="img-upload-prompt"
                         onclick="document.getElementById('real-file-input').click()">
                        Click to select images &mdash; or drag &amp; drop files here
                    </div>
                    <div class="img-preview-grid" id="img-preview-grid"></div>
                </div>
            </div>

            <?php
            echo $this->Form->control('description', ['type' => 'textarea', 'label' => ['text' => 'Product Description <span style="color:red">*</span>', 'escape' => false], 'required' => true]);
            echo $this->Form->control('story ',       ['type' => 'textarea', 'label' => ['text' => 'Story <span style="color:red">*</span>',               'escape' => false], 'required' => true]);
            ?>

            <!-- Size & Stock: filtered by type -->
            <div class="input">
                <label for="Size">Size &amp; Stock <span style="color:red">*</span></label>

                <div id="variants-container">
                    <div class="variant-row">
                        <select name="product_variants[0][size]" id="first-size-select">
                            <option value="">-- Select a type first --</option>
                        </select>
                        <input type="number" name="product_variants[0][stock]" placeholder="Qty" min="0">
                        <button type="button" onclick="this.parentNode.remove()">✕</button>
                    </div>
                </div>
                <button type="button" class="btn-add-variant" onclick="addVariantRow()">+ Add Size</button>
            </div>
        </fieldset>

        <?= $this->Form->button(__('Save'), ['class' => 'login-button']) ?>
        <?= $this->Form->end() ?>
    </div>
</div>

<script>
    // Image upload with drag-to-reorder
    var fileList   = [];
    var realInput  = document.getElementById('real-file-input');
    var previewGrid = document.getElementById('img-preview-grid');
    var uploadZone = document.getElementById('img-upload-zone');
    var dragSrcIndex = null;

    realInput.addEventListener('change', function() {
        Array.from(this.files).forEach(function(f) { fileList.push(f); });
        renderPreviews();
        realInput.value = '';
    });

    uploadZone.addEventListener('dragover', function(e) {
        if (Array.from(e.dataTransfer.types).indexOf('Files') !== -1) {
            e.preventDefault();
            this.classList.add('zone-hover');
        }
    });
    uploadZone.addEventListener('dragleave', function(e) {
        if (e.relatedTarget && this.contains(e.relatedTarget)) return;
        this.classList.remove('zone-hover');
    });
    uploadZone.addEventListener('drop', function(e) {
        if (Array.from(e.dataTransfer.types).indexOf('Files') !== -1) {
            e.preventDefault();
            this.classList.remove('zone-hover');
            Array.from(e.dataTransfer.files).forEach(function(f) {
                if (f.type.startsWith('image/')) fileList.push(f);
            });
            renderPreviews();
        }
    });

    function renderPreviews() {
        previewGrid.innerHTML = '';
        fileList.forEach(function(file, i) {
            var url = URL.createObjectURL(file);
            var div = document.createElement('div');
            div.className = 'img-thumb';
            div.draggable = true;
            div.dataset.index = i;
            div.innerHTML = '<img src="' + url + '" alt="">'
                + '<button type="button" class="img-remove-btn" data-i="' + i + '">×</button>';

            div.querySelector('.img-remove-btn').addEventListener('click', function() {
                fileList.splice(parseInt(this.dataset.i), 1);
                renderPreviews();
            });

            div.addEventListener('dragstart', function(e) {
                dragSrcIndex = parseInt(this.dataset.index);
                this.classList.add('dragging');
                e.dataTransfer.effectAllowed = 'move';
            });
            div.addEventListener('dragover', function(e) {
                e.preventDefault();
                e.stopPropagation();
                e.dataTransfer.dropEffect = 'move';
                document.querySelectorAll('.img-thumb').forEach(function(el) {
                    el.classList.remove('drag-over');
                });
                this.classList.add('drag-over');
            });
            div.addEventListener('drop', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var targetIndex = parseInt(this.dataset.index);
                if (dragSrcIndex !== null && dragSrcIndex !== targetIndex) {
                    var moved = fileList.splice(dragSrcIndex, 1)[0];
                    fileList.splice(targetIndex, 0, moved);
                    renderPreviews();
                }
            });
            div.addEventListener('dragend', function() {
                document.querySelectorAll('.img-thumb').forEach(function(el) {
                    el.classList.remove('dragging', 'drag-over');
                });
                dragSrcIndex = null;
            });

            previewGrid.appendChild(div);
        });
    }

    function mergeAndRenumberVariants() {
        var container = document.getElementById('variants-container');
        var sizeMap = {};
        Array.from(container.querySelectorAll('.variant-row')).forEach(function(row) {
            var sizeEl = row.querySelector('select[name*="[size]"], input[name*="[size]"]');
            if (!sizeEl || !sizeEl.value) return;
            var size = sizeEl.value;
            var stockEl = row.querySelector('input[type="number"]');
            var stock = parseInt(stockEl ? stockEl.value : '0') || 0;
            if (sizeMap[size]) {
                var keeperStock = sizeMap[size].querySelector('input[type="number"]');
                if (keeperStock) keeperStock.value = (parseInt(keeperStock.value) || 0) + stock;
                row.remove();
            } else {
                sizeMap[size] = row;
            }
        });
        container.querySelectorAll('.variant-row').forEach(function(row, i) {
            row.querySelectorAll('[name]').forEach(function(el) {
                el.name = el.name.replace(/product_variants\[\d+\]/, 'product_variants[' + i + ']');
            });
        });
    }

    // Before submit: merge duplicate sizes, then set file input to reordered list
    document.getElementById('product-form').addEventListener('submit', function() {
        mergeAndRenumberVariants();
        if (fileList.length > 0) {
            var dt = new DataTransfer();
            fileList.forEach(function(f) { dt.items.add(f); });
            realInput.files = dt.files;
        }
    });

    // Category / type logic
    var allCategories = <?= $categoriesJson ?>;
    var variantIndex  = 1;

    var ringSizes = ['Size 5','Size 6','Size 7','Size 8','Size 9','Size 10','Size 11','Size 12','One Size'];

    function getSizes() {
        var catId = document.getElementById('categories-select').value;
        var cat = allCategories.find(function(c) { return c.id == catId; });
        return (cat && cat.name === 'Rings') ? ringSizes : ['One Size'];
    }

    function onTypeChange(type) {
        updateCategories(type);
        updateAllSizeSelects();
    }

    function updateCategories(type) {
        var select      = document.getElementById('categories-select');
        var newCatDiv   = document.getElementById('new-category-input');
        var newCatInput = document.getElementById('new-category-name');

        newCatDiv.style.display = 'none';
        newCatInput.required = false;
        newCatInput.value = '';

        if (!type) {
            select.disabled = true;
            select.innerHTML = '<option value="">-- Select a type first --</option>';
            return;
        }

        select.disabled = false;
        select.innerHTML = '<option value="">-- Select a category --</option>';
        allCategories.filter(function(c) { return c.type === type; }).forEach(function(c) {
            var opt = document.createElement('option');
            opt.value = c.id;
            opt.textContent = c.name;
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
        updateAllSizeSelects();
    }

    function buildSizeOptions(selectedValue) {
        var sizes = getSizes();
        var html = '<option value="">-- Size --</option>';
        sizes.forEach(function(s) {
            html += '<option value="' + s + '"' + (s === selectedValue ? ' selected' : '') + '>' + s + '</option>';
        });
        return html;
    }

    function buildSizeField(name) {
        var sizes = getSizes();
        if (sizes.length === 1) {
            return '<input type="hidden" name="' + name + '" value="' + sizes[0] + '">'
                + '<span class="size-text-label">' + sizes[0] + '</span>';
        }
        return '<select name="' + name + '" class="size-select">' + buildSizeOptions('') + '</select>';
    }

    function updateAllSizeSelects() {
        var sizes = getSizes();
        document.querySelectorAll('#variants-container .variant-row').forEach(function(row) {
            var sel = row.querySelector('select[name*="[size]"]');
            var hid = row.querySelector('input[type="hidden"][name*="[size]"]');
            var lbl = row.querySelector('.size-text-label');

            if (sizes.length === 1) {
                if (sel) {
                    var name = sel.name;
                    var hidden = document.createElement('input');
                    hidden.type = 'hidden'; hidden.name = name; hidden.value = sizes[0];
                    var span = document.createElement('span');
                    span.className = 'size-text-label'; span.textContent = sizes[0];
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
                    select.name = name; select.className = 'size-select';
                    select.innerHTML = buildSizeOptions('');
                    row.insertBefore(select, hid);
                    hid.remove();
                    if (lbl) lbl.remove();
                } else if (sel) {
                    sel.innerHTML = buildSizeOptions(sel.value);
                }
            }
        });
    }

    function addVariantRow() {
        var row = document.createElement('div');
        row.className = 'variant-row';
        row.innerHTML = buildSizeField('product_variants[' + variantIndex + '][size]')
            + '<input type="number" name="product_variants[' + variantIndex + '][stock]" placeholder="Qty" min="0">'
            + '<button type="button" onclick="this.parentNode.remove()">✕</button>';
        document.getElementById('variants-container').appendChild(row);
        variantIndex++;
    }
</script>
