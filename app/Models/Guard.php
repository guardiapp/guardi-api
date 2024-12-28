<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guard extends Model
{
    protected $fillable = ['user_id', 'residence_id', 'document', 'first_name', 'last_name', 'phone', 'active'];

    /**
     * Relación con el modelo Residence (cada vigilante pertenece a un condominio).
     */
    public function residence()
    {
        return $this->belongsTo(Residence::class);
    }

    /**
     * Relación con el modelo User (cada vigilante es un Usuario).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
