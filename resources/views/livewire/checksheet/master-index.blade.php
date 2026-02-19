<div class="page-fade min-h-screen pb-32">
    <!-- Header -->
    <header class="sticky top-0 z-50 bg-white/70 backdrop-blur-xl border-b border-slate-100 px-6 py-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-4">
                <a href="{{ route('home.index') }}" class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 flex items-center justify-center transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-black text-slate-800">Master Checksheet</h1>
                    <p class="text-sm text-slate-500 font-medium">Kelola data master checksheet quality</p>
                </div>
            </div>

            {{-- ✅ Button group --}}
            <div class="flex items-center gap-3">

                {{-- Serial No Button --}}
                <button wire:click="openSerialModal"
                        class="flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-slate-700 rounded-xl font-semibold text-sm hover:bg-slate-50 hover:border-slate-300 hover:shadow transition-all">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5-3.9 19.5m-2.1-19.5-3.9 19.5" />
                    </svg>
                    <span>Starting Serial No</span>
                    @if($currentSerialNo)
                        <span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded-lg text-xs font-mono font-bold">
                            {{ $currentSerialNo }}
                        </span>
                    @endif
                </button>

                {{-- Tambah Checksheet Button --}}
                <button wire:click="createHead"
                        class="px-5 py-2.5 bg-app-gradient text-white rounded-xl font-bold text-sm hover:shadow-xl hover:scale-105 active:scale-95 transition-all">
                    + Tambah Checksheet
                </button>
            </div>
        </div>
    </header>
{{-- ✅ Modal Starting Serial No --}}
@if($showSerialModal)
<div class="fixed inset-0 z-50 flex items-center justify-center p-4"
     wire:keydown.escape="closeSerialModal">

    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"
         wire:click="closeSerialModal"></div>

    {{-- Modal Card --}}
    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5-3.9 19.5m-2.1-19.5-3.9 19.5" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800">Starting Serial Number</h3>
                    <p class="text-xs text-slate-500">Atur nomor awal serial number inspeksi</p>
                </div>
            </div>
            <button wire:click="closeSerialModal"
                    class="w-8 h-8 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-600 flex items-center justify-center transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Body --}}
        <div class="px-6 py-5">
            <label class="block text-xs font-bold text-slate-600 uppercase mb-1.5">
                Starting Serial No
            </label>
            <input type="text"
                   wire:model.defer="startingSerialNo"
                   wire:keydown.enter="saveSerialNo"
                   placeholder="Contoh: SN-00001"
                   class="w-full px-3 py-2.5 rounded-xl border border-slate-300 focus:border-slate-500 focus:ring-2 focus:ring-slate-200 transition-all outline-none text-sm font-mono">
            @error('startingSerialNo')
                <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p>
            @enderror

            <p class="mt-2 text-xs text-slate-400">
                Nilai ini akan digunakan sebagai referensi awal penomoran serial inspeksi.
            </p>
        </div>

        {{-- Footer --}}
        <div class="flex items-center justify-end gap-3 px-6 py-4 bg-slate-50 border-t border-slate-100">
            <button wire:click="closeSerialModal"
                    class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-100 transition-all">
                Batal
            </button>
            <button wire:click="saveSerialNo"
                    class="px-5 py-2 bg-app-gradient text-white rounded-xl font-bold text-sm hover:shadow-lg hover:scale-105 active:scale-95 transition-all">
                Simpan
            </button>
        </div>
    </div>
</div>
@endif


    <main class="max-w-7xl mx-auto px-6 py-8">
        <!-- Alert Success -->
        @if (session()->has('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-2xl flex items-center gap-3 text-green-700 animate-fade-in">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-semibold text-sm">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Alert Error -->
        @if (session()->has('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl flex items-center gap-3 text-red-700 animate-fade-in">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
                <span class="font-semibold text-sm">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Search Bar -->
        <div class="mb-6">
            <input type="text"
                   wire:model.live.debounce.300ms="search"
                   placeholder="🔍 Cari berdasarkan kode, judul, atau nomor dokumen..."
                   class="w-full px-5 py-3 rounded-2xl border border-slate-200 focus:border-slate-400 focus:ring-4 focus:ring-slate-100 transition-all outline-none font-medium">
        </div>

        <!-- Checksheet List -->
        <div class="space-y-4">
            @forelse($checksheets as $checksheet)
                <div class="glass-card rounded-2xl border border-slate-200 overflow-hidden transition-all hover:shadow-lg">
                    <!-- Checksheet Head -->
                    <div class="p-6 bg-white">
                        <div class="flex items-start justify-between">
                            <div class="flex-grow">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="px-3 py-1 bg-slate-100 rounded-lg text-xs font-black text-slate-700">{{ $checksheet->code }}</span>
                                    <span class="px-3 py-1 {{ $checksheet->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} rounded-lg text-xs font-bold">
                                        {{ $checksheet->is_active ? '✓ Active' : '✗ Inactive' }}
                                    </span>
                                    <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-lg text-xs font-bold">
                                        {{ $checksheet->sections->count() }} Sections
                                    </span>
                                </div>
                                <h3 class="text-lg font-bold text-slate-800 mb-1">{{ $checksheet->title }}</h3>
                                @if($checksheet->subtitle)
                                    <p class="text-sm text-slate-500 mb-2">{{ $checksheet->subtitle }}</p>
                                @endif
                                <div class="flex items-center gap-4 text-xs text-slate-400 font-medium">
                                    <span>Rev: {{ $checksheet->revision }}</span>
                                    <span>•</span>
                                    <span>{{ $checksheet->document_number }}</span>
                                    @if($checksheet->process_name)
                                        <span>•</span>
                                        <span>{{ $checksheet->process_name }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center gap-2 ml-4">
                                <button wire:click="toggleChecksheet({{ $checksheet->id }})"
                                        class="w-10 h-10 rounded-xl bg-purple-50 text-purple-500 hover:bg-purple-500 hover:text-white transition-all flex items-center justify-center">
                                    <svg class="w-5 h-5 transition-transform {{ in_array($checksheet->id, $expandedChecksheets) ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </button>
                                <button wire:click="toggleStatus({{ $checksheet->id }})"
                                        class="w-10 h-10 rounded-xl bg-blue-50 text-blue-500 hover:bg-blue-500 hover:text-white transition-all flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                    </svg>
                                </button>
                                <button wire:click="editHead({{ $checksheet->id }})"
                                        class="w-10 h-10 rounded-xl bg-green-50 text-green-500 hover:bg-green-500 hover:text-white transition-all flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                </button>
                                <button wire:click="deleteHead({{ $checksheet->id }})"
                                        wire:confirm="Yakin ingin menghapus checksheet ini beserta semua sections dan details?"
                                        class="w-10 h-10 rounded-xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Sections & Details (Collapsible) -->
                    @if(in_array($checksheet->id, $expandedChecksheets))
                        <div class="bg-gradient-to-br from-slate-50 to-purple-50 border-t border-slate-200">
                            <div class="p-6 space-y-4">
                                <!-- Add Section Button -->
                                <div class="flex justify-between items-center mb-4">
                                    <h4 class="text-lg font-bold text-slate-700 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12" />
                                        </svg>
                                        Sections
                                    </h4>
                                    <button wire:click="createSection({{ $checksheet->id }})"
                                            class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-bold text-sm transition-all flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                        </svg>
                                        Tambah Section
                                    </button>
                                </div>

                                @forelse($checksheet->sections as $section)
                                    <div class="bg-white rounded-xl border-2 border-purple-200 overflow-hidden">
                                        <!-- Section Header -->
                                        <div class="p-4 bg-gradient-to-r from-purple-50 to-pink-50">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-grow">
                                                    <div class="flex items-center gap-3 mb-2">
                                                        <span class="w-8 h-8 bg-purple-600 text-white rounded-lg flex items-center justify-center text-sm font-bold">
                                                            {{ $section->section_number }}
                                                        </span>
                                                        <h5 class="text-base font-bold text-slate-800">{{ $section->section_name }}</h5>
                                                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-xs font-bold">
                                                            {{ $section->details->count() }} Items
                                                        </span>
                                                    </div>
                                                    @if($section->section_description)
                                                        <p class="text-sm text-slate-600 ml-11">{{ $section->section_description }}</p>
                                                    @endif
                                                    @if($section->section_images)
                                                        @php
                                                            $images = json_decode($section->section_images, true);
                                                        @endphp
                                                        @if(is_array($images) && count($images) > 0)
                                                            <div class="mt-3 ml-11">
                                                                <div class="flex items-center gap-2 mb-2">
                                                                    <span class="text-xs font-bold text-purple-600">📸 {{ count($images) }} Images</span>
                                                                </div>
                                                                <div class="flex gap-2 flex-wrap">
                                                                    @foreach($images as $image)
                                                                        <img src="{{ asset('images/section/' . $image) }}"
                                                                            alt="{{ $section->section_name }}"
                                                                            class="h-24 w-auto rounded-lg border-2 border-purple-200 hover:border-purple-400 transition-all cursor-pointer hover:scale-105"
                                                                            onclick="window.open(this.src, '_blank')">
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endif

                                                </div>
                                                <div class="flex items-center gap-2 ml-4">
                                                    <button wire:click="toggleSection({{ $section->id }})"
                                                            class="w-9 h-9 rounded-lg bg-blue-50 text-blue-500 hover:bg-blue-500 hover:text-white transition-all flex items-center justify-center">
                                                        <svg class="w-4 h-4 transition-transform {{ in_array($section->id, $expandedSections) ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                                        </svg>
                                                    </button>
                                                    <button wire:click="editSection({{ $section->id }})"
                                                            class="w-9 h-9 rounded-lg bg-green-50 text-green-500 hover:bg-green-500 hover:text-white transition-all flex items-center justify-center">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                        </svg>
                                                    </button>
                                                    <button wire:click="deleteSection({{ $section->id }})"
                                                            wire:confirm="Yakin ingin menghapus section ini beserta semua details?"
                                                            class="w-9 h-9 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all flex items-center justify-center">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Details (Collapsible) -->
                                        @if(in_array($section->id, $expandedSections))
                                            <div class="p-4 bg-gradient-to-br from-blue-50 to-cyan-50">
                                                <div class="flex justify-between items-center mb-3">
                                                    <h6 class="text-sm font-bold text-slate-700 flex items-center gap-2">
                                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                                                        </svg>
                                                        Detail Items
                                                    </h6>
                                                    <div class="flex gap-2">
                                                        <button wire:click="createDetail({{ $section->id }})"
                                                                class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold text-xs transition-all flex items-center gap-1">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                                            </svg>
                                                            Single
                                                        </button>
                                                        <button wire:click="createMultipleDetails({{ $section->id }})"
                                                                class="px-3 py-1.5 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white rounded-lg font-bold text-xs transition-all flex items-center gap-1">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" />
                                                            </svg>
                                                            Multiple
                                                        </button>
                                                    </div>
                                                </div>


                                                <div class="space-y-2">
                                                    @forelse($section->details as $detail)
                                                        <div class="p-3 bg-white rounded-lg border border-blue-200 hover:border-blue-400 transition-all">
                                                            <div class="flex items-start justify-between">
                                                                <div class="flex-grow">
                                                                    <div class="flex items-center gap-2 mb-1">
                                                                        <span class="px-2 py-0.5 bg-slate-100 rounded text-xs font-bold text-slate-700">{{ $detail->item_code }}</span>
                                                                        <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded text-xs font-bold">{{ $detail->check_type }}</span>
                                                                        @if($detail->is_critical)
                                                                            <span class="px-2 py-0.5 bg-red-100 text-red-700 rounded text-xs font-bold">🔴 Critical</span>
                                                                        @endif
                                                                    </div>
                                                                    <p class="text-sm font-semibold text-slate-800">{{ $detail->item_name }}</p>
                                                                    @if($detail->item_description)
                                                                        <p class="text-xs text-slate-500 mt-1">{{ $detail->item_description }}</p>
                                                                    @endif
                                                                    <div class="flex items-center gap-3 mt-2 text-xs text-slate-400">
                                                                        @if($detail->min_value || $detail->max_value)
                                                                            <span>Range: {{ $detail->min_value ?? '-' }} - {{ $detail->max_value ?? '-' }} {{ $detail->unit }}</span>
                                                                        @endif
                                                                        @if($detail->standard)
                                                                            <span>•</span>
                                                                            <span>Std: {{ $detail->standard }}</span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="flex items-center gap-1 ml-3">
                                                                    <button wire:click="editDetail({{ $detail->id }})"
                                                                            class="w-8 h-8 rounded-lg bg-green-50 text-green-500 hover:bg-green-500 hover:text-white transition-all flex items-center justify-center">
                                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                                        </svg>
                                                                    </button>
                                                                    <button wire:click="deleteDetail({{ $detail->id }})"
                                                                            wire:confirm="Yakin ingin menghapus detail ini?"
                                                                            class="w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all flex items-center justify-center">
                                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                                        </svg>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @empty
                                                        <div class="text-center py-6 text-slate-400 text-sm">
                                                            <svg class="w-12 h-12 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                                            </svg>
                                                            Belum ada detail item
                                                        </div>
                                                    @endforelse
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <div class="text-center py-8 text-slate-400">
                                        <svg class="w-16 h-16 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12" />
                                        </svg>
                                        <p class="font-medium">Belum ada section</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-16">
                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h.008a.75.75 0 00.75-.75 2.25 2.25 0 00-.659-1.591l-.007-.007a2.25 2.25 0 00-1.591-.659h-.033z" />
                        </svg>
                    </div>
                    <p class="text-slate-400 font-medium">Tidak ada data checksheet</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $checksheets->links() }}
        </div>
    </main>

    <!-- Modal Head -->
    @if($showHeadModal)
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-6">
            <div class="bg-white rounded-3xl max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl" wire:click.stop>
                <div class="sticky top-0 bg-white border-b border-slate-100 px-8 py-6 rounded-t-3xl z-10">
                    <div class="flex justify-between items-center">
                        <h2 class="text-2xl font-black text-slate-800">
                            {{ $editMode ? '✏️ Edit Checksheet' : '✨ Tambah Checksheet Baru' }}
                        </h2>
                        <button wire:click="closeHeadModal" class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-all">
                            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <form wire:submit="saveHead" class="p-8 space-y-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Kode Checksheet *</label>
                        <input type="text" wire:model="code" class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:border-slate-400 focus:ring-4 focus:ring-slate-100 transition-all outline-none font-medium @error('code') border-red-300 @enderror">
                        @error('code') <p class="mt-2 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Judul Checksheet *</label>
                        <input type="text" wire:model="title" class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:border-slate-400 focus:ring-4 focus:ring-slate-100 transition-all outline-none font-medium @error('title') border-red-300 @enderror">
                        @error('title') <p class="mt-2 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Subtitle</label>
                        <input type="text" wire:model="subtitle" class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:border-slate-400 focus:ring-4 focus:ring-slate-100 transition-all outline-none font-medium">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Revisi *</label>
                            <input type="text" wire:model="revision" class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:border-slate-400 focus:ring-4 focus:ring-slate-100 transition-all outline-none font-medium @error('revision') border-red-300 @enderror">
                            @error('revision') <p class="mt-2 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Order</label>
                            <input type="number" wire:model="order" class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:border-slate-400 focus:ring-4 focus:ring-slate-100 transition-all outline-none font-medium">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Nomor Dokumen *</label>
                        <input type="text" wire:model="document_number" class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:border-slate-400 focus:ring-4 focus:ring-slate-100 transition-all outline-none font-medium @error('document_number') border-red-300 @enderror">
                        @error('document_number') <p class="mt-2 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Nama Proses</label>
                        <input type="text" wire:model="process_name" class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:border-slate-400 focus:ring-4 focus:ring-slate-100 transition-all outline-none font-medium">
                    </div>

                    <div class="flex items-center gap-3">
                        <input type="checkbox" wire:model="is_active" id="is_active" class="w-5 h-5 rounded text-slate-600 focus:ring-2 focus:ring-slate-400">
                        <label for="is_active" class="text-sm font-semibold text-slate-700">Aktifkan checksheet ini</label>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="button" wire:click="closeHeadModal" class="flex-1 px-5 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition-all">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 px-5 py-3 bg-app-gradient text-white font-bold rounded-xl hover:shadow-xl transition-all">
                            {{ $editMode ? '💾 Update' : '✨ Simpan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal Section -->
    @if($showSectionModal)
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-6">
            <div class="bg-white rounded-3xl max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl" wire:click.stop>
                <div class="sticky top-0 bg-white border-b border-slate-100 px-8 py-6 rounded-t-3xl z-10">
                    <div class="flex justify-between items-center">
                        <h2 class="text-2xl font-black text-slate-800">
                            {{ $editMode ? '✏️ Edit Section' : '✨ Tambah Section Baru' }}
                        </h2>
                        <button wire:click="closeSectionModal" class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-all">
                            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <form wire:submit="saveSection" class="p-8 space-y-6">
                     <!-- Alert Success -->
        @if (session()->has('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-2xl flex items-center gap-3 text-green-700 animate-fade-in">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="font-semibold text-sm">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Alert Error -->
    @if (session()->has('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl flex items-center gap-3 text-red-700 animate-fade-in">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
            <span class="font-semibold text-sm">{{ session('error') }}</span>
        </div>
    @endif
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Section Name *</label>
                        <input type="text" wire:model="section_name" placeholder="Nama section..." class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:border-slate-400 focus:ring-4 focus:ring-slate-100 transition-all outline-none font-medium @error('section_name') border-red-300 @enderror">
                        @error('section_name') <p class="mt-2 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Section Number *</label>
                            <input type="number" wire:model="section_number" class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:border-slate-400 focus:ring-4 focus:ring-slate-100 transition-all outline-none font-medium @error('section_number') border-red-300 @enderror">
                            @error('section_number') <p class="mt-2 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Order *</label>
                            <input type="number" wire:model="section_order" class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:border-slate-400 focus:ring-4 focus:ring-slate-100 transition-all outline-none font-medium @error('section_order') border-red-300 @enderror">
                            @error('section_order') <p class="mt-2 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">
                            📸 Section Images (Multiple)
                        </label>
                        <input type="file"
                               wire:model="section_images"
                               accept="image/*"
                               multiple
                               class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:border-slate-400 focus:ring-4 focus:ring-slate-100 transition-all outline-none font-medium bg-white @error('section_images.*') border-red-300 @enderror">

                        <p class="mt-2 text-xs text-slate-500">💡 Pilih multiple gambar dengan Ctrl/Cmd + Click</p>

                        @error('section_images.*')
                            <p class="mt-2 text-xs text-red-500 font-semibold">{{ $message }}</p>
                        @enderror

                        <!-- Preview New Uploads -->
                        @if($section_images)
                            <div class="mt-4">
                                <p class="text-xs font-bold text-green-600 mb-2">✨ New Images ({{ count($section_images) }})</p>
                                <div class="grid grid-cols-4 gap-3">
                                    @foreach($section_images as $image)
                                        <div class="relative group">
                                            <img src="{{ $image->temporaryUrl() }}"
                                                 class="h-20 w-full object-cover rounded-lg border-2 border-green-200">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Existing Images with Delete Button -->
                        @if($existing_section_images && count($existing_section_images) > 0)
                            <div class="mt-4">
                                <p class="text-xs font-bold text-purple-600 mb-2">📁 Existing Images ({{ count($existing_section_images) }})</p>
                                <div class="grid grid-cols-4 gap-3">
                                    @foreach($existing_section_images as $image)
                                        <div class="relative group">
                                            <img src="{{ asset('images/section/' . $image) }}"
                                                 class="h-20 w-full object-cover rounded-lg border-2 border-purple-200">
                                            <button type="button"
                                                    wire:click="removeImage('{{ $image }}')"
                                                    wire:confirm="Hapus gambar ini?"
                                                    class="absolute top-1 right-1 w-6 h-6 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div wire:loading wire:target="section_images" class="mt-3 text-xs text-purple-600 font-semibold">
                            ⏳ Uploading images...
                        </div>
                    </div>


                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Description</label>
                        <textarea wire:model="section_description" rows="3" placeholder="Deskripsi section..." class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:border-slate-400 focus:ring-4 focus:ring-slate-100 transition-all outline-none font-medium"></textarea>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="button" wire:click="closeSectionModal" class="flex-1 px-5 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition-all">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 px-5 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold rounded-xl hover:shadow-xl transition-all">
                            {{ $editMode ? '💾 Update' : '✨ Simpan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

  <!-- Modal Detail -->
@if($showDetailModal)
<div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-6">
    <div class="bg-white rounded-3xl max-w-6xl w-full max-h-[90vh] overflow-y-auto shadow-2xl" wire:click.stop>
        <div class="sticky top-0 bg-white border-b border-slate-100 px-8 py-6 rounded-t-3xl z-10">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-black text-slate-800">
                    @if($multipleDetailMode)
                        ✨ Tambah Multiple Detail Items
                    @else
                        {{ $editMode ? '✏️ Edit Detail Item' : '✨ Tambah Detail Item Baru' }}
                    @endif
                </h2>
                <button wire:click="closeDetailModal" class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-all">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        @if($multipleDetailMode)
            <!-- MULTIPLE INPUT MODE -->
            <form wire:submit="saveMultipleDetails" class="p-8">
                <div class="mb-6 p-4 bg-blue-50 rounded-xl border border-blue-200">
                    <p class="text-sm text-blue-700 font-semibold">💡 Tips: Isi minimal Item Code dan Item Name. Field kosong tidak akan disimpan.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gradient-to-r from-blue-50 to-cyan-50">
                                <th class="px-3 py-3 text-left text-xs font-bold text-slate-700 border border-slate-200">No</th>
                                <th class="px-3 py-3 text-left text-xs font-bold text-slate-700 border border-slate-200">Item Code *</th>
                                <th class="px-3 py-3 text-left text-xs font-bold text-slate-700 border border-slate-200">Item Name *</th>
                                <th class="px-3 py-3 text-left text-xs font-bold text-slate-700 border border-slate-200">Q-Point</th>
                                <th class="px-3 py-3 text-left text-xs font-bold text-slate-700 border border-slate-200">Check Type</th>
                                <th class="px-3 py-3 text-left text-xs font-bold text-slate-700 border border-slate-200">Critical</th>
                                <th class="px-3 py-3 text-center text-xs font-bold text-slate-700 border border-slate-200">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($detailItems as $index => $item)
                                <tr class="hover:bg-blue-50 transition-colors" wire:key="detail-row-{{ $index }}">
                                    <td class="px-3 py-2 border border-slate-200">
                                        <span class="font-bold text-slate-600">{{ $index + 1 }}</span>
                                    </td>
                                    <td class="px-3 py-2 border border-slate-200">
                                        <input type="text"
                                               wire:model="detailItems.{{ $index }}.item_code"
                                               placeholder="Kode..."
                                               class="w-full px-2 py-1.5 text-sm rounded border border-slate-200 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none">
                                    </td>
                                    <td class="px-3 py-2 border border-slate-200">
                                        <input type="text"
                                               wire:model="detailItems.{{ $index }}.item_name"
                                               placeholder="Nama item..."
                                               class="w-full px-2 py-1.5 text-sm rounded border border-slate-200 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none">
                                    </td>
                                    <td class="px-3 py-2 border border-slate-200">
                                        <input type="text"
                                               wire:model="detailItems.{{ $index }}.qpoint_name"
                                               placeholder="Q-Point..."
                                               class="w-full px-2 py-1.5 text-sm rounded border border-slate-200 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none">
                                    </td>
                                    <td class="px-3 py-2 border border-slate-200">
                                        <select wire:model="detailItems.{{ $index }}.check_type"
                                                class="w-full px-2 py-1.5 text-sm rounded border border-slate-200 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none">
                                            <option value="visual">Visual</option>
                                            <option value="measurement">Measurement</option>
                                            <option value="functional">Functional</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </td>
                                    <td class="px-3 py-2 border border-slate-200 text-center">
                                        <input type="checkbox"
                                               wire:model="detailItems.{{ $index }}.is_critical"
                                               class="w-4 h-4 rounded text-red-600 focus:ring-2 focus:ring-red-400">
                                    </td>
                                    <td class="px-3 py-2 border border-slate-200 text-center">
                                        <button type="button"
                                                wire:click="removeDetailRow({{ $index }})"
                                                class="px-2 py-1 bg-red-50 hover:bg-red-500 text-red-500 hover:text-white rounded transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <button type="button"
                        wire:click="addDetailRow"
                        class="mt-4 w-full py-2 border-2 border-dashed border-blue-300 hover:border-blue-400 hover:bg-blue-50 rounded-xl text-blue-600 font-semibold text-sm transition-all">
                    + Tambah Baris
                </button>

                <div class="flex gap-3 pt-6 mt-6 border-t border-slate-200">
                    <button type="button" wire:click="closeDetailModal" class="flex-1 px-5 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition-all">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 px-5 py-3 bg-gradient-to-r from-blue-600 to-cyan-600 text-white font-bold rounded-xl hover:shadow-xl transition-all">
                        💾 Simpan Semua ({{ count($detailItems) }} items)
                    </button>
                </div>
            </form>

        @else
            <!-- SINGLE INPUT MODE (existing code) -->
            <form wire:submit="saveDetail" class="p-8 space-y-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Item Code *</label>
                        <input type="text" wire:model="item_code" placeholder="Kode item..." class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:border-slate-400 focus:ring-4 focus:ring-slate-100 transition-all outline-none font-medium @error('item_code') border-red-300 @enderror">
                        @error('item_code') <p class="mt-2 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Check Type *</label>
                        <select wire:model="check_type" class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:border-slate-400 focus:ring-4 focus:ring-slate-100 transition-all outline-none font-medium">
                            <option value="visual">Visual</option>
                            <option value="measurement">Measurement</option>
                            <option value="functional">Functional</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Item Name *</label>
                    <input type="text" wire:model="item_name" placeholder="Nama item..." class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:border-slate-400 focus:ring-4 focus:ring-slate-100 transition-all outline-none font-medium @error('item_name') border-red-300 @enderror">
                    @error('item_name') <p class="mt-2 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Item Description</label>
                    <textarea wire:model="item_description" rows="2" placeholder="Deskripsi item..." class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:border-slate-400 focus:ring-4 focus:ring-slate-100 transition-all outline-none font-medium"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">QPoint Code</label>
                        <input type="text" wire:model="qpoint_code" placeholder="Kode QPoint..." class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:border-slate-400 focus:ring-4 focus:ring-slate-100 transition-all outline-none font-medium">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">QPoint Name</label>
                        <input type="text" wire:model="qpoint_name" placeholder="Nama QPoint..." class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:border-slate-400 focus:ring-4 focus:ring-slate-100 transition-all outline-none font-medium">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Inspection Criteria</label>
                    <textarea wire:model="inspection_criteria" rows="2" placeholder="Kriteria inspeksi..." class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:border-slate-400 focus:ring-4 focus:ring-slate-100 transition-all outline-none font-medium"></textarea>
                </div>

                <div class="grid grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Min Value</label>
                        <input type="number" step="0.01" wire:model="min_value" placeholder="0.00" class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:border-slate-400 focus:ring-4 focus:ring-slate-100 transition-all outline-none font-medium">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Max Value</label>
                        <input type="number" step="0.01" wire:model="max_value" placeholder="0.00" class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:border-slate-400 focus:ring-4 focus:ring-slate-100 transition-all outline-none font-medium">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Unit</label>
                        <input type="text" wire:model="unit" placeholder="mm, kg..." class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:border-slate-400 focus:ring-4 focus:ring-slate-100 transition-all outline-none font-medium">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Standard</label>
                        <input type="text" wire:model="standard" placeholder="Standard..." class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:border-slate-400 focus:ring-4 focus:ring-slate-100 transition-all outline-none font-medium">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">OK Criteria</label>
                        <textarea wire:model="ok_criteria" rows="2" placeholder="Kriteria OK..." class="w-full px-5 py-3 rounded-xl border-2 border-green-200 focus:border-green-400 focus:ring-4 focus:ring-green-100 transition-all outline-none font-medium"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">NG Criteria</label>
                        <textarea wire:model="ng_criteria" rows="2" placeholder="Kriteria NG..." class="w-full px-5 py-3 rounded-xl border-2 border-red-200 focus:border-red-400 focus:ring-4 focus:ring-red-100 transition-all outline-none font-medium"></textarea>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="flex items-center gap-3 p-4 bg-red-50 rounded-xl">
                        <input type="checkbox" wire:model="is_critical" id="is_critical_modal" class="w-5 h-5 rounded text-red-600 focus:ring-2 focus:ring-red-400">
                        <label for="is_critical_modal" class="text-sm font-bold text-slate-700">🔴 Critical Item</label>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Order *</label>
                        <input type="number" wire:model="detail_order" class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:border-slate-400 focus:ring-4 focus:ring-slate-100 transition-all outline-none font-medium @error('detail_order') border-red-300 @enderror">
                        @error('detail_order') <p class="mt-2 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" wire:click="closeDetailModal" class="flex-1 px-5 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition-all">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 px-5 py-3 bg-gradient-to-r from-blue-600 to-cyan-600 text-white font-bold rounded-xl hover:shadow-xl transition-all">
                        {{ $editMode ? '💾 Update' : '✨ Simpan' }}
                    </button>
                </div>
            </form>
        @endif
    </div>
</div>
@endif

</div>
