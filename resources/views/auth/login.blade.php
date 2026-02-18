@extends('layouts.app')

@section('content')
    <div
        class="min-h-screen flex items-center justify-center bg-gray-900 py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
        {{-- Background Effect --}}
        <div class="absolute inset-0 z-0 opacity-20">
            <div
                class="absolute top-0 left-0 w-96 h-96 bg-blue-500 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2">
            </div>
            <div
                class="absolute bottom-0 right-0 w-96 h-96 bg-teal-500 rounded-full blur-3xl translate-x-1/2 translate-y-1/2">
            </div>
        </div>

        <div
            class="max-w-md w-full space-y-8 relative z-10 glass-card p-8 rounded-2xl bg-white/5 backdrop-blur-lg border border-white/10 shadow-2xl">
            <div>
                <div
                    class="mx-auto h-16 w-16 bg-gradient-to-br from-blue-500 to-teal-500 rounded-xl flex items-center justify-center text-3xl shadow-lg">
                    🏥
                </div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-white">
                    Admin Access
                </h2>
                <p class="mt-2 text-center text-sm text-gray-400">
                    Sign in to manage the clinic
                </p>
            </div>
            <form class="mt-8 space-y-6" action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="email-address" class="sr-only">Email address</label>
                        <input id="email-address" name="email" type="email" autocomplete="email" required
                            class="appearance-none rounded-none relative block w-full px-4 py-3 border border-gray-700 placeholder-gray-500 text-white bg-gray-800 rounded-t-xl focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm transition-colors"
                            placeholder="Email address" value="{{ old('email') }}">
                    </div>
                    <div>
                        <label for="password" class="sr-only">Password</label>
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                            class="appearance-none rounded-none relative block w-full px-4 py-3 border border-gray-700 placeholder-gray-500 text-white bg-gray-800 rounded-b-xl focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm transition-colors"
                            placeholder="Password">
                    </div>
                </div>

                @if ($errors->any())
                    <div class="text-red-400 text-sm text-center bg-red-500/10 py-2 rounded-lg border border-red-500/20">
                        {{ $errors->first('email') }}
                    </div>
                @endif

                <div>
                    <button type="submit"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-xl text-white bg-gradient-to-r from-blue-600 to-teal-500 hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all shadow-lg shadow-blue-500/20 hover:scale-[1.02]">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-blue-300 group-hover:text-blue-200 transition-colors"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </span>
                        Sign in
                    </button>
                </div>

                <div class="text-center">
                    <a href="{{ route('landing') }}"
                        class="font-medium text-blue-400 hover:text-blue-300 text-sm transition-colors">
                        ← Back to Landing Page
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection