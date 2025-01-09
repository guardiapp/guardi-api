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
            // Admin puede ver todos los visitors
            $query = Visitor::with([
                'apartment.building.residence.manager',
                'apartment.resident',
                'apartment.resident.profile',
            ]);
        } elseif ($user->type === 'Manager') {
            // Manager puede ver los visitors de las residences que maneja
            $residenceIds = $user->residences->pluck('id');

            $query = Visitor::whereHas('apartment.building.residence', function ($q) use ($residenceIds) {
                $q->whereIn('id', $residenceIds);
            })->with([
                'apartment.building.residence',
                'apartment.user',
            ]);
        } elseif ($user->type === 'Resident') {
            // Resident solo puede ver los visitors de su apartment asignado
            if (!$user->apartment) {
                abort(403, 'Unauthorized action.');
            }

            $query = Visitor::where('apartment_id', $user->apartment->id)
                ->with([
                    'apartment.building.residence',
                    'apartment.user',
                ]);
        } else {
            // Si no es un tipo de usuario autorizado, no puede acceder
            abort(403, 'Unauthorized action.');
        }

        // Paginación y retorno
        return $query->paginate($perPage, ['*'], 'page', $page);
    }
}

