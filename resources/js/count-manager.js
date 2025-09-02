// count-manager.js - Unified counters for cart and wishlist
(function(){
    const LS_CART = 'cart_count';
    const LS_WISH = 'wishlist_count';

    function safeInt(v){ const n = parseInt(v, 10); return Number.isFinite(n) && n >= 0 ? n : 0; }

    function setCartCount(count){
        try { localStorage.setItem(LS_CART, String(count)); } catch(_) {}
        // Direct DOM update to avoid infinite loop with window.updateCartCount
        ['.cart-count', '#cart-count-badge', '#cart-count-badge-mobile', '[data-cart-count]'].forEach(sel => {
            document.querySelectorAll(sel).forEach(el => {
                el.textContent = count > 99 ? '99+' : String(count);
                if (count > 0) el.classList.remove('hidden'); else el.classList.add('hidden');
            });
        });
    }

    function setWishlistCount(count){
        try { localStorage.setItem(LS_WISH, String(count)); } catch(_) {}
        // Direct DOM update to avoid infinite loop with window.updateWishlistCount
        ['.wishlist-count', '#wishlist-count-badge', '#wishlist-count-badge-mobile', '[data-wishlist-count]'].forEach(sel => {
            document.querySelectorAll(sel).forEach(el => {
                el.textContent = count > 99 ? '99+' : String(count);
                if (count > 0) el.classList.remove('hidden'); else el.classList.add('hidden');
            });
        });
    }

    async function fetchCartCount(){
        try {
            const res = await fetch('/user/cart/count', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const d = await res.json();
            if (d && d.success && typeof d.cart_count === 'number') setCartCount(d.cart_count);
        } catch(_){ /* silent */ }
    }

    async function fetchWishlistCount(){
        try {
            const res = await fetch('/wishlist/count', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const d = await res.json();
            if (d && d.success && typeof d.wishlist_count === 'number') setWishlistCount(d.wishlist_count);
        } catch(_){ /* silent */ }
    }

    function initFromLocalStorage(){
        setCartCount(safeInt(localStorage.getItem(LS_CART)));
        setWishlistCount(safeInt(localStorage.getItem(LS_WISH)));
    }

    function bindStorageSync(){
        window.addEventListener('storage', function(e){
            if (e.key === LS_CART) setCartCount(safeInt(e.newValue));
            if (e.key === LS_WISH) setWishlistCount(safeInt(e.newValue));
        });
        
        // Also handle page visibility changes and navigation
        window.addEventListener('pageshow', function(){
            // Reconcile counts when page is shown (e.g., back/forward navigation)
            fetchCartCount();
            fetchWishlistCount();
        });
        
        window.addEventListener('focus', function(){
            // Reconcile counts when tab becomes active
            fetchCartCount();
            fetchWishlistCount();
        });
    }

    // Public API for optimistic flows
    window.CountManager = {
        setCart: setCartCount,
        setWishlist: setWishlistCount,
        reconcileCart: fetchCartCount,
        reconcileWishlist: fetchWishlistCount,
        init(){
            initFromLocalStorage();
            // Reconcile immediately on page load for accuracy
            fetchCartCount();
            fetchWishlistCount();
            bindStorageSync();
        }
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => window.CountManager.init());
    } else {
        window.CountManager.init();
    }
})();


