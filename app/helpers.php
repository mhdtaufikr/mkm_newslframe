<?php

use App\Models\Dropdown;

function dropdown($category)
{
    return Dropdown::where('category', $category)->get();
}