<style>
    .customer-dashboard{
        background: #f5f5f5;
    }
    .dashboard-sidebar{
        background: #fff;
        border-radius: 10px;
        padding: 25px;
        box-shadow: 0 0 20px rgba(0,0,0,0.05);
    }
    .customer-info{
        margin-bottom: 30px;
    }
    .customer-image img{
        width: 90px;
        height: 90px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 15px;
        border: 3px solid #ff6600;
    }
    .customer-info h4{
        margin-bottom: 5px;
    }
    .customer-info p{
        color: #777;
        font-size: 14px;
    }
    .dashboard-menu{
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .dashboard-menu li{
        margin-bottom: 12px;
    }
    .dashboard-menu li a,
    .logout-btn{
        width: 100%;
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        background: #f8f8f8;
        border: none;
        padding: 14px 18px;
        border-radius: 8px;
        color: #222;
        transition: .3s;
        font-weight: 500;
    }
    .dashboard-menu li a:hover,
    .dashboard-menu li a.active,
    .logout-btn:hover{
        background: #E53E3E;
        color: #fff;
    }
    .dashboard-card{
        background: #fff;
        border-radius: 10px;
        padding: 25px;
        box-shadow: 0 0 20px rgba(0,0,0,0.05);
    }
    .stats-card{
        display: flex;
        align-items: center;
        gap: 18px;
    }
    .stats-icon{
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #E53E3E;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }
    .stats-icon.processing{
        background: #00b894;
    }
    .stats-icon.complete{
        background: #0984e3;
    }
    .dashboard-table thead{
        background: #f8f8f8;
    }
    .dashboard-table th{
        border: none;
        padding: 15px;
    }
    .dashboard-table td{
        vertical-align: middle;
        padding: 15px;
    }
    .order-btn,
    .view-all-btn{
        background: #E53E3E;
        color: #fff;
        padding: 7px 14px;
        border-radius: 5px;
        text-decoration: none;
        transition: .3s;
    }
    .order-btn:hover,
    .view-all-btn:hover{
        background: #E53E3E;
        color: #fff;
    }
    @media(max-width: 991px){

        .dashboard-sidebar{
            margin-bottom: 30px;
        }

    }
</style>
<div class="dashboard-sidebar">
    <div class="customer-info text-center">
        <div class="customer-image">
            @if(auth('customer')->user()->image)
                <img src="{{ asset(auth('customer')->user()->image) }}" alt="">
            @else
                <img src="{{ asset('frontend/assets/img/user.png') }}" alt="">
            @endif
        </div>
        <h4>
            {{ auth('customer')->user()->name }}
        </h4>
        <p>
            {{ auth('customer')->user()->phone }}
        </p>
    </div>

    <ul class="dashboard-menu">
        <li>
            <a href="{{ route('customer.dashboard') }}"
            class="menu-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-gauge"></i>
                Dashboard
            </a>
        </li>

        <li>
            <a href="{{ route('customer.orders') }}"
            class="menu-link {{ request()->routeIs('customer.orders') ? 'active' : '' }}">
                <i class="fa-solid fa-bag-shopping"></i>
                My Orders
            </a>
        </li>

        <li>
            <a href="{{ route('customer.profile') }}"
            class="menu-link {{ request()->routeIs('customer.profile') ? 'active' : '' }}">
                <i class="fa-solid fa-user"></i>
                My Profile
            </a>
        </li>

        {{-- <li>
            <a href="{{ route('customer.address') }}"
            class="menu-link {{ request()->routeIs('customer.address') ? 'active' : '' }}">
                <i class="fa-solid fa-location-dot"></i>
                Address
            </a>
        </li> --}}

        <li>
            <a href="{{ route('customer.change.password') }}"
            class="menu-link {{ request()->routeIs('customer.change.password') ? 'active' : '' }}">
                <i class="fa-solid fa-lock"></i>
                Change Password
            </a>
        </li>

        <li>
            <form action="{{ route('customer.logout') }}" method="POST">
                @csrf

                <button type="submit" class="logout-btn">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    Logout
                </button>
            </form>
        </li>

    </ul>
</div>