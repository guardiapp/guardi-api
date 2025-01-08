<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    protected $fillable = ['residence_id', 'name', 'floors_number', 'apartments_per_floor', 'active', 'information'];

    /**
     * Relación con el modelo Residence (cada edificio pertenece a un condominio).
     */
    public function residence()
    {
        return $this->belongsTo(Residence::class);
    }

    /**
    * Relación con el modelo Apartment (un edificio puede tener varios apartamentos asociados).
    */
    public function apartments()
    {
        return $this->hasMany(Apartment::class);
    }
}
