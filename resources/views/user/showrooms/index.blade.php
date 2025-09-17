@extends('layouts.app')

@section('title', 'Showroom & Đại lý')

@section('content')
<div class="container mx-auto px-3 sm:px-4 md:px-6 lg:px-8 py-6 sm:py-8">
    <div class="flex items-center justify-between mb-4 sm:mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl md:text-3xl font-extrabold tracking-tight">Showroom & Đại lý</h1>
            <p class="text-sm sm:text-base text-gray-500 mt-1">Tìm showroom gần bạn để xem và lái thử xe</p>
        </div>
        <div class="hidden sm:flex items-center gap-2">
            <a href="{{ route('user.showrooms.map') }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 text-sm font-semibold">
                <i class="fas fa-map-marker-alt"></i> Xem bản đồ
            </a>
        </div>
    </div>

    <!-- Filters -->
    <form method="GET" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tìm kiếm</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Tên showroom, địa chỉ, số điện thoại..." 
                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Thành phố</label>
                <select name="city" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Tất cả thành phố</option>
                    @foreach($cities as $city)
                        <option value="{{ $city }}" @selected(request('city') === $city)>{{ $city }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Đại lý</label>
                <select name="dealership_id" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Tất cả đại lý</option>
                    @foreach($dealerships as $dealership)
                        <option value="{{ $dealership->id }}" @selected(request('dealership_id') == $dealership->id)>{{ $dealership->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">
                    <i class="fas fa-search mr-2"></i>Tìm kiếm
                </button>
            </div>
        </div>
    </form>

    <!-- Showrooms Grid -->
    @if($showrooms->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($showrooms as $showroom)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-shadow">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-6 text-white">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-xl font-bold mb-2">{{ $showroom->name }}</h3>
                                @if($showroom->dealership)
                                    <span class="px-2 py-1 bg-white bg-opacity-20 rounded-full text-xs font-medium">
                                        {{ $showroom->dealership->name }}
                                    </span>
                                @endif
                            </div>
                            <div class="text-right">
                                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                    <i class="fas fa-store text-xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-6">
                        <!-- Address -->
                        <div class="flex items-start gap-3 mb-4">
                            <i class="fas fa-map-marker-alt text-indigo-600 mt-1"></i>
                            <div>
                                <p class="text-gray-900 font-medium">{{ $showroom->address }}</p>
                                <p class="text-gray-600 text-sm">{{ $showroom->city }}</p>
                            </div>
                        </div>

                        <!-- Contact Info -->
                        <div class="space-y-2 mb-6">
                            @if($showroom->phone)
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-phone text-indigo-600 w-4"></i>
                                    <a href="tel:{{ $showroom->phone }}" class="text-gray-900 hover:text-indigo-600">{{ $showroom->phone }}</a>
                                </div>
                            @endif
                            @if($showroom->email)
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-envelope text-indigo-600 w-4"></i>
                                    <a href="mailto:{{ $showroom->email }}" class="text-gray-900 hover:text-indigo-600">{{ $showroom->email }}</a>
                                </div>
                            @endif
                        </div>

                        <!-- Working Hours -->
                        @if($showroom->working_hours)
                            <div class="bg-gray-50 rounded-lg p-3 mb-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <i class="fas fa-clock text-indigo-600"></i>
                                    <span class="text-sm font-medium text-gray-700">Giờ làm việc</span>
                                </div>
                                <p class="text-sm text-gray-600">{{ $showroom->working_hours }}</p>
                            </div>
                        @endif

                        <!-- Actions -->
                        <div class="grid grid-cols-2 gap-3">
                            <a href="{{ route('user.showrooms.show', $showroom) }}" 
                               class="flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium text-sm">
                                <i class="fas fa-eye"></i> Chi tiết
                            </a>
                            @if($showroom->latitude && $showroom->longitude)
                                <button onclick="getDirections({{ $showroom->latitude }}, {{ $showroom->longitude }}, '{{ $showroom->name }}')" 
                                        class="flex items-center justify-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium text-sm">
                                    <i class="fas fa-directions"></i> Chỉ đường
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="flex justify-center">
            {{ $showrooms->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <i class="fas fa-store text-4xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Không tìm thấy showroom nào</h3>
            <p class="text-gray-500 mb-6">Không có showroom nào phù hợp với tiêu chí tìm kiếm của bạn.</p>
            <a href="{{ route('user.showrooms.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 font-medium">
                <i class="fas fa-refresh"></i> Xem tất cả
            </a>
        </div>
    @endif
</div>

<!-- Quick Stats -->
<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="text-center">
                <div class="text-3xl font-bold text-indigo-600 mb-2">{{ $showrooms->total() }}</div>
                <div class="text-gray-600">Showroom</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-indigo-600 mb-2">{{ $cities->count() }}</div>
                <div class="text-gray-600">Thành phố</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-indigo-600 mb-2">{{ $dealerships->count() }}</div>
                <div class="text-gray-600">Đại lý</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-indigo-600 mb-2">24/7</div>
                <div class="text-gray-600">Hỗ trợ</div>
            </div>
        </div>
    </div>
</div>

<script>
function getDirections(lat, lng, name) {
    // Check if geolocation is supported
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const userLat = position.coords.latitude;
            const userLng = position.coords.longitude;
            
            // Open Google Maps with directions
            const url = 'https://www.google.com/maps/dir/' + userLat + ',' + userLng + '/' + lat + ',' + lng;
            window.open(url, '_blank');
        }, function() {
            // If geolocation fails, just open the location
            const url = 'https://www.google.com/maps/search/?api=1&query=' + lat + ',' + lng;
            window.open(url, '_blank');
        });
    } else {
        // If geolocation is not supported, just open the location
        const url = 'https://www.google.com/maps/search/?api=1&query=' + lat + ',' + lng;
        window.open(url, '_blank');
    }
}
</script>
@endsection
