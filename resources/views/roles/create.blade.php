@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Create Role</h2>
        <form action="{{ route('roles.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Role Name:</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Permissions:</label>
                @foreach ($permissions as $permission)
                    <div>
                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}">
                        {{ $permission->name }}
                    </div>
                @endforeach
            </div>

            <button type="submit" class="btn btn-success">Create</button>
        </form>
    </div>
@endsection
