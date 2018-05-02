<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Custom messages error
     */
    public function messages(){
        return[
            'nom.required' => 'Nom obligatoire',
            'prenom.required' => 'Prénom obligatoire',
            'email.required' => 'Email obligatoire',
            'email.validation' => 'Email non valide',
            'password.required' => 'Mot de passe obligatoire'
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nom' => 'required',
            'prenom' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ];
    }
}
