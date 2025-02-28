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
    @media (min-width: 768px) { /* Apply only to system view (larger screens) */
    #productTable td:last-child {
        padding: 10px 0px;
    }
}
/* Toggle Switch Styling */
.switch {
  position: relative;
  display: inline-block;
  width: 34px;
  height: 18px;
}

.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  transition: .4s;
  border-radius: 34px;
}

.slider:before {
  position: absolute;
  content: "";
  height: 14px;
  width: 14px;
  left: 2px;
  bottom: 2px;
  background-color: white;
  transition: .4s;
  border-radius: 50%;
}

input:checked + .slider {
  background-color: #4CAF50;
}

input:checked + .slider:before {
  transform: translateX(16px);
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
    <link rel="stylesheet" href="{{ asset('css/datatables.min.css') }}">
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/datatables.min.js') }}"></script>
    <script>
    $(document).ready(function() {
        $('#productTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('users.index') }}",
            columns: [
                { 
                    data: "id", 
                    "render": function(data, type, row, meta) {
                        return meta.row + 1; // Row index
                    }
                },
                { data: 'name', name: 'name' },
                { data: 'registration_no', name: 'registration_no' },
                { data: 'mobile', name: 'mobile' },
                { data: 'email', name: 'email' },
                { data: 'department_names', name: 'department_names' },
                { data: 'role_name', name: 'role_name' },
                {
                    data: "status", 
                    name: "status",
                    orderable: false,
                    searchable: true,
                    "render": function(data, type, row) {
                        let checked = data == 1 ? 'checked' : ''; // Ensure 1 means active
                        let titleText = data == 1 ? 'Active' : 'Inactive'; // Set title
                        return `
                            <label class="switch" title="${titleText}">
                                <input type="checkbox" class="toggle-status" data-id="${row.id}" ${checked}>
                                <span class="slider round"></span>
                            </label>
                        `;
                    }
                },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            order: [[0, 'desc']],  
            language: {
                lengthMenu: 'Show &nbsp;_MENU_ &nbsp;&nbsp;Entries Per Page',
                info: 'Showing _START_ to _END_ of _TOTAL_ Entries'
            }
        });
    });
    $(document).on('change', '.toggle-status', function() {
        let userId = $(this).data('id');
        let isChecked = $(this).prop('checked');
        let newStatus = isChecked ? 1 : 0;
        let confirmText = newStatus 
            ? "Do you want to activate this user?" 
            : "Do you want to deactivate this user?";

        Swal.fire({
            title: "Confirm Status Change",
            text: confirmText,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes",
            cancelButtonText: "No",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/users/" + userId,  // ✅ Pass ID in URL
                    type: "PUT",  // ✅ Use PUT for updates
                    data: { status: newStatus, _token: "{{ csrf_token() }}" },
                    success: function(response) {
                        Swal.fire({
                            title: "Success",
                            text: response.message,
                            icon: "success"
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: "Error",
                            text: "Something went wrong!",
                            icon: "error"
                        });
                        // Revert toggle switch if error occurs
                        $(this).prop('checked', !isChecked);
                    }
                });
            } else {
                // Revert toggle switch state if user cancels
                $(this).prop('checked', !isChecked);
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
        // $(document).on('click', '.updateStatusBtn', function() {
        //     let taskId = $(this).data("users-id");
        //     let url = $(this).data("url");
        //     let currentStatus = $(this).data("current-status");
        //     // Set values in the modal form
        //     $("#s_user_id").val(taskId);
        //     $("#u_status").val(currentStatus).change();
        //     $("#updateUserForm").attr("action", url);
        //     $('#statusUpdateModal').modal('show');
        // });
        
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
                        $('#view_photo').attr('src', '{{asset("/images/default-user-logo.webp")}} ');
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
                    $('#role').val(data.role).change();
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
