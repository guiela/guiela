<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * CompareRequest class
 * 
 * @author Anitche Chisom <anitchec.dev@gmail.com>
 */
class CompareRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'identifiers'   => ['required', 'array'],
            'pairs'         => ['sometimes', 'array'],
            'domestic'      => ['required', 'array'],
            'foreign'       => ['required', 'array'],
            'matcher'       => ['required', 'string'],
        ];
    }
}
