<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assurance;
use Illuminate\Http\Response;

/**
 * @OA\Get(
 *     path="/api/assurances",
 *     summary="Liste des assurances",
 *     @OA\Parameter(name="type", in="query", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="client_id", in="query", required=false, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Succès")
 * )
 */
class AssuranceController extends Controller
{
    public function index(Request $request)
    {
        $query = Assurance::query();
        if ($request->has('type')) {
            $query->where('type', 'like', '%' . $request->type . '%');
        }
        if ($request->has('client_id')) {
            $query->where('client_id', $request->client_id);
        }
        return $query->get();
    }

    /**
     * @OA\Post(
     *     path="/api/assurances",
     *     summary="Créer une assurance",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"type", "numero_police", "date_debut", "date_fin", "montant", "client_id"},
     *             @OA\Property(property="type", type="string", example="Auto"),
     *             @OA\Property(property="numero_police", type="string", example="POL123456"),
     *             @OA\Property(property="date_debut", type="string", format="date", example="2024-06-01"),
     *             @OA\Property(property="date_fin", type="string", format="date", example="2025-06-01"),
     *             @OA\Property(property="montant", type="number", format="float", example=1200.50),
     *             @OA\Property(property="client_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Créée")
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'numero_police' => 'required|string|max:255|unique:assurances,numero_police',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'montant' => 'required|numeric|min:0',
            'client_id' => 'required|exists:clients,id',
        ]);
        $assurance = Assurance::create($validated);
        return response()->json($assurance, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/assurances/{id}",
     *     summary="Afficher une assurance",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Succès")
     * )
     */
    public function show(string $id)
    {
        $assurance = Assurance::findOrFail($id);
        return $assurance;
    }

    /**
     * @OA\Put(
     *     path="/api/assurances/{id}",
     *     summary="Mettre à jour une assurance",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="type", type="string", example="Habitation"),
     *             @OA\Property(property="numero_police", type="string", example="POL654321"),
     *             @OA\Property(property="date_debut", type="string", format="date", example="2024-07-01"),
     *             @OA\Property(property="date_fin", type="string", format="date", example="2025-07-01"),
     *             @OA\Property(property="montant", type="number", format="float", example=900.00),
     *             @OA\Property(property="client_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Mise à jour")
     * )
     */
    public function update(Request $request, string $id)
    {
        $assurance = Assurance::findOrFail($id);
        $validated = $request->validate([
            'type' => 'sometimes|required|string|max:255',
            'numero_police' => 'sometimes|required|string|max:255|unique:assurances,numero_police,' . $id,
            'date_debut' => 'sometimes|required|date',
            'date_fin' => 'sometimes|required|date|after_or_equal:date_debut',
            'montant' => 'sometimes|required|numeric|min:0',
            'client_id' => 'sometimes|required|exists:clients,id',
        ]);
        $assurance->update($validated);
        return response()->json($assurance);
    }

    /**
     * @OA\Delete(
     *     path="/api/assurances/{id}",
     *     summary="Supprimer une assurance",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Supprimée")
     * )
     */
    public function destroy(string $id)
    {
        $assurance = Assurance::findOrFail($id);
        $assurance->delete();
        return response()->json(null, 204);
    }
} 