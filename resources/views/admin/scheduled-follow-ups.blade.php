@extends('layouts.admin')
@section('title', 'Scheduled Follow-Ups')

@section('content')
    <div class="space-y-5 fade-up">

        {{-- Filters --}}
        <div class="glass rounded-2xl p-4">
            <form method="GET" action="{{ route('admin.scheduled-follow-ups') }}" class="flex flex-wrap gap-3 items-end">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Status</label>
                    <select name="status"
                        class="bg-white/5 border border-white/10 rounded-xl px-3 py-2 text-sm text-white focus:outline-none focus:border-violet-500/50">
                        <option value="">All</option>
                        @foreach(['pending', 'sent', 'failed'] as $s)
                            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit"
                    class="px-4 py-2 bg-violet-500/20 border border-violet-500/30 text-violet-400 rounded-xl text-sm hover:bg-violet-500/30 transition-colors">
                    🔍 Filter
                </button>
                @if(request('status'))
                    <a href="{{ route('admin.scheduled-follow-ups') }}"
                        class="px-3 py-2 text-gray-500 hover:text-white text-sm">✕ Clear</a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="glass rounded-2xl overflow-hidden">
            <div class="px-5 py-4 border-b border-white/5">
                <h2 class="text-sm font-semibold text-gray-300">{{ $followUps->total() }} follow-up(s)</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-white/5 text-xs text-gray-500 uppercase tracking-wider">
                            <th class="px-5 py-3 text-left">Patient</th>
                            <th class="px-5 py-3 text-left">Treatment Rule</th>
                            <th class="px-5 py-3 text-left">Channel</th>
                            <th class="px-5 py-3 text-left">Dispatch At</th>
                            <th class="px-5 py-3 text-left">Status</th>
                            <th class="px-5 py-3 text-left">Sent At</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($followUps as $fu)
                            <tr class="table-row transition-colors">
                                <td class="px-5 py-3.5">
                                    <p class="text-sm font-medium text-white">{{ $fu->booking->patient_name }}</p>
                                    <a href="{{ route('admin.booking-detail', $fu->booking) }}"
                                        class="text-xs text-cyan-400 font-mono hover:text-cyan-300">{{ $fu->booking->booking_code }}</a>
                                </td>
                                <td class="px-5 py-3.5">
                                    <p class="text-xs text-white">{{ $fu->rule?->treatment?->name ?? '—' }}</p>
                                    @if($fu->rule)
                                        <p class="text-xs text-gray-500">+{{ $fu->rule->interval_value }}
                                            {{ $fu->rule->interval_unit }}</p>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5">
                                    <span
                                        class="text-xs px-2 py-0.5 rounded-full bg-cyan-500/10 text-cyan-400 border border-cyan-500/20">
                                        {{ $fu->channel }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <p class="text-xs text-white">
                                        {{ \Carbon\Carbon::parse($fu->dispatch_at)->format('d M Y H:i') }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($fu->dispatch_at)->diffForHumans() }}</p>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span
                                        class="text-xs px-2.5 py-1 rounded-full badge-{{ $fu->status }}">{{ $fu->status }}</span>
                                </td>
                                <td class="px-5 py-3.5 text-xs text-gray-500">
                                    {{ $fu->sent_at ? \Carbon\Carbon::parse($fu->sent_at)->format('d M H:i') : '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-16 text-center text-gray-600">
                                    <p class="text-4xl mb-3">🔁</p>
                                    <p>No scheduled follow-ups yet</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($followUps->hasPages())
                <div class="px-5 py-4 border-t border-white/5">
                    {{ $followUps->links('vendor.pagination.simple-tailwind') }}
                </div>
            @endif
        </div>
    </div>
@endsection