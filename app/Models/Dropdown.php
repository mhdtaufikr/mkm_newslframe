<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dropdown extends Model
{
    protected $fillable = [
        'category',
        'name_value',
        'code_format'
    ];

    public static function getByCategory($category)
    {
        return self::where('category', $category)->get();
    }
}
