<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function index()
    {
        return view('test.index');
    }

    public function result(Request $request)
    {
        $flag = $request->input('flag');

        
        // === FLAG ===
        if ($flag == '1') {
            // ZONA 1
            $query = "SELECT * FROM 
                            (SELECT count(fdkcab) as toko,
                            fdkode,
                            prd_prdcd,
                            prd_deskripsipanjang,
                            round(sum(fdqtyb)) as qty,
                            st_saldoakhir as lpp,
                            LKS_KODERAK || '.' || LKS_KODESUBRAK || '.' || LKS_TIPERAK || '.' || LKS_SHELVINGRAK || '.' || LKS_NOURUT AS rak,
                            lks_qty as plano,
                            CASE
                                                                                WHEN lks_koderak IN ('D01','D02','D03','D04') THEN 'ZONA 1'
                                                                                WHEN lks_koderak IN ('D05','D06','D07','D08') THEN 'ZONA 2'
                                                                                WHEN lks_koderak IN ('D09','D10','D11','D12') THEN 'ZONA 3'
                                                                                WHEN lks_koderak IN ('D13','D14') THEN 'ZONA 4'
                                                                                WHEN lks_koderak IN ('D15','D16') THEN 'ZONA 5'
                                                                                WHEN lks_koderak IN ('D17','D18') THEN 'ZONA 6'
                                                                                WHEN lks_koderak IN ('D19','D20','D21','D22','D23','D24') THEN 'ZONA 7'
                                                                                WHEN lks_koderak IN ('D25','D26','D27','D28','D29','D30') THEN 'ZONA 8'
                                                                                WHEN lks_koderak IN ('D31','D32','D33','D34','D35','D36') THEN 'ZONA 9'
                                                                                WHEN lks_koderak IN ('D37','D38','D39','D40','D41','D42') THEN 'ZONA A'
                                                                                WHEN lks_koderak IN ('D43','D44','D45','D46','D47','D48') THEN 'ZONA B'
                                                                                WHEN lks_koderak IN ('D49','D50','D51','D52','D53','D54') THEN 'ZONA C'
                                                                                WHEN lks_koderak IN ('D55','D56') THEN 'ZONA D'
                                                                                WHEN lks_koderak = 'D57' THEN 'ZONA E'
                                                                                WHEN lks_koderak = 'DCONT' THEN 'ZONA Z'
                                                                            END AS zona
                            from igrpwt.csv_pb_idm
                                                left join igrpwt.tbmaster_prodcrm on fdkode = prc_pluidm
                                                left join igrpwt.tbmaster_prodmast on prc_pluigr = prd_prdcd
                                                left join igrpwt.tbmaster_stock on prd_prdcd = st_prdcd and st_lokasi = '01'
                                                left join igrpwt.tbmaster_lokasi on prd_prdcd = lks_prdcd and SUBSTR(Lks_Koderak, 1, 1) = 'D' AND Lks_Tiperak = 'B' AND Lks_Prdcd IS NOT NULL
                                                where tgl_proses::date = current_date
                                                group by fdkode,prd_prdcd,prd_deskripsipanjang,st_saldoakhir,lks_koderak,lks_kodesubrak,lks_tiperak,lks_shelvingrak,lks_nourut,lks_qty
                                                order by 3) as masteran WHERE ZONA = 'ZONA 1'";
        } elseif ($flag == "2") {
            // ZONA 2
            $query = "";
        } elseif ($flag == "3") {
            // ZONA 3
            $query = "";
        } elseif ($flag == "4") {
            // ZONA 4
            $query = "";
        } elseif ($flag == "5") {
            // ZONA 5
            $query = "";
        } elseif ($flag == "6") {
            // ZONA 6
            $query = "";
        } elseif ($flag == "7") {
            // ZONA 7
            $query = "";
        } elseif ($flag == "8") {
            // ZONA 8
            $query = "";
        } elseif ($flag == "9") {
            // ZONA 9
            $query = "";
        } elseif ($flag == "10") {
            // ZONA A
            $query = "";
        } elseif ($flag == "11") {
            // ZONA B
            $query = "";
        } elseif ($flag == "12") {
            // ZONA C
            $query = "";
        } elseif ($flag == "13") {
            // ZONA D
            $query = "";
        } elseif ($flag == "14") {
            // ZONA E
            $query = "";
        }
        

        $data = DB::select($query);

        return view('test.result', [
            'data' => $data,
            'flag' => $flag
        ]);
    }
}
