<?php
/**
 * Created by PhpStorm.
 * User: bilel
 * Date: 02/03/2019
 * Time: 00:46
 */

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class BlogRequest extends FormRequest
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
            'user_id.required' => 'user_id obligatoire',
            'titre.required' => 'titre obligatoire',
            'introduction.required' => 'introduction obligatoire',
            'description.required' => 'description obligatoire'
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
            'langue_id' => '',
            'titre' => 'required',
            'introduction' => '',
            'description' => 'required',
        ];
    }
}