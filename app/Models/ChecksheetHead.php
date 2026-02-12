<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChecksheetHead extends Model
{
    protected $fillable = [
        'code',
        'title',
        'subtitle',
        'revision',
        'document_number',
        'process_name',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];


    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    public function sections()
    {
        return $this->hasMany(ChecksheetSection::class, 'checksheet_head_id');
    }

    public function details()
    {
        return $this->hasMany(ChecksheetDetail::class, 'checksheet_head_id');
    }
    public function inspections()
{
    return $this->hasMany(ChecksheetInspection::class);
}




}
