@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if($pendingBookings->isNotEmpty())
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('Pending Booking Requests') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ __('Resource') }}</th>
                                        <th>{{ __('User') }}</th>
                                        <th>{{ __('Start Time') }}</th>
                                        <th>{{ __('End Time') }}</th>
                                        <th>{{ __('Description') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingBookings as $booking)
                                        <tr>
                                            <td>{{ $booking->resource->name }}</td>
                                            <td>{{ $booking->user->name }}</td>
                                            <td>{{ $booking->start_time->format('Y-m-d H:i') }}</td>
                                            <td>{{ $booking->end_time->format('Y-m-d H:i') }}</td>
                                            <td>{{ $booking->description }}</td>
                                            <td>
                                                <form action="{{ route('bookings.approve', $booking) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="_method" value="PUT">
                                                    <button type="submit" class="btn btn-success btn-sm">{{ __('Approve') }}</button>
                                                </form>
                                                <form action="{{ route('bookings.reject', $booking) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="_method" value="PUT">
                                                    <button type="submit" class="btn btn-danger btn-sm">{{ __('Reject') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Your Recent Bookings') }}</h5>
                </div>
                <div class="card-body">
                    @if($userBookings->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ __('Resource') }}</th>
                                        <th>{{ __('Start Time') }}</th>
                                        <th>{{ __('End Time') }}</th>
                                        <th>{{ __('Status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($userBookings as $booking)
                                        <tr>
                                            <td>{{ $booking->resource->name }}</td>
                                            <td>{{ $booking->start_time->format('Y-m-d H:i') }}</td>
                                            <td>{{ $booking->end_time->format('Y-m-d H:i') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $booking->status === 'approved' ? 'success' : ($booking->status === 'rejected' ? 'danger' : 'warning') }}">
                                                    {{ __(ucfirst($booking->status)) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">{{ __('You have no recent bookings.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
