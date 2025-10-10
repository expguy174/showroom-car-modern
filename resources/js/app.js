import './bootstrap';
import './wishlist-manager';
import './cart-manager';
import './compare';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Global loading state management
window.loadingStates = {
    cart: false,
    compare: false,
};

// Re-sync wishlist buttons after navigation/return
document.addEventListener('DOMContentLoaded', () => {
    
    // Force badge from localStorage immediately after navigation (e.g., click logo)
    try {
        const lc = parseInt(localStorage.getItem('wishlist_count') || '0', 10);
        if (window.WishlistCount && !isNaN(lc)) {
            window.WishlistCount.apply(lc);
        }
    } catch(_) {}
    if (typeof window.refreshWishlistStatus === 'function') {
        try { setTimeout(() => window.refreshWishlistStatus(), 200); } catch(e) {}
        try { setTimeout(() => window.refreshWishlistStatus(), 800); } catch(e) {}
        try { setTimeout(() => window.refreshWishlistStatus(), 1600); } catch(e) {}
    }

    // Extra safety: also re-apply on pageshow (BFCache or instantaneous nav)
    window.addEventListener('pageshow', () => {
        try {
            const lc2 = parseInt(localStorage.getItem('wishlist_count') || '0', 10);
            if (window.WishlistCount && !isNaN(lc2)) {
                window.WishlistCount.apply(lc2);
            } else {
                // paint directly if helpers not ready yet
                window.paintBadge && window.paintBadge(['#wishlist-count-badge','#wishlist-count-badge-mobile','.wishlist-count','.wishlist-count-badge','[data-wishlist-count]'], lc2);
            }
        } catch(_) {}
    });
    
    // Initialize managers if not already done
    try { 
        if (window.CartCount && !window.CartCount.initialized) { 
            window.CartCount.init(); 
        } 
    } catch(e) {}
    
    try { 
        if (window.WishlistCount && !window.WishlistCount.initialized) { 
            window.WishlistCount.init(); 
        } 
    } catch(e) {}

    // Fire toast from payload span if present (server-side redirect payload)
    try {
        const payload = document.getElementById('toast-payload');
        if (payload && typeof window.showMessage === 'function') {
            const kind = payload.getAttribute('data-kind') || 'info';
            const msg = payload.getAttribute('data-msg') || '';
            if (msg) window.showMessage(msg, kind);
        }
    } catch {}
});

// Show loading overlay
window.showLoading = function(message = 'Đang tải...') {
    const overlay = document.createElement('div');
    overlay.id = 'loading-overlay';
    overlay.className = 'fixed inset-0 z-[9999] bg-black/50 flex items-center justify-center';
    overlay.innerHTML = `
        <div class="bg-white rounded-lg p-6 flex flex-col items-center space-y-4">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
            <p class="text-gray-700 font-medium">${message}</p>
        </div>
    `;
    document.body.appendChild(overlay);
};

// Hide loading overlay
window.hideLoading = function() {
    const overlay = document.getElementById('loading-overlay');
    if (overlay) {
        overlay.remove();
    }
};

// Show button loading state
window.showButtonLoading = function(button, text = 'Đang xử lý...') {
    if (!button) return;
    
    button.disabled = true;
    button.dataset.originalText = button.innerHTML;
    button.innerHTML = `
        <i class="fas fa-spinner fa-spin mr-2"></i>
        ${text}
    `;
};

// Hide button loading state
window.hideButtonLoading = function(button) {
    if (!button) return;
    
    button.disabled = false;
    if (button.dataset.originalText) {
        button.innerHTML = button.dataset.originalText;
        delete button.dataset.originalText;
    }
};

// Global JavaScript functions for car showroom functionality - Enhanced with ModernCartFast
window.addToCart = function(itemType, itemId, event) {
    if (event) event.preventDefault();
    
    const button = event ? event.target.closest('button') : document.querySelector(`button[onclick*="${itemId}"]`);
    
    // Use ModernCartFast if available, otherwise fallback to server-side
    if (window.modernCartFast && typeof window.modernCartFast.addItem === 'function') {
        try {
            // Show loading state
            showButtonLoading(button, 'Đang thêm...');
            
            // Add to ModernCartFast
            window.modernCartFast.addItem(itemId, 1).then(() => {
                showMessage('Đã thêm vào giỏ hàng', 'success');
                // Update cart count from ModernCartFast
                const stats = window.modernCartFast.getCartStats();
                updateCartCount(stats.totalItems);
            }).catch(error => {
                console.error('ModernCartFast error:', error);
                // Fallback to server-side
                addToCartServer(itemType, itemId, button);
            });
        } catch (error) {
            console.error('ModernCartFast error:', error);
            // Fallback to server-side
            addToCartServer(itemType, itemId, button);
        }
    } else {
        // Fallback to server-side
        addToCartServer(itemType, itemId, button);
    }
};

// Server-side cart fallback
function addToCartServer(itemType, itemId, button) {
    if (window.loadingStates.cart) return; // Prevent double clicks
    window.loadingStates.cart = true;
    
    showButtonLoading(button, 'Đang thêm...');
    
    fetch('/user/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: `item_type=${itemType}&item_id=${itemId}&quantity=1&options_signature=`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage(data.message || 'Đã thêm vào giỏ hàng', 'success');
            if (typeof updateCartCount === 'function' && data.cart_count !== undefined) {
                updateCartCount(data.cart_count);
            }
        } else {
            showMessage(data.message || 'Có lỗi xảy ra khi thêm vào giỏ hàng', 'error');
        }
    })
    .catch(() => {
        showMessage('Có lỗi xảy ra khi thêm vào giỏ hàng', 'error');
    })
    .finally(() => {
        hideButtonLoading(button);
        window.loadingStates.cart = false;
    });
}

// Compare handled entirely by compare.js

// DOM ready miscellaneous initializations
document.addEventListener('DOMContentLoaded', function() {
    initializeLazyLoading();
    
    // Close modal when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-backdrop')) {
            const modal = e.target.closest('.modal-backdrop');
            if (modal) closeModal(modal.id);
        }
    });
    
    // Count synchronization is now handled by individual managers
    // No need for direct count updates here to prevent conflicts
});

// Delegated handler for Share only
document.addEventListener('click', function(e){
    const shareBtn = e.target.closest('.js-share-variant');
    if (!shareBtn) return;
    e.preventDefault();
    const url = shareBtn.getAttribute('data-share-url') || window.location.href;
    const title = shareBtn.getAttribute('data-variant-name') || document.title;
    if (navigator.share){
        navigator.share({ title, url }).catch(()=>{});
    } else {
        if (navigator.clipboard && navigator.clipboard.writeText){
            navigator.clipboard.writeText(url).then(()=>{
                if (typeof window.showMessage === 'function') window.showMessage('Đã sao chép link', 'success');
            }).catch(()=>{ try { window.prompt('Sao chép link:', url); } catch(_) {} });
        } else {
            try { window.prompt('Sao chép link:', url); } catch(_) {}
        }
    }
});

// Modal functions
window.openModal = function(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.classList.remove('hidden');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
};

window.closeModal = function(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.classList.add('hidden');
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
};

window.showMessage = function(message, type = 'info') {
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

    // Animate in on next frame for zero perceived delay
    requestAnimationFrame(() => {
        toast.classList.add('show');
    });

    // Auto remove sooner for snappier UX
    const visibleMs = 3000;
    setTimeout(() => {
        toast.classList.add('hide');
        setTimeout(() => {
            if (toast.parentElement) toast.remove();
        }, 300);
    }, visibleMs);
};

// Global cart count update function - Enhanced with ModernCartFast integration
// Note: This function is overridden by cart-manager.js, so we don't define it here
// to avoid conflicts and infinite loops

// Global wishlist/cart helpers (thin wrappers to new lightweight sync)
window.updateWishlistCount = function(count) {
    if (window.WishlistCount) { window.WishlistCount.apply(count); return; }
    if (window.wishlistManager) { window.wishlistManager.updateCount(parseInt(count) || 0); }
};

window.updateCartCount = function(count) {
    if (window.CartCount) { window.CartCount.apply(count); return; }
    if (window.cartManager) { window.cartManager.updateCartCount(parseInt(count) || 0); }
};

// Auto-initialize ModernCart when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    // Initialize ModernCartFast if present
    try { if (window.modernCartFast) { window.initializeModernCart(); } } catch(_) {}

    // Initialize lightweight counters immediately to reduce visible lag
    try { window.CartCount && window.CartCount.init(); } catch(_) {}
    try { window.WishlistCount && window.WishlistCount.init(); } catch(_) {}
});



// Simple lazy loading helpers (minimal)
window.initializeLazyLoading = function(){
    try {
        const images = document.querySelectorAll('img[data-src]');
        if (!('IntersectionObserver' in window)){
            images.forEach(img => window.loadImage(img));
            return;
        }
        const io = new IntersectionObserver((entries)=>{
            entries.forEach(entry => {
                if (entry.isIntersecting){
                    window.loadImage(entry.target);
                    io.unobserve(entry.target);
                }
            });
        }, { rootMargin: '50px 0px', threshold: 0.01 });
        images.forEach(img => io.observe(img));
    } catch(_) {}
};
window.loadImage = function(img){
    if (!img) return;
    const src = img.getAttribute('data-src');
    if (!src) return;
    const tmp = new Image();
    tmp.onload = function(){ img.src = src; img.removeAttribute('data-src'); };
    tmp.onerror = function(){ try { img.removeAttribute('data-src'); } catch(_) {} };
    tmp.src = src;
};

// Shared lightweight badge painter to keep UI consistent across managers
window.paintBadge = function(selectors, count) {
    try {
        const text = count > 99 ? '99+' : String(count);
        (selectors || []).forEach(selector => {
            document.querySelectorAll(selector).forEach(el => {
                el.textContent = text;
                if (count > 0) {
                    if (el.classList) {
                        el.classList.remove('hidden');
                        el.classList.add('flex');
                    }
                    if (el.style) el.style.display = '';
                } else {
                    if (el.classList) {
                        el.classList.add('hidden');
                        el.classList.remove('flex');
                    }
                    if (el.style) el.style.display = 'none';
                }
            });
        });
    } catch (_) {}
};
