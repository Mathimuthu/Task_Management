@extends('adminlte::page')

@section('title', 'Roles Management')

@section('content')
<style>
    /* Make the table horizontally scrollable */
.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    margin-bottom: 15px;
}

/* Adjust label widths for small screens */
@media (max-width: 767px) {
    .user-info {
        font-size: 12px;
    }

    .label {
        width: auto; /* Allow labels to adjust width */
    }

    /* Adjust modal content for small screens */
    .modal-content {
        width: 90%;
    }

    /* Stack the buttons vertically in smaller screens */
    .d-flex {
        flex-direction: column;
        align-items: stretch;
    }

    /* Style for buttons on mobile devices */
    .btn {
        font-size: 12px;
        padding: 10px;
        margin-bottom: 10px;
    }

    /* Make the table smaller and more compact */
    .table th, .table td {
        font-size: 12px;
        padding: 8px;
    }
}

/* Adjust for very small screens (portrait mode on mobile) */
@media (max-width: 480px) {
    .d-flex {
        flex-direction: column;
    }

    .table th, .table td {
        font-size: 10px;
        padding: 5px;
    }
}
/* Ensure action buttons stay in one row */
.table td .btn {
    white-space: nowrap;
    display: inline-block;
}

/* Prevent wrapping on smaller screens */
@media (max-width: 767px) {
    .table td {
        white-space: nowrap;
    }

    .table td .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
}

</style>
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
                        searchable: false,
                        className: "text-center", 
                        width: "100px"
                    }
                ],
                language: {
                    lengthMenu: 'Show &nbsp;_MENU_ &nbsp;&nbsp;Entries Per Page',
                    info: 'Showing _START_ to _END_ of _TOTAL_ Entries' 
                }
            });
            $(window).resize(function() {
                if ($(window).width() <= 767) {
                    $(".table").addClass("table-responsive");
                } else {
                    $(".table").removeClass("table-responsive");
                }
            }).trigger('resize');
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
