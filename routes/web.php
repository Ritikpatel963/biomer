<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AiapplicationController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\ComponentpageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FormsController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\RoleandaccessController;
use App\Http\Controllers\CryptocurrencyController;
use App\Http\Controllers\BlogCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductVariationController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderReturnController;
use App\Http\Controllers\AdminCustomerController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AdminOrderReturnController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\PageController;

// ══════════════════════════════════════════════════════════════════════
//  PUBLIC FRONTEND ROUTES
// ══════════════════════════════════════════════════════════════════════

Route::view('/technology', 'ourtechnology');
Route::view('/about', 'about');
Route::view('/collaboration', 'ourcollaboration');
Route::view('/impact', 'fieldresult');
Route::view('/contact', 'contact');

Route::get('/', fn() => view('index'));
Route::get('/products', [ProductController::class , 'shopIndex'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class , 'shopShow'])->name('products.show');

// ── Dynamic Pages with SEO ────────────────────────────────────

// ══════════════════════════════════════════════════════════════════════
//  ADMIN AUTH ROUTES — public (no login required)
// ══════════════════════════════════════════════════════════════════════

Route::get('/admin', fn() => redirect()->route('signin'));

Route::prefix('authentication')->controller(AuthenticationController::class)->group(function () {
    Route::get('/signin', 'signIn')->name('signin');
    Route::post('/login-action', 'loginProcess')->name('login.action');
    Route::post('/signout', 'signOut')->name('signout');
});

// ══════════════════════════════════════════════════════════════════════
//  CUSTOMER AUTH ROUTES — public
// ══════════════════════════════════════════════════════════════════════

// Default login route (alias for Laravel's authentication redirects)
Route::get('/login', [CustomerAuthController::class , 'showLogin'])->name('login')->middleware('guest:customer');

Route::middleware('guest:customer')->group(function () {
    Route::post('/login', [CustomerAuthController::class , 'login'])->name('customer.login.post');
    Route::get('/register', [CustomerAuthController::class , 'showRegister'])->name('customer.register');
    Route::post('/register', [CustomerAuthController::class , 'register'])->name('customer.register.post');
});

// Alias for customer.login
Route::get('/customer/login', [CustomerAuthController::class , 'showLogin'])->name('customer.login')->middleware('guest:customer');

Route::post('/logout', [CustomerAuthController::class , 'logout'])->name('customer.logout');

// Cart — session based, no login needed
Route::get('/cart', [CartController::class , 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class , 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class , 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class , 'remove'])->name('cart.remove');
Route::get('/cart/clear', [CartController::class , 'clear'])->name('cart.clear');
Route::post('/cart/coupon/apply', [CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
Route::post('/cart/coupon/remove', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');

// Customer checkout — customer login required
Route::middleware('customer.auth')->group(function () {
    Route::get('/checkout', [OrderController::class , 'checkout'])->name('checkout');
    Route::post('/order/razorpay', [OrderController::class , 'createRazorpayOrder'])->name('order.razorpay');
    Route::post('/order/payment-success', [OrderController::class , 'paymentSuccess'])->name('order.payment.success');
    Route::post('/order/payment-failed', [OrderController::class , 'paymentFailed'])->name('order.payment.failed');
    Route::get('/order/{orderNumber}/success', [OrderController::class , 'success'])->name('order.success');
    Route::get('/my-orders', [OrderController::class , 'myOrders'])->name('orders.index');
    Route::get('/my-orders/{orderNumber}', [OrderController::class , 'show'])->name('orders.show');
    Route::post('/order/cashfree', [OrderController::class , 'createCashfreeOrder'])->name('order.cashfree');
    Route::get('/order/cashfree/verify', [OrderController::class , 'verifyCashfreePayment'])->name('order.cashfree.verify');
    Route::post('/order/cod', [OrderController::class , 'createCodOrder'])->name('order.cod');

    // ── Order Returns (customer login required) ──────────────────
    Route::get('/order-returns', [OrderReturnController::class, 'index'])->name('order-returns.index');
    Route::get('/order-returns/create/{orderNumber}', [OrderReturnController::class, 'create'])->name('order-returns.create');
    Route::get('/order-returns/store/{orderNumber}', fn ($orderNumber) => redirect()->route('order-returns.create', $orderNumber));
    Route::post('/order-returns/store/{orderNumber}', [OrderReturnController::class, 'store'])->name('order-returns.store');
    Route::get('/order-returns/{id}', [OrderReturnController::class, 'show'])->name('order-returns.show');

    // ── My Account (customer login required) ───────────────────
    Route::get('/my-account', [CustomerDashboardController::class, 'account'])->name('customer.account');
    Route::get('/my-account/edit', [CustomerDashboardController::class, 'edit'])->name('customer.account.edit');
    Route::post('/my-account/update', [CustomerDashboardController::class, 'update'])->name('customer.account.update');

    // ── Wishlist (customer login required) ────────────────────

    Route::get('/wishlist', [WishlistController::class , 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle', [WishlistController::class , 'toggle'])->name('wishlist.toggle');
    Route::post('/wishlist/remove', [WishlistController::class , 'remove'])->name('wishlist.remove');
    Route::get('/customer/dashboard', [CustomerDashboardController::class , 'index'])->name('customer.dashboard');

    // Product Reviews (customer submit)
    Route::post('/products/{product}/reviews', [ProductReviewController::class, 'store'])->name('reviews.store');

});

// ══════════════════════════════════════════════════════════════════════
//  ADMIN PROTECTED ROUTES — all require admin login
// ══════════════════════════════════════════════════════════════════════

Route::middleware(['auth'])->group(function () {

    // ── Dashboard Home ────────────────────────────────────────────────
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    // ── Misc Pages ────────────────────────────────────────────────────
    Route::controller(HomeController::class)->group(function () {
            Route::get('calendar', 'calendar')->name('calendar');
            Route::get('chatmessage', 'chatMessage')->name('chatMessage');
            Route::get('chatempty', 'chatempty')->name('chatempty');
            Route::get('email', 'email')->name('email');
            Route::get('error', 'error1')->name('error');
            Route::get('faq', 'faq')->name('faq');
            Route::get('gallery', 'gallery')->name('gallery');
            Route::get('kanban', 'kanban')->name('kanban');
            Route::get('pricing', 'pricing')->name('pricing');
            Route::get('termscondition', 'termsCondition')->name('termsCondition');
            Route::get('widgets', 'widgets')->name('widgets');
            Route::get('chatprofile', 'chatProfile')->name('chatProfile');
            Route::get('veiwdetails', 'veiwDetails')->name('veiwDetails');
            Route::get('blankPage', 'blankPage')->name('blankPage');
            Route::get('comingSoon', 'comingSoon')->name('comingSoon');
            Route::get('maintenance', 'maintenance')->name('maintenance');
            Route::get('starred', 'starred')->name('starred');
            Route::get('testimonials', 'testimonials')->name('testimonials');
        }
        );

        // ── AI Application ────────────────────────────────────────────────
        Route::prefix('aiapplication')->controller(AiapplicationController::class)->group(function () {
            Route::get('/codegenerator', 'codeGenerator')->name('codeGenerator');
            Route::get('/codegeneratornew', 'codeGeneratorNew')->name('codeGeneratorNew');
            Route::get('/imagegenerator', 'imageGenerator')->name('imageGenerator');
            Route::get('/textgeneratornew', 'textGeneratorNew')->name('textGeneratorNew');
            Route::get('/textgenerator', 'textGenerator')->name('textGenerator');
            Route::get('/videogenerator', 'videoGenerator')->name('videoGenerator');
            Route::get('/voicegenerator', 'voiceGenerator')->name('voiceGenerator');
        }
        );

        // ── Charts ────────────────────────────────────────────────────────
        Route::prefix('chart')->controller(ChartController::class)->group(function () {
            Route::get('/columnchart', 'columnChart')->name('columnChart');
            Route::get('/linechart', 'lineChart')->name('lineChart');
            Route::get('/piechart', 'pieChart')->name('pieChart');
        }
        );

        // ── Components ────────────────────────────────────────────────────
        Route::prefix('componentspage')->controller(ComponentpageController::class)->group(function () {
            Route::get('/alert', 'alert')->name('alert');
            Route::get('/avatar', 'avatar')->name('avatar');
            Route::get('/badges', 'badges')->name('badges');
            Route::get('/button', 'button')->name('button');
            Route::get('/card', 'card')->name('card');
            Route::get('/carousel', 'carousel')->name('carousel');
            Route::get('/colors', 'colors')->name('colors');
            Route::get('/dropdown', 'dropdown')->name('dropdown');
            Route::get('/imageupload', 'imageUpload')->name('imageUpload');
            Route::get('/list', 'list')->name('list');
            Route::get('/pagination', 'pagination')->name('pagination');
            Route::get('/progress', 'progress')->name('progress');
            Route::get('/radio', 'radio')->name('radio');
            Route::get('/starrating', 'starRating')->name('starRating');
            Route::get('/switch', 'switch')->name('switch');
            Route::get('/tabs', 'tabs')->name('tabs');
            Route::get('/tags', 'tags')->name('tags');
            Route::get('/tooltip', 'tooltip')->name('tooltip');
            Route::get('/typography', 'typography')->name('typography');
            Route::get('/videos', 'videos')->name('videos');
        }
        );

        // ── Forms ─────────────────────────────────────────────────────────
        Route::prefix('forms')->controller(FormsController::class)->group(function () {
            Route::get('/form-layout', 'formLayout')->name('formLayout');
            Route::get('/form-validation', 'formValidation')->name('formValidation');
            Route::get('/form', 'form')->name('form');
            Route::get('/wizard', 'wizard')->name('wizard');
        }
        );

        // ── Invoice ───────────────────────────────────────────────────────
        Route::prefix('invoice')->controller(InvoiceController::class)->group(function () {
            Route::get('/invoice-add', 'invoiceAdd')->name('invoiceAdd');
            Route::get('/invoice-edit', 'invoiceEdit')->name('invoiceEdit');
            Route::get('/invoice-list', 'invoiceList')->name('invoiceList');
            Route::get('/invoice-preview', 'invoicePreview')->name('invoicePreview');
        }
        );

        // ── Settings ──────────────────────────────────────────────────────
        Route::prefix('settings')->controller(SettingsController::class)->group(function () {
            Route::get('/company', 'company')->name('company');
            Route::get('/currencies', 'currencies')->name('currencies');
            Route::get('/language', 'language')->name('language');
            Route::get('/notification', 'notification')->name('notification');
            Route::get('/notification-alert', 'notificationAlert')->name('notificationAlert');
            Route::get('/payment-gateway', 'paymentGateway')->name('paymentGateway');
            Route::post('/payment-gateway', 'updatePaymentGateway')->name('paymentGateway.update');
            Route::get('/theme', 'theme')->name('theme');
        }
        );

        // ── Tables ────────────────────────────────────────────────────────
        Route::prefix('table')->controller(TableController::class)->group(function () {
            Route::get('/tablebasic', 'tableBasic')->name('tableBasic');
            Route::get('/tabledata', 'tableData')->name('tableData');
        }
        );

        // ── Users ─────────────────────────────────────────────────────────
        Route::prefix('users')->controller(UsersController::class)->group(function () {
            Route::get('/add-user', 'addUser')->name('addUser');
            Route::get('/users-grid', 'usersGrid')->name('usersGrid');
            Route::get('/users-list', 'usersList')->name('usersList');
            Route::get('/view-profile', 'viewProfile')->name('viewProfile');
        }
        );

        // ── Blog ──────────────────────────────────────────────────────────
        Route::prefix('blog')->controller(BlogController::class)->group(function () {
            Route::get('/', 'blog')->name('blog');
            Route::get('/addBlog', 'addBlog')->name('addBlog');
            Route::post('/storeBlog', 'storeBlog')->name('storeBlog');
            Route::get('/editBlog/{blog}', 'editBlog')->name('editBlog');
            Route::post('/updateBlog/{blog}', 'updateBlog')->name('updateBlog');
            Route::post('/destroyBlog/{blog}', 'destroyBlog')->name('destroyBlog');
            Route::get('/blogDetails/{blog}', 'blogDetails')->name('blogDetails');
        }
        );

        // ── Blog Categories ───────────────────────────────────────────────
        Route::prefix('blog')->group(function () {
            Route::get('/categories', [BlogCategoryController::class , 'index'])->name('blog-categories.index');
            Route::post('/categories', [BlogCategoryController::class , 'store'])->name('blog-categories.store');
            Route::post('/categories/{category}/update', [BlogCategoryController::class , 'update'])->name('blog-categories.update');
            Route::post('/categories/{category}/delete', [BlogCategoryController::class , 'destroy'])->name('blog-categories.destroy');
        }
        );

        // ── Role & Access ─────────────────────────────────────────────────
        Route::prefix('roleandaccess')->controller(RoleandaccessController::class)->group(function () {
            Route::get('/assignRole', 'assignRole')->name('assignRole');
            Route::get('/roleAaccess', 'roleAaccess')->name('roleAaccess');
        }
        );

        // ── Cryptocurrency ────────────────────────────────────────────────
        Route::prefix('cryptocurrency')->controller(CryptocurrencyController::class)->group(function () {
            Route::get('/marketplace', 'marketplace')->name('marketplace');
            Route::get('/marketplacedetails', 'marketplaceDetails')->name('marketplaceDetails');
            Route::get('/portfolio', 'portfolio')->name('portfolio');
            Route::get('/wallet', 'wallet')->name('wallet');
        }
        );
        Route::get('/dashboard/invoices', [InvoiceController::class , 'index'])->name('dashboard.invoices.index');
        // Admin invoice — inside middleware(['admin.auth']) group
        Route::get(
            '/dashboard/orders/{orderNumber}/invoice',
        [InvoiceController::class , 'downloadAdmin']
        )
            ->name('dashboard.orders.invoice');

        // Customer invoice — inside middleware(['customer.auth']) group
        Route::get(
            '/my-orders/{orderNumber}/invoice',
        [InvoiceController::class , 'downloadCustomer']
        )
            ->name('orders.invoice'); // ← end auth middleware
    
        // ── Dashboard ─────────────────────────────────────────────────────
        Route::prefix('dashboard')->group(function () {

            // Main
            Route::get('/index', [DashboardController::class , 'index'])->name('index');

            // Customers
            Route::get('/customers', [AdminCustomerController::class , 'index'])->name('dashboard.customers.index');
            Route::get('/customers/{id}', [AdminCustomerController::class , 'show'])->name('dashboard.customers.show');

            // Orders
            Route::get('/orders', [AdminOrderController::class , 'index'])->name('dashboard.orders.index');
            Route::get('/orders/{orderNumber}', [AdminOrderController::class , 'show'])->name('dashboard.orders.show');
            Route::patch('/orders/{orderNumber}/status', [AdminOrderController::class , 'updateStatus'])->name('dashboard.orders.updateStatus');
            Route::get('/returns', [AdminOrderReturnController::class, 'index'])->name('dashboard.returns.index');
            Route::get('/returns/{id}', [AdminOrderReturnController::class, 'show'])->name('dashboard.returns.show');
            Route::post('/returns/{id}', [AdminOrderReturnController::class, 'update'])->name('dashboard.returns.update');
            //stock
    

            // Stock Management
            Route::get('/stock', [StockController::class , 'index'])->name('dashboard.stock.index');
            Route::get('/stock/edit', [StockController::class , 'edit'])->name('dashboard.stock.edit');
            Route::post('/stock/update', [StockController::class , 'update'])->name('dashboard.stock.update');
            // Products
            Route::resource('products', ProductController::class)->names([
                'index' => 'dashboard.products.index',
                'create' => 'dashboard.products.create',
                'store' => 'dashboard.products.store',
                'show' => 'dashboard.products.show',
                'edit' => 'dashboard.products.edit',
                'update' => 'dashboard.products.update',
                'destroy' => 'dashboard.products.destroy',
            ]);
            Route::delete('product-images/{image}', [ProductController::class , 'destroyImage'])->name('dashboard.products.destroyImage');
            Route::delete('product-variations/{variation}', [ProductController::class , 'destroyVariation'])->name('dashboard.products.destroyVariation');

            // Product Variations
            Route::prefix('products/{product}/variations')->name('dashboard.products.variations.')->group(function () {
                    Route::get('/', [ProductVariationController::class , 'index'])->name('index');
                    Route::get('/create', [ProductVariationController::class , 'create'])->name('create');
                    Route::post('/', [ProductVariationController::class , 'store'])->name('store');
                    Route::get('/{variation}/edit', [ProductVariationController::class , 'edit'])->name('edit');
                    Route::put('/{variation}', [ProductVariationController::class , 'update'])->name('update');
                    Route::delete('/{variation}', [ProductVariationController::class , 'destroy'])->name('destroy');
                }
                );
                Route::post('product-variations/{variation}/toggle', [ProductVariationController::class , 'toggleStatus'])->name('dashboard.variations.toggle');

                // Brands
                Route::resource('brands', BrandController::class)->names([
                    'index' => 'dashboard.brands.index',
                    'create' => 'dashboard.brands.create',
                    'store' => 'dashboard.brands.store',
                    'show' => 'dashboard.brands.show',
                    'edit' => 'dashboard.brands.edit',
                    'update' => 'dashboard.brands.update',
                    'destroy' => 'dashboard.brands.destroy',
                ]);

                // Categories
                Route::resource('categories', CategoryController::class)->names([
                    'index' => 'dashboard.categories.index',
                    'create' => 'dashboard.categories.create',
                    'store' => 'dashboard.categories.store',
                    'show' => 'dashboard.categories.show',
                    'edit' => 'dashboard.categories.edit',
                    'update' => 'dashboard.categories.update',
                    'destroy' => 'dashboard.categories.destroy',
                ]);

                // Tags
                Route::resource('tags', TagController::class)->names([
                    'index' => 'dashboard.tags.index',
                    'create' => 'dashboard.tags.create',
                    'store' => 'dashboard.tags.store',
                    'show' => 'dashboard.tags.show',
                    'edit' => 'dashboard.tags.edit',
                    'update' => 'dashboard.tags.update',
                    'destroy' => 'dashboard.tags.destroy',
                ]);
                Route::get('tags-search', [TagController::class , 'search'])->name('dashboard.tags.search');
                Route::post('tags/bulk-store', [TagController::class , 'bulkStore'])->name('dashboard.tags.bulkStore');

                // Coupons
                Route::resource('coupons', \App\Http\Controllers\CouponController::class)->names([
                    'index' => 'dashboard.coupons.index',
                    'create' => 'dashboard.coupons.create',
                    'store' => 'dashboard.coupons.store',
                    'show' => 'dashboard.coupons.show',
                    'edit' => 'dashboard.coupons.edit',
                    'update' => 'dashboard.coupons.update',
                    'destroy' => 'dashboard.coupons.destroy',
                ]);

                // Product Reviews (admin)
                Route::get('reviews', [ProductReviewController::class, 'index'])->name('dashboard.reviews.index');
                Route::post('reviews/{review}/approve', [ProductReviewController::class, 'approve'])->name('dashboard.reviews.approve');
                Route::post('reviews/{review}/reject', [ProductReviewController::class, 'reject'])->name('dashboard.reviews.reject');
                Route::delete('reviews/{review}', [ProductReviewController::class, 'destroy'])->name('dashboard.reviews.destroy');

                // Pages (Settings → Pages)
                Route::resource('pages', \App\Http\Controllers\Admin\PageController::class)->names([
                    'index' => 'dashboard.pages.index',
                    'create' => 'dashboard.pages.create',
                    'store' => 'dashboard.pages.store',
                    'show' => 'dashboard.pages.show',
                    'edit' => 'dashboard.pages.edit',
                    'update' => 'dashboard.pages.update',
                    'destroy' => 'dashboard.pages.destroy',
                ]);

                // Site Settings (Settings → Site Settings)
                Route::get('site-settings', [\App\Http\Controllers\Admin\SiteSettingController::class, 'edit'])->name('dashboard.site-settings.edit');
                Route::post('site-settings', [\App\Http\Controllers\Admin\SiteSettingController::class, 'update'])->name('dashboard.site-settings.update');

                // Header Links (Settings → Header Links)
                Route::resource('header-links', \App\Http\Controllers\Admin\HeaderLinkController::class)->names([
                    'index' => 'dashboard.header-links.index',
                    'create' => 'dashboard.header-links.create',
                    'store' => 'dashboard.header-links.store',
                    'edit' => 'dashboard.header-links.edit',
                    'update' => 'dashboard.header-links.update',
                    'destroy' => 'dashboard.header-links.destroy',
                ]);

                // Footer Links (Settings → Footer Links)
                Route::resource('footer-links', \App\Http\Controllers\Admin\FooterLinkController::class)->names([
                    'index' => 'dashboard.footer-links.index',
                    'create' => 'dashboard.footer-links.create',
                    'store' => 'dashboard.footer-links.store',
                    'edit' => 'dashboard.footer-links.edit',
                    'update' => 'dashboard.footer-links.update',
                    'destroy' => 'dashboard.footer-links.destroy',
                ]);
            }
            );
        });

// Dynamic pages catch-all
// Keep this route last so it doesn't override explicit routes above.
Route::get('/{page:slug}', [PageController::class, 'show'])->name('pages.show');


