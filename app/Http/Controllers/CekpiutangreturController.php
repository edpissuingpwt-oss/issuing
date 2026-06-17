<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CekpiutangreturController extends Controller
{
    public function piutangretur()
    {
        return view('retur.piutangretur');
    }

    public function getData(Request $request)
    {
        $namatoko = $request->input('namatoko');
        $nonrb = $request->input('nonrb');

        $query = "SELECT RECID,
                    TKO_KODEOMI,
                    TKO_KODECUSTOMER,
                    DOCNO,
                    ITEM,
                    TRPT_SALESVALUE,
                    TRPT_NETSALES,
                    TGL_INVOICE,
                    TGL_PROSES
                    FROM (SELECT
                    Recid,
                    TGL_INVOICE,
                    TGL_PROSES,
                    TKO_KODEOMI,
                    TKO_KODECUSTOMER,
                    DOCNO,
                    ITEM
                    From igrpwt.Tbmaster_Tokoigr
                    LEFT JOIN (SELECT DISTINCT RECID,SHOP,DOCNO,COUNT(PRDCD) AS ITEM,TGL1 AS TGL_INVOICE,WT_MODIFY_DT AS TGL_PROSES FROM igrpwt.TBTR_WT_INTERFACE GROUP BY RECID, SHOP, DOCNO, TGL1, WT_MODIFY_DT)AA ON TKO_KODEOMI = SHOP
                    WHERE TKO_KODESBU ='I') AB
                    LEFT JOIN 
                    (SELECT TRPT_CUS_KODEMEMBER AS KODEMEMBER,
                    TRPT_SALESVALUE,
                    Trpt_Netsales,
                    TRPT_INVOICETAXNO AS NO_NRB FROM igrpwt.TBTR_PIUTANG WHERE TRPT_TYPE ='D') AC
                    On Ac.Kodemember = Ab.Tko_Kodecustomer And Ac.No_Nrb = Ab.Docno
                    where recid in ('A','S','P') AND TKO_KODEOMI = ? AND DOCNO = ?";
        

        $result = DB::connection('pgsql')->select($query, [$namatoko, $nonrb]);

        return view('retur.piutang_table', compact('result'));
    }
}