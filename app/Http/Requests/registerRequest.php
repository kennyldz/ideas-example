<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;


class registerRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "name"      => 'required|min:1|max:250',
            "email"     => 'required|email:rfc,strict,dns,spoof,filter|unique:customers,email|max:250',
            "password"  => 'required|min:8|string|',
            "since"     => 'nullable|date',
            "revenue"   => 'nullable|numeric'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status'          => false,
            'error_message'   => 'Validation errors',
            'required'  => $validator->errors()
        ]));
    }
}
