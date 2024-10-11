<?php

namespace App\Http\Requests\Master\System;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MenuRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'groupid' => ['required'],
            'menuname' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'groupid.required' => 'Kolom kelompok harus diisi.',
            'menuname.required' => 'Kolom nama harus diisi.',
        ];
    }
}
