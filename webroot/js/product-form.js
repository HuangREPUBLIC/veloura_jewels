/**
 * Shared logic for the Products add/edit form.
 * Each template provides its data via inline globals before this script loads:
 *   csrfToken       - string, CSRF token
 *   allCategories   - array of {id, name, type, sizes:[]}
 *   selectedCatId   - id|null, edit-only preselected category
 *   existingSizes   - array of strings, edit-only preselected sizes per row
 *   variantIndex    - integer, next index for new variant rows
 */
(function () {
    function init() {
        var form = document.getElementById('product-form');
        if (!form) return;

        var realInput = document.getElementById('real-file-input');
        var previewGrid = document.getElementById('img-preview-grid');
        var uploadZone = document.getElementById('img-upload-zone');
        var typeSelect = document.getElementById('type-select');
        var catSelect = document.getElementById('categories-select');

        if (!realInput || !previewGrid || !uploadZone || !typeSelect || !catSelect) return;

        var fileList = [];
        var dragSrcIndex = null;

        // Image upload + drag-to-reorder
        realInput.addEventListener('change', function () {
            Array.from(this.files).forEach(function (f) { fileList.push(f); });
            renderPreviews();
            realInput.value = '';
        });

        uploadZone.addEventListener('dragover', function (e) {
            if (Array.from(e.dataTransfer.types).indexOf('Files') !== -1) {
                e.preventDefault();
                this.classList.add('zone-hover');
            }
        });
        uploadZone.addEventListener('dragleave', function (e) {
            if (e.relatedTarget && this.contains(e.relatedTarget)) return;
            this.classList.remove('zone-hover');
        });
        uploadZone.addEventListener('drop', function (e) {
            if (Array.from(e.dataTransfer.types).indexOf('Files') !== -1) {
                e.preventDefault();
                this.classList.remove('zone-hover');
                Array.from(e.dataTransfer.files).forEach(function (f) {
                    if (f.type.startsWith('image/')) fileList.push(f);
                });
                renderPreviews();
            }
        });

        function renderPreviews() {
            previewGrid.innerHTML = '';
            fileList.forEach(function (file, i) {
                var url = URL.createObjectURL(file);
                var div = document.createElement('div');
                div.className = 'img-thumb';
                div.draggable = true;
                div.dataset.index = i;
                div.innerHTML = '<img src="' + url + '" alt="">'
                    + '<button type="button" class="img-remove-btn" data-i="' + i + '">×</button>';

                div.querySelector('.img-remove-btn').addEventListener('click', function () {
                    fileList.splice(parseInt(this.dataset.i), 1);
                    renderPreviews();
                });

                div.addEventListener('dragstart', function (e) {
                    dragSrcIndex = parseInt(this.dataset.index);
                    this.classList.add('dragging');
                    e.dataTransfer.effectAllowed = 'move';
                });
                div.addEventListener('dragover', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.dataTransfer.dropEffect = 'move';
                    document.querySelectorAll('.img-thumb').forEach(function (el) {
                        el.classList.remove('drag-over');
                    });
                    this.classList.add('drag-over');
                });
                div.addEventListener('drop', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    var targetIndex = parseInt(this.dataset.index);
                    if (dragSrcIndex !== null && dragSrcIndex !== targetIndex) {
                        var moved = fileList.splice(dragSrcIndex, 1)[0];
                        fileList.splice(targetIndex, 0, moved);
                        renderPreviews();
                    }
                });
                div.addEventListener('dragend', function () {
                    document.querySelectorAll('.img-thumb').forEach(function (el) {
                        el.classList.remove('dragging', 'drag-over');
                    });
                    dragSrcIndex = null;
                });

                previewGrid.appendChild(div);
            });
        }

        form.addEventListener('submit', function () {
            if (fileList.length > 0) {
                var dt = new DataTransfer();
                fileList.forEach(function (f) { dt.items.add(f); });
                realInput.files = dt.files;
            }
        });

        // Edit-only: toggle delete on existing image (no-op if not on edit page)
        window.toggleImgDelete = function (btn, imgId) {
            var thumb = document.getElementById('img-' + imgId);
            var checkbox = document.getElementById('del-' + imgId);
            if (!thumb || !checkbox) return;
            var marked = thumb.classList.toggle('marked-delete');
            checkbox.checked = marked;
            btn.classList.toggle('undo', marked);
            btn.title = marked ? 'Undo deletion' : 'Mark for deletion';
        };

        // Category / size logic
        function getSizes() {
            var catId = catSelect.value;
            var cat = window.allCategories.find(function (c) { return c.id == catId; });
            return (cat && cat.sizes) ? cat.sizes : [];
        }

        function getUsedSizes(excludeRow) {
            var used = [];
            document.querySelectorAll('#variants-container .variant-row').forEach(function (row) {
                if (row === excludeRow) return;
                var sel = row.querySelector('.size-select');
                if (sel && sel.value && sel.value !== '__new__') used.push(sel.value);
            });
            return used;
        }

        function buildSizeOptions(selected, excludeRow) {
            var sizes = getSizes();
            var used = getUsedSizes(excludeRow);
            if (sizes.length === 0) return '<option value="">-- Select a category first --</option>';
            var html = '<option value="">-- Size --</option>';
            sizes.forEach(function (s) {
                var disabled = (used.indexOf(s) !== -1 && s !== selected) ? ' disabled' : '';
                html += '<option value="' + s + '"' + (s === selected ? ' selected' : '') + disabled + '>' + s + '</option>';
            });
            html += '<option value="__new__">+ Add new size...</option>';
            return html;
        }

        window.onSizeChange = function (select) {
            var row = select.closest('.variant-row');
            var newSizeDiv = row.querySelector('.new-size-div');
            var newSizeInput = row.querySelector('.new-size-input');
            if (select.value === '__new__') {
                newSizeDiv.style.display = 'block';
                newSizeInput.required = true;
            } else {
                newSizeDiv.style.display = 'none';
                newSizeInput.required = false;
                newSizeInput.value = '';
                refreshSizeSelects(row);
            }
        };

        function refreshSizeSelects(changedRow) {
            document.querySelectorAll('#variants-container .variant-row').forEach(function (row) {
                if (row === changedRow) return;
                var sel = row.querySelector('.size-select');
                if (sel && sel.value !== '__new__') {
                    sel.innerHTML = buildSizeOptions(sel.value, row);
                }
            });
        }

        function updateAllSizeSelects() {
            var hasSizes = getSizes().length > 0;
            document.querySelectorAll('#variants-container .variant-row').forEach(function (row) {
                var sel = row.querySelector('.size-select');
                var newSizeDiv = row.querySelector('.new-size-div');
                var newSizeInput = row.querySelector('.new-size-input');
                if (newSizeDiv) newSizeDiv.style.display = 'none';
                if (newSizeInput) { newSizeInput.required = false; newSizeInput.value = ''; }
                if (sel) {
                    sel.disabled = !hasSizes;
                    sel.innerHTML = buildSizeOptions('', row);
                }
            });
        }

        window.addVariantRow = function () {
            var hasSizes = getSizes().length > 0;
            var row = document.createElement('div');
            row.className = 'variant-row';
            row.innerHTML =
                '<select name="product_variants[' + window.variantIndex + '][size]" class="size-select"'
                + ' onchange="onSizeChange(this)"' + (hasSizes ? '' : ' disabled') + '>'
                + buildSizeOptions('', null) + '</select>'
                + '<div class="new-size-div" style="display:none">'
                + '<input type="text" class="new-size-input"'
                + ' name="product_variants[' + window.variantIndex + '][new_size_name]"'
                + ' placeholder="New size name"></div>'
                + '<input type="number" name="product_variants[' + window.variantIndex + '][stock]"'
                + ' placeholder="Qty" min="0">'
                + '<button type="button" onclick="removeVariantRow(this)">✕</button>';
            document.getElementById('variants-container').appendChild(row);
            window.variantIndex++;
        };

        window.removeVariantRow = function (btn) {
            var row = btn.closest('.variant-row');
            row.remove();
            refreshSizeSelects(null);
        };

        window.onTypeChange = function (type) {
            populateCategories(type, null);
            updateAllSizeSelects();
            renderCategoryPills(type);
            renderSizePills();
        };

        // Unified populate: handles add (preselected = null) and edit (preselected = id)
        function populateCategories(type, preselected) {
            var newCatDiv = document.getElementById('new-category-input');
            var newCatInput = document.getElementById('new-category-name');

            newCatDiv.style.display = 'none';
            newCatInput.required = false;
            newCatInput.value = '';

            if (!type) {
                catSelect.disabled = true;
                catSelect.innerHTML = '<option value="">-- Select a type first --</option>';
                return;
            }

            catSelect.disabled = false;
            catSelect.innerHTML = '<option value="">-- Select a category --</option>';
            window.allCategories.filter(function (c) { return c.type === type; }).forEach(function (c) {
                var opt = document.createElement('option');
                opt.value = c.id;
                opt.textContent = c.name;
                if (preselected != null && c.id == preselected) opt.selected = true;
                catSelect.appendChild(opt);
            });
            var newOpt = document.createElement('option');
            newOpt.value = '__new__';
            newOpt.textContent = '+ Add new category...';
            catSelect.appendChild(newOpt);
        }

        window.onCategoryChange = function (value) {
            var newCatDiv = document.getElementById('new-category-input');
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
            renderSizePills();
        };

        // Category & size management pills
        function escHtml(str) {
            return String(str)
                .replace(/&/g, '&amp;').replace(/</g, '&lt;')
                .replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
        }

        function renderCategoryPills(type) {
            var wrap = document.getElementById('category-pills-wrap');
            var container = document.getElementById('category-pills');
            if (!wrap || !container) return;
            if (!type) { wrap.style.display = 'none'; return; }
            var cats = window.allCategories.filter(function (c) { return c.type === type; });
            if (cats.length === 0) { wrap.style.display = 'none'; return; }
            wrap.style.display = 'block';
            container.innerHTML = '';
            cats.forEach(function (cat) {
                var pill = document.createElement('span');
                pill.className = 'mgmt-pill';
                pill.innerHTML = '<span>' + escHtml(cat.name) + '</span>'
                    + '<button type="button" class="mgmt-pill-del" data-id="' + cat.id + '">✕</button>';
                pill.querySelector('.mgmt-pill-del').addEventListener('click', function () {
                    deleteCategory(cat.id);
                });
                container.appendChild(pill);
            });
        }

        function renderSizePills() {
            var catId = catSelect.value;
            var wrap = document.getElementById('size-pills-wrap');
            var container = document.getElementById('size-pills');
            if (!wrap || !container) return;
            if (!catId || catId === '__new__') { wrap.style.display = 'none'; return; }
            var cat = window.allCategories.find(function (c) { return c.id == catId; });
            if (!cat || !cat.sizes || cat.sizes.length === 0) { wrap.style.display = 'none'; return; }
            wrap.style.display = 'block';
            container.innerHTML = '';
            cat.sizes.forEach(function (size) {
                var pill = document.createElement('span');
                pill.className = 'mgmt-pill';
                pill.innerHTML = '<span>' + escHtml(size) + '</span>'
                    + '<button type="button" class="mgmt-pill-del">✕</button>';
                pill.querySelector('.mgmt-pill-del').addEventListener('click', function () {
                    removeSize(cat.id, size);
                });
                container.appendChild(pill);
            });
        }

        function deleteCategory(id) {
            if (!confirm('Delete this category? This cannot be undone.')) return;
            fetch(window.urlDeleteCategory + id, {
                method: 'POST',
                headers: { 'X-CSRF-Token': window.csrfToken },
            })
            .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, data: d }; }); })
            .then(function (res) {
                if (!res.ok) { alert(res.data.error || 'Could not delete category.'); return; }
                var idx = window.allCategories.findIndex(function (c) { return c.id == id; });
                if (idx !== -1) window.allCategories.splice(idx, 1);
                var currentType = typeSelect.value;
                if (String(catSelect.value) === String(id)) {
                    window.onCategoryChange('');
                }
                populateCategories(currentType, null);
                renderCategoryPills(currentType);
            })
            .catch(function () { alert('Request failed.'); });
        }

        function removeSize(catId, size) {
            if (!confirm('Remove "' + size + '"? Any product variants using this size will also be deleted.')) return;
            var fd = new FormData();
            fd.append('size', size);
            fetch(window.urlRemoveSize + catId + '/remove-size', {
                method: 'POST',
                headers: { 'X-CSRF-Token': window.csrfToken },
                body: fd,
            })
            .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, data: d }; }); })
            .then(function (res) {
                if (!res.ok) { alert(res.data.error || 'Could not remove size.'); return; }
                var cat = window.allCategories.find(function (c) { return c.id == catId; });
                if (cat) cat.sizes = res.data.sizes;
                updateAllSizeSelects();
                renderSizePills();
            })
            .catch(function () { alert('Request failed.'); });
        }

        // Init: pre-populate selects on edit, otherwise just render pills
        var currentType = typeSelect.value;
        var preselectedCat = (typeof window.selectedCatId !== 'undefined') ? window.selectedCatId : null;

        if (currentType) populateCategories(currentType, preselectedCat);

        // Edit page: pre-populate size selects with existing variant sizes (sequential
        // so each row correctly sees what previous rows have already claimed).
        if (window.existingSizes && window.existingSizes.length) {
            document.querySelectorAll('#variants-container .variant-row').forEach(function (row, i) {
                var sel = row.querySelector('.size-select');
                if (sel) sel.innerHTML = buildSizeOptions(window.existingSizes[i] || '', row);
            });
        } else {
            updateAllSizeSelects();
        }

        renderCategoryPills(currentType);
        renderSizePills();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
