<?php

namespace App\Services;

use App\Models\Dropdown;

class DropdownService
{
    public function all()
    {
        return Dropdown::orderBy('category')->orderBy('name_value')->get();
    }

    public function find($id)
    {
        return Dropdown::find($id);
    }

    public function save($id, $category, $nameValue, $codeFormat)
    {
        return Dropdown::updateOrCreate(
            ['id' => $id],
            [
                'category' => $category,
                'name_value' => $nameValue,
                'code_format' => $codeFormat,
            ]
        );
    }

    public function delete($id)
    {
        Dropdown::find($id)?->delete();
    }
}
