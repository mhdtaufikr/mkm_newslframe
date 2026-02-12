<?php

namespace App\Livewire\Checksheet;

use Livewire\Component;
use App\Models\ChecksheetInspection;

class ChecksheetReportDetail extends Component
{
    public $inspection;

    public function mount($id)
    {
        $this->inspection = ChecksheetInspection::with([
            'checksheetHead',
            'results.detail',
            'results.section'
        ])->findOrFail($id);
    }

    public function render()
    {
        // Group results by section - di render aja, jangan di property
        $sections = $this->inspection->results->groupBy('checksheet_section_id');

        return view('livewire.checksheet.checksheet-report-detail', [
            'sections' => $sections
        ])
            ->extends('layouts.app')
            ->section('content');
    }
}
