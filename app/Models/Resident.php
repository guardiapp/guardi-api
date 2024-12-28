<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resident extends Model
{
    protected $fillable = ['user_id', 'building_id', 'document', 'first_name', 'last_name', 'apartment', 'phone', 'active' ];

    /**
     * Relación con el modelo User (cada residente es a un usuario).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con el modelo Building (cada edificio pertenece a un condominio).
     */
    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    /**
    * Relación con el modelo Visitor (un residente puede tener varios visitantes asociados).
    */
    public function visitors()
    {
        return $this->hasMany(Visitor::class);
    }
}
