<style>
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
        background: rgba(255, 255, 255, 0.02);
        border-left: 2px solid #3b82f6;
        margin: 5px 0 5px 20px;
        padding: 5px 0;
    }

    .nav-treeview .nav-link {
        padding: 8px 15px !important;
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

                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                        class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                @can('sale.all')
                    <li
                        class="nav-item {{ request()->routeIs(['sales.index', 'orders.index', 'order.sales', 'order.return', 'order.cancelled']) ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ request()->routeIs(['sales.index', 'orders.index', 'order.sales', 'order.return', 'order.cancelled']) ? 'active' : '' }}">
                            <i class="nav-icon bi bi-cart-check-fill"></i>
                            <p>Sales Management <i class="nav-arrow bi bi-chevron-right ms-auto"></i></p>
                        </a>
                        <ul class="nav nav-treeview">

                            @can('sale.view')
                                <li class="nav-item">
                                    <a href="{{ route('sales.index') }}"
                                        class="nav-link {{ request()->routeIs('sales.index') ? 'active' : '' }}">
                                        <i class="bi bi-list-ul"></i>
                                        <p>POS Sale List</p>
                                    </a>
                                </li>
                            @endcan

                            @can('order-sale.view')
                                <li class="nav-item">
                                    <a href="{{ route('orders.index') }}"
                                        class="nav-link {{ request()->routeIs('orders.index') ? 'active' : '' }}">
                                        <i class="bi bi-list-ul"></i>
                                        <p>Ecommerce Orders
                                            <span id="pending-order-count" class="badge bg-danger float-end"
                                                style="display:none;">0</span>
                                        </p>
                                    </a>
                                </li>
                            @endcan

                            @can('order-return.view')
                                <li class="nav-item">
                                    <a href="{{ route('order.return') }}"
                                        class="nav-link {{ request()->routeIs('order.return') ? 'active' : '' }}">
                                        <i class="bi bi-arrow-return-left"></i>
                                        <p>Order Return List</p>
                                    </a>
                                </li>
                            @endcan

                            @can('order-cancel.view')
                                <li class="nav-item">
                                    <a href="{{ route('order.cancelled') }}"
                                        class="nav-link {{ request()->routeIs('order.cancelled') ? 'active' : '' }}">
                                        <i class="bi bi-x-circle"></i>
                                        <p>Order Cancel List</p>
                                    </a>
                                </li>
                            @endcan

                        </ul>
                    </li>
                @endcan

                @can('pos.view')
                    <li class="nav-item {{ request()->routeIs('sales.create') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('sales.create') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-cart3"></i>
                            <p>POS <i class="nav-arrow bi bi-chevron-right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('pos-create')
                                <li><a href="{{ route('sales.create') }}"
                                        class="nav-link {{ request()->routeIs('sales.create') ? 'active' : '' }}">
                                        <p>POS Sale Create</p>
                                    </a></li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                @can('products.all')
                    <li
                        class="nav-item {{ request()->routeIs('products.*', 'categories.*', 'sub-categories.*', 'brands.*', 'units.*', 'colors.*', 'sizes.*', 'variations.*') ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ request()->routeIs('products.*', 'categories.*', 'sub-categories.*', 'brands.*', 'units.*', 'colors.*', 'sizes.*', 'variations.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-box-fill"></i>
                            <p>
                                Products
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">

                            @can('product.view')
                                <li>
                                    <a href="{{ route('products.index') }}"
                                        class="nav-link {{ request()->routeIs('products.index') ? 'active' : '' }}">
                                        <p>Product List</p>
                                    </a>
                                </li>
                            @endcan

                            @can('product.create')
                                <li>
                                    <a href="{{ route('products.create') }}"
                                        class="nav-link {{ request()->routeIs('products.create') ? 'active' : '' }}">
                                        <p>Product Create</p>
                                    </a>
                                </li>
                            @endcan

                            @can('product.bulk-product')
                                <li>
                                    <a href="{{ route('products.bulk_upload_view') }}"
                                        class="nav-link {{ request()->routeIs('products.bulk_upload_view') ? 'active' : '' }}">
                                        <p>Add Bulk Product</p>
                                    </a>
                                </li>
                            @endcan

                            @can('category.view')
                                <li>
                                    <a href="{{ route('categories.index') }}"
                                        class="nav-link {{ request()->routeIs('categories.index') ? 'active' : '' }}">
                                        <p>Category List</p>
                                    </a>
                                </li>
                            @endcan

                            @can('sub-category.view')
                                <li>
                                    <a href="{{ route('sub-categories.index') }}"
                                        class="nav-link {{ request()->routeIs('sub-categories.index') ? 'active' : '' }}">
                                        <p>Sub Category List</p>
                                    </a>
                                </li>
                            @endcan

                            @can('brand.view')
                                <li>
                                    <a href="{{ route('brands.index') }}"
                                        class="nav-link {{ request()->routeIs('brands.index') ? 'active' : '' }}">
                                        <p>Brand List</p>
                                    </a>
                                </li>
                            @endcan

                            @can('unit.view')
                                <li>
                                    <a href="{{ route('units.index') }}"
                                        class="nav-link {{ request()->routeIs('units.index') ? 'active' : '' }}">
                                        <p>Unit List</p>
                                    </a>
                                </li>
                            @endcan

                            @can('color.view')
                                <li>
                                    <a href="{{ route('colors.index') }}"
                                        class="nav-link {{ request()->routeIs('colors.index') ? 'active' : '' }}">
                                        <p>Color List</p>
                                    </a>
                                </li>
                            @endcan

                            @can('size.view')
                                <li>
                                    <a href="{{ route('sizes.index') }}"
                                        class="nav-link {{ request()->routeIs('sizes.index') ? 'active' : '' }}">
                                        <p>Size List</p>
                                    </a>
                                </li>
                            @endcan

                            @can('variation.view')
                                <li>
                                    <a href="{{ route('variations.index') }}"
                                        class="nav-link {{ request()->routeIs('variations.index') ? 'active' : '' }}">
                                        <p>Variation List</p>
                                    </a>
                                </li>
                            @endcan

                        </ul>
                    </li>
                @endcan
                @can('purchase.all')
                    <li class="nav-item {{ request()->routeIs('purchases.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('purchases.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-basket-fill"></i>
                            <p>
                                Purchases
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">

                            @can('purchase.view')
                                <li>
                                    <a href="{{ route('purchases.index') }}"
                                        class="nav-link {{ request()->routeIs('purchases.index') ? 'active' : '' }}">
                                        <p>Purchase List</p>
                                    </a>
                                </li>
                            @endcan

                            @can('purchase.create')
                                <li>
                                    <a href="{{ route('purchases.create') }}"
                                        class="nav-link {{ request()->routeIs('purchases.create') ? 'active' : '' }}">
                                        <p>Purchase Create</p>
                                    </a>
                                </li>
                            @endcan

                        </ul>
                    </li>
                @endcan

                @can('member.view')
                    <li class="nav-item {{ request()->routeIs('members.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('members.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-people-fill"></i>
                            <p>
                                Members
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">
                            <li>
                                <a href="{{ route('members.index') }}"
                                    class="nav-link {{ request()->routeIs('members.index') ? 'active' : '' }}">
                                    <p>Member List</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan

                @can('supplier-view')
                    <li class="nav-item {{ request()->routeIs('suppliers.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-truck"></i>
                            <p>
                                Suppliers
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">
                            <li>
                                <a href="{{ route('suppliers.index') }}"
                                    class="nav-link {{ request()->routeIs('suppliers.index') ? 'active' : '' }}">
                                    <p>Supplier List</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan

                @can('slider.view')
                    <li class="nav-item {{ request()->routeIs('sliders.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('sliders.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-tags"></i>
                            <p>
                                Sliders
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">
                            <li>
                                <a href="{{ route('sliders.index') }}"
                                    class="nav-link {{ request()->routeIs('sliders.index') ? 'active' : '' }}">
                                    <p>Slider List</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan

                @can('blog.list')
                    <li class="nav-item {{ request()->routeIs('blog-categories.*', 'blogs.*') ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ request()->routeIs('blog-categories.*', 'blogs.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-gear-fill"></i>
                            <p>
                                Blogs
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">

                            @can('blog-category.view')
                                <li>
                                    <a href="{{ route('blog-categories.index') }}"
                                        class="nav-link {{ request()->routeIs('blog-categories.*') ? 'active' : '' }}">
                                        <p>Blog Category List</p>
                                    </a>
                                </li>
                            @endcan

                            @can('blog.view')
                                <li>
                                    <a href="{{ route('blogs.index') }}"
                                        class="nav-link {{ request()->routeIs('blogs.*') ? 'active' : '' }}">
                                        <p>Blog List</p>
                                    </a>
                                </li>
                            @endcan

                        </ul>
                    </li>
                @endcan

                @can('location.all')
                    <li class="nav-item {{ request()->routeIs('locations.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('locations.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-geo-alt-fill"></i>
                            <p>
                                Locations
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">

                            @can('division.view')
                                <li>
                                    <a href="{{ route('locations.divisions') }}"
                                        class="nav-link {{ request()->routeIs('locations.divisions') ? 'active' : '' }}">
                                        <p>Divisions</p>
                                    </a>
                                </li>
                            @endcan

                            @can('district.view')
                                <li>
                                    <a href="{{ route('locations.districts') }}"
                                        class="nav-link {{ request()->routeIs('locations.districts') ? 'active' : '' }}">
                                        <p>Districts</p>
                                    </a>
                                </li>
                            @endcan

                            @can('upazila.view')
                                <li>
                                    <a href="{{ route('locations.upazilas') }}"
                                        class="nav-link {{ request()->routeIs('locations.upazilas') ? 'active' : '' }}">
                                        <p>Upazilas</p>
                                    </a>
                                </li>
                            @endcan

                        </ul>
                    </li>
                @endcan

                @can('location.all')
                    <li class="nav-item {{ request()->routeIs('shippings.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('shippings.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-box-seam"></i>
                            <p>
                                Shipping Zones
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">

                            @can('shippings.view')
                                <li>
                                    <a href="{{ route('shippings.index') }}"
                                        class="nav-link {{ request()->routeIs('shippings.*') ? 'active' : '' }}">
                                        <p>Shippings</p>
                                    </a>
                                </li>
                            @endcan

                        </ul>
                    </li>
                @endcan

                @can('expense.all')
                    <li class="nav-item {{ request()->routeIs('expenses.*','expenses-categories.*') ? 'menu-open' : '' }}">

                        <a href="#" class="nav-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}">

                            <i class="nav-icon bi bi-cash-stack"></i>

                            <p>
                                Expenses
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>

                        </a>

                        <ul class="nav nav-treeview">

                            @can('expense.view')
                                <li>
                                    <a href="{{ route('expenses.index') }}"
                                        class="nav-link {{ request()->routeIs('expenses.index') ? 'active' : '' }}">
                                        <p>All Expenses</p>
                                    </a>
                                </li>
                            @endcan

                            @can('expense.category.view')
                                <li>
                                    <a href="{{ route('expenses-categories.index') }}"
                                        class="nav-link {{ request()->routeIs('expenses-categories.*') ? 'active' : '' }}">
                                        <p>Expense Categories</p>
                                    </a>
                                </li>
                            @endcan

                        </ul>

                    </li>
                @endcan
                @can('our-services.all')
                    <li class="nav-item {{ request()->routeIs('our-services.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('our-services.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-gear"></i>
                            <p>
                                Our Services
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('our-services.view')
                            <li class="nav-item">
                                <a href="{{ route('our-services.index') }}"
                                class="nav-link {{ request()->routeIs('our-services.index') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>All Services</p>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                {{-- Our Clients --}}
                @can('clients.all')
                    <li class="nav-item {{ request()->routeIs('clients.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-people"></i>
                            <p>
                                Our Clients
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('clients.view')
                            <li class="nav-item">
                                <a href="{{ route('clients.index') }}"
                                class="nav-link {{ request()->routeIs('clients.index') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>All Clients</p>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                {{-- Success Counters --}}
                @can('counters.all')
                    <li class="nav-item {{ request()->routeIs('counters.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('counters.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-bar-chart-steps"></i>
                            <p>
                                Success Counters
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('counters.view')
                            <li class="nav-item">
                                <a href="{{ route('counters.index') }}"
                                class="nav-link {{ request()->routeIs('counters.index') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>All Counters</p>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                {{-- Why Choose Us (Features) --}}
                @can('features.all')
                    <li class="nav-item {{ request()->routeIs('features.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('features.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-star"></i>
                            <p>
                                Why Choose Us
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('features.view')
                            <li class="nav-item">
                                <a href="{{ route('features.index') }}"
                                class="nav-link {{ request()->routeIs('features.index') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>All Features</p>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                {{-- Quality Materials --}}
                @can('materials.all')
                    <li class="nav-item {{ request()->routeIs('materials.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('materials.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-layers"></i>
                            <p>
                                Quality Materials
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('materials.view')
                            <li class="nav-item">
                                <a href="{{ route('materials.index') }}"
                                class="nav-link {{ request()->routeIs('materials.index') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>All Materials</p>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                {{-- Who We Are (About Us) --}}
                @can('about-us.all')
                    <li class="nav-item {{ request()->routeIs('about-us.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('about-us.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-info-square"></i>
                            <p>
                                Who We Are
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('about-us.view')
                            <li class="nav-item">
                                <a href="{{ route('about-us.index') }}"
                                class="nav-link {{ request()->routeIs('about-us.index') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Manage Content</p>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                @can('team.all')
                <li class="nav-item {{ request()->routeIs('teams.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('teams.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-people"></i>
                        <p>
                            Team Members
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @can('team.view')
                        <li class="nav-item">
                            <a href="{{ route('teams.index') }}" class="nav-link {{ request()->routeIs('teams.index') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Manage Team</p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan

                @can('testimonial.all')
                <li class="nav-item {{ request()->routeIs('testimonials.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('testimonials.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-chat-quote"></i>
                        <p>
                            Testimonials
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @can('testimonial.view')
                        <li class="nav-item">
                            <a href="{{ route('testimonials.index') }}" class="nav-link {{ request()->routeIs('testimonials.index') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Manage Testimonials</p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan
                @can('report.view')
                    <li class="nav-item {{ request()->routeIs('reports.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-file-earmark-bar-graph-fill"></i>
                            <p>
                                Reports
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">

                            <li>
                                <a href="{{ route('reports.total-income') }}"
                                    class="nav-link {{ request()->routeIs('reports.total-income') ? 'active' : '' }}">
                                    <p>Total Income</p>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('reports.sales') }}"
                                    class="nav-link {{ request()->routeIs('reports.sales') ? 'active' : '' }}">
                                    <p>Sales Report</p>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('reports.purchase') }}"
                                    class="nav-link {{ request()->routeIs('reports.purchase') ? 'active' : '' }}">
                                    <p>Purchase Report</p>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endcan

                @can('customer.view')
                    <li class="nav-item {{ request()->routeIs('customers.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-people-fill"></i>
                            <p>
                                Customer
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li>
                                <a href="{{ route('customers.index') }}"
                                    class="nav-link {{ request()->routeIs('customers.index') ? 'active' : '' }}">
                                    <p>Customers</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan

                @can('permission.all')
                    <li class="nav-item {{ request()->routeIs('permissions.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('permissions.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-shield-lock-fill"></i>
                            <p>
                                Permission
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('permission.view')
                                <li>
                                    <a href="{{ route('permissions.index') }}"
                                        class="nav-link {{ request()->routeIs('permissions.index') ? 'active' : '' }}">
                                        <p>Permission List</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                @can('role.all')
                    <li class="nav-item {{ request()->routeIs('roles.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-people-fill"></i>
                            <p>
                                Role
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('role.view')
                                <li>
                                    <a href="{{ route('roles.index') }}"
                                        class="nav-link {{ request()->routeIs('roles.index') ? 'active' : '' }}">
                                        <p>Role List</p>
                                    </a>
                                </li>
                            @endcan

                            @can('role.create')
                                <li>
                                    <a href="{{ route('roles.create') }}"
                                        class="nav-link {{ request()->routeIs('roles.create') ? 'active' : '' }}">
                                        <p>Role Create</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                @can('user.all')
                    <li class="nav-item {{ request()->routeIs('users.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-person-badge-fill"></i>
                            <p>
                                User
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('user.view')
                                <li>
                                    <a href="{{ route('users.index') }}"
                                        class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}">
                                        <p>User List</p>
                                    </a>
                                </li>
                            @endcan

                            @can('user.create')
                                <li>
                                    <a href="{{ route('users.create') }}"
                                        class="nav-link {{ request()->routeIs('users.create') ? 'active' : '' }}">
                                        <p>User Create</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                @can('contact-us.view')
                    <li class="nav-item {{ request()->routeIs('contact.us.data') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('contact.us.data') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-people-fill"></i>
                            <p>
                                Contact
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li>
                                <a href="{{ route('contact.us.data') }}"
                                    class="nav-link {{ request()->routeIs('contact.us.data') ? 'active' : '' }}">
                                    <p>Contact List</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan

                @can('newsletter.view')
                    <li class="nav-item {{ request()->routeIs('newsletter.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('newsletter.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-envelope-fill"></i>
                            <p>
                                Newsletter
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li>
                                <a href="{{ route('newsletter.list') }}"
                                    class="nav-link {{ request()->routeIs('newsletter.list') ? 'active' : '' }}">
                                    <p>Newsletter List</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan

                @can('setting.view')
                    <li class="nav-item {{ request()->routeIs('settings.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-gear-fill"></i>
                            <p>
                                Setting
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li>
                                <a href="{{ route('settings.index') }}"
                                    class="nav-link {{ request()->routeIs('settings.index') ? 'active' : '' }}">
                                    <p>Setting List</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan

                <li class="nav-header">ACCOUNT</li>
                <li class="nav-item">
                    <a href="{{ route('profile.edit') }}"
                        class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-person-circle"></i>
                        <p>My Profile</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
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
        var currentUrl = window.location.href;

        $('.sidebar-menu a').each(function() {
            var linkUrl = $(this).attr('href');

            if (linkUrl !== "#" && currentUrl === linkUrl) {
                $(this).addClass('active');

                $(this).parents('.nav-item').each(function() {
                    $(this).addClass('menu-open');
                    $(this).children('.nav-link').addClass('active');
                    $(this).children('.nav-treeview').css('display', 'block');
                });
            }
        });

        $('.nav-item > a').on('click', function(e) {
            if ($(this).next('.nav-treeview').length > 0) {
                e.preventDefault();
                $(this).next('.nav-treeview').slideToggle(300);
                $(this).parent().toggleClass('menu-open');
            }
        });
    });
</script>
