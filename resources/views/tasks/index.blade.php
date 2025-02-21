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
                    <th>Assigned Employees</th>
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
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
@stop

@section('js')
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script src="{{ asset('js/datatables.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            // Initialize Select2
            $('.js-example-basic-multiple').select2();

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
                        data: 'employee_names',
                        name: 'employee_names'
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
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        alert(response.success);
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
            $('#addTaskForm').submit(function(e) {
                e.preventDefault();

                let submitButton = $('#submitButton');
                submitButton.prop('disabled', true);
                submitButton.html('<i class="fa fa-spinner fa-spin"></i> Saving...');

                $.ajax({
                    url: "{{ route('tasks.store') }}",
                    type: "POST",
                    data: $(this).serialize(),
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
                        alert('Error adding task.');
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
                    type: "PUT",
                    data: $(this).serialize(),
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
                    }
                });
            });
        }
    </script>
@stop
