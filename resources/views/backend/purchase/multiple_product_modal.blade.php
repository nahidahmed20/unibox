<!-- Ultimate Premium Bulk Variant Selection Modal -->
    <div class="modal fade" id="variantModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="variantModalLabel" aria-hidden="true" style=" background: rgba(15, 23, 42, 0.3);">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 24px; overflow: hidden; background: #ffffff;">
                
                <!-- Ultimate Colorful Premium Modal Header -->
                <div class="modal-header align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <!-- Translucent Glass Icon Box -->
                        <div class="modal-header-icon-box">
                            <i class="fa fa-cubes fs-5"></i>
                        </div>
                        <div>
                            <!-- White Crisp Typography -->
                            <h5 class="modal-title fw-extrabold text-white mb-0" id="variantModalLabel" style="font-size: 1.2rem; letter-spacing: -0.3px;">
                                Select Product Variants
                            </h5>
                            <div class="d-flex align-items-center gap-2 mt-1">
                                <span class="badge bg-success bg-opacity-25 text-success border-0 px-2 py-0.5 fw-bold" style="font-size: 0.72rem; background-color: rgba(34, 197, 94, 0.2) !important; color: #4ade80 !important;">
                                    <i class="fa fa-bolt me-1"></i> Bulk Entry Mode
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- Clean White Close Button -->
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <!-- Modal Body with Premium Matrix View -->
                <div class="modal-body p-0" style="max-height: 480px; overflow-y: auto; background: #ffffff;">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="variantMatrixTable">
                            <thead>
                                <tr style="background-color: #f8fafc;">
                                    <th class="ps-4 text-secondary text-uppercase py-3" style="font-size: 0.75rem; font-weight: 700; letter-spacing: 0.5px; border-bottom: 1px solid #e2e8f0; color: #64748b;">Variant Combination</th>
                                    <th class="text-secondary text-uppercase py-3" style="font-size: 0.75rem; font-weight: 700; letter-spacing: 0.5px; border-bottom: 1px solid #e2e8f0; color: #64748b;">SKU</th>
                                    <th class="text-secondary text-uppercase py-3 text-end" style="font-size: 0.75rem; font-weight: 700; letter-spacing: 0.5px; border-bottom: 1px solid #e2e8f0; color: #64748b; width: 140px;">Unit Price (BDT)</th>
                                    <th class="pe-4 text-secondary text-uppercase py-3 text-center" style="font-size: 0.75rem; font-weight: 700; letter-spacing: 0.5px; border-bottom: 1px solid #e2e8f0; color: #64748b; width: 140px;">Quantity</th>
                                </tr>
                            </thead>
                            <tbody id="variantModalTableBody">
                                <!-- Dynamic Rows Go Here -->
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Modal Footer with Modern Action Buttons -->
                <div class="modal-footer px-4 py-3" style="background-color: #f8fafc; border-top: 1px solid #f1f5f9;">
                    <button type="button" class="btn btn-link text-secondary text-decoration-none fw-semibold" data-bs-dismiss="modal" style="font-size: 0.9rem; transition: color 0.2s;">Cancel</button>
                    <button type="button" class="btn btn-primary px-4 py-2-5 fw-bold shadow-sm d-flex align-items-center gap-2" id="btnModalAddProducts" style="border-radius: 12px; font-size: 0.9rem; background: #0f172a; border: 1px solid #0f172a; transition: all 0.2s;">
                        <span>Add to Document</span>
                        <i class="fa fa-arrow-right fs-7" style="font-size: 0.8rem;"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>