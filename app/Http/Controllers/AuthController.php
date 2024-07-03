<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\Administrateur;
use App\Http\Requests\RegisterAdministrateurRequest;

class AuthController extends Controller
{
    // Inscription d'un nouvel administrateur
    public function register(RegisterAdministrateurRequest $request)
    {
        // Création de l'administrateur
        $admin = Administrateur::create([
            'nom' => $request->nom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        
        return response()->json($admin, 201);
    }

    // Connexion d'un administrateur
    public function login(Request $request)
    {
        // Recherche de l'administrateur par email
        $admin = Administrateur::where('email', $request->email)->first();

        // Vérification du mot de passe
        if (!$admin || !Hash::check($request->password, $admin->password)) {
            throw ValidationException::withMessages([
                'email' => ['Les informations d\'identification fournies sont incorrectes.'],
            ]);
        }

        // Création du token d'authentification
        $token = $admin->createToken('auth_token')->plainTextToken;

        return response()->json(['access_token' => $token, 'token_type' => 'Bearer']);
    }

    // Déconnexion d'un administrateur
    public function logout(Request $request)
    {
        // Suppression des tokens d'authentification de l'utilisateur connecté
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Déconnexion réussie']);
    }
}