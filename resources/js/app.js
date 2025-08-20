import './bootstrap';
import './wishlist-manager';
import './cart-manager';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Global loading state management
window.loadingStates = {
    cart: false,
    
    compare: false,
    quickView: false
};

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

// Global JavaScript functions for car showroom functionality
window.addToCart = function(itemType, itemId, event) {
    if (event) event.preventDefault();
    
    const button = event ? event.target.closest('button') : document.querySelector(`button[onclick*="${itemId}"]`);
    
    if (window.loadingStates.cart) return; // Prevent double clicks
    window.loadingStates.cart = true;
    
    showButtonLoading(button, 'Đang thêm...');
    
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: `item_type=${itemType}&item_id=${itemId}&quantity=1`
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
};



// Compare functionality
window.compareList = JSON.parse(localStorage.getItem('compareList') || '[]');

window.addToCompare = function(carId) {
    if (window.compareList.length >= 4) {
        showMessage('Tối đa 4 xe có thể so sánh cùng lúc', 'warning');
        return;
    }
    
    if (window.compareList.includes(carId)) {
        showMessage('Xe này đã có trong danh sách so sánh', 'info');
        return;
    }
    
    window.compareList.push(carId);
    localStorage.setItem('compareList', JSON.stringify(window.compareList));
    showMessage('Đã thêm vào danh sách so sánh', 'success');
    
    // Update compare count if function exists
    if (typeof updateCompareCount === 'function') {
        updateCompareCount(window.compareList.length);
    }
    
    // Update compare button if exists
    updateCompareUI();
};

window.removeFromCompare = function(carId) {
    window.compareList = window.compareList.filter(id => id !== carId);
    localStorage.setItem('compareList', JSON.stringify(window.compareList));
    showMessage('Đã xóa xe khỏi danh sách so sánh', 'info');
    
    if (typeof updateCompareCount === 'function') {
        updateCompareCount(window.compareList.length);
    }
    
    updateCompareUI();
};

window.updateCompareUI = function() {
    const compareBtn = document.getElementById('compareBtn');
    if (compareBtn) {
        if (window.compareList.length > 0) {
            compareBtn.classList.remove('hidden');
            compareBtn.innerHTML = `<i class="fas fa-balance-scale mr-2"></i>So sánh (${window.compareList.length})`;
        } else {
            compareBtn.classList.add('hidden');
        }
    }
};

window.openCompareModal = function() {
    if (window.compareList.length === 0) {
        showMessage('Chưa có xe nào trong danh sách so sánh', 'info');
        return;
    }
    
    const modal = document.getElementById('compare-modal');
    if (modal) {
        modal.setAttribute('data-auto-open', 'true');
        openModal('compare-modal');
        
        // Trigger comparison rendering if function exists
        if (typeof renderCompareList === 'function') {
            renderCompareList();
        }
    }
};

// Quick View functionality
window.openCarQuickView = function(id) {
    console.log('Đang tải thông tin xe ID:', id);
    
    // Fetch dữ liệu thực từ API
    fetch(`/api/v1/variants/${id}`)
        .then(response => {
            console.log('API Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('API Response data:', data);
            if (data.success && data.data) {
                const variant = data.data;
                renderQuickViewModal(variant);
            } else {
                console.error('API không trả về dữ liệu hợp lệ');
                renderQuickViewFallback(id);
            }
        })
        .catch(error => {
            console.error('Lỗi khi tải thông tin xe:', error);
            console.log('Sử dụng fallback...');
            renderQuickViewFallback(id);
        });
};

window.openAccessoryQuickView = function(id) {
    console.log('Đang tải thông tin phụ kiện ID:', id);
    
    // Fetch dữ liệu thực từ API
    fetch(`/api/v1/accessories/${id}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success && data.data) {
                const accessory = data.data;
                renderAccessoryQuickViewModal(accessory);
            } else {
                renderAccessoryQuickViewFallback(id);
            }
        })
        .catch(error => {
            console.error('Lỗi khi tải thông tin phụ kiện:', error);
            renderAccessoryQuickViewFallback(id);
        });
};

window.renderQuickViewModal = function(variant) {
    console.log('Rendering modal với variant:', variant);
    
    // Tính năng từ database
    let features = [];
    if (variant.technology_features) {
        try {
            const techFeatures = JSON.parse(variant.technology_features);
            features = techFeatures.slice(0, 4);
        } catch (e) {
            features = ['Hệ thống an toàn tiên tiến', 'Màn hình cảm ứng', 'Kết nối smartphone', 'Hệ thống âm thanh cao cấp'];
        }
    } else {
        features = ['Hệ thống an toàn tiên tiến', 'Màn hình cảm ứng', 'Kết nối smartphone', 'Hệ thống âm thanh cao cấp'];
    }
    
    // Màu sắc từ database
    let colors = [];
    if (variant.colors && variant.colors.length > 0) {
        const colorMap = {
            'Đen': '#000000', 'Trắng': '#FFFFFF', 'Đỏ': '#DC2626', 'Xanh dương': '#1E3A8A',
            'Xanh lá': '#059669', 'Xám': '#6B7280', 'Bạc': '#9CA3AF', 'Vàng': '#F59E0B',
            'Cam': '#EA580C', 'Tím': '#7C3AED', 'Xanh': '#1E3A8A', 'Trắng ngọc': '#E0F2FE', 'Đen bóng': '#1F2937'
        };
        
        colors = variant.colors.map(color => colorMap[color.color_name] || '#FFFFFF').slice(0, 5);
    }
    
    const modalContent = `
        <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full p-6 relative">
            <button type="button" class="absolute close-modal-btn text-gray-400 hover:text-red-500 text-2xl" data-modal-id="quickview-modal" style="z-index:50;top:16px;right:16px;">
                <i class="fas fa-times"></i>
            </button>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Hình ảnh -->
                <div class="relative">
                    <img src="${variant.image_url || '/img/car-placeholder.jpg'}" alt="${variant.name}" class="w-full h-64 object-cover rounded-xl">
                    <div class="absolute top-4 left-4">
                        <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold">Mới</span>
                    </div>
                </div>
                
                <!-- Thông tin -->
                <div class="space-y-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">${variant.name}</h2>
                        <p class="text-gray-600 mb-4">${variant.description || 'Xe hơi cao cấp với thiết kế hiện đại và công nghệ tiên tiến.'}</p>
                        <div class="text-3xl font-bold text-indigo-600">${new Intl.NumberFormat('vi-VN').format(variant.price)} VNĐ</div>
                    </div>
                    
                    <!-- Màu sắc -->
                    ${colors.length > 0 ? `
                    <div>
                        <h3 class="text-lg font-semibold mb-3">Màu sắc có sẵn</h3>
                        <div class="flex space-x-2">
                            ${colors.map(color => `
                                <div class="w-8 h-8 rounded-full border-2 border-gray-300" style="background-color: ${color}"></div>
                            `).join('')}
                        </div>
                    </div>
                    ` : ''}
                    
                    <!-- Tính năng -->
                    <div>
                        <h3 class="text-lg font-semibold mb-3">Tính năng nổi bật</h3>
                        <ul class="space-y-2">
                            ${features.map(feature => `
                                <li class="flex items-center text-gray-700">
                                    <i class="fas fa-check text-green-500 mr-2"></i>
                                    ${feature}
                                </li>
                            `).join('')}
                        </ul>
                    </div>
                    
                    <!-- Nút hành động -->
                    <div class="flex space-x-3 pt-4">
                        <button onclick="addToCart('car_variant', ${variant.id}, event)" class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all duration-300">
                            <i class="fas fa-shopping-cart mr-2"></i>Thêm vào giỏ
                        </button>
        
                            <i class="far fa-heart"></i>
                        </button>
                        <button onclick="addToCompare(${variant.id})" class="w-12 h-12 bg-gray-100 text-gray-600 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-all duration-300">
                            <i class="fas fa-balance-scale"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('quickview-content').innerHTML = modalContent;
    
    // Bind close button
    const closeBtn = document.querySelector('#quickview-content .close-modal-btn');
    if (closeBtn) {
        closeBtn.onclick = function(e) { 
            e.stopPropagation(); 
            closeModal('quickview-modal'); 
        };
    }
    
    openModal('quickview-modal');
};

window.renderQuickViewFallback = function(id) {
    const modalContent = `
        <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full p-6 relative">
            <button type="button" class="absolute close-modal-btn text-gray-400 hover:text-red-500 text-2xl" data-modal-id="quickview-modal" style="z-index:50;top:16px;right:16px;">
                <i class="fas fa-times"></i>
            </button>
            
            <div class="text-center py-12">
                <i class="fas fa-car text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Đang tải thông tin xe...</h3>
                <p class="text-gray-500">Vui lòng chờ trong giây lát</p>
            </div>
        </div>
    `;
    
    document.getElementById('quickview-content').innerHTML = modalContent;
    
    const closeBtn = document.querySelector('#quickview-content .close-modal-btn');
    if (closeBtn) {
        closeBtn.onclick = function(e) { 
            e.stopPropagation(); 
            closeModal('quickview-modal'); 
        };
    }
    
    openModal('quickview-modal');
};

window.renderAccessoryQuickViewModal = function(accessory) {
    const modalContent = `
        <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full p-6 relative">
            <button type="button" class="absolute close-modal-btn text-gray-400 hover:text-red-500 text-2xl" data-modal-id="quickview-modal" style="z-index:50;top:16px;right:16px;">
                <i class="fas fa-times"></i>
            </button>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Hình ảnh -->
                <div class="relative">
                    <img src="${accessory.image_url || '/img/accessory-placeholder.jpg'}" alt="${accessory.name}" class="w-full h-64 object-cover rounded-xl">
                </div>
                
                <!-- Thông tin -->
                <div class="space-y-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">${accessory.name}</h2>
                        <p class="text-gray-600 mb-4">${accessory.description || 'Phụ kiện chất lượng cao cho xe hơi.'}</p>
                        <div class="text-3xl font-bold text-emerald-600">${new Intl.NumberFormat('vi-VN').format(accessory.price)} VNĐ</div>
                    </div>
                    
                    <!-- Thông số -->
                    <div>
                        <h3 class="text-lg font-semibold mb-3">Thông số kỹ thuật</h3>
                        <ul class="space-y-2">
                            <li class="flex items-center text-gray-700">
                                <i class="fas fa-tag text-emerald-500 mr-2"></i>
                                <span class="font-medium">Danh mục:</span> ${accessory.category || 'Phụ kiện'}
                            </li>
                            <li class="flex items-center text-gray-700">
                                <i class="fas fa-box text-emerald-500 mr-2"></i>
                                <span class="font-medium">Tình trạng:</span> ${accessory.is_active ? 'Có sẵn' : 'Hết hàng'}
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Nút hành động -->
                    <div class="flex space-x-3 pt-4">
                        <button onclick="addToCart('accessory', ${accessory.id}, event)" class="flex-1 bg-gradient-to-r from-emerald-600 to-green-500 text-white py-3 rounded-lg font-semibold hover:from-emerald-700 hover:to-green-600 transition-all duration-300">
                            <i class="fas fa-shopping-cart mr-2"></i>Thêm vào giỏ
                        </button>
        
                            <i class="far fa-heart"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('quickview-content').innerHTML = modalContent;
    
    const closeBtn = document.querySelector('#quickview-content .close-modal-btn');
    if (closeBtn) {
        closeBtn.onclick = function(e) { 
            e.stopPropagation(); 
            closeModal('quickview-modal'); 
        };
    }
    
    openModal('quickview-modal');
};

window.renderAccessoryQuickViewFallback = function(id) {
    const modalContent = `
        <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full p-6 relative">
            <button type="button" class="absolute close-modal-btn text-gray-400 hover:text-red-500 text-2xl" data-modal-id="quickview-modal" style="z-index:50;top:16px;right:16px;">
                <i class="fas fa-times"></i>
            </button>
            
            <div class="text-center py-12">
                <i class="fas fa-tools text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Đang tải thông tin phụ kiện...</h3>
                <p class="text-gray-500">Vui lòng chờ trong giây lát</p>
            </div>
        </div>
    `;
    
    document.getElementById('quickview-content').innerHTML = modalContent;
    
    const closeBtn = document.querySelector('#quickview-content .close-modal-btn');
    if (closeBtn) {
        closeBtn.onclick = function(e) { 
            e.stopPropagation(); 
            closeModal('quickview-modal'); 
        };
    }
    
    openModal('quickview-modal');
};

window.shareCar = function(carId, carName) {
    if (navigator.share) {
        navigator.share({
            title: carName,
            text: `Khám phá ${carName} tại showroom xe hơi của chúng tôi`,
            url: window.location.origin + '/car-variants/' + carId
        });
    } else {
        // Fallback: copy to clipboard
        const url = window.location.origin + '/car-variants/' + carId;
        navigator.clipboard.writeText(url).then(() => {
            showMessage('Đã sao chép link vào clipboard', 'success');
        });
    }
};

window.bookTestDrive = function(carId) {
    // Redirect to test drive booking page
    window.location.href = `/test-drives/create?car_variant_id=${carId}`;
};

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
    // Create toast notification
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full`;
    
    const colors = {
        success: 'bg-green-500 text-white',
        error: 'bg-red-500 text-white',
        warning: 'bg-yellow-500 text-white',
        info: 'bg-blue-500 text-white'
    };
    
    toast.className += ` ${colors[type] || colors.info}`;
    toast.innerHTML = `
        <div class="flex items-center">
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Animate in
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
    }, 100);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => {
            if (toast.parentElement) {
                toast.remove();
            }
        }, 300);
    }, 5000);
};

// Global cart count update function
window.updateCartCount = function(count) {
    console.log('Global updateCartCount called with:', count);
    
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
};

// Global wishlist count update function
window.updateWishlistCount = function(count) {
    console.log('Global updateWishlistCount called with:', count);
    
    if (window.wishlistManager && typeof window.wishlistManager.updateCount === 'function') {
        window.wishlistManager.updateCount(count);
    } else {
        console.warn('WishlistManager not available for count update');
    }
};

// Initialize compare UI on page load
document.addEventListener('DOMContentLoaded', function() {
    window.updateCompareUI();
    
    // Initialize lazy loading
    initializeLazyLoading();
    
    // Close modal when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-backdrop')) {
            const modal = e.target.closest('.modal-backdrop');
            if (modal) {
                const modalId = modal.id;
                closeModal(modalId);
            }
        }
    });
    
    // Listen for localStorage changes to sync wishlist/cart counts across tabs/pages
    window.addEventListener('storage', function(e) {
        console.log('Storage event detected:', e.key, e.newValue);
        
        if (e.key === 'wishlist_count') {
            const newCount = parseInt(e.newValue) || 0;
            console.log('Wishlist count changed in storage:', newCount);
            
            // Update wishlist count on current page
            if (typeof window.updateWishlistCount === 'function') {
                window.updateWishlistCount(newCount);
            }
        }
        
        if (e.key === 'cart_count') {
            const newCount = parseInt(e.newValue) || 0;
            console.log('Cart count changed in storage:', newCount);
            
            // Update cart count on current page
            if (typeof window.updateCartCount === 'function') {
                window.updateCartCount(newCount);
            }
        }
    });
    
    // Listen for page visibility changes (when user navigates back/forward)
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            console.log('Page became visible, checking for count updates...');
            
            // Check if counts in localStorage are different from current display
            const storedWishlistCount = parseInt(localStorage.getItem('wishlist_count')) || 0;
            const storedCartCount = parseInt(localStorage.getItem('cart_count')) || 0;
            
            // Update wishlist count if different
            if (window.wishlistManager && typeof window.wishlistManager.updateCount === 'function') {
                const currentWishlistCount = parseInt($('.wishlist-count, #wishlist-count-badge, #wishlist-count-badge-mobile').first().text()) || 0;
                if (storedWishlistCount !== currentWishlistCount) {
                    console.log('Wishlist count mismatch, updating from', currentWishlistCount, 'to', storedWishlistCount);
                    window.wishlistManager.updateCount(storedWishlistCount);
                }
            }
            
            // Update cart count if different
            if (window.cartManager && typeof window.cartManager.updateCount === 'function') {
                const currentCartCount = parseInt($('.cart-count, #cart-count-badge, #cart-count-badge-mobile').first().text()) || 0;
                if (storedCartCount !== currentCartCount) {
                    console.log('Cart count mismatch, updating from', currentCartCount, 'to', storedCartCount);
                    window.cartManager.updateCount(storedCartCount);
                }
            }
        }
    });
});

// Lazy loading functionality
window.initializeLazyLoading = function() {
    const images = document.querySelectorAll('img[data-src]');
    
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                loadImage(img);
                observer.unobserve(img);
            }
        });
    });
    
    images.forEach(img => {
        imageObserver.observe(img);
    });
};

window.loadImage = function(img) {
    const src = img.dataset.src;
    if (!src) return;
    
    // Show placeholder
    img.classList.add('image-placeholder');
    
    const tempImage = new Image();
    tempImage.onload = function() {
        img.src = src;
        img.classList.remove('image-placeholder');
        img.classList.add('lazy-image', 'loaded');
        img.removeAttribute('data-src');
        // Remove sibling skeleton/placeholder overlays if present
        try {
            const container = img.parentElement;
            if (container) {
                const overlays = container.querySelectorAll('.image-placeholder, .skeleton-image');
                overlays.forEach(function(el){ if (el !== img) el.remove(); });
            }
        } catch (e) { /* noop */ }
    };
    
    tempImage.onerror = function() {
        img.classList.remove('image-placeholder');
        img.classList.add('image-error');
        img.innerHTML = '<i class="fas fa-image mr-2"></i>Không thể tải hình ảnh';
    };
    
    tempImage.src = src;
};
