<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class CekAcost_Controller extends Controller
{
    public function cekacost()
    {
        $query = "SELECT
                        RECID,
                        DOCNO NRB,
                        SHOP KODETOKO,
                        PRDCD PLU_IDM,
                        prd_prdcd plu_igr,
                        prd_deskripsipanjang,
                        wt_create_dt tanggal_nrb,
                        prd_avgcost ACOST
                        FROM igrpwt.tbtr_wt_interface
                        LEFT JOIN igrpwt.tbmaster_prodmast
                        ON prdcd = prd_plumcg
                        WHERE prd_tgldaftar IS NOT NULL
                        AND RECID NOT LIKE ('P')
                        ORDER BY prd_avgcost";
        
        $results = DB::select($query);

        return view('retur.cekacost', compact('results'));
    }
}