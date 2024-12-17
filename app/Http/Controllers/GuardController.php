<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGuardRequest;
use App\Http\Requests\UpdateGuardRequest;
use App\Models\Guard;
use App\Repositories\GuardRepository;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class GuardController extends Controller
{
    use AuthorizesRequests;

    protected $guardRepository;

    public function __construct(GuardRepository $guardRepository)
    {
        $this->guardRepository = $guardRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$this->authorize('viewAny', Guard::class);

        $guards = $this->guardRepository->getAll();
        return Inertia::render('Guards/Index', ['guards' => $guards]);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
