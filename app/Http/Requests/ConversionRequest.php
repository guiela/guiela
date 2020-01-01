<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * ConversionRequest class
 * 
 * @author Anitche Chisom <anitchec.dev@gmail.com>
 */
class ConversionRequest extends FormRequest
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
            'file'      => ['required', 'mimes:csv,txt'],
            'delimiter' => ['required', Rule::in([';', ',', '|'])]
        ];
    }
}
