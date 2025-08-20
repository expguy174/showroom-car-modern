@extends('layouts.app')

@section('title', 'Liên hệ - AutoLux')

@section('content')

<!-- Toast Container -->
<div id="toast-container" class="fixed top-4 right-4 z-[9999] space-y-2 pointer-events-none" style="position: fixed !important; top: 1rem !important; right: 1rem !important; z-index: 9999 !important; max-width: 400px;"></div>

        <!-- Hero Section -->
<section class="relative bg-gradient-to-br from-blue-900 via-indigo-800 to-purple-700 text-white overflow-hidden">
    <div class="absolute inset-0 bg-black/20"></div>
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.1"%3E%3Ccircle cx="30" cy="30" r="2"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-30"></div>
    
    <div class="relative container mx-auto px-4 py-20 lg:py-32">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center bg-white/10 backdrop-blur-sm rounded-full px-6 py-3 mb-8">
                <i class="fas fa-phone text-blue-400 mr-3 text-xl"></i>
                <span class="text-sm font-medium">Liên hệ với chúng tôi</span>
        </div>

            <h1 class="text-4xl lg:text-6xl font-bold mb-6 leading-tight">
                Liên hệ
                <span class="text-blue-400 block">AutoLux</span>
            </h1>
            
            <p class="text-xl lg:text-2xl text-indigo-100 max-w-3xl mx-auto leading-relaxed">
                Chúng tôi luôn sẵn sàng lắng nghe và hỗ trợ bạn. Hãy để lại tin nhắn hoặc liên hệ trực tiếp
                            </p>
                        </div>
                    </div>
</section>

<!-- Contact Information -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-16">
                <!-- Phone -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-8 text-center hover:shadow-lg transition-all duration-300 group">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-phone text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Điện thoại</h3>
                    <p class="text-blue-600 font-semibold text-lg mb-2">+84 24 1234 5678</p>
                    <p class="text-gray-600 text-sm">Thứ 2 - Chủ nhật: 8:00 - 20:00</p>
                        </div>

                <!-- Email -->
                <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-8 text-center hover:shadow-lg transition-all duration-300 group">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-envelope text-white text-2xl"></i>
                        </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Email</h3>
                    <p class="text-purple-600 font-semibold text-lg mb-2">info@autolux.vn</p>
                    <p class="text-gray-600 text-sm">Phản hồi trong vòng 24 giờ</p>
                    </div>

                <!-- Address -->
                <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-2xl p-8 text-center hover:shadow-lg transition-all duration-300 group">
                    <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-map-marker-alt text-white text-2xl"></i>
                        </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Địa chỉ</h3>
                    <p class="text-emerald-600 font-semibold text-lg mb-2">123 Đường ABC, Quận XYZ</p>
                    <p class="text-gray-600 text-sm">Hà Nội, Việt Nam</p>
                        </div>
                    </div>

            <!-- Main Contact Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
                <!-- Contact Form -->
                <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                    <div class="mb-8">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">Gửi tin nhắn</h2>
                        <p class="text-gray-600">Hãy để lại thông tin và chúng tôi sẽ liên hệ lại sớm nhất</p>
                        </div>

                    <form id="contact-form" action="{{ route('contact.store') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        @if(auth()->check())
                            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                                <div class="flex items-center">
                                    <i class="fas fa-user-check text-blue-500 mr-3"></i>
                        <div>
                                        <p class="text-blue-800 font-medium">Xin chào, {{ auth()->user()->name }}!</p>
                                        <p class="text-blue-600 text-sm">Thông tin cá nhân đã được điền sẵn. Bạn có thể chỉnh sửa nếu cần.</p>
                        </div>
                    </div>
                </div>
                        @endif
                        
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-user text-blue-500 mr-2"></i>
                                Họ và tên <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ request('name', old('name', auth()->user()->name ?? '')) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300"
                                   placeholder="Nhập họ và tên của bạn"
                                   required>
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-envelope text-blue-500 mr-2"></i>
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ request('email', old('email', auth()->user()->email ?? '')) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300"
                                   placeholder="Nhập email của bạn"
                                   required>
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-phone text-blue-500 mr-2"></i>
                                Số điện thoại
                            </label>
                            <input type="tel" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ request('phone', old('phone', auth()->user()->phone ?? '')) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300"
                                   placeholder="Nhập số điện thoại">
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Subject -->
                        <div>
                            <label for="subject" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-tag text-blue-500 mr-2"></i>
                                Chủ đề <span class="text-red-500">*</span>
                            </label>
                            <select id="subject" 
                                    name="subject" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300"
                                    required>
                                <option value="">Chọn chủ đề</option>
                                <option value="general" {{ request('subject') == 'general' || old('subject') == 'general' ? 'selected' : '' }}>Thông tin chung</option>
                                <option value="sales" {{ request('subject') == 'sales' || old('subject') == 'sales' ? 'selected' : '' }}>Tư vấn mua xe</option>
                                <option value="service" {{ request('subject') == 'service' || old('subject') == 'service' ? 'selected' : '' }}>Dịch vụ bảo dưỡng</option>
                                <option value="finance" {{ request('subject') == 'finance' || old('subject') == 'finance' ? 'selected' : '' }}>Tư vấn tài chính</option>
                                <option value="complaint" {{ request('subject') == 'complaint' || old('subject') == 'complaint' ? 'selected' : '' }}>Khiếu nại</option>
                                <option value="other" {{ request('subject') == 'other' || old('subject') == 'other' ? 'selected' : '' }}>Khác</option>
                            </select>
                            @error('subject')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Message -->
                        <div>
                            <label for="message" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-comment text-blue-500 mr-2"></i>
                                Nội dung tin nhắn <span class="text-red-500">*</span>
                            </label>
                            <textarea id="message" 
                                      name="message" 
                                      rows="5"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 resize-none"
                                      placeholder="Nhập nội dung tin nhắn của bạn"
                                      required>{{ request('message', old('message')) }}</textarea>
                            @error('message')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-4 rounded-xl font-semibold hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                            <i class="fas fa-paper-plane mr-2"></i>
                            <span class="button-text">Gửi tin nhắn</span>
                        </button>
                        
                        @guest
                            <div class="text-center">
                                <p class="text-gray-600 text-sm mb-2">Bạn đã có tài khoản?</p>
                                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-medium text-sm">
                                    <i class="fas fa-sign-in-alt mr-1"></i>
                                    Đăng nhập để điền sẵn thông tin
                        </a>
                    </div>
                        @endguest
                    </form>
                </div>

                <!-- Contact Details & Map -->
                <div class="space-y-8">
                    <!-- Office Hours -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-clock text-blue-600 mr-3"></i>
                            Giờ làm việc
                        </h3>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-3 border-b border-blue-100">
                                <span class="font-semibold text-gray-700">Thứ 2 - Thứ 6</span>
                                <span class="text-blue-600 font-semibold">8:00 - 18:00</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-blue-100">
                                <span class="font-semibold text-gray-700">Thứ 7</span>
                                <span class="text-blue-600 font-semibold">8:00 - 17:00</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-blue-100">
                                <span class="font-semibold text-gray-700">Chủ nhật</span>
                                <span class="text-blue-600 font-semibold">9:00 - 16:00</span>
                            </div>
                        </div>
                        
                        <div class="mt-6 p-4 bg-blue-100 rounded-xl">
                            <p class="text-blue-800 text-sm">
                                <i class="fas fa-info-circle mr-2"></i>
                                <strong>Lưu ý:</strong> Chúng tôi vẫn hỗ trợ khách hàng qua điện thoại và email ngoài giờ làm việc
                            </p>
                </div>
            </div>

                    <!-- Quick Contact -->
                    <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-2xl p-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-headset text-emerald-600 mr-3"></i>
                            Hỗ trợ nhanh
                        </h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-center p-4 bg-white rounded-xl shadow-sm">
                                <div class="w-12 h-12 bg-emerald-500 rounded-full flex items-center justify-center mr-4">
                                    <i class="fas fa-phone text-white"></i>
                        </div>
                        <div>
                                    <div class="font-semibold text-gray-900">Hotline 24/7</div>
                                    <div class="text-emerald-600 font-bold">1900 1234</div>
                        </div>
                    </div>
                    
                            <div class="flex items-center p-4 bg-white rounded-xl shadow-sm">
                                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mr-4">
                                    <i class="fab fa-facebook-messenger text-white"></i>
                                </div>
                    <div>
                                    <div class="font-semibold text-gray-900">Facebook Messenger</div>
                                    <div class="text-blue-600 font-bold">@AutoLux</div>
                                </div>
                    </div>
                    
                            <div class="flex items-center p-4 bg-white rounded-xl shadow-sm">
                                <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mr-4">
                                    <i class="fab fa-whatsapp text-white"></i>
                                </div>
                    <div>
                                    <div class="font-semibold text-gray-900">WhatsApp</div>
                                    <div class="text-green-600 font-bold">+84 912 345 678</div>
                                </div>
                    </div>
                    </div>
            </div>
        </div>
                </div>
            </div>
        </div>
</section>

        <!-- FAQ Section -->
<section class="py-20 bg-gradient-to-r from-gray-50 to-gray-100">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                    Câu hỏi thường gặp
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Những câu hỏi phổ biến về dịch vụ và quy trình làm việc của chúng tôi
                    </p>
                </div>
                
            <div class="space-y-4">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
                    <button class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors duration-300" onclick="toggleFAQ(this)">
                        <span class="font-semibold text-gray-900">Làm thế nào để đặt lịch hẹn xem xe?</span>
                        <i class="fas fa-chevron-down text-blue-500 transition-transform duration-300"></i>
                    </button>
                    <div class="px-6 pb-4 hidden">
                        <p class="text-gray-600">Bạn có thể đặt lịch hẹn qua điện thoại, email hoặc điền form trên website. Chúng tôi sẽ liên hệ lại để xác nhận lịch hẹn trong vòng 2 giờ.</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
                    <button class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors duration-300" onclick="toggleFAQ(this)">
                        <span class="font-semibold text-gray-900">Thời gian giao xe sau khi đặt cọc là bao lâu?</span>
                        <i class="fas fa-chevron-down text-blue-500 transition-transform duration-300"></i>
                    </button>
                    <div class="px-6 pb-4 hidden">
                        <p class="text-gray-600">Thời gian giao xe phụ thuộc vào loại xe và tình trạng kho. Thông thường từ 3-7 ngày làm việc sau khi hoàn tất thủ tục.</p>
                    </div>
                </div>
                
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
                    <button class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors duration-300" onclick="toggleFAQ(this)">
                        <span class="font-semibold text-gray-900">Chính sách bảo hành như thế nào?</span>
                        <i class="fas fa-chevron-down text-blue-500 transition-transform duration-300"></i>
                    </button>
                    <div class="px-6 pb-4 hidden">
                        <p class="text-gray-600">Chúng tôi cung cấp bảo hành chính hãng theo tiêu chuẩn của nhà sản xuất. Thời gian bảo hành từ 3-5 năm tùy thuộc vào loại xe.</p>
                    </div>
                </div>
                
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
                    <button class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors duration-300" onclick="toggleFAQ(this)">
                        <span class="font-semibold text-gray-900">Có hỗ trợ trả góp không?</span>
                        <i class="fas fa-chevron-down text-blue-500 transition-transform duration-300"></i>
                    </button>
                    <div class="px-6 pb-4 hidden">
                        <p class="text-gray-600">Có, chúng tôi hợp tác với nhiều ngân hàng để cung cấp gói vay trả góp linh hoạt với lãi suất cạnh tranh và thủ tục đơn giản.</p>
                    </div>
                </div>
                </div>
            </div>
        </div>
</section>

        <!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-blue-600 to-indigo-600 text-white">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl lg:text-4xl font-bold mb-6">
                Sẵn sàng tìm xe mơ ước?
            </h2>
            <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
                Hãy để AutoLux đồng hành cùng bạn trong hành trình tìm kiếm chiếc xe hoàn hảo
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('products.index') }}" 
                   class="inline-flex items-center bg-white text-blue-600 px-8 py-4 rounded-xl font-semibold hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-car mr-2"></i>
                    Xem xe ngay
                </a>
                <a href="{{ route('finance.index') }}" 
                   class="inline-flex items-center border-2 border-white text-white px-8 py-4 rounded-xl font-semibold hover:bg-white hover:text-blue-600 transition-all duration-300 transform hover:scale-105">
                    <i class="fas fa-calculator mr-2"></i>
                    Tư vấn tài chính
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<style>
/* Custom toast styles to ensure visibility */
#toast-container {
    position: fixed !important;
    top: 1rem !important;
    right: 1rem !important;
    z-index: 9999 !important;
    max-width: 400px;
    pointer-events: none;
}

#toast-container > div {
    pointer-events: auto;
    margin-bottom: 0.5rem;
    transform: translateX(100%);
    transition: transform 0.3s ease-in-out;
}

#toast-container > div.show {
    transform: translateX(0) !important;
}

/* Ensure toast is visible */
.toast-visible {
    transform: translateX(0) !important;
    opacity: 1 !important;
    visibility: visible !important;
}
</style>

<script>
function toggleFAQ(button) {
    const content = button.nextElementSibling;
    const icon = button.querySelector('i');
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    } else {
        content.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
}

// Toast notification functions
function showToast(message, type = 'success') {
    const toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        return;
    }
    
    const toast = document.createElement('div');
    
    const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
    
    toast.className = `${bgColor} text-white px-6 py-4 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full max-w-sm pointer-events-auto relative`;
    toast.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${icon} mr-3 text-lg"></i>
            <span class="font-medium flex-1">${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
</div>
    `;
    
    toastContainer.appendChild(toast);
    
    // Animate in
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
        toast.classList.add('show', 'toast-visible');
    }, 100);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (toast.parentElement) {
            toast.classList.add('translate-x-full');
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.remove();
                }
            }, 300);
        }
    }, 5000);
}

// Pre-fill form fields from query parameters
document.addEventListener('DOMContentLoaded', function() {
    
    const params = new URLSearchParams(window.location.search);
    
    if (params.get('subject')) {
        document.getElementById('subject').value = params.get('subject');
    }
    
    if (params.get('message')) {
        document.getElementById('message').value = params.get('message');
    }
    
    // Handle form submission
    const form = document.getElementById('contact-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            const buttonText = submitBtn.querySelector('.button-text');
            const originalText = buttonText.textContent;
            
            // Disable button and show loading
            submitBtn.disabled = true;
            buttonText.textContent = 'Đang gửi...';
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    
                    // Reset form completely
                    form.reset();
                    
                    // Manually reset subject and message to default values
                    const subjectSelect = document.getElementById('subject');
                    const messageTextarea = document.getElementById('message');
                    
                    if (subjectSelect) {
                        subjectSelect.value = ''; // Reset to "Chọn chủ đề"
                    }
                    if (messageTextarea) {
                        messageTextarea.value = ''; // Reset to empty
                    }
                    
                    // Clear URL parameters but keep the anchor
                    if (window.history && window.history.replaceState) {
                        const url = new URL(window.location);
                        url.searchParams.delete('subject');
                        url.searchParams.delete('message');
                        // Keep the #contact-form anchor
                        const newUrl = url.pathname + '#contact-form';
                        window.history.replaceState({}, document.title, newUrl);
                    }
                    
                    // Re-enable button
                    submitBtn.disabled = false;
                    buttonText.textContent = originalText;
                } else {
                    showToast(data.message, 'error');
                    submitBtn.disabled = false;
                    buttonText.textContent = originalText;
                }
            })
            .catch(error => {
                showToast('Có lỗi xảy ra khi gửi tin nhắn. Vui lòng thử lại sau.', 'error');
                submitBtn.disabled = false;
                buttonText.textContent = originalText;
            });
        });
    }
});
</script>
@endpush 