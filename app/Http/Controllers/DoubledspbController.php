<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DoubledspbController extends Controller
{
    public function doubledspb()
    {
        try {

            $query = "
                WITH data_aggregated AS (
                    SELECT 
                        'GI47' AS supco,
                        r.rpb_idsuratjalan AS nosj,
                        r.rpb_create_dt AS tanggalsj,
                        TO_CHAR(r.rpb_create_dt,'HH24:MI:SS') AS jamsj,
                        r.rpb_kodeomi AS toko,
                        'G028' AS kcab,
                        t.tko_namaomi AS namatoko,
                        r.rpb_nodokumen AS nopb,
                        COUNT(*) AS item,
                        SUM(r.rpb_qtyrealisasi) AS qty,
                        ROUND(SUM(r.rpb_ttlnilai)) AS rpdspb,
                        ROUND(SUM(r.rpb_ttlppn)) AS ppndspb,
                        ROUND(SUM(r.rpb_ttlnilai + r.rpb_ttlppn)) AS total
                    FROM igrpwt.tbtr_realpb r
                    JOIN igrpwt.tbmaster_tokoigr t 
                        ON r.rpb_kodeomi = t.tko_kodeomi
                    WHERE r.rpb_flag >= '4'
                    AND r.rpb_create_dt >= CURRENT_DATE - INTERVAL '7 days'
                    GROUP BY 
                        r.rpb_kodeigr,
                        r.rpb_idsuratjalan,
                        r.rpb_create_dt,
                        r.rpb_kodeomi,
                        t.tko_namaomi,
                        r.rpb_nodokumen
                ),
                duplikat AS (
                    SELECT toko,nopb,total
                    FROM data_aggregated
                    GROUP BY toko,nopb,total
                    HAVING COUNT(*) > 1
                )
                SELECT d.*
                FROM data_aggregated d
                JOIN duplikat dup
                    ON d.toko = dup.toko
                    AND d.nopb = dup.nopb
                    AND d.total = dup.total
                ORDER BY d.tanggalsj DESC
            ";

            $results = DB::select($query);

        } catch (\Exception $e) {

            $results = [];

        }

        return view('problem.doubledspb', compact('results'));
    }
}