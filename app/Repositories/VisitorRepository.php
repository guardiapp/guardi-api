<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Storage;
use App\Models\Visitor;
use App\Models\Resident;
use App\Models\Residence;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class VisitorRepository
{
    public function saveOrUpdate($payload, $id = null) {
        try {
            $visitor = Visitor::firstOrNew(["id" => $id]);
            $visitor->fill($payload);
            $visitor->save();
            return $visitor;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function findById(Int $id) {
        return Visitor::findOrFail($id);
    }

    public function deleteById(Int $id) {
        Visitor::destroy([ $id ]);
        return true;
    }

    public function getAll($perPage, $page, array $filters)
    {
        $user = auth()->user();

        if ($user->type === 'Admin') {
            $query = Visitor::with([
                'apartment.building.residence.manager',
                'apartment.resident.profile',
            ]);
        } elseif ($user->type === 'Manager') {
            $residenceIds = $user->residences->pluck('id');

            $query = Visitor::whereHas('apartment.building.residence', function ($q) use ($residenceIds) {
                $q->whereIn('id', $residenceIds);
            })->with([
                'apartment.building.residence.manager',
                'apartment.resident.profile',
            ]);
        } else {
            abort(403, 'Unauthorized action.');
        }

        $this->applyFilters($query, $filters);

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    /**
     * Obtener los visitantes asociados a una residencia.
     */
    public function getByResidence(Residence $residence, $perPage, $page, array $filters)
    {
        $query = Visitor::whereHas('apartment.building', function ($query) use ($residence) {
            $query->where('residence_id', $residence->id);
        })
        ->with(['apartment.resident.profile', 'apartment.building.residence.manager']);

        $this->applyFilters($query, $filters);


        return $query->paginate($perPage, ['*'], 'page', $page)->appends($filters);
    }

    /**
     * Aplicar filtros dinámicos a la consulta de apartamentos.
     */
    protected function applyFilters($query, $filters)
    {
        if (!empty($filters['name'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('first_name', 'like', '%' . $filters['name'] . '%')
                  ->orWhere('last_name', 'like', '%' . $filters['name'] . '%');
            });
        }

        if (!empty($filters['document'])) {
            $query->where('document', 'like', '%' . $filters['document'] . '%');
        }

        if (!empty($filters['resident_name'])) {
            $query->whereHas('apartment.resident.profile', function ($q) use ($filters) {
                $q->where('first_name', 'like', '%' . $filters['resident_name'] . '%')
                  ->orWhere('last_name', 'like', '%' . $filters['resident_name'] . '%');
            });
        }

        if (!empty($filters['apartment'])) {
            $query->whereHas('apartment', function ($q) use ($filters) {
                $q->where('identifier', 'like', '%' . $filters['apartment'] . '%');
            });
        }
    }
}

