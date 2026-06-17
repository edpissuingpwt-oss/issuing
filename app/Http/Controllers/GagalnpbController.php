<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class GagalnpbController extends Controller
{
    public function gagalnpb()
    {
        $query = "SELECT * FROM IGRPWT.LOG_NPB 
                  LEFT JOIN IGRPWT.TBMASTER_TOKOIGR ON NPB_KODETOKO = TKO_KODEOMI AND TKO_KODESBU = 'I'
                WHERE NPB_RESPONSE LIKE '%GAGAL%'";
        
        $results = DB::select($query);

        return view('problem.gagalnpb', compact('results'));
    }
}