<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    /**
     * Display a listing of managers.
     */
    public function index()
    {
        $managers = User::byType('Manager')->paginate(10); // Solo managers
        return response()->json($managers);
    }

    /**
     * Show the details of a specific manager.
     */
    public function show($id)
    {
        $manager = User::byType('Manager')->findOrFail($id);
        return response()->json($manager);
    }
}
