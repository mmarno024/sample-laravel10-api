<?php

namespace App\Http\Controllers\API\Master\Region;

use App\Helpers\Other;
use App\Http\Controllers\Controller;
use App\Http\Resources\Master\Region\CityResource;
use App\Models\Master\Region\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CityController extends Controller
{
    public function index(Request $request)
    {
        if (!app(Other::class)->allowaccess('MST_REGION_CITY_R')) {
            app(Other::class)->history("city", "CityController@" . __FUNCTION__, "Lihat Data Kota/Kabupaten", "read", "failed");
            return response()->json(app(Other::class)->reason(), 403);
        }
        $city = City::select('mst_region_city.citid', 'prov.provid', 'prov.provname', 'mst_region_city.citname')
            ->when($request->search, function ($query, $search) {
                $query->where('mst_region_city.citid', 'like', '%' . $search . '%')
                    ->orWhere('prov.provname', 'like', '%' . $search . '%')
                    ->orWhere('mst_region_city.citname', 'like', '%' . $search . '%');
            })
            ->join(DB::raw('mst_region_province as prov'), 'prov.provid', '=', 'mst_region_city.provid')
            ->paginate(10);
        $data = CityResource::collection($city)->resource;
        app(Other::class)->history("city", "CityController@" . __FUNCTION__, "Lihat Data Kota/Kabupaten", "read", "success");
        return $this->sendResponse($data, 'Successfully', 200);
    }
}
