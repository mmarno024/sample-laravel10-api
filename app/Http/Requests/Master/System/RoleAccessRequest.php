<?php

namespace App\Http\Requests\Master\System;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleAccessRequest extends FormRequest
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
            'roleid' => ['required'],
            'rolename' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'roleid.required' => 'Kolom id harus diisi.',
            'rolename.required' => 'Kolom nama harus diisi.',
        ];
    }
}
