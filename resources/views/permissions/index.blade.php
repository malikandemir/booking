@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Permission Management</h1>
    <a href="{{ route('permissions.create') }}" class="btn btn-primary mb-3">Add New Permission</a>
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
        @foreach($permissions as $permission)
            <tr>
                <td>{{ $permission->id }}</td>
                <td>{{ $permission->name }}</td>
                <td>{{ $permission->slug }}</td>
                <td>
                    <a href="{{ route('permissions.edit', $permission) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('permissions.destroy', $permission) }}" method="POST" style="display:inline-block;">
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
