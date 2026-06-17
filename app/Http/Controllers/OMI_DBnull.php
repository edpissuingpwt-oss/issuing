<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OMI_DBnull extends Controller
{
    public function dbnull()
    {
        return view('monitoring.dbnull');
    }

    public function getData(Request $request)
    {
        $kodetoko = $request->input('kodetoko');
        $nopb = $request->input('nopb');

        // TODO: isi query sesuai kebutuhan
        $query = "SELECT    PBO_KODEOMI,
                            PBO_NOPB,
                            pbo_pluigr,
                            prd_Deskripsipendek,
                            prd_hrgjual,prd_avgcost,
                            prd_flagomi,
                            prd_flagidm 
                        FROM igrpwt.TBMASTER_PBOMI
                        join igrpwt.tbmaster_prodmast on prd_prdcd=pbo_pluigr
                        WHERE PBO_KODEOMI = :kodetoko
                        AND PBO_NOPB = :nopb
                        AND PBO_RECORDID ='4' 
                        and (prd_hrgjual is null or prd_avgcost is null )";

        $data = DB::select($query, [
            'kodetoko' => $kodetoko,
            'nopb' => $nopb
        ]);

        return view('monitoring.dbnull_table', compact('data'));
    }
}
