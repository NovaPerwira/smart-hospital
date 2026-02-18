@extends('layouts.admin')
@section('title', 'Booking Detail')

@section('content')
    <div class="max-w-4xl mx-auto space-y-5 fade-up">

        {{-- Back --}}
        <a href="{{ route('admin.bookings') }}"
            class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-white transition-colors">
            ← Back to Bookings
        </a>

        {{-- ── HEADER CARD ──────────────────────────────────────────────────── --}}
        <div class="glass rounded-2xl p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div
                        class="w-14 h-14 rounded-2xl bg-gradient-to-br from-cyan-500/20 to-violet-500/20 flex items-center justify-center text-2xl font-bold text-cyan-400">
                        {{ strtoupper(substr($booking->patient_name, 0, 1)) }}
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">{{ $booking->patient_name }}</h2>
                        <p class="text-gray-500 text-sm">{{ $booking->patient_phone }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span
                        class="font-mono text-sm text-cyan-400 bg-cyan-500/10 px-3 py-1.5 rounded-xl">{{ $booking->booking_code }}</span>
                    <span class="text-sm px-3 py-1.5 rounded-xl font-medium badge-{{ $booking->status }}">
                        {{ str_replace('_', ' ', ucfirst($booking->status)) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

            {{-- ── BOOKING INFO ──────────────────────────────────────────────── --}}
            <div class="glass rounded-2xl p-5">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Appointment Details</h3>
                <dl class="space-y-3">
                    @php
                        $details = [
                            ['label' => 'Doctor', 'value' => $booking->doctor->name],
                            ['label' => 'Treatment', 'value' => $booking->treatment->name . ' (' . $booking->treatment->duration_minutes . ' min)'],
                            ['label' => 'Date', 'value' => \Carbon\Carbon::parse($booking->booking_date)->format('l, d F Y')],
                            [
                                'label' => 'Time',
                                'value' => $booking->slot
                                    ? \Carbon\Carbon::parse($booking->slot->start_time)->format('H:i') . ' – ' . \Carbon\Carbon::parse($booking->slot->end_time)->format('H:i')
                                    : '—'
                            ],
                            ['label' => 'Price', 'value' => 'Rp ' . number_format($booking->treatment->price, 0, ',', '.')],
                        ];
                    @endphp
                    @foreach($details as $d)
                        <div class="flex justify-between items-start gap-4">
                            <dt class="text-xs text-gray-500 flex-shrink-0">{{ $d['label'] }}</dt>
                            <dd class="text-sm text-white font-medium text-right">{{ $d['value'] }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>

            {{-- ── PHASE TIMELINE ────────────────────────────────────────────── --}}
            <div class="glass rounded-2xl p-5">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Automation Phases</h3>
                <div class="space-y-3">
                    @php
                        $phases = [
                            ['icon' => '✅', 'label' => 'Phase 1 — Confirmation Sent', 'time' => $booking->created_at, 'color' => 'cyan'],
                            ['icon' => '⏰', 'label' => 'Phase 2 — H-1 Reminder Sent', 'time' => $booking->reminder_sent_at, 'color' => 'violet'],
                            ['icon' => '🔗', 'label' => 'Phase 3 — Patient Confirmed', 'time' => $booking->confirmed_at, 'color' => 'amber'],
                            ['icon' => '🏥', 'label' => 'Phase 4 — Patient Arrived', 'time' => $booking->arrived_at, 'color' => 'emerald'],
                            ['icon' => '🏁', 'label' => 'Booking Completed', 'time' => $booking->completed_at, 'color' => 'emerald'],
                        ];
                    @endphp
                    @foreach($phases as $phase)
                        <div
                            class="flex items-center gap-3 p-2.5 rounded-xl {{ $phase['time'] ? 'bg-white/5' : 'opacity-30' }}">
                            <span class="text-lg">{{ $phase['icon'] }}</span>
                            <div class="flex-1">
                                <p class="text-xs font-medium text-white">{{ $phase['label'] }}</p>
                                @if($phase['time'])
                                    <p class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($phase['time'])->format('d M Y H:i') }}</p>
                                @else
                                    <p class="text-xs text-gray-600">Pending</p>
                                @endif
                            </div>
                            @if($phase['time'])
                                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                            @else
                                <span class="w-2 h-2 rounded-full bg-gray-700"></span>
                            @endif
                        </div>
                    @endforeach
                </div>

                {{-- Actions --}}
                <div class="mt-4 pt-4 border-t border-white/5 flex gap-2 flex-wrap">
                    @if($booking->status === 'confirmed' && !$booking->arrived_at)
                        <form method="POST" action="{{ route('admin.booking-arrived', $booking) }}">
                            @csrf
                            <button
                                class="px-4 py-2 bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 rounded-xl text-sm font-medium hover:bg-emerald-500/25 transition-colors">
                                🏥 Mark Arrived
                            </button>
                        </form>
                    @endif
                    @if(in_array($booking->status, ['confirmed']) && $booking->arrived_at && !$booking->completed_at)
                        <form method="POST" action="{{ route('admin.booking-complete', $booking) }}">
                            @csrf
                            <button
                                class="px-4 py-2 bg-violet-500/15 border border-violet-500/30 text-violet-400 rounded-xl text-sm font-medium hover:bg-violet-500/25 transition-colors">
                                🏁 Mark Completed
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── NOTIFICATION LOGS ────────────────────────────────────────────── --}}
        @if($booking->notifications->count())
            <div class="glass rounded-2xl p-5">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Notification History
                    ({{ $booking->notifications->count() }})</h3>
                <div class="space-y-2">
                    @foreach($booking->notifications as $log)
                        <div class="flex items-start gap-3 p-3 rounded-xl bg-white/3">
                            <span class="text-lg flex-shrink-0">
                                {{ $log->type === 'confirmation' ? '✅' : ($log->type === 'reminder' ? '⏰' : ($log->type === 'follow_up' ? '🔁' : '📨')) }}
                            </span>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span
                                        class="text-xs font-medium text-white capitalize">{{ str_replace('_', ' ', $log->type) }}</span>
                                    <span class="text-xs px-1.5 py-0.5 rounded badge-{{ $log->status }}">{{ $log->status }}</span>
                                    <span class="text-xs text-gray-600">{{ $log->channel }}</span>
                                </div>
                                <p class="text-xs text-gray-500 truncate">{{ Str::limit($log->message, 100) }}</p>
                                <p class="text-xs text-gray-700 mt-1">
                                    {{ \Carbon\Carbon::parse($log->created_at)->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ── SCHEDULED FOLLOW-UPS ─────────────────────────────────────────── --}}
        @if($booking->scheduledFollowUps->count())
            <div class="glass rounded-2xl p-5">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Scheduled Follow-Ups
                    ({{ $booking->scheduledFollowUps->count() }})</h3>
                <div class="space-y-2">
                    @foreach($booking->scheduledFollowUps as $fu)
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-white/3">
                            <span class="text-lg">🔁</span>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span
                                        class="text-xs font-medium text-white">{{ $fu->rule?->treatment?->name ?? 'Follow-Up' }}</span>
                                    <span class="text-xs px-1.5 py-0.5 rounded badge-{{ $fu->status }}">{{ $fu->status }}</span>
                                </div>
                                <p class="text-xs text-gray-500">Dispatch:
                                    {{ \Carbon\Carbon::parse($fu->dispatch_at)->format('d M Y H:i') }}</p>
                            </div>
                            @if($fu->sent_at)
                                <p class="text-xs text-emerald-400">Sent {{ \Carbon\Carbon::parse($fu->sent_at)->diffForHumans() }}</p>
                            @else
                                <p class="text-xs text-violet-400">{{ \Carbon\Carbon::parse($fu->dispatch_at)->diffForHumans() }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
@endsection