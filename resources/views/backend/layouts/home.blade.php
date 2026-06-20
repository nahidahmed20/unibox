@extends('backend.layouts.app')
@section('title', 'Dashboard')

@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;600;700&display=swap');

:root{
    --bg:#f5f7fb;
    --card:#ffffff;
    --text:#111827;
    --muted:#6b7280;
    --border:#e5e7eb;
    --radius:16px;
    --shadow:0 10px 30px rgba(17,24,39,0.06);
}

.app-content{
    background: var(--bg);
}

/* KPI CARDS */
.small-box{
    background: #fff;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 18px;
    transition: .25s ease;
    border-left: 4px solid transparent;
}

.small-box:hover{
    transform: translateY(-5px);
}

.small-box h3{
    font-size: 26px;
    font-weight: 700;
    color: var(--text);
}

.small-box p{
    color: var(--muted);
    margin: 0;
}

/* CARDS */
.card{
    border-radius: var(--radius);
    border: 1px solid var(--border);
    box-shadow: var(--shadow);
}

.card-header{
    background: #fff;
    border-bottom: 1px solid var(--border);
    font-weight: 600;
}

/* TABLE */
.table thead{
    background: #f9fafb;
}

.taka-font{
    font-family: 'Noto Sans Bengali', 'Inter', sans-serif;
    font-weight: 700;
}
</style>

<main class="app-main">

    {{-- HEADER --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="fw-bold">Dashboard</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            {{-- ================= KPI CARDS ================= --}}
            <div class="row g-3 mb-4">

                <div class="col-lg-3 col-6">
                    <div class="small-box">
                        <h3 class="taka-font">৳ {{ number_format($todayTotalSales,2) }}</h3>
                        <p>Today Sales</p>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box">
                        <h3>{{ $newOrdersCount }}</h3>
                        <p>New Orders</p>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box">
                        <h3>{{ $customersCount }}</h3>
                        <p>Customers</p>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box">
                        <h3>{{ $productStock }}</h3>
                        <p>Product Stock</p>
                    </div>
                </div>

            </div>

            {{-- ================= QUICK ACTIONS ================= --}}
            <div class="card mb-4">
                <div class="card-header">Quick Actions</div>
                <div class="card-body d-flex gap-2 flex-wrap">

                    <a href="{{ route('orders.index') }}" class="btn btn-dark btn-sm">Orders</a>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-dark btn-sm">Products</a>
                    <a href="{{ route('expenses.index') }}" class="btn btn-outline-dark btn-sm">Expenses</a>
                    <a href="{{ route('customers.index') }}" class="btn btn-outline-dark btn-sm">Customers</a>

                </div>
            </div>

            {{-- ================= CHARTS ================= --}}
            <div class="row g-3">

                <div class="col-lg-7">
                    <div class="card">
                        <div class="card-header">Last 7 Days Sales</div>
                        <div class="card-body">
                            <canvas id="salesChart" height="120"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card">
                        <div class="card-header">This Year Sales</div>
                        <div class="card-body">
                            <canvas id="yearSalesChart" height="200"></canvas>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ================= INCOME VS EXPENSE ================= --}}
            <div class="card mt-4">
                <div class="card-header">Income vs Expense</div>
                <div class="card-body">
                    <canvas id="pieChart" height="120"></canvas>
                </div>
            </div>

            {{-- ================= RECENT ORDERS ================= --}}
            <div class="card mt-4">
                <div class="card-header">Recent Orders</div>
                <div class="card-body p-0">

                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>Order</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($recentOrders as $order)
                                <tr>
                                    <td>{{ $order->order_number }}</td>
                                    <td>{{ $order->customer_name }}</td>
                                    <td>৳ {{ number_format($order->total,2) }}</td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ ucfirst($order->order_status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>
</main>

{{-- ================= CHART JS ================= --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
new Chart(document.getElementById('salesChart'), {
    type: 'line',
    data: {
        labels: @json($chartLabels),
        datasets: [{
            data: @json($chartData),
            borderColor: '#111827',
            backgroundColor: 'rgba(17,24,39,0.08)',
            fill: true,
            tension: 0.4
        }]
    }
});

new Chart(document.getElementById('yearSalesChart'), {
    type: 'bar',
    data: {
        labels: @json($yearLabels),
        datasets: [{
            data: @json($yearData),
            backgroundColor: '#111827'
        }]
    }
});

new Chart(document.getElementById('pieChart'), {
    type: 'doughnut',
    data: {
        labels: ['Income', 'Expense'],
        datasets: [{
            data: [
                {{ $todayTotalSales }},
                {{ $todayExpenses ?? 0 }}
            ],
            backgroundColor: ['#111827', '#ef4444']
        }]
    }
});
</script>

@endsection