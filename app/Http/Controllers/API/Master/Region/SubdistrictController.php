<?php

namespace App\Http\Controllers\API\Master\Region;

use App\Helpers\Other;
use App\Http\Controllers\Controller;
use App\Http\Resources\Master\Region\SubdistrictResource;
use App\Models\Master\Region\Subdistrict;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubdistrictController extends Controller
{
    public function index(Request $request)
    {
        if (!app(Other::class)->allowaccess('MST_REGION_SUBDISTRICT_R')) {
            app(Other::class)->history("subdistrict", "SubDistrictController@" . __FUNCTION__, "Lihat Data Kelurahan", "read", "failed");
            return response()->json(app(Other::class)->reason(), 403);
        }
        $subdistrict = Subdistrict::select('mst_region_subdistrict.subid', 'dis.disid', 'dis.disname', 'prov.provid', 'prov.provname', 'cit.citid', 'cit.citname', 'mst_region_subdistrict.subname')
            ->when($request->search, function ($query, $search) {
                $query->where('mst_region_subdistrict.subid', 'like', '%' . $search . '%')
                    ->orWhere('prov.provname', 'like', '%' . $search . '%')
                    ->orWhere('cit.citname', 'like', '%' . $search . '%')
                    ->orWhere('dis.disname', 'like', '%' . $search . '%')
                    ->orWhere('mst_region_subdistrict.subname', 'like', '%' . $search . '%');
            })
            ->join(DB::raw('mst_region_district as dis'), 'dis.disid', '=', 'mst_region_subdistrict.disid')
            ->join(DB::raw('mst_region_city as cit'), 'cit.citid', '=', 'dis.citid')
            ->join(DB::raw('mst_region_province as prov'), 'prov.provid', '=', 'cit.provid')
            ->paginate(10);
        $data = SubdistrictResource::collection($subdistrict)->resource;
        app(Other::class)->history("subdistrict", "SubDistrictController@" . __FUNCTION__, "Lihat Data Kelurahan", "read", "success");
        return $this->sendResponse($data, 'Successfully', 200);
    }
}
