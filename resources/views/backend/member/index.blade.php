@extends('backend.layouts.app')
@section('title', 'Member List')

@section('content')

    <div class="app-content-header mb-4 mt-3">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold" style="color:#212b36;"> Members </h3>
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end mb-0 bg-transparent p-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}" class="text-muted text-decoration-none">
                                Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item active fw-bold"> Member List </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="card modern-card">
                <div class="modern-card-header">
                    <h4 class="card-title">
                        <i class="fa-solid fa-users text-muted me-2"></i>
                        All Members
                    </h4>

                    <button class="btn btn-dark rounded-pill px-4 fw-bold" id="addMemberBtn">
                        <i class="fa-solid fa-plus me-1"></i>
                        Add Member
                    </button>
                </div>

                <div class="card-body p-0">
                    <div class="p-4">
                        <table id="memberTable" class="table table-modern table-hover w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>Image</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- ADD EDIT MODAL -->
    @include('backend.member.edit')

    <!-- SHOW MEMBER MODAL -->
    @include('backend.member.show')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let table =
                $('#memberTable').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: "{{ route('members.index') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'name'
                        },
                        {
                            data: 'code_number'
                        },
                        {
                            data: 'email'
                        },
                        {
                            data: 'phone'
                        },
                        {
                            data: 'address'
                        },
                        {
                            data: 'image'
                        },
                        {
                            data: 'status'
                        },
                        {
                            data: 'action',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        }
                    ],
                    dom: '<"row align-items-center mb-4"' +
                        '<"col-md-4"l>' +
                        '<"col-md-4 d-flex justify-content-center"B>' +
                        '<"col-md-4 d-flex justify-content-end"f>' +
                        '>rt' +
                        '<"d-flex justify-content-between align-items-center mt-4"ip>',

                    buttons: [{
                            extend: 'copy',
                            text: '<i class="fa-regular fa-copy"></i> Copy'
                        },
                        {
                            extend: 'excel',
                            text: '<i class="fa-regular fa-file-excel"></i> Excel'
                        },
                        {
                            extend: 'csv',
                            text: '<i class="fa-solid fa-file-csv"></i> CSV'
                        },
                        {
                            extend: 'pdf',
                            text: '<i class="fa-regular fa-file-pdf"></i> PDF'
                        },
                        {
                            extend: 'print',
                            text: '<i class="fa-solid fa-print"></i> Print'
                        }
                    ],
                    order: [
                        [1, 'asc']
                    ],

                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search brands...",
                        lengthMenu: "Show _MENU_ entries",

                        paginate: {
                            previous: '<i class="fa-solid fa-angle-left"></i>',
                            next: '<i class="fa-solid fa-angle-right"></i>'
                        }
                    }

                });

            $('#addMemberBtn').click(function() {
                $('#memberForm')[0].reset();
                $('#member_id').val('');
                $('#modalTitle').text('Add Member');
                $('#memberModal').modal('show');
            });

            $(document).on('click', '.btn-edit', function() {
                let id = $(this).data('id');
                $.get("{{ url('/admin/members') }}/" + id + "/edit", function(data) {
                    $('#member_id').val(data.id);
                    $('#name').val(data.name);
                    $('#code_number').val(data.code_number);
                    $('#email').val(data.email);
                    $('#phone').val(data.phone);
                    $('#address').val(data.address);
                    $('#gender').val(data.gender);
                    $('#date_of_birth').val(data.date_of_birth);
                    $('#marital_status').val(data.marital_status);
                    $('#occupation').val(data.occupation);
                    $('#nationality').val(data.nationality);
                    $('#religion').val(data.religion);
                    $('#education').val(data.education);
                    $('#status').val(data.status);
                    $('#modalTitle').text('Edit Member');
                    $('#memberModal').modal('show');
                });

            });

            $('#memberForm').submit(function(e) {
                e.preventDefault();
                let id = $('#member_id').val();
                let url = id ? `/admin/members/${id}` : "{{ route('members.store') }}";
                let formData = new FormData(this);

                if (id) {
                    formData.append('_method', 'PUT');
                }

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(res) {
                        toastr.success(res.message);
                        $('#memberModal').modal('hide');
                        table.ajax.reload(null, false);
                    },
                    error:function(xhr){
                        showErrors(xhr);
                    }
                });
            });

            function showErrors(xhr) {
                if (xhr.responseJSON &&
                    xhr.responseJSON.errors) {
                    let errors = '';
                    $.each(xhr.responseJSON.errors,
                        function(key, value) {
                            errors += value + '<br>';
                        });
                    toastr.error(errors);
                } else {
                    toastr.error('Something went wrong!');
                }
            }

            $(document).on('click','.show-btn',function(){
                let id=$(this).data('id');
                $.get("{{ url('/admin/members') }}/"+id,function(data){
                    $('#show_name').text(data.name);
                    $('#show_code_number').text(data.code_number);
                    $('#show_phone').text(data.phone);
                    $('#show_email').text(data.email);
                    $('#show_gender').text(data.gender);
                    $('#show_date_of_birth').text(data.date_of_birth);
                    $('#show_marital_status').text(data.marital_status);
                    $('#show_occupation').text(data.occupation ?? '-');
                    $('#show_address').text(data.address);

                    if(data.status == 1){
                        $('#show_status')
                        .removeClass()
                        .addClass('badge bg-success rounded-pill px-3')
                        .text('Active');
                    }else{
                        $('#show_status')
                        .removeClass()
                        .addClass('badge bg-danger rounded-pill px-3')
                        .text('Inactive');

                    }
                    if(data.image){
                        $('#show_image')
                        .attr('src',"{{asset('')}}"+data.image);
                    }else{
                        $('#show_image')
                        .attr('src',"{{asset('default.png')}}");
                    }
                    $('#showMemberModal').modal('show');
                });
            });
        });
    </script>
@endpush
