<div class="modal fade" id="supplierModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <form id="supplierForm">
                @csrf

                <!-- Modal Header -->
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-semibold">
                        <i class="fas fa-truck me-2"></i>
                        Add New Supplier
                    </h5>

                    <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">

                    <div class="row">
                        <!-- Name -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-semibold">
                                Supplier Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control"
                                name="name"
                                placeholder="Enter supplier name"
                                required>
                        </div>

                        <!-- Phone -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">
                                Phone
                            </label>
                            <input type="text"
                                class="form-control"
                                name="phone"
                                placeholder="01XXXXXXXXX">
                        </div>

                        <!-- Email -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">
                                Email
                            </label>
                            <input type="email"
                                class="form-control"
                                name="email"
                                placeholder="supplier@example.com">
                        </div>

                        <!-- Address -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-semibold">
                                Address
                            </label>
                            <textarea class="form-control"
                                name="address"
                                rows="3"
                                placeholder="Enter supplier address"></textarea>
                        </div>

                        <!-- Status -->
                        <div class="col-md-12">
                            <label class="form-label fw-semibold d-block">
                                Status
                            </label>

                            <div class="form-check form-switch">
                                <input class="form-check-input"
                                    type="checkbox"
                                    id="supplierStatus"
                                    name="status"
                                    value="1"
                                    checked>

                                <label class="form-check-label fw-medium"
                                    for="supplierStatus">
                                    Active Supplier
                                </label>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="modal-footer border-top">
                    <button type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit"
                        class="btn btn-primary px-4">
                        <i class="fas fa-save me-1"></i>
                        Save Supplier
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>