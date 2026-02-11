<?php

namespace App\Livewire;

use Livewire\Component;

class HomeIndex extends Component
{
    public function render()
    {
        return view('livewire.home-index')
            ->extends('layouts.app')
            ->section('content');
    }
}
