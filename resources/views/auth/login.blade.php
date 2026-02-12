@extends('layouts.app')

@section('title', 'Login - Digital Checksheet SL-Frame')

@section('content')
<div class="page-fade min-h-screen flex items-center justify-center p-6 relative overflow-hidden">
    <div class="blob -top-20 -left-20"></div>
    <div class="blob -bottom-20 -right-20" style="background: var(--app-secondary)"></div>

    <div class="w-full max-w-md z-10">
        <!-- Logo Header -->
        <div class="flex items-center justify-center gap-6 mb-12">
            <div class="h-16 w-16 bg-white rounded-2xl flex items-center justify-center shadow-lg shadow-slate-900/20 p-2 border border-slate-200">
                <img src="{{ asset('images/logo-mkm.png') }}"
                    alt="MKM Logo"
                    class="w-full h-full object-contain">
            </div>
            <div class="flex flex-col">
                <h1 class="text-4xl font-extrabold tracking-tighter text-slate-800 leading-none">
                    Digital<span class="text-[var(--app-primary)]"> Checksheet</span>
                </h1>
                <span class="text-[8px] font-black uppercase tracking-[0.3em] text-slate-400 mt-1">
                    SL-Frame Quality System
                </span>
            </div>
        </div>


        <!-- Login Card -->
        <div class="glass-card p-10 rounded-[3rem] shadow-2xl shadow-slate-200">
            <div class="mb-8">
                <h2 class="text-xl font-bold text-slate-800">Selamat Datang</h2>
                <p class="text-slate-500 text-sm font-medium">Silakan masuk untuk mengakses sistem quality checksheet</p>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl">
                    <div class="flex items-center gap-2 text-red-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span class="text-sm font-semibold">{{ $errors->first() }}</span>
                    </div>
                </div>
            @endif

            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-2xl">
                    <div class="flex items-center gap-2 text-green-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-sm font-semibold">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-2xl">
                    <div class="flex items-center gap-2 text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-sm font-semibold">{{ session('status') }}</span>
                    </div>
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Username Field -->
                <div>
                    <label for="username" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">
                        Username
                    </label>
                    <input
                        id="username"
                        type="text"
                        name="username"
                        value="{{ old('username') }}"
                        placeholder="Masukkan username Anda"
                        class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-slate-100 focus:border-[var(--app-primary)] transition-all outline-none font-medium @error('username') border-red-300 @enderror"
                        required
                        autofocus
                        autocomplete="username">
                    @error('username')
                        <p class="mt-2 text-xs text-red-500 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">
                        Password
                    </label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        placeholder="••••••••"
                        class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-slate-100 focus:border-[var(--app-primary)] transition-all outline-none font-medium @error('password') border-red-300 @enderror"
                        required
                        autocomplete="current-password">
                    @error('password')
                        <p class="mt-2 text-xs text-red-500 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input
                        type="checkbox"
                        name="remember"
                        id="remember_me"
                        class="w-4 h-4 text-[var(--app-primary)] bg-slate-50 border-slate-300 rounded focus:ring-2 focus:ring-slate-100">
                    <label for="remember_me" class="ml-2 text-sm text-slate-600 font-medium">
                        Ingat saya
                    </label>
                </div>

                <!-- Forgot Password Link -->
                @if (Route::has('password.request'))
                    <div class="text-right">
                        <a href="{{ route('password.request') }}" class="text-sm text-slate-600 hover:text-[var(--app-primary)] font-medium transition-colors">
                            Lupa password?
                        </a>
                    </div>
                @endif

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full bg-app-gradient hover:shadow-xl hover:shadow-slate-900/20 text-white font-bold py-4 rounded-2xl active:scale-95 transition-all duration-300 mt-4">
                    Masuk ke Sistem
                </button>
            </form>
        </div>

        <!-- Footer -->
        <p class="mt-12 text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest">
            © 2026 Digital Checksheet SL-Frame
        </p>
    </div>
</div>
@endsection
