/**
 * Cart Manager - Modern Automotive Showroom Cart Management
 * Handles all cart interactions with enhanced UX and features
 */
class CartManager {
    constructor() {
        this.isUpdating = false;
        this.debounceTimers = {};
        this.init();
    }

    init() {
        this.initializeColorDisplays();
        this.bindEvents();
        this.initializeAnimations();
        // Ensure counters/totals are correct on first load (cart page)
        this.updateCartTotals();
    }

    /**
     * Initialize color displays
     */
    initializeColorDisplays() {
        document.querySelectorAll('[data-bg-hex]').forEach(el => {
            const hex = el.getAttribute('data-bg-hex');
            if (hex) {
                el.style.backgroundColor = hex;
            }
        });
    }

    /**
     * Bind all event listeners
     */
    bindEvents() {
        this.bindQuantityControls();
        this.bindColorOptions();
        this.bindRemoveButtons();
        this.bindClearCart();
        this.bindQuantityInputs();
        this.initializeQuantityButtonsState();
    }

    /**
     * Bind quantity control buttons
     */
    bindQuantityControls() {
        // Delegate decrease
        document.addEventListener('click', (e) => {
            const btn = e.target.closest('.qty-decrease');
            if (!btn) return;
            e.preventDefault();
            const id = btn.dataset.id;
            const input = document.querySelector(`.cart-qty-input[data-id='${id}']`);
            if (input) {
                const currentValue = parseInt(input.value || '1');
                if (isNaN(currentValue) || currentValue <= 1) {
                    input.value = 1;
                    this.toggleDecreaseDisabled(id, true);
                    this.showMessage('Số lượng tối thiểu là 1', 'info');
                    return;
                }
                const next = currentValue - 1;
                input.value = next;
                this.toggleDecreaseDisabled(id, next <= 1);
                this.updateQuantity(id, next);
            }
        });

        // Delegate increase
        document.addEventListener('click', (e) => {
            const btn = e.target.closest('.qty-increase');
            if (!btn) return;
            e.preventDefault();
            const id = btn.dataset.id;
            const input = document.querySelector(`.cart-qty-input[data-id='${id}']`);
            if (input) {
                const currentValue = parseInt(input.value || '1');
                const next = (isNaN(currentValue) ? 1 : currentValue) + 1;
                input.value = next;
                this.toggleDecreaseDisabled(id, next <= 1);
                this.updateQuantity(id, next);
            }
        });
    }

    /**
     * Bind quantity input fields
     */
    bindQuantityInputs() {
        document.querySelectorAll('.cart-qty-input').forEach(input => {
            // Live sanitize and debounce update
            input.addEventListener('input', this.debounce((e) => {
                const id = e.target.dataset.id;
                let value = e.target.value.replace(/[^0-9]/g, '');
                if (value === '') value = '1';
                let quantity = parseInt(value, 10);
                if (isNaN(quantity) || quantity < 1) {
                    quantity = 1;
                }
                e.target.value = String(quantity);
                this.toggleDecreaseDisabled(id, quantity <= 1);
                this.updateQuantity(id, quantity);
            }, 600));

            // On blur, clamp to min 1
            input.addEventListener('blur', (e) => {
                const id = e.target.dataset.id;
                let quantity = parseInt(e.target.value, 10);
                if (isNaN(quantity) || quantity < 1) {
                    quantity = 1;
                    e.target.value = '1';
                    this.showMessage('Số lượng tối thiểu là 1', 'info');
                }
                this.toggleDecreaseDisabled(id, quantity <= 1);
            });
        });
    }

    /**
     * Initialize disable state for decrease buttons on load
     */
    initializeQuantityButtonsState() {
        document.querySelectorAll('.cart-qty-input').forEach(input => {
            const id = input.dataset.id;
            const quantity = parseInt(input.value || '1', 10);
            this.toggleDecreaseDisabled(id, isNaN(quantity) || quantity <= 1);
        });
    }

    /**
     * Toggle disabled state of the decrease button for an item
     */
    toggleDecreaseDisabled(itemId, disabled) {
        const btn = document.querySelector(`.qty-decrease[data-id='${itemId}']`);
        if (!btn) return;
        if (disabled) {
            btn.setAttribute('disabled', 'disabled');
            btn.classList.add('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
        } else {
            btn.removeAttribute('disabled');
            btn.classList.remove('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
        }
    }

    /**
     * Bind color option buttons
     */
    bindColorOptions() {
        document.addEventListener('click', (e) => {
            const button = e.target.closest('.color-option');
            if (!button) return;
            e.preventDefault();
            if (this.isUpdating) return;
            const colorId = button.dataset.colorId;
            const colorName = button.dataset.colorName;
            const itemId = button.dataset.itemId;
            const url = button.dataset.updateUrl;
            const csrf = button.dataset.csrf;
            this.updateColor(itemId, colorId, colorName, url, csrf, button);
        });
    }

    /**
     * Bind remove item buttons
     */
    bindRemoveButtons() {
        document.querySelectorAll('.remove-item-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                if (!confirm('Bạn có chắc muốn xóa sản phẩm này?')) return;
                
                const itemId = btn.dataset.id;
                const url = btn.dataset.url;
                const csrf = btn.dataset.csrf;

                this.removeItem(itemId, url, csrf);
            });
        });
    }

    /**
     * Bind clear cart button
     */
    bindClearCart() {
        const clearBtn = document.getElementById('clear-cart-btn');
        if (clearBtn) {
            clearBtn.addEventListener('click', (e) => {
                e.preventDefault();
                if (!confirm('Bạn có chắc muốn xóa toàn bộ giỏ hàng?')) return;
                
                const url = clearBtn.dataset.url;
                const csrf = clearBtn.dataset.csrf;

                this.clearCart(url, csrf);
            });
        }
    }

    /**
     * Update item quantity
     */
    async updateQuantity(itemId, quantity) {
        if (this.isUpdating) return;
        
        const input = document.querySelector(`.cart-qty-input[data-id='${itemId}']`);
        const url = input.dataset.updateUrl;
        const csrf = input.dataset.csrf;

        this.isUpdating = true;
        input.classList.add('opacity-50');

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ quantity })
            });

            const data = await response.json();

            if (data.success) {
                // Update input value in case backend changed it
                if (data.quantity !== undefined) {
                    input.value = data.quantity;
                }
                
                // If backend returns recalculated unit price (e.g. when color changes affects price)
                if (typeof data.unit_price !== 'undefined') {
                    const itemCard = document.querySelector(`.cart-item-card[data-id='${itemId}']`);
                    const priceEl = itemCard ? itemCard.querySelector('.item-price') : null;
                    if (priceEl) {
                        priceEl.dataset.price = String(parseInt(data.unit_price));
                        priceEl.textContent = this.formatNumber(parseInt(data.unit_price));
                    }
                }
                // Update UI totals
                this.updateItemTotal(itemId);
                this.updateCartTotals();
                this.updateCartCount();
                
                // Show success message
                this.showMessage('Cập nhật số lượng thành công', 'success');
            } else {
                this.showMessage(data.message || 'Có lỗi xảy ra khi cập nhật số lượng', 'error');
            }
        } catch (error) {
            this.showMessage('Có lỗi xảy ra khi cập nhật số lượng', 'error');
        } finally {
            this.isUpdating = false;
            input.classList.remove('opacity-50');
        }
    }

    /**
     * Update item color
     */
    async updateColor(itemId, colorId, colorName, url, csrf, button) {
        this.isUpdating = true;
        button.classList.add('opacity-50');

        // Update visual selection immediately
        const colorContainer = button.closest('.flex.items-center.gap-2');
        const allColorOptions = colorContainer.querySelectorAll('.color-option');
        allColorOptions.forEach(opt => {
            opt.classList.remove('border-blue-500', 'ring-2', 'ring-blue-200');
            opt.classList.add('border-gray-300');
        });
        button.classList.remove('border-gray-300');
        button.classList.add('border-blue-500', 'ring-2', 'ring-blue-200');

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ color_id: colorId })
            });

            const data = await response.json();

            if (data.success) {
                // Update color display (supports card or table layout)
                const container = button.closest('.cart-item-card, tr.cart-item-row');
                if (container) {
                    const selectedNameEl = container.querySelector('.selected-color-name');
                    if (selectedNameEl) selectedNameEl.textContent = data.color_name || colorName;
                    // Update unit price if backend returns it
                    if (typeof data.unit_price !== 'undefined') {
                        const priceEl = container.querySelector('.item-price');
                        if (priceEl) {
                            priceEl.dataset.price = String(parseInt(data.unit_price));
                            priceEl.textContent = this.formatNumber(parseInt(data.unit_price));
                        }
                    }
                }

                // Update price if backend recalculated (already handled above for container)
                // Update totals
                this.updateItemTotal(itemId);
                this.updateCartTotals();
                
                this.showMessage('Cập nhật màu sắc thành công', 'success');
            } else {
                // Revert visual selection on error
                button.classList.remove('border-blue-500', 'ring-2', 'ring-blue-200');
                button.classList.add('border-gray-300');
                
                this.showMessage(data.message || 'Có lỗi xảy ra khi cập nhật màu sắc', 'error');
            }
        } catch (error) {
            // Revert visual selection on error
            button.classList.remove('border-blue-500', 'ring-2', 'ring-blue-200');
            button.classList.add('border-gray-300');
            
            this.showMessage('Có lỗi xảy ra khi cập nhật màu sắc', 'error');
        } finally {
            this.isUpdating = false;
            button.classList.remove('opacity-50');
        }
    }

    /**
     * Remove item from cart
     */
    async removeItem(itemId, url, csrf) {
        // Support table rows or card layout
        const row = document.querySelector(`tr.cart-item-row[data-id='${itemId}']`) || document.querySelector(`.cart-item-card[data-id='${itemId}']`);
        if (!row) return;

        row.classList.add('opacity-50');

        try {
            const response = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                }
            });

            const data = await response.json();

            if (data.success) {
                // Animate removal
                row.style.transform = 'scale(0.98)';
                row.style.opacity = '0';
                
                setTimeout(() => {
                    row.remove();
                    this.updateCartTotals();
                    this.updateCartCount();
                    
                    // Check if cart is empty
                    const remainingItems = document.querySelectorAll('.cart-item-card, tr.cart-item-row');
                    if (remainingItems.length === 0) {
                        location.reload();
                    }
                }, 300);

                this.showMessage('Đã xóa sản phẩm khỏi giỏ hàng', 'success');
            } else {
                row.classList.remove('opacity-50');
                this.showMessage(data.message || 'Có lỗi xảy ra khi xóa sản phẩm', 'error');
            }
        } catch (error) {
            row.classList.remove('opacity-50');
            this.showMessage('Có lỗi xảy ra khi xóa sản phẩm', 'error');
        }
    }

    /**
     * Clear entire cart
     */
    async clearCart(url, csrf) {
        const items = document.querySelectorAll('.cart-item-card');
        
        // Add loading state to all items
        items.forEach(row => row.classList.add('opacity-50'));

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                }
            });

            const data = await response.json();

            if (data.success) {
                // Update cart count
                this.updateCartCount(data.cart_count);
                
                // Animate all items out
                items.forEach((item, index) => {
                    setTimeout(() => {
                        item.style.transform = 'scale(0.8)';
                        item.style.opacity = '0';
                    }, index * 100);
                });
                
                // Reload page after animation
                setTimeout(() => {
                    location.reload();
                }, items.length * 100 + 500);

                this.showMessage('Đã xóa toàn bộ giỏ hàng', 'success');
            } else {
                // Remove loading state on error
                items.forEach(row => row.classList.remove('opacity-50'));
                this.showMessage(data.message || 'Có lỗi xảy ra khi xóa giỏ hàng', 'error');
            }
        } catch (error) {
            // Remove loading state on error
            items.forEach(row => row.classList.remove('opacity-50'));
            this.showMessage('Có lỗi xảy ra khi xóa giỏ hàng', 'error');
        }
    }

    /**
     * Update item total
     */
    updateItemTotal(itemId) {
        const container = document.querySelector(`tr.cart-item-row[data-id='${itemId}']`) || document.querySelector(`.cart-item-card[data-id='${itemId}']`);
        if (!container) return;

        const price = parseInt((container.querySelector('.item-price')?.dataset.price) || '0');
        const quantity = parseInt((container.querySelector('.cart-qty-input')?.value) || '0');
        const itemTotal = price * quantity;

        const totalElement = container.querySelector(`.item-total[data-id='${itemId}']`);
        if (totalElement) totalElement.textContent = this.formatNumber(itemTotal);
    }

    /**
     * Update cart totals
     */
    updateCartTotals() {
        let subtotal = 0;
        let cartCount = 0;
        let carRowCount = 0;
        let accessoryRowCount = 0;

        // Support both card layout and table layout
        const itemSelectors = document.querySelectorAll('.cart-item-card, tr.cart-item-row');
        itemSelectors.forEach(row => {
            const qtyInput = row.querySelector('.cart-qty-input');
            const priceEl = row.querySelector('.item-price');
            if (!qtyInput || !priceEl) return;
            const quantity = parseInt(qtyInput.value || '0');
            const price = parseInt(priceEl.dataset.price || '0');
            subtotal += quantity * price;
            cartCount += quantity;

            // Row counters
            const table = row.closest('table');
            if (table && table.id === 'car-table') carRowCount++;
            else if (table && table.id === 'accessory-table') accessoryRowCount++;
        });

        const tax = subtotal * 0.1;
        const total = subtotal + tax;

        // Update totals
        const subtotalEl = document.getElementById('subtotal');
        const taxEl = document.getElementById('tax');
        const totalEl = document.getElementById('cart-total');
        const mobileTotalEl = document.getElementById('cart-total-mobile');

        if (subtotalEl) subtotalEl.textContent = this.formatNumber(subtotal);
        if (taxEl) taxEl.textContent = this.formatNumber(tax);
        if (totalEl) totalEl.textContent = this.formatNumber(total);
        if (mobileTotalEl) mobileTotalEl.textContent = this.formatNumber(total);

        // Update section counters based on current rows
        const carCountEl = document.getElementById('car-count');
        const accCountEl = document.getElementById('accessory-count');
        if (carCountEl) carCountEl.textContent = String(carRowCount);
        if (accCountEl) accCountEl.textContent = String(accessoryRowCount);

        // Hide (0) wrappers when zero
        const carWrap = document.getElementById('car-count-wrap');
        const accWrap = document.getElementById('accessory-count-wrap');
        if (carWrap) carWrap.style.display = carRowCount === 0 ? 'none' : 'inline';
        if (accWrap) accWrap.style.display = accessoryRowCount === 0 ? 'none' : 'inline';

        // Hide whole sections when table has no rows
        const carSection = document.getElementById('car-section');
        const accSection = document.getElementById('accessory-section');
        if (carSection) carSection.style.display = carRowCount === 0 ? 'none' : 'block';
        if (accSection) accSection.style.display = accessoryRowCount === 0 ? 'none' : 'block';

        // Sync header cart count badge
        this.updateCartCount();
    }

    /**
     * Update cart count
     */
    updateCartCount(count = null) {
        if (count === null) {
            count = 0;
            // Sum all quantities from both table rows and card layout
            document.querySelectorAll('.cart-qty-input').forEach(input => {
                const qty = parseInt(input.value || '0');
                if (!isNaN(qty)) count += qty;
            });
        }

        // Update cart count badges
        const badges = document.querySelectorAll('#cart-count-badge, .cart-count-badge');
        badges.forEach(badge => {
            badge.textContent = count > 99 ? '99+' : count;
            // Hide when zero to avoid showing 0 badge
            if (count === 0) badge.style.display = 'none';
            else badge.style.display = 'flex';
        });

        // Call global function if exists
        if (typeof window.updateCartCount === 'function') {
            window.updateCartCount(count);
        }
    }

    /**
     * Get color hex from color name
     */
    getColorHex(colorName) {
        const colorMap = {
            'trắng': '#FFFFFF', 'trang': '#FFFFFF', 'white': '#FFFFFF',
            'đen': '#000000', 'den': '#000000', 'black': '#000000',
            'xám': '#808080', 'xam': '#808080', 'gray': '#808080',
            'bạc': '#C0C0C0', 'bac': '#C0C0C0', 'silver': '#C0C0C0',
            'đỏ': '#FF0000', 'do': '#FF0000', 'red': '#FF0000',
            'xanh dương': '#0000FF', 'xanh duong': '#0000FF', 'blue': '#0000FF',
            'xanh lá': '#00FF00', 'xanh la': '#00FF00', 'green': '#00FF00',
            'vàng': '#FFFF00', 'vang': '#FFFF00', 'yellow': '#FFFF00',
            'cam': '#FFA500', 'orange': '#FFA500',
            'tím': '#800080', 'tim': '#800080', 'purple': '#800080',
            'hồng': '#FFC0CB', 'hong': '#FFC0CB', 'pink': '#FFC0CB',
            'nâu': '#A52A2A', 'nau': '#A52A2A', 'brown': '#A52A2A',
            
            // Capitalized versions
            'Trắng': '#FFFFFF', 'Trang': '#FFFFFF',
            'Đen': '#000000', 'Den': '#000000',
            'Xám': '#808080', 'Xam': '#808080',
            'Bạc': '#C0C0C0', 'Bac': '#C0C0C0',
            'Đỏ': '#FF0000', 'Do': '#FF0000',
            'Xanh dương': '#0000FF', 'Xanh duong': '#0000FF',
            'Xanh lá': '#00FF00', 'Xanh la': '#00FF00',
            'Vàng': '#FFFF00', 'Vang': '#FFFF00',
            'Cam': '#FFA500', 'Tím': '#800080', 'Tim': '#800080',
            'Hồng': '#FFC0CB', 'Hong': '#FFC0CB',
            'Nâu': '#A52A2A', 'Nau': '#A52A2A'
        };
        
        const normalizedName = colorName.trim();
        return colorMap[normalizedName] || '#CCCCCC';
    }

    /**
     * Format number with Vietnamese locale
     */
    formatNumber(num) {
        return num.toLocaleString('vi-VN');
    }

    /**
     * Debounce function
     */
    debounce(func, wait) {
        return (...args) => {
            clearTimeout(this.debounceTimers[func]);
            this.debounceTimers[func] = setTimeout(() => func.apply(this, args), wait);
        };
    }

    /**
     * Show message notification
     */
    showMessage(message, type = 'info') {
        // Check if global showMessage function exists
        if (typeof window.showMessage === 'function') {
            window.showMessage(message, type);
            return;
        }

        // Create simple notification
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
            type === 'success' ? 'bg-green-500 text-white' :
            type === 'error' ? 'bg-red-500 text-white' :
            'bg-blue-500 text-white'
        }`;
        notification.textContent = message;

        document.body.appendChild(notification);

        // Animate in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);

        // Remove after 3 seconds
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }

    /**
     * Initialize animations
     */
    initializeAnimations() {
        // Add entrance animations to cart items
        const items = document.querySelectorAll('.cart-item-card');
        items.forEach((item, index) => {
            item.style.opacity = '0';
            item.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                item.style.transition = 'all 0.5s ease';
                item.style.opacity = '1';
                item.style.transform = 'translateY(0)';
            }, index * 100);
        });
    }
}

// Initialize CartManager when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.CartManager = new CartManager();
});

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = CartManager;
}
