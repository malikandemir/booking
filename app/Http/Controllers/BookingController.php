<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Item;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = Booking::with(['user', 'item']);

        // Get items where user has approval role
        $approverRole = Role::where('name', 'Booking Approver')->first();
        $itemsWithApprovalAccess = DB::table('user_item_roles')
            ->where('user_id', $user->id)
            ->where('role_id', $approverRole->id)
            ->pluck('item_id');

        $query->where(function($q) use ($user, $itemsWithApprovalAccess) {
            $q->whereIn('item_id', $itemsWithApprovalAccess) // Bookings for items user can approve
              ->orWhere('user_id', $user->id); // User's own bookings
        })->whereHas('item', function($q) use ($user) {
            $q->where('company_id', $user->company_id);
        });

        $bookings = $query->orderBy('start_time')->get();
        
        $events = $bookings->map(function($booking) {
            return [
                'title' => $booking->user->name . ' - ' . $booking->item->name . ' - ' . $booking->purpose,
                'start' => $booking->start_time->format('Y-m-d H:i:s'),
                'end' => $booking->end_time->format('Y-m-d H:i:s'),
                'color' => $booking->status === 'approved' ? '#198754' : 
                          ($booking->status === 'rejected' ? '#dc3545' : '#ffc107')
            ];
        });

        // Get items where user has approval role for the dropdown
        $items = Item::where('company_id', $user->company_id)
            ->where('status', true)
            ->whereIn('id', $itemsWithApprovalAccess)
            ->get();

        return view('bookings.index', compact('bookings', 'events', 'items'));
    }

    public function create()
    {
        $items = Item::query()
            ->where('company_id', auth()->user()->company_id)
            ->where('status', true)
            ->get();

        return view('bookings.create', compact('items'));
    }

    public function getItemBookings($itemId)
    {
        $item = Item::findOrFail($itemId);
        
        // Check if user has access to this item
        if (Auth::user()->isAdmin() && $item->company_id !== Auth::user()->company_id) {
            abort(403);
        }

        $bookings = Booking::where('item_id', $itemId)
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
            'item_id' => 'required|exists:items,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'description' => 'nullable|string|max:255',
        ]);

        $item = Item::findOrFail($validated['item_id']);
        
        if (!auth()->user()->canBook($item)) {
            return redirect()->back()->with('error', __('You do not have permission to book this resource'));
        }

        // Check for overlapping bookings
        $hasOverlap = Booking::where('item_id', $validated['item_id'])
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
            'user_id' => auth()->id(),
            'item_id' => $validated['item_id'],
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
