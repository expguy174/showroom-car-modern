/**
 * Cart Manager - Handles cart operations
 */

class CartManager {
    constructor() {
        // Track items being processed to prevent race conditions
        this.processing = new Set();
        this.recent = new Map();
        this.recentWindowMs = 500; // 500ms debounce window
        this.reconciling = false; // Track if reconciliation is in progress
        this.lastReconcile = 0; // Timestamp of last reconciliation
        this.checkingCount = false; // Prevent multiple simultaneous count checks
        this.lastCountCheck = 0; // Timestamp of last count check
        this.countCheckDebounceMs = 200; // Debounce count checks

        // Cache DOM elements for better performance
        this.cachedElements = new Map();
        // Batch operations for better performance
        this.batchOperations = new Set();
        this.batchTimeout = null;
        // Key cache for better performance
        this.keyCache = new Map();

        // localStorage keys for consistency with wishlist
        this.STORAGE_KEY = 'cart_items'; // [{t:'car_variant'|'accessory', i:Number, q:Number, c:Number}]
        this.COUNT_KEY = 'cart_count';

        // Bind once and reuse for add/removeEventListener to avoid leaks
        this.boundHandleStorageChange = this.handleStorageChange.bind(this);

        this.init();
    }

    // Helper methods for debouncing - optimized with caching and cleanup
    key(itemType, itemId) {
        const cacheKey = `${itemType}:${itemId}`;
        if (!this.keyCache.has(cacheKey)) {
            // Clean cache if it gets too large (prevent memory leak)
            if (this.keyCache.size >= 1000) {
                this.keyCache.clear();
            }
            this.keyCache.set(cacheKey, cacheKey);
        }
        return this.keyCache.get(cacheKey);
    }

    // localStorage management methods (consistent with wishlist)
    loadFromStorage() {
        try {
            const raw = localStorage.getItem(this.STORAGE_KEY);
            if (!raw) return [];
            return JSON.parse(raw);
        } catch(_) {
            return [];
        }
    }

    computeLocalCount(items) {
        try {
            if (!Array.isArray(items)) return 0;
            return items.reduce((sum, it) => sum + Math.max(0, parseInt(it.q || 0, 10) || 0), 0);
        } catch(_) { return 0; }
    }

    saveToStorage(items) {
        try {
            localStorage.setItem(this.STORAGE_KEY, JSON.stringify(items));
            localStorage.setItem(this.COUNT_KEY, String(items.length));
        } catch(_) {}
    }

    getCartItems() {
        return this.loadFromStorage();
    }

    addToLocalCart(itemType, itemId, quantity = 1, colorId = null) {
        const items = this.getCartItems();
        const existingIndex = items.findIndex(item => 
            item.t === itemType && item.i === itemId && item.c === colorId
        );
        
        if (existingIndex >= 0) {
            items[existingIndex].q += quantity;
        } else {
            items.push({ t: itemType, i: itemId, q: quantity, c: colorId });
        }
        
        this.saveToStorage(items);
        return items;
    }

    removeFromLocalCart(itemType, itemId, colorId = null) {
        const items = this.getCartItems();
        const filteredItems = items.filter(item => 
            !(item.t === itemType && item.i === itemId && item.c === colorId)
        );
        
        this.saveToStorage(filteredItems);
        return filteredItems;
    }

    updateLocalCartQuantity(itemType, itemId, quantity, colorId = null) {
        const items = this.getCartItems();
        const itemIndex = items.findIndex(item => 
            item.t === itemType && item.i === itemId && item.c === colorId
        );
        
        if (itemIndex >= 0) {
            if (quantity <= 0) {
                items.splice(itemIndex, 1);
            } else {
                items[itemIndex].q = quantity;
            }
        }
        
        this.saveToStorage(items);
        return items;
    }

    markRecent(itemType, itemId) {
        const key = this.key(itemType, itemId);
        this.recent.set(key, Date.now());
        setTimeout(() => this.recent.delete(key), this.recentWindowMs);
    }

    isRecent(itemType, itemId) {
        const key = this.key(itemType, itemId);
        const timestamp = this.recent.get(key);
        return timestamp && (Date.now() - timestamp) < this.recentWindowMs;
    }

    init() {
        this.bindEvents();
        this.loadCartCountFromStorage();
        
        // Don't call checkServerCountAndReconcile here - let app.js handle it
        // This prevents duplicate calls on initial load
        
        this.initializeQuantityInputs();
        // Helper to update sections visibility when items become empty
        window.updateCartSectionsVisibility = function() {
            // Count both desktop and mobile rows to avoid relying on CSS visibility
            const carRows = $('#car-section tbody .cart-item-desktop').length + $('#car-section tbody .cart-item-row').length;
            const accRows = $('#accessory-section tbody .cart-item-desktop').length + $('#accessory-section tbody .cart-item-row').length;
            if (carRows === 0) { $('#car-section').fadeOut(150); } else { $('#car-section').show(); }
            if (accRows === 0) { $('#accessory-section').fadeOut(150); } else { $('#accessory-section').show(); }
            if (carRows === 0 && accRows === 0) {
                if (window.cartManager && typeof window.cartManager.showEmptyCartState === 'function') {
                    window.cartManager.showEmptyCartState();
                }
            }
        };
    }

    bindEvents() {
        // Cart add
        $(document).on('click.cart', '.js-add-to-cart', this.handleCartAdd.bind(this));
        
        // Cart remove
        $(document).on('click.cart', '.js-remove-from-cart', this.handleCartRemove.bind(this));
        // Support existing remove buttons
        $(document).on('click.cart', '.remove-item-btn', this.handleCartRemove.bind(this));
        
        // Cart update quantity - handle both desktop and mobile
        $(document).on('change.cart', '.js-cart-quantity, .cart-qty-input', this.handleCartQuantityChange.bind(this));
        
        // Prevent invalid input (negative numbers, zero)
        $(document).on('input.cart', '.js-cart-quantity, .cart-qty-input', this.handleQuantityInput.bind(this));
        
        // Cart clear
        $(document).on('click.cart', '.js-clear-cart', this.handleCartClear.bind(this));
        // Support existing clear button by id
        $(document).on('click.cart', '#clear-cart-btn', this.handleCartClear.bind(this));
        
        // Quantity controls - handle both desktop and mobile
        $(document).on('click.cart', '.qty-increase, .qty-decrease', this.handleQuantityControl.bind(this));
        
        // Feature selection for both layouts
        $(document).on('change.cart', '.cart-feature.js-opt', this.handleFeatureChange.bind(this));
        
        // Color selection for both layouts
        $(document).on('click.cart', '.color-option', this.handleColorChange.bind(this));
        
        // Listen for cart count changes from other pages/tabs
        window.addEventListener('storage', this.boundHandleStorageChange);
        
        // Prevent duplicate global updates creating oscillation
        // If some legacy code calls window.updateCartCount, ensure it doesn't recursively trigger manager updates
        if (!window.__cartCountGuardInstalled) {
            window.__cartCountGuardInstalled = true;
            const original = window.updateCartCount;
            window.updateCartCount = function(count){
                const n = parseInt(count || 0, 10) || 0;
                const prev = parseInt(localStorage.getItem('cart_count') || '0', 10) || 0;
                if (n === prev) {
                    // Only paint badges; skip LS write to avoid storage event loops
                    const sels = ['.cart-count','#cart-count-badge','#cart-count-badge-mobile','[data-cart-count]'];
                    if (window.paintBadge) { window.paintBadge(sels, n); }
                    return;
                }
                // Write once then paint
                try { localStorage.setItem('cart_count', String(n)); } catch(_) {}
                if (window.paintBadge) { window.paintBadge(['.cart-count','#cart-count-badge','#cart-count-badge-mobile','[data-cart-count]'], n); }
            }
        }
    }

    handleCartAdd(e) {
        e.preventDefault();
        const button = $(e.currentTarget);
        
        // Optimized data extraction
        const itemIdRaw = button.data('item-id') || button.attr('data-item-id');
        const itemType = (button.data('item-type') || button.attr('data-item-type') || 'car_variant').toString();
        let quantity = parseInt(button.data('quantity')) || 1;
        const itemId = parseInt(itemIdRaw, 10);
        const colorId = button.data('color-id') || null;
        
        // Validate inputs - optimized validation
        if (!Number.isFinite(itemId) || itemId <= 0 || !itemType || quantity <= 0 || quantity !== Math.floor(quantity)) {
            this.showMessage('Không tìm thấy sản phẩm!', 'error');
            return;
        }
        if (!Number.isFinite(quantity) || quantity <= 0) quantity = 1;

        const itemKey = this.key(itemType, itemId);
        
        // Prevent multiple rapid clicks on the same item
        if (this.processing.has(itemKey)) {
            return;
        }
        
        // Check if this is a recent action (within 500ms)
        if (this.isRecent(itemType, itemId)) {
            return;
        }

        // CRITICAL: Optimistic UI - add to localStorage immediately
        const updatedItems = this.addToLocalCart(itemType, itemId, quantity, colorId);
        this.updateCartCount(this.computeLocalCount(updatedItems));
        
        // Mark as processing
        this.processing.add(itemKey);
        
        // Disable button temporarily to prevent rapid clicks
        button.prop('disabled', true);
        
        // Add loading state - replace entire button content
        const originalContent = button.html();
        button.html('<i class="fas fa-spinner fa-spin mr-2"></i>Đang thêm...');
        
        // Mark as recent to prevent duplicate actions
        this.markRecent(itemType, itemId);
        
        const requestData = {
            item_id: itemId,
            item_type: itemType,
            quantity: quantity
        };
        
        if (colorId) {
            requestData.color_id = colorId;
        }
        
        const watchdog = setTimeout(() => {
            if (this.processing.has(itemKey)) {
                button.prop('disabled', false);
                button.html(originalContent);
                this.processing.delete(itemKey);
                this.showMessage('Kết nối chậm, đã khôi phục nút.', 'warning');
            }
        }, 8000);

        fetch(`/user/cart/add?t=${Date.now()}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Cache-Control': 'no-store'
            },
            body: JSON.stringify(requestData),
            cache: 'no-store'
        })
        .then(response => {
            console.log('Add to cart response:', response);
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Add to cart data:', data);
            if (data.success) {
                this.showMessage(data.message || 'Đã thêm vào giỏ hàng!', 'success');
                this.updateCartCount(parseInt(data.cart_count || 0, 10));
            } else {
                this.showMessage(data.message || 'Có lỗi xảy ra!', 'error');
            }
        })
        .catch((error) => {
            console.error('Add to cart error:', error);
            this.showMessage('Có lỗi xảy ra khi thêm sản phẩm!', 'error');
        })
        .finally(() => {
            clearTimeout(watchdog);
            // Re-enable button after a short delay
            setTimeout(() => {
                button.prop('disabled', false);
                // Restore original button content
                button.html(originalContent);
                this.processing.delete(itemKey);
            }, 800);
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

        const itemKey = `remove:${cartItemId}`;
        
        // Prevent multiple rapid clicks on the same item
        if (this.processing.has(itemKey)) {
            return;
        }

        // CRITICAL: Optimistic UI - remove from localStorage immediately if we have enough info
        // Try to read metadata from DOM; if missing, fallback to server recon after success
        let performedLocalRemoval = false;
        const cartItemElement = button.closest('.cart-item-desktop, .cart-item-row');
        if (cartItemElement && cartItemElement.length) {
            const itemType = cartItemElement.data('item-type');
            const itemId = cartItemElement.data('item-id');
            const colorId = cartItemElement.data('color-id');
            if (itemType && itemId) {
                const updatedItems = this.removeFromLocalCart(String(itemType), Number(itemId), colorId ?? null);
                this.updateCartCount(this.computeLocalCount(updatedItems));
                performedLocalRemoval = true;
            }
        }
        
        // Mark as processing
        this.processing.add(itemKey);
        
        // Disable button temporarily to prevent rapid clicks
        button.prop('disabled', true);
        
        // Add loading state - replace entire button content
        const originalContent = button.html();
        button.html('<i class="fas fa-spinner fa-spin"></i>');
        
        // Optimistic update - remove from DOM immediately
        const cartItem = button.closest('.cart-item-desktop, .cart-item-row');
        if (cartItem.length) {
            const cartItemId = cartItem.data('id');
            // Remove both desktop and mobile rows for the same item id
            const selector = `.cart-item-desktop[data-id="${cartItemId}"], .cart-item-row[data-id="${cartItemId}"]`;
            $(selector).fadeOut(200, function() {
                $(this).remove();
                // Update cart totals after removal
                if (typeof updateCartTotals === 'function') {
                    updateCartTotals();
                }
                if (typeof window.updateCartSectionsVisibility === 'function') {
                    window.updateCartSectionsVisibility();
                }
                
                // Check if cart is now empty and show empty state
                const remainingItems = $('.cart-item-desktop, .cart-item-row').length;
                if (remainingItems === 0) {
                    // Hide sections and clear button
                    $('#car-section, #accessory-section').fadeOut(150);
                    $('#clear-cart-btn').closest('.bg-white').fadeOut(150);
                    
                    // Show empty state after sections are hidden
                    setTimeout(() => {
                        if (window.cartManager && typeof window.cartManager.showEmptyCartState === 'function') {
                            window.cartManager.showEmptyCartState();
                        }
                    }, 200);
                }
            });
        }
        
        const url = button.data('url') || `/user/cart/remove/${cartItemId}`;
        
        const rmWatchdog = setTimeout(() => {
            if (this.processing.has(itemKey)) {
                button.prop('disabled', false);
                button.html(originalContent);
                this.processing.delete(itemKey);
                this.showMessage('Kết nối chậm, đã khôi phục nút.', 'warning');
            }
        }, 8000);

        fetch(`${url}?t=${Date.now()}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': button.data('csrf') || $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Cache-Control': 'no-store'
            },
            cache: 'no-store'
        })
        .then(response => {
            console.log('Remove from cart response:', response);
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Remove from cart data:', data);
            if (data.success) {
                this.showMessage('Đã xóa sản phẩm khỏi giỏ hàng!', 'success');
                // Fetch full cart from server immediately to sync LS and badges without delay
                this.fetchAllCartFromServer().then((ok)=>{
                    if (!ok) {
                        // Fallback: at least sync count from server response
                        if (typeof data.cart_count !== 'undefined') {
                            this.updateCartCount(parseInt(data.cart_count, 10) || 0);
                        } else {
                            this.checkServerCountAndReconcile();
                        }
                    }
                });
            } else {
                throw new Error(data.message || 'error');
            }
        })
        .catch((error) => {
            console.error('Remove from cart error:', error);
            this.showMessage('Có lỗi xảy ra khi xóa sản phẩm!', 'error');
            // Revert optimistic UI if needed by re-fetching from server without reloading
            this.fetchAllCartFromServer().then(()=>{
                try { if (typeof window.updateCartTotals === 'function') window.updateCartTotals(); } catch(_) {}
                try { if (typeof window.updateCartSectionsVisibility === 'function') window.updateCartSectionsVisibility(); } catch(_) {}
            });
        })
        .finally(() => {
            clearTimeout(rmWatchdog);
            // Re-enable button immediately since DOM is already updated
            button.prop('disabled', false);
            button.html(originalContent);
            this.processing.delete(itemKey);
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
        const cartItemId = parseInt(input.data('id') || input.data('cart-item-id'), 10);
        let quantity = parseInt(input.val(), 10);
        
        // Validate quantity - must be at least 1
        if (!Number.isFinite(cartItemId) || cartItemId <= 0 || !Number.isFinite(quantity) || quantity < 1) {
            if (!Number.isFinite(quantity) || quantity < 1) {
                this.showMessage('Số lượng sản phẩm phải lớn hơn 0!', 'warning');
                // Reset to previous valid value or 1
                const previousQuantity = parseInt(input.attr('data-previous-quantity')) || 1;
                input.val(previousQuantity);
            }
            return;
        }
        // Ensure integer quantity
        quantity = Math.max(1, Math.floor(quantity));

        const updateUrl = input.data('update-url') || `/user/cart/update/${cartItemId}`;
        const csrfToken = input.data('csrf') || $('meta[name="csrf-token"]').attr('content');

        fetch(`${updateUrl}?t=${Date.now()}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Cache-Control': 'no-store'
            },
            body: JSON.stringify({ quantity: quantity }),
            cache: 'no-store'
        })
        .then(response => {
            console.log('Update quantity response:', response);
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Update quantity data:', data);
            if (data.success) {
                this.updateCartCount(parseInt(data.cart_count || 0, 10));
                
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
        const cartItemId = parseInt(button.data('id'), 10);
        const isIncrease = button.hasClass('qty-increase');
        
        if (!Number.isFinite(cartItemId) || cartItemId <= 0) return;
        
        const itemKey = `qty:${cartItemId}:${isIncrease ? 'inc' : 'dec'}`;
        
        // Prevent multiple rapid clicks on the same quantity control
        if (this.processing.has(itemKey)) {
            return;
        }
        
        // Mark as processing
        this.processing.add(itemKey);
        
        // Find the quantity input in the same cart item
        const cartItem = button.closest('.cart-item-desktop, .cart-item-row');
        const quantityInput = cartItem.find('.cart-qty-input, .js-cart-quantity');
        
        if (!quantityInput.length) return;
        
        let currentQuantity = parseInt(quantityInput.val(), 10);
        if (!Number.isFinite(currentQuantity) || currentQuantity < 1) currentQuantity = 1;
        
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
        
        // Cleanup processing after a short delay
        setTimeout(() => {
            this.processing.delete(itemKey);
        }, 500);
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
                this.updateCartCount(parseInt(data.cart_count || 0, 10));
                
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
        // Chỉ hiển thị dòng màu khi có màu được chọn
        colorWraps.each(function(){
            const wrap = $(this);
            const root = wrap.closest('.cart-item-desktop, .cart-item-row');
            const name = (root.find('.selected-color-name').first().text() || '').trim();
            const hasColorSelected = !!name && name !== 'Chưa chọn';
            
            if (hasColorSelected && colorAdj >= 0) {
                wrap.show();
                wrap.find('.js-price-color-name').text(name);
                wrap.find('.js-price-color-val').text(fmt(colorAdj));
            } else {
                wrap.hide();
            }
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
        // Note: Do NOT clear immediately. Wait for user confirmation.
        
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
                
                // Optimistic update - clear local state and UI immediately AFTER user confirms
                this.saveToStorage([]);
                this.updateCartCount(0);
                try { localStorage.setItem('cart_last_action', String(Date.now())); } catch(_) {}
                // Optimistic update - clear UI immediately
                this.clearCartUI();
                
                // Server call
                const clrWatchdog = setTimeout(() => {
                    button.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
                    this.showMessage('Kết nối chậm, đã khôi phục nút.', 'warning');
                }, 8000);

                fetch(`/user/cart/clear?t=${Date.now()}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Cache-Control': 'no-store'
                    },
                    cache: 'no-store'
                })
                .then(r=>r.json())
                .then(data=>{
                    if (!data.success) throw new Error(data.message||'error');
                    
                    // Ensure cart count is properly updated everywhere
                    const newCount = parseInt(data.cart_count || 0, 10);
                    this.updateCartCount(newCount);
                    // Local storage already cleared above; keep empty state
                    
                    // Don't call window.updateCartCount to avoid infinite loop
                    // The global function is meant to be called from outside, not from within CartManager
                    
                    this.showMessage('Đã xóa toàn bộ giỏ hàng!', 'success');

                    // Short retry loop to confirm server-side emptiness and avoid stale residues from caches/CDN
                    const tryConfirmEmpty = async (attempt = 1) => {
                        const ok = await this.fetchAllCartFromServer();
                        const items = this.getCartItems();
                        const len = Array.isArray(items) ? items.length : 0;
                        if (ok && len === 0) {
                            this.updateCartCount(0);
                            try { if (typeof window.updateCartTotals === 'function') window.updateCartTotals(); } catch(_) {}
                            try { if (typeof window.updateCartSectionsVisibility === 'function') window.updateCartSectionsVisibility(); } catch(_) {}
                            return true;
                        }
                        if (attempt < 3) {
                            return new Promise(res => setTimeout(async () => res(await tryConfirmEmpty(attempt + 1)), 200));
                        }
                        // Finalize UI as empty even if server still not caught up; next reconcile will fix if needed
                        this.saveToStorage([]);
                        this.updateCartCount(0);
                        return false;
                    };
                    tryConfirmEmpty();
                })
                .catch((error)=> { 
                    // Keep optimistic empty UI; reconcile in background without reloading
                    this.showMessage('Có lỗi xảy ra khi xóa toàn bộ giỏ hàng!', 'error');
                    this.saveToStorage([]);
                    this.updateCartCount(0);
                    setTimeout(() => { this.fetchAllCartFromServer(); }, 300);
                })
                .finally(()=>{ clearTimeout(clrWatchdog); button.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed'); });
            }
        );
    }

    loadCartCountFromStorage() {
        // Load cart count from localStorage first for immediate display
        const items = this.getCartItems();
        const count = this.computeLocalCount(items);
        
        // Always update to ensure consistency across navigation
        this.updateCartCount(count);
    }

    clearCartUI() {
        // Clear all cart items from DOM
        $('.cart-item-desktop, .cart-item-row').fadeOut(200, function(){ $(this).remove(); });
        
        // Hide sections
        $('#car-section, #accessory-section').fadeOut(150);
        
        // Hide clear button
        $('#clear-cart-btn').closest('.bg-white').fadeOut(150);
        
        // Show empty state after sections are hidden
        setTimeout(() => {
            this.showEmptyCartState();
        }, 200);
        
        // Update cart count to 0 and ensure localStorage is updated
        this.updateCartCount(0);
        
        // Force update localStorage immediately for consistency
        localStorage.setItem('cart_count', '0');
        
        // Don't call window.updateCartCount to avoid infinite loop
        // The global function is meant to be called from outside, not from within CartManager
    }

    showEmptyCartState() {
        // Create empty state HTML that matches the default template exactly
        const emptyStateHTML = `
            <div class="text-center py-16">
                <div class="w-32 h-32 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full mx-auto mb-8 flex items-center justify-center">
                    <i class="fas fa-car text-blue-500 text-5xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 mb-4">Giỏ hàng trống</h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto text-lg">
                    Bạn chưa có sản phẩm nào trong giỏ hàng. Hãy khám phá các xe hơi và phụ kiện chất lượng cao!
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a href="/products?type=car" 
                       class="inline-flex items-center justify-center bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 sm:px-8 py-3 sm:py-4 rounded-xl font-semibold hover:from-blue-700 hover:to-indigo-700 transition duration-300 transform hover:scale-105 shadow-lg w-full sm:w-auto min-w-[200px]">
                        <i class="fas fa-car mr-2 sm:mr-3"></i>
                        <span class="text-sm sm:text-base">Xem xe hơi</span>
                    </a>
                    <a href="/products?type=accessory" 
                       class="inline-flex items-center justify-center bg-gradient-to-r from-emerald-600 to-teal-600 text-white px-6 sm:px-8 py-3 sm:py-4 rounded-xl font-semibold hover:from-emerald-700 hover:to-teal-700 transition duration-300 transform hover:scale-105 shadow-lg w-full sm:w-auto min-w-[200px]">
                        <i class="fas fa-tools mr-2 sm:mr-3"></i>
                        <span class="text-sm sm:text-base">Xem phụ kiện</span>
                    </a>
                </div>
            </div>
        `;
        
        // Replace only the cart content area, not the entire container
        const cartContainer = $('.container.mx-auto.px-4.sm\\:px-6.lg\\:px-8.py-8');
        if (cartContainer.length) {
            cartContainer.html(emptyStateHTML);
        } else {
            // Fallback: find the py-8 container
            $('.py-8').html(emptyStateHTML);
        }
    }

    async getServerCount() {
        try {
            const response = await fetch(`/user/cart/count?t=${Date.now()}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Cache-Control': 'no-store' },
                cache: 'no-store'
            });
            
            if (response.ok) {
                const data = await response.json();
                if (data.success) {
                    // Ensure we return a number, not a string
                    return parseInt(data.cart_count || 0, 10);
                }
            }
        } catch (error) {
            console.warn('Failed to get server cart count:', error);
        }
        return 0;
    }

    async checkServerCountAndReconcile() {
        // Prevent multiple simultaneous count checks
        if (this.checkingCount) {
            return;
        }
        
        // Debounce rapid successive calls
        const now = Date.now();
        if (now - this.lastCountCheck < this.countCheckDebounceMs) {
            return;
        }
        
        this.checkingCount = true;
        this.lastCountCheck = now;
        
        try {
            // First get server count to detect mismatches
            const serverCount = await this.getServerCount();
            const localCount = parseInt(localStorage.getItem('cart_count') || '0', 10);
            
            // Ensure both are numbers for proper comparison
            const serverCountNum = parseInt(serverCount, 10) || 0;
            const localCountNum = parseInt(localCount, 10) || 0;
            
            console.log(`Cart count check: local=${localCountNum}, server=${serverCountNum}, type: local=${typeof localCountNum}, server=${typeof serverCountNum}`);
            
            // If counts don't match, we need to reconcile
            if (serverCountNum !== localCountNum) {
                console.log(`Cart mismatch detected: local=${localCountNum}, server=${serverCountNum}. Reconciling...`);
                this.reconcileCartState();
                return;
            }
            
            // If counts match but no local data, still reconcile
            if (serverCountNum > 0 && localCountNum === 0) {
                console.log(`Cart count match but no local data: local=${localCountNum}, server=${serverCountNum}. Reconciling...`);
                this.reconcileCartState();
                return;
            }
            
            // If counts match, no need to reconcile - just log success
            if (serverCountNum === localCountNum) {
                console.log(`Cart count match: local=${localCountNum}, server=${serverCountNum}. No reconciliation needed.`);
                return;
            }
        } catch (error) {
            // If server check fails, fallback to local data
            console.warn('Failed to check server cart count, using local data:', error);
        } finally {
            this.checkingCount = false;
        }
    }

    async reconcileCartState() {
        // Prevent multiple concurrent reconciliations
        if (this.reconciling) return;
        this.reconciling = true;
        
        try {
            // First, try to get all cart items from server if we suspect a mismatch
            const serverCount = await this.getServerCount();
            const localCount = parseInt(localStorage.getItem('cart_count') || '0', 10);
            
            // Ensure both are numbers for proper comparison
            const serverCountNum = parseInt(serverCount, 10) || 0;
            const localCountNum = parseInt(localCount, 10) || 0;
            
            if (serverCountNum !== localCountNum) {
                if (serverCountNum > localCountNum) {
                    // Server has more items, fetch all from server
                    console.log(`Fetching all cart items from server (server: ${serverCountNum}, local: ${localCountNum})`);
                    const success = await this.fetchAllCartFromServer();
                    if (success) {
                        this.updateCartCount(serverCountNum);
                    } else {
                        // If server fetch fails, keep local data
                        console.log('Server fetch failed, keeping local data');
                        this.updateCartCount(localCountNum);
                    }
                } else {
                    // Local has more items, server is source of truth - sync from server
                    console.log(`Local has more items than server (local: ${localCountNum}, server: ${serverCountNum}). Syncing from server...`);
                    const success = await this.fetchAllCartFromServer();
                    if (success) {
                        this.updateCartCount(serverCountNum);
                    } else {
                        // If server fetch fails, keep local data
                        console.log('Server fetch failed, keeping local data');
                        this.updateCartCount(localCountNum);
                    }
                }
            } else {
                // Counts match, just refresh count normally
                this.refreshCartCount();
            }
        } catch (error) {
            console.warn('Failed to reconcile cart state:', error);
            // Fallback to normal refresh
            this.refreshCartCount();
        } finally {
            this.reconciling = false;
        }
    }

    async fetchAllCartFromServer() {
        try {
            // Fetch all cart items from server
            const response = await fetch(`/user/cart/items?t=${Date.now()}`, {
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Cache-Control': 'no-store'
                },
                cache: 'no-store'
            });
            
            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                console.warn('Server returned non-JSON response, likely session expired or server error');
                return false;
            }
            
            if (response.ok) {
                const data = await response.json();
                if (data.success && data.cart_items) {
                    console.log(`Loaded ${data.cart_items.length} cart items from server`);
                    
                    // Convert server items to localStorage format
                    const localItems = data.cart_items.map(item => ({
                        t: item.item_type,
                        i: item.item_id,
                        q: item.quantity,
                        c: item.color_id
                    }));
                    
                    // Update localStorage with server data
                    this.saveToStorage(localItems);
                    this.updateCartCount(this.computeLocalCount(localItems));
                    
                    // If we're on cart page, avoid reload; DOM has been updated optimistically
                    // Ensure totals/sections reflect current state
                    try { if (typeof window.updateCartTotals === 'function') window.updateCartTotals(); } catch(_) {}
                    try { if (typeof window.updateCartSectionsVisibility === 'function') window.updateCartSectionsVisibility(); } catch(_) {}
                    return true;
                }
            } else {
                console.warn('Server returned error status:', response.status);
            }
        } catch (error) {
            console.warn('Failed to fetch all cart from server:', error);
        }
        return false;
    }

    updateCartCount(count) {
        // Update all cart count badges - optimized with caching
        const selectors = ['.cart-count','#cart-count-badge','#cart-count-badge-mobile','[data-cart-count]'];
        // Guard: avoid redundant paints if value unchanged
        const prev = parseInt(localStorage.getItem('cart_count') || '0', 10);
        if (prev === Number(count)) {
            if (window.paintBadge) { window.paintBadge(selectors, count); }
        } else {
            if (window.paintBadge) { window.paintBadge(selectors, count); }
        }
        
        // Store count in localStorage for cross-page/tab synchronization
        const countStr = count.toString();
        try { 
            localStorage.setItem('cart_count', countStr);
            localStorage.setItem('cart_last_action', String(Date.now()));
        } catch (error) {
            console.warn('Failed to update cart count in localStorage:', error);
        }
        
        // Don't call window.updateCartCount to avoid infinite loop
        // The global function is meant to be called from outside, not from within CartManager
    }

    handleStorageChange(e) {
        // Listen for cart_items changes from other pages/tabs
        if (e.key === this.STORAGE_KEY) {
            const newItems = e.newValue ? JSON.parse(e.newValue) : [];
            const oldItems = e.oldValue ? JSON.parse(e.oldValue) : [];
            
            // Only update if the items actually changed
            if (JSON.stringify(newItems) !== JSON.stringify(oldItems)) {
                const oldCount = this.computeLocalCount(oldItems);
                const newCount = this.computeLocalCount(newItems);
                console.log(`Cart items changed from ${oldCount} to ${newCount} (from storage event)`);
                this.updateCartCount(newCount);
            }
        }
        
        // Also listen for cart_count changes for backward compatibility
        if (e.key === this.COUNT_KEY) {
            const newCount = parseInt(e.newValue || '0', 10);
            const oldCount = parseInt(e.oldValue || '0', 10);
            
            // Only update if the count actually changed
            if (newCount !== oldCount) {
                console.log(`Cart count changed from ${oldCount} to ${newCount} (from storage event)`);
                this.updateCartCount(newCount);
            }
        }
    }

    refreshCartCount() {
        fetch('/user/cart/count', {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Ensure we pass a number to updateCartCount
                const count = parseInt(data.cart_count || 0, 10);
                this.updateCartCount(count);
            }
        })
        .catch(error => {
            console.error('Failed to refresh cart count:', error);
        });
    }



    showMessage(message, type = 'info') {
        console.log(`CartManager showMessage: ${message} (${type})`);
        
        // Try to use existing showMessage function
        if (typeof window.showMessage === 'function') {
            try {
                window.showMessage(message, type);
                return;
            } catch (error) {
                console.warn('window.showMessage failed, falling back to createSimpleToast:', error);
            }
        }
        
        if (typeof window.showToast === 'function') {
            try {
                window.showToast(message, type);
                return;
            } catch (error) {
                console.warn('window.showToast failed, falling back to createSimpleToast:', error);
            }
        }
        
        // Fallback: create a simple toast if no system is available
        this.createSimpleToast(message, type);
    }

    createSimpleToast(message, type = 'info') {
        // Find existing toast with same message and type
        const existingToasts = document.querySelectorAll('.toast-notification');
        let existingToast = null;
        
        // Look for toast with same content and type
        for (const toast of existingToasts) {
            const toastMessage = toast.querySelector('.toast-message')?.textContent;
            const toastType = toast.className.includes('bg-green-500') ? 'success' :
                             toast.className.includes('bg-red-500') ? 'error' :
                             toast.className.includes('bg-yellow-500') ? 'warning' : 'info';
            
            if (toastMessage === message && toastType === type) {
                existingToast = toast;
                break;
            }
        }
        
        // If found same toast, remove it first to show new one
        if (existingToast) {
            existingToast.remove();
        }
        
        // Remove any other existing toasts to prevent stacking
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
        const colorName = (cartItem.find('.selected-color-name').text() || '').trim();
        const hasColorSelected = !!colorName && colorName !== 'Chưa chọn';

        if (hasColorSelected && colorAdj >= 0) {
            colorWrap.show();
            colorWrap.find('.js-price-color-name').text(colorName);
            colorWrap.find('.js-price-color-val').text(nf(colorAdj));
        } else {
            colorWrap.hide();
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

// Lightweight Cart Count Sync (simple, robust)
// - Source of truth for instant UX is localStorage('cart_count')
// - Server is reconciled in background when needed
// - Common pattern: load from LS on navigation/focus; write to LS on success responses
window.CartCount = {
    storageKey: 'cart_items',
    countKey: 'cart_count',
    initialized: false,
    init() {
        if (this.initialized) return; // Prevent duplicate initialization
        this.initialized = true;
        
        // Initial paint from localStorage
        this.load();
        this.guardWindowMs = 1200; // ignore stale server loads for a short window
        // Note: Event listeners are handled by main cart manager to avoid duplicates
    },
    load() {
        // Load count from cart_items (consistent with wishlist)
        const items = JSON.parse(localStorage.getItem(this.storageKey) || '[]');
        const count = items.length;
        const lastAction = parseInt(localStorage.getItem('cart_last_action') || '0', 10);
        const now = Date.now();
        
        // If a local action just happened, prefer local immediately and skip server-triggered loads
        if (window.cartManager && Number.isFinite(count)) {
            if (!lastAction || now - lastAction > this.guardWindowMs) {
                window.cartManager.updateCartCount(count);
            }
        }
    },
    apply(count) {
        const n = parseInt(count || 0, 10);
        try { localStorage.setItem(this.countKey, String(n)); } catch (_) {}
        // Apply immediately without being blocked by guard
        if (window.cartManager && Number.isFinite(n)) {
            window.cartManager.updateCartCount(n);
        } else {
            this.load();
        }
    },
    async reconcile() {
        try {
            const r = await fetch(`/user/cart/count?t=${Date.now()}`, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Cache-Control': 'no-store' }, cache: 'no-store' });
            const data = r.ok ? await r.json() : null;
            if (data && data.success && typeof data.cart_count !== 'undefined') {
                const lastAction = parseInt(localStorage.getItem('cart_last_action') || '0', 10);
                const now = Date.now();
                // If a local action just happened, avoid overwriting with stale server count
                if (!lastAction || now - lastAction > this.guardWindowMs) {
                    this.apply(data.cart_count);
                }
            }
        } catch (_) {}
    }
};

// Initialize CartManager when DOM is ready
function initializeCartManager() {
    // Destroy existing instance if exists
    if (window.cartManager) {
        // Remove all event handlers
        $(document).off('.cart');
        
        // Remove storage event listener
        window.removeEventListener('storage', window.cartManager.boundHandleStorageChange);
    }

    window.cartManager = new CartManager();
}

// Initialize on DOM ready
$(document).ready(initializeCartManager);

// Also initialize on page show (for browser navigation)
$(window).on('pageshow', function(event) {
    if (window.cartManager) {
        // Check if page was restored from cache (back/forward navigation)
        if (event.originalEvent && event.originalEvent.persisted) {
            // Page was restored from cache, refresh state immediately
            window.cartManager.loadCartCountFromStorage();
            console.log('Cart state refreshed from cache');
        } else {
            // Normal page load, load from storage first
            window.cartManager.loadCartCountFromStorage();
        }
        
        // Only reconcile if no operations in progress and not recently reconciled
        if (!window.cartManager.processing.size && !window.cartManager.reconciling) {
            const lastReconcile = window.cartManager.lastReconcile || 0;
            const now = Date.now();
            if (now - lastReconcile > 1500) { // reconcile sớm hơn để tránh cảm giác lag
                window.cartManager.lastReconcile = now;
                // Delay reconciliation slightly to let UI settle, but check debounce
                setTimeout(() => { 
                    if (Date.now() - window.cartManager.lastCountCheck > window.cartManager.countCheckDebounceMs) {
                        window.cartManager.checkServerCountAndReconcile(); 
                    }
                }, 60);
            }
        }
    }
});

// Handle browser back/forward navigation for cart
$(window).on('popstate', function() {
    if (window.cartManager) {
        // Immediate state refresh to prevent flickering
        window.cartManager.loadCartCountFromStorage();
        console.log('Cart state refreshed after navigation');
    }
});

// Handle visibility change (tab focus/blur)
$(document).on('visibilitychange', function() {
    if (window.cartManager && !document.hidden) {
        // Tab became visible, refresh state
        window.cartManager.loadCartCountFromStorage();
    }
});

// Handle window focus (alternative to visibilitychange)
$(window).on('focus', function() {
    if (window.cartManager) {
        // Window gained focus, refresh state
        window.cartManager.loadCartCountFromStorage();
    }
});

// Handle beforeunload to save state before navigation
$(window).on('beforeunload', function() {
    if (window.cartManager) {
        // Ensure state is saved before leaving page
        const currentCount = parseInt(localStorage.getItem('cart_count') || '0', 10);
        window.cartManager.updateCartCount(currentCount);
    }
});

// Global functions for backward compatibility
window.updateCartCount = function(count) {
    // This function is meant to be called from outside to update cart count
    // It should NOT call CartManager methods to avoid infinite loops
    if (window.cartManager) {
        // Only update UI elements directly, don't call CartManager methods
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
        
        // Update localStorage for cross-page/tab synchronization
        localStorage.setItem('cart_count', count.toString());
    }
};

window.refreshCartCount = function() {
    if (window.cartManager) {
        window.cartManager.refreshCartCount();
    }
};

// Export cart manager globally
window.cartManager = window.cartManager || new CartManager();


