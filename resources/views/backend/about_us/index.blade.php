@extends('backend.layouts.app')
@section('title', 'About Us')

@section('content')
    <div class="app-content-header mb-4 mt-3">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6"><h3 class="mb-0 fw-bold" style="color:#212b36;">Who We Are (About Us)</h3></div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="card modern-card p-4">
                <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                    <h4 class="card-title mb-0"><i class="fa-solid fa-address-card text-muted me-2"></i> About Content Settings</h4>
                    @if($about)
                        <button class="btn btn-primary fw-bold" id="editAboutBtn"><i class="fa-solid fa-pen-to-square me-1"></i> Edit Content</button>
                    @else
                        <button class="btn btn-dark fw-bold" id="editAboutBtn"><i class="fa-solid fa-plus me-1"></i> Add Content</button>
                    @endif
                </div>

                @if($about)
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="text-muted mb-1">{{ $about->subtitle }}</h5>
                            <h2 class="fw-bold text-dark mb-3">{{ $about->title }}</h2>
                            <p class="text-secondary" style="line-height: 1.7; white-space: pre-line;">{{ $about->description }}</p>
                            <hr>
                            <div class="row text-center mt-3">
                                <div class="col-md-4 border-end"><h6>Experience</h6><strong class="fs-4 text-primary">{{ $about->experience_years ?? 0 }} Years</strong></div>
                                <div class="col-md-4 border-end"><h6>Button Text</h6><strong class="text-dark">{{ $about->btn_text ?? '-' }}</strong></div>
                                <div class="col-md-4"><h6>Video Link</h6><a href="{{ $about->video_url }}" target="_blank" class="text-danger fw-bold"><i class="fa-brands fa-youtube"></i> Watch Video</a></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="fw-bold d-block text-muted">Main Image</label>
                                <img src="{{ asset($about->image1) }}" class="w-100 img-thumbnail" style="max-height: 200px; object-fit: cover;">
                            </div>
                            @if($about->image2)
                                <div>
                                    <label class="fw-bold d-block text-muted">Secondary Image</label>
                                    <img src="{{ asset($about->image2) }}" class="w-100 img-thumbnail" style="max-height: 150px; object-fit: cover;">
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning text-center py-4">No content added yet. Please click the button above to add configuration details.</div>
                @endif
        </div>
    </div>

    <div class="modal fade" id="aboutModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="aboutForm" action="{{ route('about-us.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modern-card-header d-flex justify-content-between align-items-center" style="background:#000032; color:#fff; padding:15px 20px;">
                        <h4 style="color:#fff; margin-bottom:0;">Manage About Content</h4>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="fw-semibold">Main Title *</label>
                                <input type="text" name="title" class="form-control" value="{{ $about->title ?? '' }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-semibold">Subtitle / Tagline</label>
                                <input type="text" name="subtitle" class="form-control" value="{{ $about->subtitle ?? '' }}">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="fw-semibold">About Description</label>
                                <textarea name="description" rows="5" class="form-control" required>{{ $about->description ?? '' }}</textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-semibold">Button Text</label>
                                <input type="text" name="btn_text" class="form-control" value="{{ $about->btn_text ?? '' }}" placeholder="e.g., Read More">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-semibold">Button URL</label>
                                <input type="text" name="btn_url" class="form-control" value="{{ $about->btn_url ?? '' }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-semibold">Video URL (YouTube)</label>
                                <input type="url" name="video_url" class="form-control" value="{{ $about->video_url ?? '' }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-semibold">Years of Experience</label>
                                <input type="number" name="experience_years" class="form-control" value="{{ $about->experience_years ?? '' }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-semibold">Main Image (Image 1)</label>
                                <input type="file" name="image1" class="form-control" accept="image/*">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-semibold">Secondary Image (Image 2)</label>
                                <input type="file" name="image2" class="form-control" accept="image/*">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="saveAboutBtn">Save Settings</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $('#editAboutBtn').click(function () {
            $('#aboutModal').modal('show');
        });

        $('#aboutForm').submit(function (e) {
            e.preventDefault();
            let formData = new FormData(this);
            $('#saveAboutBtn').prop('disabled', true).text('Saving...');

            $.ajax({
                url: $(this).attr('action'), type: "POST", data: formData, contentType: false, processData: false,
                success: function (res) {
                    toastr.success("About section successfully updated!");
                    $('#aboutModal').modal('hide');
                    setTimeout(function() { window.location.reload(); }, 500);
                },
                error: function () {
                    $('#saveAboutBtn').prop('disabled', false).text('Save Settings');
                    toastr.error('Something went wrong!');
                }
            });
        });
    });
</script>
@endpush