<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $fillable = ['resident_id', 'document', 'first_name', 'last_name'];

    /**
     * Relación con el modelo Resident (cada visitante pertenece a un residente).
     */
    public function resident()
    {
        return $this->belongsTo(Resient::class);
    }

}
