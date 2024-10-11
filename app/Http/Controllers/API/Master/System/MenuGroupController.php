<?php

namespace App\Http\Controllers\API\Master\System;

use App\Helpers\Other;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\System\MenuGroupRequest;
use App\Http\Resources\Master\System\MenuGroupResource;
use App\Models\Master\System\MenuGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuGroupController extends Controller
{
    public function index(Request $request)
    {
        if (!app(Other::class)->allowaccess('MST_SYSTEM_MENU_GROUP_R')) {
            app(Other::class)->history("menu-group", "MenuGroupController@" . __FUNCTION__, "Lihat Data Menu Group", "read", "failed");
            return response()->json(app(Other::class)->reason(), 403);
        }
        $menugroup = MenuGroup::when($request->search, function ($query, $search) {
            $query->where('groupid', 'like', '%' . $search . '%')
                ->orWhere('groupname', 'like', '%' . $search . '%');
        })
            ->with(['rel_created_by' => function ($q) {
                $q->select('email', 'name');
            }])
            ->paginate(10);
        $data = MenuGroupResource::collection($menugroup)->resource;
        app(Other::class)->history("menu-group", "MenuGroupController@" . __FUNCTION__, "Lihat Data Menu Group", "read", "success");
        return $this->sendResponse($data, 'Successfully', 200);
    }

    public function store(MenuGroupRequest $request)
    {
        if (!app(Other::class)->allowaccess('MST_SYSTEM_MENU_GROUP_C')) {
            app(Other::class)->history("menu-group", "MenuGroupController@" . __FUNCTION__, "Tambah Data Menu Group", "store", "failed");
            return response()->json(app(Other::class)->reason(), 403);
        }

        $today = date('Ymd');
        $latest = MenuGroup::where('groupid', 'like', "groupid-$today-%")->latest('groupid')->first();
        $new_number = $latest ? intval(substr($latest->groupid, -3)) + 1 : 1;
        $formatted_number = str_pad($new_number, 3, '0', STR_PAD_LEFT);
        $groupid = "groupid-$today-$formatted_number";

        $data = $request->validated();
        $data['groupid'] = $groupid;
        $data['created_by'] = Auth::user()->userid;
        $menugroup = MenuGroup::create($data);
        app(Other::class)->history("menu-group", "MenuGroupController@" . __FUNCTION__, "Tambah Data " . $data['groupid'], "store", "success");
        return new MenuGroupResource($menugroup);
    }

    public function show(MenuGroup $menugroup)
    {
        if (!app(Other::class)->allowaccess('MST_SYSTEM_MENU_GROUP_R')) {
            app(Other::class)->history("menu-group", "MenuGroupController@" . __FUNCTION__, "Lihat Data Menu Group", "show", "failed");
            return response()->json(app(Other::class)->reason(), 403);
        }
        $result = new MenuGroupResource($menugroup);
        app(Other::class)->history("menu-group", "MenuGroupController@" . __FUNCTION__, "Lihat Data " . $menugroup->groupid, "show", "success");
        return $this->sendResponse($result, 'Successfully', 200);
    }

    public function update(MenuGroupRequest $request, MenuGroup $menugroup)
    {
        if (!app(Other::class)->allowaccess('MST_SYSTEM_MENU_GROUP_U')) {
            app(Other::class)->history("menu-group", "MenuGroupController@" . __FUNCTION__, "Ubah Data Menu Group", "update", "failed");
            return response()->json(app(Other::class)->reason(), 403);
        }
        $data = $request->validated();
        app(Other::class)->history("menu-group", "MenuGroupController@" . __FUNCTION__, "Ubah Data " . $menugroup->groupid . " dari " . $menugroup->menuname . " menjadi " . $data['menuname'], "update", "success");
        $menugroup->update($data);
        return new MenuGroupResource($menugroup);
    }

    public function destroy(MenuGroup $menugroup)
    {
        if (!app(Other::class)->allowaccess('MST_SYSTEM_MENU_GROUP_D')) {
            app(Other::class)->history("menu-group", "MenuGroupController@" . __FUNCTION__, "Hapus Data Menu Group", "delete", "failed");
            return response()->json(app(Other::class)->reason(), 403);
        }
        app(Other::class)->history("menu-group", "MenuGroupController@" . __FUNCTION__, "Hapus Data " . $menugroup->groupid, "delete", "success");
        $menugroup->delete();
        return $this->sendResponse('', 'Successfully', 200);
    }
}
