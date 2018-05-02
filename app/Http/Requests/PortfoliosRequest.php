<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PortfoliosRequest extends FormRequest
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
            'titre.required' => 'titre obligatoire',
            'description.required' => 'description obligatoire',
            'role.required' => 'role obligatoire',
            'date_debut.required' => 'date de debut obligatoire',
            'date_fin.required' => 'date de fin obligatoire'
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
            'user_id' => 'required',
            'titre' => 'required',
            'description' => 'required',
            'role' => 'required',
            'client' => 'required',
            'date_debut' => 'required',
            'date_fin' => 'required'
        ];
    }
}
