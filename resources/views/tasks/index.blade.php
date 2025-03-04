@extends('adminlte::page')

@section('title', 'Task Management')

@section('content')
<style>
    /* Prevent icons from getting too big on smaller screens */
@media (max-width: 767px) {
    .fa, .btn i { /* Target Font Awesome icons and icons inside buttons */
        font-size: 14px; /* Set a smaller font size for icons */
    }

    /* Optional: Further size adjustments for mobile view */
    .d-flex {
        flex-direction: column;
        align-items: stretch;
    }

    /* Smaller button icons */
    .btn i {
        font-size: 16px;
        margin-right: 5px; /* Keep a small margin */
    }
}

/* Adjust for very small screens (portrait mode on mobile) */
@media (max-width: 480px) {
    .fa, .btn i {
        font-size: 12px; /* Even smaller icons on very small screens */
    }
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
/* Ensure selected option is always visible in mobile view */
@media (max-width: 767px) {
    select.form-control {
        -webkit-appearance: none; /* Hide default styling */
        -moz-appearance: none;
        appearance: none;
        background-color: white;
        padding-right: 20px; /* Space for dropdown arrow */
    }

    .status-dropdown {
        min-width: 100px; /* Prevent shrinking too much */
        text-align: center; /* Center text */
    }
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
            <h2>Task List</h2>
            <button onclick="showAddTaskModal()" class="bg-purple btn">Add Task</button>
        </div>
        <table id="taskTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Title</th>
                    <th>Priority</th>
                    <th>Assign Date</th>
                    <th>Deadline</th>
                    <th>Department</th>
                    <th>Assigned Employee</th>
                    <th>Updated by</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    {{-- Themed Modal for Task --}}
    @include('tasks.create')
    @include('tasks.statusUpdate')
    @include('tasks.timeline')

@stop

@section('css')
    {{-- Add custom stylesheets --}}
    <link rel="stylesheet" href="{{ asset('css/datatables.min.css') }}">
@stop

@section('js')
    <script src="{{ asset('js/datatables.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#employee_ids').on('change', function() {
                var employeeId = $(this).val(); 
                if (employeeId) {
                    $.ajax({
                        url: "{{ route('getUserDepartment') }}",  
                        type: "GET",
                        data: { employee_id: employeeId }, 
                        success: function(response) {
                            if (response.success) {
                                $('#department_ids').val(response.department_id).change();
                                $('#department_id').val(response.department_id);
                            }
                        },
                        error: function() {
                            alert('Error fetching user department');
                        }
                    });
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
        $(document).ready(function() {
            $('#taskTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('tasks.index') }}",
                columns: [{
                        data: "id",
                        "render": function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'priority',
                        name: 'priority'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'deadline',
                        name: 'deadline'
                    },
                    {
                        data: 'departmentname',
                        name: 'departmentname'
                    },
                    {
                        data: 'username',
                        name: 'username'
                    },
                    {
                        data: 'updatedby',
                        name: 'updatedby'
                    },
                    {
                        data: "status",
                        name: "status",
                        orderable: false,
                        searchable: true,
                        render: function(data, type, row) {
                            let statusOptions = ["Pending", "In Progress", "Completed", "Cancelled"];
                            let selectedStatus = data || "Pending"; // Default to Pending if data is null

                            let optionsHtml = statusOptions.map(status => 
                                `<option value="${status}" ${selectedStatus === status ? "selected" : ""}>${status}</option>`
                            ).join("");

                            return `
                                <select class="form-control form-control-sm status-dropdown" data-id="${row.id}">
                                    ${optionsHtml}
                                </select>
                            `;
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [[0, 'desc']],  
                language: {
                    lengthMenu: 'Show &nbsp;_MENU_ &nbsp;&nbsp;Entries Per Page',
                    info: 'Showing _START_ to _END_ of _TOTAL_ Entries' 
                }
            });
        });
        $(document).on("change", ".status-dropdown", function() {
            let taskId = $(this).data("id");
            let newStatus = $(this).val();
            
            Swal.fire({
                title: "Confirm Status Change",
                text: `Are you sure you want to change the status to "${newStatus}"?`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "No"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "/tasks/" + taskId,
                        type: "PUT",
                        data: {
                            status: newStatus,
                            _token: $('meta[name="csrf-token"]').attr('content') // Ensure CSRF token is included
                        },
                        success: function(response) {
                            Swal.fire({
                                title: "Success",
                                text: "Task status updated successfully!",
                                icon: "success"
                            });
                        },
                        error: function() {
                            Swal.fire({
                                title: "Error",
                                text: "Failed to update task status.",
                                icon: "error"
                            });
                        }
                    });
                } else {
                    swal.close();
                    $('#taskTable').DataTable().ajax.reload();
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
                    $('#modalTask').modal('show');
                    $('#task_id').val(data.id);
                    $('#title').val(data.title);
                    $('#priority').val(data.priority);
                    $('#assign_date').val(data.date);
                    $('#deadline').val(data.deadline);
                    $('#department_id').val(data.department_id).change();
                    $('#employee_ids').val(JSON.parse(data.employee_ids)).change();
                    $('#status').val(data.status);
                    if (data.upload_task) {
                        let fileExtension = data.upload_task.split('.').pop().toLowerCase();
                        if (fileExtension === 'pdf') {
                            $('#filePreview').html('<iframe src="{{ asset('') }}' + data.upload_task + '" width="100%" height="400px"></iframe>');
                            $('#filePreview').show();
                        } else if (fileExtension === 'docx') {
                            $('#filePreview').html('<a href="{{ asset('') }}' + data.upload_task + '" target="_blank">Download DOCX</a>');
                            $('#filePreview').show();
                        } else {
                            // Hide if the file type is unsupported
                            $('#filePreview').hide();
                        }
                    } else {
                        $('#filePreview').hide();
                    }
                    $('#submitButton').text("Update Task");
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

            if (confirm("Are you sure you want to delete this task?")) {
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        _method: "DELETE",
                        id:id
                    },
                    success: function(response) {
                        $('#taskTable').DataTable().ajax.reload();
                    },
                    error: function() {
                        alert("Error processing request.");
                    }
                });
            }
        });

        function showAddTaskModal() {
            $('#modalTask').modal('show');
            $('#task_id').val("");
            $('#title').val("");
            $('#priority').val("Medium");
            $('#assign_date').val("");
            $('#deadline').val("");
            $('#department_id').val("").change();
            $('#department_ids').val("").change();
            $('#employee_ids').val("").change();
            $('#employe').prop('disabled', true);
            $('#submitButton').text("Add Task");
        }

        // $(document).on('click', '.updateStatusBtn', function() {
        //     let taskId = $(this).data("task-id");
        //     let url = $(this).data("url");
        //     let description = $(this).data("description");
        //     let currentStatus = $(this).data("current-status");
        //     // Set values in the modal form
        //     $("#s_task_id").val(taskId);
        //     $("#description").val(description);
        //     $("#u_status").val(currentStatus).change();
        //     $("#updateTaskForm").attr("action", url);
        //     $('#statusUpdateModal').modal('show');
        // });

        function submitTaskForm() {
            let isSubmitting = false;

            $('#addTaskForm').submit(function(e) {
                e.preventDefault();

                // Ensure that the form is not being submitted more than once
                if (isSubmitting) return; 
                isSubmitting = true;

                // Disable the submit button and show loading state
                let submitButton = $('#submitButton');
                submitButton.prop('disabled', true);
                submitButton.html('<i class="fa fa-spinner fa-spin"></i> Saving...');

                // Get the file from the form
                var fileInput = $('#upload_task')[0]; // Assuming upload_task is the ID of the file input
                var file = fileInput.files[0];
                var maxSize = 40 * 1024 * 1024; // 40MB in bytes

                // Check if the file size exceeds the maximum size
                if (file && file.size > maxSize) {
                    submitButton.prop('disabled', false);
                    submitButton.html('Save Task');
                    alert('File is too large. Maximum allowed size is 40MB.');
                    isSubmitting = false;
                    return;
                }

                // Create FormData object to send the form data, including files
                var formData = new FormData(this); 

                // Send the form data via AJAX
                $.ajax({
                    url: "{{ route('tasks.store') }}",
                    type: "POST",
                    data: formData,
                    contentType: false, 
                    processData: false, 
                    success: function(response) {
                        if (response.success == 1) {
                            submitButton.prop('disabled', false);
                            submitButton.html('Save Task');
                            $('#modalTask').modal('hide');
                            $('#taskTable').DataTable().ajax.reload();
                        } else {
                            submitButton.prop('disabled', false);
                            submitButton.html('Save Task');
                            alert(response.msg);
                        }
                    },
                    error: function() {
                        submitButton.prop('disabled', false);
                        submitButton.html('Save Task');
                        alert('Error adding task.');
                    },
                    complete: function() {
                        isSubmitting = false;
                    }
                });
            });
        }

        $(document).on('click', '.viewTimelineBtn', function() {
            let taskId = $(this).data("task-id");
            let url = $(this).data("url");

            $('#taskTimeline').html('<li>Loading...</li>'); 

            $.ajax({
                url: url,
                type: "GET",
                success: function(response) {
                    if (response.success) {
                        $('#taskTimeline').html(response.html); 
                    } else {
                        $('#taskTimeline').html('<li>No status updates available.</li>');
                    }
                },
                error: function() {
                    $('#taskTimeline').html('<li>Error fetching timeline.</li>');
                }
            });

            $('#taskTimelineModal').modal('show'); 
        });


        function submitTaskStatusForm() {
            $('#updateTaskForm').submit(function(e) {
                e.preventDefault();
                let url = $(this).attr("action");
                let submitButton = $('#updateButton');
                submitButton.prop('disabled', true);
                submitButton.html('<i class="fa fa-spinner fa-spin"></i> Saving...');
                $.ajax({
                    url: url,
                    type: "POST",
                    data: $(this).serialize() + '&_method=PUT',  
                    success: function(response) {
                        if (response.success == 1) {
                            submitButton.prop('disabled', false);
                            submitButton.html('Save Task');
                            $('#statusUpdateModal').modal('hide');
                            $('#taskTable').DataTable().ajax.reload();
                        } else {
                            submitButton.prop('disabled', false);
                            submitButton.html('Save Task');
                            alert(response.msg);
                        }
                    },
                    error: function() {
                        submitButton.prop('disabled', false);
                        submitButton.html('Save Task');
                        alert('Error adding task.');
                    }
                });
            });
        }
        $(document).on('click', '.restore-btn', function() {
            let url = $(this).data('url');
            if (confirm("Are you sure you want to restore this task?")) {
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        $('#taskTable').DataTable().ajax.reload();
                    },
                    error: function() {
                        alert("Error processing request.");
                    }
                });
            }
        });
    </script>
@stop
