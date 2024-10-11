<?php

namespace App\Http\Controllers\API\Popup;

use App\Http\Controllers\Controller;
use App\Models\Master\System\RoleAccess;
use App\Models\User;
use Illuminate\Http\Request;

class PopupController extends Controller
{
    public function popupUser(Request $request)
    {
        $user = User::select('userid', 'name', 'phone')
            ->with(['rel_compid' => function ($q) {
                $q->select('compid', 'compname');
            }])
            ->paginate(10);
        return $this->sendResponse($user, 'Successfully', 200);
    }
    public function popupRoleAccess(Request $request)
    {
        $role = RoleAccess::select('roleid', 'rolename')->orderBy('id', 'ASC')->paginate(10);
        return $this->sendResponse($role, 'Successfully', 200);
    }
}
