@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">

                @if($type === 'success')
                    <div class="bg-green-500 px-6 py-8 text-center">
                        <div class="text-5xl mb-3">✅</div>
                        <h1 class="text-2xl font-bold text-white">{{ $title }}</h1>
                    </div>
                    <div class="p-6 text-center">
                        <p class="text-gray-700 mb-4">{{ $message }}</p>
                        @isset($booking)
                            <div class="bg-gray-50 rounded-lg p-4 text-left text-sm space-y-2 mb-6">
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Booking Code</span>
                                    <span class="font-semibold">{{ $booking->booking_code }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Doctor</span>
                                    <span class="font-semibold">{{ $booking->doctor->name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Date</span>
                                    <span
                                        class="font-semibold">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Time</span>
                                    <span
                                        class="font-semibold">{{ \Carbon\Carbon::parse($booking->slot->start_time)->format('H:i') }}</span>
                                </div>
                            </div>
                        @endisset
                        <p class="text-sm text-green-600 font-medium">See you at your appointment! 👋</p>
                    </div>

                @elseif($type === 'info')
                    <div class="bg-blue-500 px-6 py-8 text-center">
                        <div class="text-5xl mb-3">📋</div>
                        <h1 class="text-2xl font-bold text-white">{{ $title }}</h1>
                    </div>
                    <div class="p-6 text-center">
                        <p class="text-gray-700 mb-6">{{ $message }}</p>
                        <a href="{{ route('bookings.create') }}"
                            class="inline-block px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                            Book a New Appointment
                        </a>
                    </div>

                @else {{-- error --}}
                    <div class="bg-red-500 px-6 py-8 text-center">
                        <div class="text-5xl mb-3">❌</div>
                        <h1 class="text-2xl font-bold text-white">{{ $title }}</h1>
                    </div>
                    <div class="p-6 text-center">
                        <p class="text-gray-700 mb-6">{{ $message }}</p>
                        <a href="{{ route('bookings.create') }}"
                            class="inline-block px-6 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                            Go to Booking Page
                        </a>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection