<?php

namespace App\Http\Controllers\API\Master\Region;

use App\Helpers\Other;
use App\Http\Controllers\Controller;
use App\Http\Resources\Master\Region\ProvinceResource;
use App\Models\Master\Region\Province;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    public function index(Request $request)
    {
        if (!app(Other::class)->allowaccess('MST_REGION_PROVINCE_R')) {
            app(Other::class)->history("province", "ProvinceController@" . __FUNCTION__, "Lihat Data provinsi", "read", "failed");
            return response()->json(app(Other::class)->reason(), 403);
        }
        $users = Province::when($request->search, function ($query, $search) {
            $query->where('provid', 'like', '%' . $search . '%')
                ->orWhere('provname', 'like', '%' . $search . '%');
        })->paginate(10);
        $data = ProvinceResource::collection($users)->resource;
        app(Other::class)->history("province", "ProvinceController@" . __FUNCTION__, "Lihat Data Provinsi", "read", "success");
        return $this->sendResponse($data, 'Successfully', 200);
    }
}
