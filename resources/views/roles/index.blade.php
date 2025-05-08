@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Role Management</h1>
    <a href="{{ route('roles.create') }}" class="btn btn-primary mb-3">Add New Role</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Slug</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($roles as $role)
            <tr>
                <td>{{ $role->id }}</td>
                <td>{{ $role->name }}</td>
                <td>{{ $role->slug }}</td>
                <td>
                    <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('roles.destroy', $role) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
