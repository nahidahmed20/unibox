<div class="modal fade" id="memberModal">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="memberForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="member_id">
                    <div class="modern-card-header d-flex justify-content-between align-items-center"
                        style="background:#000032; color:#fff; padding:15px 20px;">
                        <h4 id="modalTitle" style="color:#fff; margin-bottom:0;">
                            Add Variation
                        </h4>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="card-body p-4">
                        <div class="row">
                            @php
                                $fields = [
                                    'name' => 'Name',
                                    'code_number' => 'Code Number',
                                    'email' => 'Email',
                                    'phone' => 'Phone',
                                    'address' => 'Address',
                                    'occupation' => 'Occupation',
                                    'nationality' => 'Nationality',
                                    'religion' => 'Religion',
                                    'education' => 'Education',
                                ];
                            @endphp

                            @foreach ($fields as $key => $label)
                                <div class="col-md-6 mb-3">
                                    <label> {{ $label }} </label>
                                    <input type="text" id="{{ $key }}" name="{{ $key }}"
                                        class="form-control">
                                </div>
                            @endforeach
                            <div class="col-md-6 mb-3">
                                <label> Gender </label>
                                <select id="gender" name="gender" class="form-select">
                                    <option value="Male"> Male </option>
                                    <option value="Female"> Female</option>
                                    <option value="Other"> Other </option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label> Date Of Birth </label>
                                <input type="date" id="date_of_birth" name="date_of_birth" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label> Marital Status </label>
                                <select id="marital_status" name="marital_status" class="form-select">
                                    <option value="Single"> Single </option>
                                    <option value="Married"> Married </option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label> Image </label>
                                <input type="file" id="image" name="image" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label> Status </label>
                                <select id="status" name="status" class="form-select">
                                    <option value="1"> Active </option>
                                    <option value="0"> Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary" id="saveBtn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>