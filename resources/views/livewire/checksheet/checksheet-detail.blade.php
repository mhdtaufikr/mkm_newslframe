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

        <!-- Flash Messages -->
        @if(session('error'))
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl flex items-start gap-3">
                <svg class="w-5 h-5 text-red-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
                <p class="text-sm font-semibold text-red-700">{{ session('error') }}</p>
            </div>
        @endif

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl flex items-start gap-3">
                <svg class="w-5 h-5 text-green-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm font-semibold text-green-700">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Form Header Info -->
        <div class="bg-white border border-slate-200 rounded-xl p-4 mb-4 shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Nama</label>
                    <input type="text"
                           wire:model.defer="nama"
                           readonly
                           class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-slate-100 text-slate-500 cursor-not-allowed outline-none text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Tanggal</label>
                    <input type="date"
                           wire:model.defer="tanggal"
                           readonly
                           class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-slate-100 text-slate-500 cursor-not-allowed outline-none text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Serial Number</label>
                    <input type="text"
                           wire:model.defer="serial_number"
                           readonly
                           class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-slate-100 text-slate-500 cursor-not-allowed outline-none text-sm font-mono">
                </div>
            </div>
        </div>

        <!-- Sections -->
        <div class="space-y-4">
            @forelse($sections as $section)
                @php
                    // Progress per section
                    $totalItems  = $section->details->count();
                    $filledItems = 0;

                    if ($checksheet->process_name === 'Welding') {
                        foreach ($section->details as $d) {
                            $isManualOk = (bool) ($checkResults[$d->id]['is_ok'] ?? false);
                            $ngSum = 0;
                            foreach ($weldingNgTypes as $t) {
                                $k = 'ng_' . strtolower(str_replace(' ', '_', $t));
                                $ngSum += (int) ($checkResults[$d->id][$k] ?? 0);
                            }
                            if ($isManualOk || $ngSum > 0) $filledItems++;
                        }
                    } else {
                        foreach ($section->details as $d) {
                            if (!empty($checkResults[$d->id]['result'])) $filledItems++;
                        }
                    }

                    $remaining = $totalItems - $filledItems;
                    $pct       = $totalItems > 0 ? round(($filledItems / $totalItems) * 100) : 0;
                @endphp

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
                            <div class="flex items-center gap-3">
                                <!-- Progress Badge -->
                                <span class="text-xs font-bold px-2 py-1 rounded-lg {{ $pct === 100 ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ $filledItems }}/{{ $totalItems }}
                                </span>
                                <svg class="w-5 h-5 text-slate-600 transition-transform {{ in_array($section->id, $expandedSections) ? 'rotate-180' : '' }}"
                                     fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="mt-2 w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                            <div class="h-1.5 rounded-full transition-all duration-300 {{ $pct === 100 ? 'bg-green-500' : 'bg-yellow-400' }}"
                                 style="width: {{ $pct }}%"></div>
                        </div>
                    </div>

                    @if(in_array($section->id, $expandedSections))
                    <div x-data="{ showModal: false, selectedImage: '' }">

                        <!-- Section Image & Description -->
                        @if($section->section_images || $section->section_description)
                            <div class="px-4 py-3 bg-slate-50 border-b border-slate-200">
                                <p class="text-xs font-bold text-slate-500 uppercase mb-2">📸 SKETCH SECTION</p>

                                @if($section->section_images)
                                    @php $images = json_decode($section->section_images, true); @endphp
                                    @if(is_array($images) && count($images) > 0)
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($images as $image)
                                                <div class="relative group bg-slate-100 rounded-lg overflow-hidden cursor-pointer shadow-sm hover:shadow-md transition-shadow"
                                                     style="width: 120px; height: 120px;"
                                                     @click="showModal = true; selectedImage = '{{ $image }}'">
                                                    <img src="{{ asset('images/section/' . $image) }}"
                                                         alt="{{ $section->section_name }}"
                                                         class="w-full h-full object-cover group-hover:scale-110 transition-all duration-300">
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
                             class="fixed inset-0 bg-black/95 backdrop-blur-sm z-[100] flex items-center justify-center animate-fade-in">
                            <div class="relative w-full h-full flex items-center justify-center" @click.stop>
                                <button @click="showModal = false"
                                        class="absolute top-4 right-4 w-12 h-12 bg-white hover:bg-red-500 hover:text-white rounded-full flex items-center justify-center shadow-xl transition-all z-10 group">
                                    <svg class="w-6 h-6 text-slate-700 group-hover:text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                                <img :src="'{{ asset('images/section') }}/' + selectedImage"
                                     alt="Zoomed Image"
                                     class="w-screen h-screen object-contain">
                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-6">
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
                                @if($checksheet->process_name === 'Welding')
                                    <tr class="bg-slate-50 border-b border-slate-200">
                                        <th class="px-3 py-2 text-center text-xs font-bold text-slate-700 uppercase w-12" rowspan="2">NO</th>
                                        <th class="px-3 py-2 text-left text-xs font-bold text-slate-700 uppercase" rowspan="2">ITEM CHECK</th>
                                        <th class="px-3 py-2 text-center text-xs font-bold text-slate-700 uppercase" colspan="2">Σ WELDING</th>
                                        <th class="px-3 py-2 text-left text-xs font-bold text-slate-700 uppercase" rowspan="2">Q-POINT</th>
                                        <th class="px-3 py-2 text-center text-xs font-bold text-slate-700 uppercase w-16" rowspan="2">
                                            OK <span class="text-red-500">*</span>
                                        </th>
                                        <th class="px-3 py-2 text-center text-xs font-bold text-red-600 uppercase" colspan="{{ count($weldingNgTypes) }}">WELDING NG</th>
                                        <th class="px-3 py-2 text-left text-xs font-bold text-slate-700 uppercase w-48" rowspan="2">STATUS / REMARKS</th>
                                    </tr>
                                    <tr class="bg-slate-50 border-b border-slate-200">
                                        <th class="px-3 py-2 text-center text-xs font-bold text-slate-700 uppercase w-16">ATAS</th>
                                        <th class="px-3 py-2 text-center text-xs font-bold text-slate-700 uppercase w-16">BAWAH</th>
                                        @foreach($weldingNgTypes as $ngType)
                                            <th class="px-3 py-2 text-center text-xs font-bold text-red-600 uppercase w-20">
                                                {{ strtoupper($ngType) }}
                                            </th>
                                        @endforeach
                                    </tr>
                                @else
                                    <tr class="bg-slate-50 border-b border-slate-200">
                                        <th class="px-3 py-2 text-center text-xs font-bold text-slate-700 uppercase w-12">NO</th>
                                        <th class="px-3 py-2 text-left text-xs font-bold text-slate-700 uppercase">ITEM CHECK</th>
                                        <th class="px-3 py-2 text-left text-xs font-bold text-slate-700 uppercase">Q-POINT</th>
                                        <th class="px-3 py-2 text-center text-xs font-bold text-slate-700 uppercase w-16">
                                            OK <span class="text-red-500">*</span>
                                        </th>
                                        <th class="px-3 py-2 text-center text-xs font-bold text-slate-700 uppercase w-16">
                                            NG <span class="text-red-500">*</span>
                                        </th>
                                        <th class="px-3 py-2 text-left text-xs font-bold text-slate-700 uppercase w-64">STATUS / REMARKS</th>
                                    </tr>
                                @endif
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($section->details as $index => $detail)
                                    @php
                                        $isOk       = isset($checkResults[$detail->id]['result']) && $checkResults[$detail->id]['result'] === 'ok';
                                        $isNg       = isset($checkResults[$detail->id]['result']) && $checkResults[$detail->id]['result'] === 'ng';
                                        $isCritical = $detail->is_critical;
                                    @endphp

                                    @if($checksheet->process_name === 'Welding')
                                        @php
                                            preg_match('/Atas\s*:\s*(\d+)/i',  $detail->qpoint_code ?? '', $matchAtas);
                                            preg_match('/Bawah\s*:\s*(\d+)/i', $detail->qpoint_code ?? '', $matchBawah);
                                            $weldingAtas  = $matchAtas[1]  ?? null;
                                            $weldingBawah = $matchBawah[1] ?? null;
                                            $ngKeys       = array_map(fn($t) => 'ng_' . strtolower(str_replace(' ', '_', $t)), $weldingNgTypes);
                                            $ngKeysJson   = json_encode($ngKeys);
                                            $detailId     = $detail->id;
                                            $isOkInit     = ($checkResults[$detail->id]['is_ok'] ?? false) ? 'true' : 'false';
                                        @endphp

                                        <tr wire:key="detail-{{ $detailId }}"
                                            x-data="{
                                                isOk: {{ $isOkInit }},
                                                unfilled: false,
                                                ngKeys: {{ $ngKeysJson }},
                                                detailId: {{ $detailId }},
                                                toggle() {
                                                    this.isOk = !this.isOk;
                                                    this.unfilled = false;
                                                    $wire.set('checkResults.' + this.detailId + '.is_ok', this.isOk);
                                                    if (this.isOk) {
                                                        this.ngKeys.forEach(key => {
                                                            $wire.set('checkResults.' + this.detailId + '.' + key, null);
                                                        });
                                                    }
                                                }
                                            }"
                                            x-on:highlight-unfilled.window="unfilled = $event.detail.detailIds.includes(detailId)"
                                            x-on:clear-highlight.window="unfilled = false"
                                            :class="unfilled
                                                ? 'bg-yellow-50 border-l-4 border-yellow-400'
                                                : '{{ $isCritical ? 'bg-red-50 border-l-4 border-l-transparent' : 'bg-white hover:bg-slate-50 border-l-4 border-l-transparent' }}'"
                                            class="transition-colors">

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
                                                    <span x-show="unfilled" x-cloak
                                                          class="inline-flex items-center gap-1 mt-0.5 text-xs text-yellow-600 font-semibold">
                                                        ⚠ Wajib diisi
                                                    </span>
                                                </div>
                                            </td>

                                            <!-- Σ Welding Atas -->
                                            <td class="px-3 py-2 text-center">
                                                @if($weldingAtas)
                                                    <span class="inline-flex items-center justify-center w-10 h-8 bg-slate-100 rounded font-bold text-slate-700 text-sm">{{ $weldingAtas }}</span>
                                                @else
                                                    <span class="text-slate-400 text-sm">-</span>
                                                @endif
                                            </td>

                                            <!-- Σ Welding Bawah -->
                                            <td class="px-3 py-2 text-center">
                                                @if($weldingBawah)
                                                    <span class="inline-flex items-center justify-center w-10 h-8 bg-slate-100 rounded font-bold text-slate-700 text-sm">{{ $weldingBawah }}</span>
                                                @else
                                                    <span class="text-slate-400 text-sm">-</span>
                                                @endif
                                            </td>

                                            <!-- Q-Point -->
                                            <td class="px-3 py-2">
                                                @if($detail->qpoint_name)
                                                    <p class="text-xs text-slate-600 leading-relaxed">{{ $detail->qpoint_name }}</p>
                                                @else
                                                    <span class="text-slate-400">-</span>
                                                @endif
                                            </td>

                                            <!-- OK Toggle -->
                                            <td class="px-3 py-2 text-center w-20">
                                                <div class="flex justify-center items-center">
                                                    <button type="button"
                                                            x-on:click="toggle()"
                                                            :class="isOk ? 'bg-green-500 shadow-sm' : 'bg-green-50 hover:bg-green-100'"
                                                            class="p-2 rounded-lg transition-colors duration-100">
                                                        <svg :class="isOk ? 'text-white' : 'text-green-300'"
                                                             :fill="isOk ? 'currentColor' : 'none'"
                                                             class="w-5 h-5"
                                                             stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>

                                            <!-- NG Inputs -->
                                            @foreach($weldingNgTypes as $ngType)
                                                @php $key = 'ng_' . strtolower(str_replace(' ', '_', $ngType)); @endphp
                                                <td class="px-3 py-2 text-center">
                                                    <input type="number" min="0"
                                                           wire:model="checkResults.{{ $detailId }}.{{ $key }}"
                                                           placeholder="-"
                                                           :disabled="isOk"
                                                           x-on:input="unfilled = false"
                                                           :class="isOk
                                                               ? 'border-slate-200 bg-slate-100 text-slate-400 cursor-not-allowed'
                                                               : 'border-red-200 focus:border-red-400 focus:ring-2 focus:ring-red-100 bg-white'"
                                                           class="w-14 text-center px-1 py-1.5 rounded border transition-colors outline-none text-sm">
                                                </td>
                                            @endforeach

                                            <!-- Status / Remarks -->
                                            <td class="px-3 py-2">
                                                <input type="text"
                                                       wire:model.defer="checkResults.{{ $detailId }}.status"
                                                       placeholder="Status / Remarks..."
                                                       class="w-full px-2 py-1.5 rounded border border-slate-300 focus:border-slate-500 focus:ring-2 focus:ring-slate-200 transition-all outline-none text-sm bg-white">
                                            </td>

                                        </tr>

                                    @else
                                        <tr x-data="{ unfilled: false, detailId: {{ $detail->id }} }"
                                            x-on:highlight-unfilled.window="unfilled = $event.detail.detailIds.includes(detailId)"
                                            x-on:clear-highlight.window="unfilled = false"
                                            :class="unfilled
                                                ? 'bg-yellow-50 border-l-4 border-yellow-400'
                                                : '{{ $isCritical ? 'bg-red-50 border-l-4 border-l-transparent' : 'bg-white hover:bg-slate-50 border-l-4 border-l-transparent' }}'"
                                            class="transition-colors">

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
                                                    <span x-show="unfilled" x-cloak
                                                          class="inline-flex items-center gap-1 mt-0.5 text-xs text-yellow-600 font-semibold">
                                                        ⚠ Wajib diisi
                                                    </span>
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
                                                               x-on:change="unfilled = false"
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
                                                               x-on:change="unfilled = false"
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
                                    @endif

                                @endforeach
                            </tbody>
                        </table>
                    </div>

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

        <!-- Action Buttons -->
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

        <!-- Loading Overlay -->
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
            from { opacity: 0; transform: scale(0.95); }
            to   { opacity: 1; transform: scale(1); }
        }
        .animate-fade-in { animation: fadeIn 0.2s ease-out; }
        [x-cloak] { display: none !important; }
    </style>
</div>
