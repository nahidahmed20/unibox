<style>
    /* Sidebar Modern Enhancements */
    .sidebar-wrapper {
        padding: 10px;
    }

    .sidebar-wrapper .nav-item {
        margin-bottom: 4px;
    }

    .sidebar-wrapper .nav-link {
        border-radius: 10px !important;
        padding: 12px 15px !important;
        transition: all 0.3s ease;
        color: #94a3b8 !important;
        display: flex;
        align-items: center;
        font-weight: 500;
    }

    /* Hover & Active Effect */
    .sidebar-wrapper .nav-link:hover,
    .sidebar-wrapper .nav-link.active {
        background: linear-gradient(90deg, #001a45 0%, #001a45 100%) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.3);
    }

    .nav-icon {
        margin-right: 12px;
        font-size: 1.1rem;
    }

    /* Submenu Styling */
    .nav-treeview {
        overflow: hidden;
    }

    .nav-treeview .nav-link {
        padding-left: 30px  !important;
        font-size: 0.9rem;
        border-radius: 6px !important;
    }

    /* Header Text */
    .nav-header {
        font-size: 0.7rem !important;
        font-weight: 700;
        letter-spacing: 0.05em;
        color: #475569 !important;
        padding: 20px 15px 10px !important;
    }

    /* Sidebar Background */
    .app-sidebar {
        background: #0f172a !important;
        /* Deep Dark Blue Theme */
        border-right: 1px solid #1e293b;
    }
</style>

<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="{{ route('dashboard') }}" class="brand-link">
            <img src="{{ asset(setting('footer_logo')) }}" alt="Logo" class="brand-image opacity-75 shadow" />
        </a>
    </div>

    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview">

                {{-- ==========================================
                    DASHBOARD
                ========================================== --}}
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-header">CORE BUSINESS</li>

                {{-- ==========================================
                    ১. Sales & Orders (Sales, POS, E-commerce)
                ========================================== --}}
                @canany(['sale.all', 'sale.view', 'order-sale.view', 'order-return.view', 'order-cancel.view', 'pos.view', 'pos-create'])
                    <li class="nav-item {{ request()->routeIs('sales.*', 'orders.*', 'order.*','orders.sales-list') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('sales.*', 'orders.*', 'order.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-cart-check-fill"></i>
                            <p>
                                Sales & Orders
                                <i class="nav-arrow bi bi-chevron-right ms-auto"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="{{ request()->routeIs('sales.*', 'orders.*', 'order.*') ? 'display: block;' : 'display: none;' }}">
                            @can('pos-create')
                                <li class="nav-item">
                                    <a href="{{ route('sales.create') }}" class="nav-link {{ request()->routeIs('sales.create') ? 'active' : '' }}">
                                        <i class="bi bi-pc-display-horizontal"></i>
                                        <p>POS Sale Create</p>
                                    </a>
                                </li>
                            @endcan
                            @can('sale.view')
                                <li class="nav-item">
                                    <a href="{{ route('sales.index') }}" class="nav-link {{ request()->routeIs('sales.index') ? 'active' : '' }}">
                                        <i class="bi bi-list-ul"></i>
                                        <p>POS Sale List</p>
                                    </a>
                                </li>
                            @endcan
                            @can('order-sale.view')
                                <li class="nav-item">
                                    <a href="{{ route('orders.index') }}" class="nav-link {{ request()->routeIs('orders.index') ? 'active' : '' }}">
                                        <i class="bi bi-shop"></i>
                                        <p>Ecommerce Orders <span id="pending-order-count" class="badge bg-danger float-end" style="display:none;">0</span></p>
                                    </a>
                                </li>
                            @endcan
                            @can('sale.view')
                                <li class="nav-item">
                                    <a href="{{ route('orders.sales-list') }}" class="nav-link {{ request()->routeIs('orders.sales-list') ? 'active' : '' }}">
                                        <i class="bi bi-list-ul"></i>
                                        <p>Ecommerce Sale List</p>
                                    </a>
                                </li>
                            @endcan
                            @can('order-return.view')
                                <li class="nav-item">
                                    <a href="{{ route('order.return') }}" class="nav-link {{ request()->routeIs('order.return') ? 'active' : '' }}">
                                        <i class="bi bi-arrow-return-left"></i>
                                        <p>Order Return List</p>
                                    </a>
                                </li>
                            @endcan
                            @can('order-cancel.view')
                                <li class="nav-item">
                                    <a href="{{ route('order.cancelled') }}" class="nav-link {{ request()->routeIs('order.cancelled') ? 'active' : '' }}">
                                        <i class="bi bi-x-circle"></i>
                                        <p>Order Cancel List</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                {{-- ==========================================
                    ২. Catalog (Products, Categories, Brands, etc.)
                ========================================== --}}
                @canany(['products.all', 'product.view', 'product.create', 'product.bulk-product', 'category.view', 'sub-category.view', 'brand.view', 'unit.view', 'color.view', 'size.view', 'variation.view'])
                    <li class="nav-item {{ request()->routeIs('products.*', 'categories.*', 'sub-categories.*', 'brands.*', 'units.*', 'colors.*', 'sizes.*', 'variations.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('products.*', 'categories.*', 'sub-categories.*', 'brands.*', 'units.*', 'colors.*', 'sizes.*', 'variations.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-box-seam-fill"></i>
                            <p>
                                Catalog Setup
                                <i class="nav-arrow bi bi-chevron-right ms-auto"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="{{ request()->routeIs('products.*', 'categories.*', 'sub-categories.*', 'brands.*', 'units.*', 'colors.*', 'sizes.*', 'variations.*') ? 'display: block;' : 'display: none;' }}">
                            @can('product.view')
                                <li class="nav-item"><a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.index') ? 'active' : '' }}"><i class="bi bi-list-ul"></i><p>Product List</p></a></li>
                            @endcan
                            @can('product.create')
                                <li class="nav-item"><a href="{{ route('products.create') }}" class="nav-link {{ request()->routeIs('products.create') ? 'active' : '' }}"><i class="bi bi-plus-circle-fill"></i><p>Product Create</p></a></li>
                            @endcan
                            @can('product.bulk-product')
                                <li class="nav-item"><a href="{{ route('products.bulk_upload_view') }}" class="nav-link {{ request()->routeIs('products.bulk_upload_view') ? 'active' : '' }}"><i class="bi bi-cloud-arrow-up"></i><p>Bulk Product</p></a></li>
                            @endcan
                            @can('category.view')
                                <li class="nav-item"><a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.index') ? 'active' : '' }}"><i class="bi bi-grid-fill"></i><p>Category List</p></a></li>
                            @endcan
                            @can('sub-category.view')
                                <li class="nav-item"><a href="{{ route('sub-categories.index') }}" class="nav-link {{ request()->routeIs('sub-categories.index') ? 'active' : '' }}"><i class="bi bi-grid-3x3-gap"></i><p>Sub Category List</p></a></li>
                            @endcan
                            @can('brand.view')
                                <li class="nav-item"><a href="{{ route('brands.index') }}" class="nav-link {{ request()->routeIs('brands.index') ? 'active' : '' }}"><i class="bi bi-patch-check"></i><p>Brand List</p></a></li>
                            @endcan
                            @can('unit.view')
                                <li class="nav-item"><a href="{{ route('units.index') }}" class="nav-link {{ request()->routeIs('units.index') ? 'active' : '' }}"><i class="bi bi-hash"></i><p>Unit List</p></a></li>
                            @endcan
                            @can('color.view')
                                <li class="nav-item"><a href="{{ route('colors.index') }}" class="nav-link {{ request()->routeIs('colors.index') ? 'active' : '' }}"><i class="bi bi-palette"></i><p>Color List</p></a></li>
                            @endcan
                            @can('size.view')
                                <li class="nav-item"><a href="{{ route('sizes.index') }}" class="nav-link {{ request()->routeIs('sizes.index') ? 'active' : '' }}"><i class="bi bi-rulers"></i><p>Size List</p></a></li>
                            @endcan
                            @can('variation.view')
                                <li class="nav-item"><a href="{{ route('variations.index') }}" class="nav-link {{ request()->routeIs('variations.index') ? 'active' : '' }}"><i class="bi bi-sliders"></i><p>Variation List</p></a></li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                {{-- ==========================================
                    ৩. Inventory & Purchases (Stock, Suppliers)
                ========================================== --}}
                @canany(['purchase.all', 'purchase.view', 'purchase.create', 'stock_adjustment.all', 'stock_adjustment.view', 'stock_adjustment.create', 'supplier-view'])
                    <li class="nav-item {{ request()->routeIs('purchases.*', 'stock-adjustments.*', 'stock-adjustment.*', 'suppliers.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('purchases.*', 'stock-adjustments.*', 'stock-adjustment.*', 'suppliers.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-basket-fill"></i>
                            <p>
                                Inventory & Purchases
                                <i class="nav-arrow bi bi-chevron-right ms-auto"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="{{ request()->routeIs('purchases.*', 'stock-adjustments.*', 'stock-adjustment.*', 'suppliers.*') ? 'display: block;' : 'display: none;' }}">
                            @can('purchase.view')
                                <li class="nav-item"><a href="{{ route('purchases.index') }}" class="nav-link {{ request()->routeIs('purchases.index') ? 'active' : '' }}"><i class="bi bi-list-ul"></i><p>Purchase List</p></a></li>
                            @endcan
                            @can('purchase.create')
                                <li class="nav-item"><a href="{{ route('purchases.create') }}" class="nav-link {{ request()->routeIs('purchases.create') ? 'active' : '' }}"><i class="bi bi-plus-circle"></i><p>Purchase Create</p></a></li>
                            @endcan
                            @can('stock_adjustment.view')
                                <li class="nav-item"><a href="{{ route('stock-adjustments.index') }}" class="nav-link {{ request()->routeIs('stock-adjustments.index') ? 'active' : '' }}"><i class="bi bi-sliders"></i><p>Stock Adjustments</p></a></li>
                            @endcan
                            @can('stock_adjustment.create')
                                <li class="nav-item"><a href="{{ route('stock-adjustments.create') }}" class="nav-link {{ request()->routeIs('stock-adjustments.create') ? 'active' : '' }}"><i class="bi bi-plus-circle-dotted"></i><p>Adjustment Create</p></a></li>
                            @endcan
                            @can('supplier-view')
                                <li class="nav-item"><a href="{{ route('suppliers.index') }}" class="nav-link {{ request()->routeIs('suppliers.index') ? 'active' : '' }}"><i class="bi bi-person-badge-fill"></i><p>Supplier List</p></a></li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                {{-- ==========================================
                    ৪. Finance & Reports (Expenses, Reports)
                ========================================== --}}
                @canany(['expense.all', 'expense.view', 'expense.category.view', 'report.view'])
                    <li class="nav-item {{ request()->routeIs('expenses.*', 'expenses-categories.*', 'reports.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('expenses.*', 'expenses-categories.*', 'reports.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-wallet2"></i>
                            <p>
                                Finance & Reports
                                <i class="nav-arrow bi bi-chevron-right ms-auto"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="{{ request()->routeIs('expenses.*', 'expenses-categories.*', 'reports.*') ? 'display: block;' : 'display: none;' }}">
                            @can('expense.view')
                                <li class="nav-item"><a href="{{ route('expenses.index') }}" class="nav-link {{ request()->routeIs('expenses.index') ? 'active' : '' }}"><i class="bi bi-receipt"></i><p>All Expenses</p></a></li>
                            @endcan
                            @can('expense.category.view')
                                <li class="nav-item"><a href="{{ route('expenses-categories.index') }}" class="nav-link {{ request()->routeIs('expenses-categories.*') ? 'active' : '' }}"><i class="bi bi-tags"></i><p>Expense Categories</p></a></li>
                            @endcan
                            @can('report.view')
                                <li class="nav-item"><a href="{{ route('reports.total-income') }}" class="nav-link {{ request()->routeIs('reports.total-income') ? 'active' : '' }}"><i class="bi bi-cash-coin"></i><p>Total Income</p></a></li>
                                <li class="nav-item"><a href="{{ route('reports.sales') }}" class="nav-link {{ request()->routeIs('reports.sales') ? 'active' : '' }}"><i class="bi bi-cart-check-fill"></i><p>Sales Report</p></a></li>
                                <li class="nav-item"><a href="{{ route('reports.purchase') }}" class="nav-link {{ request()->routeIs('reports.purchase') ? 'active' : '' }}"><i class="bi bi-bag-check-fill"></i><p>Purchase Report</p></a></li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                {{-- ==========================================
                    ৫. CRM & People (Customers, Members)
                ========================================== --}}
                @canany(['customer.view', 'member.view'])
                    <li class="nav-item {{ request()->routeIs('customers.*', 'members.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('customers.*', 'members.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-people-fill"></i>
                            <p>
                                CRM & People
                                <i class="nav-arrow bi bi-chevron-right ms-auto"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="{{ request()->routeIs('customers.*', 'members.*') ? 'display: block;' : 'display: none;' }}">
                            @can('customer.view')
                                <li class="nav-item"><a href="{{ route('customers.index') }}" class="nav-link {{ request()->routeIs('customers.index') ? 'active' : '' }}"><i class="bi bi-person-bounding-box"></i><p>Customers List</p></a></li>
                            @endcan
                            @can('member.view')
                                <li class="nav-item"><a href="{{ route('members.index') }}" class="nav-link {{ request()->routeIs('members.index') ? 'active' : '' }}"><i class="bi bi-person-lines-fill"></i><p>Members List</p></a></li>
                            @endcan
                            @can('customer.view')
                                <li class="nav-item">
                                    <a href="{{ route('blocked-phones.index') }}" class="nav-link {{ request()->routeIs('blocked-phones.index') ? 'active' : '' }}">
                                        <i class="bi bi-person-x-fill text-danger"></i>
                                        <p>Return & Block List</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                <li class="nav-header">WEBSITE & ADMIN</li>

                {{-- ==========================================
                    ৬. Website Content (CMS, Blogs, Pages)
                ========================================== --}}
                @canany(['slider.view', 'blog.list', 'blog-category.view', 'blog.view', 'about-us.all', 'our-services.all', 'clients.all', 'counters.all', 'features.all', 'team.all', 'testimonial.all', 'contact-us.view', 'newsletter.view'])
                    <li class="nav-item {{ request()->routeIs('sliders.*', 'blog-categories.*', 'blogs.*', 'about-us.*', 'our-services.*', 'features.*', 'counters.*', 'testimonials.*', 'teams.*', 'clients.*', 'contact.us.data', 'newsletter.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('sliders.*', 'blog-categories.*', 'blogs.*', 'about-us.*', 'our-services.*', 'features.*', 'counters.*', 'testimonials.*', 'teams.*', 'clients.*', 'contact.us.data', 'newsletter.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-window-desktop"></i>
                            <p>
                                Website Content
                                <i class="nav-arrow bi bi-chevron-right ms-auto"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="{{ request()->routeIs('sliders.*', 'blog-categories.*', 'blogs.*', 'about-us.*', 'our-services.*', 'features.*', 'counters.*', 'testimonials.*', 'teams.*', 'clients.*', 'contact.us.data', 'newsletter.*') ? 'display: block;' : 'display: none;' }}">
                            @can('slider.view')
                                <li class="nav-item"><a href="{{ route('sliders.index') }}" class="nav-link {{ request()->routeIs('sliders.*') ? 'active' : '' }}"><i class="bi bi-images"></i><p>Sliders</p></a></li>
                            @endcan
                            @can('blog-category.view')
                                <li class="nav-item"><a href="{{ route('blog-categories.index') }}" class="nav-link {{ request()->routeIs('blog-categories.*') ? 'active' : '' }}"><i class="bi bi-tags-fill"></i><p>Blog Categories</p></a></li>
                            @endcan
                            @can('blog.view')
                                <li class="nav-item"><a href="{{ route('blogs.index') }}" class="nav-link {{ request()->routeIs('blogs.*') ? 'active' : '' }}"><i class="bi bi-file-earmark-post"></i><p>Blogs</p></a></li>
                            @endcan
                            @can('about-us.all')
                                <li class="nav-item"><a href="{{ route('about-us.index') }}" class="nav-link {{ request()->routeIs('about-us.*') ? 'active' : '' }}"><i class="bi bi-building-fill"></i><p>Who We Are</p></a></li>
                            @endcan
                            @can('our-services.all')
                                <li class="nav-item"><a href="{{ route('our-services.index') }}" class="nav-link {{ request()->routeIs('our-services.*') ? 'active' : '' }}"><i class="bi bi-briefcase-fill"></i><p>Our Services</p></a></li>
                            @endcan
                            @can('features.all')
                                <li class="nav-item"><a href="{{ route('features.index') }}" class="nav-link {{ request()->routeIs('features.*') ? 'active' : '' }}"><i class="bi bi-hand-thumbs-up-fill"></i><p>Why Choose Us</p></a></li>
                            @endcan
                            @can('team.all')
                                <li class="nav-item"><a href="{{ route('teams.index') }}" class="nav-link {{ request()->routeIs('teams.*') ? 'active' : '' }}"><i class="bi bi-person-hearts"></i><p>Team Members</p></a></li>
                            @endcan
                            @can('testimonial.all')
                                <li class="nav-item"><a href="{{ route('testimonials.index') }}" class="nav-link {{ request()->routeIs('testimonials.*') ? 'active' : '' }}"><i class="bi bi-chat-heart-fill"></i><p>Testimonials</p></a></li>
                            @endcan
                            @can('counters.all')
                                <li class="nav-item"><a href="{{ route('counters.index') }}" class="nav-link {{ request()->routeIs('counters.*') ? 'active' : '' }}"><i class="bi bi-patch-check-fill"></i><p>Success Counters</p></a></li>
                            @endcan
                            @can('clients.all')
                                <li class="nav-item"><a href="{{ route('clients.index') }}" class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}"><i class="bi bi-person-check-fill"></i><p>Our Clients</p></a></li>
                            @endcan
                            @can('contact-us.view')
                                <li class="nav-item"><a href="{{ route('contact.us.data') }}" class="nav-link {{ request()->routeIs('contact.us.data') ? 'active' : '' }}"><i class="bi bi-envelope-paper-fill"></i><p>Contact Inquiries</p></a></li>
                            @endcan
                            @can('newsletter.view')
                                <li class="nav-item"><a href="{{ route('newsletter.list') }}" class="nav-link {{ request()->routeIs('newsletter.list') ? 'active' : '' }}"><i class="bi bi-send-check-fill"></i><p>Newsletter Subscriptions</p></a></li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                {{-- ==========================================
                    ৭. Logistics & Configuration (Locations, Shipping, Courier)
                ========================================== --}}
                @canany(['location.all', 'division.view', 'district.view', 'upazila.view', 'shippings.view', 'materials.all', 'materials.view', 'courier.view'])
                    <li class="nav-item {{ request()->routeIs('locations.*', 'shippings.*', 'materials.*', 'couriers.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('locations.*', 'shippings.*', 'materials.*', 'couriers.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-truck"></i>
                            <p>
                                Logistics & Config
                                <i class="nav-arrow bi bi-chevron-right ms-auto"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="{{ request()->routeIs('locations.*', 'shippings.*', 'materials.*', 'couriers.*') ? 'display: block;' : 'display: none;' }}">
                            
                            @can('division.view')
                                <li class="nav-item"><a href="{{ route('locations.divisions') }}" class="nav-link {{ request()->routeIs('locations.divisions') ? 'active' : '' }}"><i class="bi bi-map"></i><p>Divisions</p></a></li>
                            @endcan
                            
                            @can('district.view')
                                <li class="nav-item"><a href="{{ route('locations.districts') }}" class="nav-link {{ request()->routeIs('locations.districts') ? 'active' : '' }}"><i class="bi bi-pin-map-fill"></i><p>Districts</p></a></li>
                            @endcan
                            
                            @can('upazila.view')
                                <li class="nav-item"><a href="{{ route('locations.upazilas') }}" class="nav-link {{ request()->routeIs('locations.upazilas') ? 'active' : '' }}"><i class="bi bi-signpost-2"></i><p>Upazilas</p></a></li>
                            @endcan
                            
                            @can('shippings.view')
                                <li class="nav-item"><a href="{{ route('shippings.index') }}" class="nav-link {{ request()->routeIs('shippings.*') ? 'active' : '' }}"><i class="bi bi-globe2"></i><p>Shipping Zones</p></a></li>
                            @endcan

                            @can('courier.view')
                                <li class="nav-item">
                                    <a href="{{ route('couriers.index') }}" class="nav-link {{ request()->routeIs('couriers.*') ? 'active' : '' }}">
                                        <i class="bi bi-box2-heart"></i> 
                                        <p>Courier List</p>
                                    </a>
                                </li>
                            @endcan
                            
                            @can('materials.view')
                                <li class="nav-item"><a href="{{ route('materials.index') }}" class="nav-link {{ request()->routeIs('materials.*') ? 'active' : '' }}"><i class="bi bi-gem"></i><p>Quality Materials</p></a></li>
                            @endcan
                            
                        </ul>
                    </li>
                @endcanany

                {{-- ==========================================
                    ৮. Administration (Users, Roles, Settings)
                ========================================== --}}
                @canany(['user.all', 'user.view', 'user.create', 'role.all', 'role.view', 'role.create', 'permission.all', 'permission.view', 'setting.view'])
                    <li class="nav-item {{ request()->routeIs('users.*', 'roles.*', 'permissions.*', 'settings.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('users.*', 'roles.*', 'permissions.*', 'settings.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-shield-lock-fill"></i>
                            <p>
                                Administration
                                <i class="nav-arrow bi bi-chevron-right ms-auto"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="{{ request()->routeIs('users.*', 'roles.*', 'permissions.*', 'settings.*') ? 'display: block;' : 'display: none;' }}">
                            @can('user.view')
                                <li class="nav-item"><a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}"><i class="bi bi-person-lines-fill"></i><p>System Users</p></a></li>
                            @endcan
                            @can('role.view')
                                <li class="nav-item"><a href="{{ route('roles.index') }}" class="nav-link {{ request()->routeIs('roles.index') ? 'active' : '' }}"><i class="bi bi-person-workspace"></i><p>Roles & Rights</p></a></li>
                            @endcan
                            @can('permission.view')
                                <li class="nav-item"><a href="{{ route('permissions.index') }}" class="nav-link {{ request()->routeIs('permissions.index') ? 'active' : '' }}"><i class="bi bi-key-fill"></i><p>Permissions Setup</p></a></li>
                            @endcan
                            @can('setting.view')
                                <li class="nav-item"><a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.index') ? 'active' : '' }}"><i class="bi bi-gear-wide-connected"></i><p>Global Settings</p></a></li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                {{-- ==========================================
                    ACCOUNT
                ========================================== --}}
                <li class="nav-header">ACCOUNT</li>
                <li class="nav-item">
                    <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-person-circle"></i>
                        <p>My Profile</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="nav-icon bi bi-box-arrow-right"></i>
                        <p>Logout</p>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>

            </ul>
        </nav>
    </div>
</aside>

<script>
    $(document).ready(function() {
        $('.nav-item > a').on('click', function(e) {
            var $submenu = $(this).next('.nav-treeview');
            if ($submenu.length > 0) {
                e.preventDefault();
                $(this).parent().toggleClass('menu-open'); 
                $submenu.slideToggle(300); 
            }
        });
    });
</script>
