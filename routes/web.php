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
use App\Http\Controllers\BlogController;
use App\Http\Controllers\RoleandaccessController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\CryptocurrencyController;
use App\Http\Controllers\BlogCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductAttributeController;
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
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\ProductFaqController;
use App\Http\Controllers\BlogReviewController;
use App\Http\Controllers\AudiencePreferenceController;
use App\Http\Controllers\Admin\AudiencePreferenceController as AdminAudiencePreferenceController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\TaxController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ContactController;

// ══════════════════════════════════════════════════════════════════════
//  PUBLIC FRONTEND ROUTES
// ══════════════════════════════════════════════════════════════════════

Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap.index');
Route::get('/sitemap-pages.xml', [\App\Http\Controllers\SitemapController::class, 'pages'])->name('sitemap.pages');
Route::get('/sitemap-products.xml', [\App\Http\Controllers\SitemapController::class, 'products'])->name('sitemap.products');
Route::get('/sitemap-blogs.xml', [\App\Http\Controllers\SitemapController::class, 'blogs'])->name('sitemap.blogs');
Route::view('/technology', 'ourtechnology');
Route::view('/about', 'about');
Route::view('/collaboration', 'ourcollaboration');
Route::view('/impact', 'fieldresult');
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:5,1')->name('contact.store');
Route::view('/bharat-biomer', 'bharat-biomer')->name('bharat-biomer');

Route::get('/', fn() => view('bharat-biomer'));
Route::get('/products', [ProductController::class, 'shopIndex'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'shopShow'])->name('products.show');


Route::get('/blogs', [BlogController::class, 'frontendIndex'])->name('frontend.blog.index');
Route::get('/blogs/{slug}', [BlogController::class, 'frontendDetails'])->name('frontend.blog.show');
Route::post('/audience-preference', [AudiencePreferenceController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('audience-preference.store');
// ── Dynamic Pages with SEO ────────────────────────────────────

// ══════════════════════════════════════════════════════════════════════
//  ADMIN AUTH ROUTES — public (no login required)
// ══════════════════════════════════════════════════════════════════════

Route::post('/webhooks/razorpay', [OrderController::class, 'razorpayWebhook'])
    ->middleware('throttle:60,1')
    ->name('webhooks.razorpay');
Route::post('/webhooks/cashfree', [OrderController::class, 'cashfreeWebhook'])
    ->middleware('throttle:60,1')
    ->name('webhooks.cashfree');

Route::get('/admin', fn() => redirect()->route('signin'));

Route::prefix('authentication')->controller(AuthenticationController::class)->group(function () {
    Route::get('/signin', 'signIn')->name('signin');
    Route::post('/login-action', 'loginProcess')->middleware('throttle:6,1')->name('login.action');
    Route::post('/signout', 'signOut')->name('signout');
});

// ══════════════════════════════════════════════════════════════════════
//  CUSTOMER AUTH ROUTES — public
// ══════════════════════════════════════════════════════════════════════

// Default login route (alias for Laravel's authentication redirects)
Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('login')->middleware('guest:customer');

Route::middleware('guest:customer')->group(function () {
    Route::post('/login', [CustomerAuthController::class, 'login'])->middleware('throttle:6,1')->name('customer.login.post');
    Route::get('/register', [CustomerAuthController::class, 'showRegister'])->name('customer.register');
    Route::post('/register', [CustomerAuthController::class, 'register'])->middleware('throttle:6,1')->name('customer.register.post');
});

// Alias for customer.login
Route::get('/customer/login', [CustomerAuthController::class, 'showLogin'])->name('customer.login')->middleware('guest:customer');

Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('customer.logout');

// Cart — session based, no login needed
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add')->middleware('throttle:30,1');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update')->middleware('throttle:30,1');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove')->middleware('throttle:30,1');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear')->middleware('throttle:10,1');
Route::post('/cart/coupon/apply', [CartController::class, 'applyCoupon'])->name('cart.coupon.apply')->middleware('throttle:10,1');
Route::post('/cart/coupon/remove', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove')->middleware('throttle:20,1');

// Customer checkout — customer login required
Route::middleware('customer.auth')->group(function () {
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/order/razorpay', [OrderController::class, 'createRazorpayOrder'])->name('order.razorpay');
    Route::post('/order/payment-success', [OrderController::class, 'paymentSuccess'])->name('order.payment.success');
    Route::post('/order/payment-failed', [OrderController::class, 'paymentFailed'])->name('order.payment.failed');
    Route::get('/order/{orderNumber}/success', [OrderController::class, 'success'])->name('order.success');
    Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('orders.index');
    Route::get('/my-orders/{orderNumber}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/order/cashfree', [OrderController::class, 'createCashfreeOrder'])->name('order.cashfree');
    Route::get('/order/cashfree/verify', [OrderController::class, 'verifyCashfreePayment'])
        ->name('order.cashfree.verify')
        ->middleware('throttle:10,1');
    Route::post('/order/cod', [OrderController::class, 'createCodOrder'])->name('order.cod');

    // ── Order Returns (customer login required) ──────────────────
    Route::get('/order-returns', [OrderReturnController::class, 'index'])->name('order-returns.index');
    Route::get('/order-returns/create/{orderNumber}', [OrderReturnController::class, 'create'])->name('order-returns.create');
    Route::post('/order-returns/store/{orderNumber}', [OrderReturnController::class, 'store'])->name('order-returns.store');
    Route::get('/order-returns/{id}', [OrderReturnController::class, 'show'])->name('order-returns.show');

    // ── My Account (customer login required) ───────────────────
    Route::get('/my-account', [CustomerDashboardController::class, 'account'])->name('customer.account');
    Route::get('/my-account/edit', [CustomerDashboardController::class, 'edit'])->name('customer.account.edit');
    Route::post('/my-account/update', [CustomerDashboardController::class, 'update'])->name('customer.account.update');

    // ── Wishlist (customer login required) ────────────────────

    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::get('/my-orders/{orderNumber}/invoice', [InvoiceController::class, 'downloadCustomer'])->name('orders.invoice');
    Route::post('/wishlist/remove', [WishlistController::class, 'remove'])->name('wishlist.remove');
    Route::get('/customer/dashboard', [CustomerDashboardController::class, 'index'])->name('customer.dashboard');

    // Product Reviews (customer submit)
    Route::post('/products/{product}/reviews', [ProductReviewController::class, 'store'])->name('reviews.store');

});

Route::middleware('customer.auth')->group(function () {
    Route::post('/blogs/{blog}/reviews', [BlogController::class, 'storeReview'])->name('frontend.blog.reviews.store');
});

// ══════════════════════════════════════════════════════════════════════
//  ADMIN PROTECTED ROUTES — all require admin login
// ══════════════════════════════════════════════════════════════════════

Route::middleware(['admin.auth'])->group(function () {

    // ── Dashboard Home ────────────────────────────────────────────────
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Misc Pages ────────────────────────────────────────────────────
    Route::controller(HomeController::class)->group(
        function () {
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
            Route::get('viewdetails', 'viewDetails')->name('viewDetails');
            Route::get('veiwdetails', 'veiwDetails')->name('veiwDetails');
            Route::get('blankPage', 'blankPage')->name('blankPage');
            Route::get('comingSoon', 'comingSoon')->name('comingSoon');
            Route::get('maintenance', 'maintenance')->name('maintenance');
            Route::get('starred', 'starred')->name('starred');
            Route::get('testimonials', 'testimonials')->name('testimonials');
        }
    );

    // ── AI Application ────────────────────────────────────────────────
    Route::prefix('aiapplication')->controller(AiapplicationController::class)->group(
        function () {
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
    Route::prefix('chart')->controller(ChartController::class)->group(
        function () {
            Route::get('/columnchart', 'columnChart')->name('columnChart');
            Route::get('/linechart', 'lineChart')->name('lineChart');
            Route::get('/piechart', 'pieChart')->name('pieChart');
        }
    );

    // ── Components ────────────────────────────────────────────────────
    Route::prefix('componentspage')->controller(ComponentpageController::class)->group(
        function () {
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
    Route::prefix('forms')->controller(FormsController::class)->group(
        function () {
            Route::get('/form-layout', 'formLayout')->name('formLayout');
            Route::get('/form-validation', 'formValidation')->name('formValidation');
            Route::get('/form', 'form')->name('form');
            Route::get('/wizard', 'wizard')->name('wizard');
        }
    );

    // ── Invoice ───────────────────────────────────────────────────────
    Route::prefix('invoice')->controller(InvoiceController::class)->group(
        function () {
            Route::get('/invoice-add', 'invoiceAdd')->name('invoiceAdd');
            Route::get('/invoice-edit/{orderNumber?}', 'invoiceEdit')->name('invoiceEdit');
            Route::get('/invoice-list', 'invoiceList')->name('invoiceList');
            Route::get('/invoice-preview/{orderNumber?}', 'invoicePreview')->name('invoicePreview');
        }
    );

    // ── Settings ──────────────────────────────────────────────────────
    Route::prefix('settings')->controller(SettingsController::class)->middleware('permission:view settings')->group(
        function () {
            Route::get('/company', 'company')->name('company');
            Route::get('/currencies', 'currencies')->name('currencies');
            Route::get('/notification', 'notification')->name('notification');
            Route::get('/notification-alert', 'notificationAlert')->name('notificationAlert');
            Route::get('/payment-gateway', 'paymentGateway')->name('paymentGateway');
            Route::post('/payment-gateway', 'updatePaymentGateway')->middleware('permission:edit settings')->name('paymentGateway.update');
            Route::post('/payment-gateway/status', 'updatePaymentGatewayStatus')->middleware('permission:edit settings')->name('paymentGateway.status');
            Route::get('/theme', 'theme')->name('theme');
        }
    );

    // ── Tables ────────────────────────────────────────────────────────
    Route::prefix('table')->controller(TableController::class)->group(
        function () {
            Route::get('/tablebasic', 'tableBasic')->name('tableBasic');
            Route::get('/tabledata', 'tableData')->name('tableData');
        }
    );

    // ── Users ─────────────────────────────────────────────────────────
    Route::prefix('users')->controller(UsersController::class)->middleware('permission:view roles')->group(function () {
        Route::get('/list', 'usersList')->name('usersList');
        Route::get('/add', 'addUser')->middleware('permission:create roles')->name('addUser');
        Route::post('/store', 'storeUser')->middleware('permission:create roles')->name('users.store');
        Route::delete('/{id}', 'destroyUser')->middleware('permission:delete roles')->name('users.destroy');
    });
    // ── Blog ──────────────────────────────────────────────────────────
    Route::prefix('blog')->controller(BlogController::class)->middleware('permission:view blogs')->group(
        function () {
            Route::get('/', 'blog')->name('blog');
            Route::get('/addBlog', 'addBlog')->middleware('permission:create blogs')->name('addBlog');
            Route::post('/storeBlog', 'storeBlog')->middleware('permission:create blogs')->name('storeBlog');
            Route::get('/editBlog/{blog}', 'editBlog')->middleware('permission:edit blogs')->name('editBlog');
            Route::post('/updateBlog/{blog}', 'updateBlog')->middleware('permission:edit blogs')->name('updateBlog');
            Route::post('/destroyBlog/{blog}', 'destroyBlog')->middleware('permission:delete blogs')->name('destroyBlog');
            Route::get('/blogDetails/{blog}', 'blogDetails')->name('blogDetails');
        }
    );

    // ── Blog Categories ───────────────────────────────────────────────
    Route::prefix('blog')->middleware('permission:view blogs')->group(
        function () {
            Route::get('/categories', [BlogCategoryController::class, 'index'])->name('blog-categories.index');
            Route::post('/categories', [BlogCategoryController::class, 'store'])->middleware('permission:create blogs')->name('blog-categories.store');
            Route::post('/categories/{category}/update', [BlogCategoryController::class, 'update'])->middleware('permission:edit blogs')->name('blog-categories.update');
            Route::post('/categories/{category}/delete', [BlogCategoryController::class, 'destroy'])->middleware('permission:delete blogs')->name('blog-categories.destroy');
        }
    );

    // ── Role & Access ─────────────────────────────────────────────────
    Route::prefix('roleandaccess')->controller(RoleandaccessController::class)->middleware('permission:view roles')->group(
        function () {
            Route::get('/roleAaccess', 'roleAaccess')->name('roleAaccess');
            Route::post('/roles', 'storeRole')->middleware('permission:create roles')->name('roleandaccess.store');
            Route::get('/roles/{id}/edit', 'editRole')->middleware('permission:edit roles')->name('roleandaccess.edit');
            Route::put('/roles/{id}', 'updateRole')->middleware('permission:edit roles')->name('roleandaccess.update');
            Route::delete('/roles/{id}', 'destroyRole')->middleware('permission:delete roles')->name('roleandaccess.destroy');
            Route::get('/assignRole', 'assignRole')->name('assignRole');
            Route::post('/assignRole', 'updateUserRole')->middleware('permission:edit roles')->name('roleandaccess.assign');
        }
    );

    // ── Cryptocurrency ────────────────────────────────────────────────
    Route::prefix('cryptocurrency')->controller(CryptocurrencyController::class)->group(
        function () {
            Route::get('/marketplace', 'marketplace')->name('marketplace');
            Route::get('/marketplacedetails', 'marketplaceDetails')->name('marketplaceDetails');
            Route::get('/portfolio', 'portfolio')->name('portfolio');
            Route::get('/wallet', 'wallet')->name('wallet');
        }
    );
    Route::get('/dashboard/invoices', [InvoiceController::class, 'index'])->middleware('permission:view orders')->name('dashboard.invoices.index');
    // Admin invoice inside middleware(['admin.auth']) group
    Route::get(
        '/dashboard/orders/{orderNumber}/invoice',
        [InvoiceController::class, 'downloadAdmin']
    )->middleware(['role:super-admin', 'signed', 'throttle:5,1'])->name('dashboard.orders.invoice');

    // ── Dashboard ─────────────────────────────────────────────────────
    Route::prefix('dashboard')->group(
        function () {

            // Main
            Route::get('/index', [DashboardController::class, 'index'])->name('index');
            Route::get('/analytics', [DashboardController::class, 'analytics'])
                ->middleware('permission:view products|view orders|view customers')
                ->name('dashboard.analytics');
            Route::get('/analytics/export', [DashboardController::class, 'exportAnalytics'])
                ->middleware(['role:super-admin', 'throttle:5,60'])
                ->name('dashboard.analytics.export');

            // Customers
            Route::middleware('permission:view customers')->group(function () {
                Route::get('/customers', [AdminCustomerController::class, 'index'])->name('dashboard.customers.index');
                Route::get('/customers/{id}', [AdminCustomerController::class, 'show'])->name('dashboard.customers.show');
                Route::get('audience-preferences', [AdminAudiencePreferenceController::class, 'index'])->name('dashboard.audience-preferences.index');
            });

            // Orders
            Route::middleware('permission:view orders')->group(function () {
                Route::get('/orders', [AdminOrderController::class, 'index'])->name('dashboard.orders.index');
                Route::get('/orders/{orderNumber}', [AdminOrderController::class, 'show'])->name('dashboard.orders.show');
                Route::patch('/orders/{orderNumber}/status', [AdminOrderController::class, 'updateStatus'])->middleware('permission:edit orders')->name('dashboard.orders.updateStatus');
                Route::get('/returns', [AdminOrderReturnController::class, 'index'])->name('dashboard.returns.index');
                Route::get('/returns/{id}', [AdminOrderReturnController::class, 'show'])->name('dashboard.returns.show');
                Route::post('/returns/{id}', [AdminOrderReturnController::class, 'update'])->middleware('permission:edit orders')->name('dashboard.returns.update');
            });

            // Products
            Route::middleware('permission:view products')->group(function () {
                Route::resource('products', ProductController::class)->names([
                    'index' => 'dashboard.products.index',
                    'create' => 'dashboard.products.create',
                    'store' => 'dashboard.products.store',
                    'show' => 'dashboard.products.show',
                    'edit' => 'dashboard.products.edit',
                    'update' => 'dashboard.products.update',
                    'destroy' => 'dashboard.products.destroy',
                ])->middlewareFor(['create', 'store'], 'permission:create products')
                    ->middlewareFor(['edit', 'update'], 'permission:edit products')
                    ->middlewareFor('destroy', 'permission:delete products');
                Route::delete('product-images/{image}', [ProductController::class, 'destroyImage'])->middleware('permission:edit products')->name('dashboard.products.destroyImage');
                Route::delete('products/{product}/featured-image', [ProductController::class, 'destroyFeaturedImage'])->middleware('permission:edit products')->name('dashboard.products.destroyFeaturedImage');
                Route::delete('product-variations/{variation}', [ProductController::class, 'destroyVariation'])->middleware('permission:edit products')->name('dashboard.products.destroyVariation');
                Route::resource('attributes', ProductAttributeController::class)
                    ->only(['index', 'store', 'update', 'destroy'])
                    ->parameters(['attributes' => 'attribute'])
                    ->names([
                        'index' => 'dashboard.attributes.index',
                        'store' => 'dashboard.attributes.store',
                        'update' => 'dashboard.attributes.update',
                        'destroy' => 'dashboard.attributes.destroy',
                    ])->middlewareFor('store', 'permission:create products')
                        ->middlewareFor('update', 'permission:edit products')
                        ->middlewareFor('destroy', 'permission:delete products');

                // Product Variations
                Route::prefix('products/{product}/variations')->name('dashboard.products.variations.')->group(function () {
                    Route::get('/', [ProductVariationController::class, 'index'])->name('index');
                    Route::post('/attributes', [ProductVariationController::class, 'storeAttribute'])->middleware('permission:edit products')->name('attributes.store');
                    Route::get('/create', [ProductVariationController::class, 'create'])->middleware('permission:create products')->name('create');
                    Route::post('/', [ProductVariationController::class, 'store'])->middleware('permission:create products')->name('store');
                    Route::get('/{variation}/edit', [ProductVariationController::class, 'edit'])->middleware('permission:edit products')->name('edit');
                    Route::put('/{variation}', [ProductVariationController::class, 'update'])->middleware('permission:edit products')->name('update');
                    Route::delete('/{variation}', [ProductVariationController::class, 'destroy'])->middleware('permission:delete products')->name('destroy');
                });
                Route::post('product-variations/{variation}/toggle', [ProductVariationController::class, 'toggleStatus'])->middleware('permission:edit products')->name('dashboard.variations.toggle');

                // Brands
                Route::resource('brands', BrandController::class)->names([
                    'index' => 'dashboard.brands.index',
                    'create' => 'dashboard.brands.create',
                    'store' => 'dashboard.brands.store',
                    'show' => 'dashboard.brands.show',
                    'edit' => 'dashboard.brands.edit',
                    'update' => 'dashboard.brands.update',
                    'destroy' => 'dashboard.brands.destroy',
                ])->middlewareFor(['create', 'store'], 'permission:create products')
                    ->middlewareFor(['edit', 'update'], 'permission:edit products')
                    ->middlewareFor('destroy', 'permission:delete products');

                // Categories
                Route::resource('categories', CategoryController::class)->names([
                    'index' => 'dashboard.categories.index',
                    'create' => 'dashboard.categories.create',
                    'store' => 'dashboard.categories.store',
                    'show' => 'dashboard.categories.show',
                    'edit' => 'dashboard.categories.edit',
                    'update' => 'dashboard.categories.update',
                    'destroy' => 'dashboard.categories.destroy',
                ])->middlewareFor(['create', 'store'], 'permission:create products')
                    ->middlewareFor(['edit', 'update'], 'permission:edit products')
                    ->middlewareFor('destroy', 'permission:delete products');

                // Tags
                Route::resource('tags', TagController::class)->names([
                    'index' => 'dashboard.tags.index',
                    'create' => 'dashboard.tags.create',
                    'store' => 'dashboard.tags.store',
                    'show' => 'dashboard.tags.show',
                    'edit' => 'dashboard.tags.edit',
                    'update' => 'dashboard.tags.update',
                    'destroy' => 'dashboard.tags.destroy',
                ])->middlewareFor(['create', 'store'], 'permission:create products')
                    ->middlewareFor(['edit', 'update'], 'permission:edit products')
                    ->middlewareFor('destroy', 'permission:delete products');
                Route::get('tags-search', [TagController::class, 'search'])->name('dashboard.tags.search');
                Route::post('tags/bulk-store', [TagController::class, 'bulkStore'])->middleware('permission:create products')->name('dashboard.tags.bulkStore');

                // Coupons
                Route::resource('coupons', \App\Http\Controllers\CouponController::class)->names([
                    'index' => 'dashboard.coupons.index',
                    'create' => 'dashboard.coupons.create',
                    'store' => 'dashboard.coupons.store',
                    'show' => 'dashboard.coupons.show',
                    'edit' => 'dashboard.coupons.edit',
                    'update' => 'dashboard.coupons.update',
                    'destroy' => 'dashboard.coupons.destroy',
                ])->middlewareFor(['create', 'store'], 'permission:create products')
                    ->middlewareFor(['edit', 'update'], 'permission:edit products')
                    ->middlewareFor('destroy', 'permission:delete products');

                // Product Reviews (admin)
                Route::get('reviews', [ProductReviewController::class, 'index'])->name('dashboard.reviews.index');
                Route::post('reviews/{review}/approve', [ProductReviewController::class, 'approve'])->middleware('permission:edit products')->name('dashboard.reviews.approve');
                Route::post('reviews/{review}/reject', [ProductReviewController::class, 'reject'])->middleware('permission:edit products')->name('dashboard.reviews.reject');
                Route::delete('reviews/{review}', [ProductReviewController::class, 'destroy'])->middleware('permission:delete products')->name('dashboard.reviews.destroy');

                // Product FAQs (admin)
                Route::post('products/{product}/faqs', [ProductFaqController::class, 'store'])->middleware('permission:edit products')->name('dashboard.products.faqs.store');
                Route::put('products/faqs/{faq}', [ProductFaqController::class, 'update'])->middleware('permission:edit products')->name('dashboard.products.faqs.update');
                Route::delete('products/faqs/{faq}', [ProductFaqController::class, 'destroy'])->middleware('permission:delete products')->name('dashboard.products.faqs.destroy');

                // Taxes (GST)
                Route::get('taxes', [TaxController::class, 'index'])->name('dashboard.taxes.index');
            });

            // Blog Reviews
            Route::middleware('permission:view blogs')->group(function () {
                Route::get('blog-reviews', [BlogReviewController::class, 'index'])->name('dashboard.blog-reviews.index');
                Route::post('blog-reviews/{review}/approve', [BlogReviewController::class, 'approve'])->middleware('permission:edit blogs')->name('dashboard.blog-reviews.approve');
                Route::post('blog-reviews/{review}/reject', [BlogReviewController::class, 'reject'])->middleware('permission:edit blogs')->name('dashboard.blog-reviews.reject');
                Route::delete('blog-reviews/{review}', [BlogReviewController::class, 'destroy'])->middleware('permission:delete blogs')->name('dashboard.blog-reviews.destroy');
            });

            // Settings & Pages
            Route::middleware('permission:view settings')->group(function () {
                Route::resource('pages', \App\Http\Controllers\Admin\PageController::class)->names([
                    'index' => 'dashboard.pages.index',
                    'create' => 'dashboard.pages.create',
                    'store' => 'dashboard.pages.store',
                    'show' => 'dashboard.pages.show',
                    'edit' => 'dashboard.pages.edit',
                    'update' => 'dashboard.pages.update',
                    'destroy' => 'dashboard.pages.destroy',
                ])->middlewareFor(['create', 'store', 'edit', 'update', 'destroy'], 'permission:edit settings');

                // Site Settings (Settings → Site Settings)
                Route::get('home-page', [\App\Http\Controllers\Admin\HomePageSettingController::class, 'edit'])->name('dashboard.home-page.edit');
                Route::post('home-page', [\App\Http\Controllers\Admin\HomePageSettingController::class, 'update'])->middleware('permission:edit settings')->name('dashboard.home-page.update');
                Route::get('site-settings', [\App\Http\Controllers\Admin\SiteSettingController::class, 'edit'])->name('dashboard.site-settings.edit');
                Route::post('site-settings', [\App\Http\Controllers\Admin\SiteSettingController::class, 'update'])->middleware('permission:edit settings')->name('dashboard.site-settings.update');
                Route::get('google-analytics', [\App\Http\Controllers\Admin\SiteSettingController::class, 'editAnalytics'])->name('dashboard.google-analytics.edit');
                Route::post('google-analytics', [\App\Http\Controllers\Admin\SiteSettingController::class, 'updateAnalytics'])->middleware('permission:edit settings')->name('dashboard.google-analytics.update');

                // Header Links (Settings → Header Links)
                Route::resource('header-links', \App\Http\Controllers\Admin\HeaderLinkController::class)->names([
                    'index' => 'dashboard.header-links.index',
                    'create' => 'dashboard.header-links.create',
                    'store' => 'dashboard.header-links.store',
                    'edit' => 'dashboard.header-links.edit',
                    'update' => 'dashboard.header-links.update',
                    'destroy' => 'dashboard.header-links.destroy',
                ])->middlewareFor(['create', 'store', 'edit', 'update', 'destroy'], 'permission:edit settings');

                // Footer Links (Settings → Footer Links)
                Route::resource('footer-links', \App\Http\Controllers\Admin\FooterLinkController::class)->names([
                    'index' => 'dashboard.footer-links.index',
                    'create' => 'dashboard.footer-links.create',
                    'store' => 'dashboard.footer-links.store',
                    'edit' => 'dashboard.footer-links.edit',
                    'update' => 'dashboard.footer-links.update',
                    'destroy' => 'dashboard.footer-links.destroy',
                ])->middlewareFor(['create', 'store', 'edit', 'update', 'destroy'], 'permission:edit settings');
            });
        }
    );
});

// Static policy pages
Route::view('/terms-and-conditions', 'policies.terms')->name('policy.terms');
Route::view('/privacy-policy', 'policies.privacy')->name('policy.privacy');
Route::view('/shipping-policy', 'policies.shipping')->name('policy.shipping');
Route::view('/return-policy', 'policies.return')->name('policy.return');

// Dynamic pages catch-all
// Keep this route last so it doesn't override explicit routes above.
Route::get('/{page:slug}', [PageController::class, 'show'])->name('pages.show');
