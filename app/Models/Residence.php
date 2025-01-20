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
     * Relación con el modelo Guard (una residencia puede tener varios vigilantes asociados).
     */
    public function guards()
    {
        return $this->hasMany(Guard::class);
    }

    /**
     * Relación con el modelo Bulding (una residencia puede tener varios edificios asociados).
     */
    public function buildings()
    {
        return $this->hasMany(Building::class);
    }

    /**
     * Get all of the apartments for the residence.
     */
    public function apartments()
    {
        return $this->hasManyThrough(Apartment::class, Building::class);
    }

    public function visitors()
    {
        return $this->hasManyThrough(
            Visitor::class,
            Apartment::class,
            'building_id',
            'apartment_id',
            'id',
            'id'
        )->join('buildings', 'buildings.id', '=', 'apartments.building_id')
          ->where('buildings.residence_id', '=', $this->id);
    }

    public function visits()
    {
        return $this->hasManyThrough(
            Visit::class,
            Apartment::class,
            'building_id',
            'apartment_id',
            'id',
            'id'
        )->join('buildings', 'buildings.id', '=', 'apartments.building_id')
        ->where('buildings.residence_id', '=', $this->id);
    }
}
