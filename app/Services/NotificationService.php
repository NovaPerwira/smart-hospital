<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\NotificationLog;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send a notification and log it.
     * In production, replace Log::info with actual WhatsApp/Email API calls.
     */
    public function send(Booking $booking, string $type, string $message): void
    {
        $channel = 'whatsapp'; // Primary channel per flowchart

        // TODO: Integrate with WhatsApp Business API (e.g., Twilio, Fonnte, etc.)
        // WhatsApp::send($booking->patient_phone, $message);

        Log::channel('stack')->info("[{$channel}:{$type}] → {$booking->patient_phone}", [
            'booking_code' => $booking->booking_code,
            'message' => $message,
        ]);

        // Log to database
        NotificationLog::create([
            'booking_id' => $booking->id,
            'channel' => $channel,
            'type' => $type,
            'message' => $message,
            'recipient' => $booking->patient_phone,
            'status' => 'sent',
        ]);
    }

    /**
     * Build the Phase 1 instant confirmation message.
     */
    public function buildConfirmationMessage(Booking $booking): string
    {
        $date = \Carbon\Carbon::parse($booking->booking_date)->format('d M Y');
        $time = \Carbon\Carbon::parse($booking->slot->start_time)->format('H:i');
        $confirmUrl = route('bookings.confirm', ['token' => $booking->confirmation_token]);
        $cancelUrl = route('bookings.cancel', ['token' => $booking->confirmation_token]);

        return <<<MSG
        ✅ *Booking Confirmed!*

        Hi {$booking->patient_name}, your appointment has been booked.

        📋 *Booking Code:* {$booking->booking_code}
        👨‍⚕️ *Doctor:* {$booking->doctor->name}
        💊 *Treatment:* {$booking->treatment->name}
        📅 *Date:* {$date}
        🕐 *Time:* {$time}

        We will send you a reminder 1 day before your appointment.
        MSG;
    }

    /**
     * Build the Phase 2 H-1 reminder message.
     */
    public function buildReminderMessage(Booking $booking): string
    {
        $date = \Carbon\Carbon::parse($booking->booking_date)->format('d M Y');
        $time = \Carbon\Carbon::parse($booking->slot->start_time)->format('H:i');
        $confirmUrl = route('bookings.confirm', ['token' => $booking->confirmation_token]);
        $cancelUrl = route('bookings.cancel', ['token' => $booking->confirmation_token]);

        return <<<MSG
        ⏰ *Appointment Reminder (H-1)*

        Hi {$booking->patient_name}, your appointment is *tomorrow*!

        📋 *Booking Code:* {$booking->booking_code}
        👨‍⚕️ *Doctor:* {$booking->doctor->name}
        📅 *Date:* {$date}
        🕐 *Time:* {$time}

        Please confirm your attendance:
        ✅ Confirm: {$confirmUrl}
        ❌ Cancel:  {$cancelUrl}
        MSG;
    }
}
