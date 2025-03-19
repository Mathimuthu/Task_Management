<div class="modal fade" id="modal_Purple" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Department</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="addDepartmentForm" method="POST">
                    @csrf
                    <input type="hidden" id="department_id" name="department_id">
                    <div class="modal-body">
                        <label>Name:</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                        <!-- <label class="mt-2">Assign Employee:</label>
                        <select id="employee_id" name="employee_id[]" class="form-control" multiple required>
                            @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select> -->
                        <label class="mt-2">Description:</label>
                        <textarea id="description" name="description" class="form-control" rows="3"
                            placeholder="Enter department description"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" id="submitDep" onclick="submitDepartmentForm()">Save Changes</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>