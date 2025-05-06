@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 text-primary">{{ __('Companies') }}</h4>
                    <a href="{{ route('companies.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-1"></i>{{ __('Create New Company') }}
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="py-3">{{ __('Name') }}</th>
                                    <th class="py-3">{{ __('Description') }}</th>
                                    <th class="py-3">{{ __('Status') }}</th>
                                    <th class="py-3">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($companies as $company)
                                <tr>
                                    <td class="py-3">{{ $company->name }}</td>
                                    <td class="py-3">
                                        <div class="text-wrap" style="max-width: 300px;">
                                            {{ $company->description }}
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <span class="badge rounded-pill bg-{{ $company->status ? 'success' : 'danger' }} px-3 py-2">
                                            {{ $company->status ? __('Active') : __('Inactive') }}
                                        </span>
                                    </td>
                                    <td class="py-3">
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('companies.edit', $company) }}" class="btn btn-sm btn-info text-white">
                                                <i class="fas fa-edit me-1"></i>{{ __('Edit') }}
                                            </a>
                                            <form action="{{ route('companies.destroy', $company) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('{{ __('Are you sure you want to delete this company?') }}')">
                                                    <i class="fas fa-trash me-1"></i>{{ __('Delete') }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
