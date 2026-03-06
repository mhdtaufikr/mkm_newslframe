<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;  // ← ini
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChecksheetSection extends Model
{
    protected $fillable = [
        'checksheet_head_id',
        'format_id',
        'section_name',
        'section_number',
        'section_images',
        'section_description',
        'order',
    ];

    // ─── Relations ───────────────────────────────────────────

    public function checksheetHead(): BelongsTo
    {
        return $this->belongsTo(ChecksheetHead::class, 'checksheet_head_id');
    }

    public function format(): BelongsTo
    {
        return $this->belongsTo(ChecksheetFormat::class, 'format_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(ChecksheetDetail::class, 'checksheet_section_id')
                    ->orderBy('order');
    }

    // ─── Helper ──────────────────────────────────────────────

    public function resolvedFormat(): ?ChecksheetFormat
    {
        return $this->format ?? $this->checksheetHead->defaultFormat;
    }
}
