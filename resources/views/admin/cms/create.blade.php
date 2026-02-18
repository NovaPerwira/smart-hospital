@extends('layouts.admin')

@section('title', 'CMS — Add New Content Key')

@section('content')
<div class="max-w-2xl space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.cms.index') }}" class="w-9 h-9 rounded-xl bg-slate-700 hover:bg-slate-600 flex items-center justify-center text-slate-300 transition-colors">
            ←
        </a>
        <div>
            <h1 class="text-2xl font-bold text-white">➕ Add New Content Key</h1>
            <p class="text-slate-400 text-sm mt-0.5">Create a new CMS entry for both EN and ID</p>
        </div>
    </div>

    {{-- Form --}}
    <form method="POST" action="{{ route('admin.cms.store') }}" class="space-y-5">
        @csrf

        <div class="glass-card rounded-2xl p-6 space-y-5">

            {{-- Key --}}
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1.5">
                    Key <span class="text-red-400">*</span>
                    <span class="text-slate-500 font-normal ml-1">(snake_case, e.g. hero_title)</span>
                </label>
                <input type="text" name="key" value="{{ old('key') }}" required
                       placeholder="hero_new_badge"
                       class="w-full bg-slate-800 border border-slate-700 focus:border-blue-500 text-white text-sm rounded-xl px-4 py-3 outline-none transition-colors font-mono">
                @error('key') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Section --}}
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1.5">Section <span class="text-red-400">*</span></label>
                <select name="section" required
                        class="w-full bg-slate-800 border border-slate-700 focus:border-blue-500 text-white text-sm rounded-xl px-4 py-3 outline-none transition-colors">
                    @foreach($sections as $sec)
                    <option value="{{ $sec }}" {{ (old('section', $section) === $sec) ? 'selected' : '' }}>
                        {{ ucfirst($sec) }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Type --}}
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1.5">Type <span class="text-red-400">*</span></label>
                <div class="flex gap-3">
                    @foreach(['text' => '📝 Text (single line)', 'textarea' => '📄 Textarea (multi-line)', 'html' => '🌐 HTML'] as $val => $label)
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="type" value="{{ $val }}" {{ old('type', 'text') === $val ? 'checked' : '' }}
                               class="text-blue-500">
                        <span class="text-sm text-slate-300">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <hr class="border-white/10">

            {{-- English --}}
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1.5">🇬🇧 English Value <span class="text-red-400">*</span></label>
                <textarea name="en" rows="3" required
                          placeholder="English content here..."
                          class="w-full bg-slate-800 border border-slate-700 focus:border-blue-500 text-white text-sm rounded-xl px-4 py-3 resize-none outline-none transition-colors">{{ old('en') }}</textarea>
                @error('en') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Indonesian --}}
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1.5">🇮🇩 Indonesian Value <span class="text-red-400">*</span></label>
                <textarea name="id" rows="3" required
                          placeholder="Konten dalam Bahasa Indonesia..."
                          class="w-full bg-slate-800 border border-slate-700 focus:border-blue-500 text-white text-sm rounded-xl px-4 py-3 resize-none outline-none transition-colors">{{ old('id') }}</textarea>
                @error('id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex gap-3 justify-end">
            <a href="{{ route('admin.cms.index') }}"
               class="px-6 py-2.5 bg-slate-700 hover:bg-slate-600 text-white text-sm font-medium rounded-xl transition-colors">
                Cancel
            </a>
            <button type="submit"
                    class="px-8 py-2.5 bg-gradient-to-r from-blue-600 to-teal-500 text-white font-bold rounded-xl hover:opacity-90 transition-opacity shadow-lg shadow-blue-500/20">
                💾 Save Key
            </button>
        </div>
    </form>
</div>
@endsection
