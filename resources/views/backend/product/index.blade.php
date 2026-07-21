@extends('backend.layouts.app')
@section('title', 'Product List')

@section('content')

<div class="app-content-header mb-4 mt-3">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0 fw-bold" style="color: #212b36;">Products</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end mb-0 bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
                    <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">Product List</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="card modern-card">
            <div class="modern-card-header">
                <h4 class="card-title"><i class="fa-solid fa-list-ul text-muted me-2"></i> All Products</h4>
                <a href="{{ route('products.create') }}" class="btn btn-dark rounded-pill px-4 fw-bold shadow-sm">
                    <i class="fa-solid fa-plus me-1"></i> Add Product
                </a>
            </div>
            
            <div class="card-body p-0">
                <div class="p-4">
                    <table class="table table-modern table-hover w-100" id="productsTable">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="20%">Product Name</th>
                                <th>SKU</th>
                                <th>Category</th>
                                <th>Image</th>
                                <th>Cost (<span class="taka-symbol">৳</span>)</th>
                                <th>Selling (<span class="taka-symbol">৳</span>)</th>
                                <th>Stock</th> <th>Status</th>
                                <th width="12%" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5" class="text-end text-uppercase text-muted" style="font-size: 12px;">Page Total:</th>
                                <th id="totalPurchase" class="text-danger fw-bold fs-6"></th>
                                <th id="totalSelling" class="text-success fw-bold fs-6"></th>
                                <th colspan="3"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header modal-header-modern">
                <h5 class="modal-title modal-title-modern">
                    <i class="fa-solid fa-box-open text-muted me-2"></i> Product Details
                </h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="productModalBody">
                </div>
            <div class="modal-footer border-top-0 bg-light">
                <button type="button" class="btn btn-secondary px-4 rounded-pill fw-bold" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function () {

    // --- DataTable Initialization ---
    var table = $('#productsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('products.index') }}",
        columns: [
            { data: 'DT_RowIndex',    name: 'DT_RowIndex',    orderable: false, searchable: false },
            { data: 'name',           name: 'name', className: 'fw-bold text-dark' },
            { data: 'sku',            name: 'sku', className: 'font-monospace text-muted' },
            { data: 'category_name',  name: 'category_name' },
            { data: 'image',          name: 'image',          orderable: false, searchable: false },
            { data: 'purchase_price', name: 'purchase_price', className: 'fw-bold' },
            { data: 'selling_price',  name: 'selling_price', className: 'fw-bold text-success' },
            { data: 'stock',          name: 'stock',          orderable: false, searchable: false }, // JS এ ম্যাপ করা হলো
            { data: 'status',         name: 'status' },
            { data: 'action',         name: 'action',         orderable: false, searchable: false, className: 'text-center' },
        ],
        dom: '<"row align-items-center mb-4"<"col-md-4"l><"col-md-4 d-flex justify-content-center"B><"col-md-4 d-flex justify-content-end"f>>rt<"d-flex justify-content-between align-items-center mt-4"ip>',
        buttons: ['copy', 'excel', 'csv', 'pdf', 'print'],
        order: [[1, 'asc']],
        responsive: true,
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search products...",
            lengthMenu: "Show _MENU_ entries",
            paginate: {
                previous: '<i class="fa-solid fa-angle-left"></i>',
                next: '<i class="fa-solid fa-angle-right"></i>'
            }
        },
        footerCallback: function (row, data, start, end, display) {
            let api = this.api();
            let intVal = function (i) {
                return typeof i === 'string'
                    ? i.replace(/[\$,]/g, '') * 1
                    : typeof i === 'number' ? i : 0;
            };

            let totalPurchase = api.column(5, { page: 'current' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);
            let totalSelling  = api.column(6, { page: 'current' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);

            $('#totalPurchase').html('<span class="taka-symbol">৳</span> ' + totalPurchase.toLocaleString('en-IN', {minimumFractionDigits: 2}));
            $('#totalSelling').html('<span class="taka-symbol">৳</span> ' + totalSelling.toLocaleString('en-IN', {minimumFractionDigits: 2}));
        }
    });

    // --- Delete Confirmation ---
    $(document).on('click', '.btn-delete', function (e) {
        e.preventDefault();
        let form = $(this).closest('form');
        if (confirm("Are you sure you want to delete this product? This action cannot be undone.")) {
            form.submit();
        }
    });

    $(document).on('click', '.btn-show', function () {
        let id = $(this).data('id');
        let btn = $(this);
        
        let originalHtml = btn.html();
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');

        $.ajax({
            url: '/admin/products/' + id,
            type: 'GET',
            success: function (res) {
                btn.prop('disabled', false).html(originalHtml);

                let mainImageHtml = res.image
                    ? `<img src="${res.image}" class="img-fluid rounded-4 mb-2 shadow-sm border" style="max-height:220px; width:100%; object-fit:cover;">`
                    : '<div class="bg-light rounded-4 border p-4 text-center text-muted"><i class="fa-regular fa-image fs-1 mb-2"></i><br>No image</div>';

                let sizeGuideHtml = res.size_guide
                    ? `<img src="${res.size_guide}" class="img-fluid rounded-4 mb-2 shadow-sm border" style="max-height:220px; width:100%; object-fit:cover;">`
                    : '<span class="text-muted fst-italic">Not uploaded</span>';

                let multipleImagesHtml = (res.images && res.images.length)
                    ? res.images.map(img => `<img src="${img}" class="img-thumbnail rounded-3 shadow-sm me-2 mb-2" style="width:70px; height:70px; object-fit:cover;">`).join('')
                    : '<span class="text-muted fst-italic">No gallery images</span>';

                let colorImagesHtml = (res.colors && res.colors.length)
                    ? res.colors.map(c => {
                        if (c.images && c.images.length) {
                            return `
                                <div class="mb-3 bg-light p-3 rounded-3 border">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="rounded-circle shadow-sm border me-2" style="background:${c.code}; width:20px; height:20px; display:inline-block;"></span>
                                        <strong class="text-dark">${c.name}</strong>
                                    </div>
                                    <div class="d-flex flex-wrap">
                                        ${c.images.map(img => `<img src="${img}" class="img-thumbnail rounded-3 shadow-sm me-2 mb-2" style="width:65px; height:65px; object-fit:cover;">`).join('')}
                                    </div>
                                </div>`;
                        }
                        return `
                            <div class="mb-2 d-flex align-items-center">
                                <span class="rounded-circle shadow-sm border me-2" style="background:${c.code}; width:16px; height:16px; display:inline-block;"></span>
                                <strong class="text-dark">${c.name}</strong>
                                <span class="text-muted ms-2" style="font-size:12px;">(No specific images)</span>
                            </div>`;
                    }).join('')
                    : '<span class="text-muted fst-italic">No color variants</span>';

                let sizesHtml = (res.sizes && res.sizes.length)
                    ? res.sizes.map(s => `<span class="badge bg-dark rounded-pill px-3 py-2 me-1 mb-1">${s.name}</span>`).join('')
                    : '<span class="text-muted">-</span>';

                let sizeWiseStockHtml = '';
                if (res.stocks && res.stocks.length > 0) {
                    let grouped = {};
                    res.stocks.forEach(stock => {
                        let sizeName = stock.size?.name ?? 'Standard';
                        if (!grouped[sizeName]) grouped[sizeName] = [];
                        grouped[sizeName].push(stock);
                    });

                    Object.keys(grouped).forEach(size => {
                        let totalQty = grouped[size].reduce((sum, s) => sum + parseInt(s.quantity || 0), 0);
                        sizeWiseStockHtml += `
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3 d-flex align-items-center">
                                    <span class="badge bg-dark rounded-pill px-3 py-2 fs-6 me-2">Size: ${size}</span>
                                    <span class="text-muted" style="font-size:14px;">Total Qty: <strong class="text-dark">${totalQty}</strong></span>
                                </h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered table-modern mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="50%">Color Variant</th>
                                                <th class="text-center">Stock Qty</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                        `;

                        grouped[size].forEach(stock => {
                            let colorName = stock.color?.name ?? '—';
                            let colorCode = stock.color?.code ?? null;
                            let qty       = stock.quantity ?? 0;
                            sizeWiseStockHtml += `
                                <tr>
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center">
                                            ${colorCode ? `<span class="rounded-circle shadow-sm border me-2" style="background:${colorCode}; width:16px; height:16px;"></span>` : ''}
                                            <span class="fw-medium">${colorName}</span>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="badge ${qty > 0 ? 'bg-success' : 'bg-danger'} rounded-pill px-3 py-1 fs-6">${qty}</span>
                                    </td>
                                </tr>
                            `;
                        });
                        sizeWiseStockHtml += `</tbody></table></div></div>`;
                    });
                } else {
                    sizeWiseStockHtml = '<div class="alert alert-light border text-center text-muted">No stock variations available</div>';
                }

                let html = `
                    <div class="row g-4">
                        <div class="col-lg-4 col-md-5">
                            <div class="bg-white rounded-4 p-3 border shadow-sm mb-4">
                                <h6 class="fw-bold text-uppercase text-muted mb-3" style="font-size: 12px; letter-spacing: 1px;">Primary Image</h6>
                                ${mainImageHtml}
                            </div>
                            <div class="bg-white rounded-4 p-3 border shadow-sm mb-4">
                                <h6 class="fw-bold text-uppercase text-muted mb-3" style="font-size: 12px; letter-spacing: 1px;">Size Guide</h6>
                                ${sizeGuideHtml}
                            </div>
                            <div class="bg-white rounded-4 p-3 border shadow-sm mb-4">
                                <h6 class="fw-bold text-uppercase text-muted mb-3" style="font-size: 12px; letter-spacing: 1px;">Gallery</h6>
                                <div class="d-flex flex-wrap">${multipleImagesHtml}</div>
                            </div>
                        </div>

                        <div class="col-lg-8 col-md-7">
                            <div class="bg-white rounded-4 p-4 border shadow-sm mb-4">
                                <h4 class="fw-bold text-dark mb-1">${res.name ?? 'Unnamed Product'}</h4>
                                <div class="d-flex flex-wrap gap-3 mb-4 text-muted" style="font-size: 14px;">
                                    <span><i class="fa-solid fa-barcode me-1"></i> ${res.sku ?? '-'}</span>
                                    <span><i class="fa-solid fa-folder me-1"></i> ${res.category?.name ?? '-'}</span>
                                    <span><i class="fa-solid fa-tag me-1"></i> ${res.brand?.name ?? '-'}</span>
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-sm-4">
                                        <div class="p-3 bg-light rounded-3 border">
                                            <span class="d-block text-muted" style="font-size:12px; text-transform:uppercase;">Cost Price</span>
                                            <strong class="fs-5 text-dark"><span class="taka-symbol">৳</span>${res.purchase_price ?? '0.00'}</strong>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="p-3 bg-light rounded-3 border">
                                            <span class="d-block text-muted" style="font-size:12px; text-transform:uppercase;">Selling Price</span>
                                            <strong class="fs-5 text-success"><span class="taka-symbol">৳</span>${res.selling_price ?? '0.00'}</strong>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="p-3 bg-light rounded-3 border">
                                            <span class="d-block text-muted" style="font-size:12px; text-transform:uppercase;">Discount</span>
                                            <strong class="fs-5 text-danger">${res.discount_type ? res.discount_type : 'None'}</strong>
                                        </div>
                                    </div>
                                </div>

                                <table class="table table-borderless table-sm mb-0">
                                    <tbody>
                                        <tr><th width="140" class="text-muted fw-normal">Available Sizes</th><td>${sizesHtml}</td></tr>
                                        <tr><th class="text-muted fw-normal">Unit Base</th><td class="fw-medium">${res.unit?.name ?? '-'}</td></tr>
                                        <tr><th class="text-muted fw-normal">Alert Quantity</th><td class="fw-medium text-danger">${res.alert_quantity ?? '-'}</td></tr>
                                        <tr>
                                            <th class="text-muted fw-normal">Tags/Badges</th>
                                            <td>
                                                ${res.is_featured ? '<span class="badge bg-warning text-dark me-1"><i class="fa-solid fa-star me-1"></i>Featured</span>' : ''}
                                                ${res.is_new ? '<span class="badge bg-info text-dark me-1"><i class="fa-solid fa-sparkles me-1"></i>New Arrival</span>' : ''}
                                                ${res.is_bestseller ? '<span class="badge bg-danger me-1"><i class="fa-solid fa-fire me-1"></i>Best Seller</span>' : ''}
                                                ${res.is_trending ? '<span class="badge bg-primary me-1"><i class="fa-solid fa-chart-line me-1"></i>Trending</span>' : ''}
                                                ${res.status ? '<span class="badge bg-success"><i class="fa-solid fa-check-circle me-1"></i>Active</span>' : '<span class="badge bg-secondary"><i class="fa-solid fa-ban me-1"></i>Draft</span>'}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="bg-white rounded-4 p-4 border shadow-sm mb-4">
                                <h6 class="fw-bold text-uppercase text-muted mb-3" style="font-size: 12px; letter-spacing: 1px;">Color Specific Images</h6>
                                ${colorImagesHtml}
                            </div>

                            <div class="bg-white rounded-4 p-4 border shadow-sm">
                                <h6 class="fw-bold text-uppercase text-muted mb-4" style="font-size: 12px; letter-spacing: 1px;"><i class="fa-solid fa-boxes-stacked me-2"></i> Inventory Status</h6>
                                ${sizeWiseStockHtml}
                            </div>
                            
                            <div class="bg-white rounded-4 p-4 border shadow-sm mt-4">
                                <h6 class="fw-bold text-uppercase text-muted mb-3" style="font-size: 12px; letter-spacing: 1px;">Short Description</h6>
                                <p class="text-dark bg-light p-3 rounded-3 border" style="font-size: 14px;">${res.short_description || 'No short description provided.'}</p>

                                <h6 class="fw-bold text-uppercase text-muted mt-4 mb-3" style="font-size: 12px; letter-spacing: 1px;">বিশেষ নোট</h6>
                                <p class="text-dark bg-light p-3 rounded-3 border" style="font-size: 14px;">${res.extra_note || 'কোনো বিশেষ নোট দেওয়া হয়নি।'}</p>

                                <h6 class="fw-bold text-uppercase text-muted mt-4 mb-3" style="font-size: 12px; letter-spacing: 1px;">Full Description</h6>
                                <div class="text-dark bg-light p-3 rounded-3 border" style="font-size: 14px; max-height: 250px; overflow-y: auto;">
                                    ${res.description || 'No full description provided.'}
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                $('#productModalBody').html(html);
                $('#productModal').modal('show'); 
            },
            error: function () {
                btn.prop('disabled', false).html(originalHtml);
                alert('Failed to load product details.');
            }
        });
    });


});
</script>
@endpush