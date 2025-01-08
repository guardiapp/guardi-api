<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApartmentRequest;
use App\Http\Requests\UpdateApartmentRequest;
use Illuminate\Http\Request;
use App\Models\Apartment;
use App\Models\User;
use App\Repositories\ApartmentRepository;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ApartmentController extends Controller
{
    use AuthorizesRequests;

    protected $apartmentRepository;

    public function __construct(ApartmentRepository $apartmentRepository)
    {
        $this->apartmentRepository = $apartmentRepository;
    }

    /**
     * Listar todas los los apartamentos para el usuario autenticado.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Apartment::class);

        $perPage = $request->input('per_page', 5);

        $page = $request->input('page', 1);

        $apartments = $this->apartmentRepository->getAll($perPage, $page);

        return Inertia::render('Apartments/Index', $apartments);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreApartmentRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Apartment $apartment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Apartment $apartment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateApartmentRequest $request, Apartment $apartment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Apartment $apartment)
    {
        //
    }
}
