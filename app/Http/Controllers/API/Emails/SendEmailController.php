<?php

namespace App\Http\Controllers\API\Emails;

use App\Helpers\Other;
use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use App\Models\Activity\Waterhole;
use Exception;

class SendEmailController extends Controller
{
    public function sendEmail($id)
    {
        if (!app(Other::class)->allowaccess('NOTIF_EMAIL_S')) {
            app(Other::class)->history("sendemail", "SendEmailController@" . __FUNCTION__, "Mengirim email notifikasi perizinan", "send", "failed");
            return response()->json(app(Other::class)->reason(), 403);
        }
        try {
            $query = Waterhole::where('whid', $id)
                ->with(['rel_typeid' => function ($q) {
                    $q->select('typeid', 'typename');
                }])
                ->with(['rel_compid' => function ($q) {
                    $q->select('compid', 'compname', 'phone', 'email', 'address as compaddress', 'citid as compcitid', 'disid as compdisid');
                }])
                ->with(['rel_provid' => function ($q) {
                    $q->select('provid', 'provname');
                }])
                ->with(['rel_citid' => function ($q) {
                    $q->select('citid', 'citname');
                }])
                ->with(['rel_disid' => function ($q) {
                    $q->select('disid', 'disname');
                }])->first();

            $data['email'] = 'mmarno.024@gmail.com';
            $data['content'] = $query;
            dispatch(new SendEmailJob($data));
            app(Other::class)->history("sendemail", "SendEmailController@" . __FUNCTION__, "Mengirim email notifikasi perizinan", "send", "success");
            return response()->json(['message' => 'Email berhasil dikirim']);
        } catch (Exception $e) {
            app(Other::class)->history("sendemail", "SendEmailController@" . __FUNCTION__, "Mengirim email notifikasi perizinan", "send", "failed");
            return response()->json(['message' => 'error ' . $e->getMessage()]);
        }
    }
}
