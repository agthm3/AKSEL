<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LkeComponent extends Model
{
    use HasFactory;

    protected $fillable = ['component_number', 'name', 'weight'];

    // Relasi ke Sub-Komponen (One-to-Many)
    public function subComponents()
    {
        return $this->hasMany(LkeSubComponent::class, 'lke_component_id');
    }
}