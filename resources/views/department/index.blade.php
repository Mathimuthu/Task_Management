@extends('adminlte::page')

@section('title', 'Departments')
<style>
.select2-container--default .select2-selection--multiple .select2-selection__choice {
    color: black !important;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #f0f0f0 !important; 
}
</style>
@section('content')
    <div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Department List</h2>
            @if ($hasCreatepermissions)
            <button onclick="showAddDepartmentModal()" class="bg-purple btn">Add Department</button>
            @endif
        </div>
        <table id="departmentTable" class="table table-bordered">
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
    @include('department.create')
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script src="{{ asset('js/datatables.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/datatables.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#modal_Purple').on('shown.bs.modal', function() {
                $('#employee_id').select2();
            });
            $('#departmentTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('department.index') }}",
                columns: [
                    { data: "id", "render": function(data, type, row, meta) { return meta.row + 1; } },
                    { data: 'name', name: 'name' },
                    { data: 'manager_name', name: 'manager_name' }, 
                    { data: "status", "render": function(data, type, row) { return data == 1 ? 'Active' : 'Inactive'; } },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });

        $(document).on('click', '.edit-btn', function() {
            let url = $(this).data('url');
            $.ajax({
                url: url,
                type: "GET",
                success: function(data) {
                    $('#department_id').val(data.id); 
                    $('#name').val(data.name);
                    let managerIds = JSON.parse(data.manager_id); 
                    $('#employee_id').val(managerIds).trigger('change'); 
                    $('#employee_id').select2();
                    $('#description').val(data.description);
                    $('#modal_Purple .modal-title').text('Edit Department'); 
                    $('#modal_Purple').modal('show');  
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
                        _token: "{{ csrf_token() }}",
                        _method:"DELETE",
                        id:id
                    },
                    success: function(response) {
                        $('#departmentTable').DataTable().ajax.reload(); 
                    },
                    error: function() {
                        alert("Error processing request.");
                    }
                });
            }
        });
        function showAddDepartmentModal() {
            $('#modal_Purple').modal('show'); 
            $('#department_id').val("");
            $('#name').val("");
            $('#employee_id').val("");
            $('#employee_id').val(null).trigger('change');
            $('#modal_Purple .modal-title').text('Add Department'); 
        }
        function submitDepartmentForm() {
            let isSubmitting = false;
            $('#addDepartmentForm').submit(function(e) {
                e.preventDefault();
                if (isSubmitting) return; 
                isSubmitting = true;
                let submitButton = $('#submitDep');
                submitButton.prop('disabled', true);
                submitButton.html('<i class="fa fa-spinner fa-spin"></i> Saving...');

                $.ajax({
                    url: "{{ route('department.store') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            submitButton.prop('disabled', false);
                            submitButton.html('Save Department');
                            alert(response.msg);

                            // Clear form fields
                            $('#department_id').val(""); 
                            $('#name').val("");
                            $('#employee_id').val(""); 
                            $('#description').val(""); 

                            // Hide the modal
                            $('#modal_Purple').modal('hide');

                            // Reload DataTable
                            $('#departmentTable').DataTable().ajax.reload();

                            // Redirect after successful submission
                            window.location.href = response.redirect_url;  // Redirect to department index page
                        } else {
                            submitButton.prop('disabled', false);
                            submitButton.html('Save Department');
                            alert(response.msg);
                        }
                    },
                    error: function() {
                        submitButton.prop('disabled', false);
                        submitButton.html('Save Department');
                        alert('Error adding department.');
                    }
                });
            });
        }
    </script>
@stop
