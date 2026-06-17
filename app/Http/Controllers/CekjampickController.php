<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CekjampickController extends Controller
{
    public function jampick()
    {
        return view('monitoring.jampick');
    }

    public function getData(Request $request)
    {
        $noPick = $request->input('noPick');
        $zona   = strtoupper($request->input('zona'));

        // TODO: isi query sesuai kebutuhan
        $query = "SELECT 
                        KODEZONA AS ZONA,
                        KODERAK AS ALAMAT,
                        SUBSTR(PRDCD, 1, 6) || '0' AS PLU,  -- PostgreSQL uses 1-based indexing
                        DESC2 AS DESKRIPSI,
                        QTYR AS QTY,
                        jam_picking as JAM_PICK,
                        CASE
                            WHEN FMRCID IS NULL THEN 'BELUM PICKING'
                            WHEN FMRCID < '2' THEN 'BELUM PICKING'
                            WHEN QTYR = '0' THEN 'REALISASI NOL'
                            ELSE 'SUDAH PICKING'
                        END AS STATUS,
                        FMKCAB AS KODETOKO,
                        NOPICKING AS NO_PICKING,
                        TGLUPD AS TGL_PB
                    FROM 
                        igrpwt.DPD_IDM_ORA
                        WHERE NOPICKING = :noPick AND KODEZONA LIKE :zona 
                        ORDER BY PRDCD";

        $data = DB::select($query, [
            'noPick' => $noPick,
            'zona' => "%$zona%"
        ]);

        return view('monitoring.jampick_table', compact('data'));
    }
}
