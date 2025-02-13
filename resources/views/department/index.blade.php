@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content')
    <div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Department List</h2>
            @if ($hasCreatepermissions)
                <a href="{{ route('department.create') }}" data-toggle="modal" data-target="#modalPurple"
                    class="bg-purple btn">Add Department</a>
            @endif
        </div>
        <table id="productTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Manager</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>

    @include('department.edit')

    {{-- Themed --}}
    <x-adminlte-modal id="modalPurple" title="Add Department" theme="purple" icon="fas fa-tag" size='md'>

        <form id="addDepartmentForm" name="yes" method="POST">
            <!-- CSRF Token -->
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <!-- Product Name Input -->
            <div class="form-group">
                <label for="product_name">Name</label>
                <input
                    value="@php
if (isset($department)) {
                        echo $department->name;
                    } @endphp"
                    type="text" id="name" name="name" class="form-control" placeholder="enter name" required>
                <input
                    value="@php
if (isset($department)) {
                        echo $department->id;
                    } @endphp"
                    type="hidden" id="id" name="id">

                <!-- Employee Dropdown -->
                <label class="mt-2">Assign Employee:</label>
                <select id="employee_id" name="employee_id" class="form-control" required>
                    <option value="">Select Employee</option>
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->name }} - {{ $employee->role }}</option>
                    @endforeach
                </select>

                <!-- Description -->
                <label class="mt-2">Description:</label>
                <textarea id="description" name="description" class="form-control" rows="3"
                    placeholder="Enter department description"></textarea>

            </div>

            <!-- Submit Button -->
            <div class="form-group">
                <button type="submit" onclick="submitDepartmentForm()" class="btn btn-success">
                    <i class="fas fa-save"></i> Save
                </button>
            </div>
        </form>
        <x-slot name="footerSlot" :null="true">

            <!-- This will effectively remove the footer section  -->

        </x-slot>
    </x-adminlte-modal>
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
                ajax: "{{ route('department.index') }}",
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
                        data: 'manager_name',
                        name: 'manager_name'
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

        $(document).on('click', '.edit-btn', function() {
            let id = $(this).data('id');
            let url = $(this).data('url');

            $.ajax({
                url: url,
                type: "GET",
                success: function(data) {
                    $('#editModal').modal('show'); // Show modal
                    $('#department_id').val(data.id); // Populate form fields
                    $('#department_name').val(data.name);
                    $('#productTable').DataTable().ajax.reload(); // Refresh DataTable
                },
                error: function() {
                    alert("Error fetching data.");
                }
            });
        });

        $(document).on('click', '.delete-btn', function() {
            let url = $(this).data('url');
            let id = $(this).data('id');

            if (confirm("Are you sure you want to delete this department?")) {
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}", // Include CSRF token for Laravel
                    },
                    success: function(response) {
                        $('#productTable').DataTable().ajax.reload(); // Refresh DataTable
                    },
                    error: function() {
                        alert("Error processing request.");
                    }
                });
            }
        });



        function submitProductForm() {
            $('#addCustomerForm').submit(function(e) {
                $.ajax({
                    url: "{{ route('department.store') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#modalPurple').modal('hide'); // Hide modal
                        $('#productTable').DataTable().ajax.reload(); // Refresh DataTable
                        alert('Department added successfully!');
                    },
                    error: function(xhr) {
                        alert('Error adding product.');
                    }
                });
            });
        }
    </script>
@stop
