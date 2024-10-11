<?php

namespace App\Http\Controllers\API\Setting;

use App\Helpers\Other;
use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\UserRequest;
use App\Http\Resources\Setting\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->when(auth()->user()->roleid === 'SUPERADMIN', function ($query) {
                $query->where('userid', '!=', 'marno');
            })
            ->when(auth()->user()->roleid !== 'SUPERADMIN', function ($query) {
                $query->where('roleid', '!=', 'SUPERADMIN');
            })
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
            })
            ->with(['rel_compid' => function ($q) {
                $q->select('compid', 'compname');
            }])
            ->with(['rel_roleid' => function ($q) {
                $q->select('roleid', 'rolename');
            }])
            ->paginate(10);

        $data = UserResource::collection($users)->resource;
        app(Other::class)->history("user", "UserController@" . __FUNCTION__, "Lihat Data Pengguna", "read", "success");
        return $this->sendResponse($data, 'Successfully', 200);
    }

    public function store(UserRequest $request)
    {
        if (!app(Other::class)->allowaccess('SETTING_USER_C')) {
            app(Other::class)->history("user", "UserController@" . __FUNCTION__, "Tambah Data Pengguna", "store", "failed");
            return response()->json(app(Other::class)->reason(), 403);
        }
        $data = $request->validated();
        if ($request->photo != null) {
            $fileName = date('YmdHi') . '_' . $request->userid . '.' . $request->photo->extension();
            $path = 'uploads/users/' . $request->userid;
            $request->photo->move(public_path($path), $fileName);
            $data['photo'] = $fileName;
            $data['photo_path'] = $path . '/' . $fileName;
        }

        $data['password'] = Hash::make('Esdm123!');
        $data['compid'] = $request->compid != null ? $request->compid : null;
        $data['created_by'] = Auth::user()->userid;
        $user = User::create($data);
        app(Other::class)->history("user", "UserController@" . __FUNCTION__, "Tambah Data " . $data['userid'], "store", "success");
        return new UserResource($user);
    }

    public function show(User $user)
    {
        if (!app(Other::class)->allowaccess('SETTING_USER_R')) {
            app(Other::class)->history("user", "UserController@" . __FUNCTION__, "Lihat Data Pengguna", "show", "failed");
            return response()->json(app(Other::class)->reason(), 403);
        }
        if (auth()->user()->roleid === 'SUPERADMIN') {
            if ($user->userid === 'marno') {
                return response()->json(['errors' => 'Anda tidak memiliki akses untuk melihat pengguna ini.'], 403);
            }
        } elseif (auth()->user()->roleid !== 'SUPERADMIN' && $user->roleid === 'SUPERADMIN') {
            return response()->json(['errors' => 'Anda tidak memiliki akses untuk melihat pengguna ini.'], 403);
        }
        $result = new UserResource($user);
        app(Other::class)->history("user", "UserController@" . __FUNCTION__, "Lihat Data " . $user->userid, "show", "success");
        return $this->sendResponse($result, 'Successfully', 200);
    }

    public function update(UserRequest $request, User $user)
    {
        if (!app(Other::class)->allowaccess('SETTING_USER_U')) {
            app(Other::class)->history("user", "UserController@" . __FUNCTION__, "Ubah Data Pengguna", "update", "failed");
            return response()->json(app(Other::class)->reason(), 403);
        }
        if (auth()->user()->roleid === 'SUPERADMIN') {
            if ($user->userid === 'marno') {
                return response()->json(['errors' => 'Anda tidak memiliki akses untuk melihat pengguna ini.'], 403);
            }
        } elseif (auth()->user()->roleid !== 'SUPERADMIN' && $user->roleid === 'SUPERADMIN') {
            return response()->json(['errors' => 'Anda tidak memiliki akses untuk melihat pengguna ini.'], 403);
        }
        $data = $request->validated();
        if ($request->photo != null) {
            $fileName = date('YmdHi') . '_' . $request->userid . '.' . $request->photo->extension();
            $path = 'uploads/users/' . $request->userid;
            $request->photo->move(public_path($path), $fileName);
            $data['photo'] = $fileName;
            $data['photo_path'] = $path . '/' . $fileName;
        }

        if ($request->password != null) {
            $data['password'] = Hash::make('Esdm123!');
        }
        $data['compid'] = $request->compid == null || $data['roleid'] != 'USER' ? null : $request->compid;
        $data['created_by'] = Auth::user()->userid;
        app(Other::class)->history("user", "UserController@" . __FUNCTION__, "Ubah Data " . $user->userid . " dari " . $user->userid . ', ' . $user->name . ', ' . $user->gender . ', ' . $user->address . ', ' . $user->phone . ', ' . $user->compid . ', ' . $user->roleid . " menjadi " . $request->userid . ', ' . $data['name'] . ', ' . $data['gender'] . ', ' . $request->address . ', ' . $data['phone'] . ', ' . $data['compid'] . ', ' . $data['roleid'], "update", "success");
        $user->update($data);
        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        if (!app(Other::class)->allowaccess('SETTING_USER_D')) {
            app(Other::class)->history("user", "UserController@" . __FUNCTION__, "Hapus Data Pengguna", "delete", "failed");
            return response()->json(app(Other::class)->reason(), 403);
        }
        app(Other::class)->history("user", "UserController@" . __FUNCTION__, "Hapus Data " . $user->userid, "delete", "success");
        $user->delete();
        return $this->sendResponse('', 'Successfully', 200);
    }

    public function exportXlsUser(Request $request)
    {
        if (!app(Other::class)->allowaccess('SETTING_USER_R')) {
            app(Other::class)->history("user", "UserController@" . __FUNCTION__, "Unduh Data Pengguna", "export", "failed");
            return response()->json(app(Other::class)->reason(), 403);
        }
        $user = User::orderBy('name', 'ASC')
            ->with(['rel_compid' => function ($q) {
                $q->select('compid', 'compname');
            }])
            ->with(['rel_roleid' => function ($q) {
                $q->select('roleid', 'rolename');
            }])
            ->get();
        $data = [];
        foreach ($user as $k => $v) {
            $data[$k]['ID'] = $v->userid;
            $data[$k]['NAMA'] = $v->name;
            $data[$k]['EMAIL'] = $v->email;
            $data[$k]['JENIS KELAMIN'] = $v->gender;
            $data[$k]['ALAMAT'] = $v->address;
            $data[$k]['TELEPON'] = $v->phone;
            $data[$k]['Pengguna'] = $v->rel_compid != null ? $v->rel_compid->compname : ($v->roleid != 'USER' ? 'Balai ESDM' : 'Tanpa Pengguna');
            $data[$k]['PERAN PENGGUNA'] = $v->rel_roleid != null ? $v->rel_roleid->rolename : 'Tanpa Peran';
        }
        app(Other::class)->history("user", "UserController@" . __FUNCTION__, "Unduh Data Pengguna", "export", "success");
        return $this->sendResponse($data, 'Successfully', 200);
    }

    public function getCheckUserID(Request $request)
    {
        $check = User::select('userid')->where('userid', $request->check)->first();
        if ($check) {
            $messages = ['errors' => ['userid' => ["ID Pengguna sudah dipakai akun lain."]], 'message' => "ID Pengguna sudah dipakai akun lain."];
            return response()->json($messages, 422);
        } else {
            return response()->json('ID Pengguna masih tersedia.', 200);
        }
    }

    public function resetPassword($user)
    {
        if (!app(Other::class)->allowaccess('SETTING_USER_U')) {
            app(Other::class)->history("user", "UserController@" . __FUNCTION__, "Atur Ulang Sandi Pengguna", "reset", "failed");
            return response()->json(app(Other::class)->reason(), 403);
        }
        $checkUser = User::where('userid', $user)->first();
        if ($checkUser) {
            User::where('userid', $checkUser->userid)->update([
                'password' => Hash::make('Esdm123!'),
            ]);
            app(Other::class)->history("user", "UserController@" . __FUNCTION__, "Atur Ulang Sandi Pengguna", "reset", "success");
            return $this->sendResponse($checkUser, 'Successfully', 200);
        } else {
            app(Other::class)->history("user", "UserController@" . __FUNCTION__, "Atur Ulang Sandi Pengguna", "reset", "failed");
            return $this->sendResponse('Error', 'Data tidak ditemukan', 422);
        }
    }
}
