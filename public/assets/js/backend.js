(function () {
    "use strict";

    function initAttributeEditModal() {
        var editForm = document.getElementById("editAttributeForm");

        if (!editForm) {
            return;
        }

        var nameInput = document.getElementById("edit_attribute_name");
        var valuesInput = document.getElementById("edit_attribute_values");
        var sortOrderInput = document.getElementById("edit_attribute_sort_order");
        var activeInput = document.getElementById("edit_attribute_active");

        document.querySelectorAll(".edit-attribute-btn").forEach(function (button) {
            button.addEventListener("click", function () {
                editForm.setAttribute("action", button.dataset.action || "");

                if (nameInput) {
                    nameInput.value = button.dataset.name || "";
                }

                if (valuesInput) {
                    valuesInput.value = button.dataset.values || "";
                }

                if (sortOrderInput) {
                    sortOrderInput.value = button.dataset.sortOrder || 0;
                }

                if (activeInput) {
                    activeInput.checked = button.dataset.isActive === "1";
                }
            });
        });
    }

    // Brand create page js
    function initBrandCreatePage() {
        var brandNameInput = document.querySelector("[data-brand-slug-source]");
        var slugField = document.getElementById("slugField");
        var logoInput = document.querySelector("[data-brand-logo-input]");
        var logoPreview = document.getElementById("logoPreview");

        if (brandNameInput && slugField) {
            brandNameInput.addEventListener("input", function () {
                slugField.value = brandNameInput.value
                    .toLowerCase()
                    .trim()
                    .replace(/[^a-z0-9]+/g, "-")
                    .replace(/^-|-$/g, "");
            });
        }

        if (logoInput && logoPreview) {
            logoInput.addEventListener("change", function () {
                if (!logoInput.files || !logoInput.files[0]) {
                    logoPreview.removeAttribute("src");
                    logoPreview.style.display = "none";
                    return;
                }

                var reader = new FileReader();

                reader.addEventListener("load", function (event) {
                    logoPreview.src = event.target.result;
                    logoPreview.style.display = "block";
                });

                reader.readAsDataURL(logoInput.files[0]);
            });
        }
    }

    // Brand edit page js
    function initBrandEditPage() {
        var logoInput = document.querySelector("[data-brand-edit-logo-input]");
        var logoPreview = document.getElementById("logoPreview");

        if (!logoInput || !logoPreview) {
            return;
        }

        logoInput.addEventListener("change", function () {
            if (!logoInput.files || !logoInput.files[0]) {
                logoPreview.removeAttribute("src");
                logoPreview.style.display = "none";
                return;
            }

            var reader = new FileReader();

            reader.addEventListener("load", function (event) {
                logoPreview.src = event.target.result;
                logoPreview.style.display = "block";
            });

            reader.readAsDataURL(logoInput.files[0]);
        });
    }

    // Category create page js
    function initCategoryCreatePage() {
        var categoryNameInput = document.querySelector("[data-category-slug-source]");
        var slugField = document.getElementById("slugField");

        if (!categoryNameInput || !slugField) {
            return;
        }

        categoryNameInput.addEventListener("input", function () {
            slugField.value = categoryNameInput.value
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9]+/g, "-")
                .replace(/^-|-$/g, "");
        });
    }

    // Tags create page js
    function initTagsCreatePage() {
        var tagNameInput = document.querySelector("[data-tag-create-name]");
        var slugField = document.querySelector("[data-tag-create-slug]");
        var tagPreview = document.querySelector("[data-tag-create-preview]");

        if (!tagNameInput || !slugField) {
            return;
        }

        tagNameInput.addEventListener("input", function () {
            slugField.value = tagNameInput.value
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9]+/g, "-")
                .replace(/^-|-$/g, "");

            if (tagPreview) {
                tagPreview.textContent = tagNameInput.value;
            }
        });
    }

    // Tags edit page js
    function initTagsEditPage() {
        var nameInput = document.querySelector("[data-tag-edit-name]");
        var slugInput = document.querySelector("[data-tag-edit-slug]");

        if (!nameInput || !slugInput) {
            return;
        }

        nameInput.addEventListener("input", function () {
            if (!slugInput.dataset.manual) {
                slugInput.value = nameInput.value
                    .toLowerCase()
                    .trim()
                    .replace(/[^a-z0-9\s-]/g, "")
                    .replace(/\s+/g, "-");
            }
        });

        slugInput.addEventListener("input", function () {
            slugInput.dataset.manual = "true";
        });
    }

    // Order show page js
    function initOrderShowPage() {
        var saveButton = document.querySelector("[data-order-status-save]");
        var statusSelect = document.querySelector("[data-order-status-select]");
        var statusMessage = document.querySelector("[data-order-status-message]");
        var csrfToken = document.querySelector('meta[name="csrf-token"]');

        if (!saveButton || !statusSelect || !statusMessage || !saveButton.dataset.statusUrl) {
            return;
        }

        saveButton.addEventListener("click", function () {
            saveButton.disabled = true;
            saveButton.textContent = "Saving...";

            fetch(saveButton.dataset.statusUrl, {
                method: "PATCH",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken ? csrfToken.content : "",
                    "Accept": "application/json"
                },
                body: JSON.stringify({
                    status: statusSelect.value
                })
            })
                .then(function (response) {
                    if (!response.ok) {
                        throw new Error("HTTP " + response.status);
                    }

                    return response.json();
                })
                .then(function (data) {
                    saveButton.disabled = false;
                    saveButton.textContent = "Save";
                    statusMessage.style.display = "inline";
                    statusMessage.style.color = "#198754";
                    statusMessage.textContent = "Saved: " + data.message;

                    setTimeout(function () {
                        statusMessage.style.display = "none";
                    }, 3000);
                })
                .catch(function (error) {
                    saveButton.disabled = false;
                    saveButton.textContent = "Save";
                    statusMessage.style.display = "inline";
                    statusMessage.style.color = "#dc3545";
                    statusMessage.textContent = "Update failed: " + error.message;
                });
        });
    }

    // Pages create page js
    function initPagesCreatePage() {
        var titleInput = document.querySelector("[data-page-create-title]");
        var slugInput = document.querySelector("[data-page-create-slug]");
        var counterInputs = document.querySelectorAll("[data-page-create-counter-input]");

        if (titleInput && slugInput) {
            titleInput.addEventListener("input", function () {
                slugInput.value = titleInput.value
                    .toLowerCase()
                    .trim()
                    .replace(/[^\w\s-]/g, "")
                    .replace(/\s+/g, "-")
                    .replace(/-+/g, "-")
                    .replace(/^-+|-+$/g, "");
            });
        }

        counterInputs.forEach(function (input) {
            var counter = document.getElementById(input.dataset.counterTarget);
            var maxLength = Number(input.dataset.counterMax || input.getAttribute("maxlength") || 0);

            if (!counter || !maxLength) {
                return;
            }

            function updateCounter() {
                var count = input.value.length;

                counter.textContent = count + " / " + maxLength;
                counter.classList.toggle("warning", count > maxLength);
            }

            input.addEventListener("input", updateCounter);
            updateCounter();
        });
    }

    // Pages edit page js
    function initPagesEditPage() {
        var counterInputs = document.querySelectorAll("[data-page-edit-counter-input]");

        counterInputs.forEach(function (input) {
            var counter = document.getElementById(input.dataset.counterTarget);

            if (!counter) {
                return;
            }

            function updateCounter() {
                counter.textContent = input.value.length;
            }

            input.addEventListener("input", updateCounter);
            updateCounter();
        });
    }

    // Product create page js
    function initProductCreatePage() {
        var builder = document.querySelector("[data-product-variation-builder]");
        var csrfToken = document.querySelector('meta[name="csrf-token"]');

        function getCsrfToken() {
            return csrfToken ? csrfToken.content : "";
        }

        function escapeAttr(value) {
            return String(value || "")
                .replace(/&/g, "&amp;")
                .replace(/"/g, "&quot;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;");
        }

        function updateBuilderEmptyState() {
            var list = document.getElementById("variationsTableBody");
            var emptyRow = document.getElementById("emptyVariationsRow");
            var countLabel = document.getElementById("variationCountLabel");
            var total = list ? list.querySelectorAll(".variation-row").length : 0;

            if (emptyRow) {
                emptyRow.classList.toggle("d-none", total > 0);
            }

            if (countLabel) {
                countLabel.textContent = total;
            }
        }

        if (builder) {
            var panels = builder.querySelectorAll(".wc-tab-panel");
            var tabs = builder.querySelectorAll(".wc-data-tab");
            var list = document.getElementById("variationsTableBody");
            var initialIndex = Number(builder.dataset.initialVarIndex || 0);
            var varIndex = initialIndex;

            function activateTab(panelId) {
                tabs.forEach(function (tab) {
                    tab.classList.toggle("active", tab.dataset.wcTab === panelId);
                });

                panels.forEach(function (panel) {
                    panel.classList.toggle("active", panel.id === panelId);
                });
            }

            function slugPart(value) {
                return String(value || "")
                    .trim()
                    .replace(/[^a-zA-Z0-9]+/g, "-")
                    .replace(/^-|-$/g, "")
                    .toUpperCase();
            }

            function selectedAttributeSets() {
                return Array.from(builder.querySelectorAll(".variation-attribute-toggle:checked")).map(function (toggle) {
                    var card = toggle.closest(".attribute-card");
                    var values = card ? Array.from(card.querySelectorAll(".variation-value-toggle:checked")).map(function (input) {
                        return input.value;
                    }) : [];

                    return {
                        name: toggle.dataset.attributeName,
                        values: values
                    };
                }).filter(function (attribute) {
                    return attribute.values.length > 0;
                });
            }

            function combinations(sets) {
                return sets.reduce(function (carry, set) {
                    var next = [];

                    carry.forEach(function (combo) {
                        set.values.forEach(function (value) {
                            var merged = Object.assign({}, combo);

                            merged[set.name] = value;
                            next.push(merged);
                        });
                    });

                    return next;
                }, [{}]);
            }

            function variationName(attributes) {
                return Object.keys(attributes).map(function (name) {
                    return name + ": " + attributes[name];
                }).join(" / ");
            }

            function addVariationRow(attributes) {
                if (!list) {
                    return;
                }

                var index = varIndex++;
                var name = variationName(attributes);
                var productSkuInput = document.querySelector('[name="sku"]');
                var basePriceInput = document.querySelector('[name="base_price"]');
                var unitInput = document.querySelector('[name="unit"]');
                var productSku = productSkuInput ? productSkuInput.value : "PRODUCT";
                var basePrice = basePriceInput ? basePriceInput.value : "";
                var unit = unitInput ? unitInput.value : "";
                var sku = slugPart(productSku + "-" + Object.values(attributes).join("-"));
                var hiddenAttributes = Object.keys(attributes).map(function (attributeName) {
                    return '<input type="hidden" name="variations[' + index + '][attributes][' + escapeAttr(attributeName) + ']" value="' + escapeAttr(attributes[attributeName]) + '">';
                }).join("");
                var row = document.createElement("div");

                row.className = "variation-row is-open";
                row.innerHTML =
                    '<div class="wc-variation-heading">' +
                        '<input type="hidden" name="variations[' + index + '][id]" value="">' +
                        '<input type="hidden" name="variations[' + index + '][name]" value="' + escapeAttr(name) + '">' +
                        hiddenAttributes +
                        '<button type="button" class="wc-variation-title">' +
                            '<iconify-icon icon="lucide:grip-vertical"></iconify-icon>' +
                            '<span>#New ' + escapeAttr(name) + '</span>' +
                        '</button>' +
                        '<div class="wc-variation-actions">' +
                            '<span class="badge bg-success-100 text-success-600">Enabled</span>' +
                            '<button type="button" class="btn btn-sm btn-outline-danger-600 remove-variation">Remove</button>' +
                            '<button type="button" class="wc-row-toggle" aria-label="Toggle variation"><iconify-icon icon="lucide:chevron-down"></iconify-icon></button>' +
                        '</div>' +
                    '</div>' +
                    '<div class="wc-variation-body">' +
                        '<div class="row g-3">' +
                            '<div class="col-md-6"><label class="form-label fw-bold">SKU</label><input type="text" name="variations[' + index + '][sku]" class="form-control" value="' + escapeAttr(sku) + '" placeholder="Auto generated if empty"></div>' +
                            '<div class="col-md-3"><label class="form-label fw-bold">MRP / Compare (INR)</label><input type="number" step="0.01" min="0" name="variations[' + index + '][compare_at_price]" class="form-control" placeholder="MRP"><input type="hidden" name="variations[' + index + '][cost_price]" value=""></div>' +
                            '<div class="col-md-3"><label class="form-label fw-bold">Selling Price (INR)</label><input type="number" step="0.01" min="0" name="variations[' + index + '][price]" class="form-control" value="' + escapeAttr(basePrice) + '" required></div>' +
                            '<div class="col-md-4"><label class="form-label fw-bold">Stock Quantity</label><input type="hidden" name="variations[' + index + '][track_stock]" value="0"><input type="hidden" name="variations[' + index + '][is_in_stock]" value="0"><input type="number" min="0" name="variations[' + index + '][stock_qty]" class="form-control" value="0"></div>' +
                            '<input type="hidden" name="variations[' + index + '][unit]" value="' + escapeAttr(unit) + '">' +
                            '<div class="col-md-8"><label class="form-label fw-bold">Variation Settings</label><div class="wc-checkbox-grid">' +
                                '<input type="hidden" name="variations[' + index + '][track_stock]" value="1">' +
                                '<input type="hidden" name="variations[' + index + '][is_in_stock]" value="1">' +
                                '<input type="hidden" name="variations[' + index + '][is_active]" value="0">' +
                                '<input type="hidden" class="variation-default-hidden" name="variations[' + index + '][is_default]" value="0">' +
                                '<label><input class="form-check-input variation-default-radio" type="radio" name="default_variation_row" value="' + index + '"> Default variation</label>' +
                                '<label><input class="form-check-input" type="checkbox" name="variations[' + index + '][is_active]" value="1" checked> Enabled</label>' +
                            '</div></div>' +
                        '</div>' +
                    '</div>';

                list.appendChild(row);
                updateBuilderEmptyState();
            }

            tabs.forEach(function (tab) {
                tab.addEventListener("click", function () {
                    activateTab(tab.dataset.wcTab);
                });
            });

            var goToVariationsButton = document.getElementById("goToVariationsBtn");
            var generateButton = document.getElementById("generateVariationsBtn");

            if (goToVariationsButton) {
                goToVariationsButton.addEventListener("click", function () {
                    activateTab("variationsPanel");
                });
            }

            builder.querySelectorAll(".wc-attribute-heading").forEach(function (button) {
                button.addEventListener("click", function () {
                    var item = button.closest(".wc-attribute-item");

                    if (item) {
                        item.classList.toggle("is-open");
                    }
                });
            });

            builder.addEventListener("click", function (event) {
                var toggle = event.target.closest(".wc-row-toggle, .wc-variation-title");
                var existingRemove = event.target.closest("[data-existing-variation-remove]");
                var removeButton = event.target.closest(".remove-variation");

                if (toggle) {
                    var toggleRow = toggle.closest(".variation-row");

                    if (toggleRow) {
                        toggleRow.classList.toggle("is-open");
                    }
                }

                if (existingRemove) {
                    if (!confirm("Remove this variation?")) {
                        return;
                    }

                    fetch("/dashboard/product-variations/" + existingRemove.dataset.variationId, {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": getCsrfToken(),
                            "Accept": "application/json"
                        }
                    })
                        .then(function (response) {
                            return response.json();
                        })
                        .then(function (data) {
                            if (data.success) {
                                var row = existingRemove.closest(".variation-row");

                                if (row) {
                                    row.remove();
                                }

                                updateBuilderEmptyState();
                            }
                        })
                        .catch(function () {
                            alert("Error removing variation. Try again.");
                        });
                    return;
                }

                if (removeButton) {
                    var rowToRemove = removeButton.closest(".variation-row");

                    if (rowToRemove) {
                        rowToRemove.remove();
                    }

                    updateBuilderEmptyState();
                }
            });

            if (generateButton) {
                generateButton.addEventListener("click", function () {
                    var sets = selectedAttributeSets();

                    if (!sets.length) {
                        alert("Select at least one attribute value.");
                        return;
                    }

                    combinations(sets).forEach(addVariationRow);
                    activateTab("variationsPanel");
                });
            }

            builder.addEventListener("change", function (event) {
                var hidden;

                if (!event.target.classList.contains("variation-default-radio")) {
                    return;
                }

                builder.querySelectorAll(".variation-default-hidden").forEach(function (input) {
                    input.value = "0";
                });

                hidden = event.target.closest(".wc-checkbox-grid");
                hidden = hidden ? hidden.querySelector(".variation-default-hidden") : null;

                if (hidden) {
                    hidden.value = "1";
                }
            });

            updateBuilderEmptyState();
        }

        var featuredInput = document.querySelector("[data-featured-image-input]");
        var featuredClear = document.querySelector("[data-featured-preview-clear]");
        var galleryInput = document.querySelector("[data-gallery-input]");

        if (featuredInput) {
            featuredInput.addEventListener("change", function () {
                var preview = document.getElementById(featuredInput.dataset.previewTarget);
                var wrap = document.getElementById("featuredPreviewWrap");

                if (!preview || !featuredInput.files || !featuredInput.files[0]) {
                    return;
                }

                var reader = new FileReader();

                reader.addEventListener("load", function (event) {
                    preview.src = event.target.result;

                    if (wrap) {
                        wrap.style.display = "block";
                    }
                });

                reader.readAsDataURL(featuredInput.files[0]);
            });
        }

        if (featuredClear) {
            featuredClear.addEventListener("click", function () {
                var input = document.getElementById("featuredImageInput");
                var wrap = document.getElementById("featuredPreviewWrap");
                var preview = document.getElementById("featuredPreview");

                if (input) {
                    input.value = "";
                }

                if (preview) {
                    preview.removeAttribute("src");
                }

                if (wrap) {
                    wrap.style.display = "none";
                }
            });
        }

        document.querySelectorAll("[data-featured-image-delete]").forEach(function (button) {
            button.addEventListener("click", function () {
                if (!confirm("Remove featured image?")) {
                    return;
                }

                fetch("/dashboard/products/" + button.dataset.productId + "/featured-image", {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": getCsrfToken(),
                        "Accept": "application/json"
                    }
                })
                    .then(function (response) {
                        return response.json();
                    })
                    .then(function (data) {
                        if (data.success) {
                            var wrap = button.closest(".featured-img-wrap");

                            if (wrap) {
                                wrap.remove();
                            }
                        }
                    })
                    .catch(function () {
                        alert("Error removing featured image. Try again.");
                    });
            });
        });

        function previewGallery(input) {
            var container = document.getElementById("galleryPreviews");

            if (!container) {
                return;
            }

            container.innerHTML = "";

            Array.from(input.files || []).forEach(function (file, index) {
                var reader = new FileReader();

                reader.addEventListener("load", function (event) {
                    var wrap = document.createElement("div");
                    var img = document.createElement("img");
                    var remove = document.createElement("button");

                    wrap.className = "existing-img";
                    wrap.dataset.previewIndex = index;
                    img.src = event.target.result;
                    img.className = "preview-thumb";
                    remove.type = "button";
                    remove.className = "del-img";
                    remove.title = "Remove";
                    remove.textContent = "x";
                    remove.dataset.galleryPreviewRemove = String(index);
                    wrap.appendChild(img);
                    wrap.appendChild(remove);
                    container.appendChild(wrap);
                });

                reader.readAsDataURL(file);
            });
        }

        if (galleryInput) {
            galleryInput.addEventListener("change", function () {
                previewGallery(galleryInput);
            });
        }

        document.addEventListener("click", function (event) {
            var previewRemove = event.target.closest("[data-gallery-preview-remove]");
            var existingDelete = event.target.closest("[data-gallery-image-delete]");

            if (previewRemove && galleryInput) {
                var indexToRemove = Number(previewRemove.dataset.galleryPreviewRemove);
                var transfer = new DataTransfer();

                Array.from(galleryInput.files || []).forEach(function (file, index) {
                    if (index !== indexToRemove) {
                        transfer.items.add(file);
                    }
                });

                galleryInput.files = transfer.files;
                previewGallery(galleryInput);
            }

            if (existingDelete) {
                if (!confirm("Remove this image?")) {
                    return;
                }

                fetch("/dashboard/product-images/" + existingDelete.dataset.imageId, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": getCsrfToken(),
                        "Accept": "application/json"
                    }
                })
                    .then(function (response) {
                        return response.json();
                    })
                    .then(function (data) {
                        var existingImage;

                        if (data.success) {
                            existingImage = document.getElementById("existingImg_" + existingDelete.dataset.imageId);

                            if (existingImage) {
                                existingImage.remove();
                            }
                        }
                    })
                    .catch(function () {
                        alert("Error removing image. Try again.");
                    });
            }
        });

        var tagInput = document.querySelector("[data-product-tag-input]");
        var tagAddButton = document.querySelector("[data-product-tag-add]");
        var tagPills = document.querySelector("[data-product-tag-pills]");
        var tagInputs = document.getElementById("tagInputsContainer");
        var existingTags = document.querySelector("[data-product-existing-tags]");
        var activeTags = new Set();

        if (existingTags) {
            try {
                JSON.parse(existingTags.dataset.productExistingTags || "[]").forEach(function (tag) {
                    activeTags.add(tag);
                });
            } catch (error) {
                activeTags = new Set();
            }
        }

        function renderTags() {
            if (!tagPills || !tagInputs) {
                return;
            }

            tagPills.innerHTML = "";
            tagInputs.innerHTML = "";

            activeTags.forEach(function (tag) {
                var pill = document.createElement("span");
                var remove = document.createElement("button");
                var hidden = document.createElement("input");

                pill.className = "tag-pill";
                pill.appendChild(document.createTextNode(tag));
                remove.type = "button";
                remove.textContent = "x";
                remove.dataset.productTagRemove = tag;
                pill.appendChild(remove);
                tagPills.appendChild(pill);

                hidden.type = "hidden";
                hidden.name = "tags[]";
                hidden.value = tag;
                tagInputs.appendChild(hidden);
            });
        }

        function addTagByName(name) {
            if (name && !activeTags.has(name)) {
                activeTags.add(name);
                renderTags();
            }
        }

        if (tagAddButton) {
            tagAddButton.addEventListener("click", function () {
                var value = tagInput ? tagInput.value.trim() : "";

                addTagByName(value);

                if (tagInput) {
                    tagInput.value = "";
                }
            });
        }

        if (tagInput) {
            tagInput.addEventListener("keydown", function (event) {
                if (event.key === "Enter") {
                    event.preventDefault();

                    if (tagAddButton) {
                        tagAddButton.click();
                    }
                }
            });
        }

        document.querySelectorAll("[data-product-tag-suggestion]").forEach(function (suggestion) {
            suggestion.addEventListener("click", function () {
                addTagByName(suggestion.dataset.tagName);
            });
        });

        if (tagPills) {
            tagPills.addEventListener("click", function (event) {
                var remove = event.target.closest("[data-product-tag-remove]");

                if (!remove) {
                    return;
                }

                activeTags.delete(remove.dataset.productTagRemove);
                renderTags();
            });
        }

        renderTags();
    }

    // Product edit page js
    function initProductEditPage() {
        var featuredInput = document.querySelector("[data-product-edit-featured-input]");
        var featuredPreview = document.querySelector("[data-product-edit-featured-preview]");
        var featuredPlaceholder = document.querySelector("[data-product-edit-featured-placeholder]");
        var csrfToken = document.querySelector('meta[name="csrf-token"]');

        function getCsrfToken() {
            return csrfToken ? csrfToken.content : "";
        }

        if (featuredInput && featuredPreview) {
            featuredInput.addEventListener("change", function () {
                if (!featuredInput.files || !featuredInput.files[0]) {
                    return;
                }

                var reader = new FileReader();

                reader.addEventListener("load", function (event) {
                    featuredPreview.src = event.target.result;
                    featuredPreview.classList.remove("d-none");

                    if (featuredPlaceholder) {
                        featuredPlaceholder.classList.add("d-none");
                    }
                });

                reader.readAsDataURL(featuredInput.files[0]);
            });
        }

        document.querySelectorAll("[data-product-edit-gallery-delete]").forEach(function (button) {
            button.addEventListener("click", function () {
                if (!confirm("Remove this image?")) {
                    return;
                }

                fetch(button.dataset.deleteUrl, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": getCsrfToken(),
                        "Accept": "application/json"
                    }
                })
                    .then(function (response) {
                        if (!response.ok) {
                            throw new Error("HTTP " + response.status);
                        }

                        return response.json();
                    })
                    .then(function () {
                        var item = document.getElementById("gallery-item-" + button.dataset.imageId);

                        if (item) {
                            item.remove();
                        }
                    })
                    .catch(function () {
                        alert("Failed to delete image. Please try again.");
                    });
            });
        });
    }

    // Home page settings page js
    function initHomePageSettingsPage() {
        var root = document.querySelector("[data-home-page-settings]");

        if (!root) {
            return;
        }

        var blankPreview = root.dataset.blankPreview || "";
        var labels = {
            problem: "Card",
            solution: "Solution Card",
            why: "Why Card",
            stats: "Stats Card",
            story: "Video Story"
        };

        function renumber(repeater) {
            var type = repeater.dataset.repeater;

            repeater.querySelectorAll("[data-repeater-item]").forEach(function (item, index) {
                var title = item.querySelector("[data-repeater-title]");

                if (title) {
                    title.textContent = (labels[type] || "Card") + " " + (index + 1);
                }

                item.querySelectorAll("[name]").forEach(function (field) {
                    field.name = field.name.replace(/\[\d+\]/, "[" + index + "]");
                });
            });
        }

        function clearItem(item) {
            item.querySelectorAll("input, textarea").forEach(function (field) {
                if (field.type === "file") {
                    field.value = "";
                    return;
                }

                field.value = field.name.includes("[url]") ? "#" : "";
            });

            item.querySelectorAll(".admin-logo-preview img").forEach(function (image) {
                image.src = blankPreview;
            });
        }

        root.querySelectorAll("[data-repeater-add]").forEach(function (button) {
            button.addEventListener("click", function () {
                var type = button.dataset.repeaterAdd;
                var repeater = root.querySelector('[data-repeater="' + type + '"]');
                var source = repeater ? repeater.querySelector("[data-repeater-item]:last-child") : null;
                var clone;
                var firstField;

                if (!repeater || !source) {
                    return;
                }

                clone = source.cloneNode(true);
                clearItem(clone);
                repeater.appendChild(clone);
                renumber(repeater);

                firstField = clone.querySelector('input:not([type="hidden"]):not([type="file"]), textarea');

                if (firstField) {
                    firstField.focus();
                }
            });
        });

        root.addEventListener("click", function (event) {
            var editButton = event.target.closest("[data-repeater-edit]");
            var deleteButton = event.target.closest("[data-repeater-delete]");
            var item;
            var firstField;
            var repeater;

            if (editButton) {
                item = editButton.closest("[data-repeater-item]");
                firstField = item ? item.querySelector('input:not([type="hidden"]):not([type="file"]), textarea') : null;

                if (firstField) {
                    firstField.focus();
                    firstField.scrollIntoView({ behavior: "smooth", block: "center" });
                }
            }

            if (deleteButton) {
                item = deleteButton.closest("[data-repeater-item]");
                repeater = item ? item.closest("[data-repeater]") : null;

                if (!item || !repeater) {
                    return;
                }

                if (repeater.querySelectorAll("[data-repeater-item]").length === 1) {
                    clearItem(item);
                } else {
                    item.remove();
                }

                renumber(repeater);
            }
        });
    }

    // Forms wizard page js
    function initFormsWizardPage() {
        var root = document.querySelector("[data-form-wizard-page]");

        if (!root) {
            return;
        }

        function showError(input, shouldShow) {
            var error = input.parentElement ? input.parentElement.querySelector(".wizard-form-error") : null;

            if (error) {
                error.style.display = shouldShow ? "block" : "none";
            }
        }

        function validateFieldset(fieldset) {
            var isValid = true;

            fieldset.querySelectorAll(".wizard-required").forEach(function (input) {
                var isEmpty = input.value === "";

                showError(input, isEmpty);

                if (isEmpty) {
                    isValid = false;
                }
            });

            return isValid;
        }

        function updateStepMove(wizard, fieldset) {
            var formAttr = fieldset ? fieldset.getAttribute("data-tab-content") : null;

            if (!formAttr) {
                return;
            }

            wizard.querySelectorAll(".form-wizard-list .form-wizard-step-item").forEach(function (item) {
                var isActive = item.getAttribute("data-attr") === formAttr;
                var move = wizard.querySelector(".form-wizard-step-move");

                item.classList.toggle("active", isActive);

                if (isActive && move) {
                    move.style.left = item.offsetLeft + "px";
                    move.style.width = item.offsetWidth + "px";
                }
            });
        }

        function showFieldset(wizard, currentFieldset, targetFieldset, direction) {
            var currentActiveStep = wizard.querySelector(".form-wizard-list .active");
            var targetStep = direction === "next"
                ? currentActiveStep && currentActiveStep.nextElementSibling
                : currentActiveStep && currentActiveStep.previousElementSibling;

            if (!targetFieldset) {
                return;
            }

            currentFieldset.classList.remove("show");
            targetFieldset.classList.add("show");

            if (currentActiveStep && targetStep) {
                currentActiveStep.classList.remove("active");

                if (direction === "next") {
                    currentActiveStep.classList.add("activated");
                    targetStep.classList.add("active");
                } else {
                    targetStep.classList.remove("activated");
                    targetStep.classList.add("active");
                }
            }

            updateStepMove(wizard, targetFieldset);
        }

        root.addEventListener("click", function (event) {
            var nextButton = event.target.closest(".form-wizard-next-btn");
            var previousButton = event.target.closest(".form-wizard-previous-btn");
            var submitButton = event.target.closest(".form-wizard .form-wizard-submit");
            var fieldset;
            var wizard;

            if (nextButton) {
                fieldset = nextButton.closest(".wizard-fieldset");
                wizard = nextButton.closest(".form-wizard");

                if (fieldset && wizard && validateFieldset(fieldset)) {
                    showFieldset(wizard, fieldset, fieldset.nextElementSibling, "next");
                }
            }

            if (previousButton) {
                fieldset = previousButton.closest(".wizard-fieldset");
                wizard = previousButton.closest(".form-wizard");

                if (fieldset && wizard) {
                    showFieldset(wizard, fieldset, fieldset.previousElementSibling, "previous");
                }
            }

            if (submitButton) {
                fieldset = submitButton.closest(".wizard-fieldset");

                if (fieldset) {
                    validateFieldset(fieldset);
                }
            }
        });

        root.querySelectorAll(".form-control").forEach(function (input) {
            input.addEventListener("focus", function () {
                if (input.parentElement) {
                    input.parentElement.classList.add("focus-input");
                }
            });

            input.addEventListener("blur", function () {
                var isEmpty = input.value === "";

                if (input.parentElement) {
                    input.parentElement.classList.toggle("focus-input", !isEmpty);
                }

                showError(input, isEmpty);
            });
        });
    }

    document.addEventListener("DOMContentLoaded", function () {
        initAttributeEditModal();
        initBrandCreatePage();
        initBrandEditPage();
        initCategoryCreatePage();
        initTagsCreatePage();
        initTagsEditPage();
        initOrderShowPage();
        initPagesCreatePage();
        initPagesEditPage();
        initProductCreatePage();
        initProductEditPage();
        initHomePageSettingsPage();
        initFormsWizardPage();
    });
})();
