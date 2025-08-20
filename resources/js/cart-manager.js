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
    }

    bindEvents() {
        // Cart add
        $(document).on('click', '.js-add-to-cart', this.handleCartAdd.bind(this));
        
        // Cart remove
        $(document).on('click', '.js-remove-from-cart', this.handleCartRemove.bind(this));
        
        // Cart update quantity
        $(document).on('change', '.js-cart-quantity', this.handleCartQuantityChange.bind(this));
        
        // Cart clear
        $(document).on('click', '.js-clear-cart', this.handleCartClear.bind(this));
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
        
        fetch('/cart/add', {
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
                this.showMessage(data.message, 'success');
                this.updateCartCount(data.cart_count);
            } else {
                this.showMessage(data.message || 'Có lỗi xảy ra!', 'error');
            }
        })
        .catch(() => {
            this.showMessage('Có lỗi xảy ra!', 'error');
        })
        .finally(() => {
            button.prop('disabled', false);
        });
    }

    handleCartRemove(e) {
        e.preventDefault();
        const button = $(e.currentTarget);
        const cartItemId = button.data('cart-item-id');
        
        if (!cartItemId) {
            this.showMessage('Không tìm thấy sản phẩm!', 'error');
            return;
        }

        // Optimistic update - disable button
        button.prop('disabled', true);
        
        fetch(`/cart/remove/${cartItemId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.showMessage(data.message, 'success');
                this.updateCartCount(data.cart_count);
                
                // Remove item from DOM if on cart page
                button.closest('.cart-item').fadeOut();
            } else {
                this.showMessage(data.message || 'Có lỗi xảy ra!', 'error');
            }
        })
        .catch(() => {
            this.showMessage('Có lỗi xảy ra!', 'error');
        })
        .finally(() => {
            button.prop('disabled', false);
        });
    }

    handleCartQuantityChange(e) {
        const input = $(e.currentTarget);
        const cartItemId = input.data('cart-item-id');
        const quantity = parseInt(input.val());
        
        if (!cartItemId || quantity < 1) {
            return;
        }

        fetch(`/cart/update/${cartItemId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ quantity: quantity })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.updateCartCount(data.cart_count);
                
                // Update item total if exists
                const itemTotalElement = input.closest('.cart-item').find('.item-total');
                if (itemTotalElement.length && data.item_total !== undefined) {
                    itemTotalElement.text(this.formatPrice(data.item_total));
                }
            } else {
                this.showMessage(data.message || 'Có lỗi xảy ra!', 'error');
            }
        })
        .catch(() => {
            this.showMessage('Có lỗi xảy ra!', 'error');
        });
    }

    handleCartClear(e) {
        e.preventDefault();
        const button = $(e.currentTarget);
        
        if (!confirm('Bạn có chắc chắn muốn xóa toàn bộ giỏ hàng?')) {
            return;
        }

        // Optimistic update - disable button
        button.prop('disabled', true);
        
        fetch('/cart/clear', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.showMessage(data.message, 'success');
                this.updateCartCount(data.cart_count);
                
                // Clear cart items from DOM if on cart page
                $('.cart-item').fadeOut();
            } else {
                this.showMessage(data.message || 'Có lỗi xảy ra!', 'error');
            }
        })
        .catch(() => {
            this.showMessage('Có lỗi xảy ra!', 'error');
        })
        .finally(() => {
            button.prop('disabled', false);
        });
    }

    updateCartCount(count) {
        console.log('Updating cart count to:', count);
        
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
                    console.log('Cart badge shown for count:', count);
                } else {
                    badge.addClass('hidden');
                    console.log('Cart badge hidden for count:', count);
                }
            });
        });
        
        // IMPORTANT: Store count in localStorage for cross-page/tab synchronization
        localStorage.setItem('cart_count', count.toString());
        console.log('Cart count stored in localStorage:', count);
    }

    refreshCartCount() {
        fetch('/cart/count', {
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
            // Fallback to simple alert
            alert(message);
        }
    }

    formatPrice(price) {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(price);
    }
}

// Initialize CartManager when DOM is ready
function initializeCartManager() {
    // Destroy existing instance if exists
    if (window.cartManager) {
        // Remove all event handlers
        $(document).off('click', '.js-add-to-cart');
        $(document).off('click', '.js-remove-from-cart');
        $(document).off('change', '.js-cart-quantity');
        $(document).off('click', '.js-clear-cart');
    }
    
    window.cartManager = new CartManager();
}

// Initialize on DOM ready
$(document).ready(initializeCartManager);

// Also initialize on page show (for browser navigation)
$(window).on('pageshow', function() {
    console.log('CartManager: Page show event detected');
    if (window.cartManager) {
        // Check if localStorage count is different from current display
        const storedCartCount = parseInt(localStorage.getItem('cart_count')) || 0;
        const currentCartCount = parseInt($('.cart-count, #cart-count-badge, #cart-count-badge-mobile').first().text()) || 0;
        
        if (storedCartCount !== currentCartCount) {
            console.log('CartManager: Count mismatch detected, updating from', currentCartCount, 'to', storedCartCount);
            window.cartManager.updateCartCount(storedCartCount);
        } else {
            console.log('CartManager: Count is up to date, refreshing from server');
            window.cartManager.refreshCartCount();
        }
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
