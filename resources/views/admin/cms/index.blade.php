@extends('layouts.admin')

@section('title', 'CMS — Content Management')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-white">✏️ Content Management System</h1>
                <p class="text-slate-400 text-sm mt-1">Edit landing page text in English & Indonesian</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('landing') }}" target="_blank"
                    class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white text-sm font-medium rounded-xl transition-colors flex items-center gap-2">
                    🌐 Preview Landing Page
                </a>
                <a href="{{ route('admin.cms.create') }}"
                    class="px-4 py-2 bg-gradient-to-r from-blue-600 to-teal-500 text-white text-sm font-semibold rounded-xl hover:opacity-90 transition-opacity flex items-center gap-2">
                    ➕ Add New Key
                </a>
            </div>
        </div>

        {{-- Flash --}}
        @if(session('success'))
            <div
                class="p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-xl text-emerald-400 text-sm flex items-center gap-2">
                ✅ {{ session('success') }}
            </div>
        @endif

        {{-- Section + Locale Tabs --}}
        <div class="glass-card rounded-2xl p-1 flex flex-wrap gap-1">
            @php
                $sections = ['nav', 'hero', 'services', 'why', 'about', 'cta', 'footer'];
                $sectionLabels = ['nav' => '🔗 Nav', 'hero' => '🦸 Hero', 'services' => '🩺 Services', 'why' => '💡 Why Us', 'about' => '📖 About', 'cta' => '📣 CTA', 'footer' => '🦶 Footer'];
            @endphp
            @foreach($sections as $sec)
                <a href="{{ route('admin.cms.index', ['section' => $sec, 'locale' => $locale]) }}"
                    class="px-4 py-2 rounded-xl text-sm font-medium transition-all
                                  {{ $section === $sec ? 'bg-blue-600 text-white shadow' : 'text-slate-400 hover:text-white hover:bg-slate-700' }}">
                    {{ $sectionLabels[$sec] ?? ucfirst($sec) }}
                </a>
            @endforeach
        </div>

        {{-- Locale Switcher --}}
        <div class="flex items-center gap-3">
            <span class="text-slate-400 text-sm">Language:</span>
            @foreach(['en' => '🇬🇧 English', 'id' => '🇮🇩 Indonesian'] as $lang => $label)
                <a href="{{ route('admin.cms.index', ['section' => $section, 'locale' => $lang]) }}"
                    class="px-4 py-1.5 rounded-full text-sm font-medium transition-all
                                  {{ $locale === $lang ? 'bg-blue-600 text-white' : 'bg-slate-700 text-slate-300 hover:bg-slate-600' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        {{-- Content Editor --}}
        <form method="POST" action="{{ route('admin.cms.update') }}" class="space-y-4">
            @csrf
            {{-- Pass current section/locale so controller can redirect back correctly --}}
            <input type="hidden" name="section" value="{{ $section }}">
            <input type="hidden" name="locale" value="{{ $locale }}">

            @if($contents->isEmpty())
                <div class="glass-card rounded-2xl p-12 text-center">
                    <p class="text-slate-400 text-lg mb-2">No content found for this section.</p>
                    <a href="{{ route('admin.cms.create', ['section' => $section]) }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-500 transition-colors mt-2">
                        ➕ Add Content
                    </a>
                </div>
            @else

                <div class="glass-card rounded-2xl overflow-hidden">
                    <div class="p-5 border-b border-white/5">
                        <h3 class="text-white font-semibold">
                            {{ $sectionLabels[$section] ?? ucfirst($section) }} —
                            {{ $locale === 'en' ? '🇬🇧 English' : '🇮🇩 Indonesian' }}
                        </h3>
                        <p class="text-slate-400 text-xs mt-0.5">{{ $contents->count() }} content keys</p>
                    </div>

                    <div class="divide-y divide-white/5">
                        @foreach($contents as $item)
                            <div class="p-5 hover:bg-white/2 transition-colors">
                                <input type="hidden" name="updates[{{ $loop->index }}][key]" value="{{ $item->key }}">
                                <input type="hidden" name="updates[{{ $loop->index }}][locale]" value="{{ $item->locale }}">
                                <input type="hidden" name="updates[{{ $loop->index }}][section]" value="{{ $item->section }}">


                                <div class="flex items-start gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <code
                                                class="text-xs bg-slate-700 text-blue-300 px-2 py-0.5 rounded font-mono">{{ $item->key }}</code>
                                            <span
                                                class="text-xs text-slate-500 bg-slate-800 px-2 py-0.5 rounded">{{ $item->type }}</span>
                                        </div>

                                        @if($item->type === 'textarea' || $item->type === 'html')
                                            <textarea name="updates[{{ $loop->index }}][value]" rows="3"
                                                class="w-full bg-slate-800 border border-slate-700 focus:border-blue-500 text-white text-sm rounded-xl px-4 py-3 resize-none outline-none transition-colors placeholder-slate-500">{{ $item->value }}</textarea>
                                        @else
                                            <input type="text" name="updates[{{ $loop->index }}][value]" value="{{ $item->value }}"
                                                class="w-full bg-slate-800 border border-slate-700 focus:border-blue-500 text-white text-sm rounded-xl px-4 py-3 outline-none transition-colors">
                                        @endif
                                    </div>

                                    {{-- Delete --}}
                                    <form method="POST" action="{{ route('admin.cms.destroy', $item->key) }}"
                                        onsubmit="return confirm('Delete key \'{{ $item->key }}\'? This removes all locales.')"
                                        class="flex-shrink-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="w-9 h-9 rounded-xl bg-red-500/10 hover:bg-red-500/20 text-red-400 flex items-center justify-center transition-colors text-sm">
                                            🗑
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Save Button --}}
                <div class="flex justify-end">
                    <button type="submit"
                        class="px-8 py-3 bg-gradient-to-r from-blue-600 to-teal-500 text-white font-bold rounded-xl hover:opacity-90 transition-opacity shadow-lg shadow-blue-500/20 flex items-center gap-2">
                        💾 Save Changes
                    </button>
                </div>

            @endif
        </form>

        {{-- Info Card --}}
        <div class="glass-card rounded-2xl p-5 border border-blue-500/20">
            <h4 class="text-blue-300 font-semibold text-sm mb-3">📋 Available Sections</h4>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                @foreach($sectionLabels as $sec => $label)
                    <div class="text-xs text-slate-400 bg-slate-800 rounded-lg px-3 py-2">
                        <span class="font-mono text-blue-400">{{ $sec }}</span> — {{ $label }}
                    </div>
                @endforeach
            </div>
        </div>

    </div>
@endsection