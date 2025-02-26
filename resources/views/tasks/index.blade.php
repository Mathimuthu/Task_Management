@extends('adminlte::page')

@section('title', 'Task Management')

@section('content')
    <div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Task List</h2>
            <button onclick="showAddTaskModal()" class="bg-purple btn">Add Task</button>
        </div>
        <table id="taskTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
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
                        "render": function(data) {
                            return data == "Completed" ?
                                '<span class="text-success">Completed</span>' :
                                '<span class="text-warning">' + data + '</span>';
                        }
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
                            // If the file is a PDF, show it in an iframe
                            $('#filePreview').html('<iframe src="{{ asset('') }}' + data.upload_task + '" width="100%" height="400px"></iframe>');
                            $('#filePreview').show();
                        } else if (fileExtension === 'docx') {
                            // If the file is DOCX, show a download link or convert it
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
            $('#employee_ids').val("").change();
            $('#submitButton').text("Add Task");
        }

        $(document).on('click', '.updateStatusBtn', function() {
            let taskId = $(this).data("task-id");
            let url = $(this).data("url");
            let currentStatus = $(this).data("current-status");
            // Set values in the modal form
            $("#s_task_id").val(taskId);
            $("#u_status").val(currentStatus).change();
            $("#updateTaskForm").attr("action", url);
            $('#statusUpdateModal').modal('show');
        });

        function submitTaskForm() {
            let isSubmitting = false;

            $('#addTaskForm').submit(function(e) {
                e.preventDefault();
                console.log('Form submitted once');
                if (isSubmitting) return; 
                isSubmitting = true;
                let submitButton = $('#submitButton');
                submitButton.prop('disabled', true);
                submitButton.html('<i class="fa fa-spinner fa-spin"></i> Saving...');
                var formData = new FormData(this); 
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

            $('#taskTimeline').html('<li>Loading...</li>'); // Show loading message

            $.ajax({
                url: url,
                type: "GET",
                success: function(response) {
                    if (response.success) {
                        $('#taskTimeline').html(response.html); // Insert generated HTML
                    } else {
                        $('#taskTimeline').html('<li>No status updates available.</li>');
                    }
                },
                error: function() {
                    $('#taskTimeline').html('<li>Error fetching timeline.</li>');
                }
            });

            $('#taskTimelineModal').modal('show'); // Open modal
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
