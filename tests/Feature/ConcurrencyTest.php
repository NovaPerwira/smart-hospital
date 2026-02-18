<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Models\Doctor;
use App\Models\Chair;
use App\Models\Treatment;
use App\Models\BookingSlot;

class ConcurrencyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed necessary data (User, and initial Doctor/Chair/Treatment for convenience)
        $this->seed();
    }

    #[Test]
    public function it_prevents_overlapping_bookings_for_same_doctor()
    {
        $doctor = Doctor::first();
        $chair = Chair::first();
        $treatment = Treatment::first();

        // Ensure doctor has treatment
        if (!$doctor->treatments->contains($treatment->id)) {
            $doctor->treatments()->attach($treatment->id);
        }

        $date = now()->addDays(2)->toDateString(); // Future date

        $data = [
            'patient_name' => 'John Doe',
            'patient_phone' => '1234567890',
            'doctor_id' => $doctor->id,
            'chair_id' => $chair->id, // Only 1 chair usually, so maybe conflict on chair too?
            'treatment_id' => $treatment->id,
            'booking_date' => $date,
            'start_time' => '10:00',
            'end_time' => '10:30',
        ];

        // First booking should succeed
        $response1 = $this->post(route('bookings.store'), $data);
        if ($response1->status() !== 302) {
            dump($response1->content());
        }
        $response1->assertRedirect();
        $response1->assertSessionHas('success');

        // Second booking with same time should fail
        // Using a different chair to isolate Doctor conflict? But here we use same chair too.
        // Let's create a specific test for doctor conflict by creating a NEW chair for the second booking.
        $chair2 = Chair::create(['name' => 'Chair 2', 'is_active' => true]);

        $data2 = array_merge($data, ['chair_id' => $chair2->id]);

        $response2 = $this->post(route('bookings.store'), $data2);

        // Expecting session error on start_time
        $response2->assertSessionHasErrors('start_time');
    }

    #[Test]
    public function it_prevents_overlapping_bookings_for_same_chair()
    {
        // Create 2 Doctors
        $doctor1 = Doctor::create(['name' => 'Dr One', 'specialization' => 'General']);
        $doctor2 = Doctor::create(['name' => 'Dr Two', 'specialization' => 'General']);

        $chair = Chair::first(); // Use existing seeded chair
        $treatment = Treatment::first();

        $doctor1->treatments()->attach($treatment->id);
        $doctor2->treatments()->attach($treatment->id);

        $date = now()->addDays(3)->toDateString();

        $data1 = [
            'patient_name' => 'John Doe',
            'patient_phone' => '1234567890',
            'doctor_id' => $doctor1->id,
            'chair_id' => $chair->id,
            'treatment_id' => $treatment->id,
            'booking_date' => $date,
            'start_time' => '14:00',
            'end_time' => '14:30',
        ];

        // First booking succeeds
        $this->post(route('bookings.store'), $data1)->assertSessionHasNoErrors();

        $data2 = [
            'patient_name' => 'Jane Doe',
            'patient_phone' => '0987654321',
            'doctor_id' => $doctor2->id, // DIFFERENT doctor
            'chair_id' => $chair->id,    // SAME chair
            'treatment_id' => $treatment->id,
            'booking_date' => $date,
            'start_time' => '14:00', // Overlapping time
            'end_time' => '14:30',
        ];

        // Second booking should fail due to CHAIR conflict
        $response = $this->post(route('bookings.store'), $data2);

        // Expecting session error
        $response->assertSessionHasErrors();
    }

    #[Test]
    public function it_enforces_database_unique_constraints()
    {
        $doctor = Doctor::create(['name' => 'Dr Three', 'specialization' => 'General']);
        $chair = Chair::create(['name' => 'Chair 3', 'is_active' => true]);

        $date = now()->addDays(4)->toDateString();
        $startTime = '09:00';
        $endTime = '09:30';

        // Manually create a slot
        BookingSlot::create([
            'doctor_id' => $doctor->id,
            'chair_id' => $chair->id,
            'date' => $date,
            'start_time' => $startTime,
            'end_time' => $endTime,
        ]);

        // Attempt to create duplicate slot directly in DB (Bypassing Service)
        $this->expectException(\Illuminate\Database\QueryException::class);

        BookingSlot::create([
            'doctor_id' => $doctor->id, // Same doctor
            'chair_id' => $chair->id,
            'date' => $date, // Same date
            'start_time' => $startTime, // Same time
            'end_time' => $endTime,
        ]);
    }
}
