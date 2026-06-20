<link rel="preload" href="{{ asset('backend/css/adminlte.css') }}" as="style" />
    <!--end::Accessibility Features-->
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
        integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" media="print"
        onload="this.media='all'" />
    <link rel="icon" type="image/png" href="{{ asset('backend/assets/img/favicon.png') }}" />
    <!--end::Fonts-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
        crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
        crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ asset('backend/css/adminlte.css') }}" />
    <!--end::Required Plugin(AdminLTE)-->
    <!-- apexcharts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
        integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0=" crossorigin="anonymous" />
    <!-- jsvectormap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css"
        integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4=" crossorigin="anonymous" />
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

    <style>
        .form-control{
            border-radius: 0px !important;
        }
        .form-select{
            border-radius: 0px !important;
        }
        aside.app-sidebar.bg-body-secondary.shadow {
            background: #000032 !important;
        }
        .app-header .nav-link {
            color: white !important;
        }
        .app-header {
            background: #001a4a !important;
        }
        .select2-container--default .select2-selection--single{
            border-radius: 0px !important;
        }
    </style>
    {{-- For index badle design  --}}
    <style>
        /* Modern Dashboard Background */
        .app-content {
            background-color: #f4f6f8;
            padding-bottom: 50px;
        }

        /* Modern Card UI */
        .modern-card {
            border: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            border-radius: 16px;
            background: #ffffff;
            overflow: hidden;
        }

        .modern-card-header {
            background: #ffffff;
            border-bottom: 1px solid #f0f2f5;
            padding: 20px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modern-card-header h4 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            color: #212b36;
        }

        /* Table Styling */
        .table-modern {
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table-modern thead th {
            background-color: #f9fafb;
            color: #637381;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
            padding: 16px 24px;
            border-bottom: none;
        }

        .table-modern tbody td {
            padding: 16px 24px;
            vertical-align: middle;
            color: #212b36;
            font-size: 14px;
            border-bottom: 1px solid #f0f2f5;
        }

        .table-modern tbody tr:hover {
            background-color: #f8f9fa;
        }

        .table-modern tfoot th {
            background-color: #f9fafb;
            padding: 16px 24px;
            font-size: 15px;
            color: #212b36;
        }

        /* DataTables Overrides */
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #ced4da;
            border-radius: 8px;
            padding: 6px 12px;
            outline: none;
            transition: 0.3s;
        }
        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
        }
        .dataTables_wrapper .dataTables_length select {
            border-radius: 8px;
            padding: 4px 8px;
            border: 1px solid #ced4da;
        }

        .btn-edit {
            border: 1px solid #0d6efd !important;
        }
        
        .btn-delete {
            border: 1px solid #dc3545 !important;
        }
        .btn-show {
            border: 1px solid #198754 !important;
        }
        
        .btn-soft-primary {
            border-color: #0d6efd !important;
        }
        .btn-soft-success {
            border: 1px solid #198754 !important;
        }
        
        /* Modern Export Buttons */
        div.dt-buttons {
            display: flex;
            gap: 8px;
        }
        .dt-button {
            background: #ffffff !important;
            border: 1px solid #ced4da !important;
            color: #495057 !important;
            border-radius: 6px !important;
            padding: 6px 14px !important;
            font-size: 13px !important;
            font-weight: 500 !important;
            transition: all 0.2s !important;
        }
        .dt-button:hover {
            background: #f8f9fa !important;
            border-color: #adb5bd !important;
            color: #212b36 !important;
        }
        /* ================= Modern Pagination Fix (Default DataTables Markup) ================= */
        .dataTables_wrapper .dataTables_paginate {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .dataTables_wrapper .dataTables_paginate span {
            display: flex;
            gap: 6px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 8px !important;
            border: none !important;
            color: #637381 !important;
            background-color: #f4f6f8 !important;
            font-weight: 600;
            font-size: 14px;
            min-width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 12px !important;
            transition: all 0.2s ease;
            margin: 0 !important;
            cursor: pointer;
            text-decoration: none;
        }

        /* Active Page */
        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background-color: #212b36 !important;
            color: #ffffff !important;
            box-shadow: 0 4px 8px rgba(33, 43, 54, 0.2) !important;
        }

        /* Hover Effect (For non-active pages) */
        .dataTables_wrapper .dataTables_paginate .paginate_button:not(.current):not(.disabled):hover {
            background-color: #e2e8f0 !important;
            color: #212b36 !important;
        }

        /* Disabled State (Previous/Next when at limits) */
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover,
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:active {
            background-color: transparent !important;
            color: #adb5bd !important;
            cursor: not-allowed !important;
            box-shadow: none !important;
        }
        /* Taka Symbol Fix */
        .taka-symbol {
            font-size: 1.15em; 
            font-weight: 500;
            font-style: normal !important; 
            font-family: Arial, Helvetica, sans-serif !important; 
            display: inline-block;
            vertical-align: baseline;
            margin-right: 2px;
        }

        /* Modal Styling */
        .modal-content {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .modal-header-modern {
            background: #ffffff;
            border-bottom: 1px solid #f0f2f5;
            padding: 20px 24px;
        }
        .modal-title-modern {
            font-weight: 700;
            color: #212b36;
            font-size: 18px;
        }
        .modal-body {
            padding: 24px;
            background: #fdfdfd;
        }
        .modal-dialog-scrollable .modal-body {
            overflow-x: hidden;
        }

        /* Custom Responsive Modal Widths */
        @media (min-width: 768px) { .modal-dialog-scrollable { max-width: 700px; } }
        @media (min-width: 992px) { .modal-dialog-scrollable { max-width: 900px; } }
        @media (min-width: 1200px) { .modal-dialog-scrollable { max-width: 1100px; } }
        @media (max-width: 768px) {
            .modern-card-header{
                flex-direction: column;
                gap: 10px;
                align-items: flex-start;
            }

            .table-responsive{
                overflow-x: auto;
            }

            #productsTable{
                min-width: 1000px;
            }
            #saleTable{
                min-width: 1000px;
            }
        }

        /* Action Buttons in Table */
        .btn-xxs {
            padding: 6px 12px !important;
            font-size: 12px !important;
            line-height: 1.2 !important;
            border-radius: 6px !important;
            font-weight: 500;
        }
        
    </style>
    @stack('styles')