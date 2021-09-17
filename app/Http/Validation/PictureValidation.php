<?php

namespace App\Http\Validation;

class PictureValidation 
{
    // Regles de validation lors de l'inscription d'un user
    public function rules()
    {
        return [
            'title' => 'required|string|min:10',
            'description' => 'required|string|max:250',
            'image' => 'required|image'
        ];
    }

    // Les messages d'erreurs custom
    public function messages()
    {
        return [
            'title.required' => 'Le titre est obligatoire',
            'title.min' => 'Le titre est trop court',
            'description.required' => 'La description est obligatoire',
            'description.max' => '250 caratcères maximum',
            'image.required' => 'L\'image est obligatoire',
            'image.image' => 'L\'image n\'est pas au bon format'
        ];
    }
}