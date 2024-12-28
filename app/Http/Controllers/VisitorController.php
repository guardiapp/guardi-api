<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\StoreVisitorRequest;
use App\Http\Requests\UpdateVisitorRequest;
use App\Repositories\VisitorRepository;
use App\Models\Visitor;
use Inertia\Inertia;

class VisitorController extends Controller
{
    use AuthorizesRequests;

    protected $visitorRepository;

    public function __construct(VisitorRepository $visitorRepository)
    {
        $this->visitorRepository = $visitorRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Visitor::class);

        $perPage = $request->input('per_page', 5);
        $page = $request->input('page', 1);

        $visitors = $this->visitorRepository->getAll($perPage, $page);

        return Inertia::render('Visitors/Index', $visitors);
    }
}
