<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;  // ← pastikan ini
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
        'default_format_id',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ─── Relations ───────────────────────────────────────────

    public function defaultFormat(): BelongsTo
    {
        return $this->belongsTo(ChecksheetFormat::class, 'default_format_id');
    }

    public function sections(): HasMany
    {
        return $this->hasMany(ChecksheetSection::class, 'checksheet_head_id')
                    ->orderBy('order');
    }
}
