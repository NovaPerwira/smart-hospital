@extends('layouts.admin')
@section('title', 'Daily Reports')

@section('content')
    <div class="space-y-5 fade-up">

        <div class="glass rounded-2xl overflow-hidden">
            <div class="px-5 py-4 border-b border-white/5">
                <h2 class="text-sm font-semibold text-gray-300">Daily Report History</h2>
                <p class="text-xs text-gray-600 mt-0.5">KPI snapshots generated at 21:00 WIB daily</p>
            </div>

            <div class="divide-y divide-white/5">
                @forelse($reports as $report)
                    @php $d = $report->data ?? []; @endphp
                    <a href="{{ route('admin.report-detail', $report) }}"
                        class="flex items-center gap-4 px-5 py-4 table-row transition-colors cursor-pointer">

                        {{-- Date --}}
                        <div class="w-16 text-center flex-shrink-0">
                            <p class="text-xl font-bold text-white">
                                {{ \Carbon\Carbon::parse($report->report_date)->format('d') }}</p>
                            <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($report->report_date)->format('M Y') }}
                            </p>
                        </div>

                        {{-- KPIs --}}
                        <div class="flex-1 grid grid-cols-2 md:grid-cols-4 gap-3">
                            <div>
                                <p class="text-xs text-gray-600">Bookings</p>
                                <p class="text-sm font-semibold text-white">{{ $d['total_bookings'] ?? '—' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600">Revenue</p>
                                <p class="text-sm font-semibold text-emerald-400">Rp
                                    {{ number_format($d['revenue_today'] ?? 0, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600">No-Show Rate</p>
                                <p
                                    class="text-sm font-semibold {{ ($d['no_show_rate'] ?? 0) > 20 ? 'text-rose-400' : 'text-amber-400' }}">
                                    {{ $d['no_show_rate'] ?? 0 }}%
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600">Empty Slots Tomorrow</p>
                                <p class="text-sm font-semibold text-cyan-400">{{ $d['empty_slots_tomorrow'] ?? '—' }}</p>
                            </div>
                        </div>

                        {{-- Telegram status --}}
                        <div class="flex-shrink-0">
                            <span class="text-xs px-2.5 py-1 rounded-full badge-{{ $report->telegram_status }}">
                                📨 {{ $report->telegram_status }}
                            </span>
                        </div>

                        <span class="text-gray-600 text-sm flex-shrink-0">→</span>
                    </a>
                @empty
                    <div class="px-5 py-16 text-center text-gray-600">
                        <p class="text-4xl mb-3">📈</p>
                        <p class="text-sm">No reports generated yet.</p>
                        <p class="text-xs mt-2 text-gray-700">Run <code
                                class="bg-white/5 px-1 rounded">php artisan report:daily</code> to generate one.</p>
                    </div>
                @endforelse
            </div>

            @if($reports->hasPages())
                <div class="px-5 py-4 border-t border-white/5">
                    {{ $reports->links('vendor.pagination.simple-tailwind') }}
                </div>
            @endif
        </div>
    </div>
@endsection