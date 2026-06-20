@extends('frontend.layouts.app')

@section('title', 'Customer Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 bg-light min-vh-100 p-3">
            <h4 class="mb-4">Customer Panel</h4>
            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a href="#" class="nav-link active">Dashboard</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="#" class="nav-link">Profile</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="#" class="nav-link">Orders</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="#" class="nav-link">Settings</a>
                </li>
                <li class="nav-item mt-3">
                    <form action="{{ route('customer_logout') }}" method="POST">
                        @csrf
                        <button class="btn btn-danger w-100" type="submit">Logout</button>
                    </form>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 p-4">
            <h2>Welcome, {{ auth('customer')->user()->name }}!</h2>
            <p class="text-muted">This is your customer dashboard.</p>

            <!-- Summary Cards -->
            <div class="row my-4">
                <div class="col-md-4 mb-3">
                    <div class="card text-white bg-primary h-100">
                        <div class="card-body">
                            <h5 class="card-title">Orders</h5>
                            <p class="card-text display-6">12</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card text-white bg-success h-100">
                        <div class="card-body">
                            <h5 class="card-title">Pending Payments</h5>
                            <p class="card-text display-6">3</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card text-white bg-warning h-100">
                        <div class="card-body">
                            <h5 class="card-title">Support Tickets</h5>
                            <p class="card-text display-6">1</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Table -->
            <div class="card mt-4">
                <div class="card-header">
                    Recent Orders
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#1001</td>
                                <td>2025-10-28</td>
                                <td><span class="badge bg-success">Completed</span></td>
                                <td>$120.00</td>
                            </tr>
                            <tr>
                                <td>#1002</td>
                                <td>2025-10-25</td>
                                <td><span class="badge bg-warning">Pending</span></td>
                                <td>$85.50</td>
                            </tr>
                            <tr>
                                <td>#1003</td>
                                <td>2025-10-20</td>
                                <td><span class="badge bg-danger">Cancelled</span></td>
                                <td>$45.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
