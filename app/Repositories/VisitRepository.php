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
            $query = Visit::with([
                'visitor',
                'apartment.resident.profile',
                'apartment.building.residence.manager'
            ]);
        } elseif ($user->type === 'Manager') {
            $residenceIds = $user->residences->pluck('id');
            $query = Visit::whereHas('apartment.building.residence', function ($q) use ($residenceIds) {
                $q->whereIn('id', $residenceIds);
            })->with([
                'visitor',
                'apartment.resident.profile',
                'apartment.building.residence.manager'
            ]);
        } elseif ($user->type === 'Resident') {
            $query = Visit::where('apartment_id', $user->apartment->id)->with([
                'visitor',
                'apartment.resident.profile',
                'apartment.building.residence.manager'
            ]);
        } else {
            abort(403, 'Unauthorized action.');
        }

        return $query->paginate($perPage, ['*'], 'page', $page);
    }
}
