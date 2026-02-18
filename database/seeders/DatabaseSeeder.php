<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $doctor = \App\Models\Doctor::create([
            'name' => 'Dr. Smith',
            'specialization' => 'Dentist',
        ]);

        $chair = \App\Models\Chair::create([
            'name' => 'Chair 1',
            'is_active' => true,
        ]);

        // --- Treatments with follow-up rules (matching flowchart examples) ---

        // 1. Scaling Gigi → +6 months follow-up
        $scaling = \App\Models\Treatment::create([
            'name' => 'Scaling Gigi',
            'duration_minutes' => 60,
            'price' => 150.00,
        ]);
        $doctor->treatments()->attach($scaling->id);
        \App\Models\FollowUpRule::create([
            'treatment_id' => $scaling->id,
            'trigger_event' => 'completed',
            'interval_value' => 6,
            'interval_unit' => 'months',
            'channel' => 'whatsapp',
            'message_template' => "Halo {patient_name}! Sudah 6 bulan, waktunya scaling! 😊\nSilakan hubungi kami untuk jadwal scaling berikutnya bersama {doctor_name}.\nKode booking terakhir Anda: {booking_code}",
            'is_active' => true,
        ]);

        // 2. Cabut Gigi → +3 days post-extraction check
        $cabut = \App\Models\Treatment::create([
            'name' => 'Cabut Gigi',
            'duration_minutes' => 45,
            'price' => 100.00,
        ]);
        $doctor->treatments()->attach($cabut->id);
        \App\Models\FollowUpRule::create([
            'treatment_id' => $cabut->id,
            'trigger_event' => 'completed',
            'interval_value' => 3,
            'interval_unit' => 'days',
            'channel' => 'whatsapp',
            'message_template' => "Halo {patient_name}! Luka sudah membaik? 🦷\nIni adalah follow-up 3 hari pasca pencabutan gigi Anda bersama {doctor_name}.\nJika ada keluhan, segera hubungi kami. Kode: {booking_code}",
            'is_active' => true,
        ]);

        // 3. Tambal Gigi → +1 week evaluation
        $tambal = \App\Models\Treatment::create([
            'name' => 'Tambal Gigi',
            'duration_minutes' => 30,
            'price' => 80.00,
        ]);
        $doctor->treatments()->attach($tambal->id);
        \App\Models\FollowUpRule::create([
            'treatment_id' => $tambal->id,
            'trigger_event' => 'completed',
            'interval_value' => 1,
            'interval_unit' => 'weeks',
            'channel' => 'whatsapp',
            'message_template' => "Halo {patient_name}! Bagaimana kondisi tambalan? 😊\nEvaluasi 1 minggu pasca tambal gigi bersama {doctor_name}.\nKode booking: {booking_code}",
            'is_active' => true,
        ]);

        // 4. General Checkup (original) — no follow-up rule
        $checkup = \App\Models\Treatment::create([
            'name' => 'General Checkup',
            'duration_minutes' => 30,
            'price' => 50.00,
        ]);
        $doctor->treatments()->attach($checkup->id);
    }
}

