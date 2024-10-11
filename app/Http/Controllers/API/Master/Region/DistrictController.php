<?php

namespace App\Http\Controllers\API\Master\Region;

use App\Helpers\Other;
use App\Http\Controllers\Controller;
use App\Http\Resources\Master\Region\DistrictResource;
use App\Models\Master\Region\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DistrictController extends Controller
{
    public function index(Request $request)
    {
        if (!app(Other::class)->allowaccess('MST_REGION_DISTRICT_R')) {
            app(Other::class)->history("district", "DistrictController@" . __FUNCTION__, "Lihat Data Kecamatan", "read", "failed");
            return response()->json(app(Other::class)->reason(), 403);
        }
        $district = District::select('mst_region_district.disid', 'prov.provid', 'prov.provname', 'cit.citid', 'cit.citname', 'mst_region_district.disname')
            ->when($request->search, function ($query, $search) {
                $query->where('mst_region_district.disid', 'like', '%' . $search . '%')
                    ->orWhere('prov.provname', 'like', '%' . $search . '%')
                    ->orWhere('cit.citname', 'like', '%' . $search . '%')
                    ->orWhere('mst_region_district.disname', 'like', '%' . $search . '%');
            })
            ->join(DB::raw('mst_region_city as cit'), 'cit.citid', '=', 'mst_region_district.citid')
            ->join(DB::raw('mst_region_province as prov'), 'prov.provid', '=', 'cit.provid')
            ->paginate(10);
        $data = DistrictResource::collection($district)->resource;
        app(Other::class)->history("district", "DistrictController@" . __FUNCTION__, "Lihat Data Kecamatan", "read", "success");
        return $this->sendResponse($data, 'Successfully', 200);
    }
}
