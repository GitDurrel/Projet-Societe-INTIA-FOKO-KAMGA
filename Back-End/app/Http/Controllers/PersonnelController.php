<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PersonnelController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/personnels",
     *     tags={"Personnel"},
     *     summary="Lister le personnel",
     *     @OA\Response(response=200, description="Liste du personnel")
     * )
     */
    public function index()
    {
        return User::all();
    }

    /**
     * @OA\Post(
     *     path="/api/personnels",
     *     tags={"Personnel"},
     *     summary="Créer un membre du personnel",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nom","email","mot_de_passe"},
     *             @OA\Property(property="nom", type="string", example="Durand"),
     *             @OA\Property(property="email", type="string", example="durand@email.com"),
     *             @OA\Property(property="mot_de_passe", type="string", example="secret123")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Personnel créé"),
     *     @OA\Response(response=422, description="Erreur de validation")
     * )
     */
    public function store(Request $request)
    {
        $donneesValidees = $request->validate([
            'nom' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'mot_de_passe' => 'required|string|min:6',
        ], [
            'nom.required' => 'Le nom est obligatoire.',
            'email.required' => "L'email est obligatoire.",
            'email.email' => "L'email doit être valide.",
            'email.unique' => "Cet email est déjà utilisé.",
            'mot_de_passe.required' => 'Le mot de passe est obligatoire.',
            'mot_de_passe.min' => 'Le mot de passe doit contenir au moins 6 caractères.'
        ]);
        $donneesValidees['password'] = bcrypt($donneesValidees['mot_de_passe']);
        $donneesValidees['name'] = $donneesValidees['nom'];
        unset($donneesValidees['mot_de_passe'], $donneesValidees['nom']);
        $personnel = User::create($donneesValidees);
        return response()->json($personnel, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/personnels/{id}",
     *     tags={"Personnel"},
     *     summary="Afficher un membre du personnel",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Personnel trouvé"),
     *     @OA\Response(response=404, description="Non trouvé")
     * )
     */
    public function show($identifiant)
    {
        return User::findOrFail($identifiant);
    }

    /**
     * @OA\Put(
     *     path="/api/personnels/{id}",
     *     tags={"Personnel"},
     *     summary="Mettre à jour un membre du personnel",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nom", type="string", example="Durand"),
     *             @OA\Property(property="email", type="string", example="durand@email.com"),
     *             @OA\Property(property="mot_de_passe", type="string", example="secret123")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Personnel mis à jour"),
     *     @OA\Response(response=404, description="Non trouvé")
     * )
     */
    public function update(Request $request, $identifiant)
    {
        $personnel = User::findOrFail($identifiant);
        $donneesValidees = $request->validate([
            'nom' => 'sometimes|string',
            'email' => 'sometimes|email|unique:users,email,' . $identifiant,
            'mot_de_passe' => 'sometimes|string|min:6',
        ]);
        if (isset($donneesValidees['mot_de_passe'])) {
            $donneesValidees['password'] = bcrypt($donneesValidees['mot_de_passe']);
            unset($donneesValidees['mot_de_passe']);
        }
        if (isset($donneesValidees['nom'])) {
            $donneesValidees['name'] = $donneesValidees['nom'];
            unset($donneesValidees['nom']);
        }
        $personnel->update($donneesValidees);
        return $personnel;
    }

    /**
     * @OA\Delete(
     *     path="/api/personnels/{id}",
     *     tags={"Personnel"},
     *     summary="Supprimer un membre du personnel",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Personnel supprimé"),
     *     @OA\Response(response=404, description="Non trouvé")
     * )
     */
    public function destroy($identifiant)
    {
        $personnel = User::findOrFail($identifiant);
        $personnel->delete();
        return response()->json(['message' => 'Personnel supprimé']);
    }
} 