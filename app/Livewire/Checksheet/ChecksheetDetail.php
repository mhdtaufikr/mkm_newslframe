<?php

namespace App\Livewire\Checksheet;

use Livewire\Component;
use App\Models\ChecksheetHead;
use App\Models\ChecksheetDetail as ChecksheetDetailModel; // Alias untuk model
use App\Models\ChecksheetInspection;
use App\Models\ChecksheetInspectionResult;
use Illuminate\Support\Facades\DB;

class ChecksheetDetail extends Component
{
    public $checksheet;
    public $sections;
    public $expandedSections = [];

    // Image modal
    public $showImageModal = false;
    public $selectedImage = null;

    // Form data untuk inspeksi
    public $nama;
    public $tanggal;
    public $serial_number;
    public $checkResults = [];

    public function mount($id)
    {
        $this->checksheet = ChecksheetHead::with(['sections.details'])->findOrFail($id);
        $this->sections = $this->checksheet->sections;
        $this->tanggal = date('Y-m-d');

        // Expand all sections by default untuk inspeksi
        foreach ($this->sections as $section) {
            $this->expandedSections[] = $section->id;
        }

        // Initialize checkResults
        foreach ($this->sections as $section) {
            foreach ($section->details as $detail) {
                $this->checkResults[$detail->id] = [
                    'result' => null,
                    'status' => ''
                ];
            }
        }
    }

    public function toggleSection($sectionId)
    {
        if (in_array($sectionId, $this->expandedSections)) {
            $this->expandedSections = array_diff($this->expandedSections, [$sectionId]);
        } else {
            $this->expandedSections[] = $sectionId;
        }
    }

    public function showImage($imagePath)
    {
        $this->selectedImage = $imagePath;
        $this->showImageModal = true;
    }

    public function closeImageModal()
    {
        $this->showImageModal = false;
        $this->selectedImage = null;
    }

    public function submitInspection()
    {
        // Validasi input
        $this->validate([
            'nama' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'serial_number' => 'nullable|string|max:255',
        ], [
            'nama.required' => 'Nama inspector harus diisi',
            'tanggal.required' => 'Tanggal harus diisi',
        ]);

        // Cek apakah ada hasil yang diisi
        $hasResults = false;
        foreach ($this->checkResults as $detailId => $data) {
            if (!empty($data['result'])) {
                $hasResults = true;
                break;
            }
        }

        if (!$hasResults) {
            session()->flash('error', 'Belum ada item yang di-check! Minimal pilih 1 item OK atau NG.');
            return;
        }

        DB::beginTransaction();
        try {
            // Create inspection header
            $inspection = ChecksheetInspection::create([
                'checksheet_head_id' => $this->checksheet->id,
                'nama' => $this->nama,
                'tanggal' => $this->tanggal,
                'serial_number' => $this->serial_number,
                'status' => 'completed',
                'submitted_at' => now(),
            ]);

            // Save inspection results
            $savedCount = 0;
            foreach ($this->checkResults as $detailId => $data) {
                // Skip jika belum dipilih
                if (empty($data['result'])) {
                    continue;
                }

                // Pakai alias ChecksheetDetailModel
                $detail = ChecksheetDetailModel::find($detailId);
                if ($detail) {
                    ChecksheetInspectionResult::create([
                        'checksheet_inspection_id' => $inspection->id,
                        'checksheet_section_id' => $detail->checksheet_section_id,
                        'checksheet_detail_id' => $detailId,
                        'result' => $data['result'],
                        'status' => $data['status'] ?? null,
                    ]);
                    $savedCount++;
                }
            }

            // Calculate totals
            $inspection->calculateTotals();

            DB::commit();

            // Flash success message
            session()->flash('success', "✅ Inspeksi berhasil disimpan!
                Total items: {$savedCount} |
                OK: {$inspection->total_ok} ({$inspection->percentage_ok}%) |
                NG: {$inspection->total_ng} ({$inspection->percentage_ng}%)");

            // Redirect ke halaman home
            return redirect()->route('home.index');

        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.checksheet.checksheet-detail')
            ->extends('layouts.app')
            ->section('content');
    }
}
