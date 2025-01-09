<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $fillable = ['apartment_id', 'visitor_id', 'qr', 'remarks', 'visit_date', 'expiraton_date', 'cancelled', 'visited', 'entry_time'];

    /**
     * Relación con el modelo Apartment (cada visita pertenece a un apartamento).
     */
    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }

    /**
     * Relación con el modelo Visitor (cada visita pertenece a un vistante).
     */
    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }
}
