<!-- ADD / EDIT EVENT MODAL -->
<div class="modal fade" id="addEventModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="addEventModalLabel">Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="eventForm" method="POST" action="{{ route('calendar.store') }}">
                @csrf
                <input type="hidden" name="event_id" id="event_id">

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="fw-bold">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="event_title" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Type <span class="text-danger">*</span></label>
                        <select name="type" id="event_type" class="form-select" required>
                            <option value="">Select</option>
                            <option value="meeting">Meeting</option>
                            <option value="task">Task</option>
                            <option value="appointment">Appointment</option>
                            <option value="deadline">Deadline</option>
                            <option value="presentation">Presentation</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Description</label>
                        <textarea name="description" id="event_description" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Start <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="start_time" id="event_start" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">End <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="end_time" id="event_end" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Status</label>
                        <select name="status" id="event_status" class="form-select">
                            <option value="1">Active</option>
                            <option value="2">Cancelled</option>
                            <option value="3">Completed</option>
                        </select>
                    </div>

                    <input type="hidden" name="backgroundcolor" id="backgroundcolor" value="#6c757d">
                    <input type="hidden" name="bordercolor" id="bordercolor" value="#6c757d">
                    <input type="hidden" name="textcolor" id="textcolor" value="#ffffff">

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger d-none" id="deleteBtn">Delete</button>
                    <button type="submit" class="btn btn-primary" id="modal-submit-btn">Save</button>
                </div>

            </form>
        </div>
    </div>
</div>
