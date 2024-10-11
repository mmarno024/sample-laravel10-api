<?php

namespace App\Http\Controllers\API\Master\System;

use App\Helpers\Other;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\System\MenuRequest;
use App\Http\Resources\Master\System\MenuResource;
use App\Models\Master\System\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        if (!app(Other::class)->allowaccess('MST_SYSTEM_MENU_R')) {
            app(Other::class)->history("menu", "MenuController@" . __FUNCTION__, "Lihat Data Menu", "read", "failed");
            return response()->json(app(Other::class)->reason(), 403);
        }
        $menu = Menu::when($request->search, function ($query, $search) {
            $query->where('groupid', 'like', '%' . $search . '%')
                ->orWhere('menuname', 'like', '%' . $search . '%')
                ->orWhere('icon', 'like', '%' . $search . '%');
        })
            ->with(['rel_created_by' => function ($q) {
                $q->select('email', 'name');
            }])
            ->with(['rel_groupid' => function ($q) {
                $q->select('groupid', 'groupname');
            }])
            ->paginate(10);
        $data = MenuResource::collection($menu)->resource;
        app(Other::class)->history("menu", "MenuController@" . __FUNCTION__, "Lihat Data Menu", "read", "success");
        return $this->sendResponse($data, 'Successfully', 200);
    }

    public function store(MenuRequest $request)
    {
        if (!app(Other::class)->allowaccess('MST_SYSTEM_MENU_C')) {
            app(Other::class)->history("menu", "MenuController@" . __FUNCTION__, "Tambah Data Menu", "store", "failed");
            return response()->json(app(Other::class)->reason(), 403);
        }

        $today = date('Ymd');
        $latest = Menu::where('menuid', 'like', "menuid-$today-%")->latest('menuid')->first();
        $new_number = $latest ? intval(substr($latest->menuid, -3)) + 1 : 1;
        $formatted_number = str_pad($new_number, 3, '0', STR_PAD_LEFT);
        $menuid = "menuid-$today-$formatted_number";

        $data = $request->validated();
        $data['menuid'] = $menuid;
        $data['created_by'] = Auth::user()->userid;
        $menu = Menu::create($data);
        app(Other::class)->history("menu", "MenuController@" . __FUNCTION__, "Tambah Data " . $data['menuid'], "store", "success");
        return new MenuResource($menu);
    }

    public function show(Menu $menu)
    {
        if (!app(Other::class)->allowaccess('MST_SYSTEM_MENU_R')) {
            app(Other::class)->history("menu", "MenuController@" . __FUNCTION__, "Lihat Data Menu", "show", "failed");
            return response()->json(app(Other::class)->reason(), 403);
        }
        $result = new MenuResource($menu);
        app(Other::class)->history("menu", "MenuController@" . __FUNCTION__, "Lihat Data " . $menu->menuid, "show", "success");
        return $this->sendResponse($result, 'Successfully', 200);
    }

    public function update(MenuRequest $request, Menu $menu)
    {
        if (!app(Other::class)->allowaccess('MST_SYSTEM_MENU_U')) {
            app(Other::class)->history("menu", "MenuController@" . __FUNCTION__, "Ubah Data Menu", "update", "failed");
            return response()->json(app(Other::class)->reason(), 403);
        }
        $data = $request->validated();
        app(Other::class)->history("menu", "MenuController@" . __FUNCTION__, "Ubah Data " . $menu->menuid . " dari " . $menu->menuname . " menjadi " . $data['menuname'], "update", "success");
        $menu->update($data);
        return new MenuResource($menu);
    }

    public function destroy(Menu $menu)
    {
        if (!app(Other::class)->allowaccess('MST_SYSTEM_MENU_D')) {
            app(Other::class)->history("menu", "MenuController@" . __FUNCTION__, "Hapus Data Menu", "delete", "failed");
            return response()->json(app(Other::class)->reason(), 403);
        }
        app(Other::class)->history("menu", "MenuController@" . __FUNCTION__, "Hapus Data " . $menu->menuid, "delete", "success");
        $menu->delete();
        return $this->sendResponse('', 'Successfully', 200);
    }

    public function accessMenu(Request $request)
    {
        $menu = Menu::when($request->search, function ($query, $search) {
            $query->where('menuid', 'like', '%' . $search . '%')
                ->orWhere('menuname', 'like', '%' . $search . '%');
        })
            ->get();
        $data = MenuResource::collection($menu)->resource;
        return $this->sendResponse($data, 'Successfully', 200);
    }
}
