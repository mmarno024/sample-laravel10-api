<?php

namespace App\Http\Controllers\API\Auth;

use App\Helpers\Other;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthApiController extends Controller
{

    public function userLogin(Request $request)
    {
        $messages = [
            'userid.required' => 'User ID wajib diisi!',
            'password.required' => 'Password wajib diisi!'
        ];

        $validatedData = $request->validate([
            'userid' => 'required',
            'password' => 'required'
        ], $messages);
        try {
            Log::info('Login attempt', ['userid' => $request->userid]);

            if (!Auth::attempt($validatedData)) {
                app(Other::class)->history("login", "Authenticate@" . __FUNCTION__, "Login", "", "failed");
                return $this->sendError('Unauthorized', 'Gagal masuk, pastikan userid atau password sesuai!', 500);
            }
            $user = User::where('userid', $validatedData['userid'])->first();
            if (!$user || !Hash::check($validatedData['password'], $user->password)) {
                app(Other::class)->history("login", "Authenticate@" . __FUNCTION__, "Login", "", "failed");
                throw new \Exception('Gagal masuk, pastikan userid atau password sesuai!');
            }
            $tokenResult = $user->createToken('authToken')->plainTextToken;
            app(Other::class)->history("login", "Authenticate@" . __FUNCTION__, "Login", "", "success");
            return $this->sendResponse([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ], 'Authenticated');
        } catch (ValidationException $e) {
            return $this->sendResponse([
                'status' => false,
                'errors' => $e->errors()
            ], 'Validation failed', 403);
        } catch (\Exception $e) {
            Log::error('Login error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return $this->sendError(
                [
                    'message' => 'Something went wrong',
                    'error' => $e
                ],
                'Login Failed',
                403
            );
        }
    }

    public function logout(Request $request)
    {
        $user = User::find(Auth::user()->id);
        app(Other::class)->history("logout", "Authenticate@" . __FUNCTION__, "Logout", "", "success");
        $user->tokens()->delete();
        return response()->noContent();
    }
}
