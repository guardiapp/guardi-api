<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Residence extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'address'];

    /**
     * Relación con el modelo User (cada residencia pertenece a un manager).
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relación con el modelo Bulding (una residencia puede tener varios edificios asociados).
     */
    public function buildings()
    {
        return $this->hasMany(Building::class);
    }

    /**
     * Relación con el modelo Guard (una residencia puede tener varios vigilantes asociados).
     */
    public function guards()
    {
        return $this->hasMany(Guard::class);
    }

}
