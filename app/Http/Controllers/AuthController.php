<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\RegisterAdministrateurRequest;
use App\Models\Administrateur;
use App\DTO\Auth\RegisterDTO;
use App\DTO\Auth\LoginDTO;

class AuthController extends Controller
{
    // Inscription d'un nouvel administrateur
    public function register(RegisterAdministrateurRequest $request) : JsonResponse
    {
        $data = new RegisterDTO($request->all());
        // Création de l'administrateur
        $admin = Administrateur::create([
            'nom' => $data->nom,
            'email' => $data->email,
            'password' => Hash::make($data->password),
        ]);
        
        return response()->json($admin, 201);
    }

    // Connexion d'un administrateur
    public function login(Request $request) : JsonResponse
    {
        $data = new LoginDTO($request->all());
        // Recherche de l'administrateur par email
        $admin = Administrateur::where('email', $data->email)->first();

        // Vérification du mot de passe
        if (!$admin || !Hash::check($data->password, $admin->password)) {
            throw ValidationException::withMessages([
                'email' => ['Les informations d\'identification fournies sont incorrectes.'],
            ]);
        }

        // Création du token d'authentification
        $token = $admin->createToken('auth_token')->plainTextToken;

        return response()->json(['access_token' => $token, 'token_type' => 'Bearer']);
    }

    // Déconnexion d'un administrateur
    public function logout(Request $request) : JsonResponse
    {
        // Suppression des tokens d'authentification de l'utilisateur connecté
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Déconnexion réussie']);
    }
}