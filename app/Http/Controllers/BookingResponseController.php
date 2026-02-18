<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Phase 3 — Patient Response Handling
 * Handles GET /bookings/confirm/{token} and GET /bookings/cancel/{token}
 * These are the CTA links sent in the H-1 reminder WhatsApp message.
 */
class BookingResponseController extends Controller
{
    /**
     * Patient clicks "Confirm" link from H-1 reminder.
     * GET /bookings/confirm/{token}
     */
    public function confirm(string $token)
    {
        $booking = Booking::where('confirmation_token', $token)
            ->where('status', 'confirmed')
            ->first();

        if (!$booking) {
            return view('bookings.response', [
                'type' => 'error',
                'title' => 'Invalid or Expired Link',
                'message' => 'This confirmation link is invalid or has already been used.',
            ]);
        }

        $booking->update([
            'confirmed_at' => now(),
            'cancellation_pending' => false,
        ]);

        Log::info('[confirm] Patient confirmed attendance', [
            'booking_code' => $booking->booking_code,
            'patient' => $booking->patient_name,
        ]);

        return view('bookings.response', [
            'type' => 'success',
            'title' => 'Attendance Confirmed!',
            'message' => "Thank you, {$booking->patient_name}! We look forward to seeing you for booking {$booking->booking_code}.",
            'booking' => $booking->load(['doctor', 'slot']),
        ]);
    }

    /**
     * Patient clicks "Cancel" link from H-1 reminder.
     * GET /bookings/cancel/{token}
     */
    public function cancel(string $token)
    {
        $booking = Booking::where('confirmation_token', $token)
            ->whereIn('status', ['confirmed'])
            ->first();

        if (!$booking) {
            return view('bookings.response', [
                'type' => 'error',
                'title' => 'Invalid or Expired Link',
                'message' => 'This cancellation link is invalid or has already been used.',
            ]);
        }

        // Mark cancellation pending — staff can review before fully releasing slot
        $booking->update([
            'status' => 'cancelled',
            'cancellation_pending' => true,
        ]);

        // Release the slot immediately on cancellation
        \App\Models\BookingSlot::where('id', $booking->slot_id)->delete();

        Log::info('[cancel] Patient cancelled booking', [
            'booking_code' => $booking->booking_code,
            'patient' => $booking->patient_name,
        ]);

        return view('bookings.response', [
            'type' => 'info',
            'title' => 'Booking Cancelled',
            'message' => "Your booking {$booking->booking_code} has been cancelled. The slot is now available for others.",
        ]);
    }
}
