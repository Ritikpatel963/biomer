<aside class="sidebar">
    <button type="button" class="sidebar-close-btn">
        <i class="ri-close-line"></i>
    </button>
    <div>
        <a href="" class="sidebar-logo">
            <img src="{{ asset('assets/images/logo.png') }}" alt="site logo" class="light-logo">
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
            <li class="{{ request()->routeIs('dashboard.analytics') ? 'active-page' : '' }}">
                <a href="{{ route('dashboard.analytics') }}">
                    <i class="ri-bar-chart-grouped-line menu-icon"></i>
                    <span>Analytics</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('dashboard.invoices.index') ? 'active-page' : '' }}">
                <a href="{{ route('dashboard.invoices.index') }}">
                    <i class="ri-file-list-3-line menu-icon"></i>
                    <span>Invoice</span>
                </a>
            </li>
            <li class="dropdown">
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

            <li class="dropdown">
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

            <li class="dropdown">
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
                        <a href="{{ route('dashboard.reviews.index', ['status'=>'pending']) }}"><i
                                class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Pending</a>
                    </li>
                </ul>
            </li>

            <li class="dropdown">
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

            <li class="dropdown">
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

            <li class="dropdown">
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
            <li class="dropdown">
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
                    <li>
                        <a href="{{ route('addBlog') }}">
                            <i class="ri-circle-fill circle-icon text-info-main w-auto"></i> Add New Post
                        </a>
                    </li>
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
             <li class="dropdown">
                <a href="javascript:void(0)">
                    <i class="ri-settings-3-line menu-icon"></i>
                    <span>Settings</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('dashboard.homepage-editor.edit') }}"><i
                                class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Homepage Editor</a>
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
            <li>
                <a href="{{ route('error') }}">
                    <i class="ri-error-warning-line menu-icon"></i>
                    <span>404</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
