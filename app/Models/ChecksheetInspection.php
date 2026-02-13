<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecksheetInspection extends Model
{
    use HasFactory;

    protected $fillable = [
        'checksheet_head_id',
        'nama',
        'tanggal',
        'serial_number',
        'status',
        'total_ok',
        'total_ng',
        'total_items',
        'notes',
        'submitted_at',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'submitted_at' => 'datetime',
    ];

    // Relationships
    public function checksheetHead()
    {
        return $this->belongsTo(ChecksheetHead::class);
    }

    public function results()
    {
        return $this->hasMany(ChecksheetInspectionResult::class);
    }

    // Helpers
    public function calculateTotals()
    {
        $this->total_ok = $this->results()->where('result', 'ok')->count();
        $this->total_ng = $this->results()->where('result', 'ng')->count();
        $this->total_items = $this->results()->whereNotNull('result')->count();
        $this->save();
    }

    public function getPercentageOkAttribute()
    {
        if ($this->total_items == 0) return 0;
        return round(($this->total_ok / $this->total_items) * 100, 2);
    }

    public function getPercentageNgAttribute()
    {
        if ($this->total_items == 0) return 0;
        return round(($this->total_ng / $this->total_items) * 100, 2);
    }
}
