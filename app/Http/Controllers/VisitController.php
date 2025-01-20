<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Repositories\VisitRepository;
use App\Models\Residence;
use App\Models\Visit;
use Inertia\Inertia;

class VisitController extends Controller
{
    use AuthorizesRequests;

    protected $visitRepository;

    public function __construct(VisitRepository $visitRepository)
    {
        $this->visitRepository = $visitRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Visit::class);

        $filters = $request->only([
            'visitor_name',
            'resident_name',
            'apartment',
            'visit_date',
            'expiration_date',
            'normalTab'
        ]);

        $perPage = $request->input('per_page', 5);

        $page = $request->input('page', 1);

        $visits = $this->visitRepository->getAll($perPage, $page, $filters);

        return Inertia::render('Visits/Index', [
            'visits' => $visits,
            'filters' => $filters,
        ]);
    }

    /**
     * Mostrar los vigilantes filtrados por residencia.
     */
    public function indexByResidence(Request $request, $residenceId)
    {
        $residence = Residence::with('apartments.visits')->findOrFail($residenceId);

        $this->authorize('viewByResidence', $residence);

        $filters = $request->only([
            'visitor_name',
            'resident_name',
            'apartment',
            'visit_date',
            'expiration_date',
            'normalTab'
        ]);

        if (isset($filters['normalTab'])) {
            $filters['normalTab'] = filter_var($filters['normalTab'], FILTER_VALIDATE_BOOLEAN);
        }

        $perPage = $request->input('per_page', 5);

        $page = $request->input('page', 1);

        $visits = $this->visitRepository->getByResidence($residence, $perPage, $page, $filters);

        return Inertia::render('Visits/Index', [
            'visits' => $visits,
            'residence' => $residence,
            'filters' => $filters,
        ]);
    }
}
