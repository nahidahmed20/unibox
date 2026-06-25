<div class="modal fade" id="variantModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow" style="border-radius: 16px; overflow: hidden;">

            <div class="modal-header border-0 bg-light pt-4 px-4 pb-3">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center">
                    <i class="fa fa-sliders text-warning me-2"></i> Select Variant
                </h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <input type="hidden" id="modal_product_id">
            <input type="hidden" id="modal_product_name">
            <input type="hidden" id="modal_product_price">
            <input type="hidden" id="modal_product_stock">

            <div class="modal-body px-4 pt-4 pb-3">

                <div class="mb-4">
                    <label class="form-label text-muted small fw-bold text-uppercase tracking-wide mb-2">
                        <i class="fa fa-paint-brush me-1"></i> Color
                    </label>
                    <select id="modal_color" class="form-select custom-select-style shadow-sm">
                        <option value="">-- Select Color --</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label text-muted small fw-bold text-uppercase tracking-wide mb-2">
                        <i class="fa fa-expand me-1"></i> Size
                    </label>
                    <select id="modal_size" class="form-select custom-select-style shadow-sm">
                        <option value="">-- Select Size --</option>
                    </select>
                </div>

                <div class="d-flex align-items-center justify-content-between p-3 rounded-3 mt-2"
                    style="background-color: #f8f9fa; border: 1px dashed #ced4da;">
                    <div class="d-flex align-items-center text-secondary">
                        <i class="fa fa-cubes me-2 fs-5 text-secondary"></i>
                        <span class="small fw-bold">Available Stock</span>
                    </div>
                    <span id="variantStock" class="badge bg-success fs-6 rounded-pill px-3 py-2 shadow-sm">0</span>
                </div>
            </div>

            <div class="modal-footer border-0 px-4 pb-4 pt-2">
                <button type="button" id="addVariantCart"
                    class="btn btn-dark w-100 py-3 fw-bold rounded-3 shadow-sm variant-btn">
                    <i class="fa fa-shopping-cart me-2"></i> Add To Cart
                </button>
            </div>

        </div>
    </div>
</div>
