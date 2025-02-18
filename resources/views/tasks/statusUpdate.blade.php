<x-adminlte-modal id="statusUpdateModal" title="Update Task" theme="purple" icon="fas fa-edit" size='md'>
    <form id="updateTaskForm" method="POST" action="">
        @csrf
        @method('PUT')

        <input type="hidden" id="task_id" name="task_id">


        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status" class="form-control" required>
                <option value="Pending">Pending</option>
                <option value="In Progress">In Progress</option>
                <option value="Completed">Completed</option>
                <option value="Cancelled">Cancelled</option>
            </select>
        </div>

        <div class="form-group">
            <label class="mt-2">Description:</label>
            <textarea id="description" name="description" class="form-control" rows="3"></textarea>
        </div>


        <div class="form-group">
            <button id="updateButton" type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update Status
            </button>
        </div>
    </form>

    <x-slot name="footerSlot" :null="true"></x-slot>
</x-adminlte-modal>
