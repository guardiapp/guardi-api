<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Residence extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'address'];

    /**
     * Relación con el modelo User (cada residencia pertenece a un usuario).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con el modelo Bulding (una residencia puede tener varios edificios asociados).
     */
    public function buildings()
    {
        return $this->hasMany(Building::class);
    }
}
