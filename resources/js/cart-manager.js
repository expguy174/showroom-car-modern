/**
 * Cart Manager - Handles cart operations
 */

class CartManager {
    constructor() {
        this.init();
    }

    init() {
        this.bindEvents();
        this.refreshCartCount();
        this.initializeQuantityInputs();
        // Helper to update sections visibility when items become empty
        window.updateCartSectionsVisibility = function() {
            // Count both desktop and mobile rows to avoid relying on CSS visibility
            const carRows = $('#car-section tbody .cart-item-desktop').length + $('#car-section tbody .cart-item-row').length;
            const accRows = $('#accessory-section tbody .cart-item-desktop').length + $('#accessory-section tbody .cart-item-row').length;
            if (carRows === 0) { $('#car-section').fadeOut(150); } else { $('#car-section').show(); }
            if (accRows === 0) { $('#accessory-section').fadeOut(150); } else { $('#accessory-section').show(); }
            if (carRows === 0 && accRows === 0) {
                window.location.reload();
            }
        };
    }

    bindEvents() {
        // Cart add
        $(document).on('click', '.js-add-to-cart', this.handleCartAdd.bind(this));
        
        // Cart remove
        $(document).on('click', '.js-remove-from-cart', this.handleCartRemove.bind(this));
        // Support existing remove buttons
        $(document).on('click', '.remove-item-btn', this.handleCartRemove.bind(this));
        
        // Cart update quantity - handle both desktop and mobile
        $(document).on('change', '.js-cart-quantity, .cart-qty-input', this.handleCartQuantityChange.bind(this));
        
        // Prevent invalid input (negative numbers, zero)
        $(document).on('input', '.js-cart-quantity, .cart-qty-input', this.handleQuantityInput.bind(this));
        
        // Cart clear
        $(document).on('click', '.js-clear-cart', this.handleCartClear.bind(this));
        // Support existing clear button by id
        $(document).on('click', '#clear-cart-btn', this.handleCartClear.bind(this));
        
        // Quantity controls - handle both desktop and mobile
        $(document).on('click', '.qty-increase, .qty-decrease', this.handleQuantityControl.bind(this));
        
        // Feature selection for both layouts
        $(document).on('change', '.cart-feature.js-opt', this.handleFeatureChange.bind(this));
        
        // Color selection for both layouts
        $(document).on('click', '.color-option', this.handleColorChange.bind(this));
    }

    handleCartAdd(e) {
        e.preventDefault();
        const button = $(e.currentTarget);
        const itemId = button.data('item-id') || button.attr('data-item-id');
        const itemType = button.data('item-type') || button.attr('data-item-type') || 'car_variant';
        const quantity = button.data('quantity') || 1;
        const colorId = button.data('color-id') || null;
        
        if (!itemId) {
            this.showMessage('Không tìm thấy sản phẩm!', 'error');
            return;
        }

        // Optimistic update - disable button
        button.prop('disabled', true);
        
        const requestData = {
            item_id: itemId,
            item_type: itemType,
            quantity: quantity
        };
        
        if (colorId) {
            requestData.color_id = colorId;
        }
        
        fetch('/user/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(requestData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.showMessage(data.message || 'Đã thêm vào giỏ hàng!', 'success');
                this.updateCartCount(data.cart_count);
            } else {
                this.showMessage(data.message || 'Có lỗi xảy ra!', 'error');
            }
        })
        .catch(() => {
            this.showMessage('Có lỗi xảy ra khi thêm sản phẩm!', 'error');
        })
        .finally(() => {
            button.prop('disabled', false);
        });
    }

    handleCartRemove(e) {
        e.preventDefault();
        const button = $(e.currentTarget);
        const cartItemId = button.data('id') || button.data('cart-item-id');
        
        if (!cartItemId) {
            this.showMessage('Không tìm thấy sản phẩm!', 'error');
            return;
        }

        // Optimistic update - disable button
        button.prop('disabled', true);
        
        const url = button.data('url') || `/user/cart/remove/${cartItemId}`;
        
        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': button.data('csrf') || $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.showMessage('Đã xóa sản phẩm khỏi giỏ hàng!', 'success');
                this.updateCartCount(data.cart_count);
                
                // Remove item from DOM - handle both desktop and mobile layouts
                const cartItem = button.closest('.cart-item-desktop, .cart-item-row');
                if (cartItem.length) {
                    const isDesktopRow = cartItem.hasClass('cart-item-desktop');
                    const cartItemId = cartItem.data('id');
                    // Remove both desktop and mobile rows for the same item id to keep counts correct
                    const selector = `.cart-item-desktop[data-id="${cartItemId}"], .cart-item-row[data-id="${cartItemId}"]`;
                    $(selector).fadeOut(300, function() {
                        $(this).remove();
                        // Update cart totals after removal
                        if (typeof updateCartTotals === 'function') {
                            updateCartTotals();
                        }
                        if (typeof window.updateCartSectionsVisibility === 'function') {
                            window.updateCartSectionsVisibility();
                        }
                    });
                }
            } else {
                this.showMessage(data.message || 'Có lỗi xảy ra!', 'error');
            }
        })
        .catch(() => {
            this.showMessage('Có lỗi xảy ra khi xóa sản phẩm!', 'error');
        })
        .finally(() => {
            button.prop('disabled', false);
        });
    }

    initializeQuantityInputs() {
        // Initialize all quantity inputs with their current values
        $('.js-cart-quantity, .cart-qty-input').each(function() {
            const input = $(this);
            const currentValue = parseInt(input.val()) || 1;
            input.attr('data-previous-quantity', currentValue);
        });
        
        // Update button states after initialization
        this.updateQuantityButtonStatesForAll();
    }

    handleQuantityInput(e) {
        const input = $(e.currentTarget);
        const value = input.val();
        
        // Remove any non-numeric characters except minus sign
        let cleanValue = value.replace(/[^0-9-]/g, '');
        
        // If it's a negative number or zero, prevent it
        if (cleanValue.startsWith('-') || parseInt(cleanValue) === 0) {
            cleanValue = '1';
            input.val(cleanValue);
            this.showMessage('Số lượng sản phẩm phải lớn hơn 0!', 'error');
        }
        
        // Store the current value for validation
        if (cleanValue && parseInt(cleanValue) > 0) {
            input.attr('data-previous-quantity', cleanValue);
        }
    }

    handleCartQuantityChange(e) {
        const input = $(e.currentTarget);
        const cartItemId = input.data('id') || input.data('cart-item-id');
        const quantity = parseInt(input.val());
        
        // Validate quantity - must be at least 1
        if (!cartItemId || quantity < 1) {
            if (quantity < 1) {
                this.showMessage('Số lượng sản phẩm phải lớn hơn 0!', 'warning');
                // Reset to previous valid value or 1
                const previousQuantity = parseInt(input.attr('data-previous-quantity')) || 1;
                input.val(previousQuantity);
            }
            return;
        }

        const updateUrl = input.data('update-url') || `/user/cart/update/${cartItemId}`;
        const csrfToken = input.data('csrf') || $('meta[name="csrf-token"]').attr('content');

        fetch(updateUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ quantity: quantity })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.updateCartCount(data.cart_count);
                
                // Show success toast for quantity update
                this.showMessage('Đã cập nhật số lượng thành công!', 'success');
                
                // Update item total for both desktop and mobile layouts
                const cartItem = input.closest('.cart-item-desktop, .cart-item-row');
                if (cartItem.length) {
                    // Update item total
                    const itemTotalElement = cartItem.find('.item-total');
                    if (itemTotalElement.length && data.item_total !== undefined) {
                        itemTotalElement.text(this.formatPrice(data.item_total));
                    }
                    // Persist fresh baseline where available
                    if (data.item_price !== undefined) {
                        cartItem.attr('data-base-unit', Number(data.item_price) || 0);
                    }
                    if (data.original_price_before_discount !== undefined) {
                        cartItem.attr('data-original-price', Number(data.original_price_before_discount) || 0);
                    }
                    if (data.current_price !== undefined) {
                        cartItem.attr('data-current-price', Number(data.current_price) || 0);
                    }
                    // Update price UI using new deterministic updater
                    this.updateItemPriceUI(cartItem, data);
                    
                    // Update cart totals
                    if (typeof window.updateCartTotals === 'function') {
                        window.updateCartTotals();
                    }
                }
                
                // Store current quantity for next comparison
                input.attr('data-previous-quantity', quantity);
                
                // Update button states after successful update
                this.updateQuantityButtonStates(input);
            } else {
                this.showMessage(data.message || 'Có lỗi xảy ra!', 'error');
            }
        })
        .catch(() => {
            this.showMessage('Có lỗi xảy ra!', 'error');
        });
    }

    handleQuantityControl(e) {
        e.preventDefault();
        const button = $(e.currentTarget);
        const cartItemId = button.data('id');
        const isIncrease = button.hasClass('qty-increase');
        
        if (!cartItemId) return;
        
        // Find the quantity input in the same cart item
        const cartItem = button.closest('.cart-item-desktop, .cart-item-row');
        const quantityInput = cartItem.find('.cart-qty-input, .js-cart-quantity');
        
        if (!quantityInput.length) return;
        
        let currentQuantity = parseInt(quantityInput.val()) || 1;
        
        // Store previous quantity for comparison
        const previousQuantity = currentQuantity;
        
        if (isIncrease) {
            currentQuantity++;
        } else {
            // Check if current quantity is 1, if so return without action
            if (currentQuantity <= 1) {
                return;
            }
            currentQuantity = Math.max(1, currentQuantity - 1);
        }
        
        // Update input value WITHOUT triggering change event
        quantityInput.val(currentQuantity);
        
        // Store previous quantity as data attribute for comparison
        quantityInput.attr('data-previous-quantity', previousQuantity);
        
        // Manually update the cart without the change event
        this.updateQuantityDirectly(cartItemId, currentQuantity, quantityInput);
        
        // Update button states after quantity change
        this.updateQuantityButtonStates(quantityInput);
    }

    updateQuantityButtonStates(inputElement) {
        const cartItem = inputElement.closest('.cart-item-desktop, .cart-item-row');
        if (!cartItem.length) return;
        
        const currentQuantity = parseInt(inputElement.val()) || 1;
        const decreaseButton = cartItem.find('.qty-decrease');
        const increaseButton = cartItem.find('.qty-increase');
        
        // Disable decrease button when quantity is 1
        if (currentQuantity <= 1) {
            decreaseButton.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
        } else {
            decreaseButton.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
        }
        
        // Enable increase button always
        increaseButton.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
    }

    updateQuantityButtonStatesForAll() {
        // Update button states for all quantity inputs
        $('.js-cart-quantity, .cart-qty-input').each((index, element) => {
            this.updateQuantityButtonStates($(element));
        });
    }

    updateQuantityDirectly(cartItemId, quantity, inputElement) {
        const updateUrl = inputElement.data('update-url') || `/user/cart/update/${cartItemId}`;
        const csrfToken = inputElement.data('csrf') || $('meta[name="csrf-token"]').attr('content');

        fetch(updateUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ quantity: quantity })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.updateCartCount(data.cart_count);
                
                // Show success toast for quantity update
                const previousQuantity = parseInt(inputElement.attr('data-previous-quantity') || 1);
                const action = quantity > previousQuantity ? 'tăng' : 'giảm';
                this.showMessage(`Đã ${action} số lượng thành công!`, 'success');
                
                // Update item total for both desktop and mobile layouts
                const cartItem = inputElement.closest('.cart-item-desktop, .cart-item-row');
                if (cartItem.length) {
                    // Update item total
                    const itemTotalElement = cartItem.find('.item-total');
                    if (itemTotalElement.length && data.item_total !== undefined) {
                        itemTotalElement.text(this.formatPrice(data.item_total));
                    }
                    // Persist fresh baseline where available
                    if (data.item_price !== undefined) {
                        cartItem.attr('data-base-unit', Number(data.item_price) || 0);
                    }
                    if (data.original_price_before_discount !== undefined) {
                        cartItem.attr('data-original-price', Number(data.original_price_before_discount) || 0);
                    }
                    if (data.current_price !== undefined) {
                        cartItem.attr('data-current-price', Number(data.current_price) || 0);
                    }
                    // Update price UI using new deterministic updater
                    this.updateItemPriceUI(cartItem, data);
                    
                    // Update cart totals
                    if (typeof window.updateCartTotals === 'function') {
                        window.updateCartTotals();
                    }
                }
                
                // Store current quantity for next comparison
                inputElement.attr('data-previous-quantity', quantity);
                
                // Update button states after successful update
                this.updateQuantityButtonStates(inputElement);
            } else {
                this.showMessage(data.message || 'Có lỗi xảy ra!', 'error');
            }
        })
        .catch(() => {
            this.showMessage('Có lỗi xảy ra khi cập nhật số lượng!', 'error');
        });
    }

    handleFeatureChange(e) {
        const checkbox = $(e.currentTarget);
        const cartItemId = checkbox.data('id');
        const featureId = checkbox.val();
        const isChecked = checkbox.is(':checked');
        
        if (!cartItemId) return;
        
        // Toast color: green when thêm, blue (info) when bỏ
        const action = isChecked ? 'thêm' : 'bỏ';
        const type = isChecked ? 'success' : 'info';
        this.showMessage(`Đã ${action} tùy chọn!`, type);
        
        // Send update to server to get accurate pricing
        this.updateFeatureOnServer(cartItemId, featureId, isChecked);
    }
    
    updateFeatureOnServer(cartItemId, featureId, isChecked) {
        // Send feature update to server to get accurate pricing
        const updateUrl = `/user/cart/update/${cartItemId}`;
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        
        fetch(updateUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ 
                feature_id: featureId,
                feature_enabled: isChecked,
                update_type: 'feature'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update prices from server response
                const cartItem = $(`.cart-item-desktop[data-id="${cartItemId}"], .cart-item-row[data-id="${cartItemId}"]`);
                if (cartItem.length) {
                    // Keep both desktop and mobile checkboxes in sync
                    $(`.cart-item-desktop[data-id="${cartItemId}"] .cart-feature.js-opt[value="${featureId}"], .cart-item-row[data-id="${cartItemId}"] .cart-feature.js-opt[value="${featureId}"]`).prop('checked', isChecked);
                    // Update base unit price
                    if (data.base_price !== undefined) {
                        cartItem.attr('data-base-unit', data.base_price);
                    }
                    
                    // Update item price and total from server
                    if (data.item_price !== undefined) {
                        cartItem.find('.item-price').text(this.formatPrice(data.item_price));
                    }
                    if (data.item_total !== undefined) {
                        cartItem.find('.item-total').text(this.formatPrice(data.item_total));
                    }
                    // Update price UI using new deterministic updater
                    this.updateItemPriceUI(cartItem, data);
                    
                    // Update cart totals
                    if (typeof window.updateCartTotals === 'function') {
                        window.updateCartTotals();
                    }
                }
            } else {
                // Revert feature selection on error
                const checkbox = $(`.cart-feature[data-id="${cartItemId}"][value="${featureId}"]`);
                if (checkbox.length) {
                    checkbox.prop('checked', !isChecked);
                    this.revertFeaturePrices(cartItemId);
                }
                this.showMessage(data.message || 'Có lỗi xảy ra khi cập nhật tùy chọn!', 'error');
            }
        })
        .catch(() => {
            // Revert feature selection on error
            const checkbox = $(`.cart-feature[data-id="${cartItemId}"][value="${featureId}"]`);
            if (checkbox.length) {
                checkbox.prop('checked', !isChecked);
                this.revertFeaturePrices(cartItemId);
            }
            this.showMessage('Có lỗi xảy ra khi cập nhật tùy chọn!', 'error');
        });
    }
    
    revertFeaturePrices(cartItemId) {
        // Revert to previous prices when feature update fails
        const cartItem = $(`.cart-item-desktop[data-id="${cartItemId}"], .cart-item-row[data-id="${cartItemId}"]`);
        if (cartItem.length) {
            this.recalculateItemPrices(cartItem);
        }
    }
    
    // New deterministic price UI updater
    updateItemPriceUI(cartItem, data) {
        const fmt = (v) => new Intl.NumberFormat('vi-VN').format(Math.max(0, Math.round(Number(v || 0))));
        // Baseline numbers
        let original = Number(cartItem.attr('data-original-price')) || 0;
        let current = Number(cartItem.attr('data-current-price')) || 0;
        if (data.original_price_before_discount !== undefined) original = Number(data.original_price_before_discount) || original;
        if (data.current_price !== undefined) current = Number(data.current_price) || current;
        // If still missing, try DOM
        if (!original) {
            const t = cartItem.find('.js-price-original-val').first().text().replace(/[^\d]/g, '');
            original = t ? parseInt(t, 10) : original;
        }
        if (!current) {
            const t = cartItem.find('.js-price-current-val').first().text().replace(/[^\d]/g, '');
            current = t ? parseInt(t, 10) : current;
        }
        // Derive discount
        const hasDiscount = original > 0 && current > 0 && current < original;
        const discountAmount = hasDiscount ? (original - current) : 0;
        const discountPercent = hasDiscount ? Math.round(((original - current) / original) * 100) : 0;
        // Derive color adj (use explicit field; do NOT infer from item_price)
        let colorAdj = 0;
            if (data.color_price_adjustment !== undefined) {
            colorAdj = Math.max(0, Number(data.color_price_adjustment) || 0);
        }
        if (!Number.isFinite(colorAdj)) colorAdj = 0;
        // Derive addon sum (aggregate, de-duplicate desktop/mobile)
        let addonSumClient = 0;
        const seenKeys = new Set();
        $(`.cart-item-desktop[data-id="${cartItem.data('id')}"] .cart-feature.js-opt:checked, .cart-item-row[data-id="${cartItem.data('id')}"] .cart-feature.js-opt:checked`).each(function(){
            const fee = Math.max(0, Number($(this).data('fee') || 0));
            const name = $(this).closest('label').find('.text-gray-700, .font-medium').first().text().trim();
            const key = `${name}|${fee}`;
            if (!seenKeys.has(key)) {
                seenKeys.add(key);
                addonSumClient += fee;
            }
        });
        let addonSum = addonSumClient;
            if (data.addon_sum !== undefined) {
            addonSum = Math.max(addonSumClient, Math.max(0, Number(data.addon_sum) || 0));
        }
        // Update DOM strictly within hooks
        const originalWrap = cartItem.find('.js-price-original');
        if (original > 0) { originalWrap.show(); originalWrap.find('.js-price-original-val').text(fmt(original)); }
        else { originalWrap.hide(); }
        const discountWrap = cartItem.find('.js-price-discount');
        if (hasDiscount && discountAmount > 0 && discountPercent > 0) {
            discountWrap.show();
            discountWrap.find('.js-price-discount-percent').text(String(discountPercent));
            discountWrap.find('.js-price-discount-amount').text(fmt(discountAmount));
        } else { discountWrap.hide(); }
        const currentWrap = cartItem.find('.js-price-current');
        if (current > 0) { currentWrap.show(); currentWrap.find('.js-price-current-val').text(fmt(current)); }
        else { currentWrap.hide(); }
        const colorWraps = cartItem.find('.js-price-color');
        // Luôn hiển thị dòng màu, kể cả +0 đ; cập nhật theo từng layout để tránh ghép tên từ nhiều phần tử
        colorWraps.each(function(){
            const wrap = $(this);
            const root = wrap.closest('.cart-item-desktop, .cart-item-row');
            const name = (root.find('.selected-color-name').first().text() || '').trim();
            wrap.show();
            wrap.find('.js-price-color-name').text(name);
            wrap.find('.js-price-color-val').text(fmt(colorAdj));
        });
        const addonWrap = cartItem.find('.js-price-addon');
        const addonList = cartItem.find('.js-price-options');
        if (addonSum > 0) {
            const checked = $(`.cart-item-desktop[data-id="${cartItem.data('id')}"] .cart-feature.js-opt:checked, .cart-item-row[data-id="${cartItem.data('id')}"] .cart-feature.js-opt:checked`);
            // Build detailed lines
            let html = '';
            const used = new Set();
            checked.each(function(){
                const fee = Math.max(0, Number($(this).data('fee') || 0));
                const name = $(this).closest('label').find('.text-gray-700, .font-medium').first().text().trim();
                const key = `${name}|${fee}`;
                if (fee > 0 && name && !used.has(key)) {
                    used.add(key);
                    html += `<div class="text-[11px] text-emerald-700">${name}: +${fmt(fee)} đ</div>`;
                }
            });
            // Fallback if server only sends total
            if (!html && data.addon_sum && data.addon_sum > 0) {
                html = `<div class="text-[11px] text-emerald-700">+${fmt(data.addon_sum)} đ</div>`;
            }
            // Render
            addonWrap.hide();
            addonList.html(html).toggle(!!html);
                    } else {
            addonWrap.hide(); addonList.hide().empty();
        }
    }
    
    updatePricesFromServer(cartItemId) {
        // Fetch current prices from server for a specific cart item
        const updateUrl = `/user/cart/update/${cartItemId}`;
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        
        fetch(updateUrl, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const cartItem = $(`.cart-item-desktop[data-id="${cartItemId}"], .cart-item-row[data-id="${cartItemId}"]`);
                if (cartItem.length) {
                    // Update all price information from server
                    if (data.base_price !== undefined) {
                        cartItem.attr('data-base-unit', data.base_price);
                    }
                    if (data.item_price !== undefined) {
                        cartItem.find('.item-price').text(this.formatPrice(data.item_price));
                    }
                    if (data.item_total !== undefined) {
                        cartItem.find('.item-total').text(this.formatPrice(data.item_total));
                    }
                    
                    // Update price UI using new deterministic updater
                    this.updateItemPriceUI(cartItem, data);
        
        // Update cart totals
        if (typeof window.updateCartTotals === 'function') {
            window.updateCartTotals();
        }
                }
            }
        })
        .catch(error => {
            console.error('Failed to update prices from server:', error);
        });
    }

    handleColorChange(e) {
        e.preventDefault();
        const button = $(e.currentTarget);
        const cartItemId = button.data('item-id');
        const colorId = button.data('color-id');
        const colorName = button.data('color-name');
        const updateUrl = button.data('update-url');
        const csrfToken = button.data('csrf') || $('meta[name="csrf-token"]').attr('content');
        
        if (!cartItemId || !colorId || !updateUrl) return;
        
        // Find the cart item
        const cartItem = button.closest('.cart-item-desktop, .cart-item-row');
        
        // Update color selection UI
        cartItem.find('.color-option').removeClass('border-blue-500 ring-2 ring-blue-200').addClass('border-gray-300');
        button.removeClass('border-gray-300').addClass('border-blue-500 ring-2 ring-blue-200');
        
        // Update color name display
        cartItem.find('.selected-color-name').text(colorName);
        
        // Send update to server
        fetch(updateUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ 
                color_id: colorId,
                update_type: 'color'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update prices from server response
                if (data.base_price !== undefined) {
                    cartItem.attr('data-base-unit', data.base_price);
                }
                
                // Update item price and total from server
                if (data.item_price !== undefined) {
                    cartItem.find('.item-price').text(this.formatPrice(data.item_price));
                }
                if (data.item_total !== undefined) {
                    cartItem.find('.item-total').text(this.formatPrice(data.item_total));
                }
                // Update scoped price breakdown
                this.applyPriceHooks(cartItem, data);
                
                // Update cart totals
                if (typeof window.updateCartTotals === 'function') {
                    window.updateCartTotals();
                }
                
                this.showMessage(`Đã đổi màu sang ${colorName}!`, 'success');
            } else {
                this.showMessage(data.message || 'Có lỗi xảy ra khi cập nhật màu!', 'error');
                // Revert UI changes on error
                this.revertColorSelection(cartItem, button);
            }
        })
        .catch(() => {
            this.showMessage('Có lỗi xảy ra khi cập nhật màu sắc!', 'error');
            // Revert UI changes on error
            this.revertColorSelection(cartItem, button);
        });
    }

    recalculateItemPrices(cartItem) {
        // This method is now used for fallback calculations
        // Server should provide accurate pricing for color and feature changes
        const baseUnit = parseInt(cartItem.data('base-unit')) || 0;
        const quantity = parseInt(cartItem.find('.cart-qty-input, .js-cart-quantity').val()) || 1;
        
        // Calculate display unit (base + selected features)
        let displayUnit = baseUnit;
        const checkedFeatures = cartItem.find('.cart-feature.js-opt:checked');
        checkedFeatures.each(function() {
            displayUnit += parseInt($(this).data('fee')) || 0;
        });
        
        // Calculate item total
        const itemTotal = displayUnit * quantity;
        
        // Update prices in the cart item
        cartItem.find('.item-price').text(this.formatPrice(displayUnit));
        cartItem.find('.item-total').text(this.formatPrice(itemTotal));
        
        // Update cart totals
        if (typeof window.updateCartTotals === 'function') {
            window.updateCartTotals();
        }
    }

    revertColorSelection(cartItem, button) {
        // Revert color selection UI
        button.removeClass('border-blue-500 ring-2 ring-blue-200').addClass('border-gray-300');
        
        // Find the previously selected color and restore it
        const previouslySelected = cartItem.find('.color-option.border-blue-500');
        if (previouslySelected.length) {
            previouslySelected.removeClass('border-gray-300').addClass('border-blue-500 ring-2 ring-blue-200');
        }
    }

    handleCartClear(e) {
        e.preventDefault();
        const button = $(e.currentTarget);
        
        // Modern confirm dialog like wishlist
        function showConfirmDialog(title, message, confirmText, cancelText, onConfirm){
            const existing = document.querySelector('.fast-confirm-dialog');
            if (existing) existing.remove();
            const wrapper = document.createElement('div');
            wrapper.className = 'fast-confirm-dialog fixed inset-0 z-[100000] bg-black/50 backdrop-blur-sm flex items-center justify-center p-4';
            wrapper.innerHTML = `
                <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all duration-200 scale-95 opacity-0">
                    <div class="p-6">
                        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                            <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">${title}</h3>
                        <p class="text-gray-600 text-center mb-6">${message}</p>
                        <div class="flex space-x-3">
                            <button class="fast-cancel flex-1 px-4 py-2.5 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition-colors duration-200">${cancelText}</button>
                            <button class="fast-confirm flex-1 px-4 py-2.5 text-white bg-red-600 hover:bg-red-700 rounded-lg font-medium transition-colors duration-200">${confirmText}</button>
                        </div>
                    </div>
                </div>`;
            document.body.appendChild(wrapper);
            const panel = wrapper.firstElementChild;
            requestAnimationFrame(()=>{ panel.classList.remove('scale-95','opacity-0'); panel.classList.add('scale-100','opacity-100'); });
            const close = ()=> wrapper.remove();
            wrapper.addEventListener('click', (ev)=>{ if (ev.target === wrapper) close(); });
            wrapper.querySelector('.fast-cancel').addEventListener('click', close);
            wrapper.querySelector('.fast-confirm').addEventListener('click', ()=>{ close(); onConfirm && onConfirm(); });
            document.addEventListener('keydown', function esc(e){ if (e.key==='Escape'){ close(); document.removeEventListener('keydown', esc); } });
        }

        showConfirmDialog(
            'Xóa toàn bộ giỏ hàng?',
            'Bạn có chắc chắn muốn xóa tất cả sản phẩm khỏi giỏ hàng? Hành động này không thể hoàn tác.',
            'Xóa tất cả',
            'Hủy',
            () => {
                button.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
        fetch('/user/cart/clear', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
                .then(r=>r.json())
                .then(data=>{
                    if (!data.success) throw new Error(data.message||'error');
                this.updateCartCount(data.cart_count);
                    $('.cart-item-desktop, .cart-item-row').fadeOut(200, function(){ $(this).remove(); });
                    this.showMessage('Đã xóa toàn bộ giỏ hàng!', 'success');
                    // Ensure sections disappear and show empty-state like initial
                    $('#car-section, #accessory-section').fadeOut(150);
                    setTimeout(()=> window.location.reload(), 200);
                })
                .catch(()=>{ this.showMessage('Có lỗi xảy ra khi xóa toàn bộ giỏ hàng!', 'error'); })
                .finally(()=>{ button.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed'); });
            }
        );
    }

    updateCartCount(count) {
        
        // Update all cart count badges
        const selectors = [
            '.cart-count',
            '#cart-count-badge', 
            '#cart-count-badge-mobile',
            '[data-cart-count]'
        ];

        selectors.forEach(selector => {
            $(selector).each(function() {
                const badge = $(this);
                badge.text(count > 99 ? '99+' : count);
                
                // Show/hide badges based on count
                if (count > 0) {
                    badge.removeClass('hidden');
                    
                } else {
                    badge.addClass('hidden');
                    
                }
            });
        });
        
        // IMPORTANT: Store count in localStorage for cross-page/tab synchronization
        localStorage.setItem('cart_count', count.toString());
        
    }

    refreshCartCount() {
        fetch('/user/cart/count', {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.updateCartCount(data.cart_count);
            }
        })
        .catch(error => {
            console.error('Failed to refresh cart count:', error);
        });
    }

    showMessage(message, type = 'info') {
        // Try to use existing showMessage function
        if (typeof window.showMessage === 'function') {
            window.showMessage(message, type);
        } else if (typeof window.showToast === 'function') {
            window.showToast(message, type);
        } else {
            // Fallback: create a simple toast if no system is available
            this.createSimpleToast(message, type);
        }
    }

    createSimpleToast(message, type = 'info') {
        // Remove any existing toasts to prevent stacking
        const existingToasts = document.querySelectorAll('.toast-notification');
        existingToasts.forEach(toast => toast.remove());

        const toast = document.createElement('div');
        toast.className = `toast-notification`;

        const colors = {
            success: 'bg-green-500 text-white',
            error: 'bg-red-500 text-white',
            warning: 'bg-yellow-500 text-white',
            info: 'bg-blue-500 text-white'
        };

        toast.className += ` ${colors[type] || colors.info}`;
        toast.innerHTML = `
            <div class="toast-content">
                <span class="toast-message">${message}</span>
                <button class="toast-close" aria-label="Đóng" onclick="this.closest('.toast-notification').remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;

        document.body.appendChild(toast);

        // Animate in
        requestAnimationFrame(() => {
            toast.classList.add('show');
        });

        // Auto remove
        setTimeout(() => {
            toast.classList.add('hide');
            setTimeout(() => {
                if (toast.parentElement) toast.remove();
            }, 300);
        }, 3000);
    }

    applyPriceHooks(cartItem, data) {
        // Safely update only inside the current cart item using dedicated hooks
        const nf = (v) => this.formatPrice(Number(v || 0));
        const parseDom = (sel) => {
            const t = cartItem.find(sel).first().text().replace(/[^\d]/g, '');
            return t ? parseInt(t, 10) : 0;
        };

        // Baseline from data attributes first (avoid parsing issues), then DOM, then server
        let original = Number(cartItem.attr('data-original-price')) || 0;
        let current = Number(cartItem.attr('data-current-price')) || 0;
        if (!original) original = parseDom('.js-price-original-val');
        if (!current) current = parseDom('.js-price-current-val');
        if (data.original_price_before_discount !== undefined) original = Number(data.original_price_before_discount) || original;
        if (data.current_price !== undefined) current = Number(data.current_price) || current;

        // Prefer server-provided numbers if available
        if (data.original_price_before_discount !== undefined) original = Number(data.original_price_before_discount) || 0;
        if (data.current_price !== undefined) current = Number(data.current_price) || current;

        // Always update original/current visibility
        const originalWrap = cartItem.find('.js-price-original');
        if (original > 0) {
            originalWrap.show();
            originalWrap.find('.js-price-original-val').text(nf(original));
        } else {
            originalWrap.hide();
        }

        const currentWrap = cartItem.find('.js-price-current');
        if (current > 0) {
            currentWrap.show();
            currentWrap.find('.js-price-current-val').text(nf(current));
        } else {
            currentWrap.hide();
        }

        // Discount: use server flags if present; else compute from original/current
        let hasDiscount = (data.has_discount !== undefined) ? !!data.has_discount : (original > 0 && current > 0 && current < original);
        let discountAmount = (data.discount_amount !== undefined) ? Number(data.discount_amount) : (hasDiscount ? Math.max(0, original - current) : 0);
        let discountPercent = (data.discount_percentage !== undefined) ? Number(data.discount_percentage) : (hasDiscount && original > 0 ? Math.round(((original - current) / original) * 100) : 0);
        const discountWrap = cartItem.find('.js-price-discount');
        if (hasDiscount && discountPercent > 0 && discountAmount > 0) {
            discountWrap.show();
            discountWrap.find('.js-price-discount-percent').text(String(discountPercent));
            discountWrap.find('.js-price-discount-amount').text(nf(discountAmount));
        } else {
            discountWrap.hide();
        }

        // Color adjustment: ONLY use server field, never infer from totals
        let colorAdj = 0;
        if (data.color_price_adjustment !== undefined) {
            colorAdj = Math.max(0, Number(data.color_price_adjustment));
                } else {
            // Fallback: compute from data attributes on first paint
            const priceWithColor = Number(cartItem.attr('data-base-unit')) || 0;
            if (priceWithColor > 0 && current > 0) {
                colorAdj = Math.max(0, priceWithColor - current);
            }
        }
        const colorWrap = cartItem.find('.js-price-color');
        if (colorAdj >= 0) {
            const colorName = cartItem.find('.selected-color-name').text() || '';
            colorWrap.show();
            colorWrap.find('.js-price-color-name').text(colorName);
            colorWrap.find('.js-price-color-val').text(nf(colorAdj));
        }

        // Addon sum: use server else compute from checked options
        let addonSum = (data.addon_sum !== undefined) ? Number(data.addon_sum) : 0;
        if (data.addon_sum === undefined) {
            cartItem.find('.cart-feature.js-opt:checked').each(function() {
                addonSum += Number($(this).data('fee') || 0);
            });
        }
        const addonWrap = cartItem.find('.js-price-addon');
        if (addonSum > 0) {
            addonWrap.show();
            addonWrap.find('.js-price-addon-val').text(nf(addonSum));
        } else {
            addonWrap.hide();
        }
    }

    formatPrice(price) {
        return new Intl.NumberFormat('vi-VN').format(price);
    }
}

// Initialize CartManager when DOM is ready
function initializeCartManager() {
    // Destroy existing instance if exists
    if (window.cartManager) {
        // Remove all event handlers
        $(document).off('click', '.js-add-to-cart');
        $(document).off('click', '.js-remove-from-cart');
        $(document).off('change', '.js-cart-quantity, .cart-qty-input');
        $(document).off('click', '.js-clear-cart');
        $(document).off('click', '.qty-increase, .qty-decrease');
        $(document).off('change', '.cart-feature.js-opt');
        $(document).off('click', '.color-option');
    }

    window.cartManager = new CartManager();
}

// Initialize on DOM ready
$(document).ready(initializeCartManager);

// Also initialize on page show (for browser navigation)
$(window).on('pageshow', function() {
    
    if (window.cartManager) {
        // Check if localStorage count is different from current display
        const storedCartCount = parseInt(localStorage.getItem('cart_count')) || 0;
        const currentCartCount = parseInt($('.cart-count, #cart-count-badge, #cart-count-badge-mobile').first().text()) || 0;

        if (storedCartCount !== currentCartCount) {
            
            window.cartManager.updateCartCount(storedCartCount);
        } else {
            
            window.cartManager.refreshCartCount();
        }
    }
    
    // Also reconcile counts from server when page is shown
    if (window.CountManager) {
        window.CountManager.reconcileCart();
        window.CountManager.reconcileWishlist();
    }
});

// Global functions for backward compatibility
window.updateCartCount = function(count) {
    if (window.cartManager) {
        window.cartManager.updateCartCount(count);
    }
};

window.refreshCartCount = function() {
    if (window.cartManager) {
        window.cartManager.refreshCartCount();
    }
};


