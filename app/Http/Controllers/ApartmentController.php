<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApartmentRequest;
use App\Http\Requests\UpdateApartmentRequest;
use Illuminate\Http\Request;
use App\Models\Apartment;
use App\Models\Residence;
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

        $filters = $request->only(['identifier', 'resident_name', 'building_name']);

        $perPage = $request->input('per_page', 5);

        $page = $request->input('page', 1);

        $apartments = $this->apartmentRepository->getAll($perPage, $page, $filters);

        return Inertia::render('Apartments/Index', [
            'apartments' => $apartments,
            'filters' => $filters,
        ]);
    }

    /**
     * Mostrar los apartamentos filtrados por residencia.
     */
    public function indexByResidence(Request $request, $residenceId)
    {
        $residence = Residence::with('apartments')->findOrFail($residenceId);

        $this->authorize('viewByResidence', $residence);

        $filters = $request->only(['identifier', 'resident_name', 'building_name']);

        $perPage = $request->input('per_page', 5);

        $page = $request->input('page', 1);

        $apartments = $this->apartmentRepository->getByResidence($residence, $perPage, $page, $filters);

        return Inertia::render('Apartments/Index', [
            'apartments' => $apartments,
            'residence' => $residence,
            'filters' => $filters,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Apartment::class);

        $user = Auth::user();

        $data = [];

        if ($user->type === 'Admin') {
            $data['managers'] = $this->apartmentRepository->getManagers();
            $data['residences'] = [];
            $data['buildings'] = [];
        } elseif ($user->type === 'Manager') {
            $data['residences'] = $this->apartmentRepository->getResidences();
            $data['buildings'] = $this->apartmentRepository->getBuildings();
        }

        return Inertia::render('Apartments/Create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreApartmentRequest $request)
    {
        $this->authorize('create', Apartment::class);

        try {
            $data = $request->validated();

            $data['avatar'] = $request->file('avatar');

            $resident = $this->apartmentRepository->create($data);
            return redirect()->route('apartments.create')->with('success', 'Apartamento creado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('apartments.create')->with('error', 'Error al crear el apartamento: ' . $e->getMessage());
        }
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
    public function edit(string $id)
    {
        $apartment = $this->apartmentRepository->findApartment($id);

        $this->authorize('update', $apartment);

        $user = Auth::user();

        $data = [];

        $data['apartment'] = $apartment;

        if ($user->type === 'Admin') {
            $data['managers'] = $this->apartmentRepository->getManagers();
            $data['residences'] = $this->apartmentRepository->getResidences();
            $data['buildings'] = $this->apartmentRepository->getBuildings();
        } elseif ($user->type === 'Manager') {
            $data['residences'] = $this->apartmentRepository->getResidences();
            $data['buildings'] = $this->apartmentRepository->getBuildings();
        }

        return Inertia::render('Apartments/Edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateApartmentRequest $request,  string $id)
    {
        dd('hoola');
        try {
            $apartment = $this->apartmentRepository->findApartment($id);

            $this->authorize('update', $apartment);

            $data = $request->validated();
            $avatarFile = $request->file('avatar');
            $avatarDeleted = $request->input('avatar_deleted', false);

            $this->apartmentRepository->update($apartment, $data, $avatarFile, $avatarDeleted);

            return redirect()->route('apartments.index')->with('success', 'Apartmento actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('apartments.index')->with('error', 'Error al actualizar el apartmento: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $apartment = $this->apartmentRepository->findApartment($id);
        $this->authorize('delete', $apartment);

        try {
            $this->apartmentRepository->delete($id);
            return redirect()->route('apartments.index')->with('success', 'Apartamento eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('apartments.index')->with('error', 'Error al eliminar el apartmento: ' . $e->getMessage());
        }
    }
}
