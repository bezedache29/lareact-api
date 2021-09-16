<?php

namespace App\Http\Validation;

class LoginValidation 
{
    // Regles de validation lors de l'inscription d'un user
    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
        ];
    }

    // Les messages d'erreurs custom
    public function messages()
    {
        return [
            'email.required' => 'L\'adresse email est obligatoire',
            'email.email' => 'L\'adresse email doit être une adresse email valide',
            'password.required' => 'Le mot de passe est obligatoire',
        ];
    }
}