<?php

namespace App\Livewire\Dropdowns;

use App\Services\DropdownService;
use Livewire\Component;

class Index extends Component
{
    public $category = '';
    public $name_value = '';
    public $code_format = '';
    public $editingId = null;

    protected $listeners = ['deleteConfirmed' => 'deleteConfirmed'];

    protected $rules = [
        'category' => 'required',
        'name_value' => 'required',
        'code_format' => 'nullable',
    ];

    public function save(DropdownService $service)
    {
        $this->validate();

        try {
            $service->save($this->editingId, $this->category, $this->name_value, $this->code_format);
            $this->dispatch('alert', type: 'success', message: 'Dropdown saved successfully');
            $this->reset(['category', 'name_value', 'code_format', 'editingId']);
        } catch (\Throwable $e) {
            $this->dispatch('alert', type: 'error', message: 'Failed to save dropdown');
        }
    }

    public function create()
    {
        $this->reset(['category', 'name_value', 'code_format', 'editingId']);
    }

    public function edit($id, DropdownService $service)
    {
        $item = $service->find($id);
        $this->editingId = $item->id;
        $this->category = $item->category;
        $this->name_value = $item->name_value;
        $this->code_format = $item->code_format;

        $this->dispatch('open-modal');
    }

    public function deleteConfirmed($id)
    {
        try {
            app(DropdownService::class)->delete($id);
            $this->dispatch('alert', type: 'success', message: 'Dropdown deleted');
        } catch (\Throwable $e) {
            $this->dispatch('alert', type: 'error', message: 'Failed to delete dropdown');
        }
    }

    public function render()
    {
        return view('livewire.dropdowns.index', [
            'items' => app(DropdownService::class)->all(),
        ])
        ->extends('layouts.app')
        ->section('content');
    }
}
