<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DoublepiutangController extends Controller
{
    public function doublepiutang()
    {
        $query = "SELECT 
                        Trpt_Salesinvoiceno,
                        REF_PIUTANG,
                        TRPT_SALESVALUE,
                        TRPT_NETSALES,
                        TRPT_PPNTAXVALUE,
                        TRPT_CASHIERID AS USERABSEN,
                        USERNAME,
                        TRPT_CREATE_DT
                    FROM (
                        SELECT 
                            Trpt_Salesinvoiceno || '.' || TKO_KODEOMI || '.' || Trpt_Invoicetaxno AS REF_PIUTANG,
                            Trpt_Salesvalue,
                            TRPT_NETSALES,
                            TRPT_CASHIERID,
                            USERNAME,
                            Trpt_Ppntaxvalue,
                            TRPT_CREATE_DT,
                            Trpt_Salesinvoiceno
                        FROM 
                            igrpwt.TBTR_PIUTANG
                        LEFT JOIN 
                            igrpwt.TBMASTER_TOKOIGR ON TBTR_PIUTANG.TRPT_CUS_KODEMEMBER = TKO_KODECUSTOMER
                        LEFT JOIN 
                            (SELECT USERID, USERNAME FROM igrpwt.TBMASTER_USER) AS u ON TRPT_CASHIERID = u.USERID
                        WHERE 
                            Trpt_Type = 'D'
                            AND DATE(TRPT_CREATE_DT) >= '2022-07-01'  -- Date format changed to 'YYYY-MM-DD'
                    ) AS subquery
                    WHERE 
                        REF_PIUTANG IN (
                            SELECT ref
                            FROM (
                                SELECT 
                                    REF_PIUTANG AS ref,
                                    COUNT(*) 
                                FROM (
                                    SELECT 
                                        Trpt_Salesinvoiceno || '.' || TKO_KODEOMI || '.' || Trpt_Invoicetaxno AS REF_PIUTANG,
                                        Trpt_Salesvalue,
                                        TRPT_NETSALES,
                                        TRPT_PPNTAXVALUE,
                                        TRPT_CREATE_DT
                                    FROM 
                                        igrpwt.TBTR_PIUTANG
                                    LEFT JOIN 
                                        igrpwt.TBMASTER_TOKOIGR ON TBTR_PIUTANG.TRPT_CUS_KODEMEMBER = TKO_KODECUSTOMER
                                    WHERE 
                                        Trpt_Type = 'D'
                                        AND DATE(TRPT_CREATE_DT) >= '2024-06-01'  -- Date format changed to 'YYYY-MM-DD'
                                ) AS inner_query
                                GROUP BY REF_PIUTANG
                                HAVING COUNT(*) > 1
                            ) AS count_query
                        )
                    ORDER BY 
                        REF_PIUTANG";
        
        $results = DB::select($query);

        return view('problem.doublepiutang', compact('results'));
    }
}