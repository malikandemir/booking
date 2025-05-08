<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Resource;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = Booking::with(['user', 'resource']);

        $resourcesWithApprovalAccess = DB::table('user_resource_roles')
            ->where('user_id', $user->id)
            ->pluck('resource_id');

        $query->where(function($q) use ($user, $resourcesWithApprovalAccess) {
            $q->whereIn('resource_id', $resourcesWithApprovalAccess) // Bookings for resources user can approve
              ->orWhere('user_id', $user->id); // User's own bookings
        })->whereHas('resource', function($q) use ($user) {
            $q->where('company_id', $user->company_id);
        });

        $bookings = $query->orderBy('start_time')->get();
        
        $events = $bookings->map(function($booking) {
            return [
                'title' => $booking->user->name . ' - ' . $booking->resource->name . ' - ' . $booking->purpose,
                'start' => $booking->start_time->format('Y-m-d H:i:s'),
                'end' => $booking->end_time->format('Y-m-d H:i:s'),
                'color' => $booking->status === 'approved' ? '#198754' : 
                          ($booking->status === 'rejected' ? '#dc3545' : '#ffc107')
            ];
        });

        $resources = Resource::where('company_id', $user->company_id)
            ->where('status', true)
            ->whereIn('id', $resourcesWithApprovalAccess)
            ->get();

        return view('bookings.index', compact('bookings', 'events', 'resources'));
    }

    public function create()
    {
        $resources = Resource::query()
            ->where('company_id', auth()->user()->company_id)
            ->where('status', true)
            ->get();

        return view('bookings.create', compact('resources'));
    }

    public function getResourceBookings($resourceId)
    {
        $resource = Resource::findOrFail($resourceId);
        
        // Check if user has access to this resource
        if (Auth::user()->isAdmin() && $resource->company_id !== Auth::user()->company_id) {
            abort(403);
        }

        $bookings = Booking::where('resource_id', $resourceId)
            ->where('start_time', '>=', now()->startOfDay())
            ->orderBy('start_time')
            ->with('user')
            ->get()
            ->map(function($booking) {
                return [
                    'user' => $booking->user->name,
                    'start' => $booking->start_time->format('Y-m-d H:i'),
                    'end' => $booking->end_time->format('Y-m-d H:i'),
                    'purpose' => $booking->purpose,
                    'status' => $booking->status
                ];
            });

        return response()->json($bookings);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'resource_id' => 'required|exists:resources,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'description' => 'nullable|string|max:255',
        ]);

        $resource = Resource::findOrFail($validated['resource_id']);

        // Check for overlapping bookings
        $hasOverlap = Booking::where('resource_id', $validated['resource_id'])
            ->where('status', '!=', 'rejected')
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('start_time', '<=', $validated['start_time'])
                            ->where('end_time', '>=', $validated['end_time']);
                    });
            })->exists();

        if ($hasOverlap) {
            return redirect()->back()->with('error', __('This time slot is already booked'));
        }

        // Set default values for optional fields
        $validated['description'] = $validated['description'] ?? null;

        $booking = Booking::create([
            'company_id' => auth()->user()->company_id,
            'user_id' => auth()->id(),
            'resource_id' => $validated['resource_id'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'description' => $validated['description'],
            'status' => 'pending'
        ]);

        return redirect()->route('bookings.index')->with('success', __('Booking created successfully'));
    }

    public function approve(Booking $booking)
    {
        if (!auth()->user()->canApproveBooking($booking)) {
            return redirect()->back()->with('error', __('You do not have permission to approve this booking.'));
        }

        $booking->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', __('Booking has been approved.'));
    }

    public function reject(Booking $booking)
    {
        if (!auth()->user()->canApproveBooking($booking)) {
            return redirect()->back()->with('error', __('You do not have permission to reject this booking.'));
        }

        try {
            DB::beginTransaction();

            $booking->update([
                'status' => 'rejected',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            // Send notification to user about rejection
            // TODO: Implement notification

            DB::commit();
            return redirect()->back()->with('success', __('Booking has been rejected.'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', __('Failed to reject booking. Please try again.'));
        }
    }
}
