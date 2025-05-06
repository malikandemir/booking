@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h4 class="mb-0 text-primary">{{ __('Create New Booking') }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('bookings.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="item_id" class="form-label">{{ __('Resource') }}</label>
                            <select name="item_id" id="item_id" class="form-select @error('item_id') is-invalid @enderror" required>
                                <option value="">{{ __('Select a resource') }}</option>
                                @foreach($items as $item)
                                        <option value="{{ $item->id }}" @selected(old('item_id') == $item->id)>
                                            {{ $item->name }}
                                        </option>
                                @endforeach
                            </select>
                            @error('item_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="bookings-list" class="mb-4" style="display: none;">
                            <h5 class="text-muted mb-3">{{ __('Existing Bookings') }}</h5>
                            <div class="list-group">
                                <!-- Bookings will be loaded here -->
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="start_time" class="form-label">{{ __('Start Time') }}</label>
                            <input type="datetime-local" class="form-control @error('start_time') is-invalid @enderror" 
                                   id="start_time" name="start_time" value="{{ old('start_time') }}" required>
                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="end_time" class="form-label">{{ __('End Time') }}</label>
                            <input type="datetime-local" class="form-control @error('end_time') is-invalid @enderror" 
                                   id="end_time" name="end_time" value="{{ old('end_time') }}" required>
                            @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">{{ __('Description') }}</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="purpose" class="form-label">{{ __('Purpose') }}</label>
                            <textarea class="form-control @error('purpose') is-invalid @enderror" id="purpose" 
                                      name="purpose" rows="3" required>{{ old('purpose') }}</textarea>
                            @error('purpose')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('bookings.index') }}" class="btn btn-light">{{ __('Cancel') }}</a>
                            <button type="submit" class="btn btn-primary">{{ __('Create Booking') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var startTimeInput = document.getElementById('start_time');
    var endTimeInput = document.getElementById('end_time');
    var itemSelect = document.getElementById('item_id');
    var bookingsList = document.getElementById('bookings-list');

    function loadBookings(itemId) {
        if (!itemId) {
            bookingsList.style.display = 'none';
            return;
        }

        fetch(`/bookings/item/${itemId}/bookings`)
            .then(response => response.json())
            .then(bookings => {
                const listGroup = bookingsList.querySelector('.list-group');
                listGroup.innerHTML = '';

                if (bookings.length === 0) {
                    listGroup.innerHTML = '<div class="list-group-item text-muted">No bookings found</div>';
                } else {
                    bookings.forEach(booking => {
                        const statusClass = booking.status === 'approved' ? 'text-success' : 
                                        (booking.status === 'rejected' ? 'text-danger' : 'text-warning');
                        
                        const item = document.createElement('div');
                        item.className = 'list-group-item';
                        item.innerHTML = `
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">${booking.user}</h6>
                                    <p class="mb-1 text-muted small">${booking.purpose}</p>
                                </div>
                                <div class="text-end">
                                    <div class="small ${statusClass} text-capitalize">${booking.status}</div>
                                    <div class="small text-muted">
                                        ${booking.start} - ${booking.end}
                                    </div>
                                </div>
                            </div>
                        `;
                        listGroup.appendChild(item);
                    });
                }
                bookingsList.style.display = 'block';
            })
            .catch(error => {
                console.error('Error loading bookings:', error);
                bookingsList.style.display = 'none';
            });
    }

    itemSelect.addEventListener('change', function() {
        loadBookings(this.value);
    });

    // Load bookings for initial selection
    if (itemSelect.value) {
        loadBookings(itemSelect.value);
    }

    startTimeInput.addEventListener('change', function() {
        var startTime = new Date(this.value);
        var endTime = new Date(startTime);
        endTime.setHours(startTime.getHours() + 1);
        endTimeInput.value = endTime.toISOString().slice(0, 16);
    });

    document.querySelector('form').addEventListener('submit', function(e) {
        var startTime = new Date(startTimeInput.value);
        var endTime = new Date(endTimeInput.value);

        if (endTime <= startTime) {
            e.preventDefault();
            alert('{{ __("End time must be after start time") }}');
            return;
        }

        if (startTime.getHours() < 9 || endTime.getHours() > 17 || 
            (endTime.getHours() === 17 && endTime.getMinutes() > 0)) {
            e.preventDefault();
            alert('{{ __("Bookings must be between 9 AM and 5 PM") }}');
            return;
        }
    });
});
</script>
@endpush
@endsection
