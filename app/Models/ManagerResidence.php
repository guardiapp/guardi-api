<?php

namespace App\Models;

use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Eloquent\Model;

class ManagerResidence extends Model
{
    protected $table = "managers_residences";

    protected $fillable = [
        'manager_id',
        'residence_id',
        'state'
    ];

    public function managers() {
        return $this->hasMany(User::class, "id", "manager_id");
    }

    public function residences() {
        return $this->hasMany(Residence::class, 'id', 'residence_id');
    }
}
