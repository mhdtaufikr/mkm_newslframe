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
            'results.section',
        ])->findOrFail($id);
    }

    public function render()
    {
        $sections = $this->inspection->results->groupBy('checksheet_section_id');

        // Deteksi tipe per section berdasarkan result_data
        $sectionMeta = [];

        foreach ($sections as $sectionId => $results) {
            $firstWithData = $results->first(fn($r) => !is_null($r->result_data));
            $decoded       = $firstWithData ? json_decode($firstWithData->result_data, true) : null;

            $hasNgBreakdown = !empty($decoded['ng_breakdown']);
            $hasRepair      = array_key_exists('repair', $decoded ?? []);

            if ($hasRepair) {
                $type    = 'painting';
                $ngTypes = array_keys($decoded['ng_breakdown'] ?? []);
            } elseif ($hasNgBreakdown) {
                $type    = 'welding';
                $ngTypes = array_keys($decoded['ng_breakdown']);
            } else {
                $type    = 'standard';
                $ngTypes = [];
            }

            $sectionMeta[$sectionId] = [
                'type'    => $type,
                'ngTypes' => $ngTypes,
            ];
        }

        return view('livewire.checksheet.checksheet-report-detail', [
            'sections'    => $sections,
            'sectionMeta' => $sectionMeta,
        ])
            ->extends('layouts.app')
            ->section('content');
    }
}
