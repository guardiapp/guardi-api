<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'avatar'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relación con el modelo Profile (un usuario puede tener un perfil).
     */
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * Relación con el modelo Residence (un usuario puede tener varias residencias asociadas).
     */
    public function residences()
    {
        return $this->belongsToMany(
            Residence::class,
            'managers_residences',
            'manager_id',
            'residence_id'
        );
    }

    /**
     * Relación con el modelo Apartment (un usuario puede tener apartamentos).
     */
    public function residentAparments()
    {
        return $this->hasMany(Apartment::class);
    }

    /**
     * Relación con el modelo Guard (un usuario puede ser un vigilante).
     */
    public function securityGuard()
    {
        return $this->hasOne(Guard::class);
    }

    public function managerApartments()
    {
        return $this->hasManyThrough(Apartment::class, Building::class, 'apartment_id', 'building_id');
    }

    /**
     * Scope to filter users by type.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }
}
