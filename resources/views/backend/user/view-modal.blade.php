<div class="modal fade" id="showUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
        <div class="modal-content shadow-lg border-0" style="border-radius: 15px; overflow: hidden;">
            
            <div class="modal-header bg-dark text-white p-4" style="border-bottom: 0;">
                <div class="d-flex align-items-center w-100">
                    <img id="show_image" src="" alt="User Image" class="rounded-circle border border-white border-4 me-4 shadow" style="width: 100px; height: 100px; object-fit: cover;">
                    <div class="flex-grow-1">
                        <h2 id="show_name" class="modal-title fw-bold m-0 text-white"></h2>
                        <div class="mt-2 d-flex gap-2 align-items-center">
                            <span id="show_roles" class="badge bg-white text-dark p-2 fs-7" style="border-radius: 4px;"></span>
                            <span id="show_status" class="badge p-2 fs-7" style="border-radius: 4px;"></span>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="font-size: 1.2rem; margin-top: -3rem;"></button>
            </div>

            <div class="modal-body p-4 bg-light">
                <div class="row g-4">
                    
                    <div class="col-lg-6">
                        <div class="card h-100 border-0 shadow-sm rounded-3">
                            <div class="card-header bg-primary text-white fw-bold text-uppercase rounded-top-3 d-flex align-items-center" style="font-size: 13px; letter-spacing: 1px; padding: 12px 20px;">
                                <i class="fa-solid fa-briefcase me-2 fs-6"></i> Work Information
                            </div>
                            <div class="card-body p-4">
                                <table class="table table-sm table-borderless m-0 text-sm">
                                    <tbody>
                                        <tr><td class="text-muted w-40 fw-medium">Employee Code</td><td class="fw-bold text-dark" id="show_employee_code"></td></tr>
                                        <tr><td class="text-muted fw-medium">Designation</td><td class="fw-bold text-dark" id="show_designation"></td></tr>
                                        <tr><td class="text-muted fw-medium">Joining Date</td><td class="fw-bold text-dark" id="show_joining_date"></td></tr>
                                        <tr><td class="text-muted fw-medium">Salary (BDT)</td><td class="fw-bold text-dark" id="show_salary"></td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card h-100 border-0 shadow-sm rounded-3">
                            <div class="card-header bg-success text-white fw-bold text-uppercase rounded-top-3 d-flex align-items-center" style="font-size: 13px; letter-spacing: 1px; padding: 12px 20px;">
                                <i class="fa-solid fa-user me-2 fs-6"></i> Personal Details
                            </div>
                            <div class="card-body p-4">
                                <table class="table table-sm table-borderless m-0 text-sm">
                                    <tbody>
                                        <tr><td class="text-muted w-40 fw-medium">Username</td><td class="fw-bold text-dark" id="show_username"></td></tr>
                                        <tr><td class="text-muted fw-medium">Gender</td><td class="fw-bold text-dark" id="show_gender"></td></tr>
                                        <tr><td class="text-muted fw-medium">Birth Date</td><td class="fw-bold text-dark" id="show_birth_date"></td></tr>
                                        <tr><td class="text-muted fw-medium">NID Number</td><td class="fw-bold text-dark" id="show_nid"></td></tr>
                                        <tr><td class="text-muted fw-medium">Passport Number</td><td class="fw-bold text-dark" id="show_passport"></td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card h-100 border-0 shadow-sm rounded-3">
                            <div class="card-header bg-info text-dark fw-bold text-uppercase rounded-top-3 d-flex align-items-center" style="font-size: 13px; letter-spacing: 1px; padding: 12px 20px;">
                                <i class="fa-solid fa-phone me-2 fs-6"></i> Contact Information
                            </div>
                            <div class="card-body p-4">
                                <table class="table table-sm table-borderless m-0 text-sm">
                                    <tbody>
                                        <tr><td class="text-muted w-40 fw-medium">Email</td><td class="fw-bold text-dark" id="show_email"></td></tr>
                                        <tr><td class="text-muted fw-medium">Phone</td><td class="fw-bold text-dark" id="show_phone"></td></tr>
                                        <tr><td class="text-muted fw-medium">Alternate Phone</td><td class="fw-bold text-dark" id="show_alt_phone"></td></tr>
                                        <tr><td class="text-muted fw-medium">Emergency Contact</td><td class="fw-bold text-dark" id="show_emg_contact"></td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card h-100 border-0 shadow-sm rounded-3">
                            <div class="card-header bg-warning text-dark fw-bold text-uppercase rounded-top-3 d-flex align-items-center" style="font-size: 13px; letter-spacing: 1px; padding: 12px 20px;">
                                <i class="fa-solid fa-location-dot me-2 fs-6"></i> Address Details
                            </div>
                            <div class="card-body p-4">
                                <table class="table table-sm table-borderless m-0 text-sm">
                                    <tbody>
                                        <tr><td class="text-muted w-40 fw-medium">Location</td><td class="fw-bold text-dark" id="show_location"></td></tr>
                                        <tr><td class="text-muted fw-medium">Postal / Country</td><td class="fw-bold text-dark" id="show_country"></td></tr>
                                        <tr><td class="text-muted fw-medium">Full Address</td><td class="fw-bold text-dark" id="show_address"></td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div> </div>

            <div class="modal-footer bg-white p-3 border-top shadow-lg" style="border-radius: 0 0 15px 15px;">
                <div class="w-100 d-flex justify-content-between align-items-center">
                    <span class="text-muted small">
                        <i class="fa-regular fa-calendar-plus me-1"></i> Profile Created: <strong class="text-dark" id="show_joined"></strong>
                    </span>
                    <button type="button" class="btn btn-primary px-5 fw-bold" data-bs-dismiss="modal" style="border-radius: 8px;">Close Details</button>
                </div>
            </div>
        </div>
    </div>
</div>