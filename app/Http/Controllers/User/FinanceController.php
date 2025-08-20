<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CarVariant;
use App\Models\FinanceOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinanceController extends Controller
{
    /**
     * Display the main finance page
     */
    public function index()
    {
        $financeOptions = FinanceOption::where('is_active', 1)->get();
        
        $popularCars = CarVariant::with(['carModel.carBrand', 'images'])
            ->where('is_featured', 1)
            ->where('is_active', 1)
            ->take(6)
            ->get();

        return view('user.finance.index', compact('financeOptions', 'popularCars'));
    }

    /**
     * Calculate installment payment
     */
    public function calculateInstallment(Request $request)
    {
        $validated = $request->validate([
            'car_price' => 'required|numeric|min:1000000',
            'down_payment' => 'required|numeric|min:0',
            'loan_term' => 'required|integer|min:12|max:84',
            'interest_rate' => 'required|numeric|min:0|max:30',
            'loan_type' => 'required|in:fixed,reducing'
        ]);

        $carPrice = $validated['car_price'];
        $downPayment = $validated['down_payment'];
        $loanAmount = $carPrice - $downPayment;
        $loanTerm = $validated['loan_term'];
        $monthlyInterestRate = $validated['interest_rate'] / 100 / 12;
        $loanType = $validated['loan_type'];

        if ($downPayment >= $carPrice) {
            return response()->json([
                'success' => false,
                'message' => 'Số tiền trả trước phải nhỏ hơn giá xe'
            ]);
        }

        if ($loanAmount <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Số tiền vay phải lớn hơn 0'
            ]);
        }

        $monthlyPayment = $this->calculateMonthlyPayment($loanAmount, $monthlyInterestRate, $loanTerm, $loanType);
        $totalInterest = $this->calculateTotalInterest($monthlyPayment, $loanTerm, $loanAmount);
        $totalAmount = $monthlyPayment * $loanTerm;

        $calculation = [
            'car_price' => number_format($carPrice),
            'down_payment' => number_format($downPayment),
            'loan_amount' => number_format($loanAmount),
            'monthly_payment' => number_format($monthlyPayment),
            'total_interest' => number_format($totalInterest),
            'total_amount' => number_format($totalAmount),
            'loan_term' => $loanTerm,
            'interest_rate' => $validated['interest_rate'],
            'down_payment_percentage' => round(($downPayment / $carPrice) * 100, 1)
        ];

        return response()->json([
            'success' => true,
            'calculation' => $calculation
        ]);
    }

    /**
     * Calculate monthly payment based on loan type
     */
    private function calculateMonthlyPayment($principal, $monthlyRate, $term, $type)
    {
        if ($monthlyRate == 0) {
            return $principal / $term;
        }

        if ($type === 'fixed') {
            // Fixed monthly payment (principal + interest)
            $numerator = $principal * $monthlyRate * pow(1 + $monthlyRate, $term);
            $denominator = pow(1 + $monthlyRate, $term) - 1;
            return $numerator / $denominator;
        } else {
            // Reducing balance (principal decreases each month)
            $monthlyPrincipal = $principal / $term;
            $firstMonthInterest = $principal * $monthlyRate;
            $lastMonthInterest = $monthlyPrincipal * $monthlyRate;
            $averageInterest = ($firstMonthInterest + $lastMonthInterest) / 2;
            return $monthlyPrincipal + $averageInterest;
        }
    }

    /**
     * Calculate total interest paid
     */
    private function calculateTotalInterest($monthlyPayment, $term, $principal)
    {
        return ($monthlyPayment * $term) - $principal;
    }

    /**
     * Get available finance options
     */
    public function getFinanceOptions()
    {
        $options = FinanceOption::where('is_active', 1)
            ->orderBy('min_loan_amount')
            ->get()
            ->map(function ($option) {
                return [
                    'id' => $option->id,
                    'name' => $option->name,
                    'description' => $option->description,
                    'min_loan_amount' => $option->min_loan_amount,
                    'max_loan_amount' => $option->max_loan_amount,
                    'min_down_payment' => $option->min_down_payment,
                    'max_down_payment' => $option->max_down_payment,
                    'min_loan_term' => $option->min_loan_term,
                    'max_loan_term' => $option->max_loan_term,
                    'interest_rate' => $option->interest_rate,
                    'processing_fee' => $option->processing_fee,
                    'required_documents' => json_decode($option->required_documents, true),
                    'features' => json_decode($option->features, true)
                ];
            });

        return response()->json([
            'success' => true,
            'options' => $options
        ]);
    }

    /**
     * Apply for financing
     */
    public function applyForFinancing(Request $request)
    {
        // Tối giản showroom: không lưu hồ sơ vay, chỉ tạo lead liên hệ
        $validated = $request->validate([
            'car_variant_id' => 'required|exists:car_variants,id',
            'loan_amount' => 'required|numeric|min:1000000',
            'down_payment' => 'required|numeric|min:0',
            'loan_term' => 'required|integer|min:12|max:84',
            'contact_name' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'contact_email' => 'required|email|max:255',
        ]);

        // Có thể gửi email/thông báo cho admin tại đây nếu cần
        return response()->json([
            'success' => true,
            'message' => 'Đã ghi nhận nhu cầu vay. Nhân viên sẽ liên hệ hỗ trợ trong thời gian sớm nhất.',
        ]);
    }

    /**
     * Get financing calculator with preset options
     */
    public function calculator()
    {
        $financeOptions = FinanceOption::where('is_active', 1)->get();
        $carVariants = CarVariant::with(['carModel.carBrand'])
            ->where('is_active', 1)
            ->orderBy('price')
            ->get();

        return view('user.finance.calculator', compact('financeOptions', 'carVariants'));
    }

    /**
     * Get financing requirements
     */
    public function requirements()
    {
        $requirements = [
            'personal' => [
                'title' => 'Yêu cầu cá nhân',
                'items' => [
                    'CMND/CCCD còn hiệu lực',
                    'Sổ hộ khẩu/KT3',
                    'Giấy tờ chứng minh thu nhập (3 tháng gần nhất)',
                    'Sao kê tài khoản ngân hàng (3 tháng gần nhất)',
                    'Giấy tờ xe (nếu có)',
                    'Hợp đồng lao động hoặc giấy phép kinh doanh'
                ]
            ],
            'financial' => [
                'title' => 'Yêu cầu tài chính',
                'items' => [
                    'Thu nhập tối thiểu: 8 triệu VNĐ/tháng',
                    'Tỷ lệ trả nợ tối đa: 50% thu nhập',
                    'Lịch sử tín dụng tốt',
                    'Không có nợ xấu tại các tổ chức tín dụng',
                    'Có tài khoản ngân hàng ổn định'
                ]
            ],
            'collateral' => [
                'title' => 'Tài sản đảm bảo',
                'items' => [
                    'Xe được mua sẽ là tài sản đảm bảo',
                    'Không cần thế chấp tài sản khác',
                    'Bảo hiểm xe bắt buộc trong suốt thời gian vay',
                    'Bảo hiểm nhân thọ (tùy chọn)'
                ]
            ]
        ];

        return view('user.finance.requirements', compact('requirements'));
    }

    /**
     * Get financing FAQ
     */
    public function faq()
    {
        $faqs = [
            [
                'question' => 'Tôi có thể vay tối đa bao nhiêu tiền?',
                'answer' => 'Bạn có thể vay tối đa 90% giá trị xe, tùy thuộc vào thu nhập và khả năng trả nợ.'
            ],
            [
                'question' => 'Lãi suất vay hiện tại là bao nhiêu?',
                'answer' => 'Lãi suất vay từ 0% đến 15%/năm, tùy thuộc vào gói vay và thời hạn vay.'
            ],
            [
                'question' => 'Thời hạn vay tối đa là bao lâu?',
                'answer' => 'Thời hạn vay tối đa là 84 tháng (7 năm) cho các gói vay tiêu chuẩn.'
            ],
            [
                'question' => 'Tôi có cần trả trước không?',
                'answer' => 'Có, bạn cần trả trước tối thiểu 10% giá trị xe, tùy thuộc vào gói vay.'
            ],
            [
                'question' => 'Thủ tục vay có phức tạp không?',
                'answer' => 'Thủ tục vay rất đơn giản, chỉ cần chuẩn bị các giấy tờ cần thiết và chúng tôi sẽ hỗ trợ bạn hoàn thành.'
            ],
            [
                'question' => 'Tôi có thể trả nợ sớm không?',
                'answer' => 'Có, bạn có thể trả nợ sớm bất cứ lúc nào mà không bị phạt phí.'
            ]
        ];

        return view('user.finance.faq', compact('faqs'));
    }
}
