<?php

namespace App\Livewire\Rules;

use App\Services\RuleService;
use Livewire\Component;

class Index extends Component
{
    public $rule_name = '';
    public $rule_value = '';
    public $editingId = null;

    protected $listeners = ['deleteConfirmed' => 'deleteConfirmed'];

    protected $rules = [
        'rule_name' => 'required',
        'rule_value' => 'required'
    ];

    public function save(RuleService $service)
    {
        $this->validate();

        try {
            $service->save($this->editingId, $this->rule_name, $this->rule_value);
            $this->dispatch('alert', type: 'success', message: 'Rule saved successfully');
            $this->reset(['rule_name', 'rule_value', 'editingId']);
        } catch (\Throwable $e) {
            $this->dispatch('alert', type: 'error', message: 'Failed to save rule');
        }
    }

    public function create()
    {
        $this->reset(['rule_name', 'rule_value', 'editingId']);
    }

    public function edit($id, RuleService $service)
    {
        $rule = $service->find($id);
        $this->editingId = $rule->id;
        $this->rule_name = $rule->rule_name;
        $this->rule_value = $rule->rule_value;

        $this->dispatch('open-modal');
    }

    public function deleteConfirmed($id)
    {
        try {
            app(RuleService::class)->delete($id);
            $this->dispatch('alert', type: 'success', message: 'Rule deleted');
        } catch (\Throwable $e) {
            $this->dispatch('alert', type: 'error', message: 'Failed to delete rule');
        }
    }

    public function render(RuleService $service)
    {
        return view('livewire.rules.index', [
            'rules' => $service->all()
        ])
        ->extends('layouts.app')
        ->section('content');
    }
}
