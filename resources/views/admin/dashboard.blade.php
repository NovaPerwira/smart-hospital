@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
    <div class="space-y-6">

        {{-- ── KPI STAT CARDS ──────────────────────────────────────────────── --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

            {{-- Total Today --}}
            <div class="glass glow-cyan rounded-2xl p-5 fade-up fade-up-1">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-cyan-500/10 flex items-center justify-center text-xl">📅</div>
                    <span class="text-xs text-cyan-400 font-medium px-2 py-0.5 bg-cyan-500/10 rounded-full">Today</span>
                </div>
                <p class="text-3xl font-bold text-white">{{ $stats['total_today'] }}</p>
                <p class="text-sm text-gray-500 mt-1">Total Bookings</p>
            </div>

            {{-- Revenue --}}
            <div class="glass glow-emerald rounded-2xl p-5 fade-up fade-up-2">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center text-xl">💰</div>
                    <span
                        class="text-xs text-emerald-400 font-medium px-2 py-0.5 bg-emerald-500/10 rounded-full">Revenue</span>
                </div>
                <p class="text-3xl font-bold text-white">Rp {{ number_format($stats['revenue_today'], 0, ',', '.') }}</p>
                <p class="text-sm text-gray-500 mt-1">Today's Revenue</p>
            </div>

            {{-- No-Shows --}}
            <div class="glass glow-rose rounded-2xl p-5 fade-up fade-up-3">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-rose-500/10 flex items-center justify-center text-xl">👻</div>
                    <span class="text-xs text-rose-400 font-medium px-2 py-0.5 bg-rose-500/10 rounded-full">No-Show</span>
                </div>
                <p class="text-3xl font-bold text-white">{{ $stats['no_show'] }}</p>
                <p class="text-sm text-gray-500 mt-1">No-Shows Today</p>
            </div>

            {{-- Pending Follow-Ups --}}
            <div class="glass glow-violet rounded-2xl p-5 fade-up fade-up-4">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-violet-500/10 flex items-center justify-center text-xl">🔁</div>
                    <span class="text-xs text-violet-400 font-medium px-2 py-0.5 bg-violet-500/10 rounded-full">Queue</span>
                </div>
                <p class="text-3xl font-bold text-white">{{ $stats['pending_followups'] }}</p>
                <p class="text-sm text-gray-500 mt-1">Pending Follow-Ups</p>
            </div>
        </div>

        {{-- ── STATUS BREAKDOWN + CHART ─────────────────────────────────────── --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

            {{-- Status breakdown --}}
            <div class="glass rounded-2xl p-5 fade-up fade-up-2">
                <h2 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Today's Status</h2>
                <div class="space-y-3">
                    @php
                        $statusItems = [
                            ['key' => 'confirmed', 'label' => 'Confirmed', 'color' => 'bg-cyan-400', 'val' => $stats['confirmed']],
                            ['key' => 'completed', 'label' => 'Completed', 'color' => 'bg-emerald-400', 'val' => $stats['completed']],
                            ['key' => 'cancelled', 'label' => 'Cancelled', 'color' => 'bg-rose-400', 'val' => $stats['cancelled']],
                            ['key' => 'no_show', 'label' => 'No-Show', 'color' => 'bg-amber-400', 'val' => $stats['no_show']],
                        ];
                        $total = max($stats['total_today'], 1);
                    @endphp
                    @foreach($statusItems as $item)
                        <div>
                            <div class="flex justify-between text-xs mb-1">
                                <span class="text-gray-400">{{ $item['label'] }}</span>
                                <span class="text-white font-semibold">{{ $item['val'] }}</span>
                            </div>
                            <div class="h-1.5 bg-white/5 rounded-full overflow-hidden">
                                <div class="{{ $item['color'] }} h-full rounded-full transition-all duration-700"
                                    style="width: {{ round(($item['val'] / $total) * 100) }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-5 pt-4 border-t border-white/5 grid grid-cols-2 gap-3 text-center">
                    <div class="glass rounded-xl p-3">
                        <p class="text-lg font-bold text-white">{{ $stats['notifications_sent'] }}</p>
                        <p class="text-xs text-gray-500">Notifications Sent</p>
                    </div>
                    <div class="glass rounded-xl p-3">
                        <p class="text-lg font-bold text-white">{{ $stats['pending_followups'] }}</p>
                        <p class="text-xs text-gray-500">Queued Follow-Ups</p>
                    </div>
                </div>
            </div>

            {{-- 7-day chart --}}
            <div class="glass rounded-2xl p-5 lg:col-span-2 fade-up fade-up-3">
                <h2 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">7-Day Booking Trend</h2>
                <canvas id="weeklyChart" height="120"></canvas>
            </div>
        </div>

        {{-- ── RECENT BOOKINGS + UPCOMING FOLLOW-UPS ───────────────────────── --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

            {{-- Recent Bookings --}}
            <div class="glass rounded-2xl p-5 fade-up fade-up-3">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Today's Bookings</h2>
                    <a href="{{ route('admin.bookings') }}" class="text-xs text-cyan-400 hover:text-cyan-300">View all →</a>
                </div>
                <div class="space-y-2">
                    @forelse($recentBookings as $booking)
                        <a href="{{ route('admin.booking-detail', $booking) }}"
                            class="flex items-center gap-3 p-3 rounded-xl glass-hover cursor-pointer transition-all">
                            <div
                                class="w-9 h-9 rounded-full bg-gradient-to-br from-cyan-500/20 to-violet-500/20 flex items-center justify-center text-sm font-bold text-cyan-400 flex-shrink-0">
                                {{ strtoupper(substr($booking->patient_name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-white truncate">{{ $booking->patient_name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $booking->treatment->name }} ·
                                    {{ $booking->doctor->name }}</p>
                            </div>
                            <div class="flex flex-col items-end gap-1">
                                <span class="text-xs font-mono text-gray-500">
                                    {{ $booking->slot ? \Carbon\Carbon::parse($booking->slot->start_time)->format('H:i') : '--' }}
                                </span>
                                <span class="text-xs px-2 py-0.5 rounded-full badge-{{ $booking->status }}">
                                    {{ $booking->status }}
                                </span>
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-8 text-gray-600">
                            <p class="text-3xl mb-2">📭</p>
                            <p class="text-sm">No bookings today</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Upcoming Follow-Ups --}}
            <div class="glass rounded-2xl p-5 fade-up fade-up-4">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Upcoming Follow-Ups</h2>
                    <a href="{{ route('admin.scheduled-follow-ups') }}"
                        class="text-xs text-violet-400 hover:text-violet-300">View all →</a>
                </div>
                <div class="space-y-2">
                    @forelse($upcomingFollowUps as $fu)
                        <div class="flex items-center gap-3 p-3 rounded-xl glass">
                            <div
                                class="w-9 h-9 rounded-full bg-violet-500/10 flex items-center justify-center text-sm flex-shrink-0">
                                🔁</div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-white truncate">{{ $fu->booking->patient_name }}</p>
                                <p class="text-xs text-gray-500">{{ $fu->channel }} · {{ $fu->booking->booking_code }}</p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <p class="text-xs text-violet-400 font-medium">
                                    {{ \Carbon\Carbon::parse($fu->dispatch_at)->diffForHumans() }}</p>
                                <p class="text-xs text-gray-600">{{ \Carbon\Carbon::parse($fu->dispatch_at)->format('d M') }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-600">
                            <p class="text-3xl mb-2">✅</p>
                            <p class="text-sm">No pending follow-ups</p>
                        </div>
                    @endforelse
                </div>

                {{-- Latest Report Card --}}
                @if($latestReport)
                    <div class="mt-4 pt-4 border-t border-white/5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-gray-500">Latest Daily Report</p>
                                <p class="text-sm font-semibold text-white">
                                    {{ \Carbon\Carbon::parse($latestReport->report_date)->format('d M Y') }}</p>
                            </div>
                            <span class="text-xs px-2 py-1 rounded-full badge-{{ $latestReport->telegram_status }}">
                                📨 {{ $latestReport->telegram_status }}
                            </span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- ── AUTOMATION PIPELINE STATUS ───────────────────────────────────── --}}
        <div class="glass rounded-2xl p-5 fade-up fade-up-5">
            <h2 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Automation Pipeline</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                @php
                    $pipeline = [
                        ['icon' => '✅', 'label' => 'Phase 1', 'desc' => 'Instant Confirmation', 'color' => 'text-cyan-400', 'bg' => 'bg-cyan-500/10'],
                        ['icon' => '⏰', 'label' => 'Phase 2', 'desc' => 'H-1 Reminder (08:00)', 'color' => 'text-violet-400', 'bg' => 'bg-violet-500/10'],
                        ['icon' => '🔗', 'label' => 'Phase 3', 'desc' => 'Patient Response', 'color' => 'text-amber-400', 'bg' => 'bg-amber-500/10'],
                        ['icon' => '👻', 'label' => 'Phase 4', 'desc' => 'No-Show (T+2h)', 'color' => 'text-rose-400', 'bg' => 'bg-rose-500/10'],
                    ];
                @endphp
                @foreach($pipeline as $p)
                    <div class="rounded-xl {{ $p['bg'] }} p-4 text-center">
                        <p class="text-2xl mb-2">{{ $p['icon'] }}</p>
                        <p class="text-xs font-bold {{ $p['color'] }}">{{ $p['label'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $p['desc'] }}</p>
                        <div class="mt-2 flex items-center justify-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 pulse-dot"></span>
                            <span class="text-xs text-emerald-400">Active</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        const weeklyData = @json($weeklyBookings);
        const labels = weeklyData.map(d => {
            const date = new Date(d.date);
            return date.toLocaleDateString('en', { weekday: 'short', day: 'numeric' });
        });

        const ctx = document.getElementById('weeklyChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels,
                datasets: [
                    {
                        label: 'Total',
                        data: weeklyData.map(d => d.total),
                        backgroundColor: 'rgba(56,189,248,0.2)',
                        borderColor: '#38bdf8',
                        borderWidth: 2,
                        borderRadius: 6,
                    },
                    {
                        label: 'Completed',
                        data: weeklyData.map(d => d.completed),
                        backgroundColor: 'rgba(52,211,153,0.2)',
                        borderColor: '#34d399',
                        borderWidth: 2,
                        borderRadius: 6,
                    },
                    {
                        label: 'No-Show',
                        data: weeklyData.map(d => d.no_show),
                        backgroundColor: 'rgba(251,113,133,0.2)',
                        borderColor: '#fb7185',
                        borderWidth: 2,
                        borderRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { labels: { color: '#9ca3af', font: { size: 11 } } }
                },
                scales: {
                    x: { ticks: { color: '#6b7280' }, grid: { color: 'rgba(255,255,255,0.03)' } },
                    y: { ticks: { color: '#6b7280', stepSize: 1 }, grid: { color: 'rgba(255,255,255,0.05)' }, beginAtZero: true }
                }
            }
        });
    </script>
@endpush