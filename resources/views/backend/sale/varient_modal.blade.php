<div class="modal fade" id="variantModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-header border-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold">Select Options</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <input type="hidden" id="modal_product_id">
                <input type="hidden" id="modal_product_name">
                <input type="hidden" id="modal_product_price">
                <input type="hidden" id="modal_product_stock">
                
                <div class="modal-body px-4 pb-4">
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">COLOR</label>
                        <select id="modal_color" class="form-select custom-select-style">
                            <option value="">Select Color</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-secondary small fw-semibold">SIZE</label>
                        <select id="modal_size" class="form-select custom-select-style">
                            <option value="">Select Size</option>
                        </select>
                    </div>

                    <div class="d-flex align-items-center p-3 rounded-3 bg-light">
                        <i class="bi bi-box-seam me-2"></i>
                        <span class="small fw-bold">Available Stock:</span>
                        <span id="variantStock" class="ms-auto fw-bold text-dark">0</span>
                    </div>
                </div>

                <div class="modal-footer border-0 px-4 pb-4 pt-0">
                    <button type="button" id="addVariantCart" class="btn btn-dark w-100 py-3 fw-bold rounded-3 shadow-sm" style="transition: transform 0.2s;">
                        Add To Cart
                    </button>
                </div>
            </div>
        </div>
    </div>