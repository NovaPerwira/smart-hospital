@extends('layouts.admin')
@section('title', 'Report — ' . \Carbon\Carbon::parse($report->report_date)->format('d M Y'))

@section('content')
    @php $d = $report->data ?? []; @endphp
    <div class="max-w-3xl mx-auto space-y-5 fade-up">

        <a href="{{ route('admin.reports') }}"
            class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-white transition-colors">
            ← Back to Reports
        </a>

        {{-- Header --}}
        <div class="glass rounded-2xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-white">Daily Report</h2>
                    <p class="text-gray-500 text-sm">{{ \Carbon\Carbon::parse($report->report_date)->format('l, d F Y') }}
                    </p>
                </div>
                <span class="text-sm px-3 py-1.5 rounded-xl badge-{{ $report->telegram_status }}">
                    📨 Telegram: {{ $report->telegram_status }}
                    @if($report->telegram_sent_at)
                        <span
                            class="text-xs opacity-70 ml-1">{{ \Carbon\Carbon::parse($report->telegram_sent_at)->format('H:i') }}</span>
                    @endif
                </span>
            </div>
        </div>

        {{-- KPI Grid --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @php
                $kpis = [
                    ['label' => 'Total Bookings', 'value' => $d['total_bookings'] ?? 0, 'color' => 'text-cyan-400', 'icon' => '📅'],
                    ['label' => 'Completed', 'value' => $d['completed'] ?? 0, 'color' => 'text-emerald-400', 'icon' => '✅'],
                    ['label' => 'No-Show Rate', 'value' => ($d['no_show_rate'] ?? 0) . '%', 'color' => 'text-rose-400', 'icon' => '👻'],
                    ['label' => 'Revenue', 'value' => 'Rp ' . number_format($d['revenue_today'] ?? 0, 0, ',', '.'), 'color' => 'text-emerald-400', 'icon' => '💰'],
                ];
            @endphp
            @foreach($kpis as $kpi)
                <div class="glass rounded-2xl p-4 text-center">
                    <p class="text-2xl mb-2">{{ $kpi['icon'] }}</p>
                    <p class="text-xl font-bold {{ $kpi['color'] }}">{{ $kpi['value'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $kpi['label'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- Booking Status Breakdown --}}
        <div class="glass rounded-2xl p-5">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Booking Status Breakdown</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                @foreach(['confirmed' => 'cyan', 'completed' => 'emerald', 'cancelled' => 'rose', 'no_show' => 'amber'] as $status => $color)
                    <div class="text-center p-3 rounded-xl bg-{{ $color }}-500/5 border border-{{ $color }}-500/10">
                        <p class="text-2xl font-bold text-{{ $color }}-400">{{ $d['bookings_by_status'][$status] ?? 0 }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ str_replace('_', ' ', ucfirst($status)) }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Revenue by Treatment --}}
        @if(!empty($d['revenue_by_treatment']))
            <div class="glass rounded-2xl p-5">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Revenue by Treatment</h3>
                <div class="space-y-3">
                    @php $maxRev = collect($d['revenue_by_treatment'])->max('total') ?: 1; @endphp
                    @foreach($d['revenue_by_treatment'] as $item)
                        <div>
                            <div class="flex justify-between text-xs mb-1">
                                <span class="text-gray-400">{{ $item['treatment'] }}</span>
                                <span class="text-emerald-400 font-semibold">Rp
                                    {{ number_format($item['total'], 0, ',', '.') }}</span>
                            </div>
                            <div class="h-1.5 bg-white/5 rounded-full overflow-hidden">
                                <div class="bg-emerald-400 h-full rounded-full"
                                    style="width: {{ round(($item['total'] / $maxRev) * 100) }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Tomorrow Slots --}}
        <div class="glass rounded-2xl p-5">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Tomorrow's Availability</h3>
            <div class="grid grid-cols-2 gap-4 text-center">
                <div class="p-4 rounded-xl bg-emerald-500/5 border border-emerald-500/10">
                    <p class="text-3xl font-bold text-emerald-400">{{ $d['empty_slots_tomorrow'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">🟢 Empty Slots</p>
                </div>
                <div class="p-4 rounded-xl bg-rose-500/5 border border-rose-500/10">
                    <p class="text-3xl font-bold text-rose-400">{{ $d['booked_slots_tomorrow'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">🔴 Booked Slots</p>
                </div>
            </div>
        </div>

        {{-- Telegram Preview --}}
        <div class="glass rounded-2xl p-5">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Telegram Message Preview</h3>
            <div class="bg-black/30 rounded-xl p-4 font-mono text-xs text-gray-300 whitespace-pre-wrap leading-relaxed">📊
                *Daily Clinic Report — {{ \Carbon\Carbon::parse($report->report_date)->format('d M Y') }}*
                ━━━━━━━━━━━━━━━━━━━━━━━━

                📅 *Bookings Today*
                ✅ Confirmed : {{ $d['confirmed'] ?? 0 }}
                ✔️ Completed : {{ $d['completed'] ?? 0 }}
                ❌ Cancelled : {{ $d['cancelled'] ?? 0 }}
                👻 No-Show : {{ $d['no_show'] ?? 0 }} ({{ $d['no_show_rate'] ?? 0 }}%)
                📋 Total : {{ $d['total_bookings'] ?? 0 }}

                💰 *Revenue Today*
                Total: Rp {{ number_format($d['revenue_today'] ?? 0, 0, ',', '.') }}

                🗓️ *Tomorrow's Availability*
                🟢 Empty Slots : {{ $d['empty_slots_tomorrow'] ?? 0 }}
                🔴 Booked Slots: {{ $d['booked_slots_tomorrow'] ?? 0 }}

                ━━━━━━━━━━━━━━━━━━━━━━━━
                🤖 _Auto-generated at 21:00 WIB_</div>
        </div>

    </div>
@endsection