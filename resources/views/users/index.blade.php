@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content')
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
                    $('#department_id').val(data.department_id).change();
                    $('#submitButton').text("Update Employee");
                    $('#registration_no').val(data.registration_no);
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
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        alert(response.success);
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
            $('#submitButton').text("Add Employee");
            $('#registration_no').val("");
        }

        function submitProductForm() {
            $('#addCustomerForm').submit(function(e) {
                e.preventDefault(); // Prevent default form submission

                let submitButton = $('#submitButton'); // Change to the actual ID of your button
                submitButton.prop('disabled', true); // Disable the button
                submitButton.html('<i class="fa fa-spinner fa-spin"></i> Saving...'); // Add a loader icon
                $.ajax({
                    url: "{{ route('users.store') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        console.log(response);
                        if (response.success == 1) {
                            submitButton.prop('disabled', false); // Re-enable button
                            submitButton.html('Save Employee'); // Reset button text
                            $('#modalCustomer').modal('hide'); // Hide modal
                            $('#user_id').val("");
                            $('#name').val("");
                            $('#mobile').val("");
                            $('#email').val("");
                            $('#submitButton').text("Add Employee");
                            $('#registration_no').val("");
                            $('#productTable').DataTable().ajax.reload(); // Refresh DataTable
                        } else {
                            submitButton.prop('disabled', false); // Re-enable button
                            submitButton.html('Save Employee'); // Reset button text
                            alert(response.msg);
                        }
                    },
                    error: function(xhr) {
                        alert('Error adding product.');
                    }
                });
            });
        }
    </script>
@stop
