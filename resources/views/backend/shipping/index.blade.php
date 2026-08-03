@extends('backend.layouts.app')

@section('title', 'Shipping Management')

@push('styles')
    <style>
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .06);
        }

        .card-header {
            background: #fff;
            border-bottom: 1px solid #eef1f4;
            padding: 18px 24px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: #212b36;
        }

        .page-subtitle {
            color: #6b7280;
            font-size: 14px;
        }

        .form-label {
            font-weight: 600;
            color: #374151;
        }

        .form-control,
        .form-select {
            border: 1px solid #dfe3e8;
            border-radius: 12px;
            min-height: 45px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #002142;
            box-shadow: 0 0 0 .15rem rgba(0, 33, 66, .15);
        }

        .btn-save {
            background: #002142;
            border: none;
            color: #fff;
            border-radius: 12px;
            height: 45px;
            font-weight: 600;
        }

        .btn-save:hover {
            background: #03274b;
            color: #fff;
        }

        .badge-zone {
            background: #eef7f4;
            color: #008060;
            padding: 8px 12px;
            border-radius: 8px;
            font-weight: 600;
        }

        .shipping-cost {
            font-weight: 700;
            color: #111827;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-4">
        <div class="mb-4">
            <h3 class="page-title mb-1">
                Shipping Management
            </h3>
            <div class="page-subtitle">
                Manage shipping zones and delivery charges
            </div>
        </div>
        <div class="row">
            <!-- ADD SHIPPING -->
            <div class="col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-plus-circle text-success me-2"></i>
                            Add Shipping
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="shippingForm">
                            @csrf
                            <input type="hidden" id="shipping_id" name="id">
                            <div class="mb-3">
                                <label class="form-label">
                                    Shipping Zone
                                </label>
                                <select id="zone" name="zone" class="form-select" required>
                                    <option value="">
                                        Select Zone
                                    </option>
                                    <option value="inside_dhaka">
                                        Inside Dhaka
                                    </option>
                                    <option value="near_dhaka">
                                        Near Dhaka
                                    </option>
                                    <option value="outside_dhaka">
                                        Outside Dhaka
                                    </option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">
                                    Address
                                </label>
                                <textarea id="address" name="address" rows="3" class="form-control"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">
                                    Shipping Cost
                                </label>
                                <input type="number" id="shipping_cost" name="shipping_cost" class="form-control"
                                    value="0">
                            </div>
                            <button type="submit" class="btn btn-save w-100">
                                <i class="fas fa-save me-2"></i>
                                Save Shipping
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- LIST -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            Shipping List
                        </h5>
                        <span class="badge bg-success">
                            Shipping Zones
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="shippingTable" class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Zone</th>
                                        <th>Address</th>
                                        <th>Cost</th>
                                        <th width="120">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('backend.shipping.edit')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let table = $('#shippingTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ route('shippings.index') }}",
                lengthMenu: [[10, 25, 50, 100, 500, -1], [10, 25, 50, 100, 500, "All"]],
                columns: [
                    {
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'zone',
                        render: function(data) {

                            return `
                        <span class="badge-zone">
                            ${data.replaceAll('_',' ')}
                        </span>
                    `;
                        }
                    },

                    {
                        data: 'address'
                    },

                    {
                        data: 'shipping_cost',
                        render: function(data) {

                            return `
                        <span class="shipping-cost">
                            ৳ ${data}
                        </span>
                    `;
                        }
                    },

                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [1, 'asc']
                ],
                dom:
                    '<"row align-items-center mb-4"' +
                    '<"col-md-4"l>' +
                    '<"col-md-4 d-flex justify-content-center"B>' +
                    '<"col-md-4 d-flex justify-content-end"f>' +
                    '>rt' +
                    '<"d-flex justify-content-between align-items-center mt-4"ip>',
                buttons: [{
                        extend: 'copy',
                        className: 'btn btn-light btn-sm',
                        text: '<i class="fa-regular fa-copy me-1"></i> Copy'
                    },
                    {
                        extend: 'excel',
                        className: 'btn btn-success btn-sm',
                        text: '<i class="fa-regular fa-file-excel me-1"></i> Excel'
                    },
                    {
                        extend: 'csv',
                        className: 'btn btn-info btn-sm',
                        text: '<i class="fa-solid fa-file-csv me-1"></i> CSV'
                    },
                    {
                        extend: 'pdf',
                        className: 'btn btn-danger btn-sm',
                        text: '<i class="fa-regular fa-file-pdf me-1"></i> PDF'
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-dark btn-sm',
                        text: '<i class="fa-solid fa-print me-1"></i> Print'
                    }
                ],

                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search blogs...",
                    paginate: {
                        previous: '<i class="fa-solid fa-angle-left"></i>',
                        next: '<i class="fa-solid fa-angle-right"></i>'
                    }
                }
            });

            $('#shippingForm').submit(function(e) {
                e.preventDefault();
                let id = $('#shipping_id').val();
                let url = id ?
                    "{{ route('shippings.update', ':id') }}".replace(':id', id) :
                    "{{ route('shippings.store') }}";
                let formData = $(this).serialize();
                if (id) {
                    formData += '&_method=PUT';
                }
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    success: function() {
                        $('#shippingForm')[0].reset();
                        $('#shipping_id').val('');
                        table.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Shipping saved successfully',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                });
            });

            const editModal = new bootstrap.Modal(
                document.getElementById('editShippingModal')
            );

            $(document).on('click', '.editBtn', function () {
                let id = $(this).data('id');
                $.get(
                    "{{ route('shippings.edit', ':id') }}"
                    .replace(':id', id),
                    function(data) {
                        $('#edit_shipping_id').val(data.id);
                        $('#edit_zone').val(data.zone);
                        $('#edit_address').val(data.address);
                        $('#edit_shipping_cost').val(data.shipping_cost);
                        editModal.show();
                    }
                );
            });

            $('#editShippingForm').submit(function(e){
                e.preventDefault();
                let id = $('#edit_shipping_id').val();
                $.ajax({
                    url: "{{ route('shippings.update', ':id') }}".replace(':id', id),
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        _method: "PUT",

                        zone: $('#edit_zone').val(),
                        address: $('#edit_address').val(),
                        shipping_cost: $('#edit_shipping_cost').val()
                    },

                    success: function(){
                        editModal.hide();
                        $('#shippingTable').DataTable().ajax.reload();
                        Swal.fire({
                            icon:'success',
                            title:'Success',
                            text:'Shipping updated successfully',
                            timer:1500,
                            showConfirmButton:false
                        });
                    }
                });
            });
        });
    </script>
@endpush
