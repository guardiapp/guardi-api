<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Storage;
use App\Models\Visitor;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class VisitorRepository
{

    public function getAll($perPage, $page)
    {
        $user = auth()->user();

        if ($user->type === 'Admin') {
            $query = Visitor::with(['resident', 'resident.building.residence', 'resident.building.residence.manager']);
        } elseif ($user->type === 'Manager') {
            $residenceIds = $user->residences->pluck('id');
            $query = Visitor::whereHas('resident.building.residence', function ($q) use ($residenceIds) {
                $q->whereIn('id', $residenceIds);
            })->with(['resident', 'resident.building.residence', 'resident.building.residence.manager']);
        } elseif ($user->type === 'Resident') {
            $query = Visitor::where('resident_id', $user->resident->id)->with(['resident', 'resident.building.residence', 'resident.building.residence.manager']);
        } else {
            abort(403, 'Unauthorized action.');
        }

        return $query->paginate($perPage, ['*'], 'page', $page);
    }
}
