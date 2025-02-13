{{-- Edit Role Modal --}}
<x-adminlte-modal id="modalEditRole" title="Edit Role" theme="warning" icon="fas fa-edit" size='md'>
    <form id="editRoleForm">
        @csrf
        @method('PUT')

        <input type="hidden" id="edit_role_id" name="role_id">

        <div class="form-group">
            <label for="edit_role_name">Role Name</label>
            <input type="text" id="edit_role_name" name="name" class="form-control" required>
        </div>

        <label class="mt-2">Assign Permissions:</label>
        <div class="form-group" id="edit_permissions_list">
            <!-- Checkboxes will be loaded dynamically -->
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Update
            </button>
        </div>
    </form>
</x-adminlte-modal>
