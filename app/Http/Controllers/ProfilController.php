<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreProfilRequest;
use App\Http\Requests\UpdateProfilRequest;
use App\Models\Profil;

class ProfilController extends Controller
{
    // Afficher les détails d'un profil spécifique
    public function index()
    {
        // Récupérer le profil par son ID 
        $profils = Profil::where('statut', 'actif')->get(['id', 'prenom', 'nom', 'image', 'created_at', 'updated_at']);

        // Retourner la réponse JSON avec les informations du profil
        return response()->json($profils);
    }

    // Créer un nouveau profil
    public function store(StoreProfilRequest $request)
    {

        // Gestion de l'image
        $imagePath = $request->file('image')->store('images', 'public');

        // Création du profil
        $profils = Profil::create([
            'prenom' => $request->prenom,
            'nom' => $request->nom,
            'image' => $imagePath,
            'statut' => $request->statut,
        ]);

        // Retourner la réponse JSON avec les informations du nouveau profil
        return response()->json($profils, 201);
    }

    // Mettre à jour un profil existant sans image
    /**
     * PHP ne parvient pas à gérer les PUT avec des données de formulaire (form data). 
     * J'ai donc décidé d'implémenter les deux options : 
     * - conserver le PUT mais sans l'envoi d'images (donc pas besoin d'utiliser form data).
     * - utiliser un POST pour les mises à jour, ce qui me permettra d'utiliser form data.
     */
    public function update(UpdateProfilRequest $request, $id)
    {
        // Trouver le profil par son ID
        $profils = Profil::findOrFail($id);

        // Mise à jour des autres champs
        $profils->update($request->only('prenom', 'nom', 'statut'));

        // Retourner la réponse JSON avec les informations mises à jour du profil
        return response()->json($profils);
    }

    // Mettre à jour un profil existant avec image
    public function updateWithPost(UpdateProfilRequest $request, $id)
    {
        // Trouver le profil par son ID
        $profil = Profil::findOrFail($id);

        // Gestion du téléchargement de l'image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            Storage::disk('public')->delete($profil->image);

            // Stocker la nouvelle image
            $imagePath = $request->file('image')->store('images', 'public');
            $profil->image = $imagePath;
        }

        // Mise à jour des autres champs
        $profil->update($request->only('nom', 'prenom', 'statut'));

        // Retourner la réponse JSON avec les informations mises à jour du profil
        return response()->json($profil);
    }

    // Supprimer un profil
    public function destroy($id)
    {
        // Trouver le profil par son ID
        $profils = Profil::findOrFail($id);

        // Supprimer l'image associée si elle existe
        Storage::disk('public')->delete($profils->image);

        // Supprimer le profil
        $profils->delete();

        // Retourner une réponse JSON avec un message de succès
        return response()->json(['message' => 'profil supprimé']);
    }
}