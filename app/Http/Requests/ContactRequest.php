<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
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
            'email.required' => 'Email obligatoire',
            'email.validation' => 'Email non valide',
            'sujet.required' => 'Le sujet est obligatoire',
            'text.required' => 'Le text est obligatoire'
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
            'email' => 'required|email',
            'sujet' => 'required',
            'text' => 'required'
        ];
    }
}
