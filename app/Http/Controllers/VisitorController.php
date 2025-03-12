<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\StoreVisitorRequest;
use App\Http\Requests\UpdateVisitorRequest;
use App\Repositories\VisitorRepository;
use App\Models\Residence;
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

    public function create() {
        return Inertia::render('Visitors/Create');
    }

    public function edit($id) {
        $visitor = Visitor::findOrFail($id);
        if (!$visitor) return back();
        return Inertia::render("Visitors/Create", ["visitor" => $visitor]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Visitor::class);

        $filters = $request->only(['name', 'document', 'apartment', 'resident_name',]);

        $perPage = $request->input('per_page', 5);

        $page = $request->input('page', 1);

        $visitors = $this->visitorRepository->getAll($perPage, $page, $filters);

        return Inertia::render('Visitors/Index', [
            'visitors' => $visitors,
            'filters' => $filters
        ]);
    }


    /**
     * Mostrar los vigilantes filtrados por residencia.
     */
    public function indexByResidence(Request $request, $residenceId)
    {
        //$residence = Residence::with('apartments.visitors')->findOrFail($residenceId);
        $residence = Residence::with('visitors.apartment.resident.profile')->find($residenceId);
        //dd($residence->visitors);


        $this->authorize('viewByResidence', $residence);

        $filters = $request->only(['name', 'document', 'apartment', 'resident_name',]);

        $perPage = $request->input('per_page', 5);

        $page = $request->input('page', 1);

        $visitors = $this->visitorRepository->getByResidence($residence, $perPage, $page, $filters);

        return Inertia::render('Visitors/Index', [
            'visitors' => $visitors,
            'residence' => $residence,
            'filters' => $filters
        ]);
    }

    public function store(StoreVisitorRequest $request) {
        $this->authorize("create", Visitor::class);
        try {
            $payload = $request->validated();
            $id = $request->id;
            $visitor = Visitor::firstOrNew(["id" => $id]);
            $visitor->fill($payload);
            $visitor->save();
            return redirect()->route("visitors.create")
                ->with('success', 'Visitante '. $id ? 'actualizado' : 'creado' .' exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('visitors.create')
                ->with('error', 'Error al crear visitante: ' . $e->getMessage());
        }
    }

    public function destroy($id) {
        $visitor = Visitor::findOrFail($id);
        if(!$visitor) return redirect()->route("visitors.index")
            ->with("error", "El visitante no existe");
        Visitor::destroy($id);
        return redirect()->route("visitors.index")
            ->with("success", "Se elimino el visitante exitosamente.");
    }

    public function findAll(Request $request) {
        $apartment_id = $request->apartment_id;
        $data = Visitor::where("apartment_id", $apartment_id)->get();
        return response()->json($data);
    }
}
