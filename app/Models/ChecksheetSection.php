<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
// use Illuminate\Database\Eloquent\SoftDeletes; // ⬅️ HAPUS atau comment

class ChecksheetSection extends Model
{
    // use SoftDeletes; // ⬅️ HAPUS atau comment

    protected $fillable = [
        'checksheet_head_id',
        'section_number',
        'section_name',
        'section_description',
        'section_images',
        'order',
    ];

    protected $casts = [
        'section_number' => 'integer',
        'order' => 'integer',
    ];

    // Accessors tetap sama
    public function getSectionImagesArrayAttribute()
    {
        if (empty($this->section_images)) {
            return [];
        }

        $decoded = json_decode($this->section_images, true);
        return is_array($decoded) ? $decoded : [];
    }

    public function getFirstImageAttribute()
    {
        $images = $this->section_images_array;
        return !empty($images) ? $images[0] : null;
    }

    public function getImageCountAttribute()
    {
        return count($this->section_images_array);
    }

    // Relationships
    public function checksheetHead(): BelongsTo
    {
        return $this->belongsTo(ChecksheetHead::class, 'checksheet_head_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(ChecksheetDetail::class, 'checksheet_section_id')
            ->orderBy('order');
    }
}
