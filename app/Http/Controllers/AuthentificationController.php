<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterAuthRequest;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthentificationController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pseudo' => 'required|min:3|max:20',
            'email' => 'required|email|unique:App\Models\User,email',
            'password' => 'required|confirmed|min:6',
        ],
        [
            'pseudo.required' => 'Le pseudo est obligatoire',
            'email.required' => 'Adresse email est obligatoire',
            'password.required' => 'Les mots de passe sont obligatoire',
            'password.confirmed' => 'Les mots de passe ne correspondent pas'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->all());
        }

        $user = User::create([
            'pseudo' => $request->pseudo,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'api_token' => Str::random(60)
        ]);

        return response()->json($user);
    }
}
