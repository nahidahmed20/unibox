<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form id="editCategoryForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="editCategoryId">
                {{-- HEADER --}}
                <div class="modern-card-header d-flex justify-content-between align-items-center"
                    style="background:#000032;color:#fff;padding:15px 20px;">
                    <h4 class="card-title mb-0 text-white">
                        <i class="fa-solid fa-pen-to-square me-2"></i>
                        Edit Category
                    </h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                {{-- BODY --}}
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label">Category Name</label>
                        <input type="text" name="name" id="editCategoryName" class="form-control"
                            placeholder="Enter category name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"> Slug </label>
                        <input type="text" name="slug" id="editCategorySlug" class="form-control"
                            placeholder="Auto generate" readonly>
                    </div>
                </div>
                {{-- FOOTER --}}
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
