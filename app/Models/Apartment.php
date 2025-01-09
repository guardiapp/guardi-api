<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apartment extends Model
{

    protected $fillable = ['user_id', 'building_id', 'identifier', 'active'];


    /**
     * Relación con el modelo User (cada apartamento pertenece a un usuario).
     */
    public function resident()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relación con el modelo Building (cada apartamento pertenece a un edificio).
     */
    public function building()
    {
        return $this->belongsTo(Building::class);
    }

}
