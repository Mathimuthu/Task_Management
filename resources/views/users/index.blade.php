@extends('adminlte::page')

@section('title', 'User Management')

@section('content')
<style>
    .user-info {
        display: flex;
        align-items: center;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .label {
        width: 180px; /* Set a fixed width for the labels */
        font-weight: bold;
    }

    .colon {
        margin-right: 5px; /* Space between label and value */
    }

    .user-info span {
        white-space: nowrap; /* Prevent text from wrapping */
    }
</style>

    <div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Employees List</h2>
            @if ($hasCreatepermissions)
                <button onclick="showAddEmployeeModal()" class="bg-purple btn">Add Employee</button>
            @endif
        </div>
        <table id="productTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Register.No</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    {{-- Themed --}}
    @include('users.create')
    @include('users.statusUpdate')
    @include('users.viewUser')
@stop


@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script src="{{ asset('js/datatables.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/datatables.min.css') }}">
    <script>
        $(document).ready(function() {
            $('#productTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('users.index') }}",
                columns: [{
                        data: "id",
                        "render": function(data, type, row, meta) {
                            return meta.row + 1; // Uses row index
                        }
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'registration_no',
                        name: 'registration_no'
                    },
                    {
                        data: 'mobile',
                        name: 'mobile'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },                   
                    {
                        data: 'department_names',
                        name: 'department_names'
                    },
                    {
                        data: 'role_name',
                        name: 'role_name'
                    },
                    {
                        "data": "status",
                        "render": function(data, type, row) {
                            return data == 1 ? 'Active' : 'Inactive';
                        }
                    }, {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
        $(document).on('click', '.updateStatusBtn', function() {
            let taskId = $(this).data("users-id");
            let url = $(this).data("url");
            let currentStatus = $(this).data("current-status");
            // Set values in the modal form
            $("#s_user_id").val(taskId);
            $("#u_status").val(currentStatus).change();
            $("#updateUserForm").attr("action", url);
            $('#statusUpdateModal').modal('show');
        });
        
        function submituserStatusForm() {
            $('#updateUserForm').submit(function(e) {
                e.preventDefault();
                let url = $(this).attr("action");
                let submitButton = $('#updateButton');
                submitButton.prop('disabled', true);
                submitButton.html('<i class="fa fa-spinner fa-spin"></i> Saving...');
                $.ajax({
                    url: url,
                    type: "PUT",
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success == 1) {
                            submitButton.prop('disabled', false);
                            submitButton.html('Update Status');
                            $('#statusUpdateModal').modal('hide');
                            $('#productTable').DataTable().ajax.reload();
                        } else {
                            submitButton.prop('disabled', false);
                            submitButton.html('Update Status');
                            alert(response.msg);
                        }
                    },
                    error: function() {
                        submitButton.prop('disabled', false);
                        submitButton.html('Update Status');
                        alert('Error Updating Status.');
                    }
                });
            });
        }
        $(document).on('click', '.view-btn', function() {
            let url = $(this).data('url');
            $.ajax({
                url: url, 
                type: 'GET',                
                success: function(data) {
                    function getSafeValue(value) {
                        return value ? value : '-';
                    }
                    $('#view_name').text(getSafeValue(data.name));
                    $('#view_registration_no').text(getSafeValue(data.registration_no));
                    $('#view_mobile').text(getSafeValue(data.mobile));
                    $('#view_email').text(getSafeValue(data.email));
                    $('#view_departments').text(getSafeValue(data.department_names));
                    $('#view_dob').text(getSafeValue(data.dob));
                    $('#view_address').text(getSafeValue(data.address));
                    $('#view_blood_group').text(getSafeValue(data.blood_group));
                    $('#view_createdby').text(getSafeValue(data.createdby)); 
                    $('#view_status').text(data.status == 1 ? 'Active' : 'Inactive');
                    if (data.photo) {
                        $('#view_photo').attr('src', '{{ asset("") }}' + data.photo);
                    } else {
                        $('#view_photo').attr('src', '/default-photo.jpg');
                    }
                    $('#viewUserModal').modal('show');  
                },
                error: function() {
                    alert('Error fetching user data.');
                }
            });
        });
        // Handle Edit Button Click
        $(document).on('click', '.edit-btn', function() {
            let url = $(this).data('url');
            $.ajax({
                url: url,
                type: "GET",
                success: function(data) {
                    $('#modalCustomer').modal('show');
                    $('#user_id').val(data.id);
                    $('#name').val(data.name);
                    $("#registration_no").val(data.registration_no);
                    $('#mobile').val(data.mobile);
                    $('#email').val(data.email);
                    $('#address').val(data.address);
                    $('#dob').val(data.dob);
                    $('#blood_group').val(data.blood_group);
                    $('#department_id').val(data.department_id).change();
                    $('#submitButton').text("Update Employee");
                    if (data.photo) {
                        $('#photoPreview').attr('src', '{{ asset("") }}' + data.photo);
                        $('#photoPreview').show();
                    } else {
                        $('#photoPreview').hide(); 
                    }
                },
                error: function() {
                    alert("Error fetching data.");
                }
            });
        });

        // Handle Delete Button Click
        $(document).on('click', '.delete-btn', function() {
            let url = $(this).data('url');
            let id = $(this).data('id');
            if (confirm("Are you sure you want to deactivate this user?")) {
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        _method: "DELETE",
                        id:id
                    },
                    success: function(response) {
                        $('#productTable').DataTable().ajax.reload();
                    },
                    error: function() {
                        alert("Error processing request.");
                    }
                });
            }
        });
        $(document).on('click', '.restore-btn', function() {
            let url = $(this).data('url');
            if (confirm("Are you sure you want to restore this user?")) {
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        $('#productTable').DataTable().ajax.reload();
                    },
                    error: function() {
                        alert("Error processing request.");
                    }
                });
            }
        });
        function showAddEmployeeModal() {
            $('#modalCustomer').modal('show'); // Hide modal
            $('#user_id').val("");
            $('#name').val("");
            $('#mobile').val("");
            $('#email').val("");
            $('#address').val("");
            $('#dob').val("");
            $('#blood_group').val("");
            $('#photo').val("");
            $('#submitButton').text("Add Employee");
            $('#registration_no').val("");
        }

        function submitProductForm() {
            $('#addCustomerForm').submit(function(e) {
                e.preventDefault(); 
                let submitButton = $('#submitButton'); 
                submitButton.prop('disabled', true); 
                submitButton.html('<i class="fa fa-spinner fa-spin"></i> Saving...'); 
                
                var formData = new FormData(this);
                $.ajax({
                    url: "{{ route('users.store') }}",
                    type: "POST",
                    data: formData,
                    contentType: false, 
                    processData: false,
                    success: function(response) {
                        if (response.success == 1) {
                            submitButton.prop('disabled', false); 
                            submitButton.html('Save Employee'); 
                            $('#modalCustomer').modal('hide'); // Hide modal
                            $('#user_id').val("");
                            $('#name').val("");
                            $('#mobile').val("");
                            $('#email').val("");
                            $('#address').val("");
                            $('#dob').val("");
                            $('#blood_group').val("");
                            $('#photo').val("");
                            $('#submitButton').text("Add Employee");
                            $('#registration_no').val("");
                            $('#productTable').DataTable().ajax.reload(); 
                        } else {
                            submitButton.prop('disabled', false); 
                            submitButton.html('Save Employee'); 
                            // Only show the alert once
                            if (response.success === 0) {
                                alert(response.msg); // Show error message
                            }
                        }
                    },
                    error: function(xhr) {
                        submitButton.prop('disabled', false); 
                        submitButton.html('Save Employee');
                        alert('Error adding product.');
                    }
                });
            });
        }
    </script>
@stop
