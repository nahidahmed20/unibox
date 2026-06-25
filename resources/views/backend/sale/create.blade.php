@extends('backend.layouts.app')

@section('title', 'POS Sale')

@push('styles')
    @include('backend.partials.pos-css')

@endpush

@section('content')

    <div class="pos-wrapper">
        <!-- Sidebar -->
        <div class="pos-sidebar">
            <div class="category-item active" data-id="all">
                <i class="fa fa-th-large"></i>
                <span>All</span>
            </div>
            @foreach ($categories as $category)
                <div class="category-item" data-id="{{ $category->id }}">
                    <i class="fa fa-tag"></i>
                    <span>{{ $category->name }}</span>
                </div>
            @endforeach
        </div>

        <!-- Product Area -->
        <div class="product-area">
            <div class="pos-header">
                <div>
                    <h3>Welcome</h3>
                    <small>{{ now()->format('d M Y') }}</small>
                </div>
                <div class="header-action">
                    <input type="text" id="searchProduct" class="form-control" placeholder="Search Product...">
                </div>
            </div>
            
            <div class="product-grid">
                @foreach ($products as $product)
                    <div class="product-card"
                        data-id="{{ $product->id }}"
                        data-name="{{ $product->name }}"
                        data-price="{{ $product->selling_price }}"
                        data-category="{{ $product->category_id }}"
                        data-type="{{ $product->product_type }}"
                        data-stock="{{ $product->stock }}" 
                        data-colors='@json($product->colors)'
                        data-sizes='@json($product->sizes)'
                        data-variants='@json($product->variants)'> 

                        <div class="product-image">
                            @if ($product->image)
                                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">
                            @else
                                <img src="{{ asset('backend/no-image.png') }}" alt="No Image">
                            @endif
                        </div>
                        <div class="product-body">
                            <small class="text-muted">{{ $product->category->name ?? 'Uncategorized' }}</small>
                            <h5 class="text-truncate" title="{{ $product->name }}">{{ $product->name }}</h5>
                            <h5 class="currency-symbol">
                                ৳{{ number_format($product->selling_price, 2) }}
                            </h5>
                            <p class="mb-0 {{ $product->stock > 0 ? 'text-success' : 'text-danger' }}">
                                Stock: {{ $product->stock }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Cart Area -->
        <div class="cart-area">
            <div class="cart-header">
                <h4>Order List</h4>
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addCustomerModal" title="Add New Customer">
                    <i class="fa fa-plus"></i>
                </button>
            </div>
            
            <form id="saleForm" action="{{ route('sales.store') }}" method="POST">
                @csrf
                <input type="hidden" name="sale_date" value="{{ date('Y-m-d') }}">
                <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id ?? '' }}">
                
                <!-- Customer Selection -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Customer <span class="text-danger">*</span></label>
                    <select name="customer_id" class="form-control customer-select" required>
                        <option value="">Select Customer</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}">
                                {{ $customer->phone }} - {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Cart Table -->
                <div class="cart-table">
                    <table class="table table-sm align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th>Product</th>
                                <th>Variation</th>
                                <th>Qty</th>
                                <th>Total</th>
                                <th><i class="fa fa-trash text-danger"></i></th>
                            </tr>
                        </thead>
                        <tbody id="cartBody">
                        </tbody>
                    </table>
                </div>

                <!-- Summary Section -->
                <div class="card border-0 shadow-sm rounded-4 p-4 mt-3">
                    <h5 class="mb-4 fw-bold text-dark">Cart Summary</h5>
                
                    <div class="cart-summary">
                        <!-- Discount -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted fw-semibold">Discount</span>
                            <div class="input-group w-50">
                                <!-- FIX: added name="discount" -->
                                <input type="number" id="discount" name="discount" class="form-control form-control-sm text-end" value="0" min="0" step="0.01">
                                <!-- FIX: added name="discount_type" -->
                                <select id="discount_type" name="discount_type" class="form-select form-select-sm" style="max-width: 80px;">
                                    <option value="flat">Flat</option>
                                    <option value="percent">%</option>
                                </select>
                            </div>
                        </div>

                        <!-- Delivery -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted fw-semibold">Delivery</span>
                            <select name="delivery_type" class="form-select form-select-sm w-50">
                                <option value="inside">Inside</option>
                                <option value="outside">Outside</option>
                                <option value="free">Free</option>
                            </select>
                        </div>

                        <!-- Payment Method -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted fw-semibold">Payment</span>
                            <select name="payment_method" class="form-select form-select-sm w-50">
                                <option value="cash">Cash</option>
                                <option value="bkash">Bkash</option>
                                <option value="nagad">Nagad</option>
                                <option value="bank">Bank</option>
                            </select>
                        </div>

                        <hr class="my-3 text-secondary">
                        
                        <!-- Paid Amount -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted fw-bold">Paid Amount</span>
                            <!-- FIX: added name="paid_amount" -->
                            <input type="number" id="paid_amount" name="paid_amount" class="form-control form-control-sm w-50 text-end fw-bold" value="0" min="0" step="0.01">
                        </div>

                        <!-- Due Amount -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-bold">Due</span>
                            <strong id="dueAmount" class="text-danger fs-6">0.00</strong>
                        </div>

                        <!-- Grand Total -->
                        <div class="d-flex justify-content-between align-items-center bg-dark text-white p-3 rounded-3 mt-4">
                            <span class="fw-bold">Grand Total</span>
                            <strong id="grandTotal" class="fs-4">0.00</strong>
                        </div>
                    </div>
                </div>

                <!-- Hidden Inputs for Form Submission -->
                <input type="hidden" id="grand_total" name="grand_total" value="0">
                <input type="hidden" id="due_amount" name="due_amount" value="0">
                
                <button class="btn btn-success w-100 mt-3 payment-btn fw-bold fs-5 py-2 shadow-sm" type="submit" id="submitBtn">
                    <i class="fa fa-check-circle me-1"></i> CONFIRM PAYMENT
                </button>
            </form>
        </div>
    </div>

    @include('backend.sale.varient_modal')
    @include('backend.sale.customer_modal')

@endsection

@push('scripts')
    @include('backend.partials.pos-js')
@endpush