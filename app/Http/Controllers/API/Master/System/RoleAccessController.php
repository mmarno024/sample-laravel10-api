<?php

namespace App\Http\Controllers\API\Master\System;

use App\Helpers\Other;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\System\RoleAccessRequest;
use App\Http\Resources\Master\System\RoleAccessResource;
use App\Models\Master\System\RoleAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleAccessController extends Controller
{
    public function index(Request $request)
    {
        if (!app(Other::class)->allowaccess('MST_SYSTEM_ROLE_ACCESS_R')) {
            app(Other::class)->history("role-access", "RoleAccessController@" . __FUNCTION__, "Lihat Data Peran Pengguna", "read", "failed");
            return response()->json(app(Other::class)->reason(), 403);
        }
        $roleaccess = RoleAccess::when($request->search, function ($query, $search) {
            $query->where('roleid', 'like', '%' . $search . '%')
                ->orWhere('rolename', 'like', '%' . $search . '%');
        })
            ->with(['rel_created_by' => function ($q) {
                $q->select('email', 'name');
            }])
            ->paginate(10);
        $data = RoleAccessResource::collection($roleaccess)->resource;
        app(Other::class)->history("role-access", "RoleAccessController@" . __FUNCTION__, "Lihat Data Peran Pengguna", "read", "success");
        return $this->sendResponse($data, 'Successfully', 200);
    }

    public function store(RoleAccessRequest $request)
    {
        if (!app(Other::class)->allowaccess('MST_SYSTEM_ROLE_ACCESS_C')) {
            app(Other::class)->history("role-access", "RoleAccessController@" . __FUNCTION__, "Tambah Data Peran Pengguna", "store", "failed");
            return response()->json(app(Other::class)->reason(), 403);
        }
        $data = $request->validated();
        $data['created_by'] = Auth::user()->userid;
        $roleaccess = RoleAccess::create($data);
        app(Other::class)->history("role-access", "RoleAccessController@" . __FUNCTION__, "Tambah Data " . $data['roleid'], "store", "success");
        return new RoleAccessResource($roleaccess);
    }

    public function show(RoleAccess $roleAccess)
    {
        if (!app(Other::class)->allowaccess('MST_SYSTEM_ROLE_ACCESS_R')) {
            app(Other::class)->history("role-access", "RoleAccessController@" . __FUNCTION__, "Lihat Data Peran Pengguna", "show", "failed");
            return response()->json(app(Other::class)->reason(), 403);
        }
        $result = new RoleAccessResource($roleAccess);
        app(Other::class)->history("role-access", "RoleAccessController@" . __FUNCTION__, "Lihat Data " . $roleAccess->roleid, "show", "success");
        return $this->sendResponse($result, 'Successfully', 200);
    }

    public function update(RoleAccessRequest $request, RoleAccess $roleAccess)
    {
        if (!app(Other::class)->allowaccess('MST_SYSTEM_ROLE_ACCESS_U')) {
            app(Other::class)->history("role-access", "RoleAccessController@" . __FUNCTION__, "Ubah Data Peran Pengguna", "update", "failed");
            return response()->json(app(Other::class)->reason(), 403);
        }
        $data = $request->validated();
        app(Other::class)->history("role-access", "RoleAccessController@" . __FUNCTION__, "Ubah Data " . $roleAccess->roleid . " dari " . $roleAccess->roleid . ', ' . $roleAccess->rolename . " menjadi " . $data['roleid'] . ', ' . $data['rolename'], "update", "success");
        $roleAccess->update($data);
        return new RoleAccessResource($roleAccess);
    }

    public function destroy(RoleAccess $roleAccess)
    {
        if (!app(Other::class)->allowaccess('MST_SYSTEM_ROLE_ACCESS_D')) {
            app(Other::class)->history("role-access", "RoleAccessController@" . __FUNCTION__, "Hapus Data Peran Pengguna", "delete", "failed");
            return response()->json(app(Other::class)->reason(), 403);
        }
        app(Other::class)->history("role-access", "RoleAccessController@" . __FUNCTION__, "Hapus Data " . $roleAccess->roleid, "delete", "success");
        $roleAccess->delete();
        return $this->sendResponse('', 'Successfully', 200);
    }

    public function accessRoleAccess(Request $request)
    {
        $roleaccess = RoleAccess::when($request->search, function ($query, $search) {
            $query->where('roleid', 'like', '%' . $search . '%')
                ->orWhere('rolename', 'like', '%' . $search . '%');
        })
            ->get();
        $data = RoleAccessResource::collection($roleaccess)->resource;
        return $this->sendResponse($data, 'Successfully', 200);
    }
}
