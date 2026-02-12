<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ChecksheetHead;

class HomeIndex extends Component
{
    public function render()
    {
        // Ambil checksheet yang active, diurutkan berdasarkan order dan created_at
        $checksheets = ChecksheetHead::where('is_active', true)
            ->orderBy('order')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.home-index', [
            'checksheets' => $checksheets
        ])
            ->extends('layouts.app')
            ->section('content');
    }
}
