
@extends('backend.layouts.app')
@section('title', 'Barcode Print')

@section('content')

<div class="app-content-header mb-4 mt-3">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0 fw-bold" style="color: #212b36;">Barcode Print</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end mb-0 bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none text-muted">Products</a></li>
                    <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">Barcode Print</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('products.barcode.print') }}" method="POST" target="_blank" id="barcodeForm">
    @csrf
    <div class="app-content">
        <div class="container-fluid">
            <div class="row g-4">

                {{-- ================= LEFT: PRINT / LABEL SETTINGS ================= --}}
                <div class="col-lg-4">
                    <div class="card modern-card mb-4">
                        <div class="modern-card-header">
                            <h5 class="card-title mb-0"><i class="fa-solid fa-sliders text-muted me-2"></i> Label Settings</h5>
                        </div>
                        <div class="card-body">

                            <label class="form-label fw-bold small text-uppercase text-muted mb-2">Printer Preset</label>
                            <div class="d-grid gap-2 mb-3">
                                <button type="button" class="btn btn-outline-dark btn-sm text-start preset-btn active-preset"
                                        data-w="50" data-h="30" data-c="1" data-g="2">
                                    <i class="fa-solid fa-receipt me-1"></i> Thermal Roll (50 x 30mm, 1 column)
                                </button>
                                <button type="button" class="btn btn-outline-dark btn-sm text-start preset-btn"
                                        data-w="38" data-h="20" data-c="1" data-g="2">
                                    <i class="fa-solid fa-receipt me-1"></i> Thermal Roll - Small (38 x 20mm)
                                </button>
                                <button type="button" class="btn btn-outline-dark btn-sm text-start preset-btn"
                                        data-w="45" data-h="25" data-c="4" data-g="2">
                                    <i class="fa-regular fa-file me-1"></i> A4 Sheet (4 column)
                                </button>
                                <button type="button" class="btn btn-outline-dark btn-sm text-start preset-btn"
                                        data-w="63" data-h="30" data-c="3" data-g="2">
                                    <i class="fa-regular fa-file me-1"></i> A4 Sheet (3 column, boro label)
                                </button>
                            </div>

                            <div class="row g-2 mb-2">
                                <div class="col-6">
                                    <label class="form-label small mb-1">Label Width (mm)</label>
                                    <input type="number" step="0.1" name="label_width" id="label_width" class="form-control" value="50" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label small mb-1">Label Height (mm)</label>
                                    <input type="number" step="0.1" name="label_height" id="label_height" class="form-control" value="30" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label small mb-1">Columns</label>
                                    <input type="number" name="columns" id="columns" class="form-control" value="1" min="1" max="10" required>
                                    <small class="text-muted">Thermal printer hole 1 rakhun</small>
                                </div>
                                <div class="col-6">
                                    <label class="form-label small mb-1">Gap (mm)</label>
                                    <input type="number" step="0.1" name="gap" id="gap" class="form-control" value="2">
                                </div>
                            </div>

                            <div class="form-check form-switch mt-3">
                                <input class="form-check-input" type="checkbox" name="show_price" value="1" id="show_price" checked>
                                <label class="form-check-label" for="show_price">Label e Price dekhabe</label>
                            </div>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="show_name" value="1" id="show_name" checked>
                                <label class="form-check-label" for="show_name">Label e Product Name dekhabe</label>
                            </div>

                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small">Selected:</span>
                                <span class="fw-bold"><span id="selectedCount">0</span> product(s), <span id="totalLabelCount">0</span> label(s)</span>
                            </div>

                            <button type="submit" class="btn btn-dark w-100 rounded-pill fw-bold mt-3 shadow-sm" id="printBtn" disabled>
                                <i class="fa-solid fa-print me-1"></i> Generate & Print
                            </button>
                        </div>
                    </div>
                </div>

                {{-- ================= RIGHT: PRODUCT SELECTION ================= --}}
                <div class="col-lg-8">
                    <div class="card modern-card">
                        <div class="modern-card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0"><i class="fa-solid fa-boxes-stacked text-muted me-2"></i> Select Products</h5>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="selectAll">
                                <label class="form-check-label fw-bold" for="selectAll">Select All (filtered)</label>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="p-3">
                                <table class="table table-modern table-hover w-100" id="productBarcodeTable">
                                    <thead>
                                        <tr>
                                            <th width="4%"></th>
                                            <th width="8%">Image</th>
                                            <th>Product Name</th>
                                            <th>SKU</th>
                                            <th>Price (<span class="taka-symbol">৳</span>)</th>
                                            <th width="15%">Qty (labels)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $product)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="form-check-input row-check">
                                            </td>
                                            <td>
                                                @if($product->image)
                                                    <img src="{{ asset($product->image) }}" width="40" height="40" class="rounded" style="object-fit:cover;">
                                                @else
                                                    <span class="text-muted small">No Image</span>
                                                @endif
                                            </td>
                                            <td class="fw-bold text-dark">{{ $product->name }}</td>
                                            <td class="font-monospace text-muted">{{ $product->sku }}</td>
                                            <td class="fw-bold text-success">{{ number_format($product->selling_price, 2) }}</td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm qty-input"
                                                    value="1" min="1" max="500" disabled
                                                    data-product-id="{{ $product->id }}">
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
        </div>
    </div>

    {{-- hidden inputs get injected here right before submit --}}
    <div id="hiddenInputsContainer"></div>
</form>

@endsection

@push('scripts')
<script>
$(document).ready(function () {

    var table = $('#productBarcodeTable').DataTable({
        pageLength: 10,
        order: [[2, 'asc']],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search products...",
        }
    });

    function updateSummary() {
        let selectedRows = $('.row-check:checked');
        let totalQty = 0;
        selectedRows.each(function () {
            let qty = parseInt($(this).closest('tr').find('.qty-input').val()) || 0;
            totalQty += qty;
        });
        $('#selectedCount').text(selectedRows.length);
        $('#totalLabelCount').text(totalQty);
        $('#printBtn').prop('disabled', selectedRows.length === 0);
    }

    $(document).on('change', '.row-check', function () {
        $(this).closest('tr').find('.qty-input').prop('disabled', !this.checked);
        updateSummary();
    });

    $(document).on('input', '.qty-input', updateSummary);

    $('#selectAll').on('change', function () {
        let checked = this.checked;
        // affects rows matching current search/filter across all pages
        table.rows({ search: 'applied' }).nodes().to$().find('.row-check').prop('checked', checked).trigger('change');
    });

    // preset buttons
    $('.preset-btn').on('click', function () {
        $('#label_width').val($(this).data('w'));
        $('#label_height').val($(this).data('h'));
        $('#columns').val($(this).data('c'));
        $('#gap').val($(this).data('g'));
        $('.preset-btn').removeClass('btn-dark active-preset').addClass('btn-outline-dark');
        $(this).removeClass('btn-outline-dark').addClass('btn-dark active-preset');
    });

    $('#barcodeForm').on('submit', function () {
        $('#hiddenInputsContainer').empty();
        $('.row-check:checked').each(function () {
            let row = $(this).closest('tr');
            let qtyInput = row.find('.qty-input');
            let productId = qtyInput.data('product-id');
            let qty = qtyInput.val();

            $('#hiddenInputsContainer').append(
                '<input type="hidden" name="products[' + productId + '][qty]" value="' + qty + '">'
            );
        });
    });

});
</script>
@endpush
