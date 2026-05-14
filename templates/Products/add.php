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
            echo $this->Form->control('name', ['label' => ['text' => 'Name <span style="color:red">*</span>', 'escape' => false], 'required' => true]);
            ?><div class="price-row"><?php
            echo $this->Form->control('purchase_price', ['label' => ['text' => 'Purchase Price <span style="color:red">*</span>', 'escape' => false], 'required' => true]);
            echo $this->Form->control('sale_price',     ['label' => ['text' => 'Sale Price <span style="color:red">*</span>',     'escape' => false], 'required' => true]);
            ?></div><?php
            echo $this->Form->control('supplier_email', ['label' => 'Supplier Email']);
            ?>

            <!-- Type must be selected first -->
            <div class="input">
                <label for="type-select">Type <span style="color:red">*</span></label>
                <select name="type" id="type-select" required onchange="onTypeChange(this.value)">
                    <option value="">-- Select a type --</option>
                    <?php foreach ($types as $key => $label): ?>
                        <option value="<?= h($key) ?>" <?= ($preselectedType ?? '') === $key ? 'selected' : '' ?>>
                            <?= h($label) ?>
                        </option>
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
                <div id="category-pills-wrap" style="display:none;margin-top:0.6rem;">
                    <p class="mgmt-pills-label">Manage existing categories:</p>
                    <div class="mgmt-pills" id="category-pills"></div>
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
            echo $this->Form->control('story',        ['type' => 'textarea', 'label' => ['text' => 'Story <span style="color:red">*</span>',               'escape' => false], 'required' => true]);
            ?>

            <!-- Size & Stock -->
            <div class="input">
                <label>Size &amp; Stock <span style="color:red">*</span></label>
                <div id="variants-container">
                    <div class="variant-row">
                        <select name="product_variants[0][size]" class="size-select"
                                onchange="onSizeChange(this)" disabled>
                            <option value="">-- Select a category first --</option>
                        </select>
                        <div class="new-size-div" style="display:none">
                            <input type="text" class="new-size-input"
                                   name="product_variants[0][new_size_name]"
                                   placeholder="New size name">
                        </div>
                        <input type="number" name="product_variants[0][stock]" placeholder="Qty" min="0">
                        <button type="button" onclick="removeVariantRow(this)">✕</button>
                    </div>
                </div>
                <button type="button" class="btn-add-variant" onclick="addVariantRow()">+ Add Size</button>
                <div id="size-pills-wrap" style="display:none;margin-top:0.6rem;">
                    <p class="mgmt-pills-label">Manage available sizes:</p>
                    <div class="mgmt-pills" id="size-pills"></div>
                </div>
            </div>
        </fieldset>

        <?= $this->Form->button(__('Save'), ['class' => 'login-button']) ?>
        <?= $this->Form->end() ?>
    </div>
</div>

<script>
    window.csrfToken         = "<?= h($this->request->getAttribute('csrfToken') ?? '') ?>";
    window.allCategories     = <?= $categoriesJson ?>;
    window.variantIndex      = 1;
    window.urlDeleteCategory = "<?= $this->Url->build('/categories/delete/') ?>";
    window.urlRemoveSize     = "<?= $this->Url->build('/categories/') ?>";
</script>
<script src="<?= $this->Url->script('product-form') ?>"></script>
