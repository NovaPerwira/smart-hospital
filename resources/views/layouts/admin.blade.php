<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Smart Clinic</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: #0a0f1e;
        }

        /* Glassmorphism */
        .glass {
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .glass-hover:hover {
            background: rgba(255, 255, 255, 0.07);
            border-color: rgba(255, 255, 255, 0.14);
            transition: all 0.2s ease;
        }

        /* Gradient text */
        .grad-text {
            background: linear-gradient(135deg, #38bdf8, #818cf8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Sidebar active */
        .nav-active {
            background: linear-gradient(135deg, rgba(56, 189, 248, 0.15), rgba(129, 140, 248, 0.15));
            border-left: 3px solid #38bdf8;
        }

        /* Stat card glow */
        .glow-cyan {
            box-shadow: 0 0 30px rgba(56, 189, 248, 0.15);
        }

        .glow-violet {
            box-shadow: 0 0 30px rgba(139, 92, 246, 0.15);
        }

        .glow-emerald {
            box-shadow: 0 0 30px rgba(52, 211, 153, 0.15);
        }

        .glow-rose {
            box-shadow: 0 0 30px rgba(251, 113, 133, 0.15);
        }

        .glow-amber {
            box-shadow: 0 0 30px rgba(251, 191, 36, 0.15);
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #0a0f1e;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
        }

        /* Pulse dot */
        @keyframes pulse-dot {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.5;
                transform: scale(1.3);
            }
        }

        .pulse-dot {
            animation: pulse-dot 2s infinite;
        }

        /* Status badges */
        .badge-confirmed {
            background: rgba(56, 189, 248, 0.15);
            color: #38bdf8;
            border: 1px solid rgba(56, 189, 248, 0.3);
        }

        .badge-completed {
            background: rgba(52, 211, 153, 0.15);
            color: #34d399;
            border: 1px solid rgba(52, 211, 153, 0.3);
        }

        .badge-cancelled {
            background: rgba(251, 113, 133, 0.15);
            color: #fb7185;
            border: 1px solid rgba(251, 113, 133, 0.3);
        }

        .badge-no_show {
            background: rgba(251, 191, 36, 0.15);
            color: #fbbf24;
            border: 1px solid rgba(251, 191, 36, 0.3);
        }

        .badge-pending {
            background: rgba(167, 139, 250, 0.15);
            color: #a78bfa;
            border: 1px solid rgba(167, 139, 250, 0.3);
        }

        .badge-sent {
            background: rgba(52, 211, 153, 0.15);
            color: #34d399;
            border: 1px solid rgba(52, 211, 153, 0.3);
        }

        .badge-failed {
            background: rgba(251, 113, 133, 0.15);
            color: #fb7185;
            border: 1px solid rgba(251, 113, 133, 0.3);
        }

        /* Table rows */
        .table-row:hover {
            background: rgba(255, 255, 255, 0.03);
        }

        /* Animate in */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-up {
            animation: fadeUp 0.4s ease forwards;
        }

        .fade-up-1 {
            animation-delay: 0.05s;
            opacity: 0;
        }

        .fade-up-2 {
            animation-delay: 0.10s;
            opacity: 0;
        }

        .fade-up-3 {
            animation-delay: 0.15s;
            opacity: 0;
        }

        .fade-up-4 {
            animation-delay: 0.20s;
            opacity: 0;
        }

        .fade-up-5 {
            animation-delay: 0.25s;
            opacity: 0;
        }
    </style>
</head>

<body class="h-full text-gray-100" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">

        {{-- ── SIDEBAR ─────────────────────────────────────────────────────── --}}
        <aside class="w-64 flex-shrink-0 glass border-r border-white/5 flex flex-col z-30"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
            style="transition: transform 0.3s ease;">

            {{-- Logo --}}
            <div class="px-6 py-5 border-b border-white/5">
                <div class="flex items-center gap-3">
                    <div
                        class="w-9 h-9 rounded-xl bg-gradient-to-br from-cyan-400 to-violet-500 flex items-center justify-center text-lg">
                        🏥</div>
                    <div>
                        <p class="font-bold text-white text-sm">Smart Clinic</p>
                        <p class="text-xs text-gray-500">Automation System</p>
                    </div>
                </div>
            </div>

            {{-- Nav --}}
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                @php
                    $navItems = [
                        ['route' => 'admin.dashboard', 'icon' => '📊', 'label' => 'Dashboard'],
                        ['route' => 'admin.bookings', 'icon' => '📅', 'label' => 'Bookings'],
                        ['route' => 'admin.follow-up-rules', 'icon' => '🔁', 'label' => 'Follow-Up Rules'],
                        ['route' => 'admin.scheduled-follow-ups', 'icon' => '⏰', 'label' => 'Scheduled Follow-Ups'],
                        ['route' => 'admin.notification-logs', 'icon' => '📨', 'label' => 'Notification Logs'],
                        ['route' => 'admin.reports', 'icon' => '📈', 'label' => 'Daily Reports'],
                        ['route' => 'admin.cms.index', 'icon' => '✏️', 'label' => 'CMS / Content'],
                    ];
                @endphp

                @foreach($navItems as $item)
                    <a href="{{ route($item['route']) }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 glass-hover
                                      {{ request()->routeIs($item['route']) ? 'nav-active text-cyan-400' : 'text-gray-400 hover:text-white' }}">
                        <span class="text-base">{{ $item['icon'] }}</span>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            {{-- Footer --}}
            <div class="px-4 py-4 border-t border-white/5">
                <a href="{{ route('bookings.create') }}"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs text-gray-500 hover:text-cyan-400 transition-colors">
                    <span>🔗</span> Patient Booking Form
                </a>
            </div>
        </aside>

        {{-- ── MAIN ────────────────────────────────────────────────────────── --}}
        <div class="flex-1 flex flex-col overflow-hidden">

            {{-- Topbar --}}
            <header class="glass border-b border-white/5 px-6 py-4 flex items-center justify-between flex-shrink-0">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-gray-400 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div>
                        <h1 class="text-lg font-bold text-white">@yield('title', 'Dashboard')</h1>
                        <p class="text-xs text-gray-500">{{ now('Asia/Jakarta')->format('l, d F Y · H:i') }} WIB</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2 px-3 py-1.5 glass rounded-full text-xs text-gray-400">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 pulse-dot"></span>
                        System Online
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-2 text-gray-400 hover:text-red-400 transition-colors"
                            title="Logout">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>

            </header>

            {{-- Flash messages --}}
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                    class="mx-6 mt-4 px-4 py-3 rounded-lg bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm flex items-center justify-between">
                    <span>{{ session('success') }}</span>
                    <button @click="show = false" class="text-emerald-600 hover:text-emerald-400">✕</button>
                </div>
            @endif

            {{-- Content --}}
            <main class="flex-1 overflow-y-auto p-6">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>

</html>