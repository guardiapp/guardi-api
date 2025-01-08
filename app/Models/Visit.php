<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $fillable = ['apartament_id', 'visitor_id', 'qr', 'remarks', 'visit_date', 'expiraton_date', 'cancelled', 'visited', 'entry_time'];

    /**
     * Relación con el modelo Apartament (cada visita pertenece a un apartamento).
     */
    public function apartament()
    {
        return $this->belongsTo(Apartament::class);
    }

    /**
     * Relación con el modelo Visitor (cada visita pertenece a un vistante).
     */
    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }
}
