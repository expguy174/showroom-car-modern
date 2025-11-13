<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'email',
        'password',
        'role',
        'email_verified',
        'employee_id',
        'department',
        'position',
        'hire_date',
        'is_active',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'email_verified' => 'boolean',
        'hire_date' => 'date',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        // No-op hooks; removed legacy is_admin mapping
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

    public function userProfile()
    {
        return $this->hasOne(UserProfile::class);
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

    public function isManager()
    {
        return $this->role === 'manager';
    }

    public function isSalesPerson()
    {
        return $this->role === 'sales_person';
    }

    public function isTechnician()
    {
        return $this->role === 'technician';
    }

    public function isStaff()
    {
        return in_array($this->role, ['admin', 'manager', 'sales_person', 'technician']);
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    public function hasRole($role)
    {
        if (is_array($role)) {
            return in_array($this->role, $role);
        }
        return $this->role === $role;
    }

    public function getRoleLabel()
    {
        $labels = [
            'admin' => 'Quản trị viên',
            'manager' => 'Quản lý',
            'sales_person' => 'Nhân viên Kinh doanh',
            'technician' => 'Kỹ thuật viên',
            'user' => 'Người dùng'
        ];
        
        return $labels[$this->role] ?? ucfirst($this->role);
    }

    public function getRoleColor()
    {
        $colors = [
            'admin' => 'bg-red-100 text-red-800',
            'manager' => 'bg-purple-100 text-purple-800',
            'sales_person' => 'bg-blue-100 text-blue-800',
            'technician' => 'bg-green-100 text-green-800',
            'user' => 'bg-gray-100 text-gray-800'
        ];
        
        return $colors[$this->role] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Determine if the user has verified their email address.
     * Override to check both email_verified boolean and email_verified_at timestamp.
     */
    public function hasVerifiedEmail(): bool
    {
        return $this->email_verified || !is_null($this->email_verified_at);
    }

    /**
     * Mark the given user's email as verified.
     * Override to set both email_verified boolean and email_verified_at timestamp.
     */
    public function markEmailAsVerified(): bool
    {
        $this->email_verified = true;
        $this->email_verified_at = $this->freshTimestamp();
        return $this->save();
    }

}