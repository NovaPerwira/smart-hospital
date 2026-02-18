<!DOCTYPE html>
<html lang="{{ $locale }}" x-data="app()" :class="{ 'dark': dark }" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $cms['hero_subtitle'] ?? 'Smart Clinic — World-class healthcare.' }}">
    <title>Smart Clinic — {{ $cms['hero_title_1'] ?? 'Your Health' }} {{ $cms['hero_title_2'] ?? 'Our Priority' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: { 50:'#eff6ff', 100:'#dbeafe', 400:'#60a5fa', 500:'#3b82f6', 600:'#2563eb', 700:'#1d4ed8', 900:'#1e3a8a' },
                        teal:  { 400:'#2dd4bf', 500:'#14b8a6', 600:'#0d9488' },
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'fade-up': 'fadeUp 0.6s ease forwards',
                    },
                    keyframes: {
                        float:  { '0%,100%': { transform: 'translateY(0px)' }, '50%': { transform: 'translateY(-20px)' } },
                        fadeUp: { from: { opacity: '0', transform: 'translateY(24px)' }, to: { opacity: '1', transform: 'translateY(0)' } },
                    }
                }
            }
        }
    </script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        * { font-family: 'Inter', sans-serif; }

        /* Gradient mesh background */
        .mesh-bg {
            background: radial-gradient(ellipse at 20% 50%, rgba(59,130,246,0.08) 0%, transparent 60%),
                        radial-gradient(ellipse at 80% 20%, rgba(20,184,166,0.08) 0%, transparent 60%),
                        radial-gradient(ellipse at 50% 80%, rgba(139,92,246,0.05) 0%, transparent 60%);
        }
        .dark .mesh-bg {
            background: radial-gradient(ellipse at 20% 50%, rgba(59,130,246,0.12) 0%, transparent 60%),
                        radial-gradient(ellipse at 80% 20%, rgba(20,184,166,0.12) 0%, transparent 60%),
                        radial-gradient(ellipse at 50% 80%, rgba(139,92,246,0.08) 0%, transparent 60%);
        }

        /* Glass card */
        .glass-card {
            background: rgba(255,255,255,0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.8);
        }
        .dark .glass-card {
            background: rgba(15,23,42,0.7);
            border: 1px solid rgba(255,255,255,0.08);
        }

        /* Gradient text */
        .grad-blue { background: linear-gradient(135deg, #3b82f6, #14b8a6); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .grad-hero  { background: linear-gradient(135deg, #1d4ed8 0%, #0d9488 50%, #7c3aed 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        .dark ::-webkit-scrollbar-thumb { background: #334155; }

        /* Section reveal */
        [x-intersect] { opacity: 0; transform: translateY(24px); transition: all 0.6s ease; }
        [x-intersect].visible { opacity: 1; transform: translateY(0); }

        /* Nav blur */
        .nav-blur {
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0,0,0,0.06);
        }
        .dark .nav-blur {
            background: rgba(2,6,23,0.85);
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }

        /* Pulse ring */
        @keyframes ping-slow { 75%, 100% { transform: scale(1.8); opacity: 0; } }
        .ping-slow { animation: ping-slow 2.5s cubic-bezier(0,0,.2,1) infinite; }

        /* Service card hover */
        .service-card { transition: all 0.3s ease; }
        .service-card:hover { transform: translateY(-6px); }

        /* Stat counter */
        .stat-num { font-variant-numeric: tabular-nums; }
    </style>
</head>

<body class="bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 antialiased transition-colors duration-300">

{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- NAVBAR                                                                     --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<nav class="nav-blur fixed top-0 left-0 right-0 z-50 transition-all duration-300" x-data="{ mobileOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="{{ route('landing') }}" class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-500 to-teal-500 flex items-center justify-center text-white text-lg shadow-lg">🏥</div>
                <span class="font-bold text-lg text-slate-900 dark:text-white">Smart<span class="text-blue-600 dark:text-blue-400">Clinic</span></span>
            </a>

            {{-- Desktop Nav --}}
            <div class="hidden md:flex items-center gap-6">
                <a href="#services" class="text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">{{ $cms['nav_services'] ?? 'Services' }}</a>
                <a href="#why-us"   class="text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">{{ $cms['nav_doctors'] ?? 'Doctors' }}</a>
                <a href="#about"    class="text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">{{ $cms['nav_about'] ?? 'About' }}</a>
                <a href="#patient-portal" class="text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">{{ $cms['nav_portal'] ?? 'Patient Portal' }}</a>
                <a href="#contact"  class="text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">{{ $cms['nav_contact'] ?? 'Contact' }}</a>
            </div>


            {{-- Right Controls --}}
            <div class="flex items-center gap-3">

                {{-- Language Switcher --}}
                <div class="flex items-center gap-1 bg-slate-100 dark:bg-slate-800 rounded-full p-1">
                    @foreach(['en' => '🇬🇧', 'id' => '🇮🇩'] as $lang => $flag)
                    <form method="POST" action="{{ route('locale.set', $lang) }}">
                        @csrf
                        <button type="submit"
                                class="px-2.5 py-1 rounded-full text-xs font-semibold transition-all
                                       {{ $locale === $lang ? 'bg-white dark:bg-slate-700 shadow text-blue-600 dark:text-blue-400' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300' }}">
                            {{ $flag }} {{ strtoupper($lang) }}
                        </button>
                    </form>
                    @endforeach
                </div>

                {{-- Dark Mode Toggle --}}
                <button @click="dark = !dark; localStorage.setItem('dark', dark)"
                        class="w-9 h-9 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                    <span x-show="!dark" class="text-base">🌙</span>
                    <span x-show="dark"  class="text-base">☀️</span>
                </button>

                {{-- Book CTA --}}
                <a href="{{ route('bookings.create') }}"
                   class="hidden sm:inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-600 to-teal-500 text-white text-sm font-semibold rounded-full shadow-lg hover:shadow-blue-500/30 hover:scale-105 transition-all duration-200">
                    {{ $cms['nav_book_cta'] ?? 'Book Now' }}
                </a>

                {{-- Mobile menu --}}
                <button @click="mobileOpen = !mobileOpen" class="md:hidden w-9 h-9 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="mobileOpen" x-transition class="md:hidden pb-4 space-y-1">
            @foreach([
                ['#services',       $cms['nav_services'] ?? 'Services'],
                ['#why-us',         $cms['nav_doctors'] ?? 'Doctors'],
                ['#about',          $cms['nav_about'] ?? 'About'],
                ['#patient-portal', $cms['nav_portal'] ?? 'Patient Portal'],
                ['#contact',        $cms['nav_contact'] ?? 'Contact'],
            ] as [$href, $label])
            <a href="{{ $href }}" @click="mobileOpen = false"
               class="block px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg">
                {{ $label }}
            </a>
            @endforeach
            <a href="{{ route('bookings.create') }}"
               class="block mx-4 mt-2 py-2 text-center bg-gradient-to-r from-blue-600 to-teal-500 text-white text-sm font-semibold rounded-full">
                {{ $cms['nav_book_cta'] ?? 'Book Now' }}
            </a>
        </div>
    </div>
</nav>

{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- HERO SECTION                                                               --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<section id="home" class="relative min-h-screen flex items-center pt-16 overflow-hidden mesh-bg">

    {{-- Decorative blobs --}}
    <div class="absolute top-20 right-10 w-72 h-72 bg-blue-400/10 dark:bg-blue-500/10 rounded-full blur-3xl animate-pulse-slow pointer-events-none"></div>
    <div class="absolute bottom-20 left-10 w-96 h-96 bg-teal-400/10 dark:bg-teal-500/10 rounded-full blur-3xl animate-pulse-slow pointer-events-none" style="animation-delay:2s"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-violet-400/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

        {{-- Left: Text --}}
        <div class="space-y-8">

            {{-- Badge --}}
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 dark:bg-blue-950/50 border border-blue-200 dark:border-blue-800 rounded-full text-sm font-medium text-blue-700 dark:text-blue-300">
                <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                {{ $cms['hero_badge'] ?? '🏥 Trusted Healthcare Since 2010' }}
            </div>

            {{-- Headline --}}
            <div>
                <h1 class="text-5xl sm:text-6xl lg:text-7xl font-black leading-tight tracking-tight text-slate-900 dark:text-white">
                    {{ $cms['hero_title_1'] ?? 'Your Health,' }}<br>
                    <span class="grad-hero">{{ $cms['hero_title_2'] ?? 'Our Priority.' }}</span>
                </h1>
            </div>

            {{-- Subtitle --}}
            <p class="text-lg text-slate-600 dark:text-slate-400 leading-relaxed max-w-xl">
                {{ $cms['hero_subtitle'] ?? 'Experience world-class medical care with cutting-edge technology and compassionate professionals.' }}
            </p>

            {{-- CTAs --}}
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('bookings.create') }}"
                   class="group inline-flex items-center gap-2 px-7 py-3.5 bg-gradient-to-r from-blue-600 to-teal-500 text-white font-bold rounded-2xl shadow-xl shadow-blue-500/25 hover:shadow-blue-500/40 hover:scale-105 transition-all duration-200">
                    {{ $cms['hero_cta_primary'] ?? 'Book Appointment' }}
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
                <a href="#services"
                   class="inline-flex items-center gap-2 px-7 py-3.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-semibold rounded-2xl hover:border-blue-400 hover:text-blue-600 dark:hover:text-blue-400 transition-all duration-200 shadow-sm">
                    {{ $cms['hero_cta_secondary'] ?? 'Explore Services' }}
                </a>
            </div>

            {{-- Stats --}}
            <div class="flex flex-wrap gap-8 pt-4">
                @php
                    $heroStats = [
                        [$cms['hero_stat_1_num'] ?? '15,000+', $cms['hero_stat_1_label'] ?? 'Patients Served'],
                        [$cms['hero_stat_2_num'] ?? '98%',     $cms['hero_stat_2_label'] ?? 'Satisfaction Rate'],
                        [$cms['hero_stat_3_num'] ?? '50+',     $cms['hero_stat_3_label'] ?? 'Specialist Doctors'],
                    ];
                @endphp
                @foreach($heroStats as [$num, $label])
                <div>
                    <p class="text-3xl font-black stat-num grad-blue">{{ $num }}</p>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">{{ $label }}</p>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Right: Visual Card --}}
        <div class="relative hidden lg:block">

            {{-- Main card --}}
            <div class="relative glass-card rounded-3xl p-8 shadow-2xl animate-float">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-teal-500 flex items-center justify-center text-3xl shadow-lg">🏥</div>
                    <div>
                        <p class="font-bold text-slate-900 dark:text-white">Smart Clinic</p>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Automation System</p>
                    </div>
                    <div class="ml-auto flex items-center gap-1.5 px-3 py-1 bg-emerald-50 dark:bg-emerald-950/50 rounded-full">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-xs font-semibold text-emerald-700 dark:text-emerald-400">Online</span>
                    </div>
                </div>

                {{-- Appointment preview --}}
                <div class="space-y-3">
                    @php
                        $appts = [
                            ['🦷', 'Dental Checkup',    'Dr. Sari',    '09:00', 'confirmed'],
                            ['❤️', 'Cardiology',        'Dr. Budi',    '10:30', 'pending'],
                            ['🧴', 'Dermatology',       'Dr. Ayu',     '13:00', 'confirmed'],
                        ];
                    @endphp
                    @foreach($appts as [$icon, $name, $doc, $time, $status])
                    <div class="flex items-center gap-3 p-3 bg-white/60 dark:bg-slate-800/60 rounded-xl border border-white/80 dark:border-slate-700/50">
                        <span class="text-xl">{{ $icon }}</span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-800 dark:text-white truncate">{{ $name }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $doc }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-mono font-bold text-slate-700 dark:text-slate-300">{{ $time }}</p>
                            <span class="text-xs px-1.5 py-0.5 rounded-full {{ $status === 'confirmed' ? 'bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-400' : 'bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-400' }}">
                                {{ $status }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Automation badge --}}
                <div class="mt-4 p-3 bg-gradient-to-r from-blue-50 to-teal-50 dark:from-blue-950/50 dark:to-teal-950/50 rounded-xl border border-blue-100 dark:border-blue-900/50">
                    <p class="text-xs font-semibold text-blue-700 dark:text-blue-300">🤖 AI Follow-Up Scheduled</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">WhatsApp reminder in 6 days</p>
                </div>
            </div>

            {{-- Floating badges --}}
            <div class="absolute -top-6 -right-6 glass-card rounded-2xl px-4 py-3 shadow-xl animate-float" style="animation-delay:1s">
                <p class="text-xs font-bold text-slate-700 dark:text-white">⚡ Smart Automation</p>
                <p class="text-xs text-slate-500 dark:text-slate-400">Auto follow-up & reminders</p>
            </div>
            <div class="absolute -bottom-6 -left-6 glass-card rounded-2xl px-4 py-3 shadow-xl animate-float" style="animation-delay:2s">
                <div class="flex items-center gap-2">
                    <span class="text-2xl">⭐</span>
                    <div>
                        <p class="text-xs font-bold text-slate-700 dark:text-white">4.9/5.0</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Patient Rating</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Scroll indicator --}}
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 animate-bounce">
        <span class="text-xs text-slate-400 dark:text-slate-600">Scroll</span>
        <svg class="w-4 h-4 text-slate-400 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- TRUST BAR                                                                  --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<div class="bg-gradient-to-r from-blue-600 via-blue-700 to-teal-600 py-5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap justify-center gap-8 md:gap-16 text-white/90 text-sm font-medium">
            @foreach(['🏆 ISO 9001 Certified', '🔬 Advanced Diagnostics', '📱 WhatsApp Integration', '🤖 AI-Powered Scheduling', '🌙 24/7 Emergency Care'] as $item)
            <span class="flex items-center gap-2">{{ $item }}</span>
            @endforeach
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- SERVICES SECTION                                                           --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<section id="services" class="py-24 bg-slate-50 dark:bg-slate-900 mesh-bg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-1.5 bg-blue-100 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 text-sm font-semibold rounded-full mb-4">
                {{ $cms['services_badge'] ?? 'What We Offer' }}
            </span>
            <h2 class="text-4xl sm:text-5xl font-black text-slate-900 dark:text-white mb-4">
                {{ $cms['services_title'] ?? 'Comprehensive Medical Services' }}
            </h2>
            <p class="text-lg text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">
                {{ $cms['services_subtitle'] ?? 'From routine checkups to specialized treatments.' }}
            </p>
        </div>

        {{-- Service Cards --}}
        @php
            $services = [
                ['icon' => '🩺', 'color' => 'blue',    'key' => 'svc_1', 'default_title' => 'General Consultation', 'default_desc' => 'Comprehensive health assessments from experienced practitioners.'],
                ['icon' => '🦷', 'color' => 'teal',    'key' => 'svc_2', 'default_title' => 'Dental Care',          'default_desc' => 'Advanced dental treatments and preventive care.'],
                ['icon' => '🧴', 'color' => 'violet',  'key' => 'svc_3', 'default_title' => 'Dermatology',          'default_desc' => 'Expert skin care and aesthetic treatments.'],
                ['icon' => '❤️', 'color' => 'rose',    'key' => 'svc_4', 'default_title' => 'Cardiology',           'default_desc' => 'Comprehensive heart health services.'],
                ['icon' => '👶', 'color' => 'amber',   'key' => 'svc_5', 'default_title' => 'Pediatrics',           'default_desc' => 'Dedicated child healthcare from newborns to adolescents.'],
                ['icon' => '🤖', 'color' => 'emerald', 'key' => 'svc_6', 'default_title' => 'Smart Automation',     'default_desc' => 'AI-powered reminders and follow-up scheduling.'],
            ];
            $colorMap = [
                'blue'    => ['bg' => 'bg-blue-50 dark:bg-blue-950/30',    'icon' => 'bg-blue-100 dark:bg-blue-900/50',    'border' => 'hover:border-blue-300 dark:hover:border-blue-700'],
                'teal'    => ['bg' => 'bg-teal-50 dark:bg-teal-950/30',    'icon' => 'bg-teal-100 dark:bg-teal-900/50',    'border' => 'hover:border-teal-300 dark:hover:border-teal-700'],
                'violet'  => ['bg' => 'bg-violet-50 dark:bg-violet-950/30','icon' => 'bg-violet-100 dark:bg-violet-900/50','border' => 'hover:border-violet-300 dark:hover:border-violet-700'],
                'rose'    => ['bg' => 'bg-rose-50 dark:bg-rose-950/30',    'icon' => 'bg-rose-100 dark:bg-rose-900/50',    'border' => 'hover:border-rose-300 dark:hover:border-rose-700'],
                'amber'   => ['bg' => 'bg-amber-50 dark:bg-amber-950/30',  'icon' => 'bg-amber-100 dark:bg-amber-900/50',  'border' => 'hover:border-amber-300 dark:hover:border-amber-700'],
                'emerald' => ['bg' => 'bg-emerald-50 dark:bg-emerald-950/30','icon' => 'bg-emerald-100 dark:bg-emerald-900/50','border' => 'hover:border-emerald-300 dark:hover:border-emerald-700'],
            ];
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($services as $svc)
            @php $c = $colorMap[$svc['color']]; @endphp
            <div class="service-card group p-6 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 {{ $c['border'] }} shadow-sm hover:shadow-xl cursor-pointer">
                <div class="w-14 h-14 {{ $c['icon'] }} rounded-2xl flex items-center justify-center text-3xl mb-5 group-hover:scale-110 transition-transform duration-300">
                    {{ $svc['icon'] }}
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">
                    {{ $cms[$svc['key'] . '_title'] ?? $svc['default_title'] }}
                </h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                    {{ $cms[$svc['key'] . '_desc'] ?? $svc['default_desc'] }}
                </p>
                <div class="mt-4 flex items-center gap-1 text-sm font-semibold text-blue-600 dark:text-blue-400 opacity-0 group-hover:opacity-100 transition-opacity">
                    Learn more <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Book CTA --}}
        <div class="text-center mt-12">
            <a href="{{ route('bookings.create') }}"
               class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-blue-600 to-teal-500 text-white font-bold rounded-2xl shadow-xl shadow-blue-500/25 hover:shadow-blue-500/40 hover:scale-105 transition-all duration-200">
                📅 {{ $cms['hero_cta_primary'] ?? 'Book Appointment' }}
            </a>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- WHY US SECTION                                                             --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<section id="why-us" class="py-24 bg-white dark:bg-slate-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

            {{-- Left: Text --}}
            <div>
                <span class="inline-block px-4 py-1.5 bg-teal-100 dark:bg-teal-950/50 text-teal-700 dark:text-teal-300 text-sm font-semibold rounded-full mb-4">
                    {{ $cms['why_badge'] ?? 'Why Choose Us' }}
                </span>
                <h2 class="text-4xl sm:text-5xl font-black text-slate-900 dark:text-white mb-4 leading-tight">
                    {{ $cms['why_title'] ?? 'Healthcare Reimagined for the Digital Age' }}
                </h2>
                <p class="text-lg text-slate-600 dark:text-slate-400 mb-10">
                    {{ $cms['why_subtitle'] ?? 'We combine medical excellence with smart technology.' }}
                </p>

                @php
                    $whyItems = [
                        ['icon' => '⚡', 'color' => 'blue',    'key' => 'why_1'],
                        ['icon' => '👨‍⚕️', 'color' => 'teal',    'key' => 'why_2'],
                        ['icon' => '🔁', 'color' => 'violet',  'key' => 'why_3'],
                        ['icon' => '💎', 'color' => 'emerald', 'key' => 'why_4'],
                    ];
                    $whyDefaults = [
                        'why_1' => ['Smart Booking System', 'Book appointments in minutes with real-time slot availability.'],
                        'why_2' => ['Expert Medical Team', 'Our team of 50+ certified specialists.'],
                        'why_3' => ['Follow-Up Automation', 'Post-treatment follow-ups sent automatically via WhatsApp.'],
                        'why_4' => ['Transparent Pricing', 'No hidden fees. Clear, upfront pricing for all services.'],
                    ];
                @endphp

                <div class="space-y-5">
                    @foreach($whyItems as $item)
                    <div class="flex gap-4 p-4 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-900 transition-colors">
                        <div class="w-12 h-12 rounded-xl bg-{{ $item['color'] }}-100 dark:bg-{{ $item['color'] }}-900/30 flex items-center justify-center text-2xl flex-shrink-0">
                            {{ $item['icon'] }}
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 dark:text-white mb-1">
                                {{ $cms[$item['key'] . '_title'] ?? $whyDefaults[$item['key']][0] }}
                            </h4>
                            <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                                {{ $cms[$item['key'] . '_desc'] ?? $whyDefaults[$item['key']][1] }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Right: Visual --}}
            <div class="relative">
                {{-- Automation flow visual --}}
                <div class="glass-card rounded-3xl p-8 shadow-2xl">
                    <h4 class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-6">Automation Pipeline</h4>
                    @php
                        $pipeline = [
                            ['✅', 'Booking Confirmed',   'Instant WhatsApp confirmation sent',   'emerald'],
                            ['⏰', 'H-1 Reminder',        'Reminder sent at 08:00 WIB',            'blue'],
                            ['🔗', 'Patient Response',    'Confirm or cancel via link',            'violet'],
                            ['🏥', 'Visit Completed',     'Doctor marks treatment done',           'teal'],
                            ['🔁', 'Follow-Up Scheduled', 'Auto follow-up in 6 days via WhatsApp', 'amber'],
                            ['📊', 'Daily Report',        'Owner receives KPI report at 21:00',    'rose'],
                        ];
                    @endphp
                    <div class="space-y-3">
                        @foreach($pipeline as $i => [$icon, $title, $desc, $color])
                        <div class="flex items-center gap-3 relative">
                            @if(!$loop->last)
                            <div class="absolute left-5 top-10 bottom-0 w-px bg-slate-200 dark:bg-slate-700"></div>
                            @endif
                            <div class="w-10 h-10 rounded-full bg-{{ $color }}-100 dark:bg-{{ $color }}-900/30 flex items-center justify-center text-lg flex-shrink-0 z-10">
                                {{ $icon }}
                            </div>
                            <div class="flex-1 pb-3">
                                <p class="text-sm font-semibold text-slate-800 dark:text-white">{{ $title }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $desc }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- STATS SECTION                                                              --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<div class="py-20 bg-gradient-to-br from-blue-600 via-blue-700 to-teal-600 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-64 h-64 bg-white rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-white rounded-full blur-3xl translate-x-1/2 translate-y-1/2"></div>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center text-white">
            @foreach([
                ['15,000+', 'Patients Served',    '❤️'],
                ['98%',     'Satisfaction Rate',   '⭐'],
                ['50+',     'Specialist Doctors',  '👨‍⚕️'],
                ['14 yrs',  'Years of Excellence', '🏆'],
            ] as [$num, $label, $icon])
            <div>
                <p class="text-4xl mb-1">{{ $icon }}</p>
                <p class="text-4xl sm:text-5xl font-black stat-num mb-2">{{ $num }}</p>
                <p class="text-blue-200 text-sm font-medium">{{ $label }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- ABOUT SECTION                                                              --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<section id="about" class="py-24 bg-slate-50 dark:bg-slate-900 mesh-bg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

            {{-- Left: Visual --}}
            <div class="relative">
                <div class="glass-card rounded-3xl p-8 shadow-2xl">
                    <div class="grid grid-cols-2 gap-4">
                        @foreach([
                            ['🏥', 'Modern Facility',    'State-of-the-art equipment',    'blue'],
                            ['🤝', 'Patient-Centered',   'Compassionate care always',      'teal'],
                            ['🔬', 'Advanced Diagnostics','Precision medical testing',     'violet'],
                            ['📱', 'Digital-First',      'Smart booking & follow-ups',     'emerald'],
                        ] as [$icon, $title, $desc, $color])
                        <div class="p-4 bg-{{ $color }}-50 dark:bg-{{ $color }}-950/30 rounded-2xl border border-{{ $color }}-100 dark:border-{{ $color }}-900/50">
                            <span class="text-3xl block mb-2">{{ $icon }}</span>
                            <p class="text-sm font-bold text-slate-800 dark:text-white">{{ $title }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ $desc }}</p>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-6 p-4 bg-gradient-to-r from-blue-50 to-teal-50 dark:from-blue-950/30 dark:to-teal-950/30 rounded-2xl border border-blue-100 dark:border-blue-900/50">
                        <div class="flex items-center gap-3">
                            <span class="text-3xl">🏆</span>
                            <div>
                                <p class="font-bold text-slate-800 dark:text-white">ISO 9001:2015 Certified</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">International Quality Management Standard</p>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Year badge --}}
                <div class="absolute -top-4 -right-4 w-20 h-20 bg-gradient-to-br from-blue-600 to-teal-500 rounded-2xl flex flex-col items-center justify-center text-white shadow-xl">
                    <p class="text-xs font-medium opacity-80">Since</p>
                    <p class="text-xl font-black">2010</p>
                </div>
            </div>

            {{-- Right: Text --}}
            <div>
                <span class="inline-block px-4 py-1.5 bg-violet-100 dark:bg-violet-950/50 text-violet-700 dark:text-violet-300 text-sm font-semibold rounded-full mb-4">
                    {{ $cms['about_badge'] ?? 'Our Story' }}
                </span>
                <h2 class="text-4xl sm:text-5xl font-black text-slate-900 dark:text-white mb-6 leading-tight">
                    {{ $cms['about_title'] ?? 'A Decade of Healing & Innovation' }}
                </h2>
                <p class="text-slate-600 dark:text-slate-400 leading-relaxed mb-8">
                    {{ $cms['about_body'] ?? 'Founded in 2010, Smart Clinic has grown from a small general practice into a comprehensive multi-specialty clinic.' }}
                </p>

                <div class="p-5 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
                    <h4 class="font-bold text-slate-900 dark:text-white mb-2 flex items-center gap-2">
                        🎯 {{ $cms['about_vision_title'] ?? 'Our Vision' }}
                    </h4>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                        {{ $cms['about_vision_body'] ?? 'To be the most trusted and innovative healthcare provider in the region.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- PATIENT PORTAL / LOGIN SECTION                                             --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<section id="patient-portal" class="py-24 bg-white dark:bg-slate-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="text-center mb-14">
            <span class="inline-block px-4 py-1.5 bg-blue-100 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 text-sm font-semibold rounded-full mb-4">
                {{ $cms['portal_badge'] ?? 'Patient Portal' }}
            </span>
            <h2 class="text-4xl sm:text-5xl font-black text-slate-900 dark:text-white mb-4">
                {{ $cms['portal_title'] ?? 'Access Your Booking' }}
            </h2>
            <p class="text-lg text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">
                {{ $cms['portal_subtitle'] ?? 'Check your appointment status or book a new visit in seconds.' }}
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-5xl mx-auto">

            {{-- Card 1: Check Booking Status --}}
            <div class="glass-card rounded-3xl p-8 shadow-xl border border-slate-200 dark:border-slate-700/50">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center text-2xl">🔍</div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">
                            {{ $cms['portal_lookup_title'] ?? 'Check Booking Status' }}
                        </h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            {{ $cms['portal_lookup_subtitle'] ?? 'Enter your booking code to view details' }}
                        </p>
                    </div>
                </div>

                <div x-data="{ code: '', loading: false, result: null, error: null }" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            {{ $cms['portal_code_label'] ?? 'Booking Code' }}
                        </label>
                        <input
                            type="text"
                            x-model="code"
                            placeholder="{{ $cms['portal_code_placeholder'] ?? 'e.g. ABCD1234' }}"
                            class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all font-mono text-lg tracking-widest uppercase"
                            @input="code = code.toUpperCase()"
                            maxlength="10"
                        >
                    </div>

                    {{-- Result display --}}
                    <template x-if="result">
                        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 rounded-xl">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-emerald-600 dark:text-emerald-400 font-bold text-sm">✅ Booking Found</span>
                            </div>
                            <div class="space-y-1.5 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-slate-500 dark:text-slate-400">Patient</span>
                                    <span class="font-semibold text-slate-800 dark:text-white" x-text="result.patient_name"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-500 dark:text-slate-400">Date</span>
                                    <span class="font-semibold text-slate-800 dark:text-white" x-text="result.booking_date"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-500 dark:text-slate-400">Treatment</span>
                                    <span class="font-semibold text-slate-800 dark:text-white" x-text="result.treatment"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-500 dark:text-slate-400">Doctor</span>
                                    <span class="font-semibold text-slate-800 dark:text-white" x-text="result.doctor"></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-slate-500 dark:text-slate-400">Status</span>
                                    <span class="px-2 py-0.5 rounded-full text-xs font-bold"
                                          :class="{
                                              'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-400': result.status === 'confirmed',
                                              'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-400': result.status === 'completed',
                                              'bg-rose-100 text-rose-700 dark:bg-rose-900/50 dark:text-rose-400': result.status === 'cancelled',
                                              'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-400': result.status === 'no_show',
                                          }"
                                          x-text="result.status">
                                    </span>
                                </div>
                            </div>
                        </div>
                    </template>

                    <template x-if="error">
                        <div class="p-4 bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-800 rounded-xl text-sm text-rose-600 dark:text-rose-400">
                            ❌ <span x-text="error"></span>
                        </div>
                    </template>

                    <button
                        @click="
                            if (!code.trim()) return;
                            loading = true; result = null; error = null;
                            fetch('/bookings/lookup?code=' + encodeURIComponent(code))
                                .then(r => r.json())
                                .then(d => { if (d.found) { result = d; } else { error = d.message || 'Booking not found. Please check your code.'; } })
                                .catch(() => { error = 'Unable to check booking. Please try again.'; })
                                .finally(() => loading = false);
                        "
                        :disabled="!code.trim() || loading"
                        class="w-full py-3 bg-gradient-to-r from-blue-600 to-teal-500 text-white font-bold rounded-xl hover:opacity-90 transition-opacity disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                    >
                        <span x-show="!loading">🔍 {{ $cms['portal_lookup_btn'] ?? 'Check Status' }}</span>
                        <span x-show="loading" class="flex items-center gap-2">
                            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            Checking...
                        </span>
                    </button>
                </div>
            </div>

            {{-- Card 2: New Booking --}}
            <div class="glass-card rounded-3xl p-8 shadow-xl border border-slate-200 dark:border-slate-700/50 flex flex-col">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-teal-100 dark:bg-teal-900/40 flex items-center justify-center text-2xl">📅</div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">
                            {{ $cms['portal_book_title'] ?? 'New Appointment' }}
                        </h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            {{ $cms['portal_book_subtitle'] ?? 'Book your visit in under 2 minutes' }}
                        </p>
                    </div>
                </div>

                <div class="flex-1 space-y-4">
                    {{-- Steps --}}
                    @foreach([
                        ['1', 'Choose Treatment', 'Select from our 6+ medical specialties'],
                        ['2', 'Pick a Doctor', 'View available specialists for your treatment'],
                        ['3', 'Select Time Slot', 'Real-time availability, no double-booking'],
                        ['4', 'Confirm & Done!', 'Instant WhatsApp confirmation sent to you'],
                    ] as [$step, $title, $desc])
                    <div class="flex items-start gap-3">
                        <div class="w-7 h-7 rounded-full bg-gradient-to-br from-blue-600 to-teal-500 flex items-center justify-center text-white text-xs font-black flex-shrink-0 mt-0.5">{{ $step }}</div>
                        <div>
                            <p class="text-sm font-semibold text-slate-800 dark:text-white">{{ $title }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $desc }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    <a href="{{ route('bookings.create') }}"
                       class="w-full inline-flex items-center justify-center gap-2 py-3.5 bg-gradient-to-r from-blue-600 to-teal-500 text-white font-bold rounded-xl shadow-lg shadow-blue-500/20 hover:shadow-blue-500/40 hover:scale-[1.02] transition-all duration-200">
                        📅 {{ $cms['portal_book_btn'] ?? 'Book Appointment Now' }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <p class="text-center text-xs text-slate-400 dark:text-slate-500 mt-3">
                        ✅ Free · No registration required · Instant confirmation
                    </p>
                </div>
            </div>

            <div class="mt-4 col-span-1 lg:col-span-2 text-center">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-xs font-medium text-slate-400 hover:text-blue-600 transition-colors px-4 py-2 hover:bg-slate-50 dark:hover:bg-slate-900 rounded-lg border border-transparent hover:border-slate-200 dark:hover:border-slate-800">
                    🔒 Authorized Staff Login
                </a>
            </div>


        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- CTA SECTION                                                                --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<section class="py-24 bg-white dark:bg-slate-950 relative overflow-hidden">
    <div class="absolute inset-0 mesh-bg"></div>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative">
        <div class="glass-card rounded-3xl p-12 shadow-2xl">
            <span class="text-6xl block mb-6">🏥</span>
            <h2 class="text-4xl sm:text-5xl font-black text-slate-900 dark:text-white mb-4">
                {{ $cms['cta_title'] ?? 'Ready to Take Control of Your Health?' }}
            </h2>
            <p class="text-lg text-slate-600 dark:text-slate-400 mb-8 max-w-2xl mx-auto">
                {{ $cms['cta_subtitle'] ?? 'Book your appointment today and experience the future of healthcare.' }}
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('bookings.create') }}"
                   class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-gradient-to-r from-blue-600 to-teal-500 text-white font-bold rounded-2xl shadow-xl shadow-blue-500/25 hover:shadow-blue-500/40 hover:scale-105 transition-all duration-200 text-lg">
                    📅 {{ $cms['cta_button'] ?? 'Book Your Appointment' }}
                </a>
                <a href="tel:+622112345678"
                   class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-semibold rounded-2xl hover:border-blue-400 transition-all duration-200 text-lg">
                    📞 {{ $cms['cta_phone'] ?? 'Call Us' }}
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- FOOTER                                                                     --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<footer id="contact" class="bg-slate-900 dark:bg-slate-950 text-slate-400 pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-12">

            {{-- Brand --}}
            <div class="md:col-span-2">
                <div class="flex items-center gap-2.5 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-teal-500 flex items-center justify-center text-white text-xl">🏥</div>
                    <span class="font-bold text-xl text-white">Smart<span class="text-blue-400">Clinic</span></span>
                </div>
                <p class="text-slate-400 text-sm leading-relaxed mb-4 max-w-xs">
                    {{ $cms['footer_tagline'] ?? 'Your health is our mission.' }}
                </p>
                <div class="flex gap-3">
                    @foreach(['📘', '📸', '🐦', '▶️'] as $social)
                    <a href="#" class="w-9 h-9 rounded-full bg-slate-800 hover:bg-blue-600 flex items-center justify-center text-sm transition-colors">{{ $social }}</a>
                    @endforeach
                </div>
            </div>

            {{-- Services --}}
            <div>
                <h4 class="text-white font-semibold mb-4">{{ $cms['nav_services'] ?? 'Services' }}</h4>
                <ul class="space-y-2 text-sm">
                    @foreach(['General Consultation', 'Dental Care', 'Dermatology', 'Cardiology', 'Pediatrics'] as $svc)
                    <li><a href="#services" class="hover:text-blue-400 transition-colors">{{ $svc }}</a></li>
                    @endforeach
                </ul>
            </div>

            {{-- Contact --}}
            <div>
                <h4 class="text-white font-semibold mb-4">{{ $cms['nav_contact'] ?? 'Contact' }}</h4>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-start gap-2">
                        <span>📍</span>
                        <span>{{ $cms['footer_address'] ?? 'Jl. Kesehatan No. 1, Jakarta' }}</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span>📞</span>
                        <a href="tel:+622112345678" class="hover:text-blue-400 transition-colors">{{ $cms['footer_phone'] ?? '+62 21 1234 5678' }}</a>
                    </li>
                    <li class="flex items-center gap-2">
                        <span>📧</span>
                        <a href="mailto:hello@smartclinic.id" class="hover:text-blue-400 transition-colors">{{ $cms['footer_email'] ?? 'hello@smartclinic.id' }}</a>
                    </li>
                    <li class="flex items-start gap-2">
                        <span>🕐</span>
                        <span>{{ $cms['footer_hours'] ?? 'Mon–Sat: 08:00–20:00' }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="border-t border-slate-800 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-sm">{{ $cms['footer_copy'] ?? '© 2025 Smart Clinic. All rights reserved.' }}</p>
            <div class="flex items-center gap-4 text-sm">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-400 transition-colors">Admin Panel</a>
                <span>·</span>
                <a href="#" class="hover:text-blue-400 transition-colors">Privacy Policy</a>
            </div>
        </div>
    </div>
</footer>

<script>
function app() {
    return {
        dark: localStorage.getItem('dark') === 'true' || window.matchMedia('(prefers-color-scheme: dark)').matches,
        init() {
            // Intersection observer for scroll animations
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(el => {
                    if (el.isIntersecting) el.target.classList.add('visible');
                });
            }, { threshold: 0.1 });
            document.querySelectorAll('[data-reveal]').forEach(el => observer.observe(el));
        }
    }
}
</script>
</body>
</html>
