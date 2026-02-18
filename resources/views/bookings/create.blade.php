@extends('layouts.app')

@section('content')
<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="bg-blue-600 px-6 py-4">
                <h1 class="text-2xl font-bold text-white">Book an Appointment</h1>
                <p class="text-blue-100 mt-1">Select your treatment, doctor, and preferred time slot</p>
            </div>

            <div class="p-6">
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form 
                    x-data="bookingForm()" 
                    @submit.prevent="submitForm"
                    action="{{ route('bookings.store') }}" 
                    method="POST"
                    class="space-y-6"
                >
                    @csrf

                    <!-- Treatment Selection -->
                    <div>
                        <label for="treatment_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Select Treatment <span class="text-red-500">*</span>
                        </label>
                        <select 
                            id="treatment_id"
                            name="treatment_id"
                            x-model="formData.treatment_id"
                            @change="onTreatmentChange"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            required
                        >
                            <option value="">Choose a treatment...</option>
                            @foreach($treatments as $treatment)
                                <option value="{{ $treatment->id }}" data-duration="{{ $treatment->duration_minutes }}">
                                    {{ $treatment->name }} ({{ $treatment->duration_minutes }} min) - ${{ number_format($treatment->price, 2) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Doctor Selection -->
                    <div x-show="formData.treatment_id">
                        <label for="doctor_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Select Doctor <span class="text-red-500">*</span>
                        </label>
                        <select 
                            id="doctor_id"
                            name="doctor_id"
                            x-model="formData.doctor_id"
                            @change="onDoctorChange"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            :disabled="!formData.treatment_id"
                            required
                        >
                            <option value="">Choose a doctor...</option>
                            <template x-for="doctor in availableDoctors" :key="doctor.id">
                                <option :value="doctor.id" x-text="doctor.name + ' - ' + doctor.specialization"></option>
                            </template>
                        </select>
                    </div>

                    <!-- Chair Selection -->
                    <div x-show="formData.doctor_id">
                        <label for="chair_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Select Room/Chair <span class="text-red-500">*</span>
                        </label>
                        <select 
                            id="chair_id"
                            name="chair_id"
                            x-model="formData.chair_id"
                            @change="onChairChange"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            :disabled="!formData.doctor_id"
                            required
                        >
                            <option value="">Choose a room...</option>
                            @foreach($chairs as $chair)
                                <option value="{{ $chair->id }}">{{ $chair->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date Selection -->
                    <div x-show="formData.chair_id">
                        <label for="booking_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Select Date <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="date"
                            id="booking_date"
                            name="booking_date"
                            x-model="formData.booking_date"
                            @change="onDateChange"
                            :min="minDate"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            :disabled="!formData.chair_id"
                            required
                        >
                    </div>

                    <!-- Time Slot Selection -->
                    <div x-show="formData.booking_date && availableSlots.length > 0">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Available Time Slots <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                            <template x-for="slot in availableSlots" :key="slot.start_time">
                                <label 
                                    class="relative flex items-center p-3 border-2 rounded-lg cursor-pointer transition-all"
                                    :class="formData.start_time == slot.start_time 
                                        ? 'border-blue-500 bg-blue-50' 
                                        : 'border-gray-200 hover:border-gray-300'"
                                >
                                    <input 
                                        type="radio"
                                        name="time_slot"
                                        :value="slot.start_time"
                                        @click="selectSlot(slot)"
                                        class="sr-only"
                                        required
                                    >
                                    <span class="text-sm font-medium text-gray-900" x-text="slot.label"></span>
                                </label>
                            </template>
                        </div>
                        <p x-show="availableSlots.length === 0 && formData.booking_date && !loading" class="mt-2 text-sm text-gray-500">
                            No available slots for this date. Please select another date.
                        </p>
                    </div>

                    <!-- Hidden Inputs for Time -->
                    <input type="hidden" name="start_time" x-model="formData.start_time">
                    <input type="hidden" name="end_time" x-model="formData.end_time">

                    <!-- Patient Information -->
                    <div x-show="formData.start_time" class="border-t pt-6 space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Patient Information</h3>
                        
                        <div>
                            <label for="patient_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text"
                                id="patient_name"
                                name="patient_name"
                                x-model="formData.patient_name"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                required
                            >
                        </div>

                        <div>
                            <label for="patient_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Phone Number <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="tel"
                                id="patient_phone"
                                name="patient_phone"
                                x-model="formData.patient_phone"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                required
                            >
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end pt-6 border-t">
                        <button 
                            type="submit"
                            :disabled="loading || !formData.start_time"
                            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                        >
                            <span x-show="!loading">Confirm Booking</span>
                            <span x-show="loading">Processing...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function bookingForm() {
    return {
        loading: false,
        formData: {
            treatment_id: '',
            doctor_id: '',
            chair_id: '',
            booking_date: '',
            start_time: '',
            end_time: '',
            patient_name: '',
            patient_phone: ''
        },
        allDoctors: @json($doctorsForJs),
        availableDoctors: @json($doctorsForJs),
        availableSlots: [],
        minDate: new Date().toISOString().split('T')[0],

        onTreatmentChange() {
            this.formData.doctor_id = '';
            this.formData.chair_id = '';
            this.formData.booking_date = '';
            this.resetSlot();
            this.availableSlots = [];
            
            // Filter doctors by treatment
            const treatmentId = parseInt(this.formData.treatment_id);
            this.availableDoctors = this.allDoctors.filter(doctor => doctor.treatments.includes(treatmentId));
        },

        onDoctorChange() {
            this.formData.chair_id = '';
            this.formData.booking_date = '';
            this.resetSlot();
            this.availableSlots = [];
        },

        onChairChange() {
            this.formData.booking_date = '';
            this.resetSlot();
            this.availableSlots = [];
        },

        async onDateChange() {
            if (!this.formData.doctor_id || !this.formData.chair_id || !this.formData.booking_date) {
                this.availableSlots = [];
                return;
            }

            this.resetSlot();
            this.loading = true;

            try {
                // Construct URL with query parameters
                const params = new URLSearchParams({
                    doctor_id: this.formData.doctor_id,
                    chair_id: this.formData.chair_id,
                    date: this.formData.booking_date
                });

                const response = await fetch(`{{ route('bookings.available-slots') }}?${params.toString()}`);
                const data = await response.json();
                this.availableSlots = data.slots || [];
            } catch (error) {
                console.error('Error fetching slots:', error);
                this.availableSlots = [];
            } finally {
                this.loading = false;
            }
        },

        selectSlot(slot) {
            this.formData.start_time = slot.start_time;
            this.formData.end_time = slot.end_time;
        },

        resetSlot() {
            this.formData.start_time = '';
            this.formData.end_time = '';
        },

        submitForm() {
            if (!this.formData.start_time) {
                return;
            }
            this.loading = true;
            this.$el.submit();
        }
    }
}
</script>
@endsection