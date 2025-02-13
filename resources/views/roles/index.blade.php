@extends('adminlte::page')

@section('title', 'Roles Management')

@section('content')
    <div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Role List</h2>
            @php
                if ($hasCreatepermissions) {
                    echo '<a href="#" data-toggle="modal" data-target="#modalPurple" class="bg-purple btn">Add Role</a>';
                }
            @endphp

        </div>
        <table id="roleTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Role Name</th>
                    <th>Permissions</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>

    @include('roles.edit')

    {{-- Themed Modal for Adding Roles --}}
    <x-adminlte-modal id="modalPurple" title="Add Role" theme="purple" icon="fas fa-tag" size='md'>
        <form id="addRoleForm" method="POST">
            @csrf
            <div class="form-group">
                <label for="role_name">Role Name</label>
                <input type="text" id="role_name" name="name" class="form-control" placeholder="Enter role name"
                    required>
            </div>

            <!-- Permissions Checkbox -->
            <label class="mt-2">Assign Permissions:</label>
            <div class="form-group">
                @foreach ($permissions as $permission)
                    <div class="form-check">
                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                            class="form-check-input">
                        <label class="form-check-label">{{ $permission->name }}</label>
                    </div>
                @endforeach
            </div>

            <div class="form-group">
                <button type="submit" onclick="submitRoleForm()" class="btn btn-success">
                    <i class="fas fa-save"></i> Save
                </button>
            </div>
        </form>
    </x-adminlte-modal>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/datatables.min.css') }}">
@stop

@section('js')
    <script src="{{ asset('js/datatables.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#roleTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('role.index') }}",
                columns: [{
                        data: 'id',
                        "render": function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'permissions',
                        name: 'permissions',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
        // Open Edit Modal and Fetch Role Data
        $(document).on('click', '.edit-btn', function() {
            let id = $(this).data('id');
            let url = "{{ url('role') }}/" + id + "/edit";

            $.ajax({
                url: url,
                type: "GET",
                success: function(response) {
                    $('#edit_role_id').val(response.role.id);
                    $('#edit_role_name').val(response.role.name);

                    let permissionsHtml = '';
                    response.permissions.forEach(permission => {
                        let checked = response.rolePermissions.includes(permission.id) ?
                            'checked' : '';
                        permissionsHtml += `
                            <div class="form-check">
                                <input type="checkbox" name="permissions[]" value="${permission.name}" class="form-check-input" ${checked}>
                                <label class="form-check-label">${permission.name}</label>
                            </div>`;
                    });

                    $('#edit_permissions_list').html(permissionsHtml);
                    $('#modalEditRole').modal('show');
                },
                error: function() {
                    alert("Error fetching role data.");
                }
            });
        });

        // Submit Edit Form (AJAX)
        $('#editRoleForm').submit(function(e) {
            e.preventDefault();
            let id = $('#edit_role_id').val();
            let url = "{{ url('role') }}/" + id;

            $.ajax({
                url: url,
                type: "PUT",
                data: $(this).serialize(),
                success: function(response) {
                    $('#modalEditRole').modal('hide');
                    $('#roleTable').DataTable().ajax.reload();
                    alert('Role updated successfully!');
                },
                error: function() {
                    alert('Error updating role.');
                }
            });
        });

        function submitRoleForm() {
            $('#addRoleForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('role.store') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#modalPurple').modal('hide');
                        $('#roleTable').DataTable().ajax.reload();
                        alert('Role added successfully!');
                    },
                    error: function() {
                        alert('Error adding role.');
                    }
                });
            });
        }

        $(document).on('click', '.delete-btn', function() {
            let url = $(this).data('url');

            if (confirm("Are you sure you want to delete this role?")) {
                $.ajax({
                    url: url,
                    type: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        $('#roleTable').DataTable().ajax.reload();
                        alert('Role deleted successfully!');
                    },
                    error: function() {
                        alert("Error processing request.");
                    }
                });
            }
        });
    </script>
@stop
