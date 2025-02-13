<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Department</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <form id="editDepartmentForm" method="POST" action="{{ route('department.store') }}">
                @csrf
                <input type="hidden" id="department_id" name="id">
                <div class="modal-body">
                    <label>Name:</label>
                    <input type="text" id="department_name" name="name" class="form-control" required>
                    <!-- Employee Dropdown -->
                    <label class="mt-2">Assign Employee:</label>
                    <select id="employee_id" name="employee_id" class="form-control" required>
                        <option value="">Select Employee</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                        @endforeach
                    </select>
                    <!-- Description -->
                    <label class="mt-2">Description:</label>
                    <textarea id="description" name="description" class="form-control" rows="3"
                        placeholder="Enter department description"></textarea>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save Changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
