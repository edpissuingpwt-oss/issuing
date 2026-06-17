<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\View;

class Loadingmobil extends Controller
{
    public function loadingmobil()
    {
        return view('monitoring.loadingmobil');
    }

    public function getData(Request $request)
    {
        $flag = $request->input('flag');
        $tglMulai = $request->input('tanggal_mulai');
        $tglSelesai = $request->input('tanggal_selesai');

        $tglMulai = date("Y-m-d", strtotime($tglMulai));
        $tglSelesai = date("Y-m-d", strtotime($tglSelesai));

        $belumscan = "SELECT distinct lmi_nodspb nodspb,
                                        lmi_nosj,
                                        lmi_kodetoko,
                                        lmi_nopb,
                                        lmi_nourut,
                                        lmi_namatoko,
                                        lmi_create_dt,
                                        to_char(lmi_create_dt, 'DD-MM-YYYY') tanggal,
                                        lmi_jenispengiriman,
                                        count(lmi_nokoli) totalkoli
                                        from igrpwt.loading_mobil_idm where lmi_flag is null 
                                        group by lmi_nodspb,lmi_nosj,lmi_kodetoko,lmi_namatoko,lmi_nopb,lmi_nourut,lmi_create_dt,lmi_jenispengiriman
                                        order by lmi_nosj";
        
        $belumabsen = "SELECT distinct lmi_nodspb nodspb,
                                        lmi_nosj,
                                        lmi_kodetoko,
                                        lmi_nourut,
                                        lmi_namatoko,
                                        lmi_create_dt,
                                        to_char(lmi_create_dt, 'DD-MM-YYYY') tanggal,
                                        lmi_flag,
                                        lmi_kodemobil,
                                        lmi_nopolisi,
                                        lmi_dolly,
                                        lmi_nopb,
                                        lmi_jenispengiriman,
                                        sum(lmi_kubikasi) totalkubikasi,
                                        sum(lmi_nilai) totalnilai,
                                        count(lmi_nokoli) totalkoli 
                                        from igrpwt.loading_mobil_idm where lmi_flag = '3'
                                group by lmi_nodspb,lmi_nosj,lmi_kodetoko,lmi_namatoko,lmi_nourut,lmi_create_dt,lmi_flag,lmi_nopb,lmi_kodemobil,lmi_nopolisi,lmi_dolly,lmi_jenispengiriman
                                order by lmi_nosj";

        $belumpulang = "SELECT distinct lmi_nodspb nodspb,
                                        lmi_nosj,
                                        lmi_nopb,
                                        lmi_kodetoko,
                                        lmi_nourut,
                                        lmi_namatoko,
                                        lmi_create_dt,
                                        to_char(lmi_create_dt, 'DD-MM-YYYY') tanggal,
                                        lmi_flag,
                                        lmi_kodemobil,
                                        lmi_nopolisi,
                                        lmi_dolly,
                                        lmi_niksupir,
                                        lmi_namasupir,
                                        to_char(lmi_berangkat, 'DD-MM-YYYY') ||'|'|| TO_CHAR(lmi_berangkat, 'HH24:MI') as lmi_berangkat,
                                        lmi_jenispengiriman,
                                        sum(lmi_kubikasi) totalkubikasi,
                                        sum(lmi_nilai) totalnilai,
                                        count(lmi_nokoli) totalkoli 
                                        from igrpwt.loading_mobil_idm where lmi_flag = '4' and lmi_create_dt::date between ? and ?
                                group by lmi_nodspb,lmi_nosj,lmi_kodetoko,lmi_namatoko,lmi_nourut,lmi_create_dt,lmi_flag,lmi_kodemobil,lmi_nopolisi,lmi_dolly,lmi_niksupir,lmi_namasupir,lmi_berangkat,lmi_jenispengiriman,lmi_nopb
                                order by lmi_nosj";

        $sudahpulang = "SELECT distinct lmi_nodspb nodspb,
                                        lmi_nosj,
                                        lmi_nopb,
                                        lmi_kodetoko,
                                        lmi_nourut,
                                        lmi_namatoko,
                                        lmi_create_dt,
                                        to_char(lmi_create_dt, 'DD-MM-YYYY') tanggal,
                                        lmi_flag,
                                        lmi_kodemobil,
                                        lmi_nopolisi,
                                        lmi_dolly,
                                        lmi_niksupir,
                                        lmi_namasupir,
                                        to_char(lmi_berangkat, 'DD-MM-YYYY') ||'|'|| TO_CHAR(lmi_berangkat, 'HH24:MI') as lmi_berangkat,
                                        to_char(lmi_pulang, 'DD-MM-YYYY') ||'|'|| TO_CHAR(lmi_pulang, 'HH24:MI') as lmi_pulang,
                                        lmi_jenispengiriman,
                                        sum(lmi_kubikasi) totalkubikasi,
                                        sum(lmi_nilai) totalnilai,
                                        count(lmi_nokoli) totalkoli 
                                        from igrpwt.loading_mobil_idm where lmi_flag = '5' and lmi_create_dt::date between ? and ?
                                group by lmi_nodspb,lmi_nosj,lmi_kodetoko,lmi_namatoko,lmi_nourut,lmi_create_dt,lmi_flag,lmi_kodemobil,lmi_nopolisi,lmi_dolly,lmi_niksupir,lmi_namasupir,lmi_berangkat,lmi_pulang,lmi_jenispengiriman,lmi_nopb
                                order by lmi_nosj";

        // Tentukan query sesuai flag
        switch ($flag) {
            case 'belumscan':
                $result = DB::connection('pgsql')->select($belumscan);
                break;
            case 'belumabsen':
                $result = DB::connection('pgsql')->select($belumabsen);
                break;
            case 'belumpulang':
                $result = DB::connection('pgsql')->select($belumpulang, [$tglMulai, $tglSelesai]);
                break;
            case 'sudahpulang':
                $result = DB::connection('pgsql')->select($sudahpulang, [$tglMulai, $tglSelesai]);
                break;
            default:
                $result = [];
        }


        return view('monitoring.loadingmobil_table', compact('result', 'flag', 'tglMulai', 'tglSelesai'));
    }
}