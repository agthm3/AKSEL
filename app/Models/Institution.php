<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    protected $fillable = ['name', 'alias', 'status', 'notes'];

    // Relasi balik: Instansi ini dinilai oleh siapa saja?
    public function evaluators()
    {
        return $this->belongsToMany(User::class, 'evaluator_assignments', 'institution_id', 'user_id');
    }

    // Relasi ke User yang bekerja di instansi ini
    public function users()
    {
        return $this->hasMany(User::class, 'institution_id');
    }
}
