/**
 * Count System - Enterprise E-commerce Grade
 * Handles cart, wishlist, and notifications counts like Shopee/Amazon
 * Features: Cross-tab sync, Background sync, Error recovery, Retry logic
 */

window.CountSystem = {
    // Cross-tab communication channel
    broadcastChannel: null,
    
    // Count update functions - simple and direct
    updateCart: function(count) {
        const num = parseInt(count) || 0;
        const text = num > 99 ? '99+' : String(num);
        
        // Save to localStorage for persistence
        try { 
            localStorage.setItem('cart_count', String(num)); 
        } catch(_) {}
        
        // Update all cart badges
        document.querySelectorAll('.cart-count, #cart-count-badge, #cart-count-badge-mobile, [data-cart-count]').forEach(el => {
            el.textContent = text;
            if (num > 0) {
                el.classList.remove('hidden');
                el.classList.add('flex');
                el.style.display = 'flex';
            } else {
                el.classList.add('hidden');
                el.classList.remove('flex');
                el.style.display = 'none';
            }
        });
        
        // Broadcast to other tabs (cross-tab sync)
        this.broadcastToOtherTabs('cart_update', num);
    },
    
    updateWishlist: function(count) {
        const num = parseInt(count) || 0;
        const text = num > 99 ? '99+' : String(num);
        
        // Save to localStorage for persistence
        try { 
            localStorage.setItem('wishlist_count', String(num)); 
        } catch(_) {}
        
        // Update all wishlist badges
        document.querySelectorAll('.wishlist-count, #wishlist-count-badge, #wishlist-count-badge-mobile, [data-wishlist-count], .wishlist-count-badge, .js-wishlist-count').forEach(el => {
            el.textContent = text;
            if (num > 0) {
                el.classList.remove('hidden');
                el.classList.add('flex');
                el.style.display = 'flex';
            } else {
                el.classList.add('hidden');
                el.classList.remove('flex');
                el.style.display = 'none';
            }
        });
        
        // Broadcast to other tabs (cross-tab sync)
        this.broadcastToOtherTabs('wishlist_update', num);
    },
    
    updateNotifications: function(count) {
        const num = parseInt(count) || 0;
        const text = num > 99 ? '99+' : String(num);
        
        // Save to localStorage for persistence
        try { 
            localStorage.setItem('notification_count', String(num)); 
        } catch(_) {}
        
        // Update all notification badges (nav component selectors)
        document.querySelectorAll('.notification-count, #notif-count-badge, #notif-count-badge-mobile, [data-notification-count]').forEach(el => {
            el.textContent = text;
            if (num > 0) {
                el.classList.remove('hidden');
                el.classList.add('flex');
                el.style.display = 'flex';
            } else {
                el.classList.add('hidden');
                el.classList.remove('flex');
                el.style.display = 'none';
            }
        });
                // Broadcast to other tabs (cross-tab sync)
        this.broadcastToOtherTabs('notification_update', num);
    },
    
    // Server sync functions with retry logic (Enterprise feature)
    refreshCartFromServer: async function(retryCount = 0) {
        try {
            const response = await fetch('/user/cart/count', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (response.ok) {
                const data = await response.json();
                if (data.success) {
                    const count = parseInt(data.cart_count || 0, 10);
                    this.updateCart(count);
                }
            } else {
                throw new Error(`HTTP ${response.status}`);
            }
        } catch (error) {
            // Retry with exponential backoff (Enterprise pattern)
            if (retryCount < 3) {
                const delay = Math.pow(2, retryCount) * 1000; // 1s, 2s, 4s
                setTimeout(() => {
                    this.refreshCartFromServer(retryCount + 1);
                }, delay);
            }
        }
    },

    refreshNotificationBadge: async function() {
        try {
            const response = await fetch('/notifications/unread-count', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (response.ok) {
                const data = await response.json();
                if (data.success && data.data) {
                    const count = parseInt(data.data.unread_count || 0, 10);
                    this.updateNotifications(count);
                }
            }
        } catch (error) {
            // Silent fail for notifications
        }
    },
    
    // Cross-tab communication functions
    broadcastToOtherTabs: function(type, count) {
        if (this.broadcastChannel) {
            try {
                this.broadcastChannel.postMessage({
                    type: type,
                    count: count,
                    timestamp: Date.now(),
                    source: 'CountSystem'
                });
            } catch (error) {
                // Silent fail for cross-tab sync
            }
        }
    },
    
    handleCrossTabMessage: function(event) {
        if (event.data.source !== 'CountSystem') return;
        
        const { type, count } = event.data;
        
        // Update UI only, don't broadcast back (prevent loops)
        switch (type) {
            case 'cart_update':
                this.updateCartUI(count);
                break;
            case 'wishlist_update':
                this.updateWishlistUI(count);
                break;
            case 'notification_update':
                this.updateNotificationUI(count);
                break;
        }
    },
    
    // UI-only update functions (no localStorage, no broadcast)
    updateCartUI: function(count) {
        const num = parseInt(count) || 0;
        const text = num > 99 ? '99+' : String(num);
        
        document.querySelectorAll('.cart-count, #cart-count-badge, #cart-count-badge-mobile, [data-cart-count]').forEach(el => {
            el.textContent = text;
            if (num > 0) {
                el.classList.remove('hidden');
                el.classList.add('flex');
                el.style.display = 'flex';
            } else {
                el.classList.add('hidden');
                el.classList.remove('flex');
                el.style.display = 'none';
            }
        });
    },
    
    updateWishlistUI: function(count) {
        const num = parseInt(count) || 0;
        const text = num > 99 ? '99+' : String(num);
        
        document.querySelectorAll('.wishlist-count, #wishlist-count-badge, #wishlist-count-badge-mobile, [data-wishlist-count], .wishlist-count-badge, .js-wishlist-count').forEach(el => {
            el.textContent = text;
            if (num > 0) {
                el.classList.remove('hidden');
                el.classList.add('flex');
                el.style.display = 'flex';
            } else {
                el.classList.add('hidden');
                el.classList.remove('flex');
                el.style.display = 'none';
            }
        });
    },
    
    updateNotificationUI: function(count) {
        const num = parseInt(count) || 0;
        const text = num > 99 ? '99+' : String(num);
        
        document.querySelectorAll('.notification-count, #notif-count-badge, #notif-count-badge-mobile, [data-notification-count]').forEach(el => {
            el.textContent = text;
            if (num > 0) {
                el.classList.remove('hidden');
                el.classList.add('flex');
                el.style.display = 'flex';
            } else {
                el.classList.add('hidden');
                el.classList.remove('flex');
                el.style.display = 'none';
            }
        });
    },
    
    // Initialize system
    init: function() {
        // Set global functions for backward compatibility
        window.updateCartCount = this.updateCart;
        window.updateWishlistCount = this.updateWishlist;
        window.updateNotificationCount = this.updateNotifications;
        
        // Set global notification functions for nav component
        window.refreshNotifBadge = this.refreshNotificationBadge;
        
        // Initialize cross-tab communication
        try {
            this.broadcastChannel = new BroadcastChannel('showroom-count-sync');
            this.broadcastChannel.addEventListener('message', this.handleCrossTabMessage.bind(this));
        } catch (error) {
            // BroadcastChannel not supported, cross-tab sync disabled
        }
        
        // Auto-sync counts from server after a short delay to avoid conflicts
        setTimeout(() => {
            this.refreshCartFromServer();
            
            // Also sync notifications if user is authenticated
            if (document.querySelector('meta[name="user-authenticated"]')) {
                this.refreshNotificationBadge();
            }
        }, 1000);
        
        // Setup background sync (Enterprise feature)
        this.setupBackgroundSync();
        
        // Setup visibility change sync
        this.setupVisibilitySync();
    },
    
    // Background sync setup (Enterprise feature)
    setupBackgroundSync: function() {
        // Auto-sync every 30 seconds when page is visible
        setInterval(() => {
            if (document.visibilityState === 'visible') {
                this.refreshCartFromServer();
                
                // Also sync notifications if user is authenticated
                if (document.querySelector('meta[name="user-authenticated"]')) {
                    this.refreshNotificationBadge();
                }
            }
        }, 30000); // 30 seconds like Amazon/Shopee
    },
    
    // Visibility change sync setup
    setupVisibilitySync: function() {
        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'visible') {
                // Page became visible, sync immediately
                setTimeout(() => {
                    this.refreshCartFromServer();
                    
                    // Also sync notifications if user is authenticated
                    if (document.querySelector('meta[name="user-authenticated"]')) {
                        this.refreshNotificationBadge();
                    }
                }, 500); // Small delay to avoid conflicts
            }
        });
    }
};

// Auto-initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => CountSystem.init());
} else {
    CountSystem.init();
}
