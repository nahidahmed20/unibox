<!-- CUSTOMER VIEW MODAL (PREMIUM UI) -->
<div class="modal fade" id="customerModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">

        <div class="modal-content border-0 rounded-5 overflow-hidden shadow-lg">

            <!-- HEADER -->
            <div class="modal-header text-white position-relative"
                 style="background: linear-gradient(135deg,#0f172a,#1e3a8a,#6366f1);">

                <div class="d-flex flex-column">

                    <h3 class="fw-bold mb-1" id="customer_name">Customer Name</h3>

                    <div class="d-flex align-items-center gap-2">

                        <span class="text-white-50 small">
                            📞 <span id="customer_phone">-</span>
                        </span>

                        <span id="customer_type_badge"
                              class="badge bg-light text-dark px-3 py-2 rounded-pill">
                            Type
                        </span>

                    </div>
                </div>

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <!-- BODY -->
            <div class="modal-body bg-light p-4">

                <div class="row g-4">

                    <!-- LEFT -->
                    <div class="col-lg-8">

                        <!-- INFO -->
                        <div class="card border-0 shadow-sm rounded-4 hover-shadow">
                            <div class="card-body">

                                <h6 class="fw-bold text-primary mb-3">
                                    🧾 Basic Information
                                </h6>

                                <div class="row">

                                    <div class="col-md-6 mb-3">
                                        <div class="text-muted small">Customer Code</div>
                                        <div class="fw-bold fs-6" id="customer_code">-</div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="text-muted small">Type</div>
                                        <div class="fw-bold" id="customer_type_text">-</div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="text-muted small">Phone</div>
                                        <div class="fw-bold" id="customer_phone">-</div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="text-muted small">Alternate Phone</div>
                                        <div class="fw-bold" id="alternate_phone">-</div>
                                    </div>

                                </div>

                            </div>
                        </div>

                        <!-- ADDRESS -->
                        <div class="card border-0 shadow-sm rounded-4 mt-4">
                            <div class="card-body">

                                <h6 class="fw-bold text-success mb-3">
                                    🏠 Address Details
                                </h6>

                                <div class="mb-3">
                                    <div class="text-muted small">Full Address</div>
                                    <div class="fw-semibold" id="address">-</div>
                                </div>

                                <div class="row">

                                    <div class="col-md-4">
                                        <div class="text-muted small">City</div>
                                        <div class="fw-semibold" id="city">-</div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="text-muted small">State</div>
                                        <div class="fw-semibold" id="state">-</div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="text-muted small">Country</div>
                                        <div class="fw-semibold" id="country">-</div>
                                    </div>

                                </div>

                            </div>
                        </div>

                        <!-- NOTE -->
                        <div class="card border-0 shadow-sm rounded-4 mt-4">
                            <div class="card-body">

                                <h6 class="fw-bold text-warning mb-3">
                                    📝 Notes
                                </h6>

                                <div class="text-dark" id="note">-</div>

                            </div>
                        </div>

                    </div>

                    <!-- RIGHT -->
                    <div class="col-lg-4">

                        <!-- OPENING BALANCE -->
                        <div class="card border-0 rounded-4 text-white shadow-sm"
                             style="background: linear-gradient(135deg,#3b82f6,#6366f1);">

                            <div class="card-body text-center">

                                <div class="small opacity-75">Opening Balance</div>
                                <h2 class="fw-bold mt-2" id="customer_opening_balance">0.00</h2>

                            </div>
                        </div>

                        <!-- TOTAL DUE -->
                        <div class="card border-0 rounded-4 text-white shadow-sm mt-3"
                             style="background: linear-gradient(135deg,#ef4444,#f97316);">

                            <div class="card-body text-center">

                                <div class="small opacity-75">Total Due</div>
                                <h2 class="fw-bold mt-2" id="customer_total_due">0.00</h2>

                            </div>
                        </div>

                        <!-- STATUS -->
                        <div class="card border-0 rounded-4 shadow-sm mt-3">

                            <div class="card-body text-center">

                                <div class="small text-muted mb-2">Account Status</div>

                                <span id="status_badge"
                                      class="badge px-4 py-2 fs-6 rounded-pill bg-success">
                                    Active
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>
</div>