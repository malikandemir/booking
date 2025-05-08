@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h4 class="mb-0 text-primary">{{ __('Create New Resource') }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('resources.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="company_id" value="{{ auth()->user()->company_id }}">

                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('Name') }}</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="icon" class="form-label">{{ __('Resource Icon') }}</label>
                            <select class="form-select @error('icon') is-invalid @enderror" id="icon" name="icon" required>
                                <option value="">{{ __('Select Icon') }}</option>
                                <option value="fas fa-door-open" {{ old('icon') == 'fas fa-door-open' ? 'selected' : '' }}>
                                    <i class="fas fa-door-open"></i> {{ __('Meeting Room') }}
                                </option>
                                <option value="fas fa-car" {{ old('icon') == 'fas fa-car' ? 'selected' : '' }}>
                                    <i class="fas fa-car"></i> {{ __('Vehicle') }}
                                </option>
                                <option value="fas fa-tools" {{ old('icon') == 'fas fa-tools' ? 'selected' : '' }}>
                                    <i class="fas fa-tools"></i> {{ __('Equipment') }}
                                </option>
                                <option value="fas fa-building" {{ old('icon') == 'fas fa-building' ? 'selected' : '' }}>
                                    <i class="fas fa-building"></i> {{ __('Office') }}
                                </option>
                                <option value="fas fa-flask" {{ old('icon') == 'fas fa-flask' ? 'selected' : '' }}>
                                    <i class="fas fa-flask"></i> {{ __('Lab') }}
                                </option>
                                <option value="fas fa-video" {{ old('icon') == 'fas fa-video' ? 'selected' : '' }}>
                                    <i class="fas fa-video"></i> {{ __('Studio') }}
                                </option>
                                <option value="fas fa-desktop" {{ old('icon') == 'fas fa-desktop' ? 'selected' : '' }}>
                                    <i class="fas fa-desktop"></i> {{ __('Workspace') }}
                                </option>
                                <option value="fas fa-cube" {{ old('icon') == 'fas fa-cube' ? 'selected' : '' }}>
                                    <i class="fas fa-cube"></i> {{ __('Other') }}
                                </option>
                            </select>
                            @error('icon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">{{ __('Type') }}</label>
                            <input type="text" class="form-control @error('type') is-invalid @enderror" id="type" name="type" value="{{ old('type') }}" required>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">{{ __('Description') }}</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input @error('status') is-invalid @enderror" type="checkbox" id="status" name="status" value="1" {{ old('status') ? 'checked' : '' }}>
                                <label class="form-check-label" for="status">
                                    {{ __('Active') }}
                                </label>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Create Resource') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
