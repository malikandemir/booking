@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>{{ __('User Management') }}</h2>
        <a href="{{ route('users.create') }}" class="btn btn-primary">{{ __('Create New User') }}</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ __(session('success')) }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Email') }}</th>
                    <th>{{ __('Role') }}</th>
                    <th>{{ __('Created At') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->is_admin ? __('Admin') : __('User') }}</td>
                        <td>{{ $user->created_at ? $user->created_at->format('d.m.Y H:i') : 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
