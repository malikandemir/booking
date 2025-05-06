@php
    use Illuminate\Support\Facades\App;
@endphp

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="row align-items-center">
                        <div class="col-12 col-md-6 mb-2 mb-md-0">
                            <h4 class="mb-0 text-primary">{{ __('Bookings') }}</h4>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="d-flex flex-wrap gap-2 justify-content-start justify-content-md-end">
                                @if(auth()->user()->isSuperAdmin())
                                    <a href="{{ route('companies.index') }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-building me-1"></i>{{ __('Companies') }}
                                    </a>
                                @endif
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('items.index') }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-box me-1"></i>{{ __('Items') }}
                                    </a>
                                    <a href="{{ route('users.index') }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-users me-1"></i>{{ __('Users') }}
                                    </a>
                                @endif
                                <a href="{{ route('bookings.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus-circle me-1"></i>{{ __('New Booking') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0 p-md-3">
                    <!-- Calendar -->
                    <div id="calendar" class="mb-4"></div>

                    <!-- Bookings List -->
                    <div class="px-3">
                        <h5 class="border-bottom pb-2">{{ __('All Bookings') }}</h5>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('User') }}</th>
                                        <th>{{ __('Item') }}</th>
                                        <th class="d-none d-md-table-cell">{{ __('Start Time') }}</th>
                                        <th class="d-none d-md-table-cell">{{ __('End Time') }}</th>
                                        <th class="d-none d-md-table-cell">{{ __('Description') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookings as $booking)
                                        <tr>
                                            <td>{{ $booking->user->name }}</td>
                                            <td>{{ $booking->item->name }}</td>
                                            <td class="d-none d-md-table-cell">{{ $booking->start_time->format('Y-m-d H:i') }}</td>
                                            <td class="d-none d-md-table-cell">{{ $booking->end_time->format('Y-m-d H:i') }}</td>
                                            <td class="d-none d-md-table-cell">{{ $booking->description }}</td>
                                            <td>
                                                <span class="badge bg-{{ $booking->status === 'approved' ? 'success' : ($booking->status === 'rejected' ? 'danger' : 'warning') }}">
                                                    {{ __(ucfirst($booking->status)) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#bookingModal{{ $booking->id }}">
                                                                <i class="fas fa-eye me-1"></i>{{ __('View Details') }}
                                                            </button>
                                                        </li>
                                                        @if($booking->can_edit)
                                                            <li>
                                                                <a href="{{ route('bookings.edit', $booking) }}" class="dropdown-item">
                                                                    <i class="fas fa-edit me-1"></i>{{ __('Edit') }}
                                                                </a>
                                                            </li>
                                                        @endif
                                                        @if($booking->can_cancel)
                                                            <li>
                                                                <form action="{{ route('bookings.cancel', $booking) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <button type="submit" class="dropdown-item text-danger" onclick="return confirm('{{ __('Are you sure you want to cancel this booking?') }}')">
                                                                        <i class="fas fa-times-circle me-1"></i>{{ __('Cancel') }}
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        @endif
                                                        @if($booking->status === 'pending')
                                                            @if(auth()->user()->canApprove($booking->item))
                                                                <li>
                                                                    <form action="{{ route('bookings.approve', $booking) }}" method="POST" class="d-inline">
                                                                        @csrf
                                                                        <input type="hidden" name="_method" value="PUT">
                                                                        <button type="submit" class="dropdown-item text-success">
                                                                            <i class="fas fa-check me-1"></i>{{ __('Approve') }}
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                                <li>
                                                                    <form action="{{ route('bookings.reject', $booking) }}" method="POST" class="d-inline">
                                                                        @csrf
                                                                        <input type="hidden" name="_method" value="PUT">
                                                                        <button type="submit" class="dropdown-item text-danger" onclick="return confirm('{{ __('Are you sure you want to reject this booking?') }}')">
                                                                            <i class="fas fa-times me-1"></i>{{ __('Reject') }}
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            @endif
                                                        @endif
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Booking Details Modal -->
                                        <div class="modal fade" id="bookingModal{{ $booking->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">{{ __('Booking Details') }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <dl class="row mb-0">
                                                            <dt class="col-4">{{ __('User') }}</dt>
                                                            <dd class="col-8">{{ $booking->user->name }}</dd>
                                                            
                                                            <dt class="col-4">{{ __('Item') }}</dt>
                                                            <dd class="col-8">{{ $booking->item->name }}</dd>
                                                            
                                                            <dt class="col-4">{{ __('Start Time') }}</dt>
                                                            <dd class="col-8">{{ $booking->start_time->format('Y-m-d H:i') }}</dd>
                                                            
                                                            <dt class="col-4">{{ __('End Time') }}</dt>
                                                            <dd class="col-8">{{ $booking->end_time->format('Y-m-d H:i') }}</dd>
                                                            
                                                            <dt class="col-4">{{ __('Description') }}</dt>
                                                            <dd class="col-8">{{ $booking->description }}</dd>
                                                            
                                                            <dt class="col-4">{{ __('Status') }}</dt>
                                                            <dd class="col-8">
                                                                <span class="badge bg-{{ $booking->status === 'approved' ? 'success' : ($booking->status === 'rejected' ? 'danger' : 'warning') }}">
                                                                    {{ __(ucfirst($booking->status)) }}
                                                                </span>
                                                            </dd>
                                                        </dl>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: '{{ App::getLocale() }}',
        locales: [{
            code: 'tr',
            week: {
                dow: 1, // Monday is the first day of the week
                doy: 7  // The week that contains Jan 1st is the first week of the year
            },
            buttonText: {
                prev: '{{ __("Previous") }}',
                next: '{{ __("Next") }}',
                today: '{{ __("Today") }}',
                month: '{{ __("Month") }}',
                week: '{{ __("Week") }}',
                day: '{{ __("Day") }}',
                list: '{{ __("List") }}'
            },
            weekText: '{{ __("Week") }}',
            allDayText: '{{ __("All Day") }}',
            moreLinkText: function(n) {
                return '+ ' + n + ' {{ __("more") }}';
            },
            noEventsText: '{{ __("No Bookings") }}',
            dayHeaderFormat: {
                weekday: 'short',
                day: 'numeric',
                month: 'numeric'
            }
        }],
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: @json($events),
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        }
    });
    calendar.render();
});
</script>
@endpush

@push('styles')
<style>
/* Calendar Styles */
.fc {
    max-width: 100%;
    background: white;
}

.fc .fc-toolbar.fc-header-toolbar {
    margin-bottom: 1.5em;
    padding: 1rem;
}

.fc .fc-toolbar-title {
    font-size: 1.25rem;
    margin: 0;
}

.fc .fc-button {
    background-color: #f8f9fa;
    border-color: #ddd;
    color: #444;
}

.fc .fc-button:hover {
    background-color: #e9ecef;
    border-color: #ddd;
    color: #444;
}

.fc .fc-button-primary {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: white;
}

.fc .fc-button-primary:hover {
    background-color: #0b5ed7;
    border-color: #0a58ca;
}

.fc-event {
    cursor: pointer;
    padding: 2px 4px;
}

.fc-event:hover {
    opacity: 0.9;
}

/* Responsive Calendar */
@media (max-width: 768px) {
    .fc .fc-toolbar {
        flex-direction: column;
        gap: 1rem;
    }
    
    .fc .fc-toolbar-title {
        font-size: 1.1rem;
        text-align: center;
    }
    
    .fc .fc-button {
        padding: 0.4em 0.65em;
        font-size: 0.9em;
    }
}

/* Modal Styles */
.modal-content {
    border-radius: 0.5rem;
    border: none;
}

.modal-header {
    background-color: #fff;
}

.modal-body {
    background-color: #fff;
}

.modal-footer {
    background-color: #fff;
}

/* Table Styles */
.table > :not(caption) > * > * {
    padding: 1rem 0.75rem;
}

.badge {
    font-weight: 500;
}

/* Custom Shadows */
.shadow-sm {
    box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important;
}

/* Button Styles */
.btn {
    padding: 0.5rem 1rem;
    font-weight: 500;
}

.btn-sm {
    padding: 0.25rem 0.75rem;
}
</style>
@endpush

@endsection
