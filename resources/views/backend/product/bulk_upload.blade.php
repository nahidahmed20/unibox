@extends('backend.layouts.app')
@section('title', 'Bulk Product Upload')

@section('content')
@push('styles')
<style>
    .app-content { background-color: #f4f6f8; padding-bottom: 50px; }
    
    .modern-card {
        border: none;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        border-radius: 16px;
        background: #ffffff;
        overflow: hidden;
    }

    /* Drag & Drop Upload Zone */
    .upload-zone {
        border: 2px dashed #ced4da;
        border-radius: 12px;
        padding: 50px 20px;
        text-align: center;
        background: #fdfdfe;
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
    }
    
    .upload-zone:hover, .upload-zone.dragover {
        border-color: #0d6efd;
        background: rgba(13, 110, 253, 0.04);
    }

    .upload-zone input[type="file"] {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 10;
    }

    .upload-icon { font-size: 55px; color: #adb5bd; margin-bottom: 15px; transition: 0.3s; }
    .upload-zone:hover .upload-icon, .upload-zone.dragover .upload-icon { color: #0d6efd; transform: translateY(-5px); }
    
    .instruction-list li { margin-bottom: 12px; color: #495057; font-size: 14px; line-height: 1.6; }
    .highlight-text { background: #e9ecef; padding: 2px 6px; border-radius: 4px; font-family: monospace; color: #d63384;}
</style>
@endpush

<div class="app-content-header mb-4 mt-3">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0 fw-bold" style="color: #212b36;">Bulk Upload</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end mb-0 bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none text-muted">Products</a></li>
                    <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">Bulk Upload</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                
                @if(session('error'))
                    <div class="alert alert-danger rounded-3 shadow-sm mb-4">
                        <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
                    </div>
                @endif
                @if(session('success'))
                    <div class="alert alert-success rounded-3 shadow-sm mb-4">
                        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                    </div>
                @endif

                <div class="card modern-card mb-4">
                    <div class="card-body p-4 p-md-5">
                        
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 pb-4 border-bottom">
                            <div>
                                <h4 class="fw-bold text-dark mb-1"><i class="fa-solid fa-file-excel text-success me-2"></i> Import Products</h4>
                                <p class="text-muted mb-0 fs-6">Easily add multiple products at once using our excel template.</p>
                            </div>
                            <div class="mt-3 mt-md-0">
                                <a href="{{ route('products.demo_excel') }}" class="btn btn-dark rounded-pill fw-bold px-4 shadow-sm">
                                    <i class="fa-solid fa-download me-2"></i> Download Template
                                </a>
                            </div>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-5">
                                <div class="bg-light rounded-4 p-4 h-100 border">
                                    <h6 class="fw-bold mb-3"><i class="fa-solid fa-circle-info text-primary me-2"></i> Important Guidelines:</h6>
                                    <ul class="instruction-list ps-3 mb-0">
                                        <li><strong>Category & Brand:</strong> System will auto-create them if they don't exist.</li>
                                        <li><strong>Sizes & Colors:</strong> Use commas to separate multiple options. Example: <span class="highlight-text">S, M, L, XL</span> or <span class="highlight-text">Red, Blue</span>.</li>
                                        <li><strong>Discount Type:</strong> Use either <span class="highlight-text">percent</span> or <span class="highlight-text">fixed</span>. Leave blank for no discount.</li>
                                        <li><strong>SKU:</strong> Must be unique. Leave empty to auto-generate.</li>
                                        <li><strong>Stock:</strong> For single products, this is total stock. For multiple sizes/colors, this stock value will be applied to <b>each</b> generated variant.</li>
                                        <li><strong>Status Fields:</strong> Use <span class="highlight-text">yes</span> or <span class="highlight-text">no</span> for Featured, New, Purchased, Bestseller, and Trending columns.</li>
                                        <li><strong>Images:</strong> Upload the basic data via Excel first. You can add specific color-wise gallery images later from the <em>Edit Product</em> page for better accuracy.</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-md-7">
                                <form action="{{ route('products.bulk_upload') }}" method="POST" enctype="multipart/form-data" class="h-100 d-flex flex-column">
                                    @csrf
                                    
                                    <div class="upload-zone flex-grow-1 d-flex flex-column justify-content-center align-items-center mb-4" id="uploadZone">
                                        <input type="file" name="file" id="fileInput" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required>
                                        <i class="fa-solid fa-cloud-arrow-up upload-icon"></i>
                                        <h5 class="fw-bold text-dark mb-2">Drag & Drop your file here</h5>
                                        <p class="text-muted mb-3" style="font-size: 14px;">or click to browse (.xlsx, .csv)</p>
                                        <span class="badge bg-secondary rounded-pill px-3 py-2" id="fileName">No file selected</span>
                                    </div>

                                    @error('file')
                                        <small class="text-danger d-block mb-3 fw-bold">{{ $message }}</small>
                                    @enderror

                                    <div class="d-flex gap-2 justify-content-end mt-auto">
                                        <a href="{{ route('products.index') }}" class="btn btn-light px-4 rounded-pill border fw-bold">Cancel</a>
                                        <button type="submit" class="btn btn-primary px-5 rounded-pill fw-bold shadow-sm" id="uploadBtn">
                                            <i class="fa-solid fa-cloud-arrow-up me-2"></i> Start Upload
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        let $fileInput = $('#fileInput');
        let $uploadZone = $('#uploadZone');
        let $fileNameDisplay = $('#fileName');
        let $uploadBtn = $('#uploadBtn');

        // Handle File Selection
        $fileInput.on('change', function(e) {
            if (e.target.files.length > 0) {
                let fileName = e.target.files[0].name;
                $fileNameDisplay.html('<i class="fa-solid fa-file-excel me-1"></i> ' + fileName);
                $fileNameDisplay.removeClass('bg-secondary').addClass('bg-success');
                
                $uploadZone.css({
                    'border-color': '#198754',
                    'background': 'rgba(25, 135, 84, 0.04)'
                });
            }
        });

        // Prevent default drag behaviors
        $(document).on('dragenter dragover dragleave drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
        });

        // Drag and Drop Effects for the Upload Zone
        $uploadZone.on('dragenter dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).css({
                'border-color': '#0d6efd',
                'background': 'rgba(13, 110, 253, 0.04)'
            });
        });

        $uploadZone.on('dragleave drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            // Reset color if no file is dropped yet, otherwise keep success/default color
            if ($fileInput[0].files.length === 0) {
                $(this).css({
                    'border-color': '#ced4da',
                    'background': '#fdfdfe'
                });
            }
        });

        // Button Loading State
        $uploadBtn.on('click', function() {
            if ($fileInput[0].files.length > 0) {
                $(this).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Processing...');
                $(this).addClass('disabled');
            }
        });
    });
</script>
@endpush