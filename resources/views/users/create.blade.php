@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">{{ __('Create New User') }}</h4>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('users.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('Name') }}</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Email') }}</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('Password') }}</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if(auth()->user()->isSuperAdmin())
                            <div class="mb-3">
                                <label for="company_id" class="form-label">{{ __('Company') }}</label>
                                <select class="form-select @error('company_id') is-invalid @enderror" 
                                    id="company_id" name="company_id" required>
                                    <option value="">{{ __('Select Company') }}</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                            {{ $company->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('company_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="is_admin" name="is_admin" value="1"
                                    {{ old('is_admin') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_admin">{{ __('Is Admin') }}</label>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="role" class="form-label">{{ __('Role') }}</label>
                            <select class="form-select @error('is_admin') is-invalid @enderror" 
                                id="role" name="is_admin" required>
                                <option value="0" {{ old('is_admin') == '0' ? 'selected' : '' }}>{{ __('Normal User') }}</option>
                                @if(auth()->user()->isAdmin())
                                    <option value="1" {{ old('is_admin') == '1' ? 'selected' : '' }}>{{ __('Admin') }}</option>
                                @endif
                            </select>
                            @error('is_admin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('users.index') }}" class="btn btn-light">{{ __('Cancel') }}</a>
                            <button type="submit" class="btn btn-primary">{{ __('Create User') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
