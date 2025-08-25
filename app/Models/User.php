<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'phone', 
        'role',
        'avatar_path', 
        'is_verified',
        'verification_token',
        'email_verification_sent_at',
        'phone_verified_at',
        'two_factor_enabled',
        'two_factor_code',
        'two_factor_expires_at',
        'employee_id',
        'department',
        'position',
        'hire_date',
        'is_active',
        'last_login_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'verification_token',
        'two_factor_code',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'two_factor_enabled' => 'boolean',
        'two_factor_expires_at' => 'datetime',
        'hire_date' => 'date',
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
        'email_verification_sent_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (User $user): void {
            if (array_key_exists('is_admin', $user->attributes ?? [])) {
                $val = (bool) ($user->attributes['is_admin'] ?? false);
                if ($val) {
                    $user->role = 'admin';
                }
                unset($user->attributes['is_admin']);
            }
        });
        static::updating(function (User $user): void {
            if (array_key_exists('is_admin', $user->attributes ?? [])) {
                $val = (bool) ($user->attributes['is_admin'] ?? false);
                if ($val) {
                    $user->role = 'admin';
                }
                unset($user->attributes['is_admin']);
            }
        });
    }

    public function setIsAdminAttribute($value): void
    {
        if ((bool) $value) {
            $this->attributes['role'] = 'admin';
        }
        // Do not persist non-existent column
        if (isset($this->attributes['is_admin'])) {
            unset($this->attributes['is_admin']);
        }
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function wishlistItems()
    {
        return $this->hasMany(WishlistItem::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function customerProfile()
    {
        return $this->hasOne(CustomerProfile::class);
    }

    public function blogs()
    {
        return $this->hasMany(Blog::class, 'admin_id');
    }

    public function notifications()
    {
        return $this->hasMany(\App\Models\Notification::class);
    }

    public function testDrives()
    {
        return $this->hasMany(TestDrive::class);
    }

    public function serviceAppointments()
    {
        return $this->hasMany(ServiceAppointment::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function contactMessages()
    {
        return $this->hasMany(ContactMessage::class);
    }

    // Payment relationships
    public function paymentTransactions()
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    public function installments()
    {
        return $this->hasMany(Installment::class);
    }

    // Staff relationships
    public function assignedCustomers()
    {
        return $this->hasMany(CustomerProfile::class, 'assigned_sales_person_id');
    }

    public function assignedServiceAppointments()
    {
        return $this->hasMany(ServiceAppointment::class, 'assigned_technician_id');
    }

    public function qualityCheckedAppointments()
    {
        return $this->hasMany(ServiceAppointment::class, 'quality_check_by');
    }

    // Order audit relationships
    public function createdOrders()
    {
        return $this->hasMany(Order::class, 'created_by');
    }

    public function updatedOrders()
    {
        return $this->hasMany(Order::class, 'updated_by');
    }

    public function cancelledOrders()
    {
        return $this->hasMany(Order::class, 'cancelled_by');
    }

    public function salesOrders()
    {
        return $this->hasMany(Order::class, 'sales_person_id');
    }





    public function getUnreadNotificationsCountAttribute()
    {
        return $this->notifications()->where('is_read', false)->count();
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }


}