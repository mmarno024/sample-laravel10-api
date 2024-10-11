<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
    public function rules(Request $request): array
    {
        return [
            'userid' => ['required', Rule::unique('users')->ignore($this->user)],
            'name' => ['required'],
            'email' => ['email'],
            'address' => ['required'],
            'photo' => ['mimes:png,jpg,jpeg', 'max:2048'],
            'roleid' => ['required'],
            'compid' => ['required_if:roleid,PERUSAHAAN']
        ];
    }

    public function messages()
    {
        return [
            'userid.required' => 'Kolom id pengguna harus diisi.',
            'userid.unique' => 'Id pengguna sudah digunakan pengguna lain.',
            'name.required' => 'Kolom nama harus diisi.',
            'email.email' => 'Format email tidak sesuai.',
            'email.unique' => 'Email sudah digunakan pengguna lain.',
            'address.required' => 'Kolom alamat harus diisi.',
            'photo.mimes' => 'Format/extensi foto tidak sesuai.',
            'photo.max' => 'Ukuran foto terlalu besar.',
            'roleid.required' => 'Kolom peran pengguna harus diisi.',
            'compid.required_if' => 'Kolom perusahaan harus diisi.',
        ];
    }
}
