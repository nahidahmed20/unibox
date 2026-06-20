@extends('backend.layouts.app')
@section('title', 'Total Income Report')

@section('content')
<style>
    :root {
        --shopify-bg: #f6f6f7;
        --shopify-card-border: #e1e3e5;
        --shopify-text-main: #202223;
        --shopify-text-sub: #6d7175;
        --shopify-primary: #008060; /* Shopify Green */
    }

    body { background-color: var(--shopify-bg); color: var(--shopify-text-main); }

    /* Header Styling */
    .page-title { font-size: 1.5rem; font-weight: 700; color: var(--shopify-text-main); }

    /* Unified Filter Bar */
    .filter-container {
        background: #fff;
        border: 1px solid var(--shopify-card-border);
        border-radius: 12px;
        padding: 12px 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .btn-period {
        border: 1px solid var(--shopify-card-border);
        background: #fff;
        color: var(--shopify-text-sub);
        padding: 6px 16px;
        font-weight: 500;
        transition: all 0.2s;
    }
    .btn-period:hover { background: #f1f2f3; }
    .btn-period.active {
        background: #f1f2f3;
        border-color: #8c9196;
        color: var(--shopify-text-main);
    }

    /* Modern Card Styling */
    .s-card {
        background: #fff;
        border: 1px solid var(--shopify-card-border);
        border-radius: 12px;
        padding: 24px;
        height: 100%;
        transition: box-shadow 0.3s ease;
    }
    .s-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.05); }

    .card-label {
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--shopify-text-sub);
        margin-bottom: 8px;
    }

    .card-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--shopify-text-main);
    }

    .icon-box {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
    }

    /* Theme colors */
    .c-primary { background: #f0f5ff; color: #2c6ecb; }
    .c-success { background: #e6f4ea; color: #008060; }
    .c-danger { background: #fff1f0; color: #d72c0d; }
    .c-dark { background: #202223; color: #ffffff; }

    .net-income-section {
        background: #fff;
        border: 2px solid #202223;
        border-radius: 12px;
        padding: 30px;
    }

    .form-control-sm { border-radius: 8px; border: 1px solid var(--shopify-card-border); }
</style>

<div class="app-content pt-5">
    <div class="container-fluid" style="max-width: 1200px;">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="page-title">Financial Summary</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Analytics</a></li>
                    <li class="breadcrumb-item active">Reports</li>
                </ol>
            </nav>
        </div>

        <div class="filter-container mb-4">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="btn-group shadow-none" id="periodFilterGroup">
                        <button type="button" class="btn btn-period active" data-value="today">Today</button>
                        <button type="button" class="btn btn-period" data-value="month">Month</button>
                        <button type="button" class="btn btn-period" data-value="year">Year</button>
                    </div>
                </div>
                <div class="col-lg-6 mt-3 mt-lg-0">
                    <form id="dateFilterForm" class="d-flex align-items-center gap-2 justify-content-lg-end">
                        <div class="input-group input-group-sm w-auto">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-calendar3"></i></span>
                            <input type="date" name="start_date" id="start_date" class="form-control border-start-0 ps-0 shadow-none">
                        </div>
                        <span class="text-muted">to</span>
                        <div class="input-group input-group-sm w-auto">
                            <input type="date" name="end_date" id="end_date" class="form-control shadow-none">
                        </div>
                        <button type="submit" class="btn btn-dark btn-sm px-3 rounded-2">Apply</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="s-card">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="card-label">Ecommerce Income</div>
                            <div class="card-value"><span class="text-muted" style="font-size: 1.2rem;">৳</span> <span id="orderIncome">0.00</span></div>
                        </div>
                        <div class="icon-box c-primary"><i class="bi bi-globe2"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="s-card">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="card-label">In-Store (POS)</div>
                            <div class="card-value"><span class="text-muted" style="font-size: 1.2rem;">৳</span> <span id="posIncome">0.00</span></div>
                        </div>
                        <div class="icon-box c-success"><i class="bi bi-shop-window"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="s-card">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="card-label">Total Expenses</div>
                            <div class="card-value"><span class="text-muted" style="font-size: 1.2rem;">৳</span> <span id="totalExpenses">0.00</span></div>
                        </div>
                        <div class="icon-box c-danger"><i class="bi bi-credit-card-2-back"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="net-income-section text-center">
            <div class="card-label text-center">Net Profit / Loss</div>
            <div class="display-4 fw-bold" style="color: #202223;">
                ৳ <span id="totalIncome">0.00</span>
            </div>
            <p class="text-muted small mt-2 mb-0">Total revenue after subtracting all operating expenses.</p>
        </div>

    </div>
</div>
@endsection
@section('scripts')

@endsection