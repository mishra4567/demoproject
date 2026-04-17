<div id="addressModal" class="modal" style="display:none; background:rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('customer.save_address') }}">
                @csrf

                <div class="modal-header">
                    <h5 id="modalTitle">Add Address</h5>
                    <div class="btn-close" onclick="closeAddModal()"></div>
                </div>

                <div class="modal-body">

                    <!-- Hidden fields -->
                    <input type="hidden" name="customer_id" value="{{ $result->id }}">
                    <input type="hidden" name="id" id="addr_id">
                    <div class="mb-3">
                        <label>Address</label>
                        <textarea name="address" id="addr_address" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Landmark</label>
                        <input type="text" name="landmark" id="addr_landmark" class="form-control">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>City</label>
                            <input type="text" name="city" id="addr_city" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>State</label>
                            <input type="text" name="state" id="addr_state" class="form-control" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Zipcode</label>
                            <input type="text" name="zipcode" id="addr_zipcode" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Country</label>
                            <input type="text" name="country" id="addr_country" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Label</label>
                            <select name="label" id="addr_label" class="form-control">
                                <option value="bg-primary">Home</option>
                                <option value="bg-success">work</option>
                                <option value="bg-info">Tempury</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3 d-flex align-items-end pb-1">
                            <div class="form-check form-switch">
                                <input type="checkbox" name="is_default" class="form-check-input" id="is_default"
                                    value="1" >
                                <label class="form-check-label" for="is_trending">Set as Default</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-secondary" onclick="deleteAdd()">Delete</a>
                    <a class="btn btn-dark" onclick="closeAddModal()">Close</a>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>

            </form>
        </div>
    </div>
</div>
