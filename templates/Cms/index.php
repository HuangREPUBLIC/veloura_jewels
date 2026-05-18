<?php
/**
 * @var \App\View\AppView $this
 * @var iterable $pages
 * @var \App\Model\Entity\CmsPage $currentPage
 * @var array $contentRows
 * @var string $pageSlug
 * @var array|null $faqItems
 */

$this->assign('title', 'Content Management');
$this->Html->css('admincontact', ['block' => true]);

$saveUrl    = ['action' => 'index', $pageSlug];
$faqSaveUrl = ['action' => 'faqItemSave'];
?>

<div class="admin-wrapper">
    <div class="siteSettings index content">
        <?= $this->Html->link('← Back', ['controller' => 'Users', 'action' => 'dashboard'], ['class' => 'back-link']) ?>

        <?= $this->Flash->render() ?>

        <?= $this->Form->create(null, ['url' => $saveUrl, 'method' => 'post', 'enctype' => 'multipart/form-data']) ?>

        <div class="page-header-row">
            <h3 class="page-title">Content Management: <?= h($currentPage->title) ?></h3>
            <div class="cms-header-right">
                <select class="cms-page-select" onchange="window.location=this.value">
                    <?php foreach ($pages as $p): ?>
                        <option
                            value="<?= h($this->Url->build(['action' => 'index', $p->slug])) ?>"
                            <?= $p->slug === $pageSlug ? 'selected' : '' ?>>
                            <?= h($p->title) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?= $this->Form->button('Save', ['class' => 'btn-new-product']) ?>
            </div>
        </div>

        <div class="cms-group">
            <div class="cms-group-fields">
                <?php foreach ($contentRows as $row): ?>
                    <div class="cms-field">
                        <label class="cms-label" for="field-<?= h($row->content_key) ?>"><?= h($row->label) ?></label>
                        <?php if ($row->content_type === 'textarea'): ?>
                            <textarea
                                id="field-<?= h($row->content_key) ?>"
                                name="<?= h($row->content_key) ?>"
                                class="cms-textarea"
                                rows="3"><?= h($row->content_value) ?></textarea>
                        <?php elseif ($row->content_type === 'image'): ?>
                            <?php if (!empty($row->content_value)): ?>
                                <?= $this->Html->image(h($row->content_value), ['class' => 'cms-image-preview']) ?>
                            <?php endif; ?>

                            <input
                                id="field-<?= h($row->content_key) ?>"
                                type="file"
                                name="<?= h($row->content_key) ?>"
                                accept="image/*"
                                class="cms-input">

                        <?php elseif ($row->content_type === 'video'): ?>
                            <?php if (!empty($row->content_value)): ?>
                                <video class="cms-image-preview" controls muted>
                                    <source src="<?= $this->Url->build('/img/' . h($row->content_value)) ?>" type="video/mp4">
                                </video>
                            <?php endif; ?>
                            <input
                                id="field-<?= h($row->content_key) ?>"
                                type="file"
                                name="<?= h($row->content_key) ?>"
                                accept="video/mp4,video/webm"
                                class="cms-input">

                        <?php else: ?>
                            <input
                                id="field-<?= h($row->content_key) ?>"
                                type="text"
                                name="<?= h($row->content_key) ?>"
                                value="<?= h($row->content_value) ?>"
                                class="cms-input">
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <?= $this->Form->end() ?>

        <?php if ($faqItems !== null): ?>
        <div class="cms-faq-section">
            <h4 class="cms-group-label cms-faq-section-title">FAQ Items</h4>

            <?php foreach ($faqItems as $item): ?>
            <div class="cms-faq-item" id="faq-item-<?= $item->id ?>">
                <div class="cms-faq-item-row">
                    <span class="cms-faq-question-text"><?= h($item->question) ?></span>
                    <div class="cms-faq-item-actions">
                        <button type="button" class="btn-cms-edit" onclick="toggleFaqEdit(<?= $item->id ?>)">Edit</button>
                        <?= $this->Form->create(null, ['url' => ['action' => 'faqItemDelete', $item->id], 'method' => 'post', 'class' => 'cms-faq-delete-form']) ?>
                        <input type="hidden" name="page_slug" value="<?= h($pageSlug) ?>">
                        <button type="submit" class="btn-cms-delete" onclick="return confirm('Delete this FAQ item?')">Delete</button>
                        <?= $this->Form->end() ?>
                    </div>
                </div>
                <div class="cms-faq-edit-form" id="faq-edit-<?= $item->id ?>">
                    <?= $this->Form->create(null, ['url' => $faqSaveUrl, 'method' => 'post']) ?>
                    <input type="hidden" name="id" value="<?= $item->id ?>">
                    <input type="hidden" name="page_slug" value="<?= h($pageSlug) ?>">
                    <div class="cms-field">
                        <label class="cms-label">Question</label>
                        <input type="text" name="question" value="<?= h($item->question) ?>" class="cms-input">
                    </div>
                    <div class="cms-field">
                        <label class="cms-label">Answer</label>
                        <textarea name="answer" class="cms-textarea" rows="3"><?= h($item->answer) ?></textarea>
                    </div>
                    <div class="cms-field cms-field--inline">
                        <label class="cms-label">Sort Order</label>
                        <input type="number" name="sort_order" value="<?= (int)$item->sort_order ?>" class="cms-input cms-input--short">
                    </div>
                    <div class="cms-faq-edit-actions">
                        <button type="submit" class="btn-new-product">Save Changes</button>
                        <button type="button" class="btn-cms-cancel" onclick="toggleFaqEdit(<?= $item->id ?>)">Cancel</button>
                    </div>
                    <?= $this->Form->end() ?>
                </div>
            </div>
            <?php endforeach; ?>

            <div class="cms-faq-add">
                <h4 class="cms-group-label">Add New FAQ Item</h4>
                <?= $this->Form->create(null, ['url' => $faqSaveUrl, 'method' => 'post']) ?>
                <input type="hidden" name="page_slug" value="<?= h($pageSlug) ?>">
                <div class="cms-group-fields">
                    <div class="cms-field">
                        <label class="cms-label">Question</label>
                        <input type="text" name="question" class="cms-input">
                    </div>
                    <div class="cms-field">
                        <label class="cms-label">Answer</label>
                        <textarea name="answer" class="cms-textarea" rows="3"></textarea>
                    </div>
                    <div class="cms-field cms-field--inline">
                        <label class="cms-label">Sort Order</label>
                        <input type="number" name="sort_order" value="<?= count($faqItems) + 1 ?>" class="cms-input cms-input--short">
                    </div>
                </div>
                <button type="submit" class="btn-new-product cms-faq-add-btn">Add FAQ Item</button>
                <?= $this->Form->end() ?>
            </div>
        </div>

        <script>
        function toggleFaqEdit(id) {
            const form = document.getElementById('faq-edit-' + id);
            form.classList.toggle('is-open');
        }
        </script>
        <?php endif; ?>
    </div>
</div>
