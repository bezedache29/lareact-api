<?php

namespace App\Http\Validation;

class RegisterValidation 
{
    // Regles de validation lors de l'inscription d'un user
    public function rules()
    {
        return [
            'pseudo' => 'required|min:3|max:20',
            'email' => 'required|email|unique:App\Models\User,email',
            'password' => 'required|confirmed|min:6',
        ];
    }

    // Les messages d'erreurs custom
    public function messages()
    {
        return [
            'pseudo.required' => 'Le pseudo est obligatoire',
            'email.required' => 'L\'adresse email est obligatoire',
            'email.unique' => 'Cette adresse email est déjà utilisée',
            'password.required' => 'Les mots de passe sont obligatoire',
            'password.confirmed' => 'Les mots de passe ne correspondent pas'
        ];
    }
}