@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">{{ __('Manage Resource Roles') }} - {{ $item->name }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('items.roles.update', $item) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('User') }}</th>
                            @foreach($roles as $role)
                                <th>{{ $role->name }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                @foreach($roles as $role)
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox" 
                                                class="form-check-input"
                                                name="roles[{{ $user->id }}][]"
                                                value="{{ $role->id }}"
                                                @checked($user->itemRoles->contains(function($itemRole) use ($role) {
                                                    return $itemRole->pivot->role_id == $role->id;
                                                }))
                                            >
                                        </div>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">
                    {{ __('Save Changes') }}
                </button>
                <a href="{{ route('items.index') }}" class="btn btn-secondary">
                    {{ __('Cancel') }}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
