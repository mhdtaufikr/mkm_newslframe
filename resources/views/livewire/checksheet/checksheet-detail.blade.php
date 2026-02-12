<div class="page-fade min-h-screen pb-32">
    <!-- Header -->
    <header class="sticky top-0 z-50 bg-white/70 backdrop-blur-xl border-b border-slate-100 px-6 py-3">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-3">
                <a href="{{ route('home.index') }}" class="w-9 h-9 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 flex items-center justify-center transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                </a>
                <div>
                    <div class="flex items-center gap-2 mb-0.5">
                        <span class="px-2 py-0.5 bg-slate-100 rounded text-xs font-bold text-slate-600">Rev. {{ $checksheet->revision }}</span>
                        <span class="px-2 py-0.5 bg-slate-100 rounded text-xs font-bold text-slate-600">{{ $checksheet->document_number }}</span>
                    </div>
                    <h1 class="text-lg font-bold text-slate-800">{{ $checksheet->title }}</h1>
                    @if($checksheet->subtitle)
                        <p class="text-xs text-slate-500 font-medium">{{ $checksheet->subtitle }}</p>
                    @endif
                </div>
            </div>
            <button wire:click="submitInspection" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-lg font-bold text-sm transition-all">
                💾 Simpan
            </button>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-6">
        <!-- Form Header Info -->
        <div class="bg-white border border-slate-200 rounded-xl p-4 mb-4 shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Nama</label>
                    <input type="text" wire:model.defer="nama" placeholder="Nama inspector..."
                           class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:border-slate-500 focus:ring-2 focus:ring-slate-200 transition-all outline-none text-sm">
                    @error('nama') <p class="mt-1 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Tanggal</label>
                    <input type="date" wire:model.defer="tanggal"
                           class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:border-slate-500 focus:ring-2 focus:ring-slate-200 transition-all outline-none text-sm">
                    @error('tanggal') <p class="mt-1 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Serial Number</label>
                    <input type="text" wire:model.defer="serial_number" placeholder="Serial number..."
                           class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:border-slate-500 focus:ring-2 focus:ring-slate-200 transition-all outline-none text-sm">
                </div>
            </div>
        </div>

        <!-- Sections dengan Form Inspeksi -->
        <div class="space-y-4">
            @forelse($sections as $section)
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                    <!-- Section Header -->
                    <div class="px-4 py-3 bg-slate-100 cursor-pointer hover:bg-slate-200 transition-colors" wire:click="toggleSection({{ $section->id }})">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-slate-700 text-white rounded-lg flex items-center justify-center font-bold text-base">
                                    {{ $section->section_number }}
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-slate-800">{{ $section->section_name }}</h3>
                                    <p class="text-xs text-slate-500">{{ $section->details->count() }} items to check</p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-slate-600 transition-transform {{ in_array($section->id, $expandedSections) ? 'rotate-180' : '' }}"
                                 fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </div>
                    </div>

                    <!-- Section Image & Description -->
                    @if(in_array($section->id, $expandedSections))
                    <div x-data="{ showModal: false, selectedImage: '' }">
                        @if($section->section_images || $section->section_description)
                            <div class="px-4 py-3 bg-slate-50 border-b border-slate-200">
                                <p class="text-xs font-bold text-slate-500 uppercase mb-2">📸 SKETCH SECTION</p>

                                <!-- Multiple Images Display -->
                                @if($section->section_images)
                                @php
                                    $images = json_decode($section->section_images, true);
                                @endphp
                                @if(is_array($images) && count($images) > 0)
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($images as $image)
                                            <div class="relative group bg-slate-100 rounded-lg overflow-hidden cursor-pointer shadow-sm hover:shadow-md transition-shadow"
                                                style="width: 120px; height: 120px;"
                                                @click="showModal = true; selectedImage = '{{ $image }}'">
                                                <img src="{{ asset('images/section/' . $image) }}"
                                                    alt="{{ $section->section_name }}"
                                                    class="w-full h-full object-cover group-hover:scale-110 transition-all duration-300">
                                                <!-- Hover Overlay -->
                                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-white opacity-0 group-hover:opacity-100 transition-all" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607zM10.5 7.5v6m3-3h-6" />
                                                    </svg>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <p class="text-xs text-slate-400 italic mt-2">💡 Klik gambar untuk zoom ({{ count($images) }} images)</p>
                                @endif
                                @endif

                                @if($section->section_description)
                                    <p class="text-sm text-slate-600 mt-3 leading-relaxed">{{ $section->section_description }}</p>
                                @endif
                            </div>
                        @endif

                        <!-- Image Zoom Modal -->
                        <div x-show="showModal"
                             x-cloak
                             @click="showModal = false"
                             class="fixed inset-0 bg-black/90 backdrop-blur-sm z-[100] flex items-center justify-center p-4 animate-fade-in">
                            <div class="relative w-full max-w-7xl max-h-[95vh] flex items-center justify-center" @click.stop>
                                <!-- Close Button -->
                                <button @click="showModal = false"
                                        class="absolute -top-4 -right-4 w-12 h-12 bg-white hover:bg-red-500 hover:text-white rounded-full flex items-center justify-center shadow-xl transition-all z-10 group">
                                    <svg class="w-6 h-6 text-slate-700 group-hover:text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>

                                <!-- Image Container -->
                                <div class="flex items-center justify-center w-full h-full">
                                    <img :src="'{{ asset('images/section') }}/' + selectedImage"
                                         alt="Zoomed Image"
                                         class="max-w-full max-h-[90vh] w-auto h-auto rounded-xl shadow-2xl object-contain">
                                </div>

                                <!-- Image Info -->
                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-6 rounded-b-xl">
                                    <p class="text-sm font-semibold text-white" x-text="selectedImage"></p>
                                    <p class="text-xs text-slate-300">Klik di luar gambar atau tombol × untuk menutup</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Inspection Table -->
<div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-slate-50 border-b border-slate-200">
                <th class="px-3 py-2 text-center text-xs font-bold text-slate-700 uppercase w-12">NO</th>
                <th class="px-3 py-2 text-left text-xs font-bold text-slate-700 uppercase">ITEM CHECK</th>
                <th class="px-3 py-2 text-left text-xs font-bold text-slate-700 uppercase">Q-POINT</th>
                <th class="px-3 py-2 text-center text-xs font-bold text-slate-700 uppercase w-16">OK</th>
                <th class="px-3 py-2 text-center text-xs font-bold text-slate-700 uppercase w-16">NG</th>
                <th class="px-3 py-2 text-left text-xs font-bold text-slate-700 uppercase w-64">STATUS / REMARKS</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @foreach($section->details as $index => $detail)
                @php
                    $isOk = isset($checkResults[$detail->id]['result']) && $checkResults[$detail->id]['result'] === 'ok';
                    $isNg = isset($checkResults[$detail->id]['result']) && $checkResults[$detail->id]['result'] === 'ng';
                    $isCritical = $detail->is_critical;
                @endphp

                <tr class="transition-colors {{ $isCritical ? 'bg-red-50' : 'bg-white hover:bg-slate-50' }}">

                    <!-- No -->
                    <td class="px-3 py-2 text-center">
                        <span class="font-bold text-slate-700">{{ $index + 1 }}</span>
                    </td>

                    <!-- Item Check -->
                    <td class="px-3 py-2">
                        <div>
                            <p class="font-semibold text-slate-800 leading-tight">{{ $detail->item_name }}</p>
                            @if($detail->item_code)
                                <span class="inline-block mt-0.5 text-xs text-slate-500 font-medium">{{ $detail->item_code }}</span>
                            @endif
                        </div>
                    </td>

                    <!-- Q-Point -->
                    <td class="px-3 py-2">
                        @if($detail->qpoint_name)
                            <p class="text-sm text-slate-700">{{ $detail->qpoint_name }}</p>
                            @if($detail->qpoint_code)
                                <span class="text-xs text-slate-500">{{ $detail->qpoint_code }}</span>
                            @endif
                        @else
                            <span class="text-slate-400">-</span>
                        @endif
                    </td>

                    <!-- OK Radio -->
                    <td class="px-3 py-2 text-center w-20">
                        <div class="flex justify-center items-center">
                            <label class="p-2 rounded-lg cursor-pointer transition-all {{ $isOk ? 'bg-green-500 shadow-sm' : 'bg-green-50 hover:bg-green-100' }}">
                                <input type="radio"
                                       name="check_{{ $detail->id }}"
                                       wire:model.defer="checkResults.{{ $detail->id }}.result"
                                       value="ok"
                                       class="w-5 h-5 border-slate-300 focus:ring-0 cursor-pointer {{ $isOk ? 'accent-white' : 'text-green-600' }}">
                            </label>
                        </div>
                    </td>

                    <!-- NG Radio -->
                    <td class="px-3 py-2 text-center w-20">
                        <div class="flex justify-center items-center">
                            <label class="p-2 rounded-lg cursor-pointer transition-all {{ $isNg ? 'bg-red-500 shadow-sm' : 'bg-red-50 hover:bg-red-100' }}">
                                <input type="radio"
                                       name="check_{{ $detail->id }}"
                                       wire:model.defer="checkResults.{{ $detail->id }}.result"
                                       value="ng"
                                       class="w-5 h-5 border-slate-300 focus:ring-0 cursor-pointer {{ $isNg ? 'accent-white' : 'text-red-600' }}">
                            </label>
                        </div>
                    </td>

                    <!-- Status / Remarks -->
                    <td class="px-3 py-2">
                        <input type="text"
                               wire:model.defer="checkResults.{{ $detail->id }}.status"
                               placeholder="Status / Remarks..."
                               class="w-full px-2 py-1.5 rounded border border-slate-300 focus:border-slate-500 focus:ring-2 focus:ring-slate-200 transition-all outline-none text-sm bg-white">
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>


                    <!-- OK/NG Criteria Info -->
                    @if($section->details->whereNotNull('ok_criteria')->count() > 0 || $section->details->whereNotNull('ng_criteria')->count() > 0)
                        <div class="px-4 py-3 bg-slate-50 border-t border-slate-200">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div class="p-3 bg-green-50 rounded-lg border border-green-200">
                                    <h4 class="text-xs font-bold text-green-700 uppercase mb-1.5">✓ OK Criteria</h4>
                                    <ul class="space-y-0.5 text-xs text-green-700">
                                        @foreach($section->details->whereNotNull('ok_criteria') as $detail)
                                            @if($detail->ok_criteria)
                                                <li class="flex items-start gap-1">
                                                    <span class="text-green-500 font-bold">•</span>
                                                    <span>{{ $detail->ok_criteria }}</span>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="p-3 bg-red-50 rounded-lg border border-red-200">
                                    <h4 class="text-xs font-bold text-red-700 uppercase mb-1.5">✗ NG Criteria</h4>
                                    <ul class="space-y-0.5 text-xs text-red-700">
                                        @foreach($section->details->whereNotNull('ng_criteria') as $detail)
                                            @if($detail->ng_criteria)
                                                <li class="flex items-start gap-1">
                                                    <span class="text-red-500 font-bold">•</span>
                                                    <span>{{ $detail->ng_criteria }}</span>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                    @endif
                </div>
            @empty
                <div class="text-center py-16">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12" />
                        </svg>
                    </div>
                    <p class="text-slate-400 font-medium">Belum ada section di checksheet ini</p>
                </div>
            @endforelse
        </div>
{{-- Action Buttons dengan loading state di tombol --}}
@if($sections->count() > 0)
<div class="mt-6 sticky bottom-4 z-40">
    <div class="bg-white border-2 border-slate-300 rounded-xl p-3 shadow-lg">
        <div class="flex gap-3">
            <a href="{{ route('home.index') }}"
               class="flex-1 px-5 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-lg text-center transition-all text-sm">
                ← Batal
            </a>
            <button wire:click="submitInspection"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-50 cursor-not-allowed"
                    class="flex-1 px-5 py-3 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-lg transition-all flex items-center justify-center gap-2 text-sm disabled:hover:bg-slate-800">

                <svg wire:loading.remove wire:target="submitInspection"
                     class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>

                <svg wire:loading wire:target="submitInspection"
                     class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>

                <span wire:loading.remove wire:target="submitInspection">💾 Simpan Hasil Inspeksi</span>
                <span wire:loading wire:target="submitInspection">⏳ Menyimpan...</span>
            </button>
        </div>
    </div>
</div>
@endif
{{-- Loading Overlay (HANYA muncul saat submitInspection) --}}
<div wire:loading.class="flex"
     wire:loading.class.remove="hidden"
     wire:target="submitInspection"
     class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-[999] items-center justify-center p-4">
    <div class="bg-white rounded-2xl p-8 shadow-2xl flex flex-col items-center justify-center gap-4 w-full max-w-sm">
        <svg class="animate-spin h-16 w-16 text-slate-800" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>

        <div class="text-center w-full">
            <h3 class="text-lg font-bold text-slate-800 mb-1">Menyimpan Hasil Inspeksi</h3>
            <p class="text-sm text-slate-500">Mohon tunggu, jangan tutup halaman ini...</p>
        </div>

        <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
            <div class="bg-slate-800 h-full rounded-full animate-pulse w-[70%]"></div>
        </div>
    </div>
</div>



    </main>
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.2s ease-out;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</div>


