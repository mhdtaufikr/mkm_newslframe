<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecksheetInspectionResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'checksheet_inspection_id',
        'checksheet_section_id',
        'checksheet_detail_id',
        'result',
        'status',
    ];

    // Relationships
    public function inspection()
    {
        return $this->belongsTo(ChecksheetInspection::class, 'checksheet_inspection_id');
    }

    public function section()
    {
        return $this->belongsTo(ChecksheetSection::class, 'checksheet_section_id');
    }

    public function detail()
    {
        return $this->belongsTo(ChecksheetDetail::class, 'checksheet_detail_id');
    }

}
