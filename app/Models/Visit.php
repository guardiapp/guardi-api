<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $fillable = ['resident_id', 'visitor_id', 'qr', 'remarks', 'visit_date', 'expiraton_date', 'cancelled', 'visited', 'entry_time'];

    /**
     * Relación con el modelo Resident (cada visita pertenece a un residente).
     */
    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    /**
     * Relación con el modelo Visitor (cada visita pertenece a un vistante).
     */
    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }
}
