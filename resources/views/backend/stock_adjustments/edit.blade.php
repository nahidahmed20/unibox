@extends('backend.layouts.app')

@section('title', 'Edit Stock Adjustment')

@section('content')
@push('styles')
    <style>
        .shopify-card { background: #fff; border: 1px solid #e1e3e5; border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04); }
        .form-label { font-weight: 600; color: #212b36; font-size: 14px; }
        .form-control, .form-select { border-radius: 8px; border: 1px solid #cccccc; padding: 10px 14px; }
        .form-control:focus, .form-select:focus { border-color: #000032; box-shadow: none; }
        .table-custom thead { background: #f6f6f7; }
        .table-custom th { color: #5c5f62; font-weight: 600; font-size: 13px; text-transform: uppercase; padding: 12px; }
        .btn-shopify { background: #000032; color: #fff; border-radius: 8px; font-weight: 500; padding: 10px 24px; }
        .btn-shopify:hover { background: #000050; color: #fff; }
    </style>
@endpush

    <div class="app-content-header mb-4 mt-3">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold" style="color: #212b36;">Edit Stock Adjustment</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end mb-0 bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('stock-adjustments.index') }}" class="text-decoration-none text-muted">Adjustment List</a></li>
                        <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            
            <form action="{{ route('stock-adjustments.update', $adjustment->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="shopify-card p-4 mb-4">
                    <h5 class="fw-bold mb-4 text-dark"><i class="fa-solid fa-file-invoice me-2 text-muted"></i> Master Information</h5>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Adjustment No</label>
                            <input type="text" class="form-control bg-light fw-bold text-secondary" value="{{ $adjustment->adjustment_no }}" readonly>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Adjustment Date <span class="text-danger">*</span></label>
                            <input type="date" name="adjustment_date" class="form-control @error('adjustment_date') is-invalid @enderror" value="{{ old('adjustment_date', $adjustment->adjustment_date) }}" required>
                            @error('adjustment_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Adjustment Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                <option value="addition" {{ old('type', $adjustment->type) == 'addition' ? 'selected' : '' }}>Addition (+ Increase Stock)</option>
                                <option value="subtraction" {{ old('type', $adjustment->type) == 'subtraction' ? 'selected' : '' }}>Subtraction (- Decrease Stock)</option>
                            </select>
                            @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Reason <span class="text-danger">*</span></label>
                            <select name="reason" class="form-select text-capitalize @error('reason') is-invalid @enderror" required>
                                <option value="received" {{ old('reason', $adjustment->reason) == 'received' ? 'selected' : '' }}>Received / New Stock</option>
                                <option value="damage" {{ old('reason', $adjustment->reason) == 'damage' ? 'selected' : '' }}>Damage / Broken</option>
                                <option value="lost" {{ old('reason', $adjustment->reason) == 'lost' ? 'selected' : '' }}>Lost / Stolen</option>
                                <option value="correction" {{ old('reason', $adjustment->reason) == 'correction' ? 'selected' : '' }}>Inventory Correction</option>
                            </select>
                            @error('reason') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="shopify-card p-4 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-boxes-stacked me-2 text-muted"></i> Adjusted Items</h5>
                        <button type="button" class="btn btn-sm btn-outline-dark rounded-pill px-3" id="add-item-row">
                            <i class="fa fa-plus me-1"></i> Add Item Row
                        </button>
                    </div>

                    <div class="table-responsive border rounded-3">
                        <table class="table table-custom align-middle mb-0" id="adjustment-items-table">
                            <thead>
                                <tr>
                                    <th style="width: 40%;">Product <span class="text-danger">*</span></th>
                                    <th style="width: 20%;" class="text-center">Color ID (Optional)</th>
                                    <th style="width: 20%;" class="text-center">Size ID (Optional)</th>
                                    <th style="width: 15%;" class="text-center">Quantity <span class="text-danger">*</span></th>
                                    <th style="width: 5%;" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(old('items', $adjustment->items) as $index => $item)
                                    <tr>
                                        <td>
                                            <select name="items[{{ $index }}][product_id]" class="form-select select-product" required>
                                                <option value="">Select Product</option>
                                                @foreach($products as $prod)
                                                    <option value="{{ $prod->id }}" {{ (isset($item['product_id']) ? $item['product_id'] : $item->product_id) == $prod->id ? 'selected' : '' }}>
                                                        {{ $prod->name }} (SKU: {{ $prod->sku ?? 'N/A' }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="items[{{ $index }}][color_id]" class="form-control text-center" placeholder="Color ID" value="{{ isset($item['color_id']) ? $item['color_id'] : $item->color_id }}">
                                        </td>
                                        <td>
                                            <input type="number" name="items[{{ $index }}][size_id]" class="form-control text-center" placeholder="Size ID" value="{{ isset($item['size_id']) ? $item['size_id'] : $item->size_id }}">
                                        </td>
                                        <td>
                                            <input type="number" name="items[{{ $index }}][quantity]" class="form-control text-center fw-bold" min="1" placeholder="Qty" value="{{ isset($item['quantity']) ? $item['quantity'] : $item->quantity }}" required>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-soft-danger remove-item-row" title="Remove Row">
                                                <i class="fa-regular fa-trash-can"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">No items added yet. Click 'Add Item Row' to add.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        <label class="form-label">Notes / Remarks</label>
                        <textarea name="note" class="form-control" rows="3" placeholder="Write any specific reason or adjustment notes here...">{{ old('note', $adjustment->note) }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mb-5">
                    <a href="{{ route('stock-adjustments.index') }}" class="btn btn-light border rounded-pill px-4 fw-bold">Cancel</a>
                    <button type="submit" class="btn btn-shopify rounded-pill shadow-sm"><i class="fa-regular fa-square-check me-1"></i> Update Adjustment</button>
                </div>
            </form>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let rowIndex = {{ count(old('items', $adjustment->items)) }};

            $('#add-item-row').on('click', function() {
                let html = `
                    <tr>
                        <td>
                            <select name="items[${rowIndex}][product_id]" class="form-select select-product" required>
                                <option value="">Select Product</option>
                                @foreach($products as $prod)
                                    <option value="{{ $prod->id }}">{{ $prod->name }} (SKU: {{ $prod->sku ?? 'N/A' }})</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" name="items[${rowIndex}][color_id]" class="form-control text-center" placeholder="Color ID">
                        </td>
                        <td>
                            <input type="number" name="items[${rowIndex}][size_id]" class="form-control text-center" placeholder="Size ID">
                        </td>
                        <td>
                            <input type="number" name="items[${rowIndex}][quantity]" class="form-control text-center fw-bold" min="1" placeholder="Qty" required>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-soft-danger remove-item-row" title="Remove Row">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </td>
                    </tr>
                `;
                
                $('#adjustment-items-table tbody').append(html);
                rowIndex++;
            });

            $(document).on('click', '.remove-item-row', function() {
                let totalRows = $('#adjustment-items-table tbody tr').length;
                if(totalRows > 1) {
                    $(this).closest('tr').remove();
                } else {
                    toastr.warning("At least one item row is required!");
                }
            });
        });
    </script>
@endpush