<?php

namespace App\Livewire\Checksheet;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\ChecksheetHead;
use App\Models\ChecksheetSection;
use App\Models\ChecksheetDetail;
use Illuminate\Support\Facades\DB;

class MasterIndex extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $perPage = 10;

    // Modal States
    public $showHeadModal = false;
    public $showSectionModal = false;
    public $showDetailModal = false;
    public $editMode = false;

    // Form fields - Head
    public $checksheet_id;
    public $code;
    public $title;
    public $subtitle;
    public $revision;
    public $document_number;
    public $process_name;
    public $is_active = true;
    public $order = 0;

    // Form fields - Section
    public $section_id;
    public $selected_checksheet_id;
    public $section_name;
    public $section_number;
    public $section_images = [];           // Multiple upload
    public $existing_section_images = [];  // Existing images
    public $removed_images = [];           // Track deleted images
    public $section_description;
    public $section_order;

    // Form fields - Detail
    public $detail_id;
    public $selected_section_id;
    public $item_code;
    public $item_name;
    public $item_description;
    public $qpoint_code;
    public $qpoint_name;
    public $inspection_criteria;
    public $check_type = 'visual';
    public $standard;
    public $min_value;
    public $max_value;
    public $unit;
    public $ok_criteria;
    public $ng_criteria;
    public $is_critical = false;
    public $detail_order;

    // UI State
    public $expandedChecksheets = [];
    public $expandedSections = [];

    protected $queryString = ['search'];

    protected function getHeadRules()
    {
        $rules = [
            'code' => 'required|max:10',
            'title' => 'required|max:255',
            'subtitle' => 'nullable|max:255',
            'revision' => 'required|max:50',
            'document_number' => 'required|max:100',
            'process_name' => 'nullable|max:255',
            'is_active' => 'boolean',
            'order' => 'integer',
        ];

        if ($this->editMode) {
            $rules['code'] = 'required|max:10|unique:checksheet_heads,code,' . $this->checksheet_id;
        } else {
            $rules['code'] = 'required|max:10|unique:checksheet_heads,code';
        }

        return $rules;
    }

    protected function getSectionRules()
    {
        return [
            'selected_checksheet_id' => 'required|exists:checksheet_heads,id',
            'section_name' => 'required|max:255',
            'section_number' => 'required|integer',
            'section_images.*' => 'nullable|image|max:2048', // ⬅️ GANTI: tambah .*
            'section_description' => 'nullable',
            'section_order' => 'required|integer',
        ];
    }


    protected function getDetailRules()
    {
        return [
            'selected_section_id' => 'required|exists:checksheet_sections,id',
            'item_code' => 'required|max:50',
            'item_name' => 'required|max:255',
            'item_description' => 'nullable',
            'qpoint_code' => 'nullable|max:50',
            'qpoint_name' => 'nullable|max:255',
            'inspection_criteria' => 'nullable',
            'check_type' => 'required|in:visual,measurement,functional,other',
            'standard' => 'nullable|max:100',
            'min_value' => 'nullable|numeric',
            'max_value' => 'nullable|numeric',
            'unit' => 'nullable|max:20',
            'ok_criteria' => 'nullable',
            'ng_criteria' => 'nullable',
            'is_critical' => 'boolean',
            'detail_order' => 'required|integer',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $checksheets = ChecksheetHead::query()
            ->with(['sections.details'])
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('code', 'like', '%' . $this->search . '%')
                    ->orWhere('document_number', 'like', '%' . $this->search . '%');
            })
            ->orderBy('order')
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.checksheet.master-index', [
            'checksheets' => $checksheets
        ])
        ->extends('layouts.app')
        ->section('content');
    }

    // ==================== HEAD METHODS ====================

    public function createHead()
    {
        $this->resetHeadForm();
        $this->editMode = false;
        $this->showHeadModal = true;
    }

    public function editHead($id)
    {
        $checksheet = ChecksheetHead::findOrFail($id);

        $this->checksheet_id = $checksheet->id;
        $this->code = $checksheet->code;
        $this->title = $checksheet->title;
        $this->subtitle = $checksheet->subtitle;
        $this->revision = $checksheet->revision;
        $this->document_number = $checksheet->document_number;
        $this->process_name = $checksheet->process_name;
        $this->is_active = $checksheet->is_active;
        $this->order = $checksheet->order;

        $this->editMode = true;
        $this->showHeadModal = true;
    }

    public function saveHead()
    {
        $this->validate($this->getHeadRules());

        try {
            if ($this->editMode) {
                $checksheet = ChecksheetHead::findOrFail($this->checksheet_id);
                $checksheet->update([
                    'code' => $this->code,
                    'title' => $this->title,
                    'subtitle' => $this->subtitle,
                    'revision' => $this->revision,
                    'document_number' => $this->document_number,
                    'process_name' => $this->process_name,
                    'is_active' => $this->is_active,
                    'order' => $this->order,
                ]);

                session()->flash('success', 'Checksheet berhasil diupdate');
            } else {
                ChecksheetHead::create([
                    'code' => $this->code,
                    'title' => $this->title,
                    'subtitle' => $this->subtitle,
                    'revision' => $this->revision,
                    'document_number' => $this->document_number,
                    'process_name' => $this->process_name,
                    'is_active' => $this->is_active,
                    'order' => $this->order,
                ]);

                session()->flash('success', 'Checksheet berhasil ditambahkan');
            }

            $this->closeHeadModal();
            $this->resetHeadForm();
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function deleteHead($id)
    {
        try {
            $checksheet = ChecksheetHead::findOrFail($id);
            $checksheet->delete();

            session()->flash('success', 'Checksheet berhasil dihapus');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function toggleStatus($id)
    {
        $checksheet = ChecksheetHead::findOrFail($id);
        $checksheet->update(['is_active' => !$checksheet->is_active]);

        session()->flash('success', 'Status checksheet berhasil diubah');
    }

    public function closeHeadModal()
    {
        $this->showHeadModal = false;
        $this->resetHeadForm();
    }

    private function resetHeadForm()
    {
        $this->reset([
            'checksheet_id',
            'code',
            'title',
            'subtitle',
            'revision',
            'document_number',
            'process_name',
            'is_active',
            'order',
        ]);
        $this->is_active = true;
        $this->order = 0;
        $this->resetValidation();
    }

    // ==================== SECTION METHODS ====================

    public function createSection($checksheetId)
    {
        $this->resetSectionForm();
        $this->selected_checksheet_id = $checksheetId;
        $this->editMode = false;
        $this->showSectionModal = true;

        // Auto order
        $lastSection = ChecksheetSection::where('checksheet_head_id', $checksheetId)
            ->orderBy('order', 'desc')
            ->first();
        $this->section_order = $lastSection ? $lastSection->order + 1 : 1;
        $this->section_number = $this->section_order;
    }

    public function editSection($id)
{
    $section = ChecksheetSection::findOrFail($id);

    $this->section_id = $section->id;
    $this->selected_checksheet_id = $section->checksheet_head_id;
    $this->section_name = $section->section_name;
    $this->section_number = $section->section_number;

    // ⬇️ GANTI bagian ini
    $this->existing_section_images = $section->section_images
        ? json_decode($section->section_images, true)
        : [];

    $this->section_description = $section->section_description;
    $this->section_order = $section->order;

    $this->editMode = true;
    $this->showSectionModal = true;
}

// Method baru untuk hapus image tertentu
public function removeImage($imageName)
{
    if (!in_array($imageName, $this->removed_images)) {
        $this->removed_images[] = $imageName;
    }

    $this->existing_section_images = array_filter(
        $this->existing_section_images,
        fn($img) => $img !== $imageName
    );
}


public function saveSection()
{
    $this->validate($this->getSectionRules());

    try {
        $destinationPath = public_path('images/section');
        if (!is_dir($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        // Start dengan existing images
        $allImages = $this->existing_section_images;

        // Hapus deleted images
        foreach ($this->removed_images as $removedImage) {
            $filePath = $destinationPath . DIRECTORY_SEPARATOR . $removedImage;
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
            $allImages = array_filter($allImages, fn($img) => $img !== $removedImage);
        }

        // Upload new images
        if (!empty($this->section_images)) {
            foreach ($this->section_images as $image) {
                $extension = $image->getClientOriginalExtension();
                $imageName = time() . '_' . uniqid() . '.' . $extension;
                $fullPath = $destinationPath . DIRECTORY_SEPARATOR . $imageName;

                $tempPath = $image->getRealPath();
                if (file_exists($tempPath)) {
                    if (copy($tempPath, $fullPath)) {
                        $allImages[] = $imageName;
                    } else {
                        throw new \Exception('Gagal menyalin file gambar');
                    }
                } else {
                    throw new \Exception('File temporary tidak ditemukan');
                }
            }
        }

        // Store as JSON
        $data = [
            'section_name' => $this->section_name,
            'section_number' => $this->section_number,
            'section_images' => !empty($allImages) ? json_encode(array_values($allImages)) : null,
            'section_description' => $this->section_description,
            'order' => $this->section_order,
        ];

        if ($this->editMode) {
            $section = ChecksheetSection::findOrFail($this->section_id);
            $section->update($data);
            session()->flash('success', 'Section berhasil diupdate');
        } else {
            $data['checksheet_head_id'] = $this->selected_checksheet_id;
            ChecksheetSection::create($data);
            session()->flash('success', 'Section berhasil ditambahkan');
        }

        $this->closeSectionModal();
        $this->resetSectionForm();

        if (!in_array($this->selected_checksheet_id, $this->expandedChecksheets)) {
            $this->expandedChecksheets[] = $this->selected_checksheet_id;
        }
    } catch (\Exception $e) {
        session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}



public function deleteSection($id)
{
    try {
        $section = ChecksheetSection::findOrFail($id);

        // Delete all images
        if ($section->section_images) {
            $images = json_decode($section->section_images, true);
            if (is_array($images)) {
                foreach ($images as $image) {
                    $imagePath = public_path('images/section/' . $image);
                    if (file_exists($imagePath)) {
                        @unlink($imagePath);
                    }
                }
            }
        }

        $section->delete();
        session()->flash('success', 'Section berhasil dihapus');
    } catch (\Exception $e) {
        session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}


    public function closeSectionModal()
    {
        $this->showSectionModal = false;
        $this->resetSectionForm();
    }

    private function resetSectionForm()
    {
        $this->reset([
            'section_id',
            'selected_checksheet_id',
            'section_name',
            'section_number',
            'section_images',           // ⬅️ GANTI
            'existing_section_images',  // ⬅️ GANTI
            'removed_images',           // ⬅️ TAMBAH
            'section_description',
            'section_order',
        ]);
        $this->resetValidation();
    }




    // ==================== DETAIL METHODS ====================
    // Tambahkan di bagian property
    public $detailItems = [];
    public $multipleDetailMode = false;
    public function createDetail($sectionId)
    {
        $this->resetDetailForm();
        $this->selected_section_id = $sectionId;
        $this->editMode = false;
        $this->multipleDetailMode = false;
        $this->showDetailModal = true;

        // Auto order
        $lastDetail = ChecksheetDetail::where('checksheet_section_id', $sectionId)
            ->orderBy('order', 'desc')
            ->first();
        $this->detail_order = $lastDetail ? $lastDetail->order + 1 : 1;
    }

    public function createMultipleDetails($sectionId)
{
    $this->resetDetailForm();
    $this->selected_section_id = $sectionId;
    $this->editMode = false;
    $this->multipleDetailMode = true;
    $this->showDetailModal = true;

    // Initialize dengan 5 row kosong
    $lastDetail = ChecksheetDetail::where('checksheet_section_id', $sectionId)
        ->orderBy('order', 'desc')
        ->first();
    $startOrder = $lastDetail ? $lastDetail->order + 1 : 1;

    $this->detailItems = [];
    for ($i = 0; $i < 5; $i++) {
        $this->detailItems[] = [
            'item_code' => '',
            'item_name' => '',
            'qpoint_name' => '',
            'check_type' => 'visual',
            'is_critical' => false,
            'order' => $startOrder + $i,
        ];
    }
}

public function addDetailRow()
{
    $lastOrder = count($this->detailItems) > 0
        ? $this->detailItems[count($this->detailItems) - 1]['order']
        : 1;

    $this->detailItems[] = [
        'item_code' => '',
        'item_name' => '',
        'qpoint_name' => '',
        'check_type' => 'visual',
        'is_critical' => false,
        'order' => $lastOrder + 1,
    ];
}

// Remove row
public function removeDetailRow($index)
{
    unset($this->detailItems[$index]);
    $this->detailItems = array_values($this->detailItems);
}

public function saveMultipleDetails()
{
    try {
        $section = ChecksheetSection::findOrFail($this->selected_section_id);
        $savedCount = 0;

        foreach ($this->detailItems as $item) {
            // Skip empty rows
            if (empty($item['item_code']) && empty($item['item_name'])) {
                continue;
            }

            // Validate required fields
            if (empty($item['item_code']) || empty($item['item_name'])) {
                continue;
            }

            ChecksheetDetail::create([
                'checksheet_head_id' => $section->checksheet_head_id,
                'checksheet_section_id' => $this->selected_section_id,
                'item_code' => $item['item_code'],
                'item_name' => $item['item_name'],
                'item_description' => $item['item_description'] ?? null,
                'qpoint_code' => $item['qpoint_code'] ?? null,
                'qpoint_name' => $item['qpoint_name'] ?? null,
                'inspection_criteria' => $item['inspection_criteria'] ?? null,
                'check_type' => $item['check_type'] ?? 'visual',
                'standard' => $item['standard'] ?? null,
                'min_value' => $item['min_value'] ?? null,
                'max_value' => $item['max_value'] ?? null,
                'unit' => $item['unit'] ?? null,
                'ok_criteria' => $item['ok_criteria'] ?? null,
                'ng_criteria' => $item['ng_criteria'] ?? null,
                'is_critical' => $item['is_critical'] ?? false,
                'order' => $item['order'],
            ]);

            $savedCount++;
        }

        if ($savedCount > 0) {
            session()->flash('success', "$savedCount detail items berhasil ditambahkan");
        } else {
            session()->flash('error', 'Tidak ada data yang disimpan');
        }

        $this->closeDetailModal();
        $this->resetDetailForm();

        if (!in_array($this->selected_section_id, $this->expandedSections)) {
            $this->expandedSections[] = $this->selected_section_id;
        }
    } catch (\Exception $e) {
        session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

    public function editDetail($id)
    {
        $detail = ChecksheetDetail::findOrFail($id);

        $this->detail_id = $detail->id;
        $this->selected_section_id = $detail->checksheet_section_id;
        $this->item_code = $detail->item_code;
        $this->item_name = $detail->item_name;
        $this->item_description = $detail->item_description;
        $this->qpoint_code = $detail->qpoint_code;
        $this->qpoint_name = $detail->qpoint_name;
        $this->inspection_criteria = $detail->inspection_criteria;
        $this->check_type = $detail->check_type;
        $this->standard = $detail->standard;
        $this->min_value = $detail->min_value;
        $this->max_value = $detail->max_value;
        $this->unit = $detail->unit;
        $this->ok_criteria = $detail->ok_criteria;
        $this->ng_criteria = $detail->ng_criteria;
        $this->is_critical = $detail->is_critical;
        $this->detail_order = $detail->order;

        $this->editMode = true;
        $this->showDetailModal = true;
    }

    public function saveDetail()
    {
        $this->validate($this->getDetailRules());

        try {
            $section = ChecksheetSection::findOrFail($this->selected_section_id);

            $detailData = [
                'checksheet_head_id' => $section->checksheet_head_id,
                'checksheet_section_id' => $this->selected_section_id,
                'item_code' => $this->item_code,
                'item_name' => $this->item_name,
                'item_description' => $this->item_description,
                'qpoint_code' => $this->qpoint_code,
                'qpoint_name' => $this->qpoint_name,
                'inspection_criteria' => $this->inspection_criteria,
                'check_type' => $this->check_type,
                'standard' => $this->standard,
                'min_value' => $this->min_value,
                'max_value' => $this->max_value,
                'unit' => $this->unit,
                'ok_criteria' => $this->ok_criteria,
                'ng_criteria' => $this->ng_criteria,
                'is_critical' => $this->is_critical,
                'order' => $this->detail_order,
            ];

            if ($this->editMode) {
                $detail = ChecksheetDetail::findOrFail($this->detail_id);
                $detail->update($detailData);

                session()->flash('success', 'Detail berhasil diupdate');
            } else {
                ChecksheetDetail::create($detailData);

                session()->flash('success', 'Detail berhasil ditambahkan');
            }

            $this->closeDetailModal();
            $this->resetDetailForm();

            if (!in_array($this->selected_section_id, $this->expandedSections)) {
                $this->expandedSections[] = $this->selected_section_id;
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function deleteDetail($id)
    {
        try {
            $detail = ChecksheetDetail::findOrFail($id);
            $detail->delete();

            session()->flash('success', 'Detail berhasil dihapus');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->resetDetailForm();
    }

    private function resetDetailForm()
    {
        $this->reset([
            'detail_id',
            'selected_section_id',
            'item_code',
            'item_name',
            'item_description',
            'qpoint_code',
            'qpoint_name',
            'inspection_criteria',
            'check_type',
            'standard',
            'min_value',
            'max_value',
            'unit',
            'ok_criteria',
            'ng_criteria',
            'is_critical',
            'detail_order',
            'detailItems',
            'multipleDetailMode',
        ]);
        $this->check_type = 'visual';
        $this->is_critical = false;
        $this->resetValidation();
    }

    // ==================== UI METHODS ====================

    public function toggleChecksheet($id)
    {
        if (in_array($id, $this->expandedChecksheets)) {
            $this->expandedChecksheets = array_diff($this->expandedChecksheets, [$id]);
        } else {
            $this->expandedChecksheets[] = $id;
        }
    }

  // Di MasterIndex.php

public function toggleSection($id)
{
    if (in_array($id, $this->expandedSections)) {
        $this->expandedSections = array_diff($this->expandedSections, [$id]);
    } else {
        $this->expandedSections[] = $id;
    }
}

}
