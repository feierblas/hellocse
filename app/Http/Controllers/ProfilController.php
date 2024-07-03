<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreProfilRequest;
use App\Http\Requests\UpdateProfilRequest;
use App\Models\Profil;
use App\DTO\Profile\CreateProfileDto;
use App\DTO\Profile\UpdateProfileDto;

class ProfilController extends Controller
{
    // Afficher les détails d'un profil spécifique
    public function index() : JsonResponse
    {
        // Récupérer le profil par son ID 
        $profiles = Profil::where('statut', 'actif')->get(['id', 'prenom', 'nom', 'image', 'created_at', 'updated_at']);

        // Retourner la réponse JSON avec les informations du profil
        return response()->json($profiles);
    }

    // Créer un nouveau profil
    public function store(StoreProfilRequest $request) : JsonResponse
    {
        $data = new CreateProfileDto($request->all());

        // Gestion de l'image
        $imagePath = $request->file('image')->store('images', 'public');

        // Création du profil
        $profile = Profil::create([
            'prenom' => $data->prenom,
            'nom' => $data->nom,
            'image' => $imagePath,
            'statut' => $data->statut,
        ]);

        // Retourner la réponse JSON avec les informations du nouveau profil
        return response()->json($profile, 201);
    }

    // Mettre à jour un profil existant sans image
    /**
     * PHP ne parvient pas à gérer les PUT avec des données de formulaire (form data). 
     * J'ai donc décidé d'implémenter les deux options : 
     * - conserver le PUT mais sans l'envoi d'images (donc pas besoin d'utiliser form data).
     * - utiliser un POST pour les mises à jour, ce qui me permettra d'utiliser form data.
     */
    public function update(UpdateProfilRequest $request, int $id) : JsonResponse
    {
        $data = new UpdateProfileDto($request->all());
        // Trouver le profil par son ID
        $profile = Profil::findOrFail($id);

        // Mise à jour des autres champs
        $profile->update($data->only('prenom', 'nom', 'statut'));

        // Retourner la réponse JSON avec les informations mises à jour du profil
        return response()->json($profile);
    }

    // Mettre à jour un profil existant avec image
    public function updateWithPost(UpdateProfilRequest $request, int $id) : JsonResponse
    {
        $data = new UpdateProfileDto($request->all());
        // Trouver le profil par son ID
        $profile = Profil::findOrFail($id);

        // Gestion du téléchargement de l'image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            Storage::disk('public')->delete($profile->image);

            // Stocker la nouvelle image
            $imagePath = $request->file('image')->store('images', 'public');
            $profile->image = $imagePath;
        }

        // Mise à jour des autres champs
        $profile->update($data->only('nom', 'prenom', 'statut'));

        // Retourner la réponse JSON avec les informations mises à jour du profil
        return response()->json($profile);
    }

    // Supprimer un profil
    public function destroy(int $id) : JsonResponse
    {
        // Trouver le profil par son ID
        $profile = Profil::findOrFail($id);

        // Supprimer l'image associée si elle existe
        Storage::disk('public')->delete($profile->image);

        // Supprimer le profil
        $profile->delete();

        // Retourner une réponse JSON avec un message de succès
        return response()->json(['message' => 'profil supprimé']);
    }
}