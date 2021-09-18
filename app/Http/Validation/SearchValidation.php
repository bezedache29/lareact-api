<?php

namespace App\Http\Validation;

class SearchValidation 
{
    // Regles de validation lors de l'inscription d'un user
    public function rules()
    {
        return [
            'search' => 'max:20|string|nullable',
        ];
    }

    // Les messages d'erreurs custom
    public function messages()
    {
        return [
            'search.max' => 'La recherche est trop grande'
        ];
    }
}