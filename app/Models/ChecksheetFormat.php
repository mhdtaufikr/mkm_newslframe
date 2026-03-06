<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChecksheetFormat extends Model
{
    protected $fillable = [
        'name',
        'description',
        'config',
        'is_active',
    ];

    protected $casts = [
        'config'    => 'array',
        'is_active' => 'boolean',
    ];

    // ─── Relations ───────────────────────────────────────────

    public function checksheetHeads(): HasMany
    {
        return $this->hasMany(ChecksheetHead::class, 'default_format_id');
    }

    public function checksheetSections(): HasMany
    {
        return $this->hasMany(ChecksheetSection::class, 'format_id');
    }

    // ─── Scopes ──────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ─── Helpers ─────────────────────────────────────────────

    public function getInputType(): string
    {
        return $this->config['input_type'] ?? 'standard';
    }

    public function getNgTypes(): array
    {
        return $this->config['ng_types'] ?? [];
    }

    public function hasAtasBawah(): bool
    {
        return $this->config['has_atas_bawah'] ?? false;
    }

    public function storesBreakdown(): bool
    {
        return $this->config['store_breakdown'] ?? false;
    }

    public function isWelding(): bool
    {
        return $this->getInputType() === 'welding';
    }

    public function isStandard(): bool
    {
        return $this->getInputType() === 'standard';
    }

    // ─── Static Factories ─────────────────────────────────────

    public static function getDefault(): ?self
    {
        return static::active()->where('name', 'Standard')->first();
    }
}
