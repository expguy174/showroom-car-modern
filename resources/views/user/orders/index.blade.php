@extends('layouts.app')

@section('title', 'Đơn hàng của tôi')

@section('content')
<div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8 py-6 sm:py-8">
    <div class="flex items-center justify-between mb-4 sm:mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl md:text-3xl font-extrabold tracking-tight">Đơn hàng của tôi</h1>
            <p class="text-sm sm:text-base text-gray-500 mt-1">Xem lịch sử đặt hàng và trạng thái xử lý</p>
        </div>
        <div class="hidden sm:flex items-center gap-2">
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 text-sm font-semibold">
                <i class="fas fa-shopping-bag"></i> Mua thêm
            </a>
        </div>
    </div>

    <form id="orders-filter-form" action="{{ route('user.order.index') }}" method="get" class="mb-4 sm:mb-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tìm kiếm</label>
                    <div class="relative">
                        <input type="text" name="q" value="{{ $q ?? request('q') }}" placeholder="Mã đơn, mã vận đơn..." class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 pr-10">
                        <span class="absolute inset-y-0 right-3 flex items-center text-gray-400"><i class="fas fa-search"></i></span>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Trạng thái đơn hàng</label>
                    <select name="status" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Tất cả</option>
                        @foreach(\App\Models\Order::STATUSES as $st)
                            <option value="{{ $st }}" @selected(($status ?? request('status')) === $st)>{{ (new \App\Models\Order(['status'=>$st]))->status_display }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Trạng thái thanh toán</label>
                    <select name="payment_status" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Tất cả</option>
                        @php($__allowedPaymentStatuses = ['pending','processing','completed','failed','cancelled'])
                        @foreach($__allowedPaymentStatuses as $pst)
                            <option value="{{ $pst }}" @selected(($payment_status ?? request('payment_status')) === $pst)>{{ (new \App\Models\Order(['payment_status'=>$pst]))->payment_status_display }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-3 flex items-center justify-end gap-2"></div>
        </div>
    </form>
    <div class="flex items-center justify-between text-sm text-gray-600 mb-3">
        <div>Tổng: <span class="font-semibold">{{ number_format($orders->total()) }}</span> đơn hàng</div>
        <div>
            @php($from=$orders->firstItem() ?? 0)
            @php($to=$orders->lastItem() ?? 0)
            Hiển thị: <span class="font-semibold">{{ $from }}</span>–<span class="font-semibold">{{ $to }}</span>
        </div>
    </div>
    <div id="orders-list-wrapper">
        @include('user.orders.partials.list', ['orders' => $orders])
    </div>

    <div id="orders-pagination" class="mt-6">
        @include('components.pagination-modern', ['paginator' => $orders->withQueryString()])
    </div>

</div>
@endsection

@push('scripts')
<script>
    (function(){
        const form = document.getElementById('orders-filter-form');
        const wrapper = document.getElementById('orders-list-wrapper');
        const pagination = document.getElementById('orders-pagination');
        const pageRoot = document.getElementById('orders-filter-form') || document.getElementById('orders-list-wrapper');

        function scrollToTop(){
            try {
                const nav = document.getElementById('main-nav');
                const headerOffset = (nav && typeof nav.offsetHeight === 'number') ? (nav.offsetHeight + 12) : 80;
                const target = pageRoot || document.body;
                const y = (target.getBoundingClientRect().top + window.scrollY) - headerOffset;
                window.scrollTo({ top: Math.max(0, y), behavior: 'smooth' });
            } catch {}
        }

        function submitAjax(url){
            const params = new URLSearchParams(new FormData(form));
            const fetchUrl = url || (form.getAttribute('action') + '?' + params.toString());
            wrapper.innerHTML = '<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center text-gray-500"><i class="fas fa-spinner fa-spin"></i> Đang tải...</div>';
            fetch(fetchUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' }})
                .then(r => r.json())
                .then(res => {
                    if (res && res.html !== undefined) {
                        wrapper.innerHTML = res.html || '';
                        if (pagination) pagination.innerHTML = res.pagination || '';
                        bindPagination();
                        scrollToTop();
                    } else {
                        wrapper.innerHTML = '<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center text-gray-500">Không tải được dữ liệu</div>';
                    }
                })
                .catch(() => {
                    wrapper.innerHTML = '<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center text-gray-500">Có lỗi xảy ra</div>';
                });
        }

        function bindPagination(){
            if (!pagination) return;
            pagination.querySelectorAll('a').forEach(a => {
                a.addEventListener('click', function(e){
                    e.preventDefault();
                    const url = this.getAttribute('href');
                    if (url) submitAjax(url);
                });
            });
        }

        // Handle cancel order forms with confirm dialog like wishlist
        document.addEventListener('click', function(e) {
            if (e.target.closest('form[action*="/cancel"]')) {
                e.preventDefault();
                const form = e.target.closest('form');
                const button = form.querySelector('button[type="submit"]');
                const canCancel = !button.disabled;
                
                if (canCancel) {
                    const orderNumber = form.closest('.bg-white').querySelector('a[href*="/orders/"]').textContent;
                    
                    // Use confirm dialog like wishlist
                    showConfirmDialog(
                        'Hủy đơn hàng?',
                        `Bạn có chắc chắn muốn hủy đơn hàng ${orderNumber}? Hành động này không thể hoàn tác.`,
                        'Hủy đơn',
                        'Hủy bỏ',
                        () => {
                            // Show loading state
                            button.disabled = true;
                            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Đang hủy...';
                            
                            // Submit the form and reload page after success
                            fetch(form.action, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => {
                                if (response.ok) {
                                    // Parse JSON response
                                    return response.json().then(data => {
                                        if (data.success) {
                                            // Show success message
                                            if (typeof window.showMessage === 'function') {
                                                window.showMessage(data.message || 'Đã hủy đơn hàng thành công', 'success');
                                            }
                                            
                                            // Update UI without reload
                                            updateOrderStatusAfterCancel(form, orderNumber);
                                        } else {
                                            throw new Error(data.message || 'Failed to cancel order');
                                        }
                                    });
                                } else {
                                    // Handle different error status codes
                                    if (response.status === 403) {
                                        throw new Error('Bạn không có quyền hủy đơn hàng này');
                                    } else if (response.status === 422) {
                                        return response.json().then(data => {
                                            throw new Error(data.message || 'Đơn hàng không thể hủy ở trạng thái hiện tại');
                                        });
                                    } else {
                                        throw new Error('Có lỗi xảy ra khi hủy đơn hàng');
                                    }
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                if (typeof window.showMessage === 'function') {
                                    window.showMessage(error.message || 'Có lỗi xảy ra khi hủy đơn hàng', 'error');
                                } else {
                                    alert(error.message || 'Có lỗi xảy ra khi hủy đơn hàng');
                                }
                                // Reset button state
                                button.disabled = false;
                                button.innerHTML = '<i class="fas fa-ban mr-1"></i> Hủy đơn';
                            });
                        }
                    );
                }
            }
        });

        // Update order status after cancellation
        function updateOrderStatusAfterCancel(form, orderNumber) {
            const orderCard = form.closest('.bg-white');
            const button = form.querySelector('button[type="submit"]');
            
            // Disable the cancel button
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-ban mr-1"></i> Đã hủy';
            button.classList.remove('bg-red-500', 'hover:bg-red-600');
            button.classList.add('bg-gray-400', 'cursor-not-allowed');
            
            // Update order status badge
            const statusBadge = orderCard.querySelector('.bg-yellow-50, .bg-blue-50, .bg-indigo-50, .bg-emerald-50, .bg-rose-50');
            if (statusBadge) {
                statusBadge.className = 'inline-flex items-center px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-full text-[10px] sm:text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200';
                statusBadge.innerHTML = '<i class="fas fa-ban mr-1"></i> Đã hủy';
            }
            
            // Add visual feedback
            orderCard.style.opacity = '0.7';
            orderCard.style.transition = 'opacity 0.3s ease';
            
            // Optional: Add a small "Cancelled" indicator
            const statusContainer = orderCard.querySelector('.flex.flex-wrap.items-center.gap-2');
            if (statusContainer && !statusContainer.querySelector('.cancelled-indicator')) {
                const cancelledIndicator = document.createElement('span');
                cancelledIndicator.className = 'cancelled-indicator inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-gray-100 text-gray-600 border border-gray-200';
                cancelledIndicator.innerHTML = '<i class="fas fa-times mr-1"></i> Đã hủy';
                statusContainer.appendChild(cancelledIndicator);
            }
        }

        // Confirm dialog function (same as wishlist)
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
            
            // Animate in
            requestAnimationFrame(() => {
                panel.style.transform = 'scale(1)';
                panel.style.opacity = '1';
            });
            
            // Handle clicks
            wrapper.querySelector('.fast-cancel').addEventListener('click', () => {
                panel.style.transform = 'scale(0.95)';
                panel.style.opacity = '0';
                setTimeout(() => wrapper.remove(), 200);
            });
            
            wrapper.querySelector('.fast-confirm').addEventListener('click', () => {
                panel.style.transform = 'scale(0.95)';
                panel.style.opacity = '0';
                setTimeout(() => {
                    wrapper.remove();
                    onConfirm();
                }, 200);
            });
            
            // Close on backdrop click
            wrapper.addEventListener('click', (e) => {
                if (e.target === wrapper) {
                    panel.style.transform = 'scale(0.95)';
                    panel.style.opacity = '0';
                    setTimeout(() => wrapper.remove(), 200);
                }
            });
        }

        form.addEventListener('submit', function(e){
            e.preventDefault();
            submitAjax();
        });

        // Show success message if order was cancelled
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof window.showMessage === 'function') {
                const status = '{{ session("status") }}';
                if (status) {
                    window.showMessage(status, 'success');
                }
            }
        });
        form.querySelectorAll('select').forEach(s => s.addEventListener('change', function(){ submitAjax(); }));

        // Debounce helper
        function debounce(fn, wait){
            let t; return function(){
                const args = arguments, ctx = this;
                clearTimeout(t);
                t = setTimeout(function(){ fn.apply(ctx, args); }, wait);
            };
        }
        // Auto filter on typing in search input (300ms)
        const searchInput = form.querySelector('input[name="q"]');
        if (searchInput){
            const debounced = debounce(function(){ submitAjax(); }, 300);
            searchInput.addEventListener('input', debounced);
        }

        bindPagination();
    })();
</script>
@endpush


