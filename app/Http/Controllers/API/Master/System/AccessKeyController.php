<?php

namespace App\Http\Controllers\API\Master\System;

use App\Helpers\Other;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\System\AccessKeyRequest;
use App\Http\Resources\Master\System\AccessKeyResource;
use App\Models\Master\System\AccessKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccessKeyController extends Controller
{
    public function index(Request $request)
    {
        if (!app(Other::class)->allowaccess('MST_SYSTEM_ACCESS_KEY_R')) {
            app(Other::class)->history("access-key", "AccessKeyController@" . __FUNCTION__, "Lihat Data Item Akses", "read", "failed");
            return response()->json(app(Other::class)->reason(), 403);
        }
        $accesskey = AccessKey::when($request->search, function ($query, $search) {
            $query->where('accessid', 'like', '%' . $search . '%')
                ->orWhere('accessname', 'like', '%' . $search . '%');
        })
            ->with(['rel_created_by' => function ($q) {
                $q->select('email', 'name');
            }])
            ->paginate(10);
        $data = AccessKeyResource::collection($accesskey)->resource;
        app(Other::class)->history("access-key", "AccessKeyController@" . __FUNCTION__, "Lihat Data Item Akses", "read", "success");
        return $this->sendResponse($data, 'Successfully', 200);
    }

    public function store(AccessKeyRequest $request)
    {
        if (!app(Other::class)->allowaccess('MST_SYSTEM_ACCESS_KEY_C')) {
            app(Other::class)->history("access-key", "AccessKeyController@" . __FUNCTION__, "Tambah Data Item Akses", "store", "failed");
            return response()->json(app(Other::class)->reason(), 403);
        }
        $data = $request->validated();
        $data['created_by'] = Auth::user()->userid;
        $accesskey = AccessKey::create($data);
        app(Other::class)->history("access-key", "AccessKeyController@" . __FUNCTION__, "Tambah Data " . $data['accessid'], "store", "success");
        return new AccessKeyResource($accesskey);
    }

    public function show(AccessKey $accessKey)
    {
        if (!app(Other::class)->allowaccess('MST_SYSTEM_ACCESS_KEY_R')) {
            app(Other::class)->history("access-key", "AccessKeyController@" . __FUNCTION__, "Lihat Data Item Akses", "show", "failed");
            return response()->json(app(Other::class)->reason(), 403);
        }
        $result = new AccessKeyResource($accessKey);
        app(Other::class)->history("access-key", "AccessKeyController@" . __FUNCTION__, "Lihat Data " . $accessKey->accessid, "show", "success");
        return $this->sendResponse($result, 'Successfully', 200);
    }

    public function update(AccessKeyRequest $request, AccessKey $accessKey)
    {
        if (!app(Other::class)->allowaccess('MST_SYSTEM_ACCESS_KEY_U')) {
            app(Other::class)->history("access-key", "AccessKeyController@" . __FUNCTION__, "Ubah Data Item Akses", "update", "failed");
            return response()->json(app(Other::class)->reason(), 403);
        }
        $data = $request->validated();
        app(Other::class)->history("access-key", "AccessKeyController@" . __FUNCTION__, "Ubah Data " . $accessKey->accessid . " dari " . $accessKey->accessid . ', ' . $accessKey->accessname . " menjadi " . $data['accessid'] . ', ' . $data['accessname'], "update", "success");
        $accessKey->update($data);
        return new AccessKeyResource($accessKey);
    }

    public function destroy(AccessKey $accessKey)
    {
        if (!app(Other::class)->allowaccess('MST_SYSTEM_ACCESS_KEY_D')) {
            app(Other::class)->history("access-key", "AccessKeyController@" . __FUNCTION__, "Hapus Data Item Akses", "delete", "failed");
            return response()->json(app(Other::class)->reason(), 403);
        }
        app(Other::class)->history("access-key", "AccessKeyController@" . __FUNCTION__, "Hapus Data " . $accessKey->accessid, "delete", "success");
        $accessKey->delete();
        return $this->sendResponse('', 'Successfully', 200);
    }

    public function accessAccessKey(Request $request)
    {
        $accesskey = AccessKey::when($request->search, function ($query, $search) {
            $query->where('accessid', 'like', '%' . $search . '%')
                ->orWhere('accessname', 'like', '%' . $search . '%');
        })
            ->get();
        $data = AccessKeyResource::collection($accesskey)->resource;
        return $this->sendResponse($data, 'Successfully', 200);
    }
}
