<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LkeSubComponent extends Model
{
    use HasFactory;

    protected $fillable = ['lke_component_id', 'code', 'name', 'weight'];

    public function component()
    {
        return $this->belongsTo(LkeComponent::class, 'lke_component_id');
    }

    public function criteria()
    {
        return $this->hasMany(LkeCriteria::class, 'lke_sub_component_id');
    }
}