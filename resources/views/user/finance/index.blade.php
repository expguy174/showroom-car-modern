@extends('layouts.app')
@section('title', 'Tư vấn tài chính - AutoLux')
@section('content')

<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 text-white overflow-hidden">
    <div class="absolute inset-0 bg-black/20"></div>
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.1"%3E%3Ccircle cx="30" cy="30" r="2"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-30"></div>
    
    <div class="relative container mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center bg-white/10 backdrop-blur-sm rounded-full px-6 py-3 mb-8">
                <i class="fas fa-chart-line text-yellow-400 mr-3 text-xl"></i>
                <span class="text-sm font-medium">Giải pháp tài chính thông minh</span>
            </div>
            
            <h1 class="text-4xl lg:text-6xl font-bold mb-6 leading-tight">
                Tài chính xe hơi
                <span class="text-yellow-400 block">Đơn giản & Linh hoạt</span>
            </h1>
            
            <p class="text-xl lg:text-2xl text-blue-100 mb-10 max-w-3xl mx-auto leading-relaxed">
                Lựa chọn gói vay phù hợp với khả năng tài chính của bạn. 
                Hỗ trợ vay lên đến 90% giá trị xe với lãi suất cạnh tranh.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('finance.calculator') }}" 
                   class="inline-flex items-center bg-yellow-400 text-blue-900 px-8 py-4 rounded-xl font-semibold text-lg hover:bg-yellow-300 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-calculator mr-3"></i>
                    Tính toán ngay
                </a>
                <a href="{{ route('finance.requirements') }}" 
                   class="inline-flex items-center bg-white/20 backdrop-blur-sm text-white px-8 py-4 rounded-xl font-semibold text-lg hover:bg-white/30 transition-all duration-300 border border-white/30">
                    <i class="fas fa-clipboard-list mr-3"></i>
                    Xem điều kiện
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Finance Options Section -->
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                Gói vay tài chính
                </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Chọn gói vay phù hợp nhất với nhu cầu và khả năng tài chính của bạn
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($financeOptions as $option)
            <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-university text-white text-xl"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-500">{{ $option->bank_name }}</span>
                    </div>
                    
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $option->name }}</h3>
                    <p class="text-gray-600 mb-4 line-clamp-2">{{ $option->description }}</p>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Lãi suất:</span>
                            <span class="text-lg font-bold text-green-600">{{ number_format($option->interest_rate, 1) }}%/năm</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Trả trước tối thiểu:</span>
                            <span class="font-semibold text-gray-700">{{ $option->min_down_payment }}%</span>
            </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Thời hạn:</span>
                            <span class="font-semibold text-gray-700">{{ $option->min_tenure }}-{{ $option->max_tenure }} tháng</span>
                            </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Hạn mức:</span>
                            <span class="font-semibold text-gray-700">{{ number_format($option->max_loan_amount / 1000000, 0) }}M VNĐ</span>
                            </div>
                            </div>
                    
                    <button class="finance-detail-btn w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 rounded-xl font-semibold hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105"
                            data-finance-option='@json($option)'>
                        Tìm hiểu thêm
                            </button>
                </div>
            </div>
            @endforeach
        </div>
                    </div>
</section>

<!-- Calculator Preview Section -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div>
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-6">
                        Tính toán khoản vay
                    </h2>
                    <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                        Sử dụng công cụ tính toán thông minh để xác định khoản trả góp phù hợp. 
                        Chỉ cần nhập thông tin cơ bản, chúng tôi sẽ giúp bạn tính toán chi tiết.
                    </p>
                    
                    <div class="space-y-4 mb-8">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-check text-green-600 text-sm"></i>
                            </div>
                            <span class="text-gray-700">Tính toán chính xác với lãi suất thực tế</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-check text-green-600 text-sm"></i>
                            </div>
                            <span class="text-gray-700">So sánh các phương án trả góp khác nhau</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-check text-green-600 text-sm"></i>
                            </div>
                            <span class="text-gray-700">Kết quả chi tiết và dễ hiểu</span>
                        </div>
                    </div>
                    
                    <a href="{{ route('finance.calculator') }}" 
                       class="inline-flex items-center bg-gradient-to-r from-green-500 to-emerald-600 text-white px-8 py-4 rounded-xl font-semibold text-lg hover:from-green-600 hover:to-emerald-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-calculator mr-3"></i>
                        Sử dụng máy tính
                    </a>
                </div>
                
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-8 shadow-lg">
                    <div class="bg-white rounded-xl p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Ví dụ tính toán</h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Giá xe:</span>
                                <span class="font-semibold">800,000,000 VNĐ</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Trả trước 20%:</span>
                                <span class="font-semibold">160,000,000 VNĐ</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Số tiền vay:</span>
                                <span class="font-semibold">640,000,000 VNĐ</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Lãi suất 7.5%:</span>
                                <span class="font-semibold">7.5%/năm</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Thời hạn 60 tháng:</span>
                                <span class="font-semibold">60 tháng</span>
                            </div>
                            <hr class="my-3">
                            <div class="flex justify-between text-lg font-bold text-green-600">
                                <span>Trả góp hàng tháng:</span>
                                <span>12,850,000 VNĐ</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-blue-900 to-indigo-900 text-white">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl lg:text-4xl font-bold mb-6">
            Sẵn sàng mua xe mơ ước?
        </h2>
        <p class="text-xl text-blue-100 mb-10 max-w-3xl mx-auto">
            Đội ngũ tư vấn tài chính chuyên nghiệp của chúng tôi sẽ giúp bạn 
            tìm ra giải pháp tài chính phù hợp nhất.
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('finance.calculator') }}" 
               class="inline-flex items-center bg-yellow-400 text-blue-900 px-8 py-4 rounded-xl font-semibold text-lg hover:bg-yellow-300 transition-all duration-300 transform hover:scale-105">
                <i class="fas fa-calculator mr-3"></i>
                Tính toán ngay
            </a>
            <a href="{{ route('contact') }}" 
               class="inline-flex items-center bg-white/20 backdrop-blur-sm text-white px-8 py-4 rounded-xl font-semibold text-lg hover:bg-white/30 transition-all duration-300 border border-white/30">
                <i class="fas fa-phone mr-3"></i>
                Liên hệ tư vấn
            </a>
        </div>
    </div>
</section>

<!-- Finance Option Detail Modal -->
<div id="financeDetailModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-university text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900" id="modalTitle">Gói vay</h3>
                            <p class="text-sm text-gray-500" id="modalBank">Ngân hàng</p>
                        </div>
                    </div>
                    <button id="closeModal" class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gray-200 transition-colors duration-200">
                        <i class="fas fa-times text-gray-600"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Content -->
            <div class="p-6">
                <div class="mb-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-3">Mô tả gói vay</h4>
                    <p class="text-gray-600 leading-relaxed" id="modalDescription"></p>
                </div>

                <!-- Key Features -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-blue-50 rounded-xl p-4">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-percentage text-white text-sm"></i>
                            </div>
                            <span class="font-semibold text-gray-900">Lãi suất</span>
                        </div>
                        <div class="text-2xl font-bold text-blue-600" id="modalInterestRate"></div>
                        <div class="text-sm text-gray-600">%/năm</div>
                    </div>

                    <div class="bg-green-50 rounded-xl p-4">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-calendar-alt text-white text-sm"></i>
                            </div>
                            <span class="font-semibold text-gray-900">Thời hạn</span>
                        </div>
                        <div class="text-2xl font-bold text-green-600" id="modalTenure"></div>
                        <div class="text-sm text-gray-600">Tháng</div>
                    </div>

                    <div class="bg-purple-50 rounded-xl p-4">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-money-bill-wave text-white text-sm"></i>
                            </div>
                            <span class="font-semibold text-gray-900">Trả trước tối thiểu</span>
                        </div>
                        <div class="text-2xl font-bold text-purple-600" id="modalDownPayment"></div>
                        <div class="text-sm text-gray-600">% giá trị xe</div>
                    </div>

                    <div class="bg-orange-50 rounded-xl p-4">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-coins text-white text-sm"></i>
                            </div>
                            <span class="font-semibold text-gray-900">Hạn mức vay</span>
                        </div>
                        <div class="text-2xl font-bold text-orange-600" id="modalLoanAmount"></div>
                        <div class="text-sm text-gray-600">VNĐ</div>
                    </div>
                </div>

                <!-- Requirements -->
                <div class="mb-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-3">Yêu cầu hồ sơ</h4>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-gray-700 text-sm leading-relaxed" id="modalRequirements"></p>
                    </div>
                </div>

                <!-- Processing Fee -->
                <div class="mb-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-3">Phí xử lý hồ sơ</h4>
                    <div class="bg-yellow-50 rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-700">Phí xử lý:</span>
                            <span class="text-xl font-bold text-yellow-600" id="modalProcessingFee"></span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <a id="calcWithOptionBtn"
                       href="#"
                       class="flex-1 bg-gradient-to-r from-green-600 to-emerald-600 text-white py-3 px-6 rounded-xl font-semibold text-center hover:from-green-700 hover:to-emerald-700 transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-calculator mr-2"></i>
                        Tính toán với gói này
                    </a>
                    <button id="consultNowBtn" type="button"
                            class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 px-6 rounded-xl font-semibold hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-phone mr-2"></i>
                        Tư vấn ngay
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Finance Option Detail Modal
    const modal = document.getElementById('financeDetailModal');
    const closeBtn = document.getElementById('closeModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalBank = document.getElementById('modalBank');
    const modalDescription = document.getElementById('modalDescription');
    const financeDetailBtns = document.querySelectorAll('.finance-detail-btn');
    const calcWithOptionBtn = document.getElementById('calcWithOptionBtn');
    const consultNowBtn = document.getElementById('consultNowBtn');

    // Open modal when finance detail button is clicked
    financeDetailBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const financeOption = JSON.parse(this.dataset.financeOption);
            populateModal(financeOption);
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // Set dynamic URLs for action buttons
            calcWithOptionBtn.href = "{{ route('finance.calculator') }}?option_id=" + financeOption.id;
            consultNowBtn.href = "{{ route('contact') }}";
        });
    });

    // Close modal when close button is clicked
    closeBtn.addEventListener('click', function() {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    });

    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    });

    // Populate modal with finance option data
    function populateModal(option) {
        document.getElementById('modalTitle').textContent = option.name;
        document.getElementById('modalBank').textContent = option.bank_name;
        document.getElementById('modalDescription').textContent = option.description;
        document.getElementById('modalInterestRate').textContent = option.interest_rate + '%';
        document.getElementById('modalTenure').textContent = option.min_tenure + '-' + option.max_tenure;
        document.getElementById('modalDownPayment').textContent = option.min_down_payment + '%';
        document.getElementById('modalLoanAmount').textContent = formatCurrency(option.max_loan_amount);
        document.getElementById('modalRequirements').textContent = option.requirements || 'Không có yêu cầu đặc biệt';
        document.getElementById('modalProcessingFee').textContent = formatCurrency(option.processing_fee);

        // Wire up action buttons
        const calcBtn = document.getElementById('calcWithOptionBtn');
        const consultBtn = document.getElementById('consultNowBtn');

        const baseCalcUrl = '{{ route("finance.calculator") }}';
        calcBtn.href = baseCalcUrl + '?option_id=' + option.id;

        consultBtn.onclick = function() {
            const baseContactUrl = '{{ route("contact") }}';
            const message = `Tôi quan tâm đến gói vay ${option.name} của ${option.bank_name} với thông tin sau:

• Tên gói vay: ${option.name}
• Ngân hàng: ${option.bank_name}
• Lãi suất: ${option.interest_rate}%/năm
• Thời hạn: ${option.min_tenure}-${option.max_tenure} tháng
• Trả trước tối thiểu: ${option.min_down_payment}%
• Hạn mức vay: ${formatCurrency(option.max_loan_amount)}
• Phí xử lý: ${formatCurrency(option.processing_fee)}
• Mô tả: ${option.description}

Vui lòng liên hệ tư vấn chi tiết về gói vay này.`;
            
            // Chuyển đến trang contact với subject=finance và message chi tiết
            const contactUrl = baseContactUrl + '?subject=finance&message=' + encodeURIComponent(message) + '#contact-form';
            window.location.href = contactUrl;
        };
    }
});

// Format currency helper function
function formatCurrency(amount) {
    if (amount >= 1000000) {
        return (amount / 1000000).toFixed(1) + 'M VNĐ';
    } else if (amount >= 1000) {
        return (amount / 1000).toFixed(0) + 'K VNĐ';
    } else {
        return amount.toLocaleString() + ' VNĐ';
    }
}
</script>
@endpush