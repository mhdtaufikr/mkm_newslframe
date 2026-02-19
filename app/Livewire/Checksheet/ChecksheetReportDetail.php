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
        $sections = $this->inspection->results->groupBy('checksheet_section_id');

        // ✅ Cek apakah inspeksi ini welding (dari hasil pertama yang punya result_data)
        $isWelding = $this->inspection->results->contains(
            fn($r) => !is_null($r->result_data)
        );

        // ✅ Ambil NG types dari result_data pertama yang ada
        $weldingNgTypes = [];
        if ($isWelding) {
            $firstWithData = $this->inspection->results->first(
                fn($r) => !is_null($r->result_data)
            );
            if ($firstWithData) {
                $decoded = json_decode($firstWithData->result_data, true);
                $weldingNgTypes = array_keys($decoded['ng_breakdown'] ?? []);
            }
        }

        return view('livewire.checksheet.checksheet-report-detail', [
            'sections'       => $sections,
            'isWelding'      => $isWelding,
            'weldingNgTypes' => $weldingNgTypes,
        ])
            ->extends('layouts.app')
            ->section('content');
    }

}
