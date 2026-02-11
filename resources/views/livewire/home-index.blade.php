<div>
    <header class="sticky top-0 z-50 bg-white/70 backdrop-blur-xl border-b border-slate-100 px-6 py-4 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-app-gradient rounded-xl flex items-center justify-center shadow-lg shadow-slate-900/20">
                <span class="text-white font-black text-xl">B</span>
            </div>
            <div>
                <h1 class="text-lg font-extrabold text-slate-800 leading-none">Base App</h1>
                <p class="text-[7px] text-slate-400 font-bold uppercase tracking-widest mt-1">Management System</p>
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

    <main class="max-w-2xl mx-auto px-6 py-8 pb-40">
        <div class="mb-10 relative">
            <div class="relative z-10">
                <h2 class="text-3xl font-black text-slate-800 tracking-tight">Panel Utama</h2>
                <p class="text-slate-500 text-sm mt-1 font-medium">Sistem Manajemen Aplikasi</p>
            </div>
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-slate-500/10 blur-3xl rounded-full"></div>
        </div>

        <div class="grid grid-cols-2 gap-4 sm:gap-6">
            <!-- Rules -->
            <a href="{{ route('rules.index') }}" class="glass-card p-6 rounded-[2.5rem] flex flex-col items-start hover:shadow-xl hover:-translate-y-1 transition-all group">
                <div class="p-4 bg-slate-100 text-slate-500 rounded-2xl mb-6 group-hover:bg-[var(--app-primary)] group-hover:text-white transition-all duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .415.162.791.425 1.056l.425.425a1.5 1.5 0 001.056.425.75.75 0 00.053 0c.231 0 .454-.035.664-.1m-5.801 0A48.495 48.495 0 0112 3.75c.211 0 .423.002.634.006m-5.801 0A48.667 48.667 0 0012 3.75c.211 0 .423.002.634.006" />
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800 leading-tight">Rules</h3>
                <p class="text-[10px] text-slate-400 mt-1 font-medium">Manajemen Aturan</p>
            </a>

            <!-- Dropdowns -->
            <a href="{{ route('dropdowns.index') }}" class="glass-card aspect-square sm:aspect-auto sm:min-h-[160px] p-5 sm:p-7 rounded-[2.5rem] flex flex-col items-start justify-between hover:shadow-2xl hover:shadow-slate-200 hover:-translate-y-1.5 transition-all duration-300 group border border-white/50">
                <div class="p-3.5 bg-slate-100 text-slate-500 rounded-2xl group-hover:bg-slate-600 group-hover:text-white group-hover:-rotate-6 transition-all duration-300 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-[14px] sm:text-base font-extrabold text-slate-800 leading-tight">Dropdowns</h3>
                    <p class="text-[10px] text-slate-400 mt-1 font-bold uppercase tracking-wider">Manajemen Dropdown</p>
                </div>
            </a>

            <!-- Settings -->
            <a href="#" class="glass-card aspect-square sm:aspect-auto sm:min-h-[160px] p-5 sm:p-7 rounded-[2.5rem] flex flex-col items-start justify-between hover:shadow-2xl hover:shadow-slate-200 hover:-translate-y-1.5 transition-all duration-300 group border border-white/50">
                <div class="p-3.5 bg-slate-100 text-slate-500 rounded-2xl group-hover:bg-slate-600 group-hover:text-white group-hover:scale-110 transition-all duration-300 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-[14px] sm:text-base font-extrabold text-slate-800 leading-tight">Settings</h3>
                    <p class="text-[10px] text-slate-400 mt-1 font-bold uppercase tracking-wider">Pengaturan</p>
                </div>
            </a>

            <!-- Reports -->
            <a href="#" class="glass-card aspect-square sm:aspect-auto sm:min-h-[160px] p-5 sm:p-7 rounded-[2.5rem] flex flex-col items-start justify-between hover:shadow-2xl hover:shadow-slate-200 hover:-translate-y-1.5 transition-all duration-300 group border border-white/50">
                <div class="p-3.5 bg-slate-100 text-slate-500 rounded-2xl group-hover:bg-slate-600 group-hover:text-white group-hover:rotate-12 transition-all duration-300 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-[14px] sm:text-base font-extrabold text-slate-800 leading-tight">Reports</h3>
                    <p class="text-[10px] text-slate-400 mt-1 font-bold uppercase tracking-wider">Laporan</p>
                </div>
            </a>
        </div>

        <div class="mt-8 bg-slate-900 rounded-[2.5rem] p-7 text-white relative overflow-hidden shadow-xl shadow-slate-200">
            <div class="relative z-10 flex justify-between items-center">
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <span class="w-2 h-2 bg-emerald-400 rounded-full animate-ping"></span>
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-emerald-400">System Active</span>
                    </div>
                    <h4 class="text-xl font-bold tracking-tight">Sistem Berjalan Normal</h4>
                    <p class="text-slate-400 text-[11px] mt-1 font-medium">Terakhir diperbarui: {{ now()->format('d M Y, H:i') }}</p>
                </div>
                <div class="hidden sm:block">
                    <svg class="w-12 h-12 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <svg class="absolute -right-4 -bottom-4 w-32 h-32 text-white/5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M11.7 2.805a.75.75 0 01.6 0A60.65 60.65 0 0122.83 8.72a.75.75 0 01-.231 1.337 49.949 49.949 0 00-9.902 3.912l-.003.002-.34.18a.75.75 0 01-.707 0A50.009 50.009 0 007.5 12.174v-.224c0-.131.067-.248.172-.311a54.614 54.614 0 014.653-2.52.75.75 0 00-.65-1.352 56.129 56.129 0 00-4.78 2.589 1.858 1.858 0 00-.859 1.228 49.803 49.803 0 00-4.634-1.527.75.75 0 01-.231-1.337A60.653 60.653 0 0111.7 2.805z" />
            </svg>
        </div>
    </main>

    <nav class="fixed bottom-6 left-6 right-6 h-20 bg-slate-900/95 backdrop-blur-lg rounded-[2.2rem] flex justify-around items-center z-50 px-6 shadow-[0_20px_50px_rgba(0,0,0,0.3)] border border-white/10">
        <a href="{{ route('home.index') }}" class="flex flex-col items-center gap-1.5 text-slate-400 transition-all scale-110">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75" />
            </svg>
            <span class="text-[9px] font-black uppercase tracking-widest">Home</span>
        </a>
        <a href="{{ route('rules.index') }}" class="flex flex-col items-center gap-1.5 text-slate-500 hover:text-white transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .415.336.75.75.75h.008a.75.75 0 00.75-.75 2.25 2.25 0 00-.659-1.591l-.007-.007a2.25 2.25 0 00-1.591-.659h-.033z" />
            </svg>
            <span class="text-[9px] font-black uppercase tracking-widest">Rules</span>
        </a>
        <a href="{{ route('dropdowns.index') }}" class="flex flex-col items-center gap-1.5 text-slate-500 hover:text-white transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
            </svg>
            <span class="text-[9px] font-black uppercase tracking-widest">Dropdown</span>
        </a>
    </nav>
</div>
