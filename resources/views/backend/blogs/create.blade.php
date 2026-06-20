<div class="modal fade" id="createBlogModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form id="createBlogForm" enctype="multipart/form-data">
                @csrf
                {{-- HEADER --}}
                <div class="modern-card-header d-flex justify-content-between align-items-center"
                    style="background:#000032;color:#fff;padding:15px 20px;">
                    <h4 class="card-title mb-0 text-white">
                        <i class="fa-solid fa-blog me-2"></i>
                        Add Blog
                    </h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                {{-- BODY --}}
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" id="blogTitle" class="form-control"
                                placeholder="Enter title" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Slug</label>
                            <input type="text" name="slug" id="blogSlug" class="form-control" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Category</label>
                            <select name="blog_category_id" class="form-select" required>
                                <option value="">Select Category</option>
                                @foreach (\App\Models\BlogCategory::all() as $category)
                                    <option value="{{ $category->id }}">
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date</label>
                            <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Image</label>
                            <input type="file" name="image" id="createImage" class="form-control">
                            <img id="previewCreateImage" class="mt-2 rounded border" style="width:90px;display:none;">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="active">
                                    Active
                                </option>
                                <option value="inactive">
                                    Inactive
                                </option>
                            </select>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">
                                Description
                            </label>
                            <textarea name="description" class="form-control" rows="4" placeholder="Write description"></textarea>
                        </div>
                    </div>
                </div>
                {{-- FOOTER --}}
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
