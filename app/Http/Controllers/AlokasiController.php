<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class AlokasiController extends Controller
{
    public function alokasiseasonal()
    {
        $query = "SELECT AS_TGLKIRIM,
                                ZONA,
                                ALAMAT,
                                AS_PLUIDM,
                                AS_PLUIGR,
                                DESK,
                                FRAC,
                                toko,
                                QTYPCS_TARGET,
                                ROUND(QTYPCS_TARGET/FRAC) TARGET_CTN,
                                ACOSTCTN,
                                ROUND(ACOSTCTN/FRAC) ACOSTPCS,
                                ROUND(QTYPCS_TARGET)*ROUND(ACOSTCTN/FRAC) TOTAL,
                                LKS_NOID 
                                FROM
                                (SELECT AS_TGLKIRIM,AS_PLUIDM,AS_PLUIGR,SUM(AS_QTY) QTYPCS_TARGET, count(as_kodetoko) toko FROM IGRPWT.ALOKASI_SEASONAL 
                                        WHERE AS_RECORDID IS NULL and date(as_tglkirim) between current_date and current_date +2  GROUP BY AS_TGLKIRIM,AS_PLUIDM, AS_PLUIGR) as alk
                                LEFT JOIN
                                (SELECT PRD_PRDCD,PRD_PLUMCG,PRD_DESKRIPSIPANJANG DESK,PRD_FRAC FRAC,ROUND(PRD_AVGCOST) ACOSTCTN FROM IGRPWT.TBMASTER_PRODMAST WHERE PRD_PRDCD LIKE '%0') as prd ON AS_PLUIGR = PRD_PRDCD
                                LEFT JOIN (SELECT 
                                LKS_KODERAK || '.'|| LKS_KODESUBRAK || '.'|| LKS_TIPERAK || '.'|| LKS_SHELVINGRAK AS ALAMAT,
                                LKS_PRDCD,
                                LKS_NOID,
                                CASE
                                    WHEN LKS_KODERAK IN('D01','D02','D03','D04') THEN 'ZONA1'
                                    WHEN LKS_KODERAK IN('D05','D06','D07','D08') THEN 'ZONA2'
                                    WHEN LKS_KODERAK IN('D09','D10','D11','D12') THEN 'ZONA3'
                                    WHEN LKS_KODERAK IN('D13','D14') THEN 'ZONA4'
                                    WHEN LKS_KODERAK IN('D15','D16') THEN 'ZONA5'
                                    WHEN LKS_KODERAK IN('D17','D18') THEN 'ZONA6'
                                    WHEN LKS_KODERAK IN('D19','D20','D21','D22','D23','D24') THEN 'ZONA7'
                                    WHEN LKS_KODERAK IN('D25','D26','D27','D28','D29','D30') THEN 'ZONA8'
                                    WHEN LKS_KODERAK IN('D31','D32','D33','D34','D35','D36') THEN 'ZONA9'
                                    WHEN LKS_KODERAK IN('D37','D38','D39','D40','D41','D42') THEN 'ZONAA'
                                    WHEN LKS_KODERAK IN('D43','D44','D45','D46','D47','D48') THEN 'ZONAB'
                                    WHEN LKS_KODERAK IN('D49','D50','D51','D52','D53','D54') THEN 'ZONAC'
                                    WHEN LKS_KODERAK IN('D55','D56') THEN 'ZONAD'
                                    WHEN LKS_KODERAK IN('D57') THEN 'ZONAE'
                                    ELSE ''
                                END AS ZONA 
                                FROM IGRPWT.TBMASTER_LOKASI WHERE LKS_KODERAK LIKE 'D%' AND LKS_TIPERAK ='B' AND LKS_KODERAK NOT LIKE 'DKLIK%') as su 
                                ON LKS_PRDCD = AS_PLUIGR
                                ORDER BY 1,2";
        
        $results = DB::select($query);

        return view('seasonal.alokasi', compact('results'));
    }
}