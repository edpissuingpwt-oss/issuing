<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PajakomiController extends Controller
{
    public function pajakomi()
    {
        return view('laporan.pajakomi');
    }

    public function getData(Request $request)
    {
        $flag = $request->input('flag');
        $tglMulai = $request->input('tanggal_mulai');
        $tglSelesai = $request->input('tanggal_selesai');

        $tglMulai = date("Y-m-d", strtotime($tglMulai));
        $tglSelesai = date("Y-m-d", strtotime($tglSelesai));

        $sales = "SELECT DISTINCT 
                                    rpb_kodecustomer as kodemember, 
                                    rpb_kodeomi  AS toko, 
                                    tko_namaomi AS namatoko, 
                                    Rpb_Create_Dt As Tglstruk, 
                                    RPB_NOSPH AS NOSPH, 
                                    fkt_nofaktur as nofaktur, 
                                    SUM(rpb_ttlnilai) AS rpstruk,
                                    SUM(rpb_ttlppn)   AS ppnstruk,
                                    SUM(rpb_distributionfee) AS distfee,
                                    SUM(rpb_distributionfee) * 11/100 AS ppndistfee,
                                    SUM(rpb_ttlnilai) + SUM(rpb_ttlppn) + SUM(rpb_distributionfee) + (SUM(rpb_distributionfee) * 11/100) AS total 
                                    FROM  
                                    (SELECT * FROM IGRPWT.TBTR_REALPB 
                                    WHERE rpb_flag='5' and rpb_KODEOMI IN (select TKO_KODEOMI from IGRPWT.tbmaster_tokoigr where tko_kodesbu = 'O') 
                                    and DATE(rpb_create_dt) BETWEEN ? AND ? ) rpb
                                    left join (select * from IGRPWT.tbmaster_tokoigr where tko_kodesbu = 'O') tko
                                    On Rpb_Kodeomi = Tko_Kodeomi 
                                    LEFT JOIN (SELECT * FROM IGRPWT.TBMASTER_FAKTUR) fkt ON FKT_KODEMEMBER = RPB_KODECUSTOMER AND RPB_IDSURATJALAN = FKT_NODSPB 
                                    WHERE FKT_NOFAKTUR IS NOT NULL AND FKT_FLAGBKP2 = 'Y'
                                    GROUP BY rpb_kodecustomer, rpb_kodeomi, tko_namaomi, Rpb_Create_Dt, RPB_NOSPH, fkt_nofaktur 
                                    ORDER BY 1,2,4,5";
        
        $bkl = "SELECT Distinct Bkl_Kodeomi As Kodeomi,
                                Fkt_Kodemember As Kode_Member,
                                tko_namaomi as namatoko,
                                Bkl_Nobukti As Nobukti,
                                Bkl_Kodesupplier As Kodesupp,
                                Bkl_Nodoc As Nodoc,
                                Bkl_Tglstruk As Tglstruk,
                                Bkl_Nostruk As Nostruk,
                                Bkl_Total AS TOTAL,
                                Fkt_Nofaktur AS NOFAKTUR,
                                Fkt_Noseri AS NOSERI_FAKTUR 
                                From 
                                (Select * From IGRPWT.TBHISTORY_BKL 
                                LEFT JOIN (SELECT TKO_KODEOMI,TKO_NAMAOMI,TKO_KODECUSTOMER FROM IGRPWT.TBMASTER_TOKOIGR) tko ON BKL_KODEOMI = TKO_KODEOMI 
                                WHERE DATE(BKL_TGLSTRUK) BETWEEN ? AND ? )AC
                                Left  Join (Select * From IGRPWT.Tbmaster_Faktur)Ab
                                On Ac.Bkl_Nostruk = Ab.Fkt_Notransaksi And Ac.Tko_Kodecustomer = Fkt_Kodemember And Ac.Bkl_Tglstruk = Ab.Fkt_Tgl ORDER BY 2,5,6";

        if ($flag == 'sales') {
            $query = $sales;
        } elseif ($flag == 'bkl') {
            $query = $bkl;
        }

        $result = DB::connection('pgsql')->select($query, [$tglMulai, $tglSelesai]);

        return view('laporan.pajakomi_table', compact('result','flag'));
    }
}