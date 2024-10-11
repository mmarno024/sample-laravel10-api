<?php

namespace App\Http\Controllers\API\Lookup;

use App\Http\Controllers\Controller;
use App\Models\Master\Region\City;
use App\Models\Master\Region\District;
use App\Models\Master\Region\Province;
use App\Models\Master\Region\Subdistrict;
use App\Models\Master\System\AccessKey;
use App\Models\Master\System\Menu;
use App\Models\Master\System\MenuGroup;
use App\Models\Master\System\RoleAccess;
use App\Models\User;
use Illuminate\Http\Request;

class LookupController extends Controller
{
    public function lookupProvince(Request $request)
    {
        $province = Province::select('provid as id', 'provname as text')->orderBy('provname', 'ASC')->get();
        return $this->sendResponse($province, 'Successfully', 200);
    }
    public function lookupCity(Request $request)
    {
        $province = 34;
        $city = City::select('citid as id', 'citname as text')->where('provid', $province)->orderBy('citname', 'ASC')->get();
        return $this->sendResponse($city, 'Successfully', 200);
    }
    public function lookupDistrict(Request $request)
    {
        $city = $request->city;
        $district = District::select('disid as id', 'disname as text')
            ->where('citid', $city)
            ->orderBy('disname', 'ASC')->get();
        return $this->sendResponse($district, 'Successfully', 200);
    }
    public function lookupDistrict2(Request $request)
    {
        $district = District::select('disid as id', 'disname as text')
            ->orderBy('disname', 'ASC')->get();
        return $this->sendResponse($district, 'Successfully', 200);
    }
    public function lookupSubdistrict(Request $request)
    {
        $district = $request->district;
        $subdistrict = Subdistrict::select('subid as id', 'subname as text')->where('disid', $district)->orderBy('subname', 'ASC')->get();
        return $this->sendResponse($subdistrict, 'Successfully', 200);
    }

    public function lookupMenuGroup(Request $request)
    {
        $menugroupx = MenuGroup::select('groupid as id', 'groupname')->orderBy('groupname', 'ASC')->get();
        foreach ($menugroupx as $k => $v) {
            $menugroup[$k]['id'] = $v->id;
            $menugroup[$k]['text'] = $v->groupname;
        }
        return $this->sendResponse($menugroup, 'Successfully', 200);
    }

    public function lookupMenu(Request $request)
    {
        $menux = Menu::select('menuid as id', 'menuname', 'url')->orderBy('menuname', 'ASC')->get();
        foreach ($menux as $k => $v) {
            $menu[$k]['id'] = $v->id;
            $menu[$k]['text'] = $v->url != null ? $v->menuname . ' [ url: /' . $v->url . ' ]' : $v->menuname;
        }
        return $this->sendResponse($menu, 'Successfully', 200);
    }
    public function lookupAccessKey(Request $request)
    {
        $accesskey = AccessKey::select('accessid as id', 'accessname as text')->orderBy('accessname', 'ASC')->get();
        return $this->sendResponse($accesskey, 'Successfully', 200);
    }
    public function lookupRoleAccess(Request $request)
    {
        $roleaccess = RoleAccess::select('roleid as id', 'rolename as text')->orderBy('roleid', 'ASC')->get();
        return $this->sendResponse($roleaccess, 'Successfully', 200);
    }
    public function lookupUser(Request $request)
    {
        $user = User::select('userid as id', 'name as text')->orderBy('name', 'ASC')->get();
        return $this->sendResponse($user, 'Successfully', 200);
    }
}
