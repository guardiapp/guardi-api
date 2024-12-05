<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $fillable = ['resident_id', 'visitor_id', 'entry_time', 'exit_time', 'comments', 'visit_confirmed'];
}
