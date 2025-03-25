@extends('adminlte::page')

@section('title', 'User Management')

@section('content')
<style>
    /* Ensure text inside spans doesn't overflow */
.user-info span {
    display: inline-block;
    max-width: 100%; /* Prevent text overflow */
    word-wrap: break-word;
    overflow-wrap: break-word;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Ensure modal content is fully visible on small screens */
.modal-body {
    max-width: 100%;
    overflow-x: hidden;
}

/* Make the modal content responsive */
@media (max-width: 767px) {
    .modal-content {
        width: 95%;
        max-width: 95%;
    }

    .user-info {
        font-size: 12px; /* Reduce font size for better fit */
        flex-wrap: wrap; /* Allow content to wrap */
    }

    .label {
        width: 100px; /* Reduce label width on smaller screens */
        white-space: normal; /* Allow label text to wrap */
    }

    /* Ensure images scale correctly */
    #view_photo {
        width: 80px;
        height: 80px;
    }
}

/* Further adjustments for very small screens */
@media (max-width: 480px) {
    .label {
        width: auto;
    }

    .user-info {
        font-size: 11px;
    }
}

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
/* Ensure table width does not exceed 90% of the screen */
.table {
    max-width: 90%;
    margin: auto; /* Center the table */
}

/* Make the table responsive */
.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    width: 100%; /* Ensure it takes full available space */
}

/* Adjust table cells for better viewing */
.table th, .table td {
    white-space: nowrap; /* Prevent text wrapping */
}

/* Adjust for smaller screens */
@media (max-width: 767px) {
    .table-responsive {
        max-width: 100%; /* Allow full width on smaller screens */
    }

    .table {
        font-size: 12px; /* Reduce font size for readability */
    }

    .table th, .table td {
        padding: 8px;
        font-size: 10px;
    }
}

</style>

    <div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Employees List</h2>
            @if ($hasCreatepermissions)
                <button onclick="showAddEmployeeModal()" class="bg-purple btn">Add Employee</button>
            @endif
        </div>
        <div class="d-flex justify-content-end mb-2">
            <select id="statusFilter" class="form-control w-auto">
                <option>Status Filter </option>
                <option value="">All</option>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
        </div>
        <table id="productTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Name</th>
                    <th>Register Number</th>
                    <th>Phone Number</th>
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
            ajax: {
                url: "{{ route('users.index') }}",
                data: function (d) {
                    d.status = $('#statusFilter').val(); // Use dropdown or dynamic filter
                }
            },
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
                        console.log(data);
                        let checked = data == 'Active' ? 'checked' : '';
                        let titleText = data == 'Active' ? 'Active' : 'Inactive'; 
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
        $('#statusFilter').on('change', function() {
            $('#productTable').DataTable().ajax.reload();
        });
        $(window).resize(function() {
                if ($(window).width() <= 767) {
                    $(".table").addClass("table-responsive");
                } else {
                    $(".table").removeClass("table-responsive");
                }
            }).trigger('resize');
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
                    $('#password').removeAttr('required').val(""); 
                    $('#password').attr("placeholder", "*****");
                    $(".changepassword").show();
                    $(".showeye").hide();
                    $('#changePasswordCheckbox').prop("checked", false); // Uncheck password change checkbox
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
        $(document).ready(function () {
            $("#togglePassword").click(function () {
                let passwordField = $("#password");
                let icon = $(this).find("i");

                if (passwordField.attr("type") === "password") {
                    passwordField.attr("type", "text");
                    icon.removeClass("fa-eye").addClass("fa-eye-slash"); // Change icon
                } else {
                    passwordField.attr("type", "password");
                    icon.removeClass("fa-eye-slash").addClass("fa-eye"); // Change icon
                }
            });
            $("#changePasswordCheckbox").change(function () {
                if ($(this).is(":checked")) {
                    $("#password").prop("disabled", false).attr("required", true);
                    $('#password').attr("placeholder", "Enter new password");
                } else {
                    $("#password").prop("disabled", true).removeAttr("required").val(""); // Reset password field
                    $('#password').attr("placeholder", "*****");
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
            $('#password').val("");
            $('#address').val("");
            $('#dob').val("");
            $('#blood_group').val("");
            $('#photo').val("");
            $(".showeye").show();
            $('#submitButton').text("Add Employee");
            $('#registration_no').val("");
            $('#password').attr('required', true).val("");
            $('.changepassword').hide(); 
            $('#password').attr("placeholder", "Enter password");
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
                            $('#password').val("");
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
