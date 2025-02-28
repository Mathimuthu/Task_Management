<x-adminlte-modal id="modalTask" title="Task Details" theme="purple" icon="fas fa-tasks" size='md'>
    <form id="addTaskForm" method="POST" name="yes" action="{{ route('tasks.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="title" class="text-black">Title <span style="color: red;">*</span> </label>
            <div class="input-group">
                <input type="hidden" id="task_id" name="task_id">
                <input type="text" id="title" name="title" class="form-control" placeholder="Enter task title"
                    required>
            </div>
        </div>
        <div class="form-group">
            <label class="mt-2">Description:</label>
            <textarea id="description" name="description" class="form-control" rows="3" placeholder="Enter description"></textarea>
        </div>
        <div class="form-group">
            <label for="priority">Priority <span style="color: red;">*</span> </label>
            <select id="priority" name="priority" class="form-control" required>
                <option value="Low">Low</option>
                <option value="Medium" selected>Medium</option>
                <option value="High">High</option>
                <option value="Urgent">Urgent</option>
            </select>
        </div>
        <div class="form-group">
            <label for="employee_ids">Assigned Employees <span style="color: red;">*</span> </label>
            <input type="hidden" name="employee_ids" id="employe">
            <select id="employee_ids" name="employee_ids" class="form-control" required>
                @php
                    foreach ($employees as $value) {
                        echo "<option value='" . $value->id . "'>" . $value->name . '</option>';
                    }
                @endphp
            </select>
        </div>
        <div class="form-group">
            <label for="department_id">Department</label>
            <input type="hidden" id="department_id" name="department_id">
            <select id="department_ids" name="department_ids" class="form-control"  disabled>
                @php
                    foreach ($departments as $value) {
                        echo "<option value='" . $value->id . "'>" . $value->name . '</option>';
                    }
                @endphp
            </select>
        </div>
        <div class="form-group">
            <label for="assign_date">Assign Date <span style="color: red;">*</span> </label>
            <input type="date" id="assign_date" name="assign_date" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="deadline">Deadline <span style="color: red;">*</span> </label>
            <input type="date" id="deadline" name="deadline" class="form-control" required min="{{ \Carbon\Carbon::today()->toDateString() }}">
        </div>
        <div class="form-group" id="filePreview" style="display: none;">
            <!-- PDF or DOCX file will be previewed here -->
        </div>
        <div class="form-group">
            <label for="upload_task">Upload Photo</label>
            <input type="file" id="upload_task" name="upload_task" placeholder="Upload Photo" class="form-control">
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status" class="form-control" required>
                <option value="Pending" selected>Pending</option>
                <option value="In Progress">In Progress</option>
                <option value="Completed">Completed</option>
            </select>
        </div>

        <!-- Submit Button -->
        <div class="form-group">
            <button id="submitButton" onclick="submitTaskForm()" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Task
            </button>
        </div>
    </form>

    <x-slot name="footerSlot" :null="true"></x-slot>
</x-adminlte-modal>
