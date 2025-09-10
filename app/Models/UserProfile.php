<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'profile_type',
        'name',
        'phone',
        'avatar_path',
        'birth_date',
        'gender',
        'driver_license_number',
        'driver_license_issue_date',
        'driver_license_expiry_date',
        'driver_license_class',
        'driving_experience_years',
        'preferred_car_types',
        'preferred_brands',
        'preferred_colors',
        'budget_min',
        'budget_max',
        'purchase_purpose',
        'customer_type',
        'employee_salary',
        'employee_skills',
        'is_vip',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'driver_license_issue_date' => 'date',
        'driver_license_expiry_date' => 'date',
        'avatar_path' => 'string',
        'budget_min' => 'decimal:2',
        'budget_max' => 'decimal:2',
        'driving_experience_years' => 'integer',
        'is_vip' => 'boolean',
        'preferred_car_types' => 'json',
        'preferred_brands' => 'json',
        'preferred_colors' => 'json',
        'employee_salary' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // No extra relations defined by profile schema

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


