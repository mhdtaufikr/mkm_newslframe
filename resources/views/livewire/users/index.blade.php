<div>
    {{-- HEADER --}}
    <header class="sticky top-0 z-50 bg-white/70 backdrop-blur-xl border-b border-slate-100 px-6 py-4 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-lg shadow-slate-900/20 p-1.5 border border-slate-200">
                <img src="{{ asset('images/logo-mkm.png') }}" alt="MKM Logo" class="w-full h-full object-contain">
            </div>
            <div>
                <h1 class="text-lg font-extrabold text-slate-800 leading-none">User Management</h1>
                <p class="text-[7px] text-slate-400 font-bold uppercase tracking-widest mt-1">Digital Checksheet SL</p>
            </div>
        </div>
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
    </header>

    <main class="max-w-7xl mx-auto px-6 py-8 pb-40">

        {{-- PAGE TITLE + ADD BUTTON --}}
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">Manajemen User</h2>
                <p class="text-slate-500 text-sm mt-1 font-medium">Kelola akses dan data pengguna sistem</p>
            </div>
            <button wire:click="openCreate"
                class="flex items-center gap-2 px-4 py-2.5 bg-app-gradient text-white rounded-xl text-sm font-bold shadow-lg shadow-slate-900/20 hover:scale-105 active:scale-95 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span class="hidden sm:inline">Tambah User</span>
            </button>
        </div>

        {{-- SEARCH & FILTER --}}
        <div class="glass-card rounded-2xl p-4 mb-5 flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 15.803 7.5 7.5 0 0016.803 15.803z" />
                </svg>
                <input wire:model.live.debounce.300ms="search" type="text"
                    placeholder="Cari nama, email, username..."
                    class="w-full pl-9 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300">
            </div>
            <select wire:model.live="filterRole"
                class="px-3 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-300">
                <option value="">Semua Role</option>
                @foreach($roles as $r)
                    <option value="{{ $r }}">{{ ucfirst($r) }}</option>
                @endforeach
            </select>
            <select wire:model.live="filterStatus"
                class="px-3 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-300">
                <option value="">Semua Status</option>
                <option value="1">Aktif</option>
                <option value="0">Nonaktif</option>
            </select>
        </div>

        {{-- USER LIST --}}
        <div class="space-y-3 mb-6">
            @forelse($users as $user)
                <div class="glass-card rounded-2xl p-4 flex items-center gap-4 group hover:shadow-lg transition-all">
                    {{-- Avatar --}}
                    <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center flex-shrink-0 font-black text-slate-500 text-lg group-hover:bg-app-gradient group-hover:text-white transition-all">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <p class="text-sm font-bold text-slate-800 truncate">{{ $user->name }}</p>
                            <span class="px-2 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-wider
                                {{ $user->role === 'admin' ? 'bg-slate-800 text-white' : 'bg-slate-100 text-slate-600' }}">
                                {{ $user->role }}
                            </span>
                            @if($user->is_active)
                                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded-lg text-[10px] font-bold">Aktif</span>
                            @else
                                <span class="px-2 py-0.5 bg-red-100 text-red-600 rounded-lg text-[10px] font-bold">Nonaktif</span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-500 font-medium mt-0.5 truncate">{{ $user->email }}</p>
                        <p class="text-[10px] text-slate-400 font-medium mt-0.5">
                            @{{ $user->username }}
                            @if($user->last_login)
                                · Login terakhir: {{ \Carbon\Carbon::parse($user->last_login)->diffForHumans() }}
                            @endif
                        </p>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-2 flex-shrink-0">
                        {{-- Toggle Active --}}
                        <button wire:click="toggleActive({{ $user->id }})"
                            title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                            class="w-9 h-9 rounded-xl flex items-center justify-center transition-all
                                {{ $user->is_active
                                    ? 'bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white'
                                    : 'bg-slate-100 text-slate-400 hover:bg-slate-500 hover:text-white' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                @if($user->is_active)
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                @endif
                            </svg>
                        </button>

                        {{-- Edit --}}
                        <button wire:click="openEdit({{ $user->id }})"
                            class="w-9 h-9 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center hover:bg-blue-500 hover:text-white transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                            </svg>
                        </button>

                        {{-- Delete --}}
                        <button
                            wire:click="delete({{ $user->id }})"
                            wire:confirm="Yakin hapus user {{ $user->name }}? Data tidak bisa dikembalikan!"
                            class="w-9 h-9 rounded-xl bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                            </svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="text-center py-16">
                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </div>
                    <p class="text-slate-400 font-medium">Tidak ada user ditemukan</p>
                </div>
            @endforelse
        </div>

        {{-- PAGINATION --}}
        {{ $users->links() }}

    </main>

    {{-- MODAL CREATE / EDIT --}}
    @if($showModal)
        <div class="fixed inset-0 z-[60] flex items-end sm:items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" wire:click="closeModal"></div>

            <div class="relative w-full max-w-md bg-white rounded-[2rem] shadow-2xl z-10 overflow-hidden max-h-[90vh] overflow-y-auto">
                {{-- Modal Header --}}
                <div class="bg-app-gradient px-6 py-5 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-black text-white">{{ $isEditing ? 'Edit User' : 'Tambah User Baru' }}</h3>
                        <p class="text-white/70 text-xs font-medium mt-0.5">{{ $isEditing ? 'Perbarui data pengguna' : 'Buat akun pengguna baru' }}</p>
                    </div>
                    <button wire:click="closeModal" class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center text-white hover:bg-white/30 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="px-6 py-6 space-y-4">
                    {{-- Name --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Nama Lengkap</label>
                        <input wire:model="name" type="text" placeholder="Masukkan nama lengkap"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 transition-all">
                        @error('name') <p class="text-red-500 text-xs font-medium mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Username --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Username</label>
                        <input wire:model="username" type="text" placeholder="Masukkan username"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 transition-all">
                        @error('username') <p class="text-red-500 text-xs font-medium mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Email</label>
                        <input wire:model="email" type="email" placeholder="email@example.com"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 transition-all">
                        @error('email') <p class="text-red-500 text-xs font-medium mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Role --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Role</label>
                        <select wire:model="role"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-400 transition-all">
                            <option value="">-- Pilih Role --</option>
                            <option value="admin">Admin</option>
                            <option value="operator">Operator</option>
                            <option value="viewer">Viewer</option>
                        </select>
                        @error('role') <p class="text-red-500 text-xs font-medium mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">
                            Password
                            @if($isEditing)
                                <span class="text-slate-400 normal-case font-medium">(kosongkan jika tidak diubah)</span>
                            @endif
                        </label>
                        <input wire:model="password" type="password"
                            placeholder="{{ $isEditing ? 'Kosongkan jika tidak diubah' : 'Masukkan password' }}"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 transition-all">
                        @error('password') <p class="text-red-500 text-xs font-medium mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Konfirmasi Password</label>
                        <input wire:model="password_confirmation" type="password" placeholder="Ulangi password"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 transition-all">
                    </div>

                    {{-- Status Toggle --}}
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-200">
                        <div>
                            <p class="text-sm font-bold text-slate-700">Status Aktif</p>
                            <p class="text-xs text-slate-500 font-medium">User dapat login ke sistem</p>
                        </div>
                        <button type="button" wire:click="$toggle('is_active')"
                            class="relative inline-flex h-7 w-12 items-center rounded-full transition-colors duration-300 {{ $is_active ? 'bg-emerald-500' : 'bg-slate-300' }}">
                            <span class="inline-block h-5 w-5 transform rounded-full bg-white shadow-md transition-transform duration-300 {{ $is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                        </button>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="px-6 pb-6 flex gap-3">
                    <button wire:click="closeModal"
                        class="flex-1 px-4 py-3 bg-slate-100 text-slate-700 rounded-xl text-sm font-bold hover:bg-slate-200 transition-all">
                        Batal
                    </button>
                    <button wire:click="save" wire:loading.attr="disabled" wire:target="save"
                        class="flex-1 px-4 py-3 bg-app-gradient text-white rounded-xl text-sm font-bold hover:scale-105 active:scale-95 transition-all shadow-lg disabled:opacity-70 disabled:scale-100">
                        <span wire:loading.remove wire:target="save">{{ $isEditing ? 'Simpan Perubahan' : 'Tambah User' }}</span>
                        <span wire:loading wire:target="save">Menyimpan...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- BOTTOM NAV --}}
    <nav class="fixed bottom-6 left-6 right-6 h-20 bg-slate-900/95 backdrop-blur-lg rounded-[2.2rem] flex justify-around items-center z-50 px-6 shadow-[0_20px_50px_rgba(0,0,0,0.3)] border border-white/10">
        <a href="{{ route('home.index') }}" class="flex flex-col items-center gap-1.5 text-slate-500 hover:text-white transition-all">
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
        <a href="{{ route('users.index') }}" class="flex flex-col items-center gap-1.5 text-white transition-all scale-110">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
            </svg>
            <span class="text-[9px] font-black uppercase tracking-widest">Users</span>
        </a>
    </nav>

    {{-- SWAL LISTENER --}}
    @push('scripts')
    <script>
        window.addEventListener('swal', event => {
            const d = event.detail[0] ?? event.detail;
            Swal.fire({
                icon: d.type,
                title: d.message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true,
            });
        });
    </script>
    @endpush
</div>
