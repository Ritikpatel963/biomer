/*
 * Bharat Biomer home page interactions.
 * Kept inside an IIFE so shared frontend scripts do not create global variable conflicts.
 */
(function () {
    "use strict";

    var cropSolutions = {
        Wheat: [
            "Bio-Root Plus|Enhances root growth and nutrient uptake for stronger plants.",
            "BB Micro Boost|Improves soil health and nutrient availability naturally.",
            "BB Crop Nutrition|Balanced nutrition for better yield and grain quality."
        ],
        Tomato: [
            "Flower Max Bio|Supports flowering, fruit setting and uniform crop growth.",
            "Fruit Guard Plus|Improves fruit development, quality and marketability.",
            "Root Micro Boost|Builds active root-zone biology for better uptake."
        ],
        Cotton: [
            "Boll Booster Bio|Supports healthy boll formation and plant vigor.",
            "Stress Shield|Helps crop withstand heat and climate stress.",
            "Soil Active Granules|Improves microbial activity and root performance."
        ],
        Soybean: [
            "Nutrient Bio Fix|Improves nutrient use efficiency and early vigor.",
            "Root Zone Plus|Supports stronger root development and soil health.",
            "Yield Support Bio|Helps improve crop resilience and output."
        ],
        Paddy: [
            "Paddy Root Active|Supports root growth and better tillering.",
            "Soil Micro Granules|Improves soil microbial balance and nutrient availability.",
            "Grain Quality Support|Supports healthy grain formation and quality."
        ]
    };

    function renderCropSolutions() {
        var crop = document.getElementById("cropSelect");
        var title = document.getElementById("recTitle");
        var list = document.getElementById("recList");

        if (!crop || !title || !list || !cropSolutions[crop.value]) return;

        title.textContent = "Recommended for " + crop.value;
        list.innerHTML = cropSolutions[crop.value].map(function (item) {
            var parts = item.split("|");
            var name = parts[0];
            var description = parts[1] || "";

            return '<div class="rec"><div class="mini-product">BB</div><div><strong>' + name + '</strong><small>' + description + '</small><a href="#">View Details <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a></div></div>';
        }).join("");
    }

    function withAutoplay(url) {
        if (!url) return "";

        var separator = url.indexOf("?") === -1 ? "?" : "&";
        return url + separator + "autoplay=1&rel=0";
    }

    function playStoryVideo(card) {
        var videoUrl = card.getAttribute("data-story-video");
        if (!videoUrl || card.querySelector("iframe")) return;

        var iframe = document.createElement("iframe");
        iframe.src = withAutoplay(videoUrl);
        iframe.title = card.getAttribute("data-story-title") || "Field story video";
        iframe.loading = "lazy";
        iframe.allow = "accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share";
        iframe.allowFullscreen = true;

        card.innerHTML = "";
        card.style.backgroundImage = "none";
        card.appendChild(iframe);
    }

    function initStoriesToggle() {
        var moreButton = document.querySelector("[data-stories-more]");
        var lessButton = document.querySelector("[data-stories-less]");
        var stories = Array.prototype.slice.call(document.querySelectorAll(".stories-grid .story"));
        var visibleCount = 3;
        var step = 3;

        if (!moreButton || !lessButton || stories.length <= visibleCount) return;

        function renderStories() {
            stories.forEach(function (story, index) {
                story.classList.toggle("is-hidden", index >= visibleCount);
            });

            moreButton.classList.toggle("is-hidden", visibleCount >= stories.length);
            lessButton.classList.toggle("is-hidden", visibleCount <= step);
        }

        moreButton.addEventListener("click", function () {
            visibleCount = Math.min(visibleCount + step, stories.length);
            renderStories();
        });

        lessButton.addEventListener("click", function () {
            visibleCount = Math.max(visibleCount - step, step);
            renderStories();
        });

        renderStories();
    }

    function initCycleDots() {
        var row = document.querySelector(".cycle-row");
        var dots = document.querySelector("[data-cycle-dots]");
        if (!row || !dots) return;

        var stages = Array.prototype.slice.call(row.querySelectorAll(".stage"));
        if (stages.length < 2) return;

        var activeIndex = 0;
        var scrollFrame = null;

        function setActive(index) {
            activeIndex = index;
            dots.querySelectorAll(".cycle-dot").forEach(function (dot, dotIndex) {
                var isActive = dotIndex === activeIndex;
                dot.classList.toggle("is-active", isActive);
                dot.setAttribute("aria-current", isActive ? "true" : "false");
            });
        }

        function nearestStageIndex() {
            return stages.reduce(function (nearest, stage, index) {
                var stageLeft = stage.offsetLeft - row.offsetLeft;
                var nearestLeft = stages[nearest].offsetLeft - row.offsetLeft;
                return Math.abs(stageLeft - row.scrollLeft) < Math.abs(nearestLeft - row.scrollLeft) ? index : nearest;
            }, 0);
        }

        dots.innerHTML = "";
        stages.forEach(function (stage, index) {
            var dot = document.createElement("button");
            dot.type = "button";
            dot.className = "cycle-dot";
            dot.setAttribute("aria-label", "Show " + stage.querySelector("h3").textContent + " stage");
            dot.addEventListener("click", function () {
                stage.scrollIntoView({ behavior: "smooth", block: "nearest", inline: "start" });
                setActive(index);
            });
            dots.appendChild(dot);
        });

        row.addEventListener("scroll", function () {
            if (scrollFrame) return;
            scrollFrame = window.requestAnimationFrame(function () {
                scrollFrame = null;
                setActive(nearestStageIndex());
            });
        }, { passive: true });

        window.addEventListener("resize", function () {
            setActive(nearestStageIndex());
        });

        setActive(0);
    }

    document.addEventListener("DOMContentLoaded", function () {
        var crop = document.getElementById("cropSelect");

        if (crop) {
            crop.addEventListener("change", renderCropSolutions);
        }

        document.querySelectorAll(".story-img[data-story-video]").forEach(function (card) {
            card.addEventListener("click", function () {
                playStoryVideo(card);
            });
        });

        initStoriesToggle();
        initCycleDots();
        renderCropSolutions();
    });
})();

/*
 * Cart page interactions.
 * The button handlers stay on window because cart.blade.php calls them from inline attributes.
 */
(function () {
    "use strict";

    var cartSection = document.querySelector(".cart__section");
    var csrfMeta = document.querySelector('meta[name="csrf-token"]');
    var csrfToken = csrfMeta ? csrfMeta.content : "";

    function getById(id) {
        return document.getElementById(id);
    }

    function updateGlobalCartBadge(count) {
        if (count > 0) {
            document.querySelectorAll(".bb-cart-badge").forEach(function (badge) {
                badge.textContent = count;
            });
        } else {
            document.querySelectorAll(".bb-cart-badge").forEach(function (badge) {
                badge.remove();
            });
        }
    }

    function formatShipping(amount) {
        return amount === "\u20b90.00" ? "Free" : amount;
    }

    function showToast(message, type) {
        var toast = getById("cartToast");
        if (!toast) return;

        toast.textContent = message;
        toast.className = "cart__toast " + (type || "success");
        toast.classList.add("show");
        clearTimeout(window._toastTimer);
        window._toastTimer = setTimeout(function () {
            toast.classList.remove("show");
        }, 3000);
    }

    function updateCartSummary(data) {
        getById("summarySubtotal").textContent = data.subtotal;
        getById("summaryShipping").textContent = formatShipping(data.shipping_total);
        getById("summaryTax").textContent = data.tax_amount;
        getById("summaryTotal").textContent = data.final_total;
    }

    function sendQtyUpdate(key, index, quantity) {
        var row = getById("cartRow_" + index);
        if (!row) return;

        row.classList.add("cart__row-updating");

        fetch("/cart/update", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
                "Accept": "application/json"
            },
            body: JSON.stringify({ key: key, quantity: quantity })
        })
            .then(function (response) {
                return response.json();
            })
            .then(function (data) {
                row.classList.remove("cart__row-updating");

                if (data.success) {
                    getById("itemTotal_" + index).textContent = data.item_total;
                    updateCartSummary(data);
                    getById("summaryItemCount").textContent = data.cart_count;
                    getById("cartCountBadge").textContent = data.cart_count + " item(s)";
                    updateGlobalCartBadge(data.cart_count);
                    showToast("Quantity updated!", "success");
                } else {
                    showToast("Could not update. Try again.", "error");
                }
            })
            .catch(function () {
                row.classList.remove("cart__row-updating");
                showToast("Network error. Try again.", "error");
            });
    }

    window.changeQty = function (key, index, delta) {
        var input = getById("qtyInput_" + index);
        if (!input) return;

        var newVal = parseInt(input.value, 10) + delta;
        newVal = Math.max(1, Math.min(100, newVal));
        input.value = newVal;
        sendQtyUpdate(key, index, newVal);
    };

    window.manualQtyChange = function (input) {
        var val = parseInt(input.value, 10) || 1;
        val = Math.max(1, Math.min(100, val));
        input.value = val;
        sendQtyUpdate(input.dataset.key, input.dataset.index, val);
    };

    window.removeItem = function (key, index) {
        var row = getById("cartRow_" + index);
        if (!row) return;

        row.classList.add("cart__row-updating");

        fetch("/cart/remove", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
                "Accept": "application/json"
            },
            body: JSON.stringify({ key: key })
        })
            .then(function (response) {
                return response.json();
            })
            .then(function (data) {
                if (data.success) {
                    row.style.transition = "opacity 0.3s, transform 0.3s";
                    row.style.opacity = "0";
                    row.style.transform = "translateX(20px)";
                    setTimeout(function () {
                        row.remove();
                        updateCartSummary(data);
                        getById("summaryItemCount").textContent = data.cart_count;
                        getById("cartCountBadge").textContent = data.cart_count + " item(s)";
                        updateGlobalCartBadge(data.cart_count);
                        showToast("Item removed from cart.", "success");

                        if (data.empty) {
                            setTimeout(function () {
                                location.reload();
                            }, 800);
                        }
                    }, 300);
                } else {
                    row.classList.remove("cart__row-updating");
                    showToast("Could not remove item. Try again.", "error");
                }
            })
            .catch(function () {
                row.classList.remove("cart__row-updating");
                showToast("Network error. Try again.", "error");
            });
    };

    window.applyCoupon = function () {
        var codeInput = getById("couponCode");
        var code = codeInput ? codeInput.value.trim() : "";
        var applyUrl = cartSection ? cartSection.dataset.cartCouponApplyUrl : "";

        if (!code) return showToast("Please enter a coupon code.", "error");

        fetch(applyUrl, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
                "Accept": "application/json"
            },
            body: JSON.stringify({ code: code })
        })
            .then(function (response) {
                return response.json();
            })
            .then(function (data) {
                if (data.success) {
                    showToast(data.message, "success");
                    getById("discountRow").classList.remove("cart__summary-row--hidden");
                    getById("couponCodeBadge").textContent = code;
                    getById("summaryDiscount").innerHTML = "-" + data.discount + ' <a href="javascript:void(0)" onclick="removeCoupon()" class="cart__remove-coupon" title="Remove Coupon">&#10005;</a>';
                    updateCartSummary(data);
                    getById("couponFormWrapper").classList.add("cart__coupon-form--hidden");
                } else {
                    showToast(data.message, "error");
                }
            })
            .catch(function () {
                showToast("Network error.", "error");
            });
    };

    window.removeCoupon = function () {
        var removeUrl = cartSection ? cartSection.dataset.cartCouponRemoveUrl : "";

        fetch(removeUrl, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
                "Accept": "application/json"
            }
        })
            .then(function (response) {
                return response.json();
            })
            .then(function (data) {
                if (data.success) {
                    showToast(data.message, "success");
                    getById("discountRow").classList.add("cart__summary-row--hidden");
                    updateCartSummary(data);
                    getById("couponFormWrapper").classList.remove("cart__coupon-form--hidden");
                    getById("couponCode").value = "";
                }
            })
            .catch(function () {
                showToast("Network error.", "error");
            });
    };
})();

/*
 * Wishlist page interactions.
 * Scoped to .wl-page so wishlist add-to-cart handling does not affect other product buttons.
 */
(function () {
    "use strict";

    var wishlistPage = document.querySelector(".wl-page[data-cart-add-url]");
    var csrfMeta = document.querySelector('meta[name="csrf-token"]');
    var csrfToken = csrfMeta ? csrfMeta.content : "";

    if (!wishlistPage) return;

    wishlistPage.querySelectorAll(".add-to-cart-wl").forEach(function (button) {
        button.addEventListener("click", function () {
            fetch(wishlistPage.dataset.cartAddUrl, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },
                body: JSON.stringify({
                    product_id: this.dataset.id,
                    quantity: 1
                })
            })
                .then(function (response) {
                    return response.json();
                })
                .then(function (data) {
                    if (data.success) {
                        button.textContent = "\u2713 Added!";
                        button.classList.add("wl__btn--added");
                        setTimeout(function () {
                            button.textContent = "Add to Cart";
                            button.classList.remove("wl__btn--added");
                        }, 2000);
                    }
                });
        });
    });
})();

/*
 * Product details page interactions.
 * selectVariation and changeImage stay on window because productdetails.blade.php uses inline handlers.
 */
(function () {
    "use strict";

    var productDetailsPage = document.querySelector(".pd-page[data-cart-add-url][data-review-store-url]");
    var csrfMeta = document.querySelector('meta[name="csrf-token"]');
    var csrfToken = csrfMeta ? csrfMeta.content : "";
    var selectedRating = 0;
    var starLabels = ["", "\u2b50 Terrible", "\u2b50\u2b50 Poor", "\u2b50\u2b50\u2b50 Average", "\u2b50\u2b50\u2b50\u2b50 Good", "\u2b50\u2b50\u2b50\u2b50\u2b50 Excellent"];

    if (!productDetailsPage) return;

    function getById(id) {
        return document.getElementById(id);
    }

    function updateGlobalCartBadge(count) {
        if (count > 0) {
            document.querySelectorAll(".bb-cart-badge").forEach(function (badge) {
                badge.textContent = count;
            });

            document.querySelectorAll(".bb-cart-icon").forEach(function (icon) {
                if (!icon.querySelector(".bb-cart-badge")) {
                    var badge = document.createElement("span");
                    badge.className = "bb-cart-badge";
                    badge.textContent = count;
                    icon.appendChild(badge);
                }
            });
        } else {
            document.querySelectorAll(".bb-cart-badge").forEach(function (badge) {
                badge.remove();
            });
        }
    }

    function updateStarVisuals(stars, rating) {
        stars.forEach(function (star) {
            var value = parseInt(star.dataset.value, 10);
            star.classList.toggle("active", value <= rating);

            if (value <= rating) {
                star.classList.remove("ri-star-line");
                star.classList.add("ri-star-fill");
            } else {
                star.classList.remove("ri-star-fill");
                star.classList.add("ri-star-line");
            }
        });
    }

    function validationMessage(data) {
        if (!data) return "Something went wrong. Please try again.";

        if (data.errors) {
            return Object.keys(data.errors).reduce(function (messages, key) {
                return messages.concat(data.errors[key]);
            }, []).join("\n");
        }

        return data.message || "Something went wrong. Please try again.";
    }

    window.selectVariation = function (element) {
        var variantId = element.dataset.id || element.dataset.variantId;
        if (!variantId) return;

        document.querySelectorAll(".pd__variant-card").forEach(function (card) {
            if (card.dataset.variantId == variantId || card.dataset.id == variantId) {
                card.classList.add("pd__variant-card--active");
                card.style.borderColor = "#2d7a45";
                card.style.backgroundColor = "#f9fcf8";
            } else {
                card.classList.remove("pd__variant-card--active");
                card.style.borderColor = "#e8f0e4";
                card.style.backgroundColor = "#fff";
            }
        });

        var price = parseFloat(element.dataset.price).toLocaleString("en-IN", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
        getById("displayPrice").textContent = "\u20b9" + price;

        var unit = element.dataset.unit || "unit";
        var priceUnitEl = getById("priceUnit");
        if (priceUnitEl) {
            priceUnitEl.textContent = "/ " + unit;
        }

        var priceNote = getById("priceNote");
        if (priceNote) {
            priceNote.textContent = element.dataset.value;
        }

        var stock = parseInt(element.dataset.stock, 10);
        var stockEl = getById("stockInfo");
        if (stockEl) {
            if (stock > 10) {
                stockEl.innerHTML = '<span class="pd__stock--in">\u2713 In Stock (' + stock + " available)</span>";
            } else if (stock > 0) {
                stockEl.innerHTML = '<span class="pd__stock--low">\u26a0 Low Stock (' + stock + " left)</span>";
            } else {
                stockEl.innerHTML = '<span class="pd__stock--out">\u2715 Out of Stock</span>';
            }
        }

        if (element.dataset.image) {
            getById("mainImage").src = element.dataset.image;
            document.querySelectorAll(".pd__thumb").forEach(function (thumb) {
                thumb.classList.remove("pd__thumb--active");
            });
        }

        var addToCartButton = getById("addToCartBtn");
        if (addToCartButton) {
            addToCartButton.dataset.variationId = variantId;
        }
    };

    window.changeImage = function (thumb, src) {
        getById("mainImage").src = src;
        document.querySelectorAll(".pd__thumb").forEach(function (item) {
            item.classList.remove("pd__thumb--active");
        });
        thumb.classList.add("pd__thumb--active");
    };

    var firstVariantCard = document.querySelector(".pd__variant-card");
    if (firstVariantCard) {
        window.selectVariation(firstVariantCard);
    }

    var addToCartButton = getById("addToCartBtn");
    if (addToCartButton) {
        addToCartButton.addEventListener("click", function () {
            var productId = this.dataset.productId;
            var variationId = this.dataset.variationId || null;
            var label = this.querySelector("span");

            fetch(productDetailsPage.dataset.cartAddUrl, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },
                body: JSON.stringify({
                    product_id: productId,
                    variation_id: variationId,
                    quantity: 1
                })
            })
                .then(function (response) {
                    return response.json();
                })
                .then(function (data) {
                    if (data.success) {
                        if (label) {
                            label.textContent = "Added to Cart!";
                        }

                        addToCartButton.style.background = "#4caf72";

                        if (data.cart_count !== undefined) {
                            updateGlobalCartBadge(data.cart_count);
                        }

                        setTimeout(function () {
                            var currentLabel = addToCartButton.querySelector("span");
                            if (currentLabel) {
                                currentLabel.textContent = "Add to Cart";
                            }
                            addToCartButton.style.background = "";
                        }, 2500);
                    }
                })
                .catch(function () {
                    alert("Could not add to cart. Please try again.");
                });
        });
    }

    var pickStars = document.querySelectorAll(".rv__pick-star");
    pickStars.forEach(function (star) {
        star.addEventListener("mouseover", function () {
            var value = parseInt(star.dataset.value, 10);
            updateStarVisuals(pickStars, value);

            var starLabel = getById("starLabel");
            if (starLabel) {
                starLabel.textContent = starLabels[value];
                starLabel.style.color = "#f59e0b";
            }
        });

        star.addEventListener("mouseout", function () {
            updateStarVisuals(pickStars, selectedRating);

            if (selectedRating === 0) {
                var starLabel = getById("starLabel");
                if (starLabel) {
                    starLabel.textContent = "Select rating";
                    starLabel.style.color = "#6b7280";
                }
            }
        });

        star.addEventListener("click", function (event) {
            event.preventDefault();
            event.stopPropagation();

            var value = parseInt(star.dataset.value, 10);
            selectedRating = value;
            updateStarVisuals(pickStars, value);

            var starLabel = getById("starLabel");
            if (starLabel) {
                starLabel.textContent = starLabels[value];
                starLabel.style.color = "#2d7a45";
            }

            var ratingHint = getById("ratingHint");
            if (ratingHint) {
                ratingHint.textContent = "\u2713 Rating selected: " + value + " star" + (value !== 1 ? "s" : "");
                ratingHint.style.color = "#2d7a45";
                ratingHint.style.fontWeight = "600";
            }
        });
    });

    if (pickStars.length > 0) {
        pickStars.forEach(function (star) {
            star.classList.remove("ri-star-fill");
            star.classList.add("ri-star-line");
        });
    }

    var submitButton = getById("submitReviewBtn");
    if (submitButton) {
        submitButton.addEventListener("click", function () {
            if (!selectedRating) {
                var starLabel = getById("starLabel");
                var ratingHint = getById("ratingHint");
                var starPicker = getById("starPicker");

                if (starLabel) {
                    starLabel.textContent = "\u26a0 Please select a rating!";
                    starLabel.style.color = "#dc3545";
                }

                if (ratingHint) {
                    ratingHint.textContent = "You must select a star rating before submitting";
                    ratingHint.style.color = "#dc3545";
                    ratingHint.style.fontWeight = "600";
                }

                return;
            }

            var reviewText = getById("reviewText");
            var messageEl = getById("reviewMsg");
            var comment = reviewText ? reviewText.value.trim() : "";

            if (comment.length < 3) {
                if (messageEl) {
                    messageEl.style.display = "block";
                    messageEl.style.color = "#dc3545";
                    messageEl.textContent = "Comment must be at least 3 characters.";
                }

                if (reviewText) {
                    reviewText.focus();
                }

                return;
            }

            submitButton.disabled = true;
            submitButton.textContent = "Submitting...";

            fetch(productDetailsPage.dataset.reviewStoreUrl, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },
                body: JSON.stringify({
                    rating: selectedRating,
                    review_text: comment
                })
            })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return {
                            ok: response.ok,
                            data: data
                        };
                    });
                })
                .then(function (result) {
                    var data = result.data;

                    if (messageEl) {
                        messageEl.style.display = "block";
                    }

                    if (result.ok && data.success) {
                        if (messageEl) {
                            messageEl.style.color = "#2d7a45";
                            messageEl.textContent = data.message;
                        }

                        var reviewFormWrap = getById("reviewFormWrap");
                        if (reviewFormWrap) {
                            reviewFormWrap.style.opacity = "0.6";
                        }

                        submitButton.textContent = "\u2713 Submitted";

                        if (window.BharatBiomerModal && data.modal) {
                            window.BharatBiomerModal.show(data.modal);
                        }
                    } else {
                        if (messageEl) {
                            messageEl.style.color = "#dc3545";
                            messageEl.textContent = validationMessage(data);
                        }

                        submitButton.disabled = false;
                        submitButton.textContent = "Submit Review";
                    }
                })
                .catch(function () {
                    if (messageEl) {
                        messageEl.style.display = "block";
                        messageEl.style.color = "#dc3545";
                        messageEl.textContent = "Something went wrong. Please try again.";
                    }

                    submitButton.disabled = false;
                    submitButton.textContent = "Submit Review";
                });
        });
    }
})();

/*
 * Product listing page interactions.
 * Scoped to the product listing data attributes so other .avan__section pages are not touched.
 */
(function () {
    "use strict";

    var productSection = document.querySelector(".avan__section[data-cart-add-url][data-wishlist-toggle-url]");
    var csrfMeta = document.querySelector('meta[name="csrf-token"]');
    var csrfToken = csrfMeta ? csrfMeta.content : "";
    var latestProductRequest = 0;

    if (!productSection) return;

    function updateGlobalCartBadge(count) {
        if (count > 0) {
            document.querySelectorAll(".bb-cart-badge").forEach(function (badge) {
                badge.textContent = count;
            });

            document.querySelectorAll(".bb-cart-icon").forEach(function (icon) {
                if (!icon.querySelector(".bb-cart-badge")) {
                    var badge = document.createElement("span");
                    badge.className = "bb-cart-badge";
                    badge.textContent = count;
                    icon.appendChild(badge);
                }
            });
        } else {
            document.querySelectorAll(".bb-cart-badge").forEach(function (badge) {
                badge.remove();
            });
        }
    }

    function formatPrice(price) {
        return "\u20b9" + parseFloat(price).toLocaleString("en-IN", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function initializeVariationDefaults() {
        productSection.querySelectorAll(".shop__card").forEach(function (card) {
            var firstVariation = card.querySelector(".shop__variation-btn");
            if (firstVariation && !card.querySelector(".shop__variation-btn--active")) {
                firstVariation.click();
            }
        });
    }

    function openMobileFilters() {
        productSection.classList.add("shop__filters-open");
        document.body.classList.add("shop-filter-lock");
    }

    function closeMobileFilters() {
        productSection.classList.remove("shop__filters-open");
        document.body.classList.remove("shop-filter-lock");
    }

    function buildFilterUrl(form) {
        var params = new URLSearchParams(new FormData(form));

        Array.from(params.keys()).forEach(function (key) {
            if (!params.get(key)) {
                params.delete(key);
            }
        });

        var queryString = params.toString();
        return form.action + (queryString ? "?" + queryString : "");
    }

    function updateProductsFromUrl(url, pushState) {
        var results = productSection.querySelector("[data-product-results]");
        var pagination = productSection.querySelector("[data-product-pagination]");
        var mobileToolbar = productSection.querySelector(".shop__mobile-toolbar");
        var requestId = latestProductRequest + 1;

        if (!results) return;

        latestProductRequest = requestId;
        productSection.classList.add("shop__section--loading");

        fetch(url, {
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            }
        })
            .then(function (response) {
                if (!response.ok) {
                    throw new Error("Could not load products.");
                }

                return response.text();
            })
            .then(function (html) {
                var doc = new DOMParser().parseFromString(html, "text/html");
                var nextResults = doc.querySelector("[data-product-results]");
                var nextPagination = doc.querySelector("[data-product-pagination]");
                var nextMobileToolbar = doc.querySelector(".shop__mobile-toolbar");

                if (requestId !== latestProductRequest) {
                    return;
                }

                if (nextResults) {
                    results.innerHTML = nextResults.innerHTML;
                }

                if (pagination && nextPagination) {
                    pagination.innerHTML = nextPagination.innerHTML;
                }

                if (mobileToolbar && nextMobileToolbar) {
                    mobileToolbar.innerHTML = nextMobileToolbar.innerHTML;
                }

                if (pushState) {
                    window.history.replaceState({}, "", url);
                }

                initializeVariationDefaults();
            })
            .catch(function () {
                if (window.BharatBiomerModal) {
                    window.BharatBiomerModal.show({
                        title: "Search failed",
                        message: "Could not update products. Please try again.",
                        button: "Close"
                    });
                }
            })
            .finally(function () {
                if (requestId === latestProductRequest) {
                    productSection.classList.remove("shop__section--loading");
                }
            });
    }

    function resetFilters() {
        productSection.querySelectorAll("#filterForm input, #filterForm select").forEach(function (field) {
            if (field.tagName === "SELECT") {
                field.selectedIndex = 0;
            } else {
                field.value = "";
            }
        });

        applyFilters();
    }

    function applyFilters() {
        var form = document.getElementById("filterForm");
        if (!form) return;

        updateProductsFromUrl(buildFilterUrl(form), true);
    }

    productSection.addEventListener("click", function (event) {
        var variationButton = event.target.closest(".shop__variation-btn");
        var addButton = event.target.closest(".add-to-cart");
        var wishlistButton = event.target.closest(".wishlist-toggle");
        var paginationLink = event.target.closest("[data-product-pagination] a");
        var mobileFilterOpen = event.target.closest("[data-mobile-filter-open]");
        var mobileFilterClose = event.target.closest("[data-mobile-filter-close]");

        if (mobileFilterOpen) {
            event.preventDefault();
            openMobileFilters();
            return;
        }

        if (mobileFilterClose) {
            event.preventDefault();
            closeMobileFilters();
            return;
        }

        if (variationButton) {
            var card = variationButton.closest(".shop__card");
            if (!card) return;

            card.querySelectorAll(".shop__variation-btn").forEach(function (button) {
                button.classList.remove("shop__variation-btn--active");
            });
            variationButton.classList.add("shop__variation-btn--active");

            var priceEl = card.querySelector(".shop__price");
            var unitEl = card.querySelector(".shop__price-unit");

            if (priceEl) {
                priceEl.textContent = formatPrice(variationButton.dataset.price);
            }

            if (unitEl) {
                unitEl.textContent = "/ " + variationButton.dataset.unit;
            }

            var selectedAddButton = card.querySelector(".add-to-cart");
            if (selectedAddButton) {
                selectedAddButton.dataset.variationId = variationButton.dataset.variationId;
            }
        }

        if (addButton) {
            var label = addButton.querySelector("span");

            fetch(productSection.dataset.cartAddUrl, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },
                body: JSON.stringify({
                    product_id: addButton.dataset.id,
                    quantity: 1,
                    variation_id: addButton.dataset.variationId || null
                })
            })
                .then(function (response) {
                    return response.json();
                })
                .then(function (data) {
                    if (data.success) {
                        if (data.cart_count !== undefined) {
                            updateGlobalCartBadge(data.cart_count);
                        }

                        if (label) {
                            label.textContent = "Added!";
                        }

                        addButton.classList.add("shop__btn--added");
                        setTimeout(function () {
                            if (label) {
                                label.textContent = "Add to Cart";
                            }
                            addButton.classList.remove("shop__btn--added");
                        }, 2000);
                    }
                })
                .catch(function () {
                    alert("Could not add to cart. Please try again.");
                });
        }

        if (wishlistButton) {
            event.preventDefault();

            var self = wishlistButton;
            var originalText = self.textContent;

            self.disabled = true;
            self.setAttribute("aria-busy", "true");

            fetch(productSection.dataset.wishlistToggleUrl, {
                method: "POST",
                credentials: "same-origin",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: JSON.stringify({ product_id: self.dataset.id })
            })
                .then(function (response) {
                    if (response.status === 401 || response.redirected) {
                        window.location.href = "/customer/login";
                        return null;
                    }

                    return response.json().then(function (data) {
                        if (!response.ok) {
                            throw new Error(data.message || "Could not update wishlist.");
                        }

                        return data;
                    });
                })
                .then(function (data) {
                    if (!data) return;

                    if (data.success) {
                        self.innerHTML = data.wishlisted
                            ? '<i class="ri-heart-fill" aria-hidden="true"></i>'
                            : '<i class="ri-heart-line" aria-hidden="true"></i>';
                        self.title = data.wishlisted ? "Remove from wishlist" : "Add to wishlist";

                        if (data.wishlisted) {
                            self.classList.add("wishlisted");
                        } else {
                            self.classList.remove("wishlisted");
                        }

                        var badge = document.getElementById("wishlist-count");
                        if (badge) {
                            badge.textContent = data.count;
                            badge.style.display = data.count > 0 ? "flex" : "none";
                        }
                    }
                })
                .catch(function (error) {
                    self.textContent = originalText;
                    alert(error.message || "Could not update wishlist. Please try again.");
                })
                .finally(function () {
                    self.disabled = false;
                    self.removeAttribute("aria-busy");
                });
        }

        if (paginationLink) {
            event.preventDefault();
            updateProductsFromUrl(paginationLink.href, true);
        }
    });

    initializeVariationDefaults();

    productSection.querySelectorAll(".shop__filter-select").forEach(function (select) {
        select.addEventListener("change", applyFilters);
    });

    productSection.addEventListener("change", function (event) {
        var mobileSort = event.target.closest("[data-mobile-sort]");
        if (!mobileSort) return;

        var form = document.getElementById("filterForm");
        var sortSelect = form ? form.querySelector('select[name="sort"]') : null;
        if (!sortSelect) return;

        sortSelect.value = mobileSort.value;
        applyFilters();
    });

    var clearButton = productSection.querySelector(".shop__clear-btn");
    if (clearButton) {
        clearButton.addEventListener("click", function (event) {
            event.preventDefault();
            resetFilters();
        });
    }

    var resetButton = productSection.querySelector(".shop__filters-reset");
    if (resetButton) {
        resetButton.addEventListener("click", function (event) {
            event.preventDefault();
            resetFilters();
        });
    }

    var searchInput = productSection.querySelector(".shop__search-input");
    var searchTimer;
    if (searchInput) {
        searchInput.addEventListener("input", function () {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(applyFilters, 300);
        });

        searchInput.addEventListener("keypress", function (event) {
            if (event.key === "Enter") {
                event.preventDefault();
                clearTimeout(searchTimer);
                applyFilters();
            }
        });
    }

    var filterForm = document.getElementById("filterForm");
    if (filterForm) {
        filterForm.addEventListener("submit", function (event) {
            event.preventDefault();
            clearTimeout(searchTimer);
            applyFilters();
            closeMobileFilters();
        });

        filterForm.querySelectorAll(".shop__price-input").forEach(function (input) {
            input.addEventListener("input", function () {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(applyFilters, 500);
            });
        });
    }
})();

/*
 * Checkout page payment interactions.
 * startPayment stays on window because checkout.blade.php calls it from the Pay Now button.
 */
(function () {
    "use strict";

    var checkoutSection = document.querySelector(".chk__section");
    var csrfMeta = document.querySelector('meta[name="csrf-token"]');
    var csrfToken = csrfMeta ? csrfMeta.content : "";
    var cashfreeInstance = null;

    function getById(id) {
        return document.getElementById(id);
    }

    function selectedGateway() {
        var selected = document.querySelector('input[name="payment_method"]:checked');
        return selected ? selected.value : "";
    }

    function jsonResponse(response) {
        return response.json().then(function (data) {
            return { ok: response.ok, data: data };
        });
    }

    function resetCheckoutButton(button) {
        button.disabled = false;

        var label = button.querySelector("span");
        if (label) {
            label.textContent = "Pay Now";
        }
    }

    function checkoutFetch(url, options) {
        return fetch(url, options).then(jsonResponse);
    }

    function startRazorpayPayment(button, form) {
        var formData = new FormData(form);
        var razorpayUrl = checkoutSection.dataset.orderRazorpayUrl;
        var successUrl = checkoutSection.dataset.orderPaymentSuccessUrl;

        checkoutFetch(razorpayUrl, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                "Accept": "application/json"
            },
            body: formData
        })
            .then(function (result) {
                var data = result.data;

                if (!result.ok) {
                    throw new Error(data.error || data.message || "Something went wrong.");
                }

                var options = {
                    key: data.key_id,
                    amount: data.amount,
                    currency: data.currency,
                    name: "Bharat Biomer",
                    description: "Order Payment",
                    order_id: data.razorpay_order_id,
                    prefill: {
                        name: data.name,
                        email: data.email,
                        contact: data.phone
                    },
                    theme: {
                        color: "#2d7a45"
                    },
                    handler: function (response) {
                        button.querySelector("span").textContent = "Verifying Payment...";

                        checkoutFetch(successUrl, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": csrfToken,
                                "Accept": "application/json"
                            },
                            body: JSON.stringify({
                                razorpay_order_id: response.razorpay_order_id,
                                razorpay_payment_id: response.razorpay_payment_id,
                                razorpay_signature: response.razorpay_signature
                            })
                        })
                            .then(function (verifyResult) {
                                var verifyData = verifyResult.data;

                                if (verifyResult.ok && verifyData.redirect_url) {
                                    window.location.href = verifyData.redirect_url;
                                } else {
                                    throw new Error(verifyData.error || "Payment verification failed.");
                                }
                            })
                            .catch(function (error) {
                                alert(error.message);
                                resetCheckoutButton(button);
                            });
                    },
                    modal: {
                        ondismiss: function () {
                            resetCheckoutButton(button);
                        }
                    }
                };

                if (typeof window.Razorpay === "undefined") {
                    throw new Error("Razorpay payment library is not loaded.");
                }

                var razorpay = new window.Razorpay(options);
                razorpay.on("payment.failed", function (response) {
                    alert("Payment failed: " + response.error.description);
                    resetCheckoutButton(button);
                });
                razorpay.open();
            })
            .catch(function (error) {
                alert(error.message);
                resetCheckoutButton(button);
            });
    }

    function getCashfreeInstance() {
        if (cashfreeInstance) return cashfreeInstance;

        if (typeof window.Cashfree === "undefined") {
            throw new Error("Cashfree payment library is not loaded.");
        }

        cashfreeInstance = window.Cashfree({
            mode: checkoutSection.dataset.cashfreeEnvironment || "sandbox"
        });

        return cashfreeInstance;
    }

    function startCashfreePayment(button, form) {
        var formData = new FormData(form);

        checkoutFetch(checkoutSection.dataset.orderCashfreeUrl, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                "Accept": "application/json"
            },
            body: formData
        })
            .then(function (result) {
                var data = result.data;

                if (!result.ok) {
                    throw new Error(data.error || "Something went wrong.");
                }

                if (!data.payment_session_id) {
                    throw new Error("Cashfree session was not created. Please check the server logs.");
                }

                button.querySelector("span").textContent = "Opening Payment...";

                getCashfreeInstance().checkout({
                    paymentSessionId: data.payment_session_id,
                    redirectTarget: "_self"
                });
            })
            .catch(function (error) {
                alert(error.message);
                resetCheckoutButton(button);
            });
    }

    function validationMessage(data) {
        if (!data.errors) {
            return data.error || data.message || "Something went wrong.";
        }

        return Object.keys(data.errors).reduce(function (messages, key) {
            return messages.concat(data.errors[key]);
        }, []).join("\n");
    }

    function startCodPayment(button, form) {
        if (!confirm("Place order with Cash on Delivery? You will pay when the order is delivered.")) {
            resetCheckoutButton(button);
            return;
        }

        checkoutFetch(checkoutSection.dataset.orderCodUrl, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                "Accept": "application/json"
            },
            body: new FormData(form)
        })
            .then(function (result) {
                var data = result.data;

                if (result.ok && data.redirect_url) {
                    window.location.href = data.redirect_url;
                } else {
                    throw new Error(validationMessage(data));
                }
            })
            .catch(function (error) {
                alert(error.message);
                resetCheckoutButton(button);
            });
    }

    window.startPayment = function () {
        var button = getById("placeOrderBtn");
        var form = getById("checkoutForm");

        if (!checkoutSection || !button || !form) return;

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        button.disabled = true;
        button.querySelector("span").textContent = "Processing...";

        var gateway = selectedGateway();

        if (!gateway) {
            alert("No payment method is available right now. Please contact support.");
            resetCheckoutButton(button);
            return;
        }

        if (gateway === "cod") {
            startCodPayment(button, form);
        } else if (gateway === "cashfree") {
            startCashfreePayment(button, form);
        } else {
            startRazorpayPayment(button, form);
        }
    };
})();
