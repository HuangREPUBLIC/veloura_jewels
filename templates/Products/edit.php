<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Product $product
 * @var string[]|\Cake\Collection\CollectionInterface $categories
 * @var string $categoriesJson
 * @var array $types
 */
$this->Html->css('login', ['block' => true]);

$currentType = $product->category->type ?? $product->type ?? '';
?>

<div class="login-page">
    <div class="users form content login-card--wide">
        <?php
        $backUrl = ($from ?? '') === 'dashboard'
            ? ['controller' => 'Users', 'action' => 'dashboard']
            : ['action' => 'index'];
        ?>
        <?= $this->Html->link(__('← Back'), $backUrl) ?>

        <?= $this->Form->create($product, ['enctype' => 'multipart/form-data', 'id' => 'product-form']) ?>
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
            <?php $selectedCatId = $product->category_id ?? null; ?>
            <div class="input">
                <label for="categories-select">Category <span style="color:red">*</span></label>
                <select name="category_id" id="categories-select" required
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

            <!-- Existing images: click × to mark for deletion, click again to undo -->
            <?php if (!empty($product->product_images)): ?>
                <div class="input">
                    <label>Current Images</label>
                    <div class="existing-images-grid">
                        <?php foreach ($product->product_images as $img): ?>
                            <div class="img-existing-thumb" id="img-<?= $img->id ?>">
                                <img src="<?= $this->Url->image('products/' . h($img->filename)) ?>"
                                     alt="<?= h($img->filename) ?>">
                                <button type="button" class="img-delete-btn"
                                        onclick="toggleImgDelete(this, <?= (int)$img->id ?>)"
                                        title="Mark for deletion">×</button>
                                <input type="checkbox" name="delete_images[]"
                                       value="<?= (int)$img->id ?>"
                                       id="del-<?= $img->id ?>"
                                       style="display:none">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Add new images: drag to reorder before saving -->
            <div class="input">
                <label>Add New Images</label>
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
            echo $this->Form->control('description', ['type' => 'textarea', 'label' => 'Product Description', 'required' => true]);
            echo $this->Form->control('story',       ['type' => 'textarea', 'label' => 'Story',               'required' => true]);
            ?>

            <!-- Size & Stock -->
            <div class="input">
                <label>Size &amp; Stock</label>
                <div id="variants-container">
                    <?php if (!empty($product->product_variants)): ?>
                        <?php foreach ($product->product_variants as $i => $variant): ?>
                            <?php
                            $sizes = $currentType === 'home_decor'
                                ? ['One Size']
                                : ['Size 5','Size 6','Size 7','Size 8','Size 9','Size 10','Size 11','Size 12','One Size'];
                            ?>
                            <div class="variant-row">
                                <input type="hidden" name="product_variants[<?= $i ?>][id]"
                                       value="<?= (int)$variant->id ?>">
                                <?php if (count($sizes) === 1): ?>
                                    <input type="hidden" name="product_variants[<?= $i ?>][size]"
                                           value="<?= h($sizes[0]) ?>">
                                    <span class="size-text-label"><?= h($sizes[0]) ?></span>
                                <?php else: ?>
                                    <select name="product_variants[<?= $i ?>][size]" class="size-select">
                                        <?php foreach ($sizes as $s): ?>
                                            <option value="<?= $s ?>"
                                                    <?= $variant->size === $s ? 'selected' : '' ?>>
                                                <?= $s ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php endif; ?>
                                <input type="number" name="product_variants[<?= $i ?>][stock]"
                                       value="<?= (int)$variant->stock ?>" placeholder="Qty" min="0">
                                <button type="button" onclick="this.parentNode.remove()">✕</button>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <button type="button" class="btn-add-variant" onclick="addVariantRow()">+ Add Size</button>
            </div>
        </fieldset>

        <?= $this->Form->button(__('Save'), ['class' => 'login-button']) ?>
        <?= $this->Form->end() ?>
    </div>
</div>

<script>
    // Toggle existing image delete
    function toggleImgDelete(btn, imgId) {
        var thumb    = document.getElementById('img-' + imgId);
        var checkbox = document.getElementById('del-' + imgId);
        var marked   = thumb.classList.toggle('marked-delete');
        checkbox.checked = marked;
        btn.classList.toggle('undo', marked);
        btn.title = marked ? 'Undo deletion' : 'Mark for deletion';
    }

    // Image upload with drag-to-reorder
    var fileList    = [];
    var realInput   = document.getElementById('real-file-input');
    var previewGrid = document.getElementById('img-preview-grid');
    var uploadZone  = document.getElementById('img-upload-zone');
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

    document.getElementById('product-form').addEventListener('submit', function() {
        if (fileList.length > 0) {
            var dt = new DataTransfer();
            fileList.forEach(function(f) { dt.items.add(f); });
            realInput.files = dt.files;
        }
    });

    // Category / type logic
    var allCategories = <?= $categoriesJson ?>;
    var selectedCatId = <?= json_encode($selectedCatId) ?>;
    var variantIndex   = <?= !empty($product->product_variants) ? count($product->product_variants) : 0 ?>;

    var sizesByType = {
        'jewelry':    ['Size 5','Size 6','Size 7','Size 8','Size 9','Size 10','Size 11','Size 12','One Size'],
        'home_decor': ['One Size']
    };

    (function init() {
        var currentType = document.getElementById('type-select').value;
        if (currentType) populateCategories(currentType, selectedCatId);
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
            if (c.id == preselected) opt.selected = true;
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
            html += '<option value="' + s + '"' + (s === selectedValue ? ' selected' : '') + '>' + s + '</option>';
        });
        return html;
    }

    function buildSizeField(name, type) {
        var sizes = sizesByType[type] || ['One Size'];
        if (sizes.length === 1) {
            return '<input type="hidden" name="' + name + '" value="' + sizes[0] + '">'
                + '<span class="size-text-label">' + sizes[0] + '</span>';
        }
        return '<select name="' + name + '" class="size-select">' + buildSizeOptions(type, '') + '</select>';
    }

    function updateAllSizeSelects(type) {
        var sizes = sizesByType[type] || ['One Size'];
        document.querySelectorAll('#variants-container .variant-row').forEach(function(row) {
            var sel = row.querySelector('select.size-select');
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
                    select.innerHTML = buildSizeOptions(type, '');
                    row.insertBefore(select, hid);
                    hid.remove();
                    if (lbl) lbl.remove();
                } else if (sel) {
                    sel.innerHTML = buildSizeOptions(type, sel.value);
                }
            }
        });
    }

    function addVariantRow() {
        var type = document.getElementById('type-select').value;
        var row = document.createElement('div');
        row.className = 'variant-row';
        row.innerHTML = buildSizeField('product_variants[' + variantIndex + '][size]', type)
            + '<input type="number" name="product_variants[' + variantIndex + '][stock]" placeholder="Qty" min="0">'
            + '<button type="button" onclick="this.parentNode.remove()">✕</button>';
        document.getElementById('variants-container').appendChild(row);
        variantIndex++;
    }
</script>
