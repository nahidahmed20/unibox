<div class="modal fade" id="createCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form id="createCategoryForm">
                @csrf
                {{-- HEADER --}}
                <div class="modern-card-header d-flex justify-content-between align-items-center"
                    style="background:#000032;color:#fff;padding:15px 20px;">
                    <h4 class="card-title mb-0 text-white">
                        <i class="fa-solid fa-layer-group me-2"></i>
                        Add Category
                    </h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                {{-- BODY --}}
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label">
                            Category Name
                        </label>

                        <input type="text" name="name" id="categoryName" class="form-control"
                            placeholder="Enter category name" required>

                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            Slug
                        </label>
                        <input type="text" name="slug" id="categorySlug" class="form-control"
                            placeholder="Auto generate" readonly>
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
