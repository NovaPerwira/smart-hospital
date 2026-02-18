@extends('layouts.app')

@section('content')
<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-green-50 to-blue-50">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white shadow-xl rounded-lg overflow-hidden">
            <!-- Success Header -->
            <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-8 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-full mb-4">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">Booking Confirmed!</h1>
                <p class="text-green-100">Your appointment has been successfully booked</p>
            </div>

            <!-- Booking Details -->
            <div class="p-8">
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <div class="text-center mb-6">
                        <p class="text-sm text-gray-600 mb-1">Booking Code</p>
                        <p class="text-3xl font-bold text-gray-900 tracking-wider">{{ $booking->booking_code }}</p>
                        <p class="text-xs text-gray-500 mt-2">Please save this code for your records</p>
                    </div>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center pb-4 border-b">
                            <span class="text-gray-600 font-medium">Patient Name:</span>
                            <span class="text-gray-900 font-semibold">{{ $booking->patient_name }}</span>
                        </div>

                        <div class="flex justify-between items-center pb-4 border-b">
                            <span class="text-gray-600 font-medium">Phone:</span>
                            <span class="text-gray-900 font-semibold">{{ $booking->patient_phone }}</span>
                        </div>

                        <div class="flex justify-between items-center pb-4 border-b">
                            <span class="text-gray-600 font-medium">Doctor:</span>
                            <span class="text-gray-900 font-semibold">{{ $booking->doctor->name }}</span>
                        </div>

                        <div class="flex justify-between items-center pb-4 border-b">
                            <span class="text-gray-600 font-medium">Specialization:</span>
                            <span class="text-gray-900 font-semibold">{{ $booking->doctor->specialization }}</span>
                        </div>

                        <div class="flex justify-between items-center pb-4 border-b">
                            <span class="text-gray-600 font-medium">Treatment:</span>
                            <span class="text-gray-900 font-semibold">{{ $booking->treatment->name }}</span>
                        </div>

                        <div class="flex justify-between items-center pb-4 border-b">
                            <span class="text-gray-600 font-medium">Date:</span>
                            <span class="text-gray-900 font-semibold">{{ $booking->booking_date->format('l, F j, Y') }}</span>
                        </div>

                        <div class="flex justify-between items-center pb-4 border-b">
                            <span class="text-gray-600 font-medium">Time:</span>
                            <span class="text-gray-900 font-semibold">
                                {{ \Carbon\Carbon::parse($booking->slot->start_time)->format('g:i A') }} - 
                                {{ \Carbon\Carbon::parse($booking->slot->end_time)->format('g:i A') }}
                            </span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 font-medium">Status:</span>
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold uppercase">
                                {{ $booking->status }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Important Notes -->
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                <strong>Important:</strong> You will receive a confirmation message via WhatsApp with your booking code. 
                                Please arrive 10 minutes before your scheduled appointment time.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <a 
                        href="{{ route('bookings.create') }}" 
                        class="flex-1 text-center px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors font-medium"
                    >
                        Book Another Appointment
                    </a>
                    <button 
                        onclick="window.print()" 
                        class="flex-1 px-6 py-3 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors font-medium"
                    >
                        Print Confirmation
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
