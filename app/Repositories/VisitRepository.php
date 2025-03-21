<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use App\Models\Residence;
use App\Models\Visit;
use Illuminate\Support\Facades\Auth;

class VisitRepository
{

    public function getAll($perPage, $page, array $filters)
    {
        $user = auth()->user();

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

        $this->applyVisitFilters($query, $filters);

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    /**
     * Obtener las visitas asociadas a una residencia.
     * @param Collection<Residence, int> $residence
     */
    public function getByResidence($residence, $perPage, $page, array $filters)
    {
        $query = Visit::whereHas('apartment.building', function ($query) use ($residence) {
            $query->where('residence_id', $residence->id);
        })->with(['apartment.resident.profile', 'apartment.building.residence', 'visitor']);

        if (isset($filters['normalTab'])) {
            $isNormal = filter_var($filters['normalTab'], FILTER_VALIDATE_BOOLEAN);
            if ($isNormal) {
                $query->whereNotNull('visit_date');
            } else {
                $query->whereNotNull('expiration_date');
            }
        } else {
            $query->whereNotNull('visit_date');
        }

        $this->applyVisitFilters($query, $filters);

        return $query->paginate($perPage, ['*'], 'page', $page)->appends($filters);
    }

    /**
     * Aplicar filtros dinámicos a la consulta de visitas.
     */
    protected function applyVisitFilters($query, $filters)
    {
        if (!empty($filters['visitor_name'])) {
            $query->whereHas('visitor', function ($q) use ($filters) {
                $q->where('first_name', 'like', '%' . $filters['visitor_name'] . '%')
                    ->orWhere('last_name', 'like', '%' . $filters['visitor_name'] . '%');
            });
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

        if (!empty($filters['visit_date'])) {
            $query->whereDate('visit_date', $filters['visit_date']);
        }

        if (!empty($filters['expiration_date'])) {
            $query->whereDate('expiration_date', $filters['expiration_date']);
        }

        if(isset($filters['visitType']) && !empty($filters['visitType'])) {
            $query->where(function ($q) use ($filters) {
                //with_stay || without_stay
                $with_stay = $filters['visitType'] == 'with_stay' ? 1 : 0;
                $q->where("with_stay", "=", $with_stay);
            });
        }

        if(isset($filters['managerId']) && !empty($filters['managerId'])) {
            /*$query->whereHas('apartment.building.residence.manager', function ($q) use ($filters) {
                $q->where("users.id", "=", $filters['managerId']);
            });*/
        }

        if(isset($filters['entry_time']) && !empty($filters['entry_time'])) {
            $query->where("entry_time", "=", $filters['entry_time']);
            //$query->whereRaw("DATE_FORMAT(entry_time, '%H:%i') = ".$filters['entry_time']);
        }
    }
}
