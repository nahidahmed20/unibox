<!-- Edit Shipping Modal -->
<div class="modal fade" id="editShippingModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-white" style="background:linear-gradient(135deg,#002142,#0d4a87);">
                <div>
                    <h5 class="modal-title fw-bold mb-1">
                        Edit Shipping
                    </h5>
                    <small>Update shipping information</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editShippingForm">
                @csrf
                <input type="hidden" id="edit_shipping_id">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Shipping Zone
                        </label>
                        <select id="edit_zone" class="form-select" required>
                            <option value="inside_dhaka">
                                Inside Dhaka
                            </option>
                            <option value="near_dhaka">
                                Near Dhaka
                            </option>
                            <option value="outside_dhaka">
                                Outside Dhaka
                            </option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Address
                        </label>
                        <textarea id="edit_address" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Shipping Cost
                        </label>

                        <input type="number" id="edit_shipping_cost" class="form-control">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i>
                        Update Shipping
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
