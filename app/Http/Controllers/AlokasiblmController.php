<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class AlokasiblmController extends Controller
{
    public function alokasiblm()
    {
        $query = "SELECT
                        ts_kodetoko kode_toko, 
                        ts_pluidm pluidm,  
                        ts_pluigr pluigr,
                        prd_deskripsipanjang desk,
                        prd_frac|| '/' || prd_unit as frac,
                        ts_target as target_seasonal,
                        coalesce(qty_real, 0) qty_realisasi,
                        ts_target - coalesce(qty_real, 0) as belum_alokasi,
                        ts_target * st_avgcost as rph_target,
                        coalesce(qty_real, 0) * st_avgcost as rph_real,
                        (ts_target - coalesce(qty_real, 0)) * st_avgcost as rph_selisih
                        from igrpwt.target_seasonal
                        left join (select rs_kodetoko, rs_pluidm, rs_pluigr, sum(rs_qty_real) qty_real 
                                from igrpwt.realisasi_seasonal 
                                group by rs_kodetoko, rs_pluidm, rs_pluigr
                                order by rs_kodetoko, rs_pluidm) rs 
                                on ts_kodetoko = rs_kodetoko and ts_pluidm = rs_pluidm
                        left join igrpwt.tbmaster_prodmast on ts_pluidm = prd_plumcg and prd_prdcd like '%0'
                        left join igrpwt.tbmaster_stock on prd_prdcd = st_prdcd and st_lokasi = '01'
                        where qty_real < ts_target
                        order by ts_kodetoko, ts_pluidm";
        
        $results = DB::select($query);

        return view('seasonal.alokasiblm', compact('results'));
    }
}