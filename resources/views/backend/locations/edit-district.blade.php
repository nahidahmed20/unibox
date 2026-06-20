<!-- Edit District Modal -->
    <div class="modal fade" id="editDistrictModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header text-white" style="background:linear-gradient(135deg,#008060,#00a67e);">
                    <div>
                        <h5 class="modal-title fw-bold mb-1">
                            Edit District
                        </h5>
                        <small>Update district information</small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="editDistrictForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Division
                            </label>
                            <select name="division_id" id="edit_division_id" class="form-select edit-select2" required>
                                @foreach ($divisions as $division)
                                    <option value="{{ $division->id }}">
                                        {{ $division->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                District Name
                            </label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-1"></i>
                            Update District
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>