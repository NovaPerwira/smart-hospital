<?php

namespace App\Http\Controllers;

use App\Exceptions\BookingConflictException;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\BookingSlot;
use App\Models\Doctor;
use App\Models\Treatment;
use App\Services\BookingService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function __construct(
        private BookingService $bookingService
    ) {
    }

    /**
     * Show the booking form.
     */
    public function create()
    {
        $treatments = Treatment::all();
        $doctors = Doctor::with('treatments')->get();
        // Add Chairs
        $chairs = \App\Models\Chair::where('is_active', true)->get();

        $doctorsForJs = $doctors
            ->map(fn(Doctor $doctor) => [
                'id' => $doctor->id,
                'name' => $doctor->name,
                'specialization' => $doctor->specialization,
                'treatments' => $doctor->treatments->pluck('id')->map(fn($id) => (int) $id)->all(),
            ])
            ->values();

        return view('bookings.create', compact('treatments', 'doctors', 'doctorsForJs', 'chairs'));
    }

    /**
     * Store a new booking.
     */
    public function store(StoreBookingRequest $request)
    {
        // The service handles the complex logic and locking
        $booking = $this->bookingService->createBooking($request->validated());

        return redirect()
            ->route('bookings.confirmation', $booking)
            ->with('success', 'Booking created successfully!');
    }

    /**
     * Show booking confirmation page.
     */
    public function confirmation(Booking $booking)
    {
        $booking->load(['doctor', 'treatment', 'slot']);

        return view('bookings.confirmation', compact('booking'));
    }

    /**
     * Fetch available time slots for a doctor on a specific date.
     * Note: This is a simplified generator for the demo.
     */
    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'doctor_id' => ['required', 'exists:doctors,id'],
            'date' => ['required', 'date', 'after_or_equal:today'],
            'chair_id' => ['required', 'exists:chairs,id'], // Check chair availability too
        ]);

        $date = $request->date;
        $doctorId = $request->doctor_id;
        $chairId = $request->chair_id;

        // Configuration (could be in config or DB)
        $startHour = 9;
        $endHour = 17;
        $intervalMinutes = 30;

        $availableSlots = [];
        $current = \Carbon\Carbon::parse("$date $startHour:00:00");
        $end = \Carbon\Carbon::parse("$date $endHour:00:00");

        while ($current < $end) {
            $slotStart = $current->format('H:i');
            $current->addMinutes($intervalMinutes);
            $slotEnd = $current->format('H:i');

            // Check overlap
            $isBooked = BookingSlot::where(function ($q) use ($date, $slotStart, $slotEnd, $doctorId, $chairId) {
                // Check Doctor Conflict
                $q->where('doctor_id', $doctorId)
                    ->where('date', $date)
                    ->where(function ($sub) use ($slotStart, $slotEnd) {
                        $sub->where('start_time', '<', $slotEnd)
                            ->where('end_time', '>', $slotStart);
                    });
            })->orWhere(function ($q) use ($date, $slotStart, $slotEnd, $chairId) {
                // Check Chair Conflict
                $q->where('chair_id', $chairId)
                    ->where('date', $date)
                    ->where(function ($sub) use ($slotStart, $slotEnd) {
                        $sub->where('start_time', '<', $slotEnd)
                            ->where('end_time', '>', $slotStart);
                    });
            })->exists();

            if (!$isBooked) {
                $availableSlots[] = [
                    'start_time' => $slotStart,
                    'end_time' => $slotEnd,
                    'label' => "$slotStart - $slotEnd",
                ];
            }
        }

        return response()->json(['slots' => $availableSlots]);
    }
    /**
     * Look up a booking by booking code (for patient portal).
     */
    public function lookup(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'max:20'],
        ]);

        $booking = Booking::with(['doctor', 'treatment', 'slot'])
            ->where('booking_code', strtoupper(trim($request->code)))
            ->first();

        if (!$booking) {
            return response()->json([
                'found' => false,
                'message' => 'Booking not found. Please check your booking code.',
            ]);
        }

        return response()->json([
            'found' => true,
            'booking_code' => $booking->booking_code,
            'patient_name' => $booking->patient_name,
            'booking_date' => $booking->booking_date->format('d M Y'),
            'treatment' => $booking->treatment?->name ?? '—',
            'doctor' => $booking->doctor?->name ?? '—',
            'time' => $booking->slot
                ? \Carbon\Carbon::parse($booking->slot->start_time)->format('H:i') . ' – ' . \Carbon\Carbon::parse($booking->slot->end_time)->format('H:i')
                : '—',
            'status' => $booking->status,
        ]);
    }
}

