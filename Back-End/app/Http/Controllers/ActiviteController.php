<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ActiviteController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/activites",
     *     tags={"Activités"},
     *     summary="Lister les activités",
     *     @OA\Response(response=200, description="Liste des activités")
     * )
     */
    // Lister toutes les activités
    public function index()
    {
        return Activity::with('personnel')->get();
    }

    /**
     * @OA\Post(
     *     path="/api/activites",
     *     tags={"Activités"},
     *     summary="Créer une activité",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"personnel_id","titre","jour_semaine","heure_debut","heure_fin"},
     *             @OA\Property(property="personnel_id", type="integer", example=1),
     *             @OA\Property(property="titre", type="string", example="Réunion"),
     *             @OA\Property(property="jour_semaine", type="string", example="Lundi"),
     *             @OA\Property(property="heure_debut", type="string", example="08:00"),
     *             @OA\Property(property="heure_fin", type="string", example="09:00")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Activité créée"),
     *     @OA\Response(response=422, description="Erreur de validation")
     * )
     */
    // Enregistrer une nouvelle activité
    public function store(Request $request)
    {
        $messages = [
            'personnel_id.exists' => "Le personnel avec cet identifiant n'existe pas."
        ];

        $validated = $request->validate([
            'personnel_id' => 'required|exists:users,id',
            'titre' => 'required|string',
            'jour_semaine' => 'required|string',
            'heure_debut' => 'required',
            'heure_fin' => 'required',
        ], $messages);

        $activite = Activity::create($validated);
        return response()->json($activite, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/activites/{id}",
     *     tags={"Activités"},
     *     summary="Afficher une activité",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Activité trouvée"),
     *     @OA\Response(response=404, description="Non trouvée")
     * )
     */
    // Afficher une activité spécifique
    public function show($id)
    {
        $activite = Activity::with('personnel')->findOrFail($id);
        return $activite;
    }

    /**
     * @OA\Put(
     *     path="/api/activites/{id}",
     *     tags={"Activités"},
     *     summary="Mettre à jour une activité",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="personnel_id", type="integer", example=1),
     *             @OA\Property(property="titre", type="string", example="Réunion"),
     *             @OA\Property(property="jour_semaine", type="string", example="Lundi"),
     *             @OA\Property(property="heure_debut", type="string", example="08:00"),
     *             @OA\Property(property="heure_fin", type="string", example="09:00")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Activité mise à jour"),
     *     @OA\Response(response=404, description="Non trouvée")
     * )
     */
    // Modifier une activité
    public function update(Request $request, $id)
    {
        $activite = Activity::findOrFail($id);
        $validated = $request->validate([
            'personnel_id' => 'sometimes|exists:users,id',
            'titre' => 'sometimes|string',
            'jour_semaine' => 'sometimes|string',
            'heure_debut' => 'sometimes',
            'heure_fin' => 'sometimes',
        ]);
        $activite->update($validated);
        return $activite;
    }

    /**
     * @OA\Delete(
     *     path="/api/activites/{id}",
     *     tags={"Activités"},
     *     summary="Supprimer une activité",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Activité supprimée"),
     *     @OA\Response(response=404, description="Non trouvée")
     * )
     */
    // Supprimer une activité
    public function destroy($id)
    {
        $activite = Activity::findOrFail($id);
        $activite->delete();
        return response()->json(['message' => 'Activité supprimée']);
    }
} 