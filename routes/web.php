<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\IsAdmin;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CarController;
use App\Http\Controllers\Admin\CarModelController;
use App\Http\Controllers\Admin\CarVariantController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CartItemController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AccessoryController;
use App\Http\Controllers\Admin\OrderLogController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\TestDriveController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\UserProfileController;
use App\Http\Controllers\Admin\ServiceAppointmentController;

// Public Controllers
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\OrderController as UserOrderController;
use App\Http\Controllers\User\CarVariantController as UserCarVariantController;
use App\Http\Controllers\User\AccessoryController as UserAccessoryController;
use App\Http\Controllers\User\ReviewController as UserReviewController;
use App\Http\Controllers\User\TestDriveController as UserTestDriveController;
use App\Http\Controllers\User\SearchController;
use App\Http\Controllers\User\ProductController;

// New User Controllers
use App\Http\Controllers\User\CarBrandController as BrandController;
use App\Http\Controllers\User\ServiceController;
use App\Http\Controllers\User\FinanceController;
use App\Http\Controllers\User\PaymentController as UserPaymentController;
use App\Http\Controllers\User\AddressController as UserAddressController;
use App\Http\Controllers\User\WishlistController;

// --- Trang chủ ---
Route::get('/', [HomeController::class, 'index'])->name('home');

// --- Dashboard cho user ---
Route::get('/dashboard', function () {
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');

// Carvariant detail (User) - support slug or id
Route::get('/car-variants/{slugOrId}', [UserCarVariantController::class, 'show'])->name('car-variants.show');

// Accessory detail (User)
Route::get('/accessories/{id}', [UserAccessoryController::class, 'show'])->name('accessories.show');

// Reviews
Route::prefix('reviews')->name('reviews.')->group(function () {
    Route::post('/store', [UserReviewController::class, 'store'])->name('store')->middleware('auth');
    Route::get('/get', [UserReviewController::class, 'getReviews'])->name('get');
    Route::get('/summary', [UserReviewController::class, 'summary'])->name('summary');
});

// Test Drives
Route::prefix('test-drives')->name('test-drives.')->group(function () {
    Route::post('/book', [UserTestDriveController::class, 'store'])->name('book');
    Route::get('/', [UserTestDriveController::class, 'index'])->name('index');
    Route::get('/{testDrive}', [UserTestDriveController::class, 'show'])->name('show');
})->middleware('auth');

// Search
Route::prefix('search')->name('search.')->group(function () {
    Route::get('/', [SearchController::class, 'search'])->name('results');
    Route::get('/advanced', [SearchController::class, 'advancedSearch'])->name('advanced');
});

// Blogs (User)
Route::prefix('blogs')->name('blogs.')->group(function () {
    Route::get('/', [\App\Http\Controllers\User\BlogController::class, 'index'])->name('index');
    Route::get('/{blog}', [\App\Http\Controllers\User\BlogController::class, 'show'])->name('show');
});

// Products listing (Unified cars/accessories)
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// --- NEW ROUTES ---

// Car Brands (User)
Route::prefix('car-brands')->name('car-brands.')->group(function () {
    Route::get('/', [BrandController::class, 'index'])->name('index');
    Route::get('/{id}', [BrandController::class, 'show'])->name('show');
});

// Legacy redirects from old path/names
Route::permanentRedirect('/brands', '/car-brands');
Route::permanentRedirect('/brands/{id}', '/car-brands/{id}');

// Services (User) — removed per request

// Finance (User)
Route::prefix('finance')->name('finance.')->group(function () {
    Route::get('/', [FinanceController::class, 'index'])->name('index');
    Route::get('/calculator', [FinanceController::class, 'calculator'])->name('calculator');
    Route::get('/requirements', [FinanceController::class, 'requirements'])->name('requirements');
    Route::get('/faq', [FinanceController::class, 'faq'])->name('faq');
    Route::post('/calculate-installment', [FinanceController::class, 'calculateInstallment'])->name('calculate-installment');
    Route::post('/apply', [FinanceController::class, 'applyForFinancing'])->name('apply');
    Route::get('/options', [FinanceController::class, 'getFinanceOptions'])->name('options');
});

// About page
Route::get('/about', function () {
    return view('user.about');
})->name('about');

// Contact page
Route::get('/contact', [\App\Http\Controllers\User\ContactController::class, 'index'])->name('contact');
Route::post('/contact', [\App\Http\Controllers\User\ContactController::class, 'store'])->name('contact.store');

// --- Profile cá nhân --- (moved under /user)
Route::middleware('auth')->prefix('user')->name('user.')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Payment routes (return + webhook)
Route::prefix('payment')->group(function(){
    // VNPAY
    Route::get('/vnpay/return', [\App\Http\Controllers\PaymentController::class, 'vnpayReturn'])->name('payment.vnpay.return');
    Route::post('/vnpay/webhook', [\App\Http\Controllers\PaymentController::class, 'vnpayWebhook'])->name('payment.vnpay.webhook');
    // MoMo
    Route::get('/momo/process', [\App\Http\Controllers\PaymentController::class, 'momoProcess'])->name('payment.momo.process');
    Route::get('/momo/return', [\App\Http\Controllers\PaymentController::class, 'momoReturn'])->name('payment.momo.return');
    Route::post('/momo/webhook', [\App\Http\Controllers\PaymentController::class, 'momoWebhook'])->name('payment.momo.webhook');
});

Route::post('/order', [UserOrderController::class, 'store'])->middleware('auth')->name('order.store');

// Cart (moved under /user)
Route::prefix('user/cart')->name('user.cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index'); // Xem giỏ hàng
    Route::get('/count', [CartController::class, 'getCount'])->name('count'); // Lấy số lượng cart
    Route::post('/add', [CartController::class, 'add'])->name('add'); // Thêm vào giỏ
    Route::post('/update/{cartItem}', [CartController::class, 'update'])->name('update'); // Cập nhật số lượng
    Route::delete('/remove/{cartItem}', [CartController::class, 'remove'])->name('remove'); // Xóa khỏi giỏ
    Route::post('/clear', [CartController::class, 'clear'])->name('clear'); // Xóa toàn bộ giỏ
});

// --- Cart routes --- (moved under /user)
Route::middleware('auth')->group(function () {
    Route::get('/user/cart/checkout', [CartController::class, 'showCheckoutForm'])->name('user.cart.checkout.form');
    Route::post('/user/cart/checkout', [CartController::class, 'processCheckout'])->name('user.cart.checkout');
    Route::get('/user/order/success/{order}', [CartController::class, 'orderSuccess'])->name('user.order.success');
});

// --- Admin routes ---
Route::middleware(['auth', IsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Car
    Route::prefix('cars')->name('cars.')->group(function () {
        Route::get('/', [CarController::class, 'index'])->name('index');
        Route::get('/create', [CarController::class, 'create'])->name('create');
        Route::post('/store', [CarController::class, 'store'])->name('store');
        Route::get('/edit/{car}', [CarController::class, 'edit'])->name('edit');
        Route::put('/update/{car}', [CarController::class, 'update'])->name('update');
        Route::delete('/delete/{car}', [CarController::class, 'destroy'])->name('destroy');
    });

    // Car Models
    Route::prefix('carmodels')->name('carmodels.')->group(function () {
        Route::get('/', [CarModelController::class, 'index'])->name('index');
        Route::get('/create', [CarModelController::class, 'create'])->name('create');
        Route::post('/store', [CarModelController::class, 'store'])->name('store');
        Route::get('/edit/{carmodel}', [CarModelController::class, 'edit'])->name('edit');
        Route::put('/update/{carmodel}', [CarModelController::class, 'update'])->name('update');
        Route::delete('/delete/{carmodel}', [CarModelController::class, 'destroy'])->name('destroy');
    });

    // Car Variants
    Route::prefix('carvariants')->name('carvariants.')->group(function () {
        Route::get('/', [CarVariantController::class, 'index'])->name('index');
        Route::get('/create', [CarVariantController::class, 'create'])->name('create');
        Route::post('/store', [CarVariantController::class, 'store'])->name('store');
        Route::get('/edit/{carvariant}', [CarVariantController::class, 'edit'])->name('edit');
        Route::put('/update/{carvariant}', [CarVariantController::class, 'update'])->name('update');
        Route::delete('/delete/{carvariant}', [CarVariantController::class, 'destroy'])->name('destroy');
    });

    // Orders
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/create', [OrderController::class, 'create'])->name('create');
        Route::post('/store', [OrderController::class, 'store'])->name('store');
        Route::get('/edit/{order}', [OrderController::class, 'edit'])->name('edit');
        Route::put('/update/{order}', [OrderController::class, 'update'])->name('update');
        Route::delete('/delete/{order}', [OrderController::class, 'destroy'])->name('destroy');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        // Chuyển trạng thái đơn
        Route::post('/{order}/next-status', [OrderController::class, 'nextStatus'])->name('nextStatus');
        Route::post('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel');

        // Logs
        Route::get('/{order}/logs', [OrderLogController::class, 'index'])->name('logs');
        Route::get('/{order}/logs/export', [OrderLogController::class, 'export'])->name('logs.export');
    });

    // Accessories
    Route::prefix('accessories')->name('accessories.')->group(function () {
        Route::get('/', [AccessoryController::class, 'index'])->name('index');
        Route::get('/create', [AccessoryController::class, 'create'])->name('create');
        Route::post('/store', [AccessoryController::class, 'store'])->name('store');
        Route::get('/edit/{accessory}', [AccessoryController::class, 'edit'])->name('edit');
        Route::put('/update/{accessory}', [AccessoryController::class, 'update'])->name('update');
        Route::delete('/delete/{accessory}', [AccessoryController::class, 'destroy'])->name('destroy');
    });

    // Blogs
    Route::prefix('blogs')->name('blogs.')->group(function () {
        Route::get('/', [BlogController::class, 'index'])->name('index');
        Route::get('/create', [BlogController::class, 'create'])->name('create');
        Route::post('/store', [BlogController::class, 'store'])->name('store');
        Route::get('/edit/{blog}', [BlogController::class, 'edit'])->name('edit');
        Route::put('/update/{blog}', [BlogController::class, 'update'])->name('update');
        Route::delete('/delete/{blog}', [BlogController::class, 'destroy'])->name('destroy');
    });

    // Users
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/edit/{user}', [UserController::class, 'edit'])->name('edit');
        Route::put('/update/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/delete/{user}', [UserController::class, 'destroy'])->name('destroy');
    });

    // Reviews
    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::get('/', [ReviewController::class, 'index'])->name('index');
        Route::post('/{review}/approve', [ReviewController::class, 'approve'])->name('approve');
        Route::post('/{review}/reject', [ReviewController::class, 'reject'])->name('reject');
        Route::delete('/{review}', [ReviewController::class, 'destroy'])->name('destroy');
    });

    // Test Drives
    Route::prefix('test-drives')->name('test-drives.')->group(function () {
        Route::get('/', [TestDriveController::class, 'index'])->name('index');
        Route::get('/{testDrive}', [TestDriveController::class, 'show'])->name('show');
        Route::put('/{testDrive}/status', [TestDriveController::class, 'updateStatus'])->name('update_status');
        Route::delete('/{testDrive}', [TestDriveController::class, 'destroy'])->name('destroy');
    });

    // Payment Methods
    Route::prefix('payment-methods')->name('payment-methods.')->group(function () {
        Route::get('/', [PaymentMethodController::class, 'index'])->name('index');
        Route::get('/create', [PaymentMethodController::class, 'create'])->name('create');
        Route::post('/', [PaymentMethodController::class, 'store'])->name('store');
        Route::get('/{paymentMethod}', [PaymentMethodController::class, 'show'])->name('show');
        Route::get('/{paymentMethod}/edit', [PaymentMethodController::class, 'edit'])->name('edit');
        Route::put('/{paymentMethod}', [PaymentMethodController::class, 'update'])->name('update');
        Route::delete('/{paymentMethod}', [PaymentMethodController::class, 'destroy'])->name('destroy');
        Route::post('/{paymentMethod}/toggle-status', [PaymentMethodController::class, 'toggleStatus'])->name('toggle-status');
    });

    // User Profiles
    Route::prefix('customer-profiles')->name('customer-profiles.')->group(function () {
        Route::get('/', [UserProfileController::class, 'index'])->name('index');
        Route::get('/{customerProfile}', [UserProfileController::class, 'show'])->name('show');
        Route::get('/{customerProfile}/edit', [UserProfileController::class, 'edit'])->name('edit');
        Route::put('/{customerProfile}', [UserProfileController::class, 'update'])->name('update');
        Route::delete('/{customerProfile}', [UserProfileController::class, 'destroy'])->name('destroy');
        Route::post('/{customerProfile}/toggle-vip', [UserProfileController::class, 'toggleVip'])->name('toggle-vip');
        Route::get('/export', [UserProfileController::class, 'export'])->name('export');
    });

    // Service Appointments
    Route::prefix('service-appointments')->name('service-appointments.')->group(function () {
        Route::get('/', [ServiceAppointmentController::class, 'index'])->name('index');
        Route::get('/dashboard', [ServiceAppointmentController::class, 'dashboard'])->name('dashboard');
        Route::get('/calendar', [ServiceAppointmentController::class, 'calendar'])->name('calendar');
        Route::get('/{appointment}', [ServiceAppointmentController::class, 'show'])->name('show');
        Route::get('/{appointment}/edit', [ServiceAppointmentController::class, 'edit'])->name('edit');
        Route::put('/{appointment}', [ServiceAppointmentController::class, 'update'])->name('update');
        Route::delete('/{appointment}', [ServiceAppointmentController::class, 'destroy'])->name('destroy');
        Route::put('/{appointment}/status', [ServiceAppointmentController::class, 'updateStatus'])->name('update-status');
        Route::get('/export', [ServiceAppointmentController::class, 'export'])->name('export');
    });

    // Payments
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('index');
        Route::get('/dashboard', [\App\Http\Controllers\Admin\PaymentController::class, 'dashboard'])->name('dashboard');
        Route::get('/reports', [\App\Http\Controllers\Admin\PaymentController::class, 'reports'])->name('reports');
        Route::get('/installments', [\App\Http\Controllers\Admin\PaymentController::class, 'installments'])->name('installments');
        Route::get('/refunds', [\App\Http\Controllers\Admin\PaymentController::class, 'refunds'])->name('refunds');
        Route::get('/{transaction}', [\App\Http\Controllers\Admin\PaymentController::class, 'show'])->name('show');
        Route::get('/{transaction}/edit', [\App\Http\Controllers\Admin\PaymentController::class, 'edit'])->name('edit');
        Route::put('/{transaction}', [\App\Http\Controllers\Admin\PaymentController::class, 'update'])->name('update');
        Route::delete('/{transaction}', [\App\Http\Controllers\Admin\PaymentController::class, 'destroy'])->name('destroy');
        Route::put('/{transaction}/status', [\App\Http\Controllers\Admin\PaymentController::class, 'updateStatus'])->name('update-status');
        Route::put('/refunds/{refund}/status', [\App\Http\Controllers\Admin\PaymentController::class, 'updateRefundStatus'])->name('update-refund-status');
        Route::get('/export', [\App\Http\Controllers\Admin\PaymentController::class, 'export'])->name('export');
    });
});

// Trang chi tiết model xe
Route::get('/car-models/{id}', [\App\Http\Controllers\User\CarModelController::class, 'show'])->name('car-models.show');

// Legacy redirects for car models path/name
Route::permanentRedirect('/car_models/{id}', '/car-models/{id}');

// Notification routes
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::get('/notifications/unread-count', [\App\Http\Controllers\NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::delete('/notifications/{id}', [\App\Http\Controllers\NotificationController::class, 'delete'])->name('notifications.delete');
});

// Legacy redirects for car variants and test drives (underscore to hyphen)
Route::permanentRedirect('/car_variants/{slugOrId}', '/car-variants/{slugOrId}');
Route::permanentRedirect('/test_drives', '/test-drives');
Route::permanentRedirect('/test_drives/{testDrive}', '/test-drives/{testDrive}');

// Legacy route name aliases (underscore → hyphen) to avoid RouteNotFoundException in old blades
Route::get('/_alias/test_drives', function(){ return redirect()->route('test-drives.index'); })->name('test_drives.index');
Route::get('/_alias/test_drives/{testDrive}', function($testDrive){ return redirect()->route('test-drives.show', ['testDrive' => $testDrive]); })->name('test_drives.show');

// --- Auth routes ---
require __DIR__ . '/auth.php';

// Accessories (User)
Route::get('/accessories', [\App\Http\Controllers\User\AccessoryController::class, 'index'])->name('accessories.index');

// User Customer Profile Routes
Route::prefix('customer-profiles')->name('user.customer-profiles.')->middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\User\UserProfileController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\User\UserProfileController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\User\UserProfileController::class, 'store'])->name('store');
    Route::get('/edit', [App\Http\Controllers\User\UserProfileController::class, 'edit'])->name('edit');
    Route::put('/', [App\Http\Controllers\User\UserProfileController::class, 'update'])->name('update');
    Route::get('/orders', [App\Http\Controllers\User\UserProfileController::class, 'orders'])->name('orders');
    Route::get('/test-drives', [App\Http\Controllers\User\UserProfileController::class, 'testDrives'])->name('test-drives');
    // Route điểm tích lũy đã lược bỏ
    Route::get('/preferences', [App\Http\Controllers\User\UserProfileController::class, 'preferences'])->name('preferences');
    Route::put('/preferences', [App\Http\Controllers\User\UserProfileController::class, 'updatePreferences'])->name('update-preferences');
});

// User Service Appointment Routes
Route::prefix('service-appointments')->name('user.service-appointments.')->middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\User\ServiceAppointmentController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\User\ServiceAppointmentController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\User\ServiceAppointmentController::class, 'store'])->name('store');

    // Static routes MUST come before wildcard to avoid 404
    Route::get('/history', [App\Http\Controllers\User\ServiceAppointmentController::class, 'getServiceHistory'])->name('history');
    Route::get('/upcoming', [App\Http\Controllers\User\ServiceAppointmentController::class, 'getUpcomingAppointments'])->name('upcoming');
    Route::post('/check-availability', [App\Http\Controllers\User\ServiceAppointmentController::class, 'checkAvailability'])->name('check-availability');

    // Wildcard routes with numeric constraint
    Route::get('/{appointment}', [App\Http\Controllers\User\ServiceAppointmentController::class, 'show'])->whereNumber('appointment')->name('show');
    Route::get('/{appointment}/edit', [App\Http\Controllers\User\ServiceAppointmentController::class, 'edit'])->whereNumber('appointment')->name('edit');
    Route::put('/{appointment}', [App\Http\Controllers\User\ServiceAppointmentController::class, 'update'])->whereNumber('appointment')->name('update');
    Route::put('/{appointment}/cancel', [App\Http\Controllers\User\ServiceAppointmentController::class, 'cancel'])->whereNumber('appointment')->name('cancel');
    Route::put('/{appointment}/reschedule', [App\Http\Controllers\User\ServiceAppointmentController::class, 'reschedule'])->whereNumber('appointment')->name('reschedule');
});

// User Payment Routes
Route::prefix('payments')->name('user.payments.')->middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\User\PaymentController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\User\PaymentController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\User\PaymentController::class, 'store'])->name('store');
    Route::get('/{transaction}', [App\Http\Controllers\User\PaymentController::class, 'show'])->name('show');
    Route::post('/{transaction}/process', [App\Http\Controllers\User\PaymentController::class, 'processPayment'])->name('process');
    Route::post('/calculate-installment', [App\Http\Controllers\User\PaymentController::class, 'calculateInstallment'])->name('calculate-installment');
    Route::post('/{transaction}/refund', [App\Http\Controllers\User\PaymentController::class, 'refund'])->name('refund');
    Route::get('/installment-history', [App\Http\Controllers\User\PaymentController::class, 'installmentHistory'])->name('installment-history');
    Route::get('/payment-methods', [App\Http\Controllers\User\PaymentController::class, 'paymentMethods'])->name('payment-methods');
    Route::get('/transaction-history', [App\Http\Controllers\User\PaymentController::class, 'transactionHistory'])->name('transaction-history');
    Route::get('/{transaction}/receipt', [App\Http\Controllers\User\PaymentController::class, 'downloadReceipt'])->name('download-receipt');
});

// User Address Book
Route::prefix('addresses')->name('user.addresses.')->middleware(['auth'])->group(function () {
    Route::get('/', [UserAddressController::class, 'index'])->name('index');
    Route::post('/', [UserAddressController::class, 'store'])->name('store');
    Route::put('/{address}', [UserAddressController::class, 'update'])->name('update');
    Route::delete('/{address}', [UserAddressController::class, 'destroy'])->name('destroy');
    Route::post('/{address}/default', [UserAddressController::class, 'setDefault'])->name('set-default');
});

// Customer profile orders (fallback simple list)
Route::middleware('auth')->group(function(){
    Route::get('/my/orders', [\App\Http\Controllers\User\UserProfileController::class, 'orders'])->name('user.customer-profiles.orders');
    Route::get('/my/orders/{order}', [\App\Http\Controllers\User\UserProfileController::class, 'showOrder'])->name('user.customer-profiles.show-order');
});

// Wishlist Routes
Route::prefix('wishlist')->name('wishlist.')->group(function () {
    Route::get('/', [WishlistController::class, 'index'])->name('index');
    Route::post('/add', [WishlistController::class, 'add'])->name('add');
    Route::post('/remove', [WishlistController::class, 'remove'])->name('remove');
    Route::delete('/destroy', [WishlistController::class, 'remove'])->name('destroy'); // Alias for remove
    Route::post('/clear', [WishlistController::class, 'clear'])->name('clear');
    Route::get('/check', [WishlistController::class, 'check'])->name('check');
    Route::post('/check-bulk', [WishlistController::class, 'checkBulk'])->name('check-bulk');
    Route::get('/count', [WishlistController::class, 'getCount'])->name('count');
    Route::post('/migrate-session', [WishlistController::class, 'migrateSessionWishlist'])->name('migrate-session');
});


