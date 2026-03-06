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
    public $checkResults = [];

    // Format config per section (key = section_id)
    public $sectionFormats = [];

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
        $this->checksheet = ChecksheetHead::with([
            'sections.details',
            'sections.format',
            'defaultFormat',
        ])->findOrFail($id);

        $this->sections      = $this->checksheet->sections;
        $this->tanggal       = date('Y-m-d');
        $this->nama          = auth()->user()->name;
        $this->serial_number = $this->generateSerialNumber();

        foreach ($this->sections as $section) {
            // Expand semua section by default
            $this->expandedSections[] = $section->id;

            // Resolve format untuk section ini
            $format  = $section->resolvedFormat();
            $config  = $format?->config ?? [];

            $inputType = $config['input_type'] ?? 'standard';
            $ngTypes   = $config['ng_types'] ?? [];

            // Simpan config per section biar bisa diakses di blade & submit
            $this->sectionFormats[$section->id] = [
                'input_type'      => $inputType,
                'ng_types'        => $ngTypes,
                'has_atas_bawah'  => $config['has_atas_bawah'] ?? false,
                'store_breakdown' => $config['store_breakdown'] ?? false,
            ];

            // Init checkResults per detail
            foreach ($section->details as $detail) {
                if ($inputType === 'welding') {
                    $result = [
                        'is_ok'  => false,
                        'status' => null,
                    ];
                    foreach ($ngTypes as $ngType) {
                        $key          = 'ng_' . strtolower(str_replace(' ', '_', $ngType));
                        $result[$key] = null;
                    }
                } else {
                    $result = [
                        'result' => null,
                        'status' => '',
                    ];
                }

                $this->checkResults[$detail->id] = $result;
            }
        }
    }

    // ─────────────────────────────────────────────
    // Helper: Ambil format config untuk section
    // ─────────────────────────────────────────────

    private function getSectionConfig(int $sectionId): array
    {
        return $this->sectionFormats[$sectionId] ?? [
            'input_type'      => 'standard',
            'ng_types'        => [],
            'has_atas_bawah'  => false,
            'store_breakdown' => false,
        ];
    }

    // ─────────────────────────────────────────────
    // Toggle OK (Welding)
    // ─────────────────────────────────────────────

    public function toggleOk(int $detailId, int $sectionId): void
    {
        $current = $this->checkResults[$detailId]['is_ok'] ?? false;
        $this->checkResults[$detailId]['is_ok'] = !$current;

        if ($this->checkResults[$detailId]['is_ok']) {
            $ngTypes = $this->getSectionConfig($sectionId)['ng_types'];
            foreach ($ngTypes as $ngType) {
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

        // ── Validasi unfilled per section format ──────────────
        $unfilledItems = [];

        foreach ($this->sections as $section) {
            $config    = $this->getSectionConfig($section->id);
            $inputType = $config['input_type'];
            $ngTypes   = $config['ng_types'];

            foreach ($section->details as $detail) {
                $data = $this->checkResults[$detail->id] ?? [];

                if ($inputType === 'welding') {
                    $isManualOk = (bool) ($data['is_ok'] ?? false);
                    $ngTotal    = 0;
                    foreach ($ngTypes as $ngType) {
                        $key      = 'ng_' . strtolower(str_replace(' ', '_', $ngType));
                        $ngTotal += (int) ($data[$key] ?? 0);
                    }
                    if (!$isManualOk && $ngTotal === 0) {
                        $unfilledItems[] = (int) $detail->id;
                    }
                } else {
                    if (empty($data['result'])) {
                        $unfilledItems[] = (int) $detail->id;
                    }
                }
            }
        }

        if (!empty($unfilledItems)) {
            $this->dispatch('highlight-unfilled', detailIds: $unfilledItems);
            session()->flash('error', count($unfilledItems) . ' item belum diisi. Semua item wajib di-OK atau diisi NG.');
            return;
        }

        // ── Cek duplikat serial number ─────────────────────────
        $serialExists = ChecksheetInspection::where('checksheet_head_id', $this->checksheet->id)
            ->where('serial_number', $this->serial_number)
            ->exists();

        if ($serialExists) {
            $this->serial_number = $this->generateSerialNumber();
        }

        // ── Simpan ke database ─────────────────────────────────
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

            foreach ($this->sections as $section) {
                $config         = $this->getSectionConfig($section->id);
                $inputType      = $config['input_type'];
                $ngTypes        = $config['ng_types'];
                $storeBreakdown = $config['store_breakdown'];

                foreach ($section->details as $detail) {
                    $data = $this->checkResults[$detail->id] ?? [];

                    if ($inputType === 'welding') {
                        $isManualOk  = (bool) ($data['is_ok'] ?? false);
                        $ngBreakdown = [];
                        $ngTotal     = 0;

                        foreach ($ngTypes as $ngType) {
                            $key                  = 'ng_' . strtolower(str_replace(' ', '_', $ngType));
                            $val                  = (int) ($data[$key] ?? 0);
                            $ngBreakdown[$ngType] = $val;
                            $ngTotal             += $val;
                        }

                        ChecksheetInspectionResult::create([
                            'checksheet_inspection_id' => $inspection->id,
                            'checksheet_section_id'    => $detail->checksheet_section_id,
                            'checksheet_detail_id'     => $detail->id,
                            'result'                   => $ngTotal > 0 ? 'ng' : 'ok',
                            'status'                   => $data['status'] ?? null,
                            'result_data'              => $storeBreakdown ? json_encode([
                                'ng_breakdown' => $ngBreakdown,
                                'ng_total'     => $ngTotal,
                            ]) : null,
                        ]);

                    } else {
                        ChecksheetInspectionResult::create([
                            'checksheet_inspection_id' => $inspection->id,
                            'checksheet_section_id'    => $detail->checksheet_section_id,
                            'checksheet_detail_id'     => $detail->id,
                            'result'                   => $data['result'],
                            'status'                   => $data['status'] ?? null,
                            'result_data'              => null,
                        ]);
                    }

                    $savedCount++;
                }
            }

            $inspection->update([
                'total_ok'    => ChecksheetInspectionResult::where('checksheet_inspection_id', $inspection->id)
                                    ->where('result', 'ok')->count(),
                'total_ng'    => ChecksheetInspectionResult::where('checksheet_inspection_id', $inspection->id)
                                    ->where('result', 'ng')->count(),
                'total_items' => $savedCount,
            ]);

            DB::commit();

            session()->flash('success', "Inspeksi berhasil disimpan! Total {$savedCount} items");
            return redirect()->route('home.index');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ─────────────────────────────────────────────
    // Clear Helpers
    // ─────────────────────────────────────────────

    public function clearNgOnOk(int $detailId, int $sectionId): void
    {
        $ngTypes = $this->getSectionConfig($sectionId)['ng_types'];
        foreach ($ngTypes as $type) {
            $key = 'ng_' . strtolower(str_replace(' ', '_', $type));
            $this->checkResults[$detailId][$key] = null;
        }
    }

    public function clearOkOnNg(int $detailId): void
    {
        $this->checkResults[$detailId]['is_ok'] = false;
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
