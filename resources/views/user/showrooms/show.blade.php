@extends('layouts.app')

@section('title', $showroom->name . ' - Chi tiết showroom')

@section('content')
<div class="container mx-auto px-3 sm:px-4 md:px-6 lg:px-8 py-6 sm:py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('user.showrooms.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600">
                    <i class="fas fa-store mr-2"></i>Showroom
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-sm font-medium text-gray-500">{{ $showroom->name }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Hero Section -->
            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl p-8 text-white relative overflow-hidden">
                <div class="absolute inset-0 opacity-10">
                    <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <pattern id="hero-pattern" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                                <circle cx="10" cy="10" r="2" fill="currentColor"/>
                            </pattern>
                        </defs>
                        <rect width="100" height="100" fill="url(#hero-pattern)"/>
                    </svg>
                </div>

                <div class="relative z-10">
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <h1 class="text-3xl font-bold mb-2">{{ $showroom->name }}</h1>
                            @if($showroom->dealership)
                                <span class="px-3 py-1 bg-white bg-opacity-20 rounded-full text-sm font-medium">
                                    {{ $showroom->dealership->name }}
                                </span>
                            @endif
                        </div>
                        <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <i class="fas fa-store text-2xl"></i>
                        </div>
                    </div>

                    @if($showroom->description)
                        <p class="text-lg opacity-90">{{ $showroom->description }}</p>
                    @endif
                </div>
            </div>

            <!-- Contact Information -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Thông tin liên hệ</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-map-marker-alt text-indigo-600 mt-1"></i>
                            <div>
                                <h3 class="font-medium text-gray-900 mb-1">Địa chỉ</h3>
                                <p class="text-gray-600">{{ $showroom->address }}</p>
                                <p class="text-gray-600">{{ $showroom->city }}</p>
                            </div>
                        </div>

                        @if($showroom->phone)
                        <div class="flex items-start gap-3">
                            <i class="fas fa-phone text-indigo-600 mt-1"></i>
                            <div>
                                <h3 class="font-medium text-gray-900 mb-1">Số điện thoại</h3>
                                <a href="tel:{{ $showroom->phone }}" class="text-indigo-600 hover:text-indigo-700">{{ $showroom->phone }}</a>
                            </div>
                        </div>
                        @endif

                        @if($showroom->email)
                        <div class="flex items-start gap-3">
                            <i class="fas fa-envelope text-indigo-600 mt-1"></i>
                            <div>
                                <h3 class="font-medium text-gray-900 mb-1">Email</h3>
                                <a href="mailto:{{ $showroom->email }}" class="text-indigo-600 hover:text-indigo-700">{{ $showroom->email }}</a>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="space-y-4">
                        @if($showroom->working_hours)
                        <div class="flex items-start gap-3">
                            <i class="fas fa-clock text-indigo-600 mt-1"></i>
                            <div>
                                <h3 class="font-medium text-gray-900 mb-1">Giờ làm việc</h3>
                                <p class="text-gray-600 whitespace-pre-line">{{ $showroom->working_hours }}</p>
                            </div>
                        </div>
                        @endif

                        @if($showroom->services)
                        <div class="flex items-start gap-3">
                            <i class="fas fa-tools text-indigo-600 mt-1"></i>
                            <div>
                                <h3 class="font-medium text-gray-900 mb-1">Dịch vụ</h3>
                                <p class="text-gray-600">{{ $showroom->services }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Map -->
            @if($showroom->latitude && $showroom->longitude)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Vị trí</h2>
                <div class="aspect-video bg-gray-200 rounded-lg flex items-center justify-center">
                    <div class="text-center">
                        <i class="fas fa-map text-4xl text-gray-400 mb-4"></i>
                        <p class="text-gray-600 mb-4">Bản đồ showroom</p>
                        <button onclick="openMap()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">
                            <i class="fas fa-external-link-alt mr-2"></i>Xem trên Google Maps
                        </button>
                    </div>
                </div>
            </div>
            @endif

            <!-- Available Vehicles -->
            @if($availableVariants->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900">Xe có sẵn tại showroom</h2>
                    <a href="{{ route('products.index') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">
                        Xem tất cả →
                    </a>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($availableVariants as $variant)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-start gap-4">
                                @if($variant->images->first())
                                    <img src="{{ $variant->images->first()->image_url }}" 
                                         alt="{{ $variant->name }}" 
                                         class="w-20 h-16 object-cover rounded-lg">
                                @else
                                    <div class="w-20 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-car text-gray-400"></i>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <h3 class="font-medium text-gray-900 mb-1">{{ $variant->name }}</h3>
                                    <p class="text-sm text-gray-600 mb-2">{{ $variant->carModel->carBrand->name }} {{ $variant->carModel->name }}</p>
                                    <p class="text-lg font-bold text-indigo-600">
                                        {{ number_format($variant->current_price, 0, ',', '.') }} VND
                                    </p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('user.car-variants.show', $variant) }}" 
                                   class="block w-full text-center py-2 bg-indigo-100 text-indigo-700 rounded-lg hover:bg-indigo-200 font-medium">
                                    Xem chi tiết
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Nearby Showrooms -->
            @if($nearbyShowrooms->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Showroom khác tại {{ $showroom->city }}</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($nearbyShowrooms as $nearby)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <h3 class="font-medium text-gray-900 mb-2">{{ $nearby->name }}</h3>
                            <p class="text-sm text-gray-600 mb-2">{{ $nearby->address }}</p>
                            @if($nearby->phone)
                                <p class="text-sm text-gray-600 mb-3">
                                    <i class="fas fa-phone mr-1"></i>{{ $nearby->phone }}
                                </p>
                            @endif
                            <a href="{{ route('user.showrooms.show', $nearby) }}" 
                               class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                                Xem chi tiết →
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Thao tác nhanh</h3>
                <div class="space-y-3">
                    @if($showroom->phone)
                    <a href="tel:{{ $showroom->phone }}" 
                       class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium">
                        <i class="fas fa-phone"></i> Gọi ngay
                    </a>
                    @endif

                    @if($showroom->latitude && $showroom->longitude)
                    <button onclick="getDirections()" 
                            class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                        <i class="fas fa-directions"></i> Chỉ đường
                    </button>
                    @endif

                    <button onclick="openContactModal()" 
                            class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">
                        <i class="fas fa-envelope"></i> Gửi tin nhắn
                    </button>

                    <a href="{{ route('user.showrooms.index') }}" 
                       class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 font-medium">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>

            <!-- Dealership Info -->
            @if($showroom->dealership)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Thông tin đại lý</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Tên đại lý</p>
                        <p class="text-gray-900">{{ $showroom->dealership->name }}</p>
                    </div>
                    @if($showroom->dealership->description)
                    <div>
                        <p class="text-sm font-medium text-gray-500">Mô tả</p>
                        <p class="text-gray-900 text-sm">{{ $showroom->dealership->description }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Services -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Dịch vụ tại showroom</h3>
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-car text-indigo-600"></i>
                        <span class="text-gray-900">Xem và lái thử xe</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fas fa-handshake text-indigo-600"></i>
                        <span class="text-gray-900">Tư vấn bán hàng</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fas fa-tools text-indigo-600"></i>
                        <span class="text-gray-900">Bảo dưỡng & Sửa chữa</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fas fa-credit-card text-indigo-600"></i>
                        <span class="text-gray-900">Hỗ trợ tài chính</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fas fa-shield-alt text-indigo-600"></i>
                        <span class="text-gray-900">Bảo hành chính hãng</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Contact Modal -->
<div id="contactModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Gửi tin nhắn tới {{ $showroom->name }}</h3>
            <form action="{{ route('user.showrooms.contact', $showroom) }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Họ tên</label>
                        <input type="text" name="name" required 
                               class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" required 
                               class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại</label>
                        <input type="tel" name="phone" required 
                               class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Chủ đề</label>
                        <input type="text" name="subject" required 
                               class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tin nhắn</label>
                        <textarea name="message" rows="4" required 
                                  class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                  placeholder="Nhập tin nhắn của bạn..."></textarea>
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeContactModal()" 
                            class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 font-medium">
                        Hủy
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">
                        Gửi tin nhắn
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const showroom = @json($showroom);

function openContactModal() {
    document.getElementById('contactModal').classList.remove('hidden');
}

function closeContactModal() {
    document.getElementById('contactModal').classList.add('hidden');
}

function openMap() {
    const url = 'https://www.google.com/maps/search/?api=1&query=' + showroom.latitude + ',' + showroom.longitude;
    window.open(url, '_blank');
}

function getDirections() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const userLat = position.coords.latitude;
            const userLng = position.coords.longitude;
            
            const url = 'https://www.google.com/maps/dir/' + userLat + ',' + userLng + '/' + showroom.latitude + ',' + showroom.longitude;
            window.open(url, '_blank');
        }, function() {
            openMap();
        });
    } else {
        openMap();
    }
}

// Close modal when clicking outside
document.getElementById('contactModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeContactModal();
    }
});
</script>
@endsection
