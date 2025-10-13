<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\IsAdmin;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CarBrandController;
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
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\ShowroomController;

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
use App\Http\Controllers\User\AddressController as UserAddressController;
use App\Http\Controllers\User\WishlistController;

// --- Trang chủ ---
Route::get('/', [HomeController::class, 'index'])->name('home');

// --- Dashboard cho user ---
Route::get('/dashboard', function () {
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');

// Car brand detail (User)
Route::get('/car-brands/{id}', [\App\Http\Controllers\User\CarBrandController::class, 'show'])->name('car-brands.show');

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

// Test Drives (require auth)
Route::middleware('auth')->prefix('test-drives')->name('test-drives.')->group(function () {
    Route::post('/book', [UserTestDriveController::class, 'store'])->name('book');
    Route::get('/create', [UserTestDriveController::class, 'create'])->name('create');
    Route::get('/', [UserTestDriveController::class, 'index'])->name('index');
    Route::post('/check-availability', [UserTestDriveController::class, 'checkAvailability'])->name('check-availability');
    Route::get('/{testDrive}', [UserTestDriveController::class, 'show'])->name('show');
    Route::get('/{testDrive}/edit', [UserTestDriveController::class, 'edit'])->name('edit');
    Route::put('/{testDrive}', [UserTestDriveController::class, 'update'])->name('update');
    Route::post('/{testDrive}/cancel', [UserTestDriveController::class, 'cancel'])->name('cancel');
    Route::post('/{testDrive}/reschedule', [UserTestDriveController::class, 'reschedule'])->name('reschedule');
    Route::post('/{testDrive}/rate', [UserTestDriveController::class, 'rate'])->name('rate');
});

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
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Granular profile updates (AJAX)
    Route::patch('/profile/general', [ProfileController::class, 'updateGeneral'])->name('profile.general.update');
    Route::patch('/profile/license', [ProfileController::class, 'updateLicense'])->name('profile.license.update');
    Route::patch('/profile/preferences', [ProfileController::class, 'updatePreferences'])->name('profile.preferences.update');
    Route::patch('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
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

Route::middleware('auth')->group(function(){
    Route::get('/user/orders', [UserOrderController::class, 'index'])->name('user.order.index');
    Route::get('/user/orders/{order}', [UserOrderController::class, 'show'])->name('user.orders.show');
    Route::post('/user/orders/{order}/cancel', [UserOrderController::class, 'cancel'])->name('user.orders.cancel');
    Route::post('/user/orders/{order}/refund', [UserOrderController::class, 'requestRefund'])->name('user.orders.refund');
    Route::post('/order', [UserOrderController::class, 'store'])->name('order.store');
});

// Cart (moved under /user)
Route::prefix('user/cart')->name('user.cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index'); // Xem giỏ hàng
    Route::get('/count', [CartController::class, 'getCount'])->name('count'); // Lấy số lượng cart
    Route::get('/items', [CartController::class, 'getItems'])->name('items'); // Lấy tất cả items cart
    Route::post('/add', [CartController::class, 'add'])->name('add'); // Thêm vào giỏ
    Route::post('/update/{cartItem}', [CartController::class, 'update'])->name('update'); // Cập nhật số lượng
    Route::delete('/remove/{cartItem}', [CartController::class, 'remove'])->name('remove'); // Xóa khỏi giỏ
    Route::post('/clear', [CartController::class, 'clear'])->name('clear'); // Xóa toàn bộ giỏ
});

// Legacy cart routes and aliases (pretty URL /cart, keep names under user.cart.*)
Route::middleware('auth')->group(function () {
    // Pretty URL for viewing cart
    Route::get('/cart', function() { return redirect()->route('user.cart.index'); })->name('cart.index');
    // Backward-compatible endpoints if any old JS hits them
    Route::post('/cart/add', function() { return redirect()->to('/user/cart/add'); });
    Route::post('/cart/clear', function() { return redirect()->to('/user/cart/clear'); });
    Route::get('/cart/count', function() { return redirect()->to('/user/cart/count'); });
    Route::post('/cart/update/{cartItem}', function($cartItem) { return redirect()->to("/user/cart/update/{$cartItem}"); });
    Route::delete('/cart/remove/{cartItem}', function($cartItem) { return redirect()->to("/user/cart/remove/{$cartItem}"); });
});

// --- Cart routes --- (moved under /user)
Route::middleware('auth')->group(function () {
    Route::get('/user/cart/checkout', [CartController::class, 'showCheckoutForm'])->name('user.cart.checkout.form');
    Route::post('/user/cart/checkout', [CartController::class, 'processCheckout'])->name('user.cart.checkout');
    Route::get('/user/order/success/{order}', [CartController::class, 'orderSuccess'])->name('user.order.success');
});

// --- Admin routes ---
Route::middleware(['auth', 'staff'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('dashboard.stats');
    Route::post('/dashboard/clear-cache', [DashboardController::class, 'clearCache'])->name('dashboard.clear-cache');

    // Car Brands
    Route::prefix('carbrands')->name('carbrands.')->group(function () {
        Route::get('/', [CarBrandController::class, 'index'])->name('index');
        Route::get('/create', [CarBrandController::class, 'create'])->name('create');
        Route::post('/store', [CarBrandController::class, 'store'])->name('store');
        Route::get('/show/{car}', [CarBrandController::class, 'show'])->name('show');
        Route::get('/edit/{car}', [CarBrandController::class, 'edit'])->name('edit');
        Route::put('/update/{car}', [CarBrandController::class, 'update'])->name('update');
        Route::delete('/delete/{car}', [CarBrandController::class, 'destroy'])->name('destroy');
        Route::post('/{car}/toggle-status', [CarBrandController::class, 'toggleStatus'])->name('toggle-status');
    });

    // Car Models
    Route::prefix('carmodels')->name('carmodels.')->group(function () {
        Route::get('/', [CarModelController::class, 'index'])->name('index');
        Route::get('/create', [CarModelController::class, 'create'])->name('create');
        Route::post('/store', [CarModelController::class, 'store'])->name('store');
        Route::get('/show/{carmodel}', [CarModelController::class, 'show'])->name('show');
        Route::get('/edit/{carmodel}', [CarModelController::class, 'edit'])->name('edit');
        Route::put('/update/{carmodel}', [CarModelController::class, 'update'])->name('update');
        Route::delete('/delete/{carmodel}', [CarModelController::class, 'destroy'])->name('destroy');
        Route::post('/{carmodel}/toggle-status', [CarModelController::class, 'toggleStatus'])->name('toggle-status');
    });
    
    // Car Model Images
    Route::delete('/car-model-images/{imageId}', [CarModelController::class, 'deleteImage'])->name('car-model-images.delete');
    Route::post('/car-model-images/{imageId}/set-main', [CarModelController::class, 'setMainImage'])->name('car-model-images.set-main');
    Route::put('/car-model-images/{imageId}/update', [CarModelController::class, 'updateImage'])->name('car-model-images.update');
    Route::post('/car-model-images/cleanup', [CarModelController::class, 'cleanupImageData'])->name('car-model-images.cleanup');

    // Car Variants
    Route::prefix('carvariants')->name('carvariants.')->group(function () {
        Route::get('/', [CarVariantController::class, 'index'])->name('index');
        Route::get('/create', [CarVariantController::class, 'create'])->name('create');
        Route::post('/store', [CarVariantController::class, 'store'])->name('store');
        Route::get('/show/{carvariant}', [CarVariantController::class, 'show'])->name('show');
        Route::get('/edit/{carvariant}', [CarVariantController::class, 'edit'])->name('edit');
        Route::put('/update/{carvariant}', [CarVariantController::class, 'update'])->name('update');
        Route::delete('/delete/{carvariant}', [CarVariantController::class, 'destroy'])->name('destroy');
        Route::post('/{carvariant}/toggle-status', [CarVariantController::class, 'toggleStatus'])->name('toggle-status');
        
        // Color Management Routes
        Route::post('/{carvariant}/colors', [CarVariantController::class, 'addColor'])->name('colors.add');
        Route::put('/{carvariant}/colors/{colorId}', [CarVariantController::class, 'updateColor'])->name('colors.update');
        Route::delete('/{carvariant}/colors/{colorId}', [CarVariantController::class, 'deleteColor'])->name('colors.delete');
        
        // Specification Management Routes
        Route::post('/{carvariant}/specifications', [CarVariantController::class, 'addSpecification'])->name('specifications.add');
        Route::put('/{carvariant}/specifications/{specId}', [CarVariantController::class, 'updateSpecification'])->name('specifications.update');
        Route::delete('/{carvariant}/specifications/{specId}', [CarVariantController::class, 'deleteSpecification'])->name('specifications.delete');
        
        // Feature Management Routes
        Route::post('/{carvariant}/features', [CarVariantController::class, 'addFeature'])->name('features.add');
        Route::put('/{carvariant}/features/{featureId}', [CarVariantController::class, 'updateFeature'])->name('features.update');
        Route::delete('/{carvariant}/features/{featureId}', [CarVariantController::class, 'deleteFeature'])->name('features.delete');
        
        // Image Management Routes
        Route::post('/{carvariant}/images', [CarVariantController::class, 'uploadImages'])->name('images.upload');
        Route::put('/{carvariant}/images/{imageId}', [CarVariantController::class, 'updateImage'])->name('images.update');
        Route::delete('/{carvariant}/images/{imageId}', [CarVariantController::class, 'deleteImage'])->name('images.delete');
        Route::post('/{carvariant}/images/{imageId}/set-main', [CarVariantController::class, 'setMainImage'])->name('images.set-main');
    });

    // Orders
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        Route::delete('/delete/{order}', [OrderController::class, 'destroy'])->name('destroy');
        
        // Status management
        Route::patch('/{order}/update-status', [OrderController::class, 'updateStatus'])->name('update-status');
        Route::post('/{order}/next-status', [OrderController::class, 'nextStatus'])->name('nextStatus');
        Route::post('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel');
        
        // Inline updates (replaces edit page)
        Route::patch('/{order}/update-tracking', [OrderController::class, 'updateTracking'])->name('update-tracking');
        Route::patch('/{order}/update-note', [OrderController::class, 'updateNote'])->name('update-note');
        Route::patch('/{order}/update-payment-status', [OrderController::class, 'updatePaymentStatus'])->name('update-payment-status');
        Route::post('/{order}/refund', [OrderController::class, 'refund'])->name('refund');
        Route::post('/{order}/generate-installments', [OrderController::class, 'generateInstallments'])->name('generate-installments');

        // Edit & Logs pages removed - all functionality now on show page
        // Route::get('/edit/{order}', [OrderController::class, 'edit'])->name('edit');
        // Route::put('/update/{order}', [OrderController::class, 'update'])->name('update');
        // Route::get('/{order}/logs', [OrderLogController::class, 'index'])->name('logs');
        // Route::get('/{order}/logs/export', [OrderLogController::class, 'export'])->name('logs.export');
    });

    // Accessories
    Route::prefix('accessories')->name('accessories.')->group(function () {
        Route::get('/', [AccessoryController::class, 'index'])->name('index');
        Route::get('/create', [AccessoryController::class, 'create'])->name('create');
        Route::post('/store', [AccessoryController::class, 'store'])->name('store');
        Route::get('/show/{accessory}', [AccessoryController::class, 'show'])->name('show');
        Route::get('/edit/{accessory}', [AccessoryController::class, 'edit'])->name('edit');
        Route::put('/update/{accessory}', [AccessoryController::class, 'update'])->name('update');
        Route::delete('/delete/{accessory}', [AccessoryController::class, 'destroy'])->name('destroy');
        Route::patch('/{accessory}/restore', [AccessoryController::class, 'restore'])->name('restore');
        Route::patch('/{accessory}/toggle-status', [AccessoryController::class, 'toggleStatus'])->name('toggle-status');
        Route::get('/{accessory}/get-image/{index}', [AccessoryController::class, 'getImage'])->name('get-image');
        Route::put('/{accessory}/update-image/{index}', [AccessoryController::class, 'updateImage'])->name('update-image');
        Route::delete('/{accessory}/delete-image/{index}', [AccessoryController::class, 'deleteImage'])->name('delete-image');
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
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/store', [UserController::class, 'store'])->name('store');
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
        Route::get('/export', [TestDriveController::class, 'export'])->name('export');
        Route::get('/{testDrive}', [TestDriveController::class, 'show'])->name('show');
        Route::put('/{testDrive}/status', [TestDriveController::class, 'updateStatus'])->name('update_status');
        Route::put('/{testDrive}/confirm', [TestDriveController::class, 'confirm'])->name('confirm');
        Route::put('/{testDrive}/cancel', [TestDriveController::class, 'cancel'])->name('cancel');
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

    // Service Appointments
    Route::prefix('service-appointments')->name('service-appointments.')->group(function () {
        Route::get('/', [ServiceAppointmentController::class, 'index'])->name('index');
        Route::get('/create', [ServiceAppointmentController::class, 'create'])->name('create');
        Route::post('/', [ServiceAppointmentController::class, 'store'])->name('store');
        Route::get('/dashboard', [ServiceAppointmentController::class, 'dashboard'])->name('dashboard');
        Route::get('/calendar', [ServiceAppointmentController::class, 'calendar'])->name('calendar');
        Route::get('/{appointment}', [ServiceAppointmentController::class, 'show'])->name('show');
        Route::get('/{appointment}/edit', [ServiceAppointmentController::class, 'edit'])->name('edit');
        Route::put('/{appointment}', [ServiceAppointmentController::class, 'update'])->name('update');
        Route::delete('/{appointment}', [ServiceAppointmentController::class, 'destroy'])->name('destroy');
        Route::put('/{appointment}/status', [ServiceAppointmentController::class, 'updateStatus'])->name('update-status');
        Route::get('/export', [ServiceAppointmentController::class, 'export'])->name('export');
    });

    // Promotions
    Route::prefix('promotions')->name('promotions.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\PromotionController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\PromotionController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\PromotionController::class, 'store'])->name('store');
        Route::get('/{promotion}', [\App\Http\Controllers\Admin\PromotionController::class, 'show'])->name('show');
        Route::get('/{promotion}/edit', [\App\Http\Controllers\Admin\PromotionController::class, 'edit'])->name('edit');
        Route::put('/{promotion}', [\App\Http\Controllers\Admin\PromotionController::class, 'update'])->name('update');
        Route::delete('/{promotion}', [\App\Http\Controllers\Admin\PromotionController::class, 'destroy'])->name('destroy');
    });

    // Payment Methods
    Route::prefix('payment-methods')->name('payment-methods.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\PaymentMethodController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\PaymentMethodController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\PaymentMethodController::class, 'store'])->name('store');
        Route::get('/{paymentMethod}/edit', [\App\Http\Controllers\Admin\PaymentMethodController::class, 'edit'])->name('edit');
        Route::put('/{paymentMethod}', [\App\Http\Controllers\Admin\PaymentMethodController::class, 'update'])->name('update');
        Route::delete('/{paymentMethod}', [\App\Http\Controllers\Admin\PaymentMethodController::class, 'destroy'])->name('destroy');
        Route::patch('/{paymentMethod}/toggle', [\App\Http\Controllers\Admin\PaymentMethodController::class, 'toggleActive'])->name('toggle');
    });

    // Finance Options
    Route::prefix('finance-options')->name('finance-options.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\FinanceOptionController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\FinanceOptionController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\FinanceOptionController::class, 'store'])->name('store');
        Route::get('/{financeOption}/edit', [\App\Http\Controllers\Admin\FinanceOptionController::class, 'edit'])->name('edit');
        Route::put('/{financeOption}', [\App\Http\Controllers\Admin\FinanceOptionController::class, 'update'])->name('update');
        Route::delete('/{financeOption}', [\App\Http\Controllers\Admin\FinanceOptionController::class, 'destroy'])->name('destroy');
        Route::patch('/{financeOption}/toggle', [\App\Http\Controllers\Admin\FinanceOptionController::class, 'toggleActive'])->name('toggle');
    });

    // Reviews
    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('index');
        Route::get('/{review}', [\App\Http\Controllers\Admin\ReviewController::class, 'show'])->name('show');
        Route::patch('/{review}/approve', [\App\Http\Controllers\Admin\ReviewController::class, 'approve'])->name('approve');
        Route::patch('/{review}/reject', [\App\Http\Controllers\Admin\ReviewController::class, 'reject'])->name('reject');
        Route::delete('/{review}', [\App\Http\Controllers\Admin\ReviewController::class, 'destroy'])->name('destroy');
    });

    // Contact Messages
    Route::prefix('contact-messages')->name('contact-messages.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ContactMessageController::class, 'index'])->name('index');
        Route::get('/{contactMessage}', [\App\Http\Controllers\Admin\ContactMessageController::class, 'show'])->name('show');
        Route::patch('/{contactMessage}/mark-read', [\App\Http\Controllers\Admin\ContactMessageController::class, 'markAsRead'])->name('mark-read');
        Route::delete('/{contactMessage}', [\App\Http\Controllers\Admin\ContactMessageController::class, 'destroy'])->name('destroy');
    });

    // Services
    Route::prefix('services')->name('services.')->group(function () {
        Route::get('/', [AdminServiceController::class, 'index'])->name('index');
        Route::get('/create', [AdminServiceController::class, 'create'])->name('create');
        Route::post('/', [AdminServiceController::class, 'store'])->name('store');
        Route::get('/{service}', [AdminServiceController::class, 'show'])->name('show');
        Route::get('/{service}/edit', [AdminServiceController::class, 'edit'])->name('edit');
        Route::put('/{service}', [AdminServiceController::class, 'update'])->name('update');
        Route::delete('/{service}', [AdminServiceController::class, 'destroy'])->name('destroy');
    });

    // Showrooms
    Route::prefix('showrooms')->name('showrooms.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ShowroomController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\ShowroomController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\ShowroomController::class, 'store'])->name('store');
        Route::get('/{showroom}', [\App\Http\Controllers\Admin\ShowroomController::class, 'show'])->name('show');
        Route::get('/{showroom}/edit', [\App\Http\Controllers\Admin\ShowroomController::class, 'edit'])->name('edit');
        Route::put('/{showroom}', [\App\Http\Controllers\Admin\ShowroomController::class, 'update'])->name('update');
        Route::delete('/{showroom}', [\App\Http\Controllers\Admin\ShowroomController::class, 'destroy'])->name('destroy');
    });

    // Dealerships
    Route::prefix('dealerships')->name('dealerships.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\DealershipController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\DealershipController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\DealershipController::class, 'store'])->name('store');
        Route::get('/{dealership}', [\App\Http\Controllers\Admin\DealershipController::class, 'show'])->name('show');
        Route::get('/{dealership}/edit', [\App\Http\Controllers\Admin\DealershipController::class, 'edit'])->name('edit');
        Route::put('/{dealership}', [\App\Http\Controllers\Admin\DealershipController::class, 'update'])->name('update');
        Route::delete('/{dealership}', [\App\Http\Controllers\Admin\DealershipController::class, 'destroy'])->name('destroy');
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
    // Notifications bulk delete (simpler & faster)
    Route::delete('/notifications', [\App\Http\Controllers\NotificationController::class, 'deleteAll'])->name('notifications.delete-all');
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
    Route::post('/{appointment}/rate', [App\Http\Controllers\User\ServiceAppointmentController::class, 'rate'])->whereNumber('appointment')->name('rate');

    // Wildcard routes with numeric constraint
    Route::get('/{appointment}', [App\Http\Controllers\User\ServiceAppointmentController::class, 'show'])->whereNumber('appointment')->name('show');
    Route::get('/{appointment}/edit', [App\Http\Controllers\User\ServiceAppointmentController::class, 'edit'])->whereNumber('appointment')->name('edit');
    Route::put('/{appointment}', [App\Http\Controllers\User\ServiceAppointmentController::class, 'update'])->whereNumber('appointment')->name('update');
    Route::put('/{appointment}/cancel', [App\Http\Controllers\User\ServiceAppointmentController::class, 'cancel'])->whereNumber('appointment')->name('cancel');
    Route::put('/{appointment}/reschedule', [App\Http\Controllers\User\ServiceAppointmentController::class, 'reschedule'])->whereNumber('appointment')->name('reschedule');
});


// User Address Book
Route::prefix('addresses')->name('user.addresses.')->middleware(['auth'])->group(function () {
    Route::get('/', [UserAddressController::class, 'index'])->name('index');
    Route::post('/', [UserAddressController::class, 'store'])->name('store');
    Route::put('/{address}', [UserAddressController::class, 'update'])->name('update');
    Route::delete('/{address}', [UserAddressController::class, 'destroy'])->name('destroy');
    Route::post('/{address}/default', [UserAddressController::class, 'setDefault'])->name('set-default');
});

// User Promotions Routes
Route::prefix('promotions')->name('user.promotions.')->group(function () {
    Route::get('/', [\App\Http\Controllers\User\PromotionController::class, 'index'])->name('index');
    Route::get('/{promotion}', [\App\Http\Controllers\User\PromotionController::class, 'show'])->name('show');
    Route::post('/validate-code', [\App\Http\Controllers\User\PromotionController::class, 'validatePromotion'])->name('validate-code');
    Route::post('/{promotion}/validate', [\App\Http\Controllers\User\PromotionController::class, 'validatePromotion'])->name('validate');
    Route::post('/{promotion}/apply', [\App\Http\Controllers\User\PromotionController::class, 'apply'])->name('apply')->middleware('auth');
    Route::get('/my/used', [\App\Http\Controllers\User\PromotionController::class, 'myPromotions'])->name('my-promotions')->middleware('auth');
});

// User Showrooms Routes
Route::prefix('showrooms')->name('user.showrooms.')->group(function () {
    Route::get('/', [\App\Http\Controllers\User\ShowroomController::class, 'index'])->name('index');
    Route::get('/map', [\App\Http\Controllers\User\ShowroomController::class, 'map'])->name('map');
    Route::get('/{showroom}', [\App\Http\Controllers\User\ShowroomController::class, 'show'])->name('show');
    Route::post('/{showroom}/contact', [\App\Http\Controllers\User\ShowroomController::class, 'contact'])->name('contact');
    Route::get('/{showroom}/directions', [\App\Http\Controllers\User\ShowroomController::class, 'directions'])->name('directions');
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
    Route::get('/items', [WishlistController::class, 'getItems'])->name('items'); // Lấy tất cả items wishlist
    Route::post('/migrate-session', [WishlistController::class, 'migrateSessionWishlist'])->name('migrate-session');
});

// --- Analytics routes (Admin & Manager only) ---
Route::middleware(['auth', 'staff'])->prefix('admin/analytics')->name('admin.analytics.')->group(function () {
    Route::get('/dashboard', [AnalyticsController::class, 'dashboard'])->name('dashboard');
    Route::get('/sales-report', [AnalyticsController::class, 'salesReport'])->name('sales-report');
    Route::get('/customer-analytics', [AnalyticsController::class, 'customerAnalytics'])->name('customer-analytics');
    Route::get('/staff-performance', [AnalyticsController::class, 'staffPerformance'])->name('staff-performance');
    Route::get('/export-report', [AnalyticsController::class, 'exportReport'])->name('export-report');
});
