/**
 * Simple Wishlist Manager - Handles all wishlist operations
 */
class SimpleWishlistManager {
    constructor() {
        this.init();
    }

    init() {
        this.bindEvents();
        this.loadCountFromStorage();
        this.initializeButtons();
        
        // Refresh wishlist status after page load to handle race conditions
        setTimeout(() => {
            this.checkWishlistStatus();
        }, 500);
    }

    bindEvents() {
        // Wishlist toggle
        $(document).on('click', '.js-wishlist-toggle', this.handleClick.bind(this));
    }

    handleClick(e) {
        e.preventDefault();
        const button = $(e.currentTarget);
        const itemId = button.data('item-id');
        const itemType = button.data('item-type');
        const isInWishlist = button.hasClass('in-wishlist');

        console.log('Wishlist click:', { itemId, itemType, isInWishlist, button });

        if (isInWishlist) {
            this.removeFromWishlist(itemType, itemId, button);
        } else {
            this.addToWishlist(itemType, itemId, button);
        }
    }

    addToWishlist(itemType, itemId, button) {
        console.log('Adding to wishlist:', itemId, itemType);
        
        // Optimistic update
        this.updateButton(button, true);
        
        fetch('/wishlist/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                item_type: itemType,
                item_id: itemId
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Add response:', data);
            
            if (data.success) {
                // Check if item was already in wishlist
                if (data.message && data.message.includes('đã có trong danh sách')) {
                    // Item already exists, show info message
                    if (typeof window.showMessage === 'function') {
                        window.showMessage(data.message, 'info');
                    }
                    // Button state is already correct from optimistic update
                } else {
                    // Item was newly added, show success message
                    if (typeof window.showMessage === 'function') {
                        window.showMessage(data.message, 'success');
                    }
                }
                
                // Update count
                if (data.wishlist_count !== undefined) {
                    this.updateCount(data.wishlist_count);
                } else {
                    this.refreshCountFromServer();
                }
            } else {
                // Revert optimistic update on error
                this.updateButton(button, false);
                if (typeof window.showMessage === 'function') {
                    window.showMessage(data.message || 'Có lỗi xảy ra!', 'error');
                }
            }
        })
        .catch(error => {
            console.error('Add error:', error);
            // Revert optimistic update on error
            this.updateButton(button, false);
            if (typeof window.showMessage === 'function') {
                window.showMessage('Có lỗi xảy ra!', 'error');
            }
        });
    }

    removeFromWishlist(itemType, itemId, button) {
        console.log('Removing from wishlist:', itemId, itemType);
        
        // Optimistic update
        this.updateButton(button, false);
        
        fetch('/wishlist/remove', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                item_type: itemType,
                item_id: itemId
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Remove response:', data);
            
            if (data.success) {
                if (typeof window.showMessage === 'function') {
                    window.showMessage(data.message, 'success');
                }
                
                // Update count
                if (data.wishlist_count !== undefined) {
                    this.updateCount(data.wishlist_count);
                } else {
                    this.refreshCountFromServer();
                }
                
                // Remove item from page if on wishlist page
                this.removeItemFromPage(itemType, itemId);
            } else {
                // Revert optimistic update on error
                this.updateButton(button, true);
                if (typeof window.showMessage === 'function') {
                    window.showMessage(data.message || 'Có lỗi xảy ra!', 'error');
                }
            }
        })
        .catch(error => {
            console.error('Remove error:', error);
            // Revert optimistic update on error
            this.updateButton(button, true);
            if (typeof window.showMessage === 'function') {
                window.showMessage('Có lỗi xảy ra!', 'error');
            }
        });
    }

    updateButton(button, isInWishlist) {
        const icon = button.find('i');
        
        if (isInWishlist) {
            button.addClass('in-wishlist').removeClass('not-in-wishlist');
            icon.removeClass('far').addClass('fas');
            icon.css('color', '#ef4444'); // Red
            console.log('Button updated to IN wishlist');
        } else {
            button.removeClass('in-wishlist').addClass('not-in-wishlist');
            icon.removeClass('fas').addClass('far');
            icon.css('color', '#374151'); // Gray
            console.log('Button updated to NOT in wishlist');
        }
        
        // Force update all buttons with same item-id AND item-type
        const itemId = button.data('item-id');
        const itemType = button.data('item-type');
        if (itemId && itemType) {
            console.log(`Updating buttons with itemId: ${itemId}, itemType: ${itemType}`);
            const matchingButtons = $(`.js-wishlist-toggle[data-item-id="${itemId}"][data-item-type="${itemType}"]`);
            console.log(`Found ${matchingButtons.length} matching buttons`);
            
            matchingButtons.each((index, btn) => {
                const $btn = $(btn);
                const $btnIcon = $btn.find('i');
                if (isInWishlist) {
                    $btn.addClass('in-wishlist').removeClass('not-in-wishlist');
                    $btnIcon.removeClass('far').addClass('fas');
                    $btnIcon.css('color', '#ef4444');
                    console.log(`Updated button ${index} to IN wishlist`);
                } else {
                    $btn.removeClass('in-wishlist').addClass('not-in-wishlist');
                    $btnIcon.removeClass('fas').addClass('far');
                    $btnIcon.css('color', '#374151');
                    console.log(`Updated button ${index} to NOT in wishlist`);
                }
            });
        }
    }

    initializeButtons() {
        $('.js-wishlist-toggle').each((index, button) => {
            const $button = $(button);
            if ($button.hasClass('in-wishlist')) {
                this.updateButton($button, true);
            } else {
                this.updateButton($button, false);
            }
        });
    }

    checkWishlistStatus() {
        const itemsByType = this.getItemsByType();
        if (Object.keys(itemsByType).length === 0) {
            console.log('No wishlist buttons found to check');
            return;
        }
        
        console.log('Checking wishlist status for items by type:', itemsByType);
        
        Object.keys(itemsByType).forEach(itemType => {
            const itemIds = itemsByType[itemType];
            console.log(`Checking ${itemType} items:`, itemIds);
            
            fetch('/wishlist/check-bulk', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    item_type: itemType,
                    item_ids: itemIds
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log(`Wishlist check response for ${itemType}:`, data);
                
                if (data.success && data.existing_ids) {
                    // Reset buttons of this type first
                    $(`.js-wishlist-toggle[data-item-type="${itemType}"]`).each((index, button) => {
                        const $button = $(button);
                        this.updateButton($button, false);
                    });
                    
                    // Then set the ones that ARE in wishlist
                    data.existing_ids.forEach(id => {
                        const buttons = $(`.js-wishlist-toggle[data-item-type="${itemType}"][data-item-id="${id}"]`);
                        buttons.each((index, button) => {
                            this.updateButton($(button), true);
                        });
                    });
                    
                    console.log(`Updated ${data.existing_ids.length} ${itemType} items to IN wishlist`);
                    console.log(`Updated ${itemIds.length - data.existing_ids.length} ${itemType} items to NOT in wishlist`);
                } else {
                    console.warn(`No existing_ids found for ${itemType} or response not successful:`, data);
                }
            })
            .catch(error => {
                console.error(`Wishlist check failed for ${itemType}:`, error);
            });
        });
    }

    getItemsByType() {
        const itemsByType = {};
        
        $('.js-wishlist-toggle').each((index, button) => {
            const $button = $(button);
            const itemType = $button.data('item-type');
            const itemId = $button.data('item-id');
            
            if (itemType && itemId) {
                if (!itemsByType[itemType]) {
                    itemsByType[itemType] = [];
                }
                if (!itemsByType[itemType].includes(itemId)) {
                    itemsByType[itemType].push(itemId);
                }
            }
        });
        
        return itemsByType;
    }

    removeItemFromPage(itemType, itemId) {
        console.log('removeItemFromPage called for:', { itemType, itemId });
        
        // Check if we're on wishlist page
        const isOnWishlistPage = window.location.pathname.includes('/wishlist');
        
        if (isOnWishlistPage) {
            // On wishlist page: remove the entire item card
            const selectors = [
                `.wishlist-item[data-item-type="${itemType}"][data-item-id="${itemId}"]`,
                `.cart-item[data-item-type="${itemType}"][data-item-id="${itemId}"]`,
                `[data-item-id="${itemId}"][data-item-type="${itemType}"]`
            ];
            
            let itemFound = false;
            
            selectors.forEach(selector => {
                const item = $(selector);
                if (item.length > 0) {
                    console.log('Removing item from wishlist page:', selector);
                    item.fadeOut(300, function() {
                        $(this).remove();
                        
                        // Check if this was the last item
                        const remainingItems = $('.wishlist-item, .cart-item').length;
                        console.log('Remaining items after removal:', remainingItems);
                        
                        if (remainingItems === 0) {
                            // Show empty state like when clearing all
                            console.log('Last item removed, showing empty state');
                            
                            // Use the centralized method to show empty state
                            if (window.wishlistPage && typeof window.wishlistPage.checkAndUpdateUI === 'function') {
                                window.wishlistPage.checkAndUpdateUI();
                            } else {
                                // Fallback: manually show empty state
                                $('#filter-section').fadeOut(300);
                                $('#clear-all-btn').closest('.flex').fadeOut(300);
                                $('#empty-state').hide().fadeIn(300);
                                
                                // Update empty state content to show "Khám phá sản phẩm" like initial state
                                $('#empty-state').html(`
                                    <div class="w-32 h-32 bg-gradient-to-br from-gray-200 to-gray-300 rounded-full mx-auto mb-8 flex items-center justify-center">
                                        <i class="fas fa-heart text-gray-400 text-5xl"></i>
                                    </div>
                                    <h3 class="text-2xl font-semibold text-gray-600 mb-4">Danh sách yêu thích trống</h3>
                                    <p class="text-gray-500 mb-8 max-w-md mx-auto">
                                        Bạn chưa có sản phẩm nào trong danh sách yêu thích. Hãy khám phá các sản phẩm và thêm vào yêu thích!
                                    </p>
                                    <a href="/" 
                                       class="inline-flex items-center bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-xl font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                                        <i class="fas fa-shopping-bag mr-2"></i>
                                        Khám phá sản phẩm
                                    </a>
                                `);
                                
                                $('span.text-white.font-semibold').text('0 sản phẩm yêu thích');
                                
                                // IMPORTANT: Update wishlist count to 0 in localStorage and all badges
                                localStorage.setItem('wishlist_count', '0');
                                $('.wishlist-count, #wishlist-count-badge, #wishlist-count-badge-mobile, [data-wishlist-count]').text('0').addClass('hidden');
                            }
                            
                            console.log('Empty state displayed after removing last item');
                        } else {
                            // Update visible count for remaining items
                            console.log('Items remaining, updating hero count to:', remainingItems);
                            
                            // Update hero section count immediately using the dedicated method
                            if (window.wishlistPage && typeof window.wishlistPage.updateHeroCount === 'function') {
                                window.wishlistPage.updateHeroCount(remainingItems);
                            } else {
                                // Fallback: update directly - target ONLY the count text span
                                $('span.text-white.font-semibold').text(`${remainingItems} sản phẩm yêu thích`);
                            }
                            
                            // Also update any other count displays
                            if (window.wishlistPage && typeof window.wishlistPage.checkAndUpdateUI === 'function') {
                                window.wishlistPage.checkAndUpdateUI();
                            }
                        }
                    });
                    itemFound = true;
                    return false; // Break the forEach loop
                }
            });
            
            if (!itemFound) {
                console.warn('Item not found on wishlist page for removal:', { itemType, itemId });
            }
        } else {
            // On other pages (home, product pages): DON'T remove anything, just update button state
            console.log('Not on wishlist page, keeping item visible, only button state updated');
            
            // The button state is already updated by updateButton method
            // No need to remove anything from the page
        }
    }

    handleClearAll() {
        // This method is called from WishlistPage, so we don't need confirmation here
        // The confirmation is already handled by WishlistPage's modern dialog
        
        console.log('SimpleWishlistManager: handleClearAll called (no confirmation needed)');

        // Show loading state on all clear buttons
        $('.js-clear-wishlist, #clear-all-btn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Đang xóa...');

        fetch('/wishlist/clear', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (typeof window.showMessage === 'function') {
                    window.showMessage(data.message, 'success');
                }
                
                // Update count
                if (data.wishlist_count !== undefined) {
                    this.updateCount(data.wishlist_count);
                } else {
                    this.refreshCountFromServer();
                }
                
                // Remove all items from page
                $('.wishlist-item, .cart-item').fadeOut(300, function() {
                    $(this).remove();
                });
                
                // Show empty state with "Khám phá sản phẩm" content
                if ($('#empty-state').length > 0) {
                    $('#empty-state').html(`
                        <div class="w-32 h-32 bg-gradient-to-br from-gray-200 to-gray-300 rounded-full mx-auto mb-8 flex items-center justify-center">
                            <i class="fas fa-heart text-gray-400 text-5xl"></i>
                        </div>
                        <h3 class="text-2xl font-semibold text-gray-600 mb-4">Danh sách yêu thích trống</h3>
                        <p class="text-gray-500 mb-8 max-w-md mx-auto">
                            Bạn chưa có sản phẩm nào trong danh sách yêu thích. Hãy khám phá các sản phẩm và thêm vào yêu thích!
                        </p>
                        <a href="/" 
                           class="inline-flex items-center bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-xl font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <i class="fas fa-shopping-bag mr-2"></i>
                            Khám phá sản phẩm
                        </a>
                    `);
                    $('#empty-state').show();
                }
                
                // IMPORTANT: Ensure count is 0 in localStorage for consistency
                localStorage.setItem('wishlist_count', '0');
            } else {
                if (typeof window.showMessage === 'function') {
                    window.showMessage(data.message || 'Có lỗi xảy ra!', 'error');
                }
            }
        })
        .catch(error => {
            console.error('Clear all error:', error);
            if (typeof window.showMessage === 'function') {
                window.showMessage('Có lỗi xảy ra!', 'error');
            }
        })
        .finally(() => {
            // Reset button states
            $('.js-clear-wishlist, #clear-all-btn').prop('disabled', false).html('<i class="fas fa-trash mr-2"></i>Xóa tất cả');
        });
    }

    updateCount(count) {
        console.log('Updating wishlist count to:', count);
        
        // Validate count
        if (typeof count !== 'number' || count < 0) {
            console.warn('Invalid count received:', count);
            this.refreshCountFromServer();
            return;
        }
        
        // Update all wishlist count badges
        const selectors = [
            '.wishlist-count',
            '#wishlist-count-badge',
            '#wishlist-count-badge-mobile',
            '[data-wishlist-count]'
        ];

        selectors.forEach(selector => {
            $(selector).each(function() {
                const badge = $(this);
                badge.text(count > 99 ? '99+' : count);
                
                // Show/hide badges based on count
                if (count > 0) {
                    badge.removeClass('hidden');
                    console.log('Wishlist badges shown for count:', count);
                } else {
                    badge.addClass('hidden');
                    console.log('Wishlist badges hidden for count:', count);
                }
            });
        });
        
        // Store count in localStorage
        localStorage.setItem('wishlist_count', count);
        
        // Store current wishlist items
        this.storeWishlistItems();
        
        console.log('Wishlist count updated to:', count);
    }

    loadCountFromStorage() {
        const storedCount = localStorage.getItem('wishlist_count');
        if (storedCount !== null) {
            const count = parseInt(storedCount);
            if (!isNaN(count) && count >= 0) {
                this.updateCount(count);
            }
        }
        
        // Load wishlist items from storage
        this.loadWishlistItemsFromStorage();
    }

    storeWishlistItems() {
        const activeItems = [];
        $('.js-wishlist-toggle.in-wishlist').each((index, button) => {
            const $button = $(button);
            activeItems.push({
                item_type: $button.data('item-type'),
                item_id: $button.data('item-id')
            });
        });
        
        localStorage.setItem('wishlist_items', JSON.stringify(activeItems));
        console.log('Stored wishlist items:', activeItems);
    }

    loadWishlistItemsFromStorage() {
        const storedItems = localStorage.getItem('wishlist_items');
        if (storedItems) {
            try {
                const items = JSON.parse(storedItems);
                console.log('Loading wishlist items from localStorage:', items);
                
                items.forEach(item => {
                    const buttons = $(`.js-wishlist-toggle[data-item-type="${item.item_type}"][data-item-id="${item.item_id}"]`);
                    if (buttons.length > 0) {
                        buttons.each((index, button) => {
                            const $button = $(button);
                            $button.addClass('in-wishlist').removeClass('not-in-wishlist');
                            const $icon = $button.find('i');
                            $icon.removeClass('far').addClass('fas');
                            $icon.css('color', '#ef4444'); // Red
                        });
                        console.log(`Applied stored wishlist status to ${item.item_type} ID ${item.item_id}`);
                    }
                });
            } catch (error) {
                console.error('Error parsing stored wishlist items:', error);
            }
        }
    }

    refreshCountFromServer() {
        fetch('/wishlist/count', {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.updateCount(data.wishlist_count);
            }
        })
        .catch(error => {
            console.error('Failed to refresh wishlist count:', error);
        });
    }

    debugWishlistStatus() {
        console.log('=== Wishlist Debug Info ===');
        console.log('Total wishlist buttons found:', $('.js-wishlist-toggle').length);
        console.log('Buttons in wishlist:', $('.js-wishlist-toggle.in-wishlist').length);
        console.log('Buttons not in wishlist:', $('.js-wishlist-toggle.not-in-wishlist').length);
        
        $('.js-wishlist-toggle').each((index, button) => {
            const $button = $(button);
            console.log(`Button ${index}:`, {
                itemId: $button.data('item-id'),
                itemType: $button.data('item-type'),
                isInWishlist: $button.hasClass('in-wishlist'),
                iconClass: $button.find('i').attr('class'),
                iconColor: $button.find('i').css('color')
            });
        });
        
        console.log('Current count from badges:', $('#wishlist-count-badge').text());
        console.log('localStorage wishlist_count:', localStorage.getItem('wishlist_count'));
        console.log('localStorage wishlist_items:', localStorage.getItem('wishlist_items'));
        console.log('=== End Debug Info ===');
    }
}

// Initialize when DOM is ready
$(document).ready(() => {
    window.wishlistManager = new SimpleWishlistManager();
    
    // Refresh wishlist status after page load to handle race conditions
    setTimeout(() => {
        if (window.wishlistManager) {
            window.wishlistManager.checkWishlistStatus();
        }
    }, 500); // Delay 500ms to ensure all elements are loaded
});

/**
 * WishlistPage - Handles wishlist page specific functionality
 */
class WishlistPage {
    constructor() {
        this.filterType = '';
        this.sortBy = 'newest';
        this.init();
    }

    init() {
        this.bindEvents();
        this.setupFilterAndSort();
    }

    bindEvents() {
        console.log('WishlistPage: Binding events...');
        
        // Clear all button
        $(document).on('click', '#clear-all-btn', this.handleClearAll.bind(this));
        console.log('WishlistPage: Clear all button event bound');
        
        // Filter change
        $(document).on('change', '#filter-type', this.handleFilterChange.bind(this));
        console.log('WishlistPage: Filter change event bound');
        
        // Sort change
        $(document).on('change', '#sort-by', this.handleSortChange.bind(this));
        console.log('WishlistPage: Sort change event bound');
        
        // Debug: Check if elements exist
        console.log('WishlistPage: Elements found:', {
            clearAllBtn: $('#clear-all-btn').length,
            filterType: $('#filter-type').length,
            sortBy: $('#sort-by').length
        });
    }

    setupFilterAndSort() {
        // Initialize filter and sort values
        this.filterType = $('#filter-type').val();
        this.sortBy = $('#sort-by').val();
    }

    handleClearAll(e) {
        e.preventDefault();
        console.log('WishlistPage: Clear all button clicked');
        
        // Show modern confirmation dialog
        this.showModernConfirmDialog(
            'Xóa tất cả sản phẩm yêu thích?',
            'Bạn có chắc chắn muốn xóa tất cả sản phẩm khỏi danh sách yêu thích? Hành động này không thể hoàn tác.',
            'Xóa tất cả',
            'Hủy bỏ',
            () => {
                // User confirmed
                this.executeClearAll();
            }
        );
    }

    showModernConfirmDialog(title, message, confirmText, cancelText, onConfirm) {
        // Remove existing dialog if any
        $('.modern-confirm-dialog').remove();
        
        // Add CSS to ensure proper layering
        if (!$('#modern-dialog-styles').length) {
            $('head').append(`
                <style id="modern-dialog-styles">
                    .modern-confirm-dialog {
                        position: fixed !important;
                        top: 0 !important;
                        left: 0 !important;
                        right: 0 !important;
                        bottom: 0 !important;
                        z-index: 99999 !important;
                        background: rgba(0, 0, 0, 0.5) !important;
                        backdrop-filter: blur(8px) !important;
                        -webkit-backdrop-filter: blur(8px) !important;
                    }
                    .modern-confirm-dialog .dialog-content {
                        position: relative !important;
                        z-index: 100000 !important;
                    }
                    .modern-confirm-dialog .backdrop {
                        position: absolute !important;
                        top: 0 !important;
                        left: 0 !important;
                        right: 0 !important;
                        bottom: 0 !important;
                        z-index: 99999 !important;
                    }
                    .modern-confirm-dialog .dialog-panel {
                        position: relative !important;
                        z-index: 100000 !important;
                        background: white !important;
                        border-radius: 1rem !important;
                        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
                    }
                </style>
            `);
        }
        
        const dialog = $(`
            <div class="modern-confirm-dialog">
                <div class="backdrop"></div>
                <div class="flex items-center justify-center min-h-screen p-4">
                    <div class="dialog-panel max-w-md w-full transform transition-all duration-300 scale-95 opacity-0">
                        <div class="p-6">
                            <!-- Icon -->
                            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                                <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                            </div>
                            
                            <!-- Title -->
                            <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">${title}</h3>
                            
                            <!-- Message -->
                            <p class="text-gray-600 text-center mb-6">${message}</p>
                            
                            <!-- Buttons -->
                            <div class="flex space-x-3">
                                <button class="cancel-btn flex-1 px-4 py-2.5 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition-colors duration-200">
                                    ${cancelText}
                                </button>
                                <button class="confirm-btn flex-1 px-4 py-2.5 text-white bg-red-600 hover:bg-red-700 rounded-lg font-medium transition-colors duration-200">
                                    ${confirmText}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `);
        
        // Add to body
        $('body').append(dialog);
        
        // Animate in
        setTimeout(() => {
            dialog.find('.dialog-panel').removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100');
        }, 10);
        
        // Bind events
        dialog.find('.cancel-btn').on('click', () => {
            this.closeModernConfirmDialog(dialog);
        });
        
        dialog.find('.confirm-btn').on('click', () => {
            this.closeModernConfirmDialog(dialog);
            onConfirm();
        });
        
        // Close on backdrop click
        dialog.find('.backdrop').on('click', () => {
            this.closeModernConfirmDialog(dialog);
        });
        
        // Close on Escape key
        $(document).on('keydown.modern-confirm', (e) => {
            if (e.key === 'Escape') {
                this.closeModernConfirmDialog(dialog);
                $(document).off('keydown.modern-confirm');
            }
        });
    }

    closeModernConfirmDialog(dialog) {
        dialog.find('.dialog-panel').removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');
        setTimeout(() => {
            dialog.remove();
            $(document).off('keydown.modern-confirm');
        }, 200);
    }

    executeClearAll() {
        console.log('WishlistPage: Executing clear all...');
        
        const button = $('#clear-all-btn');
        button.prop('disabled', true);
        
        // Show loading state
        button.html('<i class="fas fa-spinner fa-spin mr-2"></i>Đang xóa...');

        // Call API directly instead of going through wishlistManager to avoid recursion
        console.log('WishlistPage: Calling clearAllItems directly');
        this.clearAllItems(button);
    }

    clearAllItems(button) {
        console.log('WishlistPage: Calling clearAllItems API...');
        
        fetch('/wishlist/clear', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log('WishlistPage: Clear all API response:', data);
            
            if (data.success) {
                // Show success message
                if (typeof window.showMessage === 'function') {
                    window.showMessage(data.message || 'Đã xóa tất cả sản phẩm khỏi yêu thích!', 'success');
                }
                
                // Update count
                if (window.wishlistManager) {
                    window.wishlistManager.updateCount(0);
                }
                
                // Show empty state
                this.showEmptyState();
                
                // Hide filter section and clear all button
                $('#filter-section').fadeOut(300);
                $('#clear-all-btn').closest('.flex').fadeOut(300);
                
                // Update hero section count immediately - target ONLY the count text span
                $('span.text-white.font-semibold').text('0 sản phẩm yêu thích');
                
                console.log('WishlistPage: Clear all completed successfully');
            } else {
                console.error('WishlistPage: Clear all failed:', data.message);
                if (typeof window.showMessage === 'function') {
                    window.showMessage(data.message || 'Có lỗi xảy ra!', 'error');
                }
            }
        })
        .catch(error => {
            console.error('WishlistPage: Clear all error:', error);
            if (typeof window.showMessage === 'function') {
                window.showMessage('Có lỗi xảy ra khi xóa tất cả sản phẩm!', 'error');
            }
        })
        .finally(() => {
            button.prop('disabled', false);
            button.html('<i class="fas fa-trash mr-2 group-hover:scale-110 transition-transform"></i>Xóa tất cả');
        });
    }

    handleFilterChange(e) {
        console.log('WishlistPage: Filter change event triggered');
        this.filterType = $(e.currentTarget).val();
        console.log('WishlistPage: New filter type:', this.filterType);
        this.applyFilterAndSort();
    }

    handleSortChange(e) {
        console.log('WishlistPage: Sort change event triggered');
        this.sortBy = $(e.currentTarget).val();
        console.log('WishlistPage: New sort by:', this.sortBy);
        this.applyFilterAndSort();
    }

    applyFilterAndSort() {
        console.log('WishlistPage: Applying filter and sort...');
        console.log('WishlistPage: Current filter type:', this.filterType);
        console.log('WishlistPage: Current sort by:', this.sortBy);
        
        const items = $('.wishlist-item');
        console.log('WishlistPage: Found items:', items.length);
        
        items.each((index, item) => {
            const $item = $(item);
            const itemType = $item.data('type');
            const price = parseFloat($item.data('price')) || 0;
            const name = $item.data('name') || '';
            const date = new Date($item.data('date'));
            
            console.log(`WishlistPage: Item ${index}:`, { itemType, price, name, date });
            
            let showItem = true;
            
            // Apply filter
            if (this.filterType && itemType !== this.filterType) {
                showItem = false;
                console.log(`WishlistPage: Hiding item ${index} (type mismatch)`);
            }
            
            // Show/hide item based on filter
            if (showItem) {
                $item.show();
                console.log(`WishlistPage: Showing item ${index}`);
            } else {
                $item.hide();
                console.log(`WishlistPage: Hiding item ${index}`);
            }
        });
        
        // Apply sorting
        this.sortItems();
        
        // Update visible count
        this.updateVisibleCount();
    }

    sortItems() {
        const items = $('.wishlist-item:visible').get();
        
        items.sort((a, b) => {
            const $a = $(a);
            const $b = $(b);
            
            switch (this.sortBy) {
                case 'newest':
                    return new Date($b.data('date')) - new Date($a.data('date'));
                case 'oldest':
                    return new Date($a.data('date')) - new Date($b.data('date'));
                case 'price-low':
                    return parseFloat($a.data('price')) - parseFloat($b.data('price'));
                case 'price-high':
                    return parseFloat($b.data('price')) - parseFloat($a.data('price'));
                case 'name':
                    return ($a.data('name') || '').localeCompare($b.data('name') || '');
                default:
                    return 0;
            }
        });
        
        // Reorder items in DOM
        const $grid = $('#wishlist-grid');
        items.forEach(item => {
            $grid.append(item);
        });
    }

    updateVisibleCount() {
        const visibleCount = $('.wishlist-item:visible').length;
        const totalCount = $('.wishlist-item').length;
        
        console.log('WishlistPage: Updating visible count:', { visibleCount, totalCount });
        
        // Update hero section count in real-time - target ONLY the count text span
        $('span.text-white.font-semibold').text(`${visibleCount} sản phẩm yêu thích`);
        
        // Also update any other count displays that might exist
        $('[data-wishlist-count]').text(visibleCount > 99 ? '99+' : visibleCount);
        
        // Show/hide empty state
        if (visibleCount === 0) {
            if (totalCount === 0) {
                // No items at all - show empty state and hide everything
                console.log('WishlistPage: No items at all, showing empty state');
                this.showEmptyState();
            } else {
                // Items exist but filtered out - show filtered empty state
                console.log('WishlistPage: Items exist but filtered out, showing filtered empty state');
                this.showFilteredEmptyState();
            }
        } else {
            // Items are visible - hide empty states
            console.log('WishlistPage: Items are visible, hiding empty states');
            $('#empty-state').hide();
            $('#filtered-empty-state').hide();
            
            // Show filter section if it was hidden
            if ($('#filter-section').is(':hidden')) {
                $('#filter-section').fadeIn(300);
            }
            
            // Show hero section buttons if they were hidden
            if ($('#clear-all-btn').closest('.flex').is(':hidden')) {
                $('#clear-all-btn').closest('.flex').fadeIn(300);
            }
        }
        
        console.log('WishlistPage: Hero section count updated to:', visibleCount);
    }

    showEmptyState() {
        console.log('WishlistPage: Showing empty state...');
        
        // Hide all wishlist items with fade effect
        $('.wishlist-item').fadeOut(300, function() {
            $(this).remove();
        });
        
        // Hide filter section with fade effect
        $('#filter-section').fadeOut(300);
        
        // Hide hero section buttons (clear all button)
        $('#clear-all-btn').closest('.flex').fadeOut(300);
        
        // Show empty state with fade effect
        $('#empty-state').hide().fadeIn(300);
        
        // Update empty state content to show "Khám phá sản phẩm" like initial state
        $('#empty-state').html(`
            <div class="w-32 h-32 bg-gradient-to-br from-gray-200 to-gray-300 rounded-full mx-auto mb-8 flex items-center justify-center">
                <i class="fas fa-heart text-gray-400 text-5xl"></i>
            </div>
            <h3 class="text-2xl font-semibold text-gray-600 mb-4">Danh sách yêu thích trống</h3>
            <p class="text-gray-500 mb-8 max-w-md mx-auto">
                Bạn chưa có sản phẩm nào trong danh sách yêu thích. Hãy khám phá các sản phẩm và thêm vào yêu thích!
            </p>
            <a href="/" 
               class="inline-flex items-center bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-xl font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                <i class="fas fa-shopping-bag mr-2"></i>
                Khám phá sản phẩm
            </a>
        `);
        
        // Update hero section count to 0 - target ONLY the count text span
        $('span.text-white.font-semibold').text('0 sản phẩm yêu thích');
        
        // IMPORTANT: Update wishlist count to 0 in localStorage and all badges
        // This ensures count is 0 when navigating to home page
        if (window.wishlistManager && typeof window.wishlistManager.updateCount === 'function') {
            window.wishlistManager.updateCount(0);
        } else {
            // Fallback: manually update count
            localStorage.setItem('wishlist_count', '0');
            $('.wishlist-count, #wishlist-count-badge, #wishlist-count-badge-mobile, [data-wishlist-count]').text('0').addClass('hidden');
        }
        
        console.log('WishlistPage: Empty state displayed, hero section updated, count set to 0');
    }

    showFilteredEmptyState() {
        console.log('WishlistPage: Showing filtered empty state...');
        
        // Create filtered empty state if it doesn't exist
        if ($('#filtered-empty-state').length === 0) {
            const filteredEmptyState = `
                <div id="filtered-empty-state" class="text-center py-16">
                    <div class="w-32 h-32 bg-gradient-to-br from-gray-200 to-gray-300 rounded-full mx-auto mb-8 flex items-center justify-center">
                        <i class="fas fa-search text-gray-400 text-5xl"></i>
                    </div>
                    <h3 class="text-2xl font-semibold text-gray-600 mb-4">Không tìm thấy sản phẩm</h3>
                    <p class="text-gray-500 mb-8 max-w-md mx-auto">
                        Không có sản phẩm nào phù hợp với bộ lọc hiện tại. Hãy thử thay đổi bộ lọc hoặc sắp xếp!
                    </p>
                    <button onclick="location.reload()" 
                           class="inline-flex items-center bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-xl font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-refresh mr-2"></i>
                        Làm mới bộ lọc
                    </button>
                </div>
            `;
            $('#wishlist-grid').after(filteredEmptyState);
        }
        
        $('#filtered-empty-state').show();
        
        // Update hero section count to show filtered count (0) - target ONLY the count text span
        $('span.text-white.font-semibold').text('0 sản phẩm yêu thích');
        
        console.log('WishlistPage: Filtered empty state displayed, hero section updated');
    }

    // Method to check and update UI when items change
    checkAndUpdateUI() {
        const totalItems = $('.wishlist-item, .cart-item').length;
        console.log('WishlistPage: Checking UI, total items:', totalItems);
        
        if (totalItems === 0) {
            // No items left, show empty state like when clearing all
            console.log('WishlistPage: No items left, showing empty state');
            this.showEmptyState();
        } else {
            // Items exist, update visible count
            this.updateVisibleCount();
        }
    }

    // Method to update hero count immediately
    updateHeroCount(count) {
        console.log('WishlistPage: Updating hero count to:', count);
        
        // Update hero section count - target ONLY the count text span
        // This span contains the count text and is NOT inside the clear-all-btn
        $('span.text-white.font-semibold').text(`${count} sản phẩm yêu thích`);
        
        // Also update any other count displays
        $('[data-wishlist-count]').text(count > 99 ? '99+' : count);
        
        console.log('WishlistPage: Hero count updated successfully');
    }
}

// Export WishlistPage to global scope
window.WishlistPage = WishlistPage;

// Global debug function
window.debugWishlist = function() {
    if (window.wishlistManager) {
        window.wishlistManager.debugWishlistStatus();
    } else {
        console.log('WishlistManager not initialized');
    }
};

// Global refresh function
window.refreshWishlistStatus = function() {
    if (window.wishlistManager) {
        window.wishlistManager.checkWishlistStatus();
    } else {
        console.log('WishlistManager not initialized');
    }
};

// Listen for page show event (when user navigates back/forward)
$(window).on('pageshow', function() {
    console.log('WishlistManager: Page show event detected');
    if (window.wishlistManager) {
        // Check if localStorage count is different from current display
        const storedWishlistCount = parseInt(localStorage.getItem('wishlist_count')) || 0;
        const currentWishlistCount = parseInt($('.wishlist-count, #wishlist-count-badge, #wishlist-count-badge-mobile').first().text()) || 0;
        
        if (storedWishlistCount !== currentWishlistCount) {
            console.log('WishlistManager: Count mismatch detected, updating from', currentWishlistCount, 'to', storedWishlistCount);
            window.wishlistManager.updateCount(storedWishlistCount);
        } else {
            console.log('WishlistManager: Count is up to date, refreshing from server');
            window.wishlistManager.refreshCountFromServer();
        }
    }
});
