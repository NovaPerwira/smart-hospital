@extends('layouts.admin')
@section('title', 'Notification Logs')

@section('content')
    <div class="space-y-5 fade-up">

        {{-- Filters --}}
        <div class="glass rounded-2xl p-4">
            <form method="GET" action="{{ route('admin.notification-logs') }}" class="flex flex-wrap gap-3 items-end">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Type</label>
                    <select name="type"
                        class="bg-white/5 border border-white/10 rounded-xl px-3 py-2 text-sm text-white focus:outline-none focus:border-cyan-500/50">
                        <option value="">All Types</option>
                        @foreach(['confirmation', 'reminder', 'follow_up', 'no_show'] as $t)
                            <option value="{{ $t }}" {{ request('type') === $t ? 'selected' : '' }}>
                                {{ str_replace('_', ' ', ucfirst($t)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Status</label>
                    <select name="status"
                        class="bg-white/5 border border-white/10 rounded-xl px-3 py-2 text-sm text-white focus:outline-none focus:border-cyan-500/50">
                        <option value="">All</option>
                        <option value="sent" {{ request('status') === 'sent' ? 'selected' : '' }}>Sent</option>
                        <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
                <button type="submit"
                    class="px-4 py-2 bg-cyan-500/20 border border-cyan-500/30 text-cyan-400 rounded-xl text-sm hover:bg-cyan-500/30 transition-colors">
                    🔍 Filter
                </button>
                @if(request()->hasAny(['type', 'status']))
                    <a href="{{ route('admin.notification-logs') }}" class="px-3 py-2 text-gray-500 hover:text-white text-sm">✕
                        Clear</a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="glass rounded-2xl overflow-hidden">
            <div class="px-5 py-4 border-b border-white/5">
                <h2 class="text-sm font-semibold text-gray-300">{{ $logs->total() }} notification(s)</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-white/5 text-xs text-gray-500 uppercase tracking-wider">
                            <th class="px-5 py-3 text-left">Type</th>
                            <th class="px-5 py-3 text-left">Booking</th>
                            <th class="px-5 py-3 text-left">Recipient</th>
                            <th class="px-5 py-3 text-left">Message</th>
                            <th class="px-5 py-3 text-left">Status</th>
                            <th class="px-5 py-3 text-left">Sent At</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($logs as $log)
                            <tr class="table-row transition-colors">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-2">
                                        <span class="text-base">
                                            {{ $log->type === 'confirmation' ? '✅' : ($log->type === 'reminder' ? '⏰' : ($log->type === 'follow_up' ? '🔁' : '📨')) }}
                                        </span>
                                        <div>
                                            <p class="text-xs font-medium text-white capitalize">
                                                {{ str_replace('_', ' ', $log->type) }}</p>
                                            <p class="text-xs text-gray-600">{{ $log->channel }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5">
                                    @if($log->booking)
                                        <a href="{{ route('admin.booking-detail', $log->booking) }}"
                                            class="font-mono text-xs text-cyan-400 hover:text-cyan-300">{{ $log->booking->booking_code }}</a>
                                    @else
                                        <span class="text-gray-600 text-xs">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-xs text-gray-400 font-mono">{{ $log->recipient }}</td>
                                <td class="px-5 py-3.5 max-w-xs">
                                    <p class="text-xs text-gray-400 truncate">{{ Str::limit($log->message, 80) }}</p>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span
                                        class="text-xs px-2 py-0.5 rounded-full badge-{{ $log->status }}">{{ $log->status }}</span>
                                </td>
                                <td class="px-5 py-3.5 text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($log->created_at)->format('d M H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-16 text-center text-gray-600">
                                    <p class="text-4xl mb-3">📭</p>
                                    <p>No notifications logged yet</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($logs->hasPages())
                <div class="px-5 py-4 border-t border-white/5">
                    {{ $logs->links('vendor.pagination.simple-tailwind') }}
                </div>
            @endif
        </div>
    </div>
@endsection