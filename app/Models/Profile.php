<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable  = [
        'user_id',
        'document',
        'first_name',
        'last_name',
        'phone',
        'birthdate'
    ];

    /**
     * Relación con el modelo User (cada perfil pertenece a un usuario).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
