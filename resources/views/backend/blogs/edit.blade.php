<div class="modal fade" id="editBlogModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form id="editBlogForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" id="editBlogId" name="blog_id">
                <div class="modern-card-header d-flex justify-content-between align-items-center"
                    style="background:#000032;color:#fff;padding:15px 20px;">
                    <h4 class="card-title mb-0 text-white">
                        <i class="fa-solid fa-pen-to-square me-2"></i>
                        Edit Blog
                    </h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Title
                            </label>
                            <input type="text" name="title" id="editBlogTitle" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Slug
                            </label>
                            <input type="text" name="slug" id="editBlogSlug" class="form-control" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Category
                            </label>
                            <select name="blog_category_id" id="editBlogCategory" class="form-select" required>
                                <option value="">
                                    Select Category
                                </option>
                                @foreach (\App\Models\BlogCategory::all() as $category)
                                    <option value="{{ $category->id }}">
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Date
                            </label>
                            <input type="date" name="date" id="editBlogDate" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Image
                            </label>
                            <input type="file" name="image" id="editImage" class="form-control">
                            <img id="previewEditImage" class="mt-2 rounded border" style="width:90px;display:none;">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Status
                            </label>
                            <select name="status" id="editStatus" class="form-select">
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
                            <textarea name="description" id="editDescription" class="form-control" rows="4"></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
