<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LkeEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'institution_id', 
        'lke_criteria_id', 
        'predicate', 
        'final_score', 
        'status', 
        'inspector_notes', 
        'evaluation_year'
    ];

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function criteria()
    {
        return $this->belongsTo(LkeCriteria::class, 'lke_criteria_id');
    }



    // Ubah namanya menjadi documents() (Jamak)
    public function documents()
    {
        return $this->belongsToMany(Document::class);
    }
}