@extends('layouts.admin')
@section('title', 'Follow-Up Rules')

@section('content')
    <div class="space-y-5 fade-up" x-data="{ showForm: false }">

        {{-- ── HEADER ───────────────────────────────────────────────────────── --}}
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-white">Follow-Up Rules</h2>
                <p class="text-sm text-gray-500 mt-0.5">Automated reminders triggered when a booking is completed</p>
            </div>
            <button @click="showForm = !showForm"
                class="px-4 py-2 bg-violet-500/15 border border-violet-500/30 text-violet-400 rounded-xl text-sm font-medium hover:bg-violet-500/25 transition-colors">
                <span x-text="showForm ? '✕ Cancel' : '+ New Rule'"></span>
            </button>
        </div>

        {{-- ── CREATE FORM ──────────────────────────────────────────────────── --}}
        <div x-show="showForm" x-transition class="glass rounded-2xl p-5">
            <h3 class="text-sm font-semibold text-white mb-4">Create Follow-Up Rule</h3>
            <form method="POST" action="{{ route('admin.follow-up-rules.store') }}"
                class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @csrf

                <div>
                    <label class="block text-xs text-gray-500 mb-1.5">Treatment <span class="text-rose-400">*</span></label>
                    <select name="treatment_id" required
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-3 py-2.5 text-sm text-white focus:outline-none focus:border-violet-500/50">
                        <option value="">Select treatment...</option>
                        @foreach($treatments as $t)
                            <option value="{{ $t->id }}">{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1.5">Interval Value <span
                                class="text-rose-400">*</span></label>
                        <input type="number" name="interval_value" min="1" required
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-3 py-2.5 text-sm text-white focus:outline-none focus:border-violet-500/50"
                            placeholder="e.g. 6">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1.5">Unit <span class="text-rose-400">*</span></label>
                        <select name="interval_unit" required
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-3 py-2.5 text-sm text-white focus:outline-none focus:border-violet-500/50">
                            @foreach(['minutes', 'hours', 'days', 'weeks', 'months'] as $u)
                                <option value="{{ $u }}">{{ ucfirst($u) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs text-gray-500 mb-1.5">Channel</label>
                    <select name="channel"
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-3 py-2.5 text-sm text-white focus:outline-none focus:border-violet-500/50">
                        <option value="whatsapp">📱 WhatsApp</option>
                        <option value="email">📧 Email</option>
                    </select>
                </div>

                <div class="flex items-center gap-3 pt-5">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 rounded accent-violet-500">
                        <span class="text-sm text-gray-400">Active immediately</span>
                    </label>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs text-gray-500 mb-1.5">
                        Message Template <span class="text-rose-400">*</span>
                        <span class="text-gray-600 ml-2">Placeholders: {patient_name} {doctor_name} {treatment_name}
                            {booking_code}</span>
                    </label>
                    <textarea name="message_template" required rows="3"
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-3 py-2.5 text-sm text-white focus:outline-none focus:border-violet-500/50 resize-none"
                        placeholder="Halo {patient_name}! Sudah waktunya kontrol. Hubungi kami untuk jadwal bersama {doctor_name}."></textarea>
                </div>

                <div class="md:col-span-2 flex justify-end">
                    <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-cyan-500/20 to-violet-500/20 border border-white/10 text-white rounded-xl text-sm font-medium hover:from-cyan-500/30 hover:to-violet-500/30 transition-all">
                        ✅ Create Rule
                    </button>
                </div>
            </form>
        </div>

        {{-- ── RULES LIST ───────────────────────────────────────────────────── --}}
        <div class="space-y-3">
            @forelse($rules as $rule)
                <div class="glass rounded-2xl p-5 {{ !$rule->is_active ? 'opacity-50' : '' }} transition-opacity">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-start gap-4 flex-1 min-w-0">
                            <div
                                class="w-10 h-10 rounded-xl bg-violet-500/10 flex items-center justify-center text-xl flex-shrink-0">
                                🔁</div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap mb-1">
                                    <span class="text-sm font-semibold text-white">{{ $rule->treatment->name }}</span>
                                    <span
                                        class="text-xs px-2 py-0.5 rounded-full bg-violet-500/10 text-violet-400 border border-violet-500/20">
                                        +{{ $rule->interval_value }} {{ $rule->interval_unit }}
                                    </span>
                                    <span
                                        class="text-xs px-2 py-0.5 rounded-full bg-cyan-500/10 text-cyan-400 border border-cyan-500/20">
                                        {{ $rule->channel }}
                                    </span>
                                    @if($rule->is_active)
                                        <span
                                            class="text-xs px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Active</span>
                                    @else
                                        <span
                                            class="text-xs px-2 py-0.5 rounded-full bg-gray-500/10 text-gray-500 border border-gray-500/20">Inactive</span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500 line-clamp-2">{{ $rule->message_template }}</p>
                                <p class="text-xs text-gray-700 mt-1">
                                    {{ $rule->scheduledFollowUps()->count() }} follow-ups scheduled
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <form method="POST" action="{{ route('admin.follow-up-rules.toggle', $rule) }}">
                                @csrf
                                <button type="submit" class="text-xs px-3 py-1.5 rounded-xl border transition-colors
                                               {{ $rule->is_active
                ? 'border-amber-500/20 text-amber-400 hover:bg-amber-500/10'
                : 'border-emerald-500/20 text-emerald-400 hover:bg-emerald-500/10' }}">
                                    {{ $rule->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.follow-up-rules.destroy', $rule) }}"
                                onsubmit="return confirm('Delete this rule?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="text-xs px-3 py-1.5 rounded-xl border border-rose-500/20 text-rose-400 hover:bg-rose-500/10 transition-colors">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="glass rounded-2xl p-16 text-center text-gray-600">
                    <p class="text-4xl mb-3">🔁</p>
                    <p class="text-sm">No follow-up rules yet.</p>
                    <button @click="showForm = true" class="mt-3 text-sm text-violet-400 hover:text-violet-300">Create your
                        first rule →</button>
                </div>
            @endforelse
        </div>
    </div>
@endsection