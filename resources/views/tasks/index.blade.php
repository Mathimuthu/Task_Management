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
@stop

@section('css')
    {{-- Add custom stylesheets --}}
    <link rel="stylesheet" href="{{ asset('css/datatables.min.css') }}">
@stop

@section('js')
    <script src="{{ asset('js/datatables.min.js') }}"></script>
    <script>
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
    </script>
@stop
