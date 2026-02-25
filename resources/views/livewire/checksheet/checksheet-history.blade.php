<div class="page-fade min-h-screen pb-32">
    <!-- Header -->
    <header class="sticky top-0 z-50 bg-white/70 backdrop-blur-xl border-b border-slate-100 px-6 py-3">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-3">
                <a href="javascript:history.back()"
                   class="w-9 h-9 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 flex items-center justify-center transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-lg font-bold text-slate-800">Riwayat Inspeksi</h1>
                    <p class="text-xs text-slate-500 font-medium">Semua hasil inspeksi checksheet</p>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-6">

        {{-- ============================================================
     UNIT QUALITY SUMMARY
     ============================================================ --}}
<div class="mb-6">
    <div class="flex items-center justify-between mb-3">
        <div>
            <h2 class="text-base font-bold text-slate-800">Unit Quality Summary</h2>
            <p class="text-xs text-slate-500">Jumlah unit OK vs NG per bulan berdasarkan gabungan checksheet — {{ $filterYear ?: date('Y') }}</p>
        </div>
        <select wire:model.live="filterYear"
        class="px-3 py-2 rounded-lg border border-slate-200 focus:border-slate-400 outline-none text-sm bg-white font-bold text-slate-700">
    @foreach($availableYears as $year)
        <option value="{{ $year }}">{{ $year }}</option>
    @endforeach
    @if(empty($availableYears))
        <option value="{{ date('Y') }}">{{ date('Y') }}</option>
    @endif
</select>
    </div>

    <div class="grid grid-cols-1 gap-4">

        {{-- Overall (semua 4 checksheet) --}}
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="text-sm font-bold text-slate-800 leading-tight">Overall — Semua Checksheet</p>
                    <span class="text-xs text-slate-400 font-medium">QG-001 + QG-002 + QG-003 + QG-004</span>
                </div>
                @php
                    $totalOkAll  = array_sum(array_column(array_filter($unitSummary['overall'], fn($v) => $v !== null), 'ok'));
                    $totalNgAll  = array_sum(array_column(array_filter($unitSummary['overall'], fn($v) => $v !== null), 'ng'));
                    $totalAllAll = $totalOkAll + $totalNgAll;
                @endphp
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-1 bg-green-50 text-green-700 rounded-lg text-xs font-bold">✓ {{ $totalOkAll }} OK</span>
                    <span class="px-2.5 py-1 bg-red-50 text-red-700 rounded-lg text-xs font-bold">✗ {{ $totalNgAll }} NG</span>
                </div>
            </div>
            <div style="position: relative; width: 100%; height: 180px;">
                <canvas id="chart-unit-overall"></canvas>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            {{-- Kelengkapan Part --}}
            <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="text-sm font-bold text-slate-800 leading-tight">Kelengkapan Part</p>
                        <span class="text-xs text-slate-400 font-medium">QG-001 (LH) + QG-002 (RH)</span>
                    </div>
                    @php
                        $totalOkKel  = array_sum(array_column(array_filter($unitSummary['kelengkapan'], fn($v) => $v !== null), 'ok'));
                        $totalNgKel  = array_sum(array_column(array_filter($unitSummary['kelengkapan'], fn($v) => $v !== null), 'ng'));
                    @endphp
                    <div class="flex flex-col items-end gap-1">
                        <span class="px-2 py-0.5 bg-green-50 text-green-700 rounded text-xs font-bold">✓ {{ $totalOkKel }}</span>
                        <span class="px-2 py-0.5 bg-red-50 text-red-700 rounded text-xs font-bold">✗ {{ $totalNgKel }}</span>
                    </div>
                </div>
                <div style="position: relative; width: 100%; height: 180px;">
                    <canvas id="chart-unit-kelengkapan"></canvas>
                </div>
            </div>

            {{-- Kualitas Welding --}}
            <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="text-sm font-bold text-slate-800 leading-tight">Kualitas Welding</p>
                        <span class="text-xs text-slate-400 font-medium">QG-003 (Sec 1-3) + QG-004 (Sec 4-6)</span>
                    </div>
                    @php
                        $totalOkWeld = array_sum(array_column(array_filter($unitSummary['welding'], fn($v) => $v !== null), 'ok'));
                        $totalNgWeld = array_sum(array_column(array_filter($unitSummary['welding'], fn($v) => $v !== null), 'ng'));
                    @endphp
                    <div class="flex flex-col items-end gap-1">
                        <span class="px-2 py-0.5 bg-green-50 text-green-700 rounded text-xs font-bold">✓ {{ $totalOkWeld }}</span>
                        <span class="px-2 py-0.5 bg-red-50 text-red-700 rounded text-xs font-bold">✗ {{ $totalNgWeld }}</span>
                    </div>
                </div>
                <div style="position: relative; width: 100%; height: 180px;">
                    <canvas id="chart-unit-welding"></canvas>
                </div>
            </div>

        </div>
    </div>
</div>


        <!-- Accuracy Trend -->
<div class="mb-6">
    <div class="flex items-center justify-between mb-3">
        <div>
            <h2 class="text-base font-bold text-slate-800">Accuracy Trend</h2>
            <p class="text-xs text-slate-500">Jumlah unit OK/NG & pass rate per bulan per checksheet</p>
        </div>

    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        @foreach($chartData as $chart)
            <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="text-sm font-bold text-slate-800 leading-tight">{{ $chart['title'] }}</p>
                        <span class="text-xs text-slate-400 font-medium">{{ $chart['code'] }}</span>
                    </div>
                    @php
                        $validData = array_filter($chart['data'], fn($v) => $v !== null);
                        $avgAll    = count($validData) > 0 ? round(array_sum($validData) / count($validData), 1) : 0;
                    @endphp
                    <span class="text-lg font-black {{ $avgAll >= 95 ? 'text-green-600' : ($avgAll >= 80 ? 'text-yellow-600' : 'text-red-600') }}">
                        {{ $avgAll }}%
                    </span>
                </div>
                {{-- tinggi naik jadi 180px karena dual axis --}}
                <div style="position: relative; width: 100%; height: 180px;">
                    <canvas id="chart-{{ $chart['id'] }}"></canvas>
                </div>
            </div>
        @endforeach
    </div>
</div>


        @if(!empty($ngBreakdown))
        <div class="mb-6">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h2 class="text-base font-bold text-slate-800">Welding NG Breakdown</h2>
                    <p class="text-xs text-slate-500">Temuan per jenis defect per bulan — {{ $filterYear ?: date('Y') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($ngBreakdown as $ng)
                    @php
                        $colors = ['#dc2626','#ea580c','#ca8a04','#65a30d','#0891b2'];
                    @endphp
                    <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <p class="text-sm font-bold text-slate-800 leading-tight">{{ $ng['title'] }}</p>
                                <span class="text-xs text-slate-400 font-medium">{{ $ng['code'] }}</span>
                            </div>
                            <span class="px-2.5 py-1 bg-red-50 text-red-700 rounded-lg text-xs font-bold">
                                {{ $ng['grandTotal'] }} temuan
                            </span>
                        </div>

                        {{-- Stacked bar chart --}}
                        <div style="position: relative; width: 100%; height: 200px;">
                            <canvas id="chart-ng-{{ $ng['id'] }}" style="cursor: pointer;"></canvas>
                        </div>

                        {{-- Legend chips --}}
                        <div class="flex flex-wrap gap-1.5 mt-3">
                            @foreach($ng['labels'] as $i => $label)
                                @php $color = $colors[$i % count($colors)]; @endphp
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium border"
                                      style="border-color: {{ $color }}; color: {{ $color }}; background-color: {{ $color }}11">
                                    <span class="w-1.5 h-1.5 rounded-full inline-block" style="background: {{ $color }}"></span>
                                    {{ $label }}
                                    <span class="font-bold">{{ $ng['data'][$i] }}</span>
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif


        <!-- Filter Bar -->
        <div class="bg-white border border-slate-200 rounded-xl p-4 mb-4 shadow-sm">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <input type="text"
                           wire:model.live.debounce.300ms="search"
                           placeholder="Cari nama / serial..."
                           class="w-full pl-9 pr-3 py-2 rounded-lg border border-slate-200 focus:border-slate-400 focus:ring-2 focus:ring-slate-100 outline-none text-sm bg-white transition-all">
                </div>
                <input type="date"
                       wire:model.live="filterDate"
                       class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:border-slate-400 focus:ring-2 focus:ring-slate-100 outline-none text-sm bg-white transition-all">
                <select wire:model.live="filterType"
                        class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:border-slate-400 focus:ring-2 focus:ring-slate-100 outline-none text-sm bg-white transition-all">
                    <option value="">Semua Checksheet</option>
                    @foreach($checksheets as $cs)
                        <option value="{{ $cs->id }}">{{ $cs->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-4 py-3 text-left text-xs font-bold text-slate-600 uppercase">Checksheet</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-slate-600 uppercase">Inspector</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-slate-600 uppercase">Serial No</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-slate-600 uppercase">Tanggal</th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-green-600 uppercase">OK</th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-red-600 uppercase">NG</th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-blue-600 uppercase">Pass %</th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-slate-600 uppercase">Status</th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-slate-600 uppercase w-20">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($inspections as $inspection)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-slate-800 leading-tight">{{ $inspection->checksheetHead->title }}</p>
                                    <span class="text-xs text-slate-400">{{ $inspection->checksheetHead->code }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-sm text-slate-700 font-medium">{{ $inspection->nama }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="font-mono text-xs bg-slate-100 px-2 py-1 rounded text-slate-600">
                                        {{ $inspection->serial_number ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-sm text-slate-700">{{ $inspection->tanggal->format('d M Y') }}</p>
                                    <p class="text-xs text-slate-400">{{ $inspection->submitted_at->format('H:i') }}</p>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="font-bold text-green-600">{{ $inspection->total_ok }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="font-bold {{ $inspection->total_ng > 0 ? 'text-red-600' : 'text-slate-400' }}">
                                        {{ $inspection->total_ng }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @php
                                        $pct = $inspection->total_items > 0
                                            ? round(($inspection->total_ok / $inspection->total_items) * 100)
                                            : 0;
                                    @endphp
                                    <span class="font-bold text-sm {{ $pct === 100 ? 'text-green-600' : ($pct >= 80 ? 'text-yellow-600' : 'text-red-600') }}">
                                        {{ $pct }}%
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($inspection->status === 'completed')
                                        <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-bold">✓ Done</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 bg-yellow-100 text-yellow-700 rounded-lg text-xs font-bold">⏳ Draft</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('checksheet.report.detail', $inspection->id) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 bg-slate-100 hover:bg-slate-800 hover:text-white text-slate-600 rounded-lg transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-16 text-center">
                                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                    </div>
                                    <p class="text-slate-400 font-medium">Belum ada riwayat inspeksi</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($inspections->hasPages())
                <div class="px-4 py-3 border-t border-slate-100">
                    {{ $inspections->links() }}
                </div>
            @endif
        </div>

        <!-- NG Detail Modal -->
        @if($modalOpen)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[200] flex items-center justify-center p-4"
             wire:click.self="closeModal">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[80vh] flex flex-col">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Detail Temuan</h3>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $modalTitle }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 bg-red-50 text-red-700 rounded-lg text-xs font-bold">
                            {{ count($modalItems) }} item terdampak
                        </span>
                        <button wire:click="closeModal"
                                class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-red-100 hover:text-red-600 text-slate-500 flex items-center justify-center transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="overflow-y-auto flex-1 p-4">
                    @forelse($modalItems as $item)
                        <div class="flex items-start gap-3 p-3 rounded-xl hover:bg-slate-50 transition-colors border border-transparent hover:border-slate-200">
                            <div class="w-10 h-10 rounded-xl bg-red-100 text-red-700 font-black text-sm flex items-center justify-center shrink-0">
                                {{ $item['count'] }}x
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <p class="text-sm font-bold text-slate-800 leading-tight">{{ $item['item_name'] }}</p>
                                        @if($item['item_code'])
                                            <span class="text-xs text-slate-400">{{ $item['item_code'] }}</span>
                                        @endif
                                    </div>
                                    <span class="font-mono text-xs bg-slate-100 px-2 py-1 rounded text-slate-600 shrink-0">
                                        {{ $item['serial_number'] }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-3 mt-1.5">
                                    <span class="text-xs text-slate-500">
                                        📅 {{ \Carbon\Carbon::parse($item['tanggal'])->format('d M Y') }}
                                    </span>
                                    <span class="text-xs text-slate-500">
                                        👤 {{ $item['inspector'] }}
                                    </span>
                                </div>
                                @if($item['status'])
                                    <p class="text-xs text-slate-500 mt-1 italic">💬 {{ $item['status'] }}</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <p class="text-slate-400 text-sm">Tidak ada data temuan</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        @endif

    </main>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const months      = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    const chartData   = @json($chartData);
    const ngBreakdown = @json($ngBreakdown);
    const unitSummary = @json($unitSummary);
    const ngColors    = ['#dc2626','#ea580c','#ca8a04','#65a30d','#0891b2'];

    // Simpan instance chart supaya bisa di-destroy saat re-init
    const charts     = {};
    const ngCharts   = {};
    const unitCharts = {};

    let modalIsOpen = false;

    function getLivewireComponent() {
        return Livewire.getByName('checksheet.checksheet-history')[0]
            ?? Livewire.getByName('checksheet-history')[0]
            ?? null;
    }

    // Helper: build mixed bar+line chart config (reusable)
    function buildMixedConfig({ okData, ngData, countLabel = 'Serial', passRateColorByValue = true }) {
        const passRate = okData.map((ok, i) => {
            const ng = ngData[i];
            if (ok === null && ng === null) return null;
            const total = (ok ?? 0) + (ng ?? 0);
            return total > 0 ? Math.round((ok / total) * 1000) / 10 : null;
        });

        const pointColors = passRate.map(v => {
            if (v === null) return 'transparent';
            if (!passRateColorByValue) return '#2563eb';
            return v >= 95 ? '#16a34a' : v >= 80 ? '#ca8a04' : '#dc2626';
        });

        return {
            type: 'bar',
            data: {
                labels: months,
                datasets: [
                    {
                        label: 'OK',
                        type: 'bar',
                        data: okData,
                        backgroundColor: '#16a34a99',
                        borderColor: '#16a34a',
                        borderWidth: 1.5,
                        borderRadius: 3,
                        stack: 'stack',
                        yAxisID: 'yCount',
                    },
                    {
                        label: 'NG',
                        type: 'bar',
                        data: ngData,
                        backgroundColor: '#dc262699',
                        borderColor: '#dc2626',
                        borderWidth: 1.5,
                        borderRadius: 3,
                        stack: 'stack',
                        yAxisID: 'yCount',
                    },
                    {
                        label: 'Pass Rate',
                        type: 'line',
                        data: passRate,
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37,99,235,0.06)',
                        borderWidth: 2,
                        pointBackgroundColor: pointColors,
                        pointBorderColor: pointColors,
                        pointRadius: passRate.map(v => v === null ? 0 : 4),
                        pointHoverRadius: 6,
                        fill: false,
                        tension: 0.4,
                        spanGaps: false,
                        yAxisID: 'yPct',
                    },
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: { font: { size: 10 }, boxWidth: 10, padding: 8 }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            title: ctx => months[ctx[0].dataIndex],
                            label: ctx => {
                                if (ctx.dataset.label === 'Pass Rate') {
                                    return ` Pass Rate: ${ctx.raw ?? '-'}%`;
                                }
                                return ` ${ctx.dataset.label}: ${ctx.raw ?? 0} ${countLabel}`;
                            },
                            footer: ctxArr => {
                                const ok    = ctxArr.find(c => c.dataset.label === 'OK')?.raw ?? 0;
                                const ng    = ctxArr.find(c => c.dataset.label === 'NG')?.raw ?? 0;
                                const total = ok + ng;
                                return total > 0 ? `Total: ${total} ${countLabel}` : '';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        stacked: true,
                        ticks: { font: { size: 9 } },
                        grid: { display: false }
                    },
                    yCount: {
                        type: 'linear',
                        position: 'left',
                        stacked: true,
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: { size: 10 },
                            callback: v => Number.isInteger(v) ? v : ''
                        },
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        title: { display: true, text: countLabel, font: { size: 9 }, color: '#94a3b8' }
                    },
                    yPct: {
                        type: 'linear',
                        position: 'right',
                        min: 0,
                        max: 100,
                        ticks: {
                            stepSize: 20,
                            font: { size: 10 },
                            callback: v => v + '%'
                        },
                        grid: { drawOnChartArea: false },
                        title: { display: true, text: 'Pass Rate', font: { size: 9 }, color: '#2563eb' }
                    }
                }
            }
        };
    }

    function initCharts() {
        if (modalIsOpen) return;

        // ── 1. Accuracy Trend (mixed bar + line per checksheet) ──
        chartData.forEach(cs => {
            const el = document.getElementById('chart-' + cs.id);
            if (!el) return;
            if (charts[cs.id]) { charts[cs.id].destroy(); delete charts[cs.id]; }

            charts[cs.id] = new Chart(el, buildMixedConfig({
                okData: cs.okData,
                ngData: cs.ngData,
                countLabel: 'Serial',
            }));
        });

        // ── 2. NG Breakdown (stacked bar per checksheet) ──
        if (ngBreakdown && ngBreakdown.length > 0) {
            ngBreakdown.forEach(ng => {
                const el = document.getElementById('chart-ng-' + ng.id);
                if (!el) return;
                if (ngCharts[ng.id]) { ngCharts[ng.id].destroy(); delete ngCharts[ng.id]; }

                const csId     = ng.id;
                const datasets = ng.datasets.map((ds, i) => ({
                    label: ds.type,
                    data: ds.data,
                    backgroundColor: ngColors[i % ngColors.length] + 'cc',
                    borderColor: ngColors[i % ngColors.length],
                    borderWidth: 1,
                    borderRadius: 3,
                    stack: 'ng',
                }));

                ngCharts[csId] = new Chart(el, {
                    type: 'bar',
                    data: { labels: months, datasets },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        onClick: (event, elements) => {
                            if (elements.length === 0) return;
                            const monthIndex = elements[0].index;
                            const dsIndex    = elements[0].datasetIndex;
                            const ngType     = ng.datasets[dsIndex].type;
                            const monthNum   = monthIndex + 1;
                            const comp       = getLivewireComponent();
                            if (comp) {
                                modalIsOpen = true;
                                comp.loadNgDetail(csId, ngType, monthNum);
                            }
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                callbacks: {
                                    title: ctx => months[ctx[0].dataIndex],
                                    label: ctx => ` ${ctx.dataset.label}: ${ctx.raw} temuan`,
                                    footer: ctxArr => {
                                        const total = ctxArr.reduce((s, c) => s + c.raw, 0);
                                        return total > 0 ? `Total: ${total} temuan` : '';
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                stacked: true,
                                ticks: { font: { size: 9 } },
                                grid: { display: false }
                            },
                            y: {
                                stacked: true,
                                beginAtZero: true,
                                ticks: { stepSize: 1, font: { size: 10 } },
                                grid: { color: 'rgba(0,0,0,0.05)' }
                            }
                        }
                    }
                });
            });
        }

        // ── 3. Unit Quality Summary (mixed bar + line per group) ──
        if (unitSummary) {
            const unitGroups = [
                { key: 'overall',     canvasId: 'chart-unit-overall',     countLabel: 'Unit' },
                { key: 'kelengkapan', canvasId: 'chart-unit-kelengkapan', countLabel: 'Unit' },
                { key: 'welding',     canvasId: 'chart-unit-welding',     countLabel: 'Unit' },
            ];

            unitGroups.forEach(group => {
                const el = document.getElementById(group.canvasId);
                if (!el) return;
                if (unitCharts[group.key]) { unitCharts[group.key].destroy(); delete unitCharts[group.key]; }

                const monthlyData = unitSummary[group.key];
                const okData = [];
                const ngData = [];

                for (let m = 1; m <= 12; m++) {
                    const d = monthlyData[m];
                    okData.push(d ? d.ok : null);
                    ngData.push(d ? d.ng : null);
                }

                unitCharts[group.key] = new Chart(el, buildMixedConfig({
                    okData,
                    ngData,
                    countLabel: group.countLabel,
                }));
            });
        }
    }

    // ── Event listeners ──
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('modalClosed', () => {
            modalIsOpen = false;
            setTimeout(initCharts, 150);
        });
    });

    document.addEventListener('DOMContentLoaded', initCharts);
    document.addEventListener('livewire:navigated', initCharts);
    Livewire.hook('morph.updated', () => {
        if (!modalIsOpen) setTimeout(initCharts, 150);
    });
</script>
@endpush


