<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $fillable = ['apartment_id', 'document', 'first_name', 'last_name', 'active'];

    /**
     * Relación con el modelo apartment (cada visitante pertenece a un apartamento).
     */
    public function apartment()
    {
        return $this->belongsTo(apartment::class);
    }

    /**
     * Relación con el modelo Visit (cada visitante tiene muchas visitas).
     */
    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

}
