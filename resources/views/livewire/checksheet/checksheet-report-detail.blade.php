<div>
    <div class="page-fade min-h-screen pb-32">
        <!-- Header -->
        <header class="sticky top-0 z-50 bg-white/70 backdrop-blur-xl border-b border-slate-100 px-6 py-3">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <a href="{{ route('checksheet.report') }}" class="w-9 h-9 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 flex items-center justify-center transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                    </a>
                    <div>
                        <div class="flex items-center gap-2 mb-0.5">
                            <span class="px-2 py-0.5 bg-slate-100 rounded text-xs font-bold text-slate-600">{{ $inspection->checksheetHead->code }}</span>
                            @if($inspection->status === 'completed')
                                <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded text-xs font-bold">✓ Completed</span>
                            @endif
                        </div>
                        <h1 class="text-lg font-bold text-slate-800">{{ $inspection->checksheetHead->title }}</h1>
                        <p class="text-xs text-slate-500 font-medium">Detail Hasil Inspeksi</p>
                    </div>
                </div>
                <button onclick="window.print()" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-lg font-bold text-sm transition-all">
                    🖨️ Print
                </button>
            </div>
        </header>

        <main class="max-w-7xl mx-auto px-6 py-6">
            <!-- Inspection Info -->
            <div class="bg-white border border-slate-200 rounded-xl p-4 mb-4 shadow-sm">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase">Inspector</p>
                        <p class="text-sm font-bold text-slate-800 mt-1">{{ $inspection->nama }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase">Tanggal</p>
                        <p class="text-sm font-bold text-slate-800 mt-1">{{ $inspection->tanggal->format('d M Y') }}</p>
                    </div>
                    @if($inspection->serial_number)
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase">Serial Number</p>
                            <p class="text-sm font-bold text-slate-800 mt-1">{{ $inspection->serial_number }}</p>
                        </div>
                    @endif
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase">Submitted</p>
                        <p class="text-sm font-bold text-slate-800 mt-1">{{ $inspection->submitted_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Summary Results -->
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-center">
                    <p class="text-3xl font-black text-green-600">{{ $inspection->total_ok }}</p>
                    <p class="text-xs font-bold text-green-700 uppercase mt-1">OK Items</p>
                </div>
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-center">
                    <p class="text-3xl font-black text-red-600">{{ $inspection->total_ng }}</p>
                    <p class="text-xs font-bold text-red-700 uppercase mt-1">NG Items</p>
                </div>
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-center">
                    <p class="text-3xl font-black text-blue-600">{{ $inspection->percentage_ok }}%</p>
                    <p class="text-xs font-bold text-blue-700 uppercase mt-1">Pass Rate</p>
                </div>
            </div>

            <!-- Results by Section -->
            <div class="space-y-4">
                @foreach($sections as $sectionId => $results)
                    @php
                        $section = $results->first()->section;
                    @endphp

                    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                        <!-- Section Header -->
                        <div class="px-4 py-3 bg-slate-100">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-slate-700 text-white rounded-lg flex items-center justify-center font-bold text-base">
                                    {{ $section->section_number }}
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-slate-800">{{ $section->section_name }}</h3>
                                    <p class="text-xs text-slate-500">{{ $results->count() }} items checked</p>
                                </div>
                            </div>
                        </div>

                      <!-- Results Table -->
<div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead>
            @if($isWelding)
                <tr class="bg-slate-50 border-b border-slate-200">
                    <th class="px-3 py-2 text-center text-xs font-bold text-slate-700 uppercase w-12" rowspan="2">NO</th>
                    <th class="px-3 py-2 text-left text-xs font-bold text-slate-700 uppercase" rowspan="2">ITEM CHECK</th>
                    <th class="px-3 py-2 text-left text-xs font-bold text-slate-700 uppercase" rowspan="2">Q-POINT</th>
                    <th class="px-3 py-2 text-center text-xs font-bold text-slate-700 uppercase w-20" rowspan="2">RESULT</th>
                    <th class="px-3 py-2 text-center text-xs font-bold text-red-600 uppercase" colspan="{{ count($weldingNgTypes) }}">WELDING NG BREAKDOWN</th>
                    <th class="px-3 py-2 text-left text-xs font-bold text-slate-700 uppercase" rowspan="2">STATUS / REMARKS</th>
                </tr>
                <tr class="bg-slate-50 border-b border-slate-200">
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
                    <th class="px-3 py-2 text-center text-xs font-bold text-slate-700 uppercase w-24">RESULT</th>
                    <th class="px-3 py-2 text-left text-xs font-bold text-slate-700 uppercase">STATUS / REMARKS</th>
                </tr>
            @endif
        </thead>
        <tbody class="divide-y divide-slate-100">
            @foreach($results as $index => $result)
                @php
                    $resultData  = $result->result_data ? json_decode($result->result_data, true) : null;
                    $ngBreakdown = $resultData['ng_breakdown'] ?? [];
                    $ngTotal     = $resultData['ng_total'] ?? 0;
                @endphp
                <tr class="hover:bg-slate-50 transition-colors {{ $result->result === 'ng' ? 'bg-red-50/50' : '' }}">

                    <!-- No -->
                    <td class="px-3 py-2 text-center">
                        <span class="font-bold text-slate-700">{{ $index + 1 }}</span>
                    </td>

                    <!-- Item Check -->
                    <td class="px-3 py-2">
                        <div>
                            <p class="font-semibold text-slate-800">{{ $result->detail->item_name }}</p>
                            @if($result->detail->item_code)
                                <span class="text-xs text-slate-500">{{ $result->detail->item_code }}</span>
                            @endif
                        </div>
                    </td>

                    <!-- Q-Point -->
                    <td class="px-3 py-2">
                        @if($result->detail->qpoint_name)
                            <p class="text-sm text-slate-700">{{ $result->detail->qpoint_name }}</p>
                        @else
                            <span class="text-slate-400">-</span>
                        @endif
                    </td>

                    <!-- Result -->
                    <td class="px-3 py-2 text-center">
                        @if($result->result === 'ok')
                            <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-bold">
                                ✓ OK
                            </span>
                        @elseif($result->result === 'ng')
                            <span class="inline-flex flex-col items-center px-3 py-1 bg-red-100 text-red-700 rounded-lg text-xs font-bold">
                                ✗ NG
                                @if($isWelding && $ngTotal > 0)
                                    <span class="text-red-500 font-normal mt-0.5">{{ $ngTotal }} defect</span>
                                @endif
                            </span>
                        @endif
                    </td>

                    <!-- NG Breakdown (welding only) -->
                    @if($isWelding)
                        @foreach($weldingNgTypes as $ngType)
                            @php $val = $ngBreakdown[$ngType] ?? 0; @endphp
                            <td class="px-3 py-2 text-center">
                                @if($val > 0)
                                    <span class="inline-flex items-center justify-center min-w-[2rem] px-2 py-1 bg-red-100 text-red-700 rounded font-bold text-sm">
                                        {{ $val }}
                                    </span>
                                @else
                                    <span class="text-slate-300 text-sm">-</span>
                                @endif
                            </td>
                        @endforeach
                    @endif

                    <!-- Status / Remarks -->
                    <td class="px-3 py-2">
                        @if($result->status)
                            <p class="text-sm text-slate-700">{{ $result->status }}</p>
                        @else
                            <span class="text-slate-400">-</span>
                        @endif
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
</div>

                    </div>
                @endforeach
            </div>

            <!-- Back Button -->
            <div class="mt-6">
                <a href="{{ route('checksheet.report') }}"
                   class="inline-flex items-center gap-2 px-5 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-lg transition-all text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Kembali ke Report List
                </a>
            </div>
        </main>
    </div>
</div>
