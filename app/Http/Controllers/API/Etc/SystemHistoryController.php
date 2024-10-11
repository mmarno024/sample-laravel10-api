<?php

namespace App\Http\Controllers\API\Etc;

use App\Helpers\Other;
use App\Http\Controllers\Controller;
use App\Http\Resources\Etc\SystemHistoryResource;
use App\Models\Etc\SystemHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SystemHistoryController extends Controller
{
    public function index(Request $request)
    {
        if (!app(Other::class)->allowaccess('SYSTEM_HISTORY_R')) {
            app(Other::class)->history("system-history", "SystemHistoryController@" . __FUNCTION__, "Lihat Data Riwayat", "read", "failed");
            return response()->json(app(Other::class)->reason(), 403);
        }
        $history = SystemHistory::when($request->search, function ($query, $search) {
            $query->where('userid', 'like', '%' . $search . '%')
                ->orWhere('route', 'like', '%' . $search . '%')
                ->orWhere('item', 'like', '%' . $search . '%')
                ->orWhere('activity', 'like', '%' . $search . '%')
                ->orWhere('tag', 'like', '%' . $search . '%')
                ->orWhere('status', 'like', '%' . $search . '%');
        })
            ->with(['rel_created_by' => function ($q) {
                $q->select('email', 'name');
            }])
            ->with('rel_userid')
            ->orderBy('id', 'DESC');

        if (Auth::user()->roleid == 'PERUSAHAAN') {
            $history = $history->where('userid', Auth::user()->userid);
        }

        $history = $history->paginate(25);
        $data = SystemHistoryResource::collection($history)->resource;
        app(Other::class)->history("system-history", "SystemHistoryController@" . __FUNCTION__, "Lihat Data Riwayat", "read", "success");
        return $this->sendResponse($data, 'Successfully', 200);
    }

    public function exportXlsHistory(Request $request)
    {
        $system_history = SystemHistory::orderBy('id', 'DESC')
            ->with(['rel_userid' => function ($q) {
                $q->select('userid', 'name');
            }])
            ->get();
        $data = [];
        foreach ($system_history as $k => $v) {
            $data[$k]['WAKTU'] = date('d F Y', strtotime($v->created_at));
            $data[$k]['NAMA'] = $v->rel_userid->name;
            $data[$k]['ALAMAT AKSES'] = $v->route;
            $data[$k]['AKTIFITAS'] = $v->activity;
            $data[$k]['JENIS AKTIFITAS'] = $v->tag;
            $data[$k]['STATUS'] = $v->status;
        }
        return $this->sendResponse($data, 'Successfully', 200);
    }
}
