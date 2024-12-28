<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Repositories\VisitRepository;
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

        $perPage = $request->input('per_page', 5);
        $page = $request->input('page', 1);

        $visits = $this->visitRepository->getAll($perPage, $page);

        return Inertia::render('Visits/Index', $visits);
    }
}
