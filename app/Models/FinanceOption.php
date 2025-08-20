<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'bank_name',
        'interest_rate',
        'min_tenure',
        'max_tenure',
        'min_down_payment',
        'max_loan_amount',
        'requirements',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'interest_rate' => 'decimal:2',
        'min_tenure' => 'integer',
        'max_tenure' => 'integer',
        'min_down_payment' => 'decimal:2',
        'max_loan_amount' => 'decimal:2',
        'is_active' => 'boolean',
        'requirements' => 'json',
        'sort_order' => 'integer',
    ];

    public function calculateMonthlyPayment($carPrice, $downPaymentPercent, $tenure)
    {
        $downPayment = $carPrice * ($downPaymentPercent / 100);
        $loanAmount = $carPrice - $downPayment;
        $monthlyRate = ($this->interest_rate / 100) / 12;
        
        if ($loanAmount > 0 && $tenure > 0) {
            $monthlyPayment = $loanAmount * ($monthlyRate * pow(1 + $monthlyRate, $tenure)) / (pow(1 + $monthlyRate, $tenure) - 1);
            return round($monthlyPayment, 0);
        }
        
        return 0;
    }

    public function getFormattedInterestRateAttribute()
    {
        return number_format($this->interest_rate, 2) . '%';
    }

    public function getFormattedMaxLoanAmountAttribute()
    {
        return number_format($this->max_loan_amount, 0, ',', '.') . ' VNĐ';
    }
} 