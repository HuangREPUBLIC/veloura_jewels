<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Product $product
 * @var string[]|\Cake\Collection\CollectionInterface $categories
 * @var string $categoriesJson
 * @var array $types
 */
$this->assign('crumbRecord', h($product->name));

$currentType   = $product->category->type ?? $product->type ?? '';
$selectedCatId = $product->category_id ?? null;

// Collect existing variant sizes for JS pre-selection.
$existingVariants = [];
foreach ($product->product_variants as $v) {
    $existingVariants[] = ['id' => (int)$v->id, 'size' => $v->size, 'stock' => (int)$v->stock];
}
?>
<div class="page-header-row">
    <div>
        <p class="cms-eyebrow">Products</p>
        <h2 class="page-title"><?= h($product->name) ?></h2>
    </div>
    <div class="cms-header-right">
        <button type="submit" form="product-form" class="btn-new-product"><?= __('Save') ?></button>
    </div>
</div>

<?= $this->Flash->render() ?>

<?= $this->Form->create($product, ['enctype' => 'multipart/form-data', 'id' => 'product-form']) ?>
<?= $this->Form->hidden('from', ['value' => $from ?? '']) ?>
<div class="cms-group">
    <div class="cms-group-fields">
        <div class="cms-field">
            <?= $this->Form->control('name', ['label' => ['text' => 'Name <span style="color:red">*</span>', 'escape' => false, 'class' => 'cms-label'], 'class' => 'cms-input', 'required' => true]) ?>
        </div>
        <div class="price-row">
            <div class="cms-field">
                <?= $this->Form->control('purchase_price', ['label' => ['text' => 'Purchase Price <span style="color:red">*</span>', 'escape' => false, 'class' => 'cms-label'], 'class' => 'cms-input', 'required' => true]) ?>
            </div>
            <div class="cms-field">
                <?= $this->Form->control('sale_price', ['label' => ['text' => 'Sale Price <span style="color:red">*</span>', 'escape' => false, 'class' => 'cms-label'], 'class' => 'cms-input', 'required' => true]) ?>
            </div>
        </div>
        <div class="cms-field">
            <?= $this->Form->control('supplier_email', ['label' => ['text' => 'Supplier Email', 'class' => 'cms-label'], 'class' => 'cms-input']) ?>
        </div>

        <div class="cms-field-row">
            <!-- Type -->
            <div class="cms-field">
                <label class="cms-label" for="type-select">Type <span style="color:red">*</span></label>
                <select name="type" id="type-select" class="cms-input" required onchange="onTypeChange(this.value)">
                    <option value="">-- Select a type --</option>
                    <?php foreach ($types as $key => $label): ?>
                        <option value="<?= h($key) ?>" <?= $currentType === $key ? 'selected' : '' ?>>
                            <?= h($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Category: filtered by type -->
            <div class="cms-field">
                <label class="cms-label" for="categories-select">Category <span style="color:red">*</span></label>
                <select name="category_id" id="categories-select" class="cms-input" required
                    <?= empty($currentType) ? 'disabled' : '' ?>
                        onchange="onCategoryChange(this.value)">
                    <?php if (empty($currentType)): ?>
                        <option value="">-- Select a type first --</option>
                    <?php endif; ?>
                </select>
            </div>
        </div>

        <div id="new-category-input" class="cms-field" style="display:none;">
            <input type="text" name="new_category_name" id="new-category-name" class="cms-input"
                   placeholder="New category name">
        </div>
        <div id="category-pills-wrap" class="cms-field" style="display:none;">
            <p class="mgmt-pills-label">Manage existing categories:</p>
            <div class="mgmt-pills" id="category-pills"></div>
        </div>

        <!-- Existing images: click × to mark for deletion, click again to undo -->
        <?php if (!empty($product->product_images)): ?>
            <div class="cms-field">
                <label class="cms-label">Current Images</label>
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

        <!-- Add new images -->
        <div class="cms-field">
            <label class="cms-label">Add New Images</label>
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

        <div class="cms-field">
            <?= $this->Form->control('description', ['type' => 'textarea', 'label' => ['text' => 'Product Description <span style="color:red">*</span>', 'escape' => false, 'class' => 'cms-label'], 'class' => 'cms-textarea', 'required' => true]) ?>
        </div>
        <div class="cms-field">
            <?= $this->Form->control('story', ['type' => 'textarea', 'label' => ['text' => 'Story <span style="color:red">*</span>', 'escape' => false, 'class' => 'cms-label'], 'class' => 'cms-textarea', 'required' => true]) ?>
        </div>

        <!-- Size & Stock -->
        <div class="cms-field">
            <label class="cms-label">Size &amp; Stock <span style="color:red">*</span></label>
            <div id="variants-container">
                <?php foreach ($existingVariants as $i => $v): ?>
                    <div class="variant-row">
                        <input type="hidden" name="product_variants[<?= $i ?>][id]"
                               value="<?= $v['id'] ?>">
                        <select name="product_variants[<?= $i ?>][size]" class="size-select"
                                onchange="onSizeChange(this)"></select>
                        <div class="new-size-div" style="display:none">
                            <input type="text" class="new-size-input"
                                   name="product_variants[<?= $i ?>][new_size_name]"
                                   placeholder="New size name">
                        </div>
                        <input type="number" name="product_variants[<?= $i ?>][stock]"
                               value="<?= $v['stock'] ?>" placeholder="Qty" min="0">
                        <button type="button" onclick="removeVariantRow(this)">✕</button>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="btn-add-variant" onclick="addVariantRow()">+ Add Size</button>
            <div id="size-pills-wrap" style="display:none;margin-top:0.6rem;">
                <p class="mgmt-pills-label">Manage available sizes:</p>
                <div class="mgmt-pills" id="size-pills"></div>
            </div>
        </div>
    </div>
</div>
<?= $this->Form->end() ?>

<script>
    window.csrfToken         = "<?= h($this->request->getAttribute('csrfToken') ?? '') ?>";
    window.allCategories     = <?= $categoriesJson ?>;
    window.selectedCatId     = <?= json_encode($selectedCatId) ?>;
    window.variantIndex      = <?= count($existingVariants) ?>;
    window.existingSizes     = <?= json_encode(array_column($existingVariants, 'size')) ?>;
    window.urlDeleteCategory = "<?= $this->Url->build('/categories/delete/') ?>";
    window.urlRemoveSize     = "<?= $this->Url->build('/categories/') ?>";
</script>
<script src="<?= $this->Url->script('product-form') ?>"></script>
