<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): Response
    {
        // $request->validate([
        //     'name' => ['required', 'string', 'max:255'],
        //     'email' => ['required', 'email', 'max:255', 'unique:' . User::class],
        //     // 'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
        //     'gender' => ['required'],
        //     'address' => ['required'],
        //     // 'password' => ['required', 'confirmed', Rules\Password::defaults()],
        //     'password' => ['required', Rules\Password::min(8)->mixedCase()->numbers()->symbols()],
        // ]);

        $messages = [
            'name.required' => 'Kolom nama harus diisi.',
            'name.string' => 'Kolom nama tidak boleh berupa angka.',
            'name.max' => 'Panjang maksimal 255 karakter.',
            'email.required' => 'Kolom email harus diisi.',
            'email.email' => 'Format email tidak sesuai.',
            'email.max' => 'Panjang maksimal 255 karakter.',
            'email.unique' => 'Email sudah pernah dipakai.',
            'gender.required' => 'Kolom jenis kelamin harus diisi.',
            'address.required' => 'Kolom alamat harus diisi.',
            'password.required' => 'Kolom kata sandi harus diisi.',
            'password.min' => 'Panjang minimal 8 karakter.',
            'password.regex' => 'Kombinasi kata sandi harus ada huruf besar dan kecil, angka dan simbol.',
            // 'password.letters' => 'Harus ada kombinasi huruf.',
            // 'password.mixedCase' => 'Harus ada kombinasi huruf besar dan kecil.',
            // 'password.numbers' => 'Harus ada kombinasi angka.',
            // 'password.symbols' => 'Harus ada kombinasi simbol.',
            'password_confirmation.required' => 'Kolom konfirmasi kata sandi harus diisi.',
            'password_confirmation.same' => 'Konfirmasi kata sandi tidak sama.',
        ];

        $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:' . User::class],
                'gender' => ['required'],
                'address' => ['required'],
                'password' => ['required', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]+$/'],
                // 'password' => ['required', Rules\Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
                'password_confirmation' => ['required', 'same:password'],
            ],
            $messages
        );

        $user = User::create([
            'userid' => $request->email,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'gender' => $request->gender,
            'address' => $request->address,
            'roleid' => 'USER',
        ]);

        event(new Registered($user));

        // Auth::login($user);

        return response()->noContent();
    }
}
