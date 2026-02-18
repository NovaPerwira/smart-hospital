@extends('layouts.admin')
@section('title', 'Bookings')

@section('content')
    <div class="space-y-5 fade-up">

        {{-- ── FILTERS ──────────────────────────────────────────────────────── --}}
        <div class="glass rounded-2xl p-4">
            <form method="GET" action="{{ route('admin.bookings') }}" class="flex flex-wrap gap-3 items-end">
                <div class="flex-1 min-w-36">
                    <label class="block text-xs text-gray-500 mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, code, phone..."
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-3 py-2 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-cyan-500/50">
                </div>
                <div class="min-w-36">
                    <label class="block text-xs text-gray-500 mb-1">Status</label>
                    <select name="status"
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-3 py-2 text-sm text-white focus:outline-none focus:border-cyan-500/50">
                        <option value="">All Statuses</option>
                        @foreach(['confirmed', 'completed', 'cancelled', 'no_show'] as $s)
                            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="min-w-40">
                    <label class="block text-xs text-gray-500 mb-1">Date</label>
                    <input type="date" name="date" value="{{ request('date') }}"
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-3 py-2 text-sm text-white focus:outline-none focus:border-cyan-500/50">
                </div>
                <button type="submit"
                    class="px-5 py-2 bg-cyan-500/20 border border-cyan-500/30 text-cyan-400 rounded-xl text-sm font-medium hover:bg-cyan-500/30 transition-colors">
                    🔍 Filter
                </button>
                @if(request()->hasAny(['search', 'status', 'date']))
                    <a href="{{ route('admin.bookings') }}"
                        class="px-4 py-2 text-gray-500 hover:text-white text-sm transition-colors">✕ Clear</a>
                @endif
            </form>
        </div>

        {{-- ── TABLE ────────────────────────────────────────────────────────── --}}
        <div class="glass rounded-2xl overflow-hidden">
            <div class="px-5 py-4 border-b border-white/5 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-gray-300">
                    {{ $bookings->total() }} booking(s) found
                </h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-white/5 text-xs text-gray-500 uppercase tracking-wider">
                            <th class="px-5 py-3 text-left">Patient</th>
                            <th class="px-5 py-3 text-left">Code</th>
                            <th class="px-5 py-3 text-left">Treatment / Doctor</th>
                            <th class="px-5 py-3 text-left">Date & Time</th>
                            <th class="px-5 py-3 text-left">Status</th>
                            <th class="px-5 py-3 text-left">Phases</th>
                            <th class="px-5 py-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($bookings as $booking)
                            <tr class="table-row transition-colors">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-full bg-gradient-to-br from-cyan-500/20 to-violet-500/20 flex items-center justify-center text-xs font-bold text-cyan-400">
                                            {{ strtoupper(substr($booking->patient_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-white">{{ $booking->patient_name }}</p>
                                            <p class="text-xs text-gray-500">{{ $booking->patient_phone }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span
                                        class="font-mono text-xs text-cyan-400 bg-cyan-500/10 px-2 py-1 rounded-lg">{{ $booking->booking_code }}</span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <p class="text-white text-xs font-medium">{{ $booking->treatment->name }}</p>
                                    <p class="text-gray-500 text-xs">{{ $booking->doctor->name }}</p>
                                </td>
                                <td class="px-5 py-3.5">
                                    <p class="text-white text-xs">
                                        {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</p>
                                    <p class="text-gray-500 text-xs">
                                        {{ $booking->slot ? \Carbon\Carbon::parse($booking->slot->start_time)->format('H:i') : '—' }}
                                    </p>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="text-xs px-2.5 py-1 rounded-full font-medium badge-{{ $booking->status }}">
                                        {{ str_replace('_', ' ', ucfirst($booking->status)) }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex gap-1">
                                        <span title="Confirmed"
                                            class="text-sm {{ $booking->confirmed_at ? 'opacity-100' : 'opacity-20' }}">✅</span>
                                        <span title="Reminder Sent"
                                            class="text-sm {{ $booking->reminder_sent_at ? 'opacity-100' : 'opacity-20' }}">⏰</span>
                                        <span title="Arrived"
                                            class="text-sm {{ $booking->arrived_at ? 'opacity-100' : 'opacity-20' }}">🏥</span>
                                        <span title="Completed"
                                            class="text-sm {{ $booking->completed_at ? 'opacity-100' : 'opacity-20' }}">🏁</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.booking-detail', $booking) }}"
                                            class="text-xs px-2.5 py-1 glass rounded-lg text-gray-400 hover:text-white transition-colors">
                                            View
                                        </a>
                                        @if($booking->status === 'confirmed' && !$booking->arrived_at)
                                            <form method="POST" action="{{ route('admin.booking-arrived', $booking) }}">
                                                @csrf
                                                <button type="submit"
                                                    class="text-xs px-2.5 py-1 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-lg hover:bg-emerald-500/20 transition-colors">
                                                    Arrived
                                                </button>
                                            </form>
                                        @endif
                                        @if(in_array($booking->status, ['confirmed']) && $booking->arrived_at && !$booking->completed_at)
                                            <form method="POST" action="{{ route('admin.booking-complete', $booking) }}">
                                                @csrf
                                                <button type="submit"
                                                    class="text-xs px-2.5 py-1 bg-violet-500/10 border border-violet-500/20 text-violet-400 rounded-lg hover:bg-violet-500/20 transition-colors">
                                                    Complete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-16 text-center text-gray-600">
                                    <p class="text-4xl mb-3">📭</p>
                                    <p>No bookings found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($bookings->hasPages())
                <div class="px-5 py-4 border-t border-white/5">
                    {{ $bookings->links('vendor.pagination.simple-tailwind') }}
                </div>
            @endif
        </div>
    </div>
@endsection