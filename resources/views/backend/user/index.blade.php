@extends('backend.layouts.app')

@section('title', 'Users')

@section('content')

@push('styles')
    <style>
        .colorful-modal {
            border-radius: 16px;
            border: 1px solid #eef0f2;
            box-shadow: 0 12px 35px rgba(0,0,0,0.08);
            background: #ffffff;
        }

        /* Avatar glow */
        .avatar-wrap {
            display: inline-block;
            padding: 5px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366f1, #06b6d4, #f97316);
        }

        .user-avatar {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            object-fit: cover;
            background: #fff;
            border: 3px solid #fff;
        }

        /* INFO CARDS */
        .info-card {
            border-radius: 12px;
            padding: 12px 14px;
            color: #111827;
            border: 1px solid #eef0f2;
            transition: 0.2s;
        }

        .info-card:hover {
            transform: translateY(-2px);
        }

        /* COLOR VARIANTS */
        .info-card.blue {
            background: #eff6ff;
        }

        .info-card.green {
            background: #ecfdf5;
        }

        .info-card.purple {
            background: #f5f3ff;
        }

        .info-card.orange {
            background: #fff7ed;
        }

        .info-card.pink {
            background: #fdf2f8;
        }

        .info-card.cyan {
            background: #ecfeff;
        }

        .label {
            font-size: 12px;
            color: #6b7280;
        }

        .value {
            font-size: 14px;
            font-weight: 600;
            color: #111827;
        }

        /* ROLE BADGES */
        .badge-role {
            background: linear-gradient(135deg, #60a5fa, #34d399);
            color: #fff;
            font-size: 11px;
            padding: 5px 10px;
            border-radius: 999px;
            display: inline-block;
            margin: 2px 3px;
        }
    </style>
@endpush

{{-- HEADER --}}
<div class="app-content-header mb-4 mt-3">
    <div class="container-fluid">
        <div class="row align-items-center">

            <div class="col-sm-6">
                <h3 class="mb-0 fw-bold" style="color:#212b36;">
                    Users
                </h3>
            </div>

            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end mb-0 bg-transparent p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="text-muted text-decoration-none">
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item active fw-bold text-dark">
                        User List
                    </li>
                </ol>
            </div>

        </div>
    </div>
</div>

{{-- CONTENT --}}
<div class="app-content">
    <div class="container-fluid">

        <div class="card modern-card shadow-sm">

            {{-- HEADER --}}
            <div class="modern-card-header d-flex align-items-center justify-content-between">

                <h4 class="card-title mb-0">
                    <i class="fa-solid fa-users text-muted me-2"></i>
                    All Users
                </h4>

                <a href="{{ route('users.create') }}"
                   class="btn btn-dark rounded-pill px-4 fw-bold shadow-sm">
                    <i class="fa-solid fa-plus me-1"></i>
                    Add User
                </a>

            </div>

            {{-- TABLE --}}
            <div class="card-body p-0">

                <div class="p-4">

                    <table id="userTable" class="table table-modern table-hover w-100">

                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Roles</th>
                                <th width="15%" class="text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody></tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>
</div>

@include('backend.user.view-modal')

@endsection

@push('scripts')
<script>
    $(document).ready(function () {

        let table = $('#userTable').DataTable({

            processing: true,
            serverSide: true,

            ajax: "{{ route('users.index') }}",

            columns: [
                { data: 'DT_RowIndex', orderable:false, searchable:false },
                { data: 'name' },
                { data: 'email' },
                { data: 'roles', orderable:false, searchable:false },
                { data: 'action', orderable:false, searchable:false, className:'text-center' }
            ],

            dom: '<"row align-items-center mb-4"<"col-md-4"l><"col-md-4 d-flex justify-content-center"B><"col-md-4 d-flex justify-content-end"f>>rt<"d-flex justify-content-between align-items-center mt-4"ip>',

            buttons: [
                {
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
            order: [[1, 'asc']],
            responsive: true,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search users...",
                lengthMenu: "Show _MENU_ entries",
                paginate: {
                    previous: '<i class="fa-solid fa-angle-left"></i>',
                    next: '<i class="fa-solid fa-angle-right"></i>'
                }
            }
        });

        $(document).on('click', '.showBtn', function (e) {
            e.preventDefault();

            let id = $(this).data('id');

            $.ajax({
                url: '/admin/users/' + id, // or your route
                type: 'GET',
                success: function (data) {

                    $('#viewUserImage').attr('src', data.image ?? 'https://ui-avatars.com/api/?name=' + data.name);
                    $('#viewUserName').text(data.name);
                    $('#viewUserEmail').text(data.email);

                    let rolesHtml = '';
                    if (data.roles.length > 0) {
                        data.roles.forEach(role => {
                            rolesHtml += `<span class="badge bg-info text-dark me-1">${role}</span>`;
                        });
                    } else {
                        rolesHtml = '<span class="text-muted">No Role</span>';
                    }
                    $('#viewUserRoles').html(rolesHtml);
                    $('#viewUserPhone').text(data.phone ?? '-');
                    $('#viewUserCity').text(data.city ?? '-');
                    $('#viewUserState').text(data.state ?? '-');
                    $('#viewUserCountry').text(data.country ?? '-');
                    $('#viewUserBirth').text(data.birth_date ?? '-');
                    // Bootstrap 5 FIX (important)
                    let modal = new bootstrap.Modal(document.getElementById('viewUserModal'));
                    modal.show();
                },
                error:function(xhr){
                    showErrors(xhr);
                }
            });

        });

        // Show User Modal Logic
        $(document).on('click', '.btn-show', function(e) {
            e.preventDefault();
            
            let userId = $(this).data('id');
            let url = "{{ route('users.show', ':id') }}".replace(':id', userId);

            $('#show_name').text('Loading...');
            
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    // Header Info
                    $('#show_name').text(response.name);
                    $('#show_image').attr('src', response.image);
                    $('#show_status').text(response.status).removeClass('bg-success bg-secondary').addClass(response.status === 'Active' ? 'bg-success' : 'bg-secondary');
                    
                    let rolesText = response.roles && response.roles.length > 0 ? response.roles.join(', ') : 'No Role Assigned';
                    $('#show_roles').text(rolesText);

                    // Work Info
                    $('#show_employee_code').text(response.employee_code);
                    $('#show_designation').text(response.designation);
                    $('#show_joining_date').text(response.joining_date);
                    $('#show_salary').text(response.salary);

                    // Personal Info
                    $('#show_username').text(response.username);
                    $('#show_gender').text(response.gender);
                    $('#show_birth_date').text(response.birth_date);
                    $('#show_nid').text(response.nid_number);
                    $('#show_passport').text(response.passport_number);

                    // Contact Info
                    $('#show_email').text(response.email);
                    $('#show_phone').text(response.phone);
                    $('#show_alt_phone').text(response.alternate_phone);
                    $('#show_emg_contact').text(response.emergency_contact);

                    // Address Info
                    $('#show_address').text(response.address);
                    
                    let locationText = [response.upazila, response.district, response.division]
                                        .filter(item => item !== '' && item !== '-') 
                                        .join(', ');
                    $('#show_location').text(locationText || '-');
                    
                    $('#show_country').text((response.postal_code !== '-' ? response.postal_code + ', ' : '') + response.country);
                    
                    // Footer Info
                    $('#show_joined').text(response.created_at);

                    $('#showUserModal').modal('show');
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
    });
</script>
@endpush