<?php

namespace App\Livewire\Checksheet;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ChecksheetInspection;
use App\Models\ChecksheetHead;
use Carbon\CarbonImmutable;

class ChecksheetReport extends Component
{
    use WithPagination;

    public $search = '';
    public $filterChecksheet = '';
    public $filterStatus = '';
    public $filterDateFrom = '';
    public $filterDateTo = '';

    protected $queryString = ['search', 'filterChecksheet', 'filterStatus'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $inspections = ChecksheetInspection::with(['checksheetHead'])
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('nama', 'like', '%'.$this->search.'%')
                      ->orWhere('serial_number', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->filterChecksheet, function($query) {
                $query->where('checksheet_head_id', $this->filterChecksheet);
            })
            ->when($this->filterStatus, function($query) {
                $query->where('status', $this->filterStatus);
            })
            ->when($this->filterDateFrom, function($query) {
                $query->where('tanggal', '>=', $this->filterDateFrom);
            })
            ->when($this->filterDateTo, function($query) {
                $query->where('tanggal', '<=', $this->filterDateTo);
            })
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(10);

        $checksheetHeads = ChecksheetHead::where('is_active', true)->get();

        $todayStart = CarbonImmutable::today();
        $tomorrowStart = $todayStart->addDay();
        $monthStart = $todayStart->startOfMonth();
        $nextMonthStart = $monthStart->addMonth();

        $statRow = ChecksheetInspection::query()
            ->selectRaw(
                'COUNT(*) as total,
                 SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as completed,
                 SUM(CASE WHEN created_at >= ? AND created_at < ? THEN 1 ELSE 0 END) as today,
                 SUM(CASE WHEN created_at >= ? AND created_at < ? THEN 1 ELSE 0 END) as this_month',
                [
                    'completed',
                    $todayStart,
                    $tomorrowStart,
                    $monthStart,
                    $nextMonthStart,
                ]
            )
            ->first();

        $stats = [
            'total' => (int) $statRow->total,
            'completed' => (int) $statRow->completed,
            'today' => (int) $statRow->today,
            'this_month' => (int) $statRow->this_month,
        ];

        return view('livewire.checksheet.checksheet-report', [
            'inspections' => $inspections,
            'checksheetHeads' => $checksheetHeads,
            'stats' => $stats,
        ])
            ->extends('layouts.app')
            ->section('content');
    }

}
