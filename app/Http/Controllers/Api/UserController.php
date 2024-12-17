<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * List users by type (e.g., Manager, Admin).
     */
    public function index(Request $request): JsonResponse
    {
        $type = $request->query('type', 'Manager');
        $users = User::byType($type)->paginate(10);

        return response()->json($users);
    }

    /**
     * Show user details by ID.
     */
    public function show($id): JsonResponse
    {
        $user = User::findOrFail($id);

        return response()->json($user);
    }
}
