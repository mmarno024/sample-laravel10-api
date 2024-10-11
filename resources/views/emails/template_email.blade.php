<!DOCTYPE html>
<html>

<head>
    <title>Notifikasi Perizinan Sumur [BP3ESDM DI YOGYAKARTA]</title>
</head>

<body>
    <div style="border: 1px solid #666;border-radius:5px;padding:10px">
        {{-- <div>
            <img src="http://localhost:8000/public/uploads/setting/head_letter/202407241546_head_letter.png}" height="200" />
        </div> --}}
        <h1>Notifikasi Perizinan Sumur [BP3ESDM DI YOGYAKARTA]</h1>
        <div style="margin:10px 0">
            <div>Kepada Yth. :</div>
            <div>Pimpinan PT. {{ $mailData['content']->rel_compid->compname }}</div>
            <div>Yang beralamat di {{ $mailData['content']->rel_compid->compaddress }}</div>
            <div>Daerah Istimewa Yogyakarta</div>
        </div>

        <div style="margin:10px 0">
            <div>Dengan ini kami memberitahukan bahwa :</div>
        </div>
        <div style="margin:10px 0">
            <table width="50%" border="1" cellpadding="5" cellspacing="0">
                <tr>
                    <td width="30%">Identitas Sumur</td>
                    <td width="70%">
                        {{ $mailData['content']->whname }}
                    </td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>
                        {{ $mailData['content']->address }}, {{ $mailData['content']->rel_disid->disname }},
                        {{ $mailData['content']->rel_citid->citname }}, Daerah Istimewa Yogyakarta
                    </td>
                </tr>
                <tr>
                    <td>Titik Lokasi</td>
                    <td>
                        {{ $mailData['content']->utm_ls_degree }}&deg;
                        {{ $mailData['content']->utm_ls_minute }}&apos;
                        {{ $mailData['content']->utm_ls_second }}&quot; LS,
                        {{ $mailData['content']->utm_bt_degree }}&deg;
                        {{ $mailData['content']->utm_bt_minute }}&apos;
                        {{ $mailData['content']->utm_bt_second }}&quot; BT /
                        {{ $mailData['content']->geo_latitude }},
                        {{ $mailData['content']->geo_longitude }}
                    </td>
                </tr>
                <tr>
                    <td>Dokumen Izin</td>
                    <td>
                        {{ $mailData['content']->license_no }}
                    </td>
                </tr>
            </table>
        </div>
        <div style="margin:10px 0">
            <div>Akan habis masa aktif perizinananya pada tanggal
                {{ \Carbon\Carbon::parse($mailData['content']->end_period)->format('d-m-Y') }}. Maka dari itu
                kami himbau agar segera
                memperbarui dokumen perizinan sumur tersebut.</div>
            <div>Terimakasih</div>
        </div>
        <div style="margin:10px 0">
            <p>&nbsp;</p>
            TTD,
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>(Pimpinan BP3ESDM)</p>
        </div>
    </div>
</body>

</html>
