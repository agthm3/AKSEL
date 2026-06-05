<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = ['institution_id', 'name', 'file_path', 'year'];

    // Dokumen ini milik instansi siapa?
    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function evaluations()
    {
        return $this->belongsToMany(LkeEvaluation::class, 'document_lke_evaluation');
    }
}