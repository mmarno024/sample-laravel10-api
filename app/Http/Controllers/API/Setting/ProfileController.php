<?php

namespace App\Http\Controllers\API\Setting;

use App\Helpers\Other;
use App\Http\Controllers\Controller;
use App\Http\Resources\Etc\SystemHistoryResource;
use App\Http\Resources\Setting\ProfileResource;
use App\Models\Etc\SystemHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        if (!app(Other::class)->allowaccess('SETTING_PROFILE_R')) {
            app(Other::class)->history("profile", "ProfileController@" . __FUNCTION__, "Lihat Profil", "read", "failed");
            return response()->json(app(Other::class)->reason(), 403);
        }
        $result = new ProfileResource(User::where('userid', Auth::user()->userid)->first());
        app(Other::class)->history("profile", "ProfileController@" . __FUNCTION__, "Lihat Profil", "read", "success");
        return $this->sendResponse($result, 'Successfully', 200);
    }

    public function update(Request $request)
    {
        if (!app(Other::class)->allowaccess('SETTING_PROFILE_U')) {
            app(Other::class)->history("profile", "ProfileController@" . __FUNCTION__, "Ubah Profil", "update", "failed");
            return response()->json(app(Other::class)->reason(), 403);
        }
        if ($request->type == 'photo') {
            $messages = [
                'photo.required' => 'Pilih foto terlebih dahulu.',
                'photo.mimes' => 'Format foto tidak sesuai.',
                'photo.max' => 'Ukuran foto terlalu besar.',
            ];

            $request->validate(
                [
                    'photo' => ['required', 'mimes:png,jpg,jpeg', 'max:2048'],
                ],
                $messages
            );
            if ($request->photo != null) {
                $fileName = date('YmdHi') . '_' . Auth::user()->userid . '.' . $request->photo->extension();
                $path = 'uploads/users/' . Auth::user()->userid;
                $request->photo->move(public_path($path), $fileName);
                $data['photo'] = $fileName;
                $data['photo_path'] = $path . '/' . $fileName;

                $user = user::find($request->id);
                if ($user->id == Auth::user()->id) {
                    app(Other::class)->history("profile", "ProfileController@" . __FUNCTION__, "Ubah Foto Profil", "update", "success");
                    User::where('id', $request->id)->update([
                        'photo' => $data['photo'],
                        'photo_path' => $data['photo_path'],
                    ]);
                }
            }
        } else if ($request->type == 'password') {
            $currentPasswordStatus = Hash::check($request->current_password, auth()->user()->password);
            if ($currentPasswordStatus) {
                $messages = [
                    'current_password.required' => 'Kolom kata sandi saat ini harus diisi.',
                    'password_new.required' => 'Kolom kata sandi harus diisi.',
                    'password_new.min' => 'Panjang minimal 8 karakter.',
                    'password_new.regex' => 'Kombinasi kata sandi harus ada huruf besar dan kecil, angka dan simbol.',
                    'password_new_confirm.required' => 'Kolom konfirmasi kata sandi harus diisi.',
                    'password_new_confirm.same' => 'Konfirmasi kata sandi tidak sama.',
                ];

                $request->validate(
                    [
                        'current_password' => ['required'],
                        'password_new' => ['required', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]+$/'],
                        'password_new_confirm' => ['required', 'same:password_new']
                    ],
                    $messages
                );

                $user = user::find($request->id);

                if ($user->id == Auth::user()->id) {
                    app(Other::class)->history("profile", "ProfileController@" . __FUNCTION__, "Ubah Kata Sandi", "update", "success");
                    User::where('id', $request->id)->update([
                        'password' => Hash::make($request->password_new),
                    ]);
                }
                return new ProfileResource($user);
            } else {
                $messages = ['errors' => ['current_password' => ["Kata sandi saat ini tidak sesuai."]], 'message' => "Kata sandi saat ini tidak sesuai."];
                return response()->json($messages, 422);
            }
        }
    }

    public function profileHistory(Request $request)
    {
        if (!app(Other::class)->allowaccess('SETTING_PROFILE_R')) {
            app(Other::class)->history("profile", "ProfileController@" . __FUNCTION__, "Lihat Profil Riwayat", "read", "failed");
            return response()->json(app(Other::class)->reason(), 403);
        }
        $history = SystemHistory::when($request->search, function ($query, $search) {
            $query->where('userid', 'like', '%' . $search . '%')
                ->orWhere('route', 'like', '%' . $search . '%')
                ->orWhere('item', 'like', '%' . $search . '%')
                ->orWhere('activity', 'like', '%' . $search . '%')
                ->orWhere('tag', 'like', '%' . $search . '%')
                ->orWhere('status', 'like', '%' . $search . '%');
        })
            ->where('userid', Auth::user()->userid)
            ->orderBy('id', 'DESC')
            ->paginate(25);
        $data = SystemHistoryResource::collection($history)->resource;
        app(Other::class)->history("profile", "ProfileController@" . __FUNCTION__, "Lihat Profil Riwayat", "read", "success");
        return $this->sendResponse($data, 'Successfully', 200);
    }
}
