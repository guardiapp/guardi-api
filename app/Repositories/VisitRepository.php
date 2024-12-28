<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Storage;
use App\Models\Visit;
use Illuminate\Support\Facades\Auth;

class VisitRepository
{

    public function getAll($perPage, $page)
    {
        $user = auth()->user();
        //faltal la relacion con Guard
        if ($user->type === 'Admin') {
            $query = Visit::with(['resident', 'visitor', 'resident.building.residence', 'resident.building.residence.manager']);
        } elseif ($user->type === 'Manager') {
            $residenceIds = $user->residences->pluck('id');
            $query = Visit::whereHas('resident.building.residence', function ($q) use ($residenceIds) {
                $q->whereIn('id', $residenceIds);
            })->with(['resident', 'visitor', 'resident.building.residence', 'resident.building.residence.manager']);
        } elseif ($user->type === 'Resident') {
            $query = Visit::where('resident_id', $user->resident->id)->with(['resident', 'visitor', 'resident.building.residence', 'resident.building.residence.manager']);
        } else {
            abort(403, 'Unauthorized action.');
        }

        return $query->paginate($perPage, ['*'], 'page', $page);
    }
}
