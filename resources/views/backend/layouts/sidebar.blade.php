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

                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                        class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                @can('sale.all')
                    <li class="nav-item {{ request()->routeIs(['sales.index', 'orders.index', 'order.sales', 'order.return', 'order.cancelled']) ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs(['sales.index', 'orders.index', 'order.sales', 'order.return', 'order.cancelled']) ? 'active' : '' }}">
                            <i class="nav-icon bi bi-cart-check-fill"></i>
                            <p>Sales Management <i class="nav-arrow bi bi-chevron-right ms-auto"></i></p>
                        </a>
                        
                        <ul class="nav nav-treeview" style="{{ request()->routeIs(['sales.index', 'orders.index', 'order.sales', 'order.return', 'order.cancelled']) ? 'display: block;' : 'display: none;' }}">
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
                                    <i class="bi bi-list-ul"></i>
                                    <p>Ecommerce Orders <span id="pending-order-count" class="badge bg-danger float-end" style="display:none;">0</span></p>
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
                @endcan

                @can('pos.view')
                    <li class="nav-item {{ request()->routeIs('sales.create') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('sales.create') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-shop"></i> {{-- Updated parent icon to a shop front --}}
                            <p>POS <i class="nav-arrow bi bi-chevron-right"></i></p>
                        </a>
                        
                        <ul class="nav nav-treeview" style="{{ request()->routeIs('sales.create') ? 'display: block;' : 'display: none;' }}">
                            @can('pos-create')
                            <li class="nav-item">
                                <a href="{{ route('sales.create') }}" class="nav-link {{ request()->routeIs('sales.create') ? 'active' : '' }}">
                                    <i class="bi bi-pc-display-horizontal"></i> {{-- Changed from bi-circle to a POS register/terminal icon --}}
                                    <p>POS Sale Create</p>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                @can('products.all')
                    <li class="nav-item {{ request()->routeIs(['products.*', 'categories.*', 'sub-categories.*', 'brands.*', 'units.*', 'colors.*', 'sizes.*', 'variations.*']) ? 'menu-open' : '' }}">
                        
                        <a href="#" class="nav-link {{ request()->routeIs(['products.*', 'categories.*', 'sub-categories.*', 'brands.*', 'units.*', 'colors.*', 'sizes.*', 'variations.*']) ? 'active' : '' }}">
                            <i class="nav-icon bi bi-box-seam-fill"></i> {{-- Changed from bi-box-fill --}}
                            <p>
                                Products 
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                    
                        <ul class="nav nav-treeview" style="{{ request()->routeIs(['products.*', 'categories.*', 'sub-categories.*', 'brands.*', 'units.*', 'colors.*', 'sizes.*', 'variations.*']) ? 'display: block;' : 'display: none;' }}">
                    
                            @can('product.view')
                            <li class="nav-item">
                                <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.index') ? 'active' : '' }}">
                                    <i class="bi bi-list-ul"></i> {{-- Product List Icon --}}
                                    <p>Product List</p>
                                </a>
                            </li>
                            @endcan
                    
                            @can('product.create')
                            <li class="nav-item">
                                <a href="{{ route('products.create') }}" class="nav-link {{ request()->routeIs('products.create') ? 'active' : '' }}">
                                    <i class="bi bi-plus-circle-fill"></i> {{-- Create Icon --}}
                                    <p>Product Create</p>
                                </a>
                            </li>
                            @endcan
                    
                            @can('product.bulk-product')
                            <li class="nav-item">
                                <a href="{{ route('products.bulk_upload_view') }}" class="nav-link {{ request()->routeIs('products.bulk_upload_view') ? 'active' : '' }}">
                                    <i class="bi bi-cloud-arrow-up"></i> {{-- Bulk Upload Icon --}}
                                    <p>Add Bulk Product</p>
                                </a>
                            </li>
                            @endcan
                    
                            @can('category.view')
                            <li class="nav-item">
                                <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.index') ? 'active' : '' }}">
                                    <i class="bi bi-grid-fill"></i> {{-- Category Icon --}}
                                    <p>Category List</p>
                                </a>
                            </li>
                            @endcan
                    
                            @can('sub-category.view')
                            <li class="nav-item">
                                <a href="{{ route('sub-categories.index') }}" class="nav-link {{ request()->routeIs('sub-categories.index') ? 'active' : '' }}">
                                    <i class="bi bi-grid-3x3-gap"></i> {{-- Sub Category Icon --}}
                                    <p>Sub Category List</p>
                                </a>
                            </li>
                            @endcan
                    
                            @can('brand.view')
                            <li class="nav-item">
                                <a href="{{ route('brands.index') }}" class="nav-link {{ request()->routeIs('brands.index') ? 'active' : '' }}">
                                    <i class="bi bi-patch-check"></i> {{-- Brand/Verified Icon --}}
                                    <p>Brand List</p>
                                </a>
                            </li>
                            @endcan
                    
                            @can('unit.view')
                            <li class="nav-item">
                                <a href="{{ route('units.index') }}" class="nav-link {{ request()->routeIs('units.index') ? 'active' : '' }}">
                                    <i class="bi bi-hash"></i> {{-- Unit/Measurement Icon --}}
                                    <p>Unit List</p>
                                </a>
                            </li>
                            @endcan
                    
                            @can('color.view')
                            <li class="nav-item">
                                <a href="{{ route('colors.index') }}" class="nav-link {{ request()->routeIs('colors.index') ? 'active' : '' }}">
                                    <i class="bi bi-palette"></i> {{-- Color Icon --}}
                                    <p>Color List</p>
                                </a>
                            </li>
                            @endcan
                    
                            @can('size.view')
                            <li class="nav-item">
                                <a href="{{ route('sizes.index') }}" class="nav-link {{ request()->routeIs('sizes.index') ? 'active' : '' }}">
                                    <i class="bi bi-rulers"></i> {{-- Size/Ruler Icon --}}
                                    <p>Size List</p>
                                </a>
                            </li>
                            @endcan
                    
                            @can('variation.view')
                            <li class="nav-item">
                                <a href="{{ route('variations.index') }}" class="nav-link {{ request()->routeIs('variations.index') ? 'active' : '' }}">
                                    <i class="bi bi-sliders"></i> {{-- Variation/Settings Icon --}}
                                    <p>Variation List</p>
                                </a>
                            </li>
                            @endcan
                    
                        </ul>
                    </li>
                @endcan

                @can('stock_adjustment.all') 
                <li class="nav-item {{ request()->routeIs('stock-adjustment.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('stock-adjustment.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-sliders"></i> 
                        <p>
                            Stock Adjustment 
                            <i class="nav-arrow bi bi-chevron-right ms-auto"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview" style="{{ request()->routeIs('stock-adjustments.*') ? 'display: block;' : 'display: none;' }}">
                        @can('stock_adjustment.view')
                        <li class="nav-item">
                            <a href="{{ route('stock-adjustments.index') }}" class="nav-link {{ request()->routeIs('stock-adjustments.index') ? 'active' : '' }}">
                                <i class="bi bi-list-ul"></i>
                                <p>Adjustment List</p>
                            </a>
                        </li>
                        @endcan

                        @can('stock_adjustment.create')
                        <li class="nav-item">
                            <a href="{{ route('stock-adjustments.create') }}" class="nav-link {{ request()->routeIs('stock-adjustments.create') ? 'active' : '' }}">
                                <i class="bi bi-plus-circle"></i>
                                <p>Adjustment Create</p>
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
                            <i class="nav-arrow bi bi-chevron-right ms-auto"></i>
                        </p>
                    </a>
                
                    <ul class="nav nav-treeview" style="{{ request()->routeIs('purchases.*') ? 'display: block;' : 'display: none;' }}">
                        @can('purchase.view')
                        <li class="nav-item">
                            <a href="{{ route('purchases.index') }}" class="nav-link {{ request()->routeIs('purchases.index') ? 'active' : '' }}">
                                <i class="bi bi-list-ul"></i>
                                <p>Purchase List</p>
                            </a>
                        </li>
                        @endcan
                
                        @can('purchase.create')
                        <li class="nav-item">
                            <a href="{{ route('purchases.create') }}" class="nav-link {{ request()->routeIs('purchases.create') ? 'active' : '' }}">
                                <i class="bi bi-plus-circle"></i>
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

                        <ul class="nav nav-treeview" style="{{ request()->routeIs('members.*') ? 'display: block;' : 'display: none;' }}">
                            <li class="nav-item"> {{-- এখানে nav-item ক্লাস যুক্ত করা হয়েছে যেন থিম ঠিকঠাক কাজ করে --}}
                                <a href="{{ route('members.index') }}"
                                    class="nav-link {{ request()->routeIs('members.index') ? 'active' : '' }}">
                                    <i class="bi bi-person-lines-fill"></i> {{-- মেম্বার লিস্টের জন্য নতুন আইকন --}}
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

                        <ul class="nav nav-treeview" style="{{ request()->routeIs('suppliers.*') ? 'display: block;' : 'display: none;' }}">
                            <li class="nav-item"> 
                                <a href="{{ route('suppliers.index') }}"
                                    class="nav-link {{ request()->routeIs('suppliers.index') ? 'active' : '' }}">
                                    <i class="bi bi-person-badge-fill"></i> 
                                    <p>Supplier List</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan

                @can('slider.view')
    <li class="nav-item {{ request()->routeIs('sliders.*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ request()->routeIs('sliders.*') ? 'active' : '' }}">
            <i class="nav-icon bi bi-images"></i> {{-- প্যারেন্ট আইকন পরিবর্তন করে bi-images করা হয়েছে --}}
            <p>
                Sliders
                <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
        </a>

        {{-- মেনু ওপেন রাখার জন্য স্টাইল কন্ডিশন যুক্ত করা হয়েছে --}}
        <ul class="nav nav-treeview" style="{{ request()->routeIs('sliders.*') ? 'display: block;' : 'display: none;' }}">
            <li class="nav-item"> {{-- nav-item ক্লাস যুক্ত করা হয়েছে --}}
                <a href="{{ route('sliders.index') }}"
                    class="nav-link {{ request()->routeIs('sliders.index') ? 'active' : '' }}">
                    <i class="bi bi-collection-play"></i> {{-- স্লাইডার লিস্টের জন্য পারফেক্ট আইকন --}}
                    <p>Slider List</p>
                </a>
            </li>
        </ul>
    </li>
@endcan

                {{-- Blogs Menu --}}
                @can('blog.list')
                    <li class="nav-item {{ request()->routeIs('blog-categories.*', 'blogs.*') ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ request()->routeIs('blog-categories.*', 'blogs.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-journal-text"></i> {{-- Changed from bi-gear-fill --}}
                            <p>
                                Blogs
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview" style="{{ request()->routeIs('blog-categories.*', 'blogs.*') ? 'display: block;' : 'display: none;' }}">

                            @can('blog-category.view')
                                <li class="nav-item"> {{-- Added nav-item --}}
                                    <a href="{{ route('blog-categories.index') }}"
                                        class="nav-link {{ request()->routeIs('blog-categories.*') ? 'active' : '' }}">
                                        <i class="bi bi-tags-fill"></i> {{-- Category Icon --}}
                                        <p>Blog Category List</p>
                                    </a>
                                </li>
                            @endcan

                            @can('blog.view')
                                <li class="nav-item"> {{-- Added nav-item --}}
                                    <a href="{{ route('blogs.index') }}"
                                        class="nav-link {{ request()->routeIs('blogs.*') ? 'active' : '' }}">
                                        <i class="bi bi-file-earmark-post"></i> {{-- Post Icon --}}
                                        <p>Blog List</p>
                                    </a>
                                </li>
                            @endcan

                        </ul>
                    </li>
                @endcan

                {{-- Locations Menu --}}
                @can('location.all')
                    <li class="nav-item {{ request()->routeIs('locations.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('locations.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-geo-alt-fill"></i>
                            <p>
                                Locations
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview" style="{{ request()->routeIs('locations.*') ? 'display: block;' : 'display: none;' }}">

                            @can('division.view')
                                <li class="nav-item"> {{-- Added nav-item --}}
                                    <a href="{{ route('locations.divisions') }}"
                                        class="nav-link {{ request()->routeIs('locations.divisions') ? 'active' : '' }}">
                                        <i class="bi bi-map"></i> {{-- Division Icon --}}
                                        <p>Divisions</p>
                                    </a>
                                </li>
                            @endcan

                            @can('district.view')
                                <li class="nav-item"> {{-- Added nav-item --}}
                                    <a href="{{ route('locations.districts') }}"
                                        class="nav-link {{ request()->routeIs('locations.districts') ? 'active' : '' }}">
                                        <i class="bi bi-pin-map-fill"></i> {{-- District Icon --}}
                                        <p>Districts</p>
                                    </a>
                                </li>
                            @endcan

                            @can('upazila.view')
                                <li class="nav-item"> {{-- Added nav-item --}}
                                    <a href="{{ route('locations.upazilas') }}"
                                        class="nav-link {{ request()->routeIs('locations.upazilas') ? 'active' : '' }}">
                                        <i class="bi bi-signpost-2"></i> {{-- Upazila Icon --}}
                                        <p>Upazilas</p>
                                    </a>
                                </li>
                            @endcan

                        </ul>
                    </li>
                @endcan

                {{-- Shipping Zones Menu --}}
                @can('location.all')
                    <li class="nav-item {{ request()->routeIs('shippings.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('shippings.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-globe2"></i> {{-- Changed to Globe/Zone Icon --}}
                            <p>
                                Shipping Zones
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview" style="{{ request()->routeIs('shippings.*') ? 'display: block;' : 'display: none;' }}">

                            @can('shippings.view')
                                <li class="nav-item"> {{-- Added nav-item --}}
                                    <a href="{{ route('shippings.index') }}"
                                        class="nav-link {{ request()->routeIs('shippings.*') ? 'active' : '' }}">
                                        <i class="bi bi-truck-flatbed"></i> {{-- Shipping/Truck Icon --}}
                                        <p>Shippings</p>
                                    </a>
                                </li>
                            @endcan

                        </ul>
                    </li>
                @endcan

                {{-- Expenses Menu --}}
                @can('expense.all')
                    <li class="nav-item {{ request()->routeIs('expenses.*','expenses-categories.*') ? 'menu-open' : '' }}">

                        <a href="#" class="nav-link {{ request()->routeIs('expenses.*','expenses-categories.*') ? 'active' : '' }}">

                            <i class="nav-icon bi bi-wallet2"></i> {{-- Changed from bi-cash-stack to Wallet --}}

                            <p>
                                Expenses
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>

                        </a>

                        <ul class="nav nav-treeview" style="{{ request()->routeIs('expenses.*','expenses-categories.*') ? 'display: block;' : 'display: none;' }}">

                            @can('expense.view')
                                <li class="nav-item"> {{-- Added nav-item --}}
                                    <a href="{{ route('expenses.index') }}"
                                        class="nav-link {{ request()->routeIs('expenses.index') ? 'active' : '' }}">
                                        <i class="bi bi-receipt"></i> {{-- Receipt Icon --}}
                                        <p>All Expenses</p>
                                    </a>
                                </li>
                            @endcan

                            @can('expense.category.view')
                                <li class="nav-item"> {{-- Added nav-item --}}
                                    <a href="{{ route('expenses-categories.index') }}"
                                        class="nav-link {{ request()->routeIs('expenses-categories.*') ? 'active' : '' }}">
                                        <i class="bi bi-tags"></i> {{-- Expense Tags Icon --}}
                                        <p>Expense Categories</p>
                                    </a>
                                </li>
                            @endcan

                        </ul>

                    </li>
                @endcan
                
                {{-- Our Services Menu --}}
                @can('our-services.all')
                    <li class="nav-item {{ request()->routeIs('our-services.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('our-services.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-briefcase-fill"></i> {{-- Changed from bi-gear to Briefcase --}}
                            <p>
                                Our Services
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="{{ request()->routeIs('our-services.*') ? 'display: block;' : 'display: none;' }}">
                            @can('our-services.view')
                            <li class="nav-item">
                                <a href="{{ route('our-services.index') }}"
                                class="nav-link {{ request()->routeIs('our-services.index') ? 'active' : '' }}">
                                    <i class="bi bi-award"></i> {{-- Changed from bi-circle to Service/Award Icon --}}
                                    <p>All Services</p>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                {{-- Our Clients Menu --}}
                @can('clients.all')
                    <li class="nav-item {{ request()->routeIs('clients.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-people-fill"></i> {{-- Updated to bold People icon --}}
                            <p>
                                Our Clients
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="{{ request()->routeIs('clients.*') ? 'display: block;' : 'display: none;' }}">
                            @can('clients.view')
                            <li class="nav-item">
                                <a href="{{ route('clients.index') }}"
                                class="nav-link {{ request()->routeIs('clients.index') ? 'active' : '' }}">
                                    <i class="bi bi-person-check-fill"></i> {{-- Changed from bi-circle to Clients list Check --}}
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
                            <i class="nav-icon bi bi-patch-check-fill"></i> {{-- Changed to Achievement/Success Icon --}}
                            <p>
                                Success Counters
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="{{ request()->routeIs('counters.*') ? 'display: block;' : 'display: none;' }}">
                            @can('counters.view')
                            <li class="nav-item">
                                <a href="{{ route('counters.index') }}"
                                class="nav-link {{ request()->routeIs('counters.index') ? 'active' : '' }}">
                                    <i class="bi bi-sliders"></i> {{-- Counter/Metrics Icon --}}
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
                            <i class="nav-icon bi bi-hand-thumbs-up-fill"></i> {{-- Changed from star to thumbs up --}}
                            <p>
                                Why Choose Us
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="{{ request()->routeIs('features.*') ? 'display: block;' : 'display: none;' }}">
                            @can('features.view')
                            <li class="nav-item">
                                <a href="{{ route('features.index') }}"
                                class="nav-link {{ request()->routeIs('features.index') ? 'active' : '' }}">
                                    <i class="bi bi-star-fill"></i> {{-- Feature Star Icon --}}
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
                            <i class="nav-icon bi bi-box-seam-fill"></i> {{-- Changed to Material/Box Icon --}}
                            <p>
                                Quality Materials
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="{{ request()->routeIs('materials.*') ? 'display: block;' : 'display: none;' }}">
                            @can('materials.view')
                            <li class="nav-item">
                                <a href="{{ route('materials.index') }}"
                                class="nav-link {{ request()->routeIs('materials.index') ? 'active' : '' }}">
                                    <i class="bi bi-gem"></i> {{-- Quality/Diamond Icon --}}
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
                            <i class="nav-icon bi bi-building-fill"></i> {{-- Changed to Company/Building Icon --}}
                            <p>
                                Who We Are
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="{{ request()->routeIs('about-us.*') ? 'display: block;' : 'display: none;' }}">
                            @can('about-us.view')
                            <li class="nav-item">
                                <a href="{{ route('about-us.index') }}"
                                class="nav-link {{ request()->routeIs('about-us.index') ? 'active' : '' }}">
                                    <i class="bi bi-file-text-fill"></i> {{-- Content/Text Icon --}}
                                    <p>Manage Content</p>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                {{-- Team Members --}}
                @can('team.all')
                <li class="nav-item {{ request()->routeIs('teams.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('teams.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-person-hearts"></i> {{-- Changed to dedicated Team Icon --}}
                        <p>
                            Team Members
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="{{ request()->routeIs('teams.*') ? 'display: block;' : 'display: none;' }}">
                        @can('team.view')
                        <li class="nav-item">
                            <a href="{{ route('teams.index') }}" class="nav-link {{ request()->routeIs('teams.index') ? 'active' : '' }}">
                                <i class="bi bi-person-lines-fill"></i> {{-- Management/List Icon --}}
                                <p>Manage Team</p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan

                {{-- Testimonials --}}
                @can('testimonial.all')
                <li class="nav-item {{ request()->routeIs('testimonials.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('testimonials.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-chat-heart-fill"></i> {{-- Changed to Review/Heart Icon --}}
                        <p>
                            Testimonials
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="{{ request()->routeIs('testimonials.*') ? 'display: block;' : 'display: none;' }}">
                        @can('testimonial.view')
                        <li class="nav-item">
                            <a href="{{ route('testimonials.index') }}" class="nav-link {{ request()->routeIs('testimonials.index') ? 'active' : '' }}">
                                <i class="bi bi-chat-square-text-fill"></i> {{-- Message Icon --}}
                                <p>Manage Testimonials</p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan

                {{-- Reports --}}
                @can('report.view')
                    <li class="nav-item {{ request()->routeIs('reports.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-graph-up-arrow"></i> {{-- Updated to modern Graph Icon --}}
                            <p>
                                Reports
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="{{ request()->routeIs('reports.*') ? 'display: block;' : 'display: none;' }}">
                            <li class="nav-item">
                                <a href="{{ route('reports.total-income') }}"
                                    class="nav-link {{ request()->routeIs('reports.total-income') ? 'active' : '' }}">
                                    <i class="bi bi-cash-coin"></i> {{-- Income Icon --}}
                                    <p>Total Income</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('reports.sales') }}"
                                    class="nav-link {{ request()->routeIs('reports.sales') ? 'active' : '' }}">
                                    <i class="bi bi-cart-check-fill"></i> {{-- Sales Icon --}}
                                    <p>Sales Report</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('reports.purchase') }}"
                                    class="nav-link {{ request()->routeIs('reports.purchase') ? 'active' : '' }}">
                                    <i class="bi bi-bag-check-fill"></i> {{-- Purchase Icon --}}
                                    <p>Purchase Report</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan

                {{-- Customer --}}
                @can('customer.view')
                    <li class="nav-item {{ request()->routeIs('customers.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-person-bounding-box"></i> {{-- Dedicated Customer CRM icon --}}
                            <p>
                                Customer
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="{{ request()->routeIs('customers.*') ? 'display: block;' : 'display: none;' }}">
                            <li class="nav-item">
                                <a href="{{ route('customers.index') }}"
                                    class="nav-link {{ request()->routeIs('customers.index') ? 'active' : '' }}">
                                    <i class="bi bi-people-fill"></i> {{-- Customer Group Icon --}}
                                    <p>Customers</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan

                {{-- Permission --}}
                @can('permission.all')
                    <li class="nav-item {{ request()->routeIs('permissions.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('permissions.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-shield-lock-fill"></i>
                            <p>
                                Permission
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="{{ request()->routeIs('permissions.*') ? 'display: block;' : 'display: none;' }}">
                            @can('permission.view')
                                <li class="nav-item">
                                    <a href="{{ route('permissions.index') }}"
                                        class="nav-link {{ request()->routeIs('permissions.index') ? 'active' : '' }}">
                                        <i class="bi bi-key-fill"></i> {{-- Key/Access Icon --}}
                                        <p>Permission List</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                {{-- Role --}}
                @can('role.all')
                    <li class="nav-item {{ request()->routeIs('roles.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-person-workspace"></i> {{-- Changed from people-fill to Workspace Role --}}
                            <p>
                                Role
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="{{ request()->routeIs('roles.*') ? 'display: block;' : 'display: none;' }}">
                            @can('role.view')
                                <li class="nav-item">
                                    <a href="{{ route('roles.index') }}"
                                        class="nav-link {{ request()->routeIs('roles.index') ? 'active' : '' }}">
                                        <i class="bi bi-list-check"></i>
                                        <p>Role List</p>
                                    </a>
                                </li>
                            @endcan
                            @can('role.create')
                                <li class="nav-item">
                                    <a href="{{ route('roles.create') }}"
                                        class="nav-link {{ request()->routeIs('roles.create') ? 'active' : '' }}">
                                        <i class="bi bi-plus-circle-fill"></i>
                                        <p>Role Create</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                {{-- User --}}
                @can('user.all')
                    <li class="nav-item {{ request()->routeIs('users.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-person-badge-fill"></i>
                            <p>
                                User
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="{{ request()->routeIs('users.*') ? 'display: block;' : 'display: none;' }}">
                            @can('user.view')
                                <li class="nav-item">
                                    <a href="{{ route('users.index') }}"
                                        class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}">
                                        <i class="bi bi-person-lines-fill"></i>
                                        <p>User List</p>
                                    </a>
                                </li>
                            @endcan
                            @can('user.create')
                                <li class="nav-item">
                                    <a href="{{ route('users.create') }}"
                                        class="nav-link {{ request()->routeIs('users.create') ? 'active' : '' }}">
                                        <i class="bi bi-person-plus-fill"></i>
                                        <p>User Create</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                {{-- Contact --}}
                @can('contact-us.view')
                    <li class="nav-item {{ request()->routeIs('contact.us.data') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('contact.us.data') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-telephone-inbound-fill"></i> {{-- Changed from people-fill to Phone Icon --}}
                            <p>
                                Contact
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="{{ request()->routeIs('contact.us.data') ? 'display: block;' : 'display: none;' }}">
                            <li class="nav-item">
                                <a href="{{ route('contact.us.data') }}"
                                    class="nav-link {{ request()->routeIs('contact.us.data') ? 'active' : '' }}">
                                    <i class="bi bi-envelope-paper-fill"></i>
                                    <p>Contact List</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan

                {{-- Newsletter --}}
                @can('newsletter.view')
                    <li class="nav-item {{ request()->routeIs('newsletter.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('newsletter.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-envelope-open-heart-fill"></i> {{-- Modernized Newsletter Icon --}}
                            <p>
                                Newsletter
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="{{ request()->routeIs('newsletter.*') ? 'display: block;' : 'display: none;' }}">
                            <li class="nav-item">
                                <a href="{{ route('newsletter.list') }}"
                                    class="nav-link {{ request()->routeIs('newsletter.list') ? 'active' : '' }}">
                                    <i class="bi bi-send-check-fill"></i>
                                    <p>Newsletter List</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan

                {{-- Setting --}}
                @can('setting.view')
                    <li class="nav-item {{ request()->routeIs('settings.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-sliders2-vertical"></i> {{-- Updated to modern Dashboard/Settings Config Icon --}}
                            <p>
                                Setting
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="{{ request()->routeIs('settings.*') ? 'display: block;' : 'display: none;' }}">
                            <li class="nav-item">
                                <a href="{{ route('settings.index') }}"
                                    class="nav-link {{ request()->routeIs('settings.index') ? 'active' : '' }}">
                                    <i class="bi bi-gear-wide-connected"></i>
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
        $('.nav-item > a').on('click', function(e) {
            var $submenu = $(this).next('.nav-treeview');
            
            // যদি ক্লিক করা আইটেমের অধীনে সাবমেনু থাকে
            if ($submenu.length > 0) {
                e.preventDefault();
                
                // মেনুর অ্যারো আইকন বা অন্য স্টাইল ঘোরানোর জন্য ক্লাস টগল করা
                $(this).parent().toggleClass('menu-open'); 
                
                // ৩০০ মিলিসেকেন্ড স্পিডে স্মুথলি ওপেন/ক্লোজ করা
                $submenu.slideToggle(300); 
            }
        });
    });
</script>
