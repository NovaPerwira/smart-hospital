<?php

namespace App\Services;

use App\Events\BookingCompleted;
use App\Events\BookingCreated;
use App\Exceptions\BookingConflictException;
use App\Models\Booking;
use App\Models\BookingSlot;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingService
{
    /**
     * Create a new booking with transaction and locking.
     *
     * @throws BookingConflictException
     */
    public function createBooking(array $data): Booking
    {
        return DB::transaction(function () use ($data) {
            // Layer 2: Pessimistic Locking
            // Lock the resources (Doctor and Chair) to prevent concurrent bookings
            // This ensures that the availability check (Layer 1) is atomic
            $doctor = \App\Models\Doctor::where('id', $data['doctor_id'])->lockForUpdate()->first();
            $chair = \App\Models\Chair::where('id', $data['chair_id'])->lockForUpdate()->first();

            // Layer 1: Application availability check
            // Check if any slot overlaps with the requested time for this doctor
            $doctorConflict = BookingSlot::where('doctor_id', $data['doctor_id'])
                ->where('date', $data['booking_date'])
                ->where(function ($query) use ($data) {
                    $query->where(function ($q) use ($data) {
                        $q->where('start_time', '<', $data['end_time'])
                            ->where('end_time', '>', $data['start_time']);
                    });
                })
                ->exists();

            if ($doctorConflict) {
                throw new BookingConflictException('The doctor is already booked for this time slot.');
            }

            // Check if any slot overlaps with the requested time for this chair
            $chairConflict = BookingSlot::where('chair_id', $data['chair_id'])
                ->where('date', $data['booking_date'])
                ->where(function ($query) use ($data) {
                    $query->where(function ($q) use ($data) {
                        $q->where('start_time', '<', $data['end_time'])
                            ->where('end_time', '>', $data['start_time']);
                    });
                })
                ->exists();

            if ($chairConflict) {
                throw new BookingConflictException('The chair is already booked for this time slot.');
            }

            try {
                // Create the slot (effectively reserving it)
                // Layer 3: Database Unique Constraint will catch any race conditions that bypassed Layer 2
                $slot = BookingSlot::create([
                    'doctor_id' => $data['doctor_id'],
                    'chair_id' => $data['chair_id'],
                    'date' => $data['booking_date'],
                    'start_time' => $data['start_time'],
                    'end_time' => $data['end_time'],
                ]);

                // Generate unique booking code
                $bookingCode = $this->generateBookingCode();

                // Create the booking
                $booking = Booking::create([
                    'booking_code' => $bookingCode,
                    'patient_name' => $data['patient_name'],
                    'patient_phone' => $data['patient_phone'],
                    'doctor_id' => $data['doctor_id'],
                    'treatment_id' => $data['treatment_id'],
                    'booking_date' => $data['booking_date'],
                    'slot_id' => $slot->id,
                    'status' => 'confirmed',
                ]);

                // Dispatch event
                event(new BookingCreated($booking));

                return $booking;

            } catch (\Illuminate\Database\QueryException $e) {
                // Layer 3 Fallback
                if ($e->errorInfo[1] == 1062) { // Duplicate entry
                    throw new BookingConflictException('The selected time slot was just booked by another user.');
                }
                throw $e;
            }
        });
    }

    /**
     * Mark a booking as completed and trigger follow-up scheduling.
     * Called by admin action.
     */
    public function completeBooking(Booking $booking): Booking
    {
        $booking->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        // Dispatch BookingCompleted event → ScheduleFollowUps listener → ScheduleFollowUpsJob
        event(new BookingCompleted($booking));

        return $booking->fresh();
    }

    /**
     * Generate a unique booking code.
     */
    protected function generateBookingCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (Booking::where('booking_code', $code)->exists());

        return $code;
    }
}
