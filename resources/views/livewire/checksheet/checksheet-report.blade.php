<div>
    <div class="page-fade min-h-screen pb-32">
        <!-- Header -->
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

            <div class="flex items-center gap-2">

                <a href="{{ route('checksheet.history') }}"
                class="flex items-center gap-1.5 px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-800 transition-all text-xs font-bold">
                 <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                 </svg>
                 <span class="hidden sm:inline">Dashboard</span>
             </a>


                <!-- User Info + Logout -->
                <div class="flex items-center gap-2">
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

            </div>
        </header>


        <main class="max-w-7xl mx-auto px-6 py-6">
            <!-- Filter & Search -->
            <div class="bg-white border border-slate-200 rounded-xl p-4 mb-4 shadow-sm">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="🔍 Cari nama atau serial number..."
                               class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:border-slate-500 focus:ring-2 focus:ring-slate-200 transition-all outline-none text-sm">
                    </div>

                    <!-- Filter Checksheet -->
                    <div>
                        <select wire:model.live="filterChecksheet"
                                class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:border-slate-500 focus:ring-2 focus:ring-slate-200 transition-all outline-none text-sm">
                            <option value="">Semua Checksheet</option>
                            @foreach($checksheetHeads as $head)
                                <option value="{{ $head->id }}">{{ $head->code }} - {{ $head->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date From -->
                    <div>
                        <input type="date" wire:model.live="filterDateFrom"
                               class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:border-slate-500 focus:ring-2 focus:ring-slate-200 transition-all outline-none text-sm">
                    </div>

                    <!-- Date To -->
                    <div>
                        <input type="date" wire:model.live="filterDateTo"
                               class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:border-slate-500 focus:ring-2 focus:ring-slate-200 transition-all outline-none text-sm">
                    </div>
                </div>
            </div>

            <!-- Stats Summary -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white border border-slate-200 rounded-xl p-4">
                    <p class="text-xs font-bold text-slate-500 uppercase">Total Inspeksi</p>
                    <p class="text-2xl font-black text-slate-800 mt-1">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                    <p class="text-xs font-bold text-green-700 uppercase">Completed</p>
                    <p class="text-2xl font-black text-green-800 mt-1">{{ $stats['completed'] }}</p>
                </div>
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                    <p class="text-xs font-bold text-blue-700 uppercase">Today</p>
                    <p class="text-2xl font-black text-blue-800 mt-1">{{ $stats['today'] }}</p>
                </div>
                <div class="bg-purple-50 border border-purple-200 rounded-xl p-4">
                    <p class="text-xs font-bold text-purple-700 uppercase">This Month</p>
                    <p class="text-2xl font-black text-purple-800 mt-1">{{ $stats['this_month'] }}</p>
                </div>
            </div>

            <!-- List Inspections -->
            <div class="space-y-3">
                @forelse($inspections as $inspection)
                    <a href="{{ route('checksheet.report.detail', $inspection->id) }}"
                       class="block bg-white border border-slate-200 rounded-xl p-4 hover:shadow-lg hover:border-slate-300 transition-all">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-grow">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="px-2 py-1 bg-slate-100 rounded text-xs font-bold text-slate-700">
                                        {{ $inspection->checksheetHead->code }}
                                    </span>
                                    @if($inspection->status === 'completed')
                                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-bold">✓ Completed</span>
                                    @elseif($inspection->status === 'draft')
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs font-bold">⏳ Draft</span>
                                    @endif
                                </div>

                                <h3 class="text-base font-bold text-slate-800 mb-1">{{ $inspection->checksheetHead->title }}</h3>

                                <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-xs">
                                    <div>
                                        <span class="text-slate-500">Inspector:</span>
                                        <span class="font-bold text-slate-800 ml-1">{{ $inspection->nama }}</span>
                                    </div>
                                    <div>
                                        <span class="text-slate-500">Tanggal:</span>
                                        <span class="font-bold text-slate-800 ml-1">{{ $inspection->tanggal->format('d M Y') }}</span>
                                    </div>
                                    @if($inspection->serial_number)
                                        <div>
                                            <span class="text-slate-500">Serial:</span>
                                            <span class="font-bold text-slate-800 ml-1">{{ $inspection->serial_number }}</span>
                                        </div>
                                    @endif
                                    <div>
                                        <span class="text-slate-500">Items:</span>
                                        <span class="font-bold text-slate-800 ml-1">{{ $inspection->total_items }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Summary Results -->
                            <div class="flex items-center gap-3">
                                <div class="text-center">
                                    <p class="text-2xl font-black text-green-600">{{ $inspection->total_ok }}</p>
                                    <p class="text-xs text-slate-500 font-bold">OK</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-2xl font-black text-red-600">{{ $inspection->total_ng }}</p>
                                    <p class="text-xs text-slate-500 font-bold">NG</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-2xl font-black text-blue-600">{{ $inspection->percentage_ok }}%</p>
                                    <p class="text-xs text-slate-500 font-bold">Pass</p>
                                </div>
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-16 bg-white border border-slate-200 rounded-xl">
                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                            </svg>
                        </div>
                        <p class="text-slate-400 font-medium">Belum ada data inspeksi</p>
                        <p class="text-xs text-slate-400 mt-1">Mulai inspeksi checksheet untuk melihat report</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($inspections->hasPages())
                <div class="mt-6">
                    <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
                        <div class="flex items-center justify-between">
                            {{-- Info --}}
                            <div class="text-sm text-slate-600">
                                Showing <span class="font-bold text-slate-800">{{ $inspections->firstItem() }}</span>
                                to <span class="font-bold text-slate-800">{{ $inspections->lastItem() }}</span>
                                of <span class="font-bold text-slate-800">{{ $inspections->total() }}</span> results
                            </div>

                            {{-- Pagination Buttons --}}
                            <div class="flex gap-2">
                                @if ($inspections->onFirstPage())
                                    <span class="px-4 py-2 bg-slate-100 text-slate-400 rounded-lg text-sm font-bold cursor-not-allowed">
                                        ← Previous
                                    </span>
                                @else
                                    <button wire:click="previousPage" class="px-4 py-2 bg-slate-100 hover:bg-slate-800 hover:text-white text-slate-700 rounded-lg text-sm font-bold transition-all">
                                        ← Previous
                                    </button>
                                @endif

                                <div class="flex items-center gap-2 px-4 py-2 bg-slate-50 rounded-lg">
                                    <span class="text-sm font-bold text-slate-800">{{ $inspections->currentPage() }}</span>
                                    <span class="text-sm text-slate-500">/</span>
                                    <span class="text-sm text-slate-600">{{ $inspections->lastPage() }}</span>
                                </div>

                                @if ($inspections->hasMorePages())
                                    <button wire:click="nextPage" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-lg text-sm font-bold transition-all">
                                        Next →
                                    </button>
                                @else
                                    <span class="px-4 py-2 bg-slate-100 text-slate-400 rounded-lg text-sm font-bold cursor-not-allowed">
                                        Next →
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </main>
    </div>

    <!-- Bottom Navigation -->
    <nav class="fixed bottom-6 left-6 right-6 h-20 bg-slate-900/95 backdrop-blur-lg rounded-[2.2rem] flex justify-around items-center z-50 px-6 shadow-[0_20px_50px_rgba(0,0,0,0.3)] border border-white/10">
        <a href="{{ route('home.index') }}" class="flex flex-col items-center gap-1.5 text-slate-500 hover:text-white transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75" />
            </svg>
            <span class="text-[9px] font-black uppercase tracking-widest">Home</span>
        </a>
        <a href="{{ route('checksheet.report') }}" class="flex flex-col items-center gap-1.5 text-white transition-all scale-110">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
            </svg>
            <span class="text-[9px] font-black uppercase tracking-widest">Reports</span>
        </a>
        <a href="{{ route('users.index') }}" class="flex flex-col items-center gap-1.5 text-slate-500 hover:text-white transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
            </svg>
            <span class="text-[9px] font-black uppercase tracking-widest">Users</span>
        </a>
    </nav>
</div>
