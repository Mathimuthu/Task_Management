<x-adminlte-modal id="statusUpdateModal" title="Update User" theme="purple" icon="fas fa-edit" size='md'>
    <form id="updateUserForm" method="POST" action="">
        @csrf
        @method('PUT')

        <input type="hidden" id="s_user_id" name="user_id" value="">


        <div class="form-group">
            <label for="status">Status</label>
            <select id="u_status" name="status" class="form-control" required>
                <option value="1">Active</option>
                <option value="0">In Active</option>
            </select>
        </div>
        <div class="form-group">
            <button id="updateButton"  onclick="submituserStatusForm()"  type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update Status
            </button>
        </div>
    </form>

    <x-slot name="footerSlot" :null="true"></x-slot>
</x-adminlte-modal>
