@extends('layouts.app')
@section('title', 'Tính trả góp - AutoLux')
@section('content')

<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-green-900 via-emerald-800 to-teal-900 text-white overflow-hidden">
    <div class="absolute inset-0 bg-black/20"></div>
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.1"%3E%3Ccircle cx="30" cy="30" r="2"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-30"></div>
    
    <div class="relative container mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center bg-white/10 backdrop-blur-sm rounded-full px-6 py-3 mb-8">
                <i class="fas fa-calculator text-emerald-400 mr-3 text-xl"></i>
                <span class="text-sm font-medium">Công cụ tính toán thông minh</span>
            </div>
            
            <h1 class="text-4xl lg:text-5xl font-bold mb-6 leading-tight">
                Tính toán khoản trả góp
            </h1>
            
            <p class="text-xl text-emerald-100 max-w-3xl mx-auto leading-relaxed">
                Sử dụng máy tính tài chính để xác định khoản vay và trả góp phù hợp nhất với khả năng tài chính của bạn
            </p>
        </div>
    </div>
</section>

<!-- Calculator Section -->
<section class="py-16 bg-gray-50">
  <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                
                <!-- Calculator Form -->
                <div class="xl:col-span-2">
                    <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                        <div class="flex items-center mb-8">
                            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-calculator text-white text-xl"></i>
          </div>
          <div>
                                <h2 class="text-2xl font-bold text-gray-900">Thông tin vay</h2>
                                <p class="text-gray-600">Nhập thông tin để tính toán khoản trả góp</p>
                            </div>
                        </div>

                        <form id="calc-form" class="space-y-6">
                            <!-- Car Selection -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-car mr-2 text-green-600"></i>
                                        Chọn xe (tùy chọn)
                                    </label>
                                    <select name="car_variant_id" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-300">
                                        <option value="">Chọn xe cụ thể</option>
                                        @foreach($carVariants as $car)
                                        <option value="{{ $car->id }}" data-price="{{ $car->current_price }}">
                                            {{ $car->carModel->carBrand->name }} {{ $car->carModel->name }} {{ $car->name }} - {{ number_format($car->current_price, 0, ',', '.') }}đ
                                        </option>
                                        @endforeach
                                    </select>
          </div>
                                
          <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-tag mr-2 text-green-600"></i>
                                        Giá xe (VNĐ)
                                    </label>
                                    <input type="number" name="car_price" id="car_price" 
                                           class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-300" 
                                           placeholder="Nhập giá xe">
                                </div>
          </div>

                            <!-- Down Payment -->
          <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-money-bill-wave mr-2 text-green-600"></i>
                                    Số tiền trả trước
                                </label>
                                <div class="space-y-3">
                                    <input type="number" name="down_payment" id="down_payment" 
                                           class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-300" 
                                           placeholder="Nhập số tiền trả trước">
                                    <div class="flex items-center space-x-4">
                                        <span class="text-sm text-gray-500">Hoặc chọn tỷ lệ:</span>
                                        <div class="flex space-x-2">
                                            <button type="button" class="down-payment-btn px-3 py-1 text-sm border border-gray-300 rounded-lg hover:bg-gray-50" data-percent="10">10%</button>
                                            <button type="button" class="down-payment-btn px-3 py-1 text-sm border border-gray-300 rounded-lg hover:bg-gray-50" data-percent="20">20%</button>
                                            <button type="button" class="down-payment-btn px-3 py-1 text-sm border border-gray-300 rounded-lg hover:bg-gray-50" data-percent="30">30%</button>
                                            <button type="button" class="down-payment-btn px-3 py-1 text-sm border border-gray-300 rounded-lg hover:bg-gray-50" data-percent="50">50%</button>
                                        </div>
                                    </div>
                                </div>
          </div>

                            <!-- Loan Terms -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-calendar-alt mr-2 text-green-600"></i>
                                        Kỳ hạn vay (tháng)
                                    </label>
                                    <select name="loan_term" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-300">
                                        <option value="12">12 tháng</option>
                                        <option value="24">24 tháng</option>
                                        <option value="36">36 tháng</option>
                                        <option value="48">48 tháng</option>
                                        <option value="60" selected>60 tháng</option>
                                        <option value="72">72 tháng</option>
                                        <option value="84">84 tháng</option>
            </select>
          </div>
                                
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-percentage mr-2 text-green-600"></i>
                                        Lãi suất (%/năm)
                                    </label>
                                    <input type="number" name="interest_rate" 
                                           class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-300" 
                                           step="0.01" value="9.5" placeholder="Nhập lãi suất">
                                </div>
                            </div>

                            <!-- Loan Type -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-chart-line mr-2 text-green-600"></i>
                                    Kiểu trả góp
                                </label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <label class="flex items-center p-4 border border-gray-300 rounded-xl cursor-pointer hover:border-green-500 transition-all duration-300">
                                        <input type="radio" name="loan_type" value="fixed" checked class="mr-3 text-green-600 focus:ring-green-500">
                                        <div>
                                            <div class="font-semibold text-gray-900">Cố định</div>
                                            <div class="text-sm text-gray-600">Khoản trả góp cố định hàng tháng</div>
                                        </div>
                                    </label>
                                    <label class="flex items-center p-4 border border-gray-300 rounded-xl cursor-pointer hover:border-green-500 transition-all duration-300">
                                        <input type="radio" name="loan_type" value="reducing" class="mr-3 text-green-600 focus:ring-green-500">
                                        <div>
                                            <div class="font-semibold text-gray-900">Dư nợ giảm dần</div>
                                            <div class="text-sm text-gray-600">Khoản trả góp giảm dần theo thời gian</div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Calculate Button -->
                            <div class="pt-4">
                                <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-emerald-600 text-white py-4 rounded-xl font-semibold text-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                                    <i class="fas fa-calculator mr-3"></i>
                                    Tính toán ngay
                                </button>
          </div>
        </form>
                    </div>
                </div>

                <!-- Results Panel -->
                <div class="xl:col-span-1">
                    <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100 sticky top-8">
                        <div class="flex items-center mb-6">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-3">
                                <i class="fas fa-chart-pie text-white"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Kết quả tính toán</h3>
                        </div>

                        <div id="calc-result" class="hidden">
                            <div class="space-y-4">
                                <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-4 border border-green-200">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-green-600" id="monthly-payment">0 VNĐ</div>
                                        <div class="text-sm text-green-700">Trả góp hàng tháng</div>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                        <span class="text-gray-600">Giá xe:</span>
                                        <span class="font-semibold" id="result-car-price">0 VNĐ</span>
                                    </div>
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                        <span class="text-gray-600">Trả trước:</span>
                                        <span class="font-semibold" id="result-down-payment">0 VNĐ</span>
                                    </div>
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                        <span class="text-gray-600">Số tiền vay:</span>
                                        <span class="font-semibold" id="result-loan-amount">0 VNĐ</span>
                                    </div>
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                        <span class="text-gray-600">Tổng lãi phải trả:</span>
                                        <span class="font-semibold text-red-600" id="result-total-interest">0 VNĐ</span>
                                    </div>
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                        <span class="text-gray-600">Tổng số tiền:</span>
                                        <span class="font-semibold text-blue-600" id="result-total-amount">0 VNĐ</span>
                                    </div>
                                    <div class="flex justify-between items-center py-2">
                                        <span class="text-gray-600">Tỷ lệ trả trước:</span>
                                        <span class="font-semibold" id="result-down-payment-percent">0%</span>
                                    </div>
                                </div>

                                <div class="pt-4">
                                    <button type="button" 
                                            class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 rounded-xl font-semibold hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 inline-flex items-center justify-center"
                                            onclick="registerForConsultation()">
                                        <i class="fas fa-user-plus mr-2"></i>
                                        Đăng ký tư vấn
                                    </button>
                                </div>
                            </div>
            </div>

                        <div id="calc-placeholder" class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-calculator text-gray-400 text-2xl"></i>
            </div>
                            <p class="text-gray-500">Nhập thông tin và nhấn "Tính toán ngay" để xem kết quả</p>
            </div>
            </div>
            </div>
          </div>
        </div>
      </div>
</section>

<!-- Finance Options Section -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Gói vay tài chính</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Chọn gói vay phù hợp nhất với nhu cầu của bạn
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          @foreach($financeOptions as $opt)
            <div class="bg-gray-50 rounded-xl p-6 border border-gray-200 hover:border-green-300 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-university text-white"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-500">{{ $opt->bank_name }}</span>
                </div>
                
                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $opt->name }}</h3>
                <p class="text-gray-600 text-sm mb-4">{{ $opt->description }}</p>
                
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Lãi suất:</span>
                        <span class="font-semibold text-green-600">{{ number_format($opt->interest_rate, 1) }}%</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Trả trước tối thiểu:</span>
                        <span class="font-semibold">{{ $opt->min_down_payment }}%</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Thời hạn:</span>
                        <span class="font-semibold">{{ $opt->min_tenure }}-{{ $opt->max_tenure }} tháng</span>
      </div>
                </div>
            </div>
            @endforeach
    </div>
  </div>
</section>

@endsection

@push('scripts')
<script>
// Function to handle registration for consultation (global scope)
function registerForConsultation() {
    console.log('registerForConsultation called');
    const carPrice = document.getElementById('car_price').value || 'Chưa nhập';
    const downPayment = document.getElementById('down_payment').value || 'Chưa nhập';
    const interestRate = document.querySelector('input[name="interest_rate"]').value || 'Chưa nhập';
    const loanTerm = document.querySelector('select[name="loan_term"]').value || 'Chưa nhập';
    const loanType = document.querySelector('input[name="loan_type"]:checked').value || 'Chưa chọn';
    
    console.log('Form values:', { carPrice, downPayment, interestRate, loanTerm, loanType });
    
    // Calculate loan amount
    const carPriceNum = parseFloat(carPrice) || 0;
    const downPaymentNum = parseFloat(downPayment) || 0;
    const loanAmount = carPriceNum - downPaymentNum;
    
    // Get calculation results if available
    const monthlyPayment = document.getElementById('monthly-payment').textContent || 'Chưa tính toán';
    const totalAmount = document.getElementById('result-total-amount').textContent || 'Chưa tính toán';
    const totalInterest = document.getElementById('result-total-interest').textContent || 'Chưa tính toán';
    
    console.log('Calculation results:', { monthlyPayment, totalAmount, totalInterest });
    
    // Get selected car info if available
    const carSelect = document.querySelector('select[name="car_variant_id"]');
    const selectedCarOption = carSelect.options[carSelect.selectedIndex];
    const carInfo = selectedCarOption.value ? 
        selectedCarOption.textContent.split(' - ')[0].trim() : 'Chưa chọn xe cụ thể';
    
    const message = `Tôi quan tâm đến việc vay mua xe với thông tin sau:

• Thông tin xe: ${carInfo}
• Giá xe: ${carPrice} VNĐ
• Số tiền trả trước: ${downPayment} VNĐ
• Số tiền vay: ${loanAmount.toLocaleString()} VNĐ
• Lãi suất: ${interestRate}%/năm
• Thời hạn: ${loanTerm} tháng
• Kiểu trả góp: ${loanType === 'fixed' ? 'Cố định' : 'Dư nợ giảm dần'}

Kết quả tính toán:
• Trả góp hàng tháng: ${monthlyPayment}
• Tổng lãi suất: ${totalInterest}
• Tổng số tiền trả: ${totalAmount}

Vui lòng liên hệ tư vấn chi tiết về gói vay phù hợp và hỗ trợ thủ tục vay.`;

    console.log('Generated message:', message);
    
    const contactUrl = `{{ route('contact') }}?subject=finance&message=${encodeURIComponent(message)}&from=calculator#contact-form`;
    console.log('New URL:', contactUrl);
    
    window.location.href = contactUrl;
}

document.addEventListener('DOMContentLoaded', function() {
const form = document.getElementById('calc-form');
    const resultDiv = document.getElementById('calc-result');
    const placeholderDiv = document.getElementById('calc-placeholder');
    const carSelect = document.querySelector('select[name="car_variant_id"]');
    const carPriceInput = document.getElementById('car_price');
    const downPaymentInput = document.getElementById('down_payment');
    const downPaymentBtns = document.querySelectorAll('.down-payment-btn');
    const interestRateInput = document.querySelector('input[name="interest_rate"]');
    const loanTermSelect = document.querySelector('select[name="loan_term"]');

    // Prefill from selected option when coming from "Tính toán với gói này"
    @if($selectedOption)
    (function prefillFromSelectedOption() {
        const option = @json($selectedOption);
        
        if (option) {
            // Prefill interest rate
            interestRateInput.value = option.interest_rate;
            
            // Prefill loan term (use max_tenure if available, otherwise min_tenure)
            const targetTenure = option.max_tenure || option.min_tenure;
            if (targetTenure) {
                const opt = Array.from(loanTermSelect.options).find(o => parseInt(o.value, 10) === targetTenure);
                if (opt) {
                    loanTermSelect.value = String(targetTenure);
                } else {
                    // If exact match not found, find closest option
                    const closestOption = Array.from(loanTermSelect.options).reduce((closest, current) => {
                        const currentVal = parseInt(current.value, 10);
                        const closestVal = parseInt(closest.value, 10);
                        return Math.abs(currentVal - targetTenure) < Math.abs(closestVal - targetTenure) ? current : closest;
                    }, loanTermSelect.options[0]);
                    loanTermSelect.value = closestOption.value;
                }
            }
            
            // Show a notification that form has been prefilled
            showPrefillNotification(option.name, option.bank_name);
        }
    })();
    @endif

    // Show notification when form is prefilled
    function showPrefillNotification(optionName, bankName) {
        const message = `Đã điền sẵn thông tin gói vay: ${optionName} (${bankName})`;
        if (typeof window.showMessage === 'function') {
            window.showMessage(message, 'success');
        } else {
            // Fallback notification
            console.log(message);
        }
    }

    // Auto-fill car price when car is selected
    carSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value && selectedOption.dataset.price) {
            // Format price without decimals
            const price = parseFloat(selectedOption.dataset.price);
            carPriceInput.value = Math.round(price);
            updateDownPayment();
        }
    });

    // Auto-calculate down payment when car price changes
    carPriceInput.addEventListener('input', updateDownPayment);

    // Down payment percentage buttons
    downPaymentBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const percent = parseInt(this.dataset.percent);
            const carPrice = parseFloat(carPriceInput.value) || 0;
            const downPayment = (carPrice * percent) / 100;
            downPaymentInput.value = Math.round(downPayment);
        });
    });

    function updateDownPayment() {
        const carPrice = parseFloat(carPriceInput.value) || 0;
        const currentDownPayment = parseFloat(downPaymentInput.value) || 0;
        
        // If down payment is more than car price, reset it
        if (currentDownPayment > carPrice) {
            downPaymentInput.value = Math.round(carPrice * 0.2); // Default to 20%
        }
    }

    // Validation function
    function validateForm() {
        const carPrice = parseFloat(carPriceInput.value);
        const downPayment = parseFloat(downPaymentInput.value);
        const interestRate = parseFloat(interestRateInput.value);
        const loanTerm = parseInt(loanTermSelect.value);

        if (!carPrice || carPrice < 100000000) {
            showToast('Vui lòng nhập giá xe tối thiểu 100 triệu VNĐ', 'error');
            carPriceInput.focus();
            return false;
        }

        if (!downPayment || downPayment < 0) {
            showToast('Vui lòng nhập số tiền trả trước hợp lệ', 'error');
            downPaymentInput.focus();
            return false;
        }

        if (downPayment >= carPrice) {
            showToast('Số tiền trả trước phải nhỏ hơn giá xe', 'error');
            downPaymentInput.focus();
            return false;
        }

        if (!interestRate || interestRate < 0 || interestRate > 30) {
            showToast('Vui lòng nhập lãi suất từ 0% đến 30%', 'error');
            interestRateInput.focus();
            return false;
        }

        if (!loanTerm || loanTerm < 6 || loanTerm > 84) {
            showToast('Vui lòng chọn thời hạn vay từ 6 đến 84 tháng', 'error');
            loanTermSelect.focus();
            return false;
        }

        return true;
    }

    // Toast notification function - use global showMessage
    function showToast(message, type = 'success') {
        if (typeof window.showMessage === 'function') {
            window.showMessage(message, type);
        } else {
            // Fallback if global showMessage not available
            alert(message);
        }
    }

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validate form
        if (!validateForm()) {
            return;
        }
        
        const formData = new FormData(form);
        
        fetch('{{ route("finance.calculate-installment") }}', {
    method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(Object.fromEntries(formData))
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayResults(data.calculation);
            } else {
                if (typeof window.showMessage === 'function') {
                    window.showMessage(data.message, 'error');
                } else {
                    alert(data.message);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof window.showMessage === 'function') {
                window.showMessage('Có lỗi xảy ra khi tính toán. Vui lòng thử lại.', 'error');
            } else {
                alert('Có lỗi xảy ra khi tính toán. Vui lòng thử lại.');
            }
        });
    });

    function displayResults(calc) {
        document.getElementById('monthly-payment').textContent = calc.monthly_payment + ' VNĐ';
        document.getElementById('result-car-price').textContent = calc.car_price + ' VNĐ';
        document.getElementById('result-down-payment').textContent = calc.down_payment + ' VNĐ';
        document.getElementById('result-loan-amount').textContent = calc.loan_amount + ' VNĐ';
        document.getElementById('result-total-interest').textContent = calc.total_interest + ' VNĐ';
        document.getElementById('result-total-amount').textContent = calc.total_amount + ' VNĐ';
        document.getElementById('result-down-payment-percent').textContent = calc.down_payment_percentage + '%';

        resultDiv.classList.remove('hidden');
        placeholderDiv.classList.add('hidden');
    }

    // Apply button click
    // Button has been replaced with a link to contact page
    // No JavaScript needed anymore
});
</script>
@endpush
