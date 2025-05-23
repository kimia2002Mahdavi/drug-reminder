<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MedicationRequest;
use App\Models\Medication;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class MedicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $user = Auth::user();
        $medications = $user->medications;
        return response()->json($medications);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MedicationRequest $request): JsonResponse
    {
        $user = Auth::user();
        $medication = $user->medications()->create($request->validated());
        return response()->json($medication, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Medication $medication): JsonResponse
    {
        if ($medication->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        return response()->json($medication);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MedicationRequest $request, Medication $medication): JsonResponse
    {
        if ($medication->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $medication->update($request->validated());

        return response()->json($medication);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Medication $medication): JsonResponse
    {
        if ($medication->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $medication->delete();

        return response()->json(['message' => 'Medication deleted successfully']);
    }
}