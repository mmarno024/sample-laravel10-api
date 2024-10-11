<?php

namespace App\Helpers;

use App\Models\Etc\SystemHistory;
use App\Models\Setting\SettingAccess;
use App\Models\Master\System\AccessKey;
use Illuminate\Support\Facades\Auth;

class Other
{
    private static $strreason;

    public static function allowaccess($accessid)
    {
        $accesskey = AccessKey::where('accessid', $accessid)->count();
        if ($accesskey == 0) {
            self::$strreason = "Item Akses $accessid tidak ditemukan";
            return false;
        }

        $usergroup = Auth::user()->roleid;
        $checkaccess = SettingAccess::where('type', 'role-to-access')->where('head_item', $usergroup)->where('item', $accessid)->count();
        if ($checkaccess == 0) {
            self::$strreason = "Anda tidak mempunyai akses $accessid untuk aktifitas ini";
            return false;
        } else {
            return true;
        }
    }
    
    public static function reason()
    {
        return self::$strreason;
    }

    public function history($route, $item, $activity, $tag, $status)
    {
        SystemHistory::create([
            'userid' => Auth::user()->userid,
            'route' => $route,
            'item' => $item,
            'activity' => $activity,
            'tag' => $tag,
            'status' => $status,
            'created_by' => Auth::user()->userid,
        ]);
        return response()->json('create_history');
    }
}
