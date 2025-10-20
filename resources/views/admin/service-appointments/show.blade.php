@extends('layouts.admin')

@section('title', 'Chi tiết lịch hẹn #' . $appointment->appointment_number)

@section('content')
<div class="container mx-auto px-4 py-6">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.service-appointments.index') }}" 
               class="text-gray-600 hover:text-gray-900">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Chi tiết lịch hẹn</h1>
                <p class="text-sm text-gray-500 mt-1">{{ $appointment->appointment_number }}</p>
            </div>
        </div>
        
        {{-- Status Badge --}}
        <div id="statusBadgeContainer">
            @switch($appointment->status)
                @case('scheduled')
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        <i class="fas fa-calendar mr-2"></i>
                        Đã đặt lịch
                    </span>
                    @break
                @case('confirmed')
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-2"></i>
                        Đã xác nhận
                    </span>
                    @break
                @case('in_progress')
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                        <i class="fas fa-cog mr-2"></i>
                        Đang thực hiện
                    </span>
                    @break
                @case('completed')
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <i class="fas fa-flag-checkered mr-2"></i>
                        Hoàn thành
                    </span>
                    @break
                @case('cancelled')
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-red-100 text-red-800">
                        <i class="fas fa-times-circle mr-2"></i>
                        Đã hủy
                    </span>
                    @break
            @endswitch
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Customer Information --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-user text-blue-600 mr-2"></i>
                    Thông tin khách hàng
                </h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Tên khách hàng</p>
                        <p class="font-medium text-gray-900">{{ $appointment->user->userProfile->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-medium text-gray-900">{{ $appointment->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Số điện thoại</p>
                        <p class="font-medium text-gray-900">{{ $appointment->user->userProfile->phone ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Địa chỉ khách hàng</p>
                        <p class="font-medium text-gray-900">
                            @php
                                $defaultAddress = $appointment->user->addresses()->where('is_default', true)->first();
                            @endphp
                            {{ $defaultAddress ? $defaultAddress->address . ', ' . $defaultAddress->city : 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Appointment Details --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-calendar-check text-blue-600 mr-2"></i>
                    Chi tiết lịch hẹn
                </h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Dịch vụ</p>
                        <p class="font-medium text-gray-900">{{ $appointment->service->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Showroom</p>
                        <p class="font-medium text-gray-900">{{ $appointment->showroom->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Ngày hẹn</p>
                        <p class="font-medium text-gray-900">{{ $appointment->appointment_date ? $appointment->appointment_date->format('d/m/Y') : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Giờ hẹn</p>
                        <p class="font-medium text-gray-900">{{ $appointment->appointment_time ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Chi phí dự kiến</p>
                        <p class="font-medium text-gray-900">{{ $appointment->estimated_cost ? number_format($appointment->estimated_cost, 0, ',', '.') . 'đ' : '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- Vehicle Information --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-car text-blue-600 mr-2"></i>
                    Thông tin xe
                </h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Biển số xe</p>
                        <p class="font-medium text-gray-900">{{ $appointment->vehicle_registration ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Số km hiện tại</p>
                        <p class="font-medium text-gray-900">{{ $appointment->current_mileage ? number_format($appointment->current_mileage) . ' km' : 'N/A' }}</p>
                    </div>
                    @if($appointment->carVariant)
                    <div class="col-span-2">
                        <p class="text-sm text-gray-500">Dòng xe</p>
                        <p class="font-medium text-gray-900">
                            {{ $appointment->carVariant->carModel->carBrand->name ?? '' }} 
                            {{ $appointment->carVariant->carModel->name ?? '' }} 
                            {{ $appointment->carVariant->name ?? '' }}
                        </p>
                    </div>
                    @endif
                    <div class="col-span-2">
                        <p class="text-sm text-gray-500">Công việc bảo hành</p>
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ $appointment->is_warranty_work ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $appointment->is_warranty_work ? 'Có' : 'Không' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Service Details --}}
            @if($appointment->requested_services || $appointment->service_description)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-list-check text-blue-600 mr-2"></i>
                    Yêu cầu dịch vụ
                </h2>
                @if($appointment->requested_services)
                <div class="mb-4">
                    <p class="text-sm text-gray-500 mb-2">Dịch vụ yêu cầu</p>
                    <div class="text-sm text-gray-900 whitespace-pre-line">{{ $appointment->requested_services }}</div>
                </div>
                @endif
                @if($appointment->service_description)
                <div>
                    <p class="text-sm text-gray-500 mb-2">Mô tả chi tiết</p>
                    <div class="text-sm text-gray-900 whitespace-pre-line">{{ $appointment->service_description }}</div>
                </div>
                @endif
            </div>
            @endif

            {{-- Feedback --}}
            @if($appointment->status == 'completed' && ($appointment->satisfaction_rating || $appointment->feedback))
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-star text-yellow-500 mr-2"></i>
                    Đánh giá của khách hàng
                </h2>
                @if($appointment->satisfaction_rating)
                <div class="mb-4">
                    <p class="text-sm text-gray-500 mb-2">Đánh giá</p>
                    <div class="flex items-center">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= $appointment->satisfaction_rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                        @endfor
                        <span class="ml-2 text-sm text-gray-600">({{ $appointment->satisfaction_rating }}/5)</span>
                    </div>
                </div>
                @endif
                @if($appointment->feedback)
                <div>
                    <p class="text-sm text-gray-500 mb-2">Nhận xét</p>
                    <div class="text-sm text-gray-900 whitespace-pre-line">{{ $appointment->feedback }}</div>
                </div>
                @endif
            </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Quick Actions --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Thao tác</h3>
                <div class="space-y-2">
                    @if($appointment->status == 'scheduled')
                        <button type="button" 
                                class="w-full btn btn-success text-left">
                            <i class="fas fa-check-circle mr-2"></i>
                            Xác nhận lịch hẹn
                        </button>
                    @endif
                    
                    @if($appointment->status == 'confirmed')
                        <button type="button"
                                class="w-full btn btn-primary text-left">
                            <i class="fas fa-play-circle mr-2"></i>
                            Bắt đầu thực hiện
                        </button>
                    @endif
                    
                    @if($appointment->status == 'in_progress')
                        <button type="button"
                                class="w-full btn btn-success text-left">
                            <i class="fas fa-check-double mr-2"></i>
                            Hoàn thành
                        </button>
                    @endif
                    
                    @if(in_array($appointment->status, ['scheduled', 'confirmed']))
                        <button type="button"
                                class="w-full btn btn-danger text-left">
                            <i class="fas fa-ban mr-2"></i>
                            Hủy lịch hẹn
                        </button>
                    @endif
                    
                    {{-- Message for completed/cancelled status --}}
                    @if($appointment->status == 'completed')
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                            <i class="fas fa-check-circle text-green-600 text-2xl mb-2"></i>
                            <p class="text-sm text-green-800 font-medium">Lịch hẹn đã hoàn thành</p>
                            <p class="text-xs text-green-600 mt-1">Không có thao tác khả dụng</p>
                        </div>
                    @endif
                    
                    @if($appointment->status == 'cancelled')
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                            <i class="fas fa-times-circle text-red-600 text-2xl mb-2"></i>
                            <p class="text-sm text-red-800 font-medium">Lịch hẹn đã bị hủy</p>
                            <p class="text-xs text-red-600 mt-1">Không có thao tác khả dụng</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Timeline --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Thời gian</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-500">Ngày tạo</p>
                        <p class="text-sm font-medium text-gray-900">{{ $appointment->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div id="updatedAtContainer">
                        <p class="text-xs text-gray-500">Cập nhật lần cuối</p>
                        <p class="text-sm font-medium text-gray-900" id="updatedAtValue">{{ $appointment->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @if($appointment->completed_at)
                    <div>
                        <p class="text-xs text-gray-500">Ngày hoàn thành</p>
                        <p class="text-sm font-medium text-gray-900">{{ $appointment->completed_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @endif
                    @if($appointment->cancelled_at)
                    <div>
                        <p class="text-xs text-gray-500">Ngày hủy</p>
                        <p class="text-sm font-medium text-gray-900">{{ $appointment->cancelled_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modals --}}
{{-- Confirm Modal --}}
<div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
        <div class="flex items-center mb-4">
            <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-check-circle text-green-600"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-900">Xác nhận lịch hẹn</h3>
            </div>
        </div>
        <div class="mb-6">
            <p class="text-gray-600">Bạn có chắc chắn muốn xác nhận lịch hẹn này?</p>
        </div>
        <div class="flex space-x-3">
            <button type="button" onclick="closeConfirmModal()" 
                    class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors">
                Hủy
            </button>
            <button type="button" id="confirmModalButton"
                    class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                <span id="confirmModalBtnText">Xác nhận</span>
            </button>
        </div>
    </div>
</div>

{{-- Status Update Modal (Start/Complete) --}}
<div id="statusModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
        <div class="flex items-center mb-4">
            <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center" id="statusModalIconWrapper">
                <i id="statusModalIcon"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-900" id="statusModalTitle"></h3>
            </div>
        </div>
        <div class="mb-6">
            <p class="text-gray-600" id="statusModalMessage"></p>
        </div>
        <div class="flex space-x-3">
            <button type="button" onclick="closeStatusModal()" 
                    class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors">
                Hủy
            </button>
            <button type="button" id="statusModalButton"
                    class="flex-1 px-4 py-2 text-white rounded-lg font-medium transition-colors">
                <span id="statusModalBtnText"></span>
            </button>
        </div>
    </div>
</div>

{{-- Cancel Modal --}}
<div id="cancelModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
        <div class="flex items-center mb-4">
            <div class="flex-shrink-0 w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                <i class="fas fa-ban text-orange-600"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-900">Hủy lịch hẹn</h3>
            </div>
        </div>
        <div class="mb-6">
            <p class="text-gray-600">Bạn có chắc chắn muốn hủy lịch hẹn này?</p>
        </div>
        <div class="flex space-x-3">
            <button type="button" onclick="closeCancelModal()" 
                    class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors">
                Không
            </button>
            <button type="button" id="cancelModalButton"
                    class="flex-1 bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                <span id="cancelModalBtnText">Hủy lịch hẹn</span>
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
const appointmentId = {{ $appointment->id }};
let currentStatus = '{{ $appointment->status }}';

// Confirm button handler
document.querySelectorAll('.btn-success').forEach(btn => {
    if (btn.textContent.includes('Xác nhận')) {
        btn.addEventListener('click', function() {
            document.getElementById('confirmModal').classList.remove('hidden');
            document.getElementById('confirmModalButton').onclick = () => executeConfirm();
        });
    }
});

// Start button handler
document.querySelectorAll('.btn-primary').forEach(btn => {
    if (btn.textContent.includes('Bắt đầu')) {
        btn.addEventListener('click', function() {
            showStatusModal({
                title: 'Bắt đầu thực hiện dịch vụ',
                message: 'Bạn có chắc chắn muốn bắt đầu thực hiện dịch vụ cho lịch hẹn này?',
                icon: 'fas fa-play-circle text-purple-600',
                iconBg: 'bg-purple-100',
                buttonClass: 'bg-purple-600 hover:bg-purple-700',
                buttonText: 'Bắt đầu',
                action: () => executeStatusUpdate('in_progress', 'Đã bắt đầu thực hiện dịch vụ!')
            });
        });
    }
});

// Complete button handler  
document.querySelectorAll('.btn-success').forEach(btn => {
    if (btn.textContent.includes('Hoàn thành')) {
        btn.addEventListener('click', function() {
            showStatusModal({
                title: 'Hoàn thành dịch vụ',
                message: 'Bạn có chắc chắn đã hoàn thành dịch vụ cho lịch hẹn này?',
                icon: 'fas fa-check-double text-green-600',
                iconBg: 'bg-green-100',
                buttonClass: 'bg-green-600 hover:bg-green-700',
                buttonText: 'Hoàn thành',
                action: () => executeStatusUpdate('completed', 'Đã hoàn thành dịch vụ!')
            });
        });
    }
});

// Cancel button handler
document.querySelectorAll('.btn-danger').forEach(btn => {
    if (btn.textContent.includes('Hủy lịch')) {
        btn.addEventListener('click', function() {
            document.getElementById('cancelModal').classList.remove('hidden');
            document.getElementById('cancelModalButton').onclick = () => executeCancel();
        });
    }
});

function closeConfirmModal() {
    document.getElementById('confirmModal').classList.add('hidden');
}

function closeStatusModal() {
    document.getElementById('statusModal').classList.add('hidden');
}

function closeCancelModal() {
    document.getElementById('cancelModal').classList.add('hidden');
}

function showStatusModal(config) {
    document.getElementById('statusModalTitle').textContent = config.title;
    document.getElementById('statusModalMessage').textContent = config.message;
    document.getElementById('statusModalIcon').className = config.icon;
    document.getElementById('statusModalIconWrapper').className = `flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center ${config.iconBg}`;
    document.getElementById('statusModalButton').className = `flex-1 px-4 py-2 text-white rounded-lg font-medium transition-colors ${config.buttonClass}`;
    document.getElementById('statusModalBtnText').textContent = config.buttonText;
    document.getElementById('statusModal').classList.remove('hidden');
    
    document.getElementById('statusModalButton').onclick = config.action;
}

async function executeConfirm() {
    const button = document.getElementById('confirmModalButton');
    const btnText = document.getElementById('confirmModalBtnText');
    const originalText = btnText.textContent;
    
    btnText.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
    button.disabled = true;
    
    try {
        const response = await fetch(`/admin/service-appointments/${appointmentId}/confirm`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        const data = await response.json();
        
        if (data.success) {
            closeConfirmModal();
            updateUIAfterStatusChange('confirmed');
            if (window.showMessage) window.showMessage(data.message || 'Đã xác nhận lịch hẹn!', 'success');
        } else {
            throw new Error(data.message || 'Có lỗi xảy ra');
        }
    } catch (error) {
        console.error('Confirm error:', error);
        if (window.showMessage) window.showMessage(error.message || 'Có lỗi khi xác nhận', 'error');
    } finally {
        btnText.textContent = originalText;
        button.disabled = false;
    }
}

async function executeStatusUpdate(newStatus, successMessage) {
    const button = document.getElementById('statusModalButton');
    const btnText = document.getElementById('statusModalBtnText');
    const originalText = btnText.textContent;
    
    btnText.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
    button.disabled = true;
    
    try {
        const response = await fetch(`/admin/service-appointments/${appointmentId}/update-status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: newStatus })
        });
        
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        const data = await response.json();
        
        if (data.success) {
            closeStatusModal();
            updateUIAfterStatusChange(newStatus);
            if (window.showMessage) window.showMessage(data.message || successMessage, 'success');
        } else {
            throw new Error(data.message || 'Có lỗi xảy ra');
        }
    } catch (error) {
        console.error('Status update error:', error);
        if (window.showMessage) window.showMessage(error.message || 'Có lỗi khi cập nhật trạng thái', 'error');
    } finally {
        btnText.textContent = originalText;
        button.disabled = false;
    }
}

async function executeCancel() {
    const button = document.getElementById('cancelModalButton');
    const btnText = document.getElementById('cancelModalBtnText');
    const originalText = btnText.textContent;
    
    btnText.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
    button.disabled = true;
    
    try {
        const response = await fetch(`/admin/service-appointments/${appointmentId}/cancel`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        const data = await response.json();
        
        if (data.success) {
            closeCancelModal();
            updateUIAfterStatusChange('cancelled');
            if (window.showMessage) window.showMessage(data.message || 'Đã hủy lịch hẹn!', 'success');
        } else {
            throw new Error(data.message || 'Có lỗi xảy ra');
        }
    } catch (error) {
        console.error('Cancel error:', error);
        if (window.showMessage) window.showMessage(error.message || 'Có lỗi khi hủy', 'error');
    } finally {
        btnText.textContent = originalText;
        button.disabled = false;
    }
}

function updateUIAfterStatusChange(newStatus) {
    currentStatus = newStatus;
    
    // Update badge
    const statusBadges = {
        'scheduled': '<span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-blue-100 text-blue-800"><i class="fas fa-calendar mr-2"></i>Đã đặt lịch</span>',
        'confirmed': '<span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800"><i class="fas fa-check-circle mr-2"></i>Đã xác nhận</span>',
        'in_progress': '<span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-purple-100 text-purple-800"><i class="fas fa-cog mr-2"></i>Đang thực hiện</span>',
        'completed': '<span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800"><i class="fas fa-flag-checkered mr-2"></i>Hoàn thành</span>',
        'cancelled': '<span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-red-100 text-red-800"><i class="fas fa-times-circle mr-2"></i>Đã hủy</span>'
    };
    
    // Find and update badge in header by ID
    const badgeContainer = document.getElementById('statusBadgeContainer');
    if (badgeContainer && statusBadges[newStatus]) {
        badgeContainer.innerHTML = statusBadges[newStatus];
    }
    
    // Update "Cập nhật lần cuối" timestamp
    const updatedAtValue = document.getElementById('updatedAtValue');
    if (updatedAtValue) {
        const now = new Date();
        const formattedDate = now.toLocaleDateString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric' }) + ' ' + 
                             now.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit', hour12: false });
        updatedAtValue.textContent = formattedDate;
    }
    
    // Update actions panel
    const actionsDiv = document.querySelector('.space-y-2');
    if (actionsDiv) {
        actionsDiv.innerHTML = getActionButtonsHTML(newStatus);
        
        // Re-attach event listeners
        attachEventListeners();
    }
}

function getActionButtonsHTML(status) {
    let html = '';
    
    if (status === 'scheduled') {
        html = `
            <button type="button" class="w-full btn btn-success text-left">
                <i class="fas fa-check-circle mr-2"></i>
                Xác nhận lịch hẹn
            </button>
            <button type="button" class="w-full btn btn-danger text-left">
                <i class="fas fa-ban mr-2"></i>
                Hủy lịch hẹn
            </button>
        `;
    } else if (status === 'confirmed') {
        html = `
            <button type="button" class="w-full btn btn-primary text-left">
                <i class="fas fa-play-circle mr-2"></i>
                Bắt đầu thực hiện
            </button>
            <button type="button" class="w-full btn btn-danger text-left">
                <i class="fas fa-ban mr-2"></i>
                Hủy lịch hẹn
            </button>
        `;
    } else if (status === 'in_progress') {
        html = `
            <button type="button" class="w-full btn btn-success text-left">
                <i class="fas fa-check-double mr-2"></i>
                Hoàn thành
            </button>
        `;
    } else if (status === 'completed') {
        html = `
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                <i class="fas fa-check-circle text-green-600 text-2xl mb-2"></i>
                <p class="text-sm text-green-800 font-medium">Lịch hẹn đã hoàn thành</p>
                <p class="text-xs text-green-600 mt-1">Không có thao tác khả dụng</p>
            </div>
        `;
    } else if (status === 'cancelled') {
        html = `
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                <i class="fas fa-times-circle text-red-600 text-2xl mb-2"></i>
                <p class="text-sm text-red-800 font-medium">Lịch hẹn đã bị hủy</p>
                <p class="text-xs text-red-600 mt-1">Không có thao tác khả dụng</p>
            </div>
        `;
    }
    
    return html;
}

function attachEventListeners() {
    // Re-attach all event listeners after DOM update
    document.querySelectorAll('.btn-success').forEach(btn => {
        if (btn.textContent.includes('Xác nhận')) {
            btn.addEventListener('click', function() {
                document.getElementById('confirmModal').classList.remove('hidden');
                document.getElementById('confirmModalButton').onclick = () => executeConfirm();
            });
        }
        if (btn.textContent.includes('Hoàn thành')) {
            btn.addEventListener('click', function() {
                showStatusModal({
                    title: 'Hoàn thành dịch vụ',
                    message: 'Bạn có chắc chắn đã hoàn thành dịch vụ cho lịch hẹn này?',
                    icon: 'fas fa-check-double text-green-600',
                    iconBg: 'bg-green-100',
                    buttonClass: 'bg-green-600 hover:bg-green-700',
                    buttonText: 'Hoàn thành',
                    action: () => executeStatusUpdate('completed', 'Đã hoàn thành dịch vụ!')
                });
            });
        }
    });
    
    document.querySelectorAll('.btn-primary').forEach(btn => {
        if (btn.textContent.includes('Bắt đầu')) {
            btn.addEventListener('click', function() {
                showStatusModal({
                    title: 'Bắt đầu thực hiện dịch vụ',
                    message: 'Bạn có chắc chắn muốn bắt đầu thực hiện dịch vụ cho lịch hẹn này?',
                    icon: 'fas fa-play-circle text-purple-600',
                    iconBg: 'bg-purple-100',
                    buttonClass: 'bg-purple-600 hover:bg-purple-700',
                    buttonText: 'Bắt đầu',
                    action: () => executeStatusUpdate('in_progress', 'Đã bắt đầu thực hiện dịch vụ!')
                });
            });
        }
    });
    
    document.querySelectorAll('.btn-danger').forEach(btn => {
        if (btn.textContent.includes('Hủy lịch')) {
            btn.addEventListener('click', function() {
                document.getElementById('cancelModal').classList.remove('hidden');
                document.getElementById('cancelModalButton').onclick = () => executeCancel();
            });
        }
    });
}
</script>
@endpush

@endsection
