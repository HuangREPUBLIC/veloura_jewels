<?php
/**
 * Shared add/edit product form. Element IDs and inline handlers are wired
 * to webroot/js/product-form.js, so keep them when changing the layout.
 *
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Product $product
 * @var array $types
 * @var string $categoriesJson
 * @var string $title
 * @var string $currentType
 * @var array $existingVariants
 * @var string|null $from
 */
$req = ' <span class="req">*</span>';
$label = fn(string $text, bool $required = true) => ['text' => $text . ($required ? $req : ''), 'escape' => false, 'class' => 'cms-label'];
$variants = $existingVariants ?: [['id' => null, 'size' => '', 'stock' => '']];
$isEdit = !$product->isNew();
?>
<div class="page-header-row">
    <div>
        <p class="cms-eyebrow">Products</p>
        <h1 class="page-title"><?= h($title) ?></h1>
    </div>
    <div class="cms-header-right">
        <button type="submit" form="product-form" class="btn-new-product"><?= __('Save') ?></button>
    </div>
</div>

<?= $this->Flash->render() ?>

<?= $this->Form->create($product, ['enctype' => 'multipart/form-data', 'id' => 'product-form', 'class' => 'product-editor']) ?>
<?php if ($isEdit): ?>
    <?= $this->Form->hidden('from', ['value' => $from ?? '']) ?>
<?php endif; ?>

<div class="product-editor__main">
    <section class="form-card form-card--details">
        <h2 class="form-card-title">Details</h2>
        <div class="cms-field">
            <?= $this->Form->control('name', ['label' => $label('Name'), 'class' => 'cms-input', 'required' => true]) ?>
        </div>
        <div class="cms-field">
            <?= $this->Form->control('description', ['type' => 'textarea', 'rows' => 6, 'label' => $label('Description'), 'class' => 'cms-textarea', 'required' => true]) ?>
        </div>
        <div class="cms-field">
            <?= $this->Form->control('story', ['type' => 'textarea', 'rows' => 4, 'label' => $label('Story'), 'class' => 'cms-textarea', 'required' => true]) ?>
        </div>
    </section>

    <section class="form-card form-card--images">
        <h2 class="form-card-title">Images<?= $isEdit ? '' : $req ?></h2>
        <?php if (!empty($product->product_images)): ?>
            <div class="existing-images-grid">
                <?php foreach ($product->product_images as $img): ?>
                    <div class="img-existing-thumb" id="img-<?= $img->id ?>">
                        <img src="<?= $this->Url->image('products/' . h($img->filename)) ?>" alt="<?= h($img->filename) ?>">
                        <button type="button" class="img-delete-btn" onclick="toggleImgDelete(this, <?= (int)$img->id ?>)"
                                title="Mark for deletion" aria-label="Mark for deletion"><?= $this->iconSvg('x') ?></button>
                        <input type="checkbox" name="delete_images[]" value="<?= (int)$img->id ?>" id="del-<?= $img->id ?>" hidden>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <div class="img-upload-zone" id="img-upload-zone">
            <input type="file" id="real-file-input" name="product_images[]" multiple accept="image/*" hidden>
            <div class="img-upload-prompt" onclick="document.getElementById('real-file-input').click()">
                Click to select images, or drag and drop them here
            </div>
            <div class="img-preview-grid" id="img-preview-grid"></div>
        </div>
    </section>

    <section class="form-card form-card--stock">
        <h2 class="form-card-title">Size &amp; stock<?= $req ?></h2>
        <div id="variants-container">
            <?php foreach ($variants as $i => $v): ?>
                <div class="variant-row">
                    <?php if ($v['id']): ?>
                        <input type="hidden" name="product_variants[<?= $i ?>][id]" value="<?= (int)$v['id'] ?>">
                    <?php endif; ?>
                    <select name="product_variants[<?= $i ?>][size]" class="size-select" onchange="onSizeChange(this)" aria-label="Size"></select>
                    <div class="new-size-div" style="display:none">
                        <input type="text" class="new-size-input" name="product_variants[<?= $i ?>][new_size_name]" placeholder="New size name">
                    </div>
                    <input type="number" name="product_variants[<?= $i ?>][stock]" value="<?= h($v['stock']) ?>" placeholder="Qty" min="0" aria-label="Stock">
                    <button type="button" onclick="removeVariantRow(this)" aria-label="Remove size"><?= $this->iconSvg('x') ?></button>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="btn-add-variant" onclick="addVariantRow()">+ Add Size</button>
        <div id="size-pills-wrap" style="display:none">
            <p class="mgmt-pills-label">Manage available sizes:</p>
            <div class="mgmt-pills" id="size-pills"></div>
        </div>
    </section>
</div>

<div class="product-editor__side">
    <section class="form-card form-card--organisation">
        <h2 class="form-card-title">Organisation</h2>
        <div class="cms-field">
            <label class="cms-label" for="type-select">Type<?= $req ?></label>
            <select name="type" id="type-select" class="cms-input" required onchange="onTypeChange(this.value)">
                <option value="">-- Select a type --</option>
                <?php foreach ($types as $key => $typeLabel): ?>
                    <option value="<?= h($key) ?>" <?= $currentType === $key ? 'selected' : '' ?>><?= h($typeLabel) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="cms-field">
            <label class="cms-label" for="categories-select">Category<?= $req ?></label>
            <select name="category_id" id="categories-select" class="cms-input" required disabled onchange="onCategoryChange(this.value)">
                <option value="">-- Select a type first --</option>
            </select>
        </div>
        <div id="new-category-input" class="cms-field" style="display:none">
            <input type="text" name="new_category_name" id="new-category-name" class="cms-input" placeholder="New category name">
        </div>
        <div id="category-pills-wrap" class="cms-field" style="display:none">
            <p class="mgmt-pills-label">Manage existing categories:</p>
            <div class="mgmt-pills" id="category-pills"></div>
        </div>
    </section>

    <section class="form-card form-card--pricing">
        <h2 class="form-card-title">Pricing</h2>
        <div class="price-row">
            <div class="cms-field">
                <?= $this->Form->control('purchase_price', ['label' => $label('Cost'), 'class' => 'cms-input', 'required' => true, 'min' => 0, 'step' => '0.01']) ?>
            </div>
            <div class="cms-field">
                <?= $this->Form->control('sale_price', ['label' => $label('Price'), 'class' => 'cms-input', 'required' => true, 'min' => 0, 'step' => '0.01']) ?>
            </div>
        </div>
        <p class="price-margin" id="price-margin" aria-live="polite"></p>
        <div class="cms-field">
            <?= $this->Form->control('supplier_email', ['label' => $label('Supplier email', false), 'class' => 'cms-input']) ?>
        </div>
    </section>
</div>
<?= $this->Form->end() ?>

<script>
    window.csrfToken         = "<?= h($this->request->getAttribute('csrfToken') ?? '') ?>";
    window.allCategories     = <?= $categoriesJson ?>;
    window.selectedCatId     = <?= json_encode($product->category_id ?? null) ?>;
    window.variantIndex      = <?= count($variants) ?>;
    window.existingSizes     = <?= json_encode(array_column($existingVariants, 'size')) ?>;
    window.urlDeleteCategory = "<?= $this->Url->build('/categories/delete/') ?>";
    window.urlRemoveSize     = "<?= $this->Url->build('/categories/') ?>";
</script>
<script src="<?= $this->Url->script('product-form') ?>"></script>
