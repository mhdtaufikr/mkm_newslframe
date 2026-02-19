<?php

namespace App\Livewire\Checksheet;

use Livewire\Component;
use App\Models\ChecksheetHead;
use App\Models\ChecksheetDetail as ChecksheetDetailModel;
use App\Models\ChecksheetInspection;
use App\Models\ChecksheetInspectionResult;
use Illuminate\Support\Facades\DB;
use App\Models\Rule;

class ChecksheetDetail extends Component
{
    public $checksheet;
    public $sections;
    public $expandedSections = [];

    // Image modal
    public $showImageModal = false;
    public $selectedImage  = null;

    // Form data
    public $nama;
    public $tanggal;
    public $serial_number;
    public $checkResults   = [];
    public $weldingNgTypes = [];

    // ─────────────────────────────────────────────
    // Serial Number Generator
    // ─────────────────────────────────────────────

    private function generateSerialNumber(): string
    {
        $starting  = Rule::where('rule_name', 'starting_serial_no')->value('rule_value');
        $candidate = $starting ?? '1000';

        while (
            ChecksheetInspection::where('checksheet_head_id', $this->checksheet->id)
                ->where('serial_number', $candidate)
                ->exists()
        ) {
            $candidate = $this->incrementSerial($candidate);
        }

        return $candidate;
    }

    private function incrementSerial(string $serial): string
    {
        if (preg_match('/^(.*?)(\d+)$/', $serial, $matches)) {
            return $matches[1] . ((int) $matches[2] + 1);
        }
        return $serial . '-1';
    }

    // ─────────────────────────────────────────────
    // Mount
    // ─────────────────────────────────────────────

    public function mount($id): void
    {
        $this->checksheet  = ChecksheetHead::with(['sections.details'])->findOrFail($id);
        $this->sections    = $this->checksheet->sections;
        $this->tanggal     = date('Y-m-d');
        $this->nama        = auth()->user()->name;
        $this->serial_number = $this->generateSerialNumber();

        foreach ($this->sections as $section) {
            $this->expandedSections[] = $section->id;
        }

        $isWelding = $this->checksheet->process_name === 'Welding';

        if ($isWelding) {
            $maxNgTypes = [];
            foreach ($this->sections as $section) {
                foreach ($section->details as $detail) {
                    if ($detail->qpoint_name) {
                        $parsed = array_map('trim', explode(',', $detail->qpoint_name));
                        if (count($parsed) > count($maxNgTypes)) {
                            $maxNgTypes = $parsed;
                        }
                    }
                }
            }
            $this->weldingNgTypes = !empty($maxNgTypes)
                ? $maxNgTypes
                : ['Uncomplete', 'Under Cut', 'Blow Hole', 'Spatter', 'Others'];
        }

        foreach ($this->sections as $section) {
            foreach ($section->details as $detail) {
                if ($isWelding) {
                    // ✅ is_ok ditambah ke initial state
                    $result = ['status' => null, 'is_ok' => false];

                    foreach ($this->weldingNgTypes as $ngType) {
                        $key          = 'ng_' . strtolower(str_replace(' ', '_', $ngType));
                        $result[$key] = null;
                    }

                    $this->checkResults[$detail->id] = $result;
                } else {
                    $this->checkResults[$detail->id] = [
                        'result' => null,
                        'status' => '',
                    ];
                }
            }
        }
    }

    // ─────────────────────────────────────────────
    // Toggle OK (Welding)
    // ─────────────────────────────────────────────

    public function toggleOk(int $detailId): void
    {
        $current = $this->checkResults[$detailId]['is_ok'] ?? false;
        $this->checkResults[$detailId]['is_ok'] = !$current;

        // Kalau di-OK → reset semua NG ke 0
        if ($this->checkResults[$detailId]['is_ok']) {
            foreach ($this->weldingNgTypes as $ngType) {
                $key = 'ng_' . strtolower(str_replace(' ', '_', $ngType));
                $this->checkResults[$detailId][$key] = 0;
            }
        }
    }

    // ─────────────────────────────────────────────
    // UI Methods
    // ─────────────────────────────────────────────

    public function toggleSection($sectionId): void
    {
        if (in_array($sectionId, $this->expandedSections)) {
            $this->expandedSections = array_diff($this->expandedSections, [$sectionId]);
        } else {
            $this->expandedSections[] = $sectionId;
        }
    }

    public function showImage($imagePath): void
    {
        $this->selectedImage  = $imagePath;
        $this->showImageModal = true;
    }

    public function closeImageModal(): void
    {
        $this->showImageModal = false;
        $this->selectedImage  = null;
    }

    // ─────────────────────────────────────────────
    // Submit
    // ─────────────────────────────────────────────

    public function submitInspection()
    {
        $this->validate([
            'tanggal'       => 'required|date',
            'serial_number' => 'required|string|max:255',
        ], [
            'tanggal.required'       => 'Tanggal harus diisi',
            'serial_number.required' => 'Serial number harus diisi',
        ]);

        $isWelding     = strtolower(trim($this->checksheet->process_name)) === 'welding';
        $unfilledItems = [];

        // ✅ Populate $unfilledItems sesuai tipe process
        if ($isWelding) {
            foreach ($this->checkResults as $detailId => $data) {
                $isManualOk = (bool) ($data['is_ok'] ?? false);
                $ngTotal    = 0;
                foreach ($this->weldingNgTypes as $ngType) {
                    $key      = 'ng_' . strtolower(str_replace(' ', '_', $ngType));
                    $ngTotal += (int) ($data[$key] ?? 0);
                }
                if (!$isManualOk && $ngTotal === 0) {
                    $unfilledItems[] = (int) $detailId;
                }
            }
        } else {
            foreach ($this->checkResults as $detailId => $data) {
                if (empty($data['result'])) {
                    $unfilledItems[] = (int) $detailId;
                }
            }
        }

        // ✅ Blok submit kalau ada yang belum diisi
        if (!empty($unfilledItems)) {
            $this->dispatch('highlight-unfilled', detailIds: $unfilledItems);
            session()->flash('error', count($unfilledItems) . ' item belum diisi. Semua item wajib di-OK atau diisi NG.');
            return;
        }

        $serialExists = ChecksheetInspection::where('checksheet_head_id', $this->checksheet->id)
            ->where('serial_number', $this->serial_number)
            ->exists();

        if ($serialExists) {
            $this->serial_number = $this->generateSerialNumber();
        }

        DB::beginTransaction();
        try {
            $inspection = ChecksheetInspection::create([
                'checksheet_head_id' => $this->checksheet->id,
                'nama'               => auth()->user()->name,
                'tanggal'            => $this->tanggal,
                'serial_number'      => $this->serial_number,
                'status'             => 'completed',
                'submitted_at'       => now(),
            ]);

            $savedCount = 0;

            foreach ($this->checkResults as $detailId => $data) {
                $detail = ChecksheetDetailModel::find($detailId);
                if (!$detail) continue;

                if ($isWelding) {
                    $isManualOk  = (bool) ($data['is_ok'] ?? false);
                    $ngBreakdown = [];
                    $ngTotal     = 0;

                    foreach ($this->weldingNgTypes as $ngType) {
                        $key                  = 'ng_' . strtolower(str_replace(' ', '_', $ngType));
                        $val                  = (int) ($data[$key] ?? 0);
                        $ngBreakdown[$ngType] = $val;
                        $ngTotal             += $val;
                    }

                    ChecksheetInspectionResult::create([
                        'checksheet_inspection_id' => $inspection->id,
                        'checksheet_section_id'    => $detail->checksheet_section_id,
                        'checksheet_detail_id'     => $detailId,
                        'result'                   => $ngTotal > 0 ? 'ng' : 'ok',
                        'status'                   => $data['status'] ?? null,
                        'result_data'              => json_encode([
                            'ng_breakdown' => $ngBreakdown,
                            'ng_total'     => $ngTotal,
                        ]),
                    ]);

                } else {
                    ChecksheetInspectionResult::create([
                        'checksheet_inspection_id' => $inspection->id,
                        'checksheet_section_id'    => $detail->checksheet_section_id,
                        'checksheet_detail_id'     => $detailId,
                        'result'                   => $data['result'],
                        'status'                   => $data['status'] ?? null,
                        'result_data'              => null,
                    ]);
                }

                $savedCount++;
            }

            $inspection->update([
                'total_ok'    => ChecksheetInspectionResult::where('checksheet_inspection_id', $inspection->id)
                                    ->where('result', 'ok')->count(),
                'total_ng'    => ChecksheetInspectionResult::where('checksheet_inspection_id', $inspection->id)
                                    ->where('result', 'ng')->count(),
                'total_items' => $savedCount,
            ]);

            DB::commit();

            session()->flash('success', "✅ Inspeksi berhasil disimpan! Total: {$savedCount} items");
            return redirect()->route('home.index');

        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }





    // ─────────────────────────────────────────────
    // Render
    // ─────────────────────────────────────────────

    public function render()
    {
        return view('livewire.checksheet.checksheet-detail')
            ->extends('layouts.app')
            ->section('content');
    }
}
