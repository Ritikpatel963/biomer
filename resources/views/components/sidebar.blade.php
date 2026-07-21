@php
    $productsOpen = request()->routeIs('dashboard.products.*', 'dashboard.categories.*', 'dashboard.attributes.*', 'dashboard.brands.*', 'dashboard.tags.*', 'dashboard.variations.*');
    $couponsOpen = request()->routeIs('dashboard.coupons.*');
    $reviewsOpen = request()->routeIs('dashboard.reviews.*');
    $taxesOpen = request()->routeIs('dashboard.taxes.*');
    $ordersOpen = request()->routeIs('dashboard.orders.*', 'dashboard.returns.*');
    $customersOpen = request()->routeIs('dashboard.customers.*', 'dashboard.audience-preferences.*');
    $blogsOpen = request()->routeIs('blog', 'addBlog', 'editBlog', 'blogDetails', 'blog-categories.*', 'dashboard.blog-reviews.*');
    $rolesOpen = request()->routeIs('roleAaccess', 'assignRole', 'roleandaccess.*');
    $usersOpen = request()->routeIs('usersList', 'addUser');
    $settingsOpen = request()->routeIs('dashboard.home-page.*', 'dashboard.site-settings.*', 'dashboard.google-analytics.*', 'dashboard.header-links.*', 'dashboard.footer-links.*', 'dashboard.pages.*', 'paymentGateway');
@endphp

<aside class="sidebar">
    <button type="button" class="sidebar-close-btn">
        <i class="ri-close-line"></i>
    </button>
    <div>
        <a href="" class="sidebar-logo">
            <img src="{{ asset('assets/images/home-img/footer-logo.png') }}" alt="site logo" class="light-logo">
            <img src="{{ asset('assets/images/logo-light.png') }}" alt="site logo" class="dark-logo">
            <img src="{{ asset('assets/images/logo-icon.png') }}" alt="site logo" class="logo-icon">
        </a>
    </div>
    <div class="sidebar-menu-area">
        <ul class="sidebar-menu" id="sidebar-menu">
            <li class="{{ request()->routeIs('dashboard') ? 'active-page' : '' }}">
                <a href="{{ route('dashboard') }}">
                    <i class="ri-dashboard-line menu-icon"></i>
                    <span>Admin Dashboard</span>
                </a>
            </li>

            @canany(['view products', 'view orders', 'view customers'])
                <li class="{{ request()->routeIs('dashboard.analytics') ? 'active-page' : '' }}">
                    <a href="{{ route('dashboard.analytics') }}">
                        <i class="ri-bar-chart-grouped-line menu-icon"></i>
                        <span>Analytics</span>
                    </a>
                </li>
            @endcanany

            @can('view orders')
                <li class="{{ request()->routeIs('dashboard.invoices.index') ? 'active-page' : '' }}">
                    <a href="{{ route('dashboard.invoices.index') }}">
                        <i class="ri-file-list-3-line menu-icon"></i>
                        <span>Invoice</span>
                    </a>
                </li>
            @endcan

            @can('view products')
                <li class="dropdown {{ $productsOpen ? 'open' : '' }}">
                    <a href="javascript:void(0)">
                        <i class="ri-box-3-line menu-icon"></i>
                        <span>Products</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('dashboard.products.index') }}"><i
                                    class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> List</a>
                        </li>
                        <li>
                            <a href="{{ route('dashboard.categories.index') }}"><i
                                    class="ri-circle-fill circle-icon text-info-main w-auto"></i>Categories</a>
                        </li>
                        <li>
                            <a href="{{ route('dashboard.attributes.index') }}"><i
                                    class="ri-circle-fill circle-icon text-success-main w-auto"></i>Attributes</a>
                        </li>
                        <li>
                            <a href="{{ route('dashboard.brands.index') }}"><i
                                    class="ri-circle-fill circle-icon text-danger-main w-auto"></i>Brands</a>
                        </li>
                        <li>
                            <a href="{{ route('dashboard.tags.index') }}"><i
                                    class="ri-circle-fill circle-icon text-warning-main w-auto"></i>Tags</a>
                        </li>
                    </ul>
                </li>

                <li class="dropdown {{ $couponsOpen ? 'open' : '' }}">
                    <a href="javascript:void(0)">
                        <i class="ri-coupon-3-line menu-icon"></i>
                        <span>Coupons</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('dashboard.coupons.index') }}"><i
                                    class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> List</a>
                        </li>
                    </ul>
                </li>

                <li class="dropdown {{ $reviewsOpen ? 'open' : '' }}">
                    <a href="javascript:void(0)">
                        <i class="ri-star-line menu-icon"></i>
                        <span>Reviews</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('dashboard.reviews.index') }}"><i
                                    class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> All Reviews</a>
                        </li>
                        <li>
                            <a href="{{ route('dashboard.reviews.index', ['status' => 'pending']) }}"><i
                                    class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Pending</a>
                        </li>
                    </ul>
                </li>
            @endcan

            @can('view products')
                <li class="dropdown {{ $taxesOpen ? 'open' : '' }}">
                    <a href="javascript:void(0)">
                        <i class="ri-calculator-line menu-icon"></i>
                        <span>Taxes (GST)</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('dashboard.taxes.index') }}"><i
                                    class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> List</a>
                        </li>
                    </ul>
                </li>
            @endcan

            @can('view orders')
                <li class="dropdown {{ $ordersOpen ? 'open' : '' }}">
                    <a href="javascript:void(0)">
                        <i class="ri-shopping-bag-3-line menu-icon"></i>
                        <span>Orders</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('dashboard.orders.index') }}">
                                <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> All Orders
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('dashboard.returns.index') }}">
                                <i class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Order Returns
                            </a>
                        </li>
                    </ul>
                </li>
            @endcan

            @can('view customers')
                <li class="dropdown {{ $customersOpen ? 'open' : '' }}">
                    <a href="javascript:void(0)">
                        <i class="ri-group-line menu-icon"></i>
                        <span>Customers</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('dashboard.customers.index') }}">
                                <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> All Customers
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('dashboard.audience-preferences.index') }}">
                                <i class="ri-circle-fill circle-icon text-success-main w-auto"></i> Audience Choices
                            </a>
                        </li>
                    </ul>
                </li>
            @endcan

            @can('view blogs')
                <li class="dropdown {{ $blogsOpen ? 'open' : '' }}">
                    <a href="javascript:void(0)">
                        <i class="ri-news-line text-xl me-6 d-flex w-auto"></i>
                        <span>Blog Management</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('blog') }}">
                                <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> All Blogs
                            </a>
                        </li>
                        @can('create blogs')
                        <li>
                            <a href="{{ route('addBlog') }}">
                                <i class="ri-circle-fill circle-icon text-info-main w-auto"></i> Add New Post
                            </a>
                        </li>
                        @endcan
                        <li>
                            <a href="{{ route('blog-categories.index') }}">
                                <i class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Categories
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('dashboard.blog-reviews.index') }}">
                                <i class="ri-circle-fill circle-icon text-success-main w-auto"></i> Blog Reviews
                            </a>
                        </li>
                    </ul>
                </li>
            @endcan

            @can('view roles')
                <li class="dropdown {{ $rolesOpen ? 'open' : '' }}">
                    <a href="javascript:void(0)">
                        <i class="ri-shield-user-line menu-icon"></i>
                        <span>Role & Access</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('roleAaccess') }}">
                                <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Roles & Permissions
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('assignRole') }}">
                                <i class="ri-circle-fill circle-icon text-success-main w-auto"></i> Assign Role
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="dropdown {{ $usersOpen ? 'open' : '' }}">
                    <a href="javascript:void(0)">
                        <i class="ri-user-settings-line menu-icon"></i>
                        <span>User Management</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('usersList') }}">
                                <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> All Users
                            </a>
                        </li>
                        @can('create roles')
                        <li>
                            <a href="{{ route('addUser') }}">
                                <i class="ri-circle-fill circle-icon text-success-main w-auto"></i> Add User
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
            @endcan

            @can('view settings')
                <li class="dropdown {{ $settingsOpen ? 'open' : '' }}">
                    <a href="javascript:void(0)">
                        <i class="ri-settings-3-line menu-icon"></i>
                        <span>Settings</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('dashboard.home-page.edit') }}"><i
                                    class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Home Page</a>
                        </li>
                        <li>
                            <a href="{{ route('dashboard.site-settings.edit') }}"><i
                                    class="ri-circle-fill circle-icon text-info-main w-auto"></i> Site Settings</a>
                        </li>
                        <li>
                            <a href="{{ route('dashboard.google-analytics.edit') }}"><i
                                    class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Google Analytics</a>
                        </li>
                        <li>
                            <a href="{{ route('dashboard.header-links.index') }}"><i
                                    class="ri-circle-fill circle-icon text-success-main w-auto"></i> Header Links</a>
                        </li>
                        <li>
                            <a href="{{ route('dashboard.footer-links.index') }}"><i
                                    class="ri-circle-fill circle-icon text-success-main w-auto"></i> Footer Links</a>
                        </li>
                        <li>
                            <a href="{{ route('dashboard.pages.index') }}"><i
                                    class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Pages</a>
                        </li>
                        <li>
                            <a href="{{ route('paymentGateway') }}"><i
                                    class="ri-circle-fill circle-icon text-danger-main w-auto"></i> Payment Gateway</a>
                        </li>
                    </ul>
                </li>
            @endcan

            <!-- Removed 404 page link -->
        </ul>
    </div>
</aside>
