@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 text-primary">{{ __('Resource List') }}</h4>
                        <a href="{{ route('items.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus-circle me-1"></i>{{ __('Create New Item') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Description') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($item->icon)
                                                    <i class="{{ $item->icon }} fa-lg text-primary me-2"></i>
                                                @else
                                                    <i class="fas fa-cube fa-lg text-primary me-2"></i>
                                                @endif
                                                <span>{{ $item->name }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $item->type }}</td>
                                        <td>
                                            <span class="text-truncate d-inline-block" style="max-width: 300px;">
                                                {{ $item->description }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill bg-{{ $item->status === 'active' ? 'success' : 'danger' }}">
                                                {{ __($item->status === 'active' ? 'Active' : 'Inactive') }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('items.edit', $item) }}" class="btn btn-sm btn-primary me-2">
                                                    <i class="fas fa-edit me-1"></i>{{ __('Edit') }}
                                                </a>
                                                <a href="{{ route('items.roles', $item) }}" class="btn btn-sm btn-info me-2">
                                                    <i class="fas fa-user-tag me-1"></i>{{ __('Manage Roles') }}
                                                </a>
                                                <form action="{{ route('items.destroy', $item) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('{{ __('Are you sure?') }}')">
                                                        <i class="fas fa-trash-alt me-1"></i>{{ __('Delete') }}
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-inbox fa-2x mb-3"></i>
                                                <p class="mb-0">{{ __('No records found') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($items instanceof \Illuminate\Pagination\LengthAwarePaginator && $items->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $items->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.table > :not(caption) > * > * {
    padding: 1rem 0.75rem;
}

.badge {
    font-weight: 500;
}

.btn-sm {
    padding: 0.25rem 0.75rem;
}

.shadow-sm {
    box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important;
}
</style>

@endsection
