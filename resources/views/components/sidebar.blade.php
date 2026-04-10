<aside class="sidebar">
    <button type="button" class="sidebar-close-btn">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
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
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                    <span>Dashboard</span>
                </a>
                <ul class="sidebar-submenu">
                    {{-- <li>
                        <a href="{{ route('index3') }}"><i class="ri-circle-fill circle-icon text-info-main w-auto"></i>
                            eCommerce</a>
                    </li> --}}

                </ul>
            </li>
            <li class="sidebar-menu-group-title">Application</li>

            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="hugeicons:invoice-03" class="menu-icon"></iconify-icon>
                    <span>Invoice</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('dashboard.invoices.index') }}"><i
                                class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> List</a>
                    </li>
                    <li>
                        <a href="{{ route('invoicePreview') }}"><i
                                class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Preview</a>
                    </li>
                    <li>
                        <a href="{{ route('invoiceAdd') }}"><i
                                class="ri-circle-fill circle-icon text-info-main w-auto"></i> Add new</a>
                    </li>
                    <li>
                        <a href="{{ route('invoiceEdit') }}"><i
                                class="ri-circle-fill circle-icon text-danger-main w-auto"></i> Edit</a>
                    </li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="hugeicons:invoice-03" class="menu-icon"></iconify-icon>
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
                    <iconify-icon icon="solar:tag-outline" class="menu-icon"></iconify-icon>
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
                    <iconify-icon icon="ri:star-half-line" class="menu-icon"></iconify-icon>
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
                    <iconify-icon icon="solar:calculator-outline" class="menu-icon"></iconify-icon>
                    <span>Taxes (GST)</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="javascript:void(0)"><i
                                class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> List</a>
                    </li>
                </ul>
            </li>

            <li class="dropdown">
                <a href="{{ route('dashboard.stock.index') }}">
                    <iconify-icon icon="lucide:package" class="menu-icon"></iconify-icon>
                    <span>Stock</span>
                </a>
            </li>

            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="mdi:package-variant" class="menu-icon"></iconify-icon>
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
                    <iconify-icon icon="ph:users-three" class="menu-icon"></iconify-icon>
                    <span>Customers</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('dashboard.customers.index') }}">
                            <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> All Customers
                        </a>
                    </li>
                </ul>
            </li>

            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                    <span>Users</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('usersList') }}"><i
                                class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Users List</a>
                    </li>
                    <li>
                        <a href="{{ route('usersGrid') }}"><i
                                class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Users Grid</a>
                    </li>
                    <li>
                        <a href="{{ route('addUser') }}"><i
                                class="ri-circle-fill circle-icon text-info-main w-auto"></i> Add User</a>
                    </li>
                    <li>
                        <a href="{{ route('viewProfile') }}"><i
                                class="ri-circle-fill circle-icon text-danger-main w-auto"></i> View Profile</a>
                    </li>
                </ul>
            </li>

            <li class="sidebar-menu-group-title">Application</li>


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
                </ul>
            </li>
            <li>
                <a href="{{ route('testimonials') }}">
                    <i class="ri-star-line text-xl me-6 d-flex w-auto"></i>
                    <span>Testimonial</span>
                </a>
            </li>
            <li>
                <a href="{{ route('faq') }}">
                    <iconify-icon icon="mage:message-question-mark-round" class="menu-icon"></iconify-icon>
                    <span>FAQs</span>
                </a>
            </li>
            <li>
                <a href="{{ route('error') }}">
                    <iconify-icon icon="streamline:straight-face" class="menu-icon"></iconify-icon>
                    <span>404</span>
                </a>
            </li>
            <li>
                <a href="{{ route('termsCondition') }}">
                    <iconify-icon icon="octicon:info-24" class="menu-icon"></iconify-icon>
                    <span>Terms & Conditions</span>
                </a>
            </li>
            <li>
                <a href="{{ route('maintenance') }}">
                    <i class="ri-hammer-line text-xl me-6 d-flex w-auto"></i>
                    <span>Maintenance</span>
                </a>
            </li>
            <li>
                <a href="{{ route('blankPage') }}">
                    <i class="ri-checkbox-multiple-blank-line text-xl me-6 d-flex w-auto"></i>
                    <span>Blank Page</span>
                </a>
            </li>
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="icon-park-outline:setting-two" class="menu-icon"></iconify-icon>
                    <span>Settings</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('dashboard.site-settings.edit') }}"><i
                                class="ri-circle-fill circle-icon text-info-main w-auto"></i> Site Settings</a>
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
                        <a href="{{ route('company') }}"><i
                                class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Company</a>
                    </li>
                    <li>
                        <a href="{{ route('notification') }}"><i
                                class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Notification</a>
                    </li>
                    <li>
                        <a href="{{ route('notificationAlert') }}"><i
                                class="ri-circle-fill circle-icon text-info-main w-auto"></i> Notification Alert</a>
                    </li>
                    <li>
                        <a href="{{ route('theme') }}"><i
                                class="ri-circle-fill circle-icon text-danger-main w-auto"></i> Theme</a>
                    </li>
                    <li>
                        <a href="{{ route('currencies') }}"><i
                                class="ri-circle-fill circle-icon text-danger-main w-auto"></i> Currencies</a>
                    </li>
                    <li>
                        <a href="{{ route('language') }}"><i
                                class="ri-circle-fill circle-icon text-danger-main w-auto"></i> Languages</a>
                    </li>
                    <li>
                        <a href="{{ route('paymentGateway') }}"><i
                                class="ri-circle-fill circle-icon text-danger-main w-auto"></i> Payment Gateway</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</aside>
