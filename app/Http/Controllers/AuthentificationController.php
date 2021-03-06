<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Validation\LoginValidation;
use App\Http\Validation\RegisterValidation;

class AuthentificationController extends Controller
{
    // Pour enregistrer un user
    public function register(Request $request, RegisterValidation $validation)
    {
        // On passe le tableau des requetes du formulaire + le tableau des règles de validation + le tableau des messages d'erreur custom
        $data = Validator::make($request->all(), $validation->rules(), $validation->messages());

        if ($data->fails()) {
            return response()->json(['errors' => $data->errors()], 401);
        }

        $user = User::create([
            'pseudo' => $request->pseudo,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'api_token' => Str::random(60)
        ]);

        return response()->json($user);
    }

    // Pour connecter un user
    public function login(Request $request, LoginValidation $validation)
    {
        $data = Validator::make($request->all(), $validation->rules(), $validation->messages());

        if ($data->fails()) {
            return response()->json(['errors' => $data->errors()], 401);
        }

        // Permet de check l'email et le password avec hashage du user en DB est valide
        if (Auth::attempt(['email' => $request->email,'password' => $request->password])) {

            $user = User::where('email', $request->email)->firstOrFail();

            return response()->json($user);

        } else {
            return response()->json([
                'errors' => [
                    'bad_credentials' => 'Email et/ou Mot de passe invalide'
                ]
            ], 401);
        }
    }
}
