<?php

namespace App\Http\Controllers;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Repositories\VisitRepository;
use App\Models\Residence;
use App\Models\Visit;
use Inertia\Inertia;
use DB;

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
            //'visits' => $visits,
            //'filters' => $filters,
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

    public function findAll(Request $request) {
        try {
            $filters = $request->only([
                'visitor_name',
                'resident_name',
                'apartment',
                'visit_date',
                'expiration_date',
                'normalTab',
                'visitType'
            ]);
            $data = $this->visitRepository->getAll(
                $request->input('per_page', 5),
                $request->input('page', 1),
                $filters
            );
            return response()->json(
                $data
            );
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request) {
        return Inertia::render("Visits/Form");
    }

    public function edit(Request $request) {
        $id = $request->id;
        $visit = Visit::select(
            "*",
            DB::raw("DATE_FORMAT(visit_date, '%Y-%m-%d') as visit_date"),
            DB::raw("DATE_FORMAT(expiration_date, '%Y-%m-%d') as expiration_date"),
            DB::raw("DATE_FORMAT(entry_time, '%H:%i') as entry_time")
        )->findOrFail($id);
        if(!$visit) return back();
        return Inertia::render("Visits/Form", ['visit' => $visit]);
    }

    public function store(Request $request) {
        $id = $request->id;
        try {
            DB::beginTransaction();
            $payload = $request->all();
            $visit = Visit::firstOrNew(["id" => $id]);
            $visit->fill($payload);
            $visit->qr = "temp";
            $visit->save();

            $qrCode = uniqid('visit_', true);
            $qrImagePath = "qr_images/{$qrCode}.png";

            // Generar código QR y guardarlo
            QrCode::format('png')
                ->size(200)
                ->generate(json_encode([
                        "visit_id" => $visit->id,
                    ]), storage_path("app/public/{$qrImagePath}")
                );
            $visit->qr = $qrImagePath;

            $visit->save();
            DB::commit();
            return redirect()->route($id ? "visits.edit" : "visits.create", $id)
                ->with("success", "Visita ". $id ? "creada " : "actualizada ". "exitosamente.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route($id ? "visits.edit" : "visits.create", $id)
                ->with("error", "Error al crear visita: " . $e->getMessage());
        }
    }

    public function destroy($id) {
        $visitor = Visit::findOrFail($id);
        if(!$visitor) return redirect()->route("visits.index")
            ->with("error", "El visitante no existe");
        Visit::destroy($id);
        return redirect()->route("visits.index")
            ->with("success", "Se elimino la visita exitosamente.");
    }
}
