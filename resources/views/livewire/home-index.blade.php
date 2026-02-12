<div>
    <header class="sticky top-0 z-50 bg-white/70 backdrop-blur-xl border-b border-slate-100 px-6 py-4 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-lg shadow-slate-900/20 p-1.5 border border-slate-200">
                <img src="{{ asset('images/logo-mkm.png') }}"
                     alt="MKM Logo"
                     class="w-full h-full object-contain">
            </div>
            <div>
                <h1 class="text-lg font-extrabold text-slate-800 leading-none">Digital Checksheet SL</h1>
                <p class="text-[7px] text-slate-400 font-bold uppercase tracking-widest mt-1">Frame Quality System</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="text-right hidden sm:block">
                <p class="text-xs font-bold text-slate-800 leading-none">{{ Auth::user()->name }}</p>
                <p class="text-[9px] text-slate-600 font-bold uppercase mt-1">{{ Auth::user()->role }}</p>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="w-10 h-10 rounded-xl bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                    </svg>
                </button>
            </form>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-6 py-8 pb-40">
        <div class="mb-8 relative">
            <div class="relative z-10">
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">Quality Checksheet</h2>
                <p class="text-slate-500 text-sm mt-1 font-medium">Pilih jenis pemeriksaan kualitas frame</p>
            </div>
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-slate-500/10 blur-3xl rounded-full"></div>
        </div>

   <!-- Grid Layout 2x2 - Compact Version -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
    @forelse($checksheets as $checksheet)
        <a href="{{ route('checksheet.detail', $checksheet->id) }}"
           class="glass-card p-5 rounded-[2rem] hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-slate-500/5 rounded-full -mr-12 -mt-12 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <!-- Icon berdasarkan process_name -->
                    <div class="w-12 h-12 bg-slate-100 text-slate-500 rounded-xl flex items-center justify-center group-hover:bg-[var(--app-primary)] group-hover:text-white group-hover:scale-105 transition-all duration-300 shadow-sm">
                        @if(str_contains(strtolower($checksheet->process_name ?? ''), 'weld'))
                            <!-- Welding Icon -->
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0112 21 8.25 8.25 0 016.038 7.048 8.287 8.287 0 009 9.6a8.983 8.983 0 013.361-6.867 8.21 8.21 0 003 2.48z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 18a3.75 3.75 0 00.495-7.467 5.99 5.99 0 00-1.925 3.546 5.974 5.974 0 01-2.133-1A3.75 3.75 0 0012 18z" />
                            </svg>
                        @else
                            <!-- Default Check Icon -->
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        @endif
                    </div>
                    <div class="px-2.5 py-1 bg-slate-100 rounded-lg">
                        <span class="text-[10px] font-black text-slate-600">{{ $checksheet->code }}</span>
                    </div>
                </div>
                <h3 class="text-base font-bold text-slate-800 leading-tight mb-1">{{ $checksheet->title }}</h3>
                @if($checksheet->subtitle)
                    <p class="text-[11px] text-slate-500 font-medium mb-3">{{ $checksheet->subtitle }}</p>
                @endif
                <div class="flex items-center text-slate-400 group-hover:text-[var(--app-primary)] transition-colors">
                    <span class="text-[10px] font-bold mr-1.5">Mulai</span>
                    <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </div>
            </div>
        </a>
    @empty
        <!-- Empty State -->
        <div class="col-span-2 text-center py-16">
            <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                </svg>
            </div>
            <p class="text-slate-400 font-medium">Belum ada checksheet aktif</p>
        </div>
    @endforelse
</div>


        <!-- System Status Card -->
        <div class="bg-slate-900 rounded-[2rem] p-6 text-white relative overflow-hidden shadow-xl shadow-slate-200">
            <div class="relative z-10 flex justify-between items-center">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="w-2 h-2 bg-emerald-400 rounded-full animate-ping"></span>
                        <span class="text-[9px] font-black uppercase tracking-[0.2em] text-emerald-400">System Active</span>
                    </div>
                    <h4 class="text-lg font-bold tracking-tight">Sistem Berjalan Normal</h4>
                    <p class="text-slate-400 text-[10px] mt-1 font-medium">Terakhir diperbarui: {{ now()->format('d M Y, H:i') }}</p>
                </div>
                <div class="hidden sm:block">
                    <svg class="w-10 h-10 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <svg class="absolute -right-4 -bottom-4 w-28 h-28 text-white/5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M11.7 2.805a.75.75 0 01.6 0A60.65 60.65 0 0122.83 8.72a.75.75 0 01-.231 1.337 49.949 49.949 0 00-9.902 3.912l-.003.002-.34.18a.75.75 0 01-.707 0A50.009 50.009 0 007.5 12.174v-.224c0-.131.067-.248.172-.311a54.614 54.614 0 014.653-2.52.75.75 0 00-.65-1.352 56.129 56.129 0 00-4.78 2.589 1.858 1.858 0 00-.859 1.228 49.803 49.803 0 00-4.634-1.527.75.75 0 01-.231-1.337A60.653 60.653 0 0111.7 2.805z" />
            </svg>
        </div>
    </main>

    <!-- ✨ FLOATING ACTION BUTTON - Master Checksheet -->
    <a href="{{ route('checksheet.master.index') }}"
       class="fixed bottom-32 right-6 w-14 h-14 bg-app-gradient text-white rounded-2xl flex items-center justify-center shadow-2xl shadow-slate-900/30 hover:shadow-slate-900/40 hover:scale-110 active:scale-95 transition-all duration-300 z-40 group">
        <svg class="w-7 h-7 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h.008a.75.75 0 00.75-.75 2.25 2.25 0 00-.659-1.591l-.007-.007a2.25 2.25 0 00-1.591-.659h-.033zM12 2.25c-.552 0-1.05.223-1.411.584a2.066 2.066 0 00-.582.662l-.009.02-.004.011-.002.005v.001l.001-.001.003-.006.011-.025a1.5 1.5 0 01.265-.408c.228-.254.543-.384.873-.384.33 0 .645.13.873.384a1.5 1.5 0 01.265.408l.011.025.003.006.001.001v-.001l-.002-.005-.004-.011a2.066 2.066 0 00-.582-.662A1.995 1.995 0 0012 2.25z" />
        </svg>
        <!-- Tooltip -->
        <span class="absolute right-16 bg-slate-900 text-white text-xs font-bold px-3 py-2 rounded-xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
            Master Checksheet
        </span>
    </a>

    <nav class="fixed bottom-6 left-6 right-6 h-20 bg-slate-900/95 backdrop-blur-lg rounded-[2.2rem] flex justify-around items-center z-50 px-6 shadow-[0_20px_50px_rgba(0,0,0,0.3)] border border-white/10">
        <a href="{{ route('home.index') }}" class="flex flex-col items-center gap-1.5 text-white transition-all scale-110">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75" />
            </svg>
            <span class="text-[9px] font-black uppercase tracking-widest">Home</span>
        </a>
        <a href="{{ route('checksheet.report') }}" class="flex flex-col items-center gap-1.5 text-slate-500 hover:text-white transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
            </svg>
            <span class="text-[9px] font-black uppercase tracking-widest">Reports</span>
        </a>
        <a href="#" class="flex flex-col items-center gap-1.5 text-slate-500 hover:text-white transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span class="text-[9px] font-black uppercase tracking-widest">Settings</span>
        </a>
    </nav>
</div>
