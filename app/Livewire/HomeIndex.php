<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ChecksheetHead;

class HomeIndex extends Component
{
    public function render()
    {
        $checksheets = ChecksheetHead::where('is_active', 1)
        ->orderBy('order')
        ->orderBy('id')  // ← tambah fallback sort by id
        ->get();


        return view('livewire.home-index', [
            'checksheets' => $checksheets
        ])
            ->extends('layouts.app')
            ->section('content');
    }
}
