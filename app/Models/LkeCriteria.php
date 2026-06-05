<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LkeCriteria extends Model
{
    use HasFactory;

    // Ingat, kita harus definisikan nama tabel karena Laravel akan mencari 'lke_criterias'
    protected $table = 'lke_criteria'; 

    protected $fillable = ['lke_sub_component_id', 'criteria_number', 'description', 'expected_evidence'];

    public function subComponent()
    {
        return $this->belongsTo(LkeSubComponent::class, 'lke_sub_component_id');
    }

    public function evaluations()
    {
        return $this->hasMany(LkeEvaluation::class, 'lke_criteria_id');
    }
}