<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class NpbtdkterbentukController extends Controller
{
    public function npbtidakterbentuk()
    {
        $query = "SELECT distinct rpb_idsuratjalan,rpb_nodokumen,rpb_kodeomi,rpb_create_dt,rpb_flag,count(rpb_plu1)jumlah_plu,ROUND(SUM(RPB_TTLNILAI)) AS RP_NILAI,
                            ROUND(SUM(RPB_TTLPPN)) AS RP_PPN,
                            ROUND(SUM(RPB_TTLNILAI) + SUM(RPB_TTLPPN)) AS TOTAL 
                            from IGRPWT.tbtr_realpb
                    LEFT JOIN IGRPWT.LOG_NPB on rpb_idsuratjalan = npb_nodspb
                    where rpb_idsuratjalan not in (select npb_nodspb from igrpwt.log_npb) and rpb_kodeomi not like 'O%' and rpb_flag = '4' 
                    group by rpb_idsuratjalan,rpb_kodeomi,rpb_create_dt,rpb_flag,rpb_nodokumen
                    order by 4 desc";
        
        $results = DB::select($query);

        return view('problem.npbtidakterbentuk', compact('results'));
    }
}