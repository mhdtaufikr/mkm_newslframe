<?php

namespace App\Livewire\Checksheet;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ChecksheetInspection;
use App\Models\ChecksheetHead;

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
                $query->whereDate('tanggal', '>=', $this->filterDateFrom);
            })
            ->when($this->filterDateTo, function($query) {
                $query->whereDate('tanggal', '<=', $this->filterDateTo);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $checksheetHeads = ChecksheetHead::where('is_active', true)->get();

        // Stats - hitung sebelum paginate
        $stats = [
            'total' => ChecksheetInspection::count(),
            'completed' => ChecksheetInspection::where('status', 'completed')->count(),
            'today' => ChecksheetInspection::whereDate('created_at', today())->count(),
            'this_month' => ChecksheetInspection::whereMonth('created_at', now()->month)
                                                 ->whereYear('created_at', now()->year)
                                                 ->count(),
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
