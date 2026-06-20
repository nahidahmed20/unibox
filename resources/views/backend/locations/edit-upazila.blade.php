<!-- Edit Upazila Modal -->
<div class="modal fade" id="editUpazilaModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-white" style="background:linear-gradient(135deg,#002142,#0d4a87);">
                <div>
                    <h5 class="modal-title fw-bold mb-1">
                        Edit Upazila
                    </h5>
                    <small>
                        Update upazila information
                    </small>
                </div>

                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editUpazilaForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            District
                        </label>
                        <select name="district_id" id="edit_district_id" class="form-select edit-select2" required>
                            @foreach ($districts as $district)
                                <option value="{{ $district->id }}">
                                    {{ $district->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Upazila Name
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
                        Update Upazila
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
