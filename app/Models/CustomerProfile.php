<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'full_name',
        'birth_date',
        'gender',
        'nationality',
        'id_card_number',
        'passport_number',
        'phone',
        'email',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'occupation',
        'company_name',
        'job_title',
        'work_phone',
        'work_email',
        'monthly_income',
        'income_source',
        'bank_name',
        'bank_account_number',
        'credit_score',
        'has_existing_loan',
        'existing_loan_details',
        'driver_license_number',
        'driver_license_issue_date',
        'driver_license_expiry_date',
        'driver_license_class',
        'driving_experience_years',
        'driving_history',
        'preferred_car_types',
        'preferred_brands',
        'preferred_colors',
        'budget_min',
        'budget_max',
        'purchase_purpose',
        'special_requirements',
        'marital_status',
        'family_size',
        'has_children',
        'children_count',
        'family_vehicles',
        'customer_type',
        'lead_source',
        'referred_by',
        'first_contact_date',
        'last_contact_date',
        'total_visits',
        'total_test_drives',
        'assigned_sales_person_id',
        'preferred_showroom_id',
        'sales_notes',
        'sales_stage',
        'consent_to_marketing',
        'marketing_preferences',
        'consent_to_sms',
        'consent_to_email',
        'consent_to_call',
        'is_active',
        'is_vip',
        'status',
        'satisfaction_score',
        'feedback'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'driver_license_issue_date' => 'date',
        'driver_license_expiry_date' => 'date',
        'first_contact_date' => 'date',
        'last_contact_date' => 'date',
        'monthly_income' => 'decimal:2',
        'budget_min' => 'decimal:2',
        'budget_max' => 'decimal:2',
        'satisfaction_score' => 'decimal:1',
        'credit_score' => 'integer',
        'driving_experience_years' => 'integer',
        'family_size' => 'integer',
        'children_count' => 'integer',
        'total_visits' => 'integer',
        'total_test_drives' => 'integer',
        'has_existing_loan' => 'boolean',
        'has_children' => 'boolean',
        'consent_to_marketing' => 'boolean',
        'consent_to_sms' => 'boolean',
        'consent_to_email' => 'boolean',
        'consent_to_call' => 'boolean',
        'is_active' => 'boolean',
        'is_vip' => 'boolean',
        'preferred_car_types' => 'json',
        'preferred_brands' => 'json',
        'preferred_colors' => 'json',
        'family_vehicles' => 'json',
        'marketing_preferences' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedSalesPerson()
    {
        return $this->belongsTo(User::class, 'assigned_sales_person_id');
    }

    public function preferredShowroom()
    {
        return $this->belongsTo(Showroom::class, 'preferred_showroom_id');
    }

    public function getGenderDisplayAttribute()
    {
        $genders = [
            'male' => 'Nam',
            'female' => 'Nữ',
            'other' => 'Khác'
        ];
        return $genders[$this->gender] ?? $this->gender;
    }

    public function getCustomerTypeDisplayAttribute()
    {
        $types = [
            'new' => 'Khách hàng mới',
            'returning' => 'Khách hàng cũ',
            'vip' => 'Khách hàng VIP',
            'prospect' => 'Khách hàng tiềm năng'
        ];
        return $types[$this->customer_type] ?? $this->customer_type;
    }

    public function getFormattedMonthlyIncomeAttribute()
    {
        return number_format($this->monthly_income, 0, ',', '.') . ' VNĐ';
    }

    public function getFormattedBudgetRangeAttribute()
    {
        return number_format($this->budget_min, 0, ',', '.') . ' - ' . 
               number_format($this->budget_max, 0, ',', '.') . ' VNĐ';
    }
}
