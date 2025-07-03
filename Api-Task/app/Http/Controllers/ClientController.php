<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Http\Response;

/**
 * @OA\Get(
 *     path="/api/clients",
 *     summary="Liste des clients",
 *     @OA\Response(response=200, description="Succès")
 * )
 */
class ClientController extends Controller
{
    public function index()
    {
        return Client::all();
    }

    /**
     * @OA\Post(
     *     path="/api/clients",
     *     summary="Créer un client",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nom", "prenom", "email"},
     *             @OA\Property(property="nom", type="string", example="Dupont"),
     *             @OA\Property(property="prenom", type="string", example="Jean"),
     *             @OA\Property(property="email", type="string", example="jean.dupont@email.com"),
     *             @OA\Property(property="telephone", type="string", example="0601020304"),
     *             @OA\Property(property="adresse", type="string", example="123 rue de Paris")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Créé")
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
        ]);
        $client = Client::create($validated);
        return response()->json($client, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/clients/{id}",
     *     summary="Afficher un client",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Succès")
     * )
     */
    public function show(string $id)
    {
        $client = Client::findOrFail($id);
        return $client;
    }

    /**
     * @OA\Put(
     *     path="/api/clients/{id}",
     *     summary="Mettre à jour un client",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nom", type="string", example="Durand"),
     *             @OA\Property(property="prenom", type="string", example="Marie"),
     *             @OA\Property(property="email", type="string", example="marie.durand@email.com"),
     *             @OA\Property(property="telephone", type="string", example="0605060708"),
     *             @OA\Property(property="adresse", type="string", example="456 avenue de Lyon")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Mis à jour")
     * )
     */
    public function update(Request $request, string $id)
    {
        $client = Client::findOrFail($id);
        $validated = $request->validate([
            'nom' => 'sometimes|required|string|max:255',
            'prenom' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:clients,email,' . $id,
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
        ]);
        $client->update($validated);
        return response()->json($client);
    }

    /**
     * @OA\Delete(
     *     path="/api/clients/{id}",
     *     summary="Supprimer un client",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Supprimé")
     * )
     */
    public function destroy(string $id)
    {
        $client = Client::findOrFail($id);
        $client->delete();
        return response()->json(null, 204);
    }
} 