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


}