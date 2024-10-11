<?php

namespace App\Http\Controllers\API\Setting;

use App\Helpers\Other;
use App\Http\Controllers\Controller;
use App\Models\Master\System\AccessKey;
use App\Models\Master\System\Menu;
use App\Models\Setting\SettingAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingAccessController extends Controller
{
    public function accessMenu(Request $request)
    {
        if (!app(Other::class)->allowaccess('SETTING_ACCESS_R')) {
            app(Other::class)->history("settingaccess", "SettingAccessController@" . __FUNCTION__, "Lihat Data Hak Akses Menu", "read", "failed");
            return response()->json(app(Other::class)->reason(), 403);
        }
        $settingaccess = SettingAccess::where('type', 'role-to-menu')->where('head_item', $request->id)->where('group', 'group')->get();
        $arrAccess = [];
        foreach ($settingaccess as $k => $v) {
            $arrAccess[$k] = $v->item;
        }
        $menu = Menu::when($request->search, function ($query, $search) {
            $query->where('menuid', 'like', '%' . $search . '%')
                ->orWhere('menuname', 'like', '%' . $search . '%');
        })
            ->get();
        $data = [];
        foreach ($menu as $k => $v) {
            $data[$k]['menuid'] = $v->menuid;
            $data[$k]['menuname'] = $v->menuname;
            $data[$k]['flag'] = in_array($v->menuid, $arrAccess) ? 1 : 0;
        }
        app(Other::class)->history("settingaccess", "SettingAccessController@" . __FUNCTION__, "Lihat Data Hak Akses Menu", "read", "success");
        return $this->sendResponse($data, 'Successfully', 200);
    }
    public function accessAccessKey(Request $request)
    {
        if (!app(Other::class)->allowaccess('SETTING_ACCESS_R')) {
            app(Other::class)->history("settingaccess", "SettingAccessController@" . __FUNCTION__, "Lihat Data Hak Akses Item Akses", "read", "failed");
            return response()->json(app(Other::class)->reason(), 403);
        }
        $settingaccess = SettingAccess::where('type', 'role-to-access')->where('head_item', $request->id)->where('group', 'group')->get();
        $arrAccess = [];
        foreach ($settingaccess as $k => $v) {
            $arrAccess[$k] = $v->item;
        }
        $accesskey = AccessKey::when($request->search, function ($query, $search) {
            $query->where('accessid', 'like', '%' . $search . '%')
                ->orWhere('accessname', 'like', '%' . $search . '%');
        })
            ->get();
        $data = [];
        foreach ($accesskey as $k => $v) {
            $data[$k]['accessid'] = $v->accessid;
            $data[$k]['accessname'] = $v->accessname;
            $data[$k]['flag'] = in_array($v->accessid, $arrAccess) ? 1 : 0;
        }
        app(Other::class)->history("settingaccess", "SettingAccessController@" . __FUNCTION__, "Lihat Data Hak Akses Item Akses", "read", "success");
        return $this->sendResponse($data, 'Successfully', 200);
    }

    public function store(Request $request)
    {
        if (!app(Other::class)->allowaccess('SETTING_ACCESS_C')) {
            app(Other::class)->history("settingaccess", "SettingAccessController@" . __FUNCTION__, "Tambah Data Pengguna", "store", "failed");
            return response()->json(app(Other::class)->reason(), 403);
        }
        $settingaccess = SettingAccess::where('type', $request->type)
            ->where('head_item', $request->head_item)
            ->where('item', $request->item)
            ->withTrashed();
        // ->first();

        if ($settingaccess) {
            if ($request->flag == 0) {
                app(Other::class)->history("settingaccess", "SettingAccessController@" . __FUNCTION__, "Hapus Data " . $request->item, "delete", "success");
                $settingaccess->first()->delete();
                return response()->json('deleted');
            } else {
                if ($settingaccess->count() > 0) {
                    app(Other::class)->history("settingaccess", "SettingAccessController@" . __FUNCTION__, "Pulihkan Data " . $request->item, "restore", "success");
                    $settingaccess->first()->restore();
                    return response()->json('restored');
                }
            }
        }
        SettingAccess::create([
            'type' => $request->type,
            'head_item' => $request->head_item,
            'item' => $request->item,
            'group' => 'group',
            'created_by' => Auth::user()->userid,
        ]);
        app(Other::class)->history("settingaccess", "SettingAccessController@" . __FUNCTION__, "Tambah Data " . $request->item, "store", "success");
        return response()->json($settingaccess);
    }

    public function accessAllowMenu(Request $request)
    {
        $usergroup = Auth::user()->roleid;
        $checkaccess = SettingAccess::select('item')->where('type', 'role-to-menu')->where('head_item', $usergroup)->get();
        $menuaccess = [];
        foreach ($checkaccess as $k => $v) {
            $menuaccess[$k] = $v->item;
        }

        $arrMenu = Menu::whereNull('parent')
            ->with(['rel_menu' => function ($q1) use ($menuaccess) {
                $q1->select('id', 'parent', 'menuid', 'menuname', 'order_no', 'icon', 'url')->whereIn('menuid', $menuaccess)->with(['rel_menu' => function ($q2) use ($menuaccess) {
                    $q2->select('id', 'parent', 'menuid', 'menuname', 'order_no', 'icon', 'url')->whereIn('menuid', $menuaccess)->with(['rel_menu'])->whereNull('deleted_at');
                }])->whereNull('deleted_at');
            }])->whereNull('deleted_at')
            ->whereIn('menuid', $menuaccess)
            ->with(['rel_groupid' => function ($x) {
                $x->select('groupid', 'groupname');
            }])
            ->orderBy('order_no')
            ->get();

        $dataMenu = [];
        foreach ($arrMenu as $k => $v) {
            $dataMenu[$v->rel_groupid->groupname][$k] = $v;
        }

        return response()->json($dataMenu);
    }

    public function accessAllowMenuRoute(Request $request)
    {
        $usergroup = Auth::user()->roleid;
        $checkaccess = SettingAccess::select('item')->where('type', 'role-to-menu')->where('head_item', $usergroup)->whereNull('deleted_at')->get();
        $dataMenu = [];
        foreach ($checkaccess as $k => $v) {
            $menuurl = Menu::select('menuid', 'url')->where('menuid', $v->item)->first();
            $dataMenu[$k] = $menuurl->url;
        }
        return response()->json($dataMenu);
    }
}
