<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChecksheetDetail extends Model
{
    protected $fillable = [
        'checksheet_head_id',
        'checksheet_section_id',
        // ❌ HAPUS ini
        // 'section_name',
        // 'section_number',
        'item_code',
        'item_name',
        'item_description',
        'qpoint_code',
        'qpoint_name',
        'inspection_criteria',
        'check_type',
        'standard',
        'min_value',
        'max_value',
        'unit',
        'ok_criteria',
        'ng_criteria',
        'is_critical',
        'order',
    ];

    protected $casts = [
        'min_value' => 'decimal:2',
        'max_value' => 'decimal:2',
        'is_critical' => 'boolean',
        'order' => 'integer',
    ];

    public function head(): BelongsTo
    {
        return $this->belongsTo(ChecksheetHead::class, 'checksheet_head_id');
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(ChecksheetSection::class, 'checksheet_section_id');
    }
}
