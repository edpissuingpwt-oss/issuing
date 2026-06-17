<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class BklomiController extends Controller
{
    public function bklomi()
    {
        return view('laporan.bklomi');
    }

    public function getData(Request $request)
    {
        // Ambil 1 tanggal start
        $start = \Carbon\Carbon::createFromFormat('d-m-Y', $request->tanggal_mulai)->format('d-m-Y');

        $sql = "SELECT * FROM IGRPWT.TBHISTORY_BKL
                INNER JOIN (SELECT SUP_KODESUPPLIER, SUP_NAMASUPPLIER FROM IGRPWT.TBMASTER_SUPPLIER) sup
                ON BKL_KODESUPPLIER = SUP_KODESUPPLIER
                where to_char(BKL_TGLSTRUK, 'DD-MM-YYYY') = :start";

        $results = DB::select($sql, [
            'start' => $start
        ]);

        return view('laporan.bklomi_table', [
            'data' => $results,
            'start' => $start
        ]);
    }
}