<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class SoissuingController extends Controller
{
    public function soissuing()
    {
        $query = "SELECT * FROM (
                        SELECT
                            Lks_Koderak||'.'||Lks_Kodesubrak||'.'||Lks_Tiperak||'.'||Lks_Shelvingrak||'.'||Lks_Nourut AS Alamat,
                            PRD_PRDCD AS PLUIGR,
                            PRD_DESKRIPSIPANJANG AS DESK,
                            coalesce(ST_SALDOAKHIR,'0') AS LPP,
                            coalesce(LKS_QTY,'0') AS PLANO,
                            DSPB.QTYINTRANSIT AS DSPB
                        FROM IGRPWT.TBMASTER_LOKASI
                        LEFT JOIN (SELECT * FROM IGRPWT.TBMASTER_PRODMAST)b 
                        -- LEFT JOIN (SELECT * FROM TBMASTER_FREEPLU WHERE FPL_RECORDID IS NULL) ON PRD_PRDCD = FPL_PLUIGR 
                        -- LEFT JOIN (SELECT * FROM TBMASTER_PRODCRM WHERE PRC_GROUP = 'O') ON PRD_PRDCD = PRC_PLUIGR WHERE PRD_PRDCD LIKE '%0' AND PRC_PLUOMI IS NOT NULL OR FPL_FREEPLUOMI IS NOT NULL) 
                        ON LKS_PRDCD = PRD_PRDCD
                        LEFT JOIN (SELECT ST_PRDCD, ST_SALDOAKHIR FROM IGRPWT.TBMASTER_STOCK WHERE ST_LOKASI = '01')a ON ST_PRDCD = PRD_PRDCD
                        LEFT JOIN (SELECT substr (pbo_pluigr,1,6)||0 AS PLUIGR , sum (pbo_qtyrealisasi) AS QTYINTRANSIT FROM IGRPWT.tbmaster_pbomi where pbo_recordid= '4' 
                        group by substr (pbo_pluigr,1,6)||0
                        order by substr (pbo_pluigr,1,6)||0) AS DSPB
                        ON PLUIGR = PRD_PRDCD
                        WHERE LKS_KODERAK BETWEEN 'D01' AND 'D41' AND LKS_TIPERAK ='B' AND PRD_PRDCD IS NOT NULL)c ORDER BY 1";
        
        $results = DB::select($query);

        return view('master.soissuing', compact('results'));
    }
}