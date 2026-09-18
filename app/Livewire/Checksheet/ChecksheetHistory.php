<?php

namespace App\Livewire\Checksheet;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ChecksheetInspection;
use App\Models\ChecksheetHead;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class ChecksheetHistory extends Component
{
    use WithPagination;

    public $search       = '';
    public $filterDate   = '';
    public $filterType   = '';
    public $filterYear   = '';
    public $checksheets  = [];
    public $modalOpen      = false;
    public $modalTitle     = '';
    public $modalNgType    = '';
    public $modalItems     = [];

    public function mount()
    {
        $this->checksheets = ChecksheetHead::orderBy('title')->get();
        $this->filterYear  = date('Y');
    }

    public function updatingSearch()     { $this->resetPage(); }
    public function updatingFilterDate() { $this->resetPage(); }
    public function updatingFilterType() { $this->resetPage(); }

    private function selectedYearRange(): array
    {
        $year = (int) ($this->filterYear ?: date('Y'));
        $start = CarbonImmutable::create($year, 1, 1)->startOfDay();

        return [$start, $start->addYear()];
    }

    private function getChartData(): array
    {
        $checksheets = ChecksheetHead::orderBy('title')->get();
        $months      = range(1, 12);
        $charts      = [];

        [$start, $end] = $this->selectedYearRange();
        $rowsByChecksheet = ChecksheetInspection::query()
            ->where('tanggal', '>=', $start->toDateString())
            ->where('tanggal', '<', $end->toDateString())
            ->where('total_items', '>', 0)
            ->selectRaw('checksheet_head_id, MONTH(tanggal) as month, COUNT(*) as total_serial, SUM(CASE WHEN total_ng = 0 THEN 1 ELSE 0 END) as serial_ok')
            ->groupBy('checksheet_head_id')
            ->groupByRaw('MONTH(tanggal)')
            ->get()
            ->groupBy('checksheet_head_id');

        foreach ($checksheets as $cs) {
            $rows = $rowsByChecksheet->get($cs->id, collect())
                ->keyBy('month');

            $data    = [];
            $okData  = [];
            $ngData  = [];

            foreach ($months as $m) {
                if (isset($rows[$m]) && $rows[$m]->total_serial > 0) {
                    $ok  = (int) $rows[$m]->serial_ok;
                    $ng  = (int) $rows[$m]->total_serial - $ok;
                    $pct = round(($ok / $rows[$m]->total_serial) * 100, 1);

                    $data[]   = $pct;
                    $okData[] = $ok;
                    $ngData[] = $ng;
                } else {
                    $data[]   = null;
                    $okData[] = null;
                    $ngData[] = null;
                }
            }

            $charts[] = [
                'id'     => $cs->id,
                'title'  => $cs->title,
                'code'   => $cs->code,
                'data'   => $data,    // accuracy %
                'okData' => $okData,  // ✅ tambah
                'ngData' => $ngData,  // ✅ tambah
            ];
        }

        return $charts;
    }


private function getNgBreakdownData(): array
{
    $checksheets = ChecksheetHead::orderBy('title')->get();
    $result      = [];

    [$start, $end] = $this->selectedYearRange();
    $rowsByChecksheet = DB::table('checksheet_inspection_results as r')
        ->join('checksheet_inspections as i', 'r.checksheet_inspection_id', '=', 'i.id')
        ->where('i.tanggal', '>=', $start->toDateString())
        ->where('i.tanggal', '<', $end->toDateString())
        ->whereNotNull('r.result_data')
        ->where('r.result_data', '!=', '')
        ->selectRaw('i.checksheet_head_id, MONTH(i.tanggal) as month, r.result_data')
        ->get()
        ->groupBy('checksheet_head_id');

    foreach ($checksheets as $cs) {
        $rows = $rowsByChecksheet->get($cs->id, collect());

        if ($rows->isEmpty()) continue;

        // Kumpulkan semua ng_type yang ada
        $allTypes = [];
        // monthly[month][type] = total
        $monthly  = [];

        foreach ($rows as $row) {
            $decoded = json_decode($row->result_data, true);
            if (!isset($decoded['ng_breakdown'])) continue;

            $m = (int) $row->month;
            foreach ($decoded['ng_breakdown'] as $type => $val) {
                $val = (int) $val;
                if ($val <= 0) continue;
                $allTypes[$type]        = true;
                $monthly[$m][$type]     = ($monthly[$m][$type] ?? 0) + $val;
            }
        }

        if (empty($allTypes)) continue;

        $types = array_keys($allTypes);

        // Hitung grand total per type untuk sort
        $grandTotals = [];
        foreach ($types as $type) {
            $grandTotals[$type] = array_sum(array_column(
                array_map(fn($m) => [$monthly[$m][$type] ?? 0], array_keys($monthly)),
                0
            ));
        }
        arsort($grandTotals);
        $types = array_keys($grandTotals);

        // Build datasets per type (12 bulan)
        $datasets = [];
        foreach ($types as $type) {
            $data = [];
            for ($m = 1; $m <= 12; $m++) {
                $data[] = $monthly[$m][$type] ?? 0;
            }
            $datasets[] = [
                'type'  => $type,
                'data'  => $data,
                'total' => $grandTotals[$type],
            ];
        }

        // Grand total keseluruhan
        $grandTotal = array_sum(array_values($grandTotals));

        $result[] = [
            'id'         => $cs->id,
            'title'      => $cs->title,
            'code'       => $cs->code,
            'datasets'   => $datasets,
            'grandTotal' => $grandTotal,
            // Untuk ranking (tetap pakai grand total)
            'labels'     => array_keys($grandTotals),
            'data'       => array_values($grandTotals),
        ];
    }

    return $result;
}



    public function render()
    {
        $query = ChecksheetInspection::with('checksheetHead')
            ->when($this->search, fn($q) =>
                $q->where(fn ($searchQuery) =>
                    $searchQuery->where('nama', 'like', "%{$this->search}%")
                        ->orWhere('serial_number', 'like', "%{$this->search}%")
                )
            )
            ->when($this->filterDate, fn($q) =>
                $q->where('tanggal', $this->filterDate)
            )
            ->when($this->filterType, fn($q) =>
                $q->where('checksheet_head_id', $this->filterType)
            )
            ->orderByDesc('submitted_at')
            ->orderByDesc('id');

        $inspections = $query->paginate(15);
        $chartData   = $this->getChartData();

        // Available years dari data
        $availableYears = ChecksheetInspection::selectRaw('YEAR(tanggal) as year')
            ->groupByRaw('YEAR(tanggal)')
            ->orderByDesc('year')
            ->pluck('year')
            ->toArray();

        $ngBreakdown = $this->getNgBreakdownData();
        $unitSummary = $this->getUnitSummaryData();

        return view('livewire.checksheet.checksheet-history', [
            'inspections'    => $inspections,
            'chartData'      => $chartData,
            'availableYears' => $availableYears,
            'ngBreakdown'    => $ngBreakdown,
            'unitSummary'    => $unitSummary,  // ✅ tambah ini
        ])
            ->extends('layouts.app')
            ->section('content');

    }

    public $modalMonth = null; // null = all months

    public function loadNgDetail(int $checksheetHeadId, string $ngType, ?int $month = null): void
    {
        [$start, $end] = $this->selectedYearRange();

        $query = DB::table('checksheet_inspection_results as r')
            ->join('checksheet_inspections as i', 'r.checksheet_inspection_id', '=', 'i.id')
            ->join('checksheet_details as d', 'r.checksheet_detail_id', '=', 'd.id')
            ->where('i.checksheet_head_id', $checksheetHeadId)
            ->where('i.tanggal', '>=', $start->toDateString())
            ->where('i.tanggal', '<', $end->toDateString())
            ->whereNotNull('r.result_data')
            ->where('r.result_data', '!=', '');

        if ($month) {
            $monthStart = $start->addMonths($month - 1);
            $query->where('i.tanggal', '>=', $monthStart->toDateString())
                ->where('i.tanggal', '<', $monthStart->addMonth()->toDateString());
        }

        $results = $query->select(
            'd.item_code', 'd.item_name',
            'i.serial_number', 'i.tanggal', 'i.nama',
            'r.result_data', 'r.status'
        )->get();

        $items = [];
        foreach ($results as $row) {
            $decoded = json_decode($row->result_data, true);
            $val = (int) ($decoded['ng_breakdown'][$ngType] ?? 0);
            if ($val <= 0) continue;

            $items[] = [
                'item_code'     => $row->item_code,
                'item_name'     => $row->item_name,
                'serial_number' => $row->serial_number,
                'tanggal'       => $row->tanggal,
                'inspector'     => $row->nama,
                'count'         => $val,
                'status'        => $row->status,
            ];
        }

        usort($items, fn($a, $b) => $b['count'] <=> $a['count']);

        $cs = ChecksheetHead::find($checksheetHeadId);
        $monthNames = ['','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        $monthLabel  = $month ? ' · ' . $monthNames[$month] : '';

        $this->modalTitle  = ($cs->title ?? '') . ' — ' . $ngType . $monthLabel;
        $this->modalNgType = $ngType;
        $this->modalMonth  = $month;
        $this->modalItems  = $items;
        $this->modalOpen   = true;
    }


public function closeModal(): void
{
    $this->modalOpen  = false;
    $this->modalItems = [];
    $this->dispatch('modalClosed'); // ✅ notify JS buat re-init chart
}

private function getUnitSummaryData(): array
{
    [$start, $end] = $this->selectedYearRange();

    $groups = [
        'overall'     => [1, 2, 3, 4],
        'kelengkapan' => [1, 2],
        'welding'     => [3, 4],
    ];

    $result = [];

    foreach ($groups as $groupKey => $csIds) {
        $monthly = array_fill(1, 12, null);
        $requiredCount = count($csIds);

        $completeUnits = ChecksheetInspection::query()
            ->whereIn('checksheet_head_id', $csIds)
            ->where('tanggal', '>=', $start->toDateString())
            ->where('tanggal', '<', $end->toDateString())
            ->whereNotNull('serial_number')
            ->where('serial_number', '!=', '')
            ->selectRaw('MONTH(tanggal) as month, serial_number, COUNT(DISTINCT checksheet_head_id) as cs_count, MAX(CASE WHEN total_ng > 0 THEN 1 ELSE 0 END) as has_ng')
            ->groupByRaw('MONTH(tanggal), serial_number')
            ->havingRaw('COUNT(DISTINCT checksheet_head_id) = ?', [$requiredCount])
            ->get()
            ->groupBy('month');

        foreach ($completeUnits as $month => $units) {
            $totalUnits = $units->count();
            $ngUnits = $units->where('has_ng', 1)->count();

            $monthly[(int) $month] = [
                'total' => $totalUnits,
                'ok'    => $totalUnits - $ngUnits,
                'ng'    => $ngUnits,
            ];
        }

        $result[$groupKey] = $monthly;
    }

    return $result;
}



}
