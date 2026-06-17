<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class MonitoringController extends Controller
{
    public function pbisli()
    {
        $query = "SELECT
                        -- ITEM
                        AA.KDTK AS KODE,
                        TKO_NAMAOMI AS NAMA_TOKO,
                        AA.ITEMPB AS ITEMP,
                        AB.ITEMR AS ITEMR,
                        ROUND(COALESCE(SUM(AB.ITEMR)::numeric / NULLIF(SUM(AA.ITEMPB), 0), 0) * 100) AS ITEMPC,
                        -- QTY
                        AA.QTYPB AS QTYP,
                        AB.QTYR AS QTYR,
                        ROUND(COALESCE(SUM(AB.QTYR)::numeric / NULLIF(SUM(AA.QTYPB), 0), 0) * 100) AS QTYPC,
                    --    ROUND((AB.QTYR/AA.QTYPB)*100) AS QTYPC,
                        -- RPH
                        AA.RPHPB AS RPHP,
                        AB.RPHR AS RPHR,
                    --    coalesce((sum(cast(AB.QTYR as double precision))/SUM(cast(AA.QTYPB as double precision)) * 100),0) AS QTYPC
                        ROUND((AB.RPHR/AA.RPHPB)*100) AS RPHPC
                        FROM
                        (SELECT
                        PBO_KODEOMI AS KDTK,
                        COUNT(PBO_PLUIGR) AS ITEMPB,
                        SUM(PBO_QTYORDER) AS QTYPB,
                        SUM(PBO_NILAIORDER) AS RPHPB
                        FROM IGRPWT.TBMASTER_PBOMI WHERE date(PBO_CREATE_DT) = current_date
                        AND PBO_KODEOMI IN(SELECT TKO_KODEOMI FROM IGRPWT.TBMASTER_TOKOIGR WHERE TKO_KODESBU = 'I') 
                        group by PBO_KODEOMI) AA
                        LEFT JOIN
                        (SELECT
                        PBO_KODEOMI,
                        COUNT(PBO_NOKOLI) AS ITEMR,
                        SUM(CASE WHEN pbo_ttlnilai != 0 AND pbo_hrgsatuan != 0 THEN pbo_ttlnilai / pbo_hrgsatuan ELSE 0 END) AS QTYR,
                        SUM(PBO_TTLNILAI) AS RPHR
                        FROM IGRPWT.TBMASTER_PBOMI WHERE date(PBO_CREATE_DT) = current_date AND PBO_NOKOLI IS NOT NULL
                        AND PBO_KODEOMI IN(SELECT TKO_KODEOMI FROM IGRPWT.TBMASTER_TOKOIGR WHERE TKO_KODESBU = 'I') 
                        group by PBO_KODEOMI) AB
                        ON AA.KDTK = AB.PBO_KODEOMI
                        LEFT JOIN
                        (SELECT * FROM IGRPWT.TBMASTER_TOKOIGR WHERE TKO_KODESBU = 'I') as tko
                        ON AA.KDTK = TKO_KODEOMI
                        WHERE AA.ITEMPB > 50 AND AA.QTYPB > 50
                        GROUP BY AA.KDTK,TKO.TKO_NAMAOMI,AA.ITEMPB,AB.ITEMR,AA.QTYPB,AB.QTYR,AA.RPHPB,AB.RPHR
                        ORDER BY AA.KDTK";


        $query2 = "SELECT SUBSTRING(pbo_pluigr FROM 1 FOR 6) || '0' AS plu,
            deskripsi,
            bb.jml_toko,
            bb.qtyo,
            coalesce((bb.qtyr),0) as qtyr,
            (SUM(bb.qtyr) / NULLIF(SUM(bb.qtyo), 0)) * 100 AS slqty,
            bb.nilaio,
            bb.ttlnilai,
            (SUM(bb.ttlnilai) / NULLIF(SUM(bb.nilaio), 0)) * 100 AS slnilai
            FROM (
                SELECT 
                pbo_pluigr,
                pbo_kodedivisi as div,
                pbo_kodedepartemen as dept,
                pbo_kodekategoribrg as katb,
                count(pbo_kodeomi) jml_toko,
                sum(pbo_qtyorder) as qtyo,
                sum(pbo_ttlnilai) / pbo_hrgsatuan as qtyr,
                sum(pbo_nilaiorder) as nilaio,
                coalesce(sum(pbo_ttlnilai), 0) as ttlnilai
                FROM igrpwt.tbmaster_pbomi
                WHERE DATE(PBO_CREATE_DT) = CURRENT_DATE
                AND pbo_kodesbu = 'I'
                GROUP BY pbo_pluigr, div, dept, katb, pbo_hrgsatuan
                ORDER BY 1
            ) bb
            LEFT JOIN (
                SELECT prd_prdcd as plu2, prd_deskripsipanjang as deskripsi 
                FROM igrpwt.tbmaster_prodmast
            ) aa 
            ON pbo_pluigr = aa.plu2
            GROUP BY bb.pbo_pluigr, aa.deskripsi, bb.jml_toko, bb.qtyo, bb.qtyr, bb.nilaio, bb.ttlnilai
            ORDER BY plu";
        
        $pbi = DB::select($query);
        $sli = DB::select($query2);

        return view('monitoring.pbisli', compact('pbi','sli'));
    }

    public function pboslo()
    {
        $query = "SELECT
                        -- ITEM
                        AA.KDTK AS KODE,
                        TKO_NAMAOMI AS NAMA_TOKO,
                        AA.NOPB AS NOPB,
                        AA.ITEMPB AS ITEMP,
                        AB.ITEMR AS ITEMR,
                        ROUND(COALESCE(SUM(AB.ITEMR)::numeric / NULLIF(SUM(AA.ITEMPB), 0), 0) * 100) AS ITEMPC,
                        -- QTY
                        AA.QTYPB AS QTYP,
                        AB.QTYR AS QTYR,
                        ROUND(COALESCE(SUM(AB.QTYR)::numeric / NULLIF(SUM(AA.QTYPB), 0), 0) * 100) AS QTYPC,
                    --    ROUND((AB.QTYR/AA.QTYPB)*100) AS QTYPC,
                        -- RPH
                        AA.RPHPB AS RPHP,
                        AB.RPHR AS RPHR,
                    --    coalesce((sum(cast(AB.QTYR as double precision))/SUM(cast(AA.QTYPB as double precision)) * 100),0) AS QTYPC
                        ROUND(COALESCE(AB.RPHR::numeric / NULLIF(AA.RPHPB, 0), 0) * 100) AS RPHPC
                        FROM
                        (SELECT
                        PBO_KODEOMI AS KDTK,
                        PBO_NOPB AS NOPB,
                        COUNT(PBO_PLUIGR) AS ITEMPB,
                        SUM(PBO_QTYORDER) AS QTYPB,
                        SUM(PBO_NILAIORDER) AS RPHPB
                        FROM IGRPWT.TBMASTER_PBOMI WHERE date(PBO_CREATE_DT) = current_date
                        AND PBO_KODEOMI IN(SELECT TKO_KODEOMI FROM IGRPWT.TBMASTER_TOKOIGR WHERE TKO_KODESBU = 'O')
                        AND PBO_CREATE_BY NOT IN ('INS','TSW') 
                        group by PBO_KODEOMI,PBO_NOPB) AA
                        LEFT JOIN
                        (SELECT
                        PBO_KODEOMI,
                        COUNT(PBO_NOKOLI) AS ITEMR,
                        SUM(CASE WHEN pbo_ttlnilai != 0 AND pbo_hrgsatuan != 0 THEN pbo_ttlnilai / pbo_hrgsatuan ELSE 0 END) AS QTYR,
                        SUM(PBO_TTLNILAI) AS RPHR
                        FROM IGRPWT.TBMASTER_PBOMI WHERE date(PBO_CREATE_DT) = current_date AND PBO_NOKOLI IS NOT NULL
                        AND PBO_KODEOMI IN(SELECT TKO_KODEOMI FROM IGRPWT.TBMASTER_TOKOIGR WHERE TKO_KODESBU = 'O') 
                        AND PBO_CREATE_BY NOT IN ('INS','TSW')
                        group by PBO_KODEOMI) AB
                        ON AA.KDTK = AB.PBO_KODEOMI
                        LEFT JOIN
                        (SELECT * FROM IGRPWT.TBMASTER_TOKOIGR WHERE TKO_KODESBU = 'O') as tko
                        ON AA.KDTK = TKO_KODEOMI
                        -- WHERE AA.ITEMPB > 5 AND AA.QTYPB > 5
                        GROUP BY AA.KDTK,TKO.TKO_NAMAOMI,AA.NOPB,AA.ITEMPB,AB.ITEMR,AA.QTYPB,AB.QTYR,AA.RPHPB,AB.RPHR
                        ORDER BY AA.KDTK";


        $query2 = "SELECT SUBSTRING(pbo_pluigr FROM 1 FOR 6) || '0' AS plu,
            deskripsi,
            bb.jml_toko,
            bb.qtyo,
            coalesce((bb.qtyr),0) as qtyr,
            (SUM(bb.qtyr) / NULLIF(SUM(bb.qtyo), 0)) * 100 AS slqty,
            bb.nilaio,
            bb.ttlnilai,
            (SUM(bb.ttlnilai) / NULLIF(SUM(bb.nilaio), 0)) * 100 AS slnilai
            FROM (
                SELECT 
                pbo_pluigr,
                pbo_kodedivisi as div,
                pbo_kodedepartemen as dept,
                pbo_kodekategoribrg as katb,
                count(pbo_kodeomi) jml_toko,
                sum(pbo_qtyorder) as qtyo,
                sum(pbo_ttlnilai) / pbo_hrgsatuan as qtyr,
                sum(pbo_nilaiorder) as nilaio,
                coalesce(sum(pbo_ttlnilai), 0) as ttlnilai
                FROM igrpwt.tbmaster_pbomi
                WHERE DATE(PBO_CREATE_DT) = CURRENT_DATE
                AND pbo_kodesbu = 'O'
                AND PBO_CREATE_BY NOT IN ('INS','TSW')
                GROUP BY pbo_pluigr, div, dept, katb, pbo_hrgsatuan
                ORDER BY 1
            ) bb
            LEFT JOIN (
                SELECT prd_prdcd as plu2, prd_deskripsipanjang as deskripsi 
                FROM igrpwt.tbmaster_prodmast
            ) aa 
            ON pbo_pluigr = aa.plu2
            GROUP BY bb.pbo_pluigr, aa.deskripsi, bb.jml_toko, bb.qtyo, bb.qtyr, bb.nilaio, bb.ttlnilai
            ORDER BY plu";
        
        $pbo = DB::select($query);
        $slo = DB::select($query2);

        return view('monitoring.pboslo', compact('pbo','slo'));
    }

    public function timepicking()
    {
        return view('monitoring.timepicking');
    }

    public function timepicking_h(Request $request)
    {
        $flag = $request->input('flag');
        $start = $request->input('tanggal_mulai');
        $end = $request->input('tanggal_selesai');

        // Cek flag valid
        if (!in_array($flag, ['omi', 'idm'])) {
            return response()->json(['error' => 'Invalid flag'], 400);
        }

        // Mapping flag ke kode SBU
        $kodeSBU = $flag === 'omi' ? 'O' : 'I';

        $sql = "SELECT 
                    FMNDOC,
                    NOPICKING,
                    FMKCAB,
                    PICO_NAMATOKO,
                    TANGGAL,
                    KODEZONA,
                    JAM_UPLOAD,
                    MULAI_PICK,
                    SELESAI_PICK,
                    JAM_DSPB,
                    ROUND(EXTRACT(EPOCH FROM (TO_TIMESTAMP(SELESAI_PICK, 'HH24:MI:SS') - TO_TIMESTAMP(MULAI_PICK, 'HH24:MI:SS'))) / 60) AS WAKTU_MNT, 
                    ROUND(EXTRACT(EPOCH FROM (TO_TIMESTAMP(SELESAI_PICK, 'HH24:MI:SS') - TO_TIMESTAMP(MULAI_PICK, 'HH24:MI:SS')))) AS WAKTU_DTK, 
                    BB.JML_KOLI,
                    ITEM
                FROM (
                    SELECT 
                        FMNDOC,
                        NOPICKING,
                        FMKCAB,
                        TO_CHAR(TO_DATE(TGLUPD, 'yyyymmdd'), 'DD-MM-YYYY') AS TANGGAL,
                        KODEZONA,
                        JAM_UPLOAD,
                        MIN(JAM_PICKING) AS MULAI_PICK,
                        MAX(JAM_PICKING) AS SELESAI_PICK,
                        COUNT(PRDCD) AS ITEM
                    FROM 
                        IGRPWT.DPD_IDM_ORA 
                    WHERE 
                        TO_CHAR(TO_DATE(TGLUPD, 'yyyymmdd'), 'DD-MM-YYYY') BETWEEN :start AND :end
                        AND FMRCID = '3'
                    GROUP BY 
                        FMNDOC, NOPICKING, FMKCAB, TO_CHAR(TO_DATE(TGLUPD, 'yyyymmdd'), 'DD-MM-YYYY'), KODEZONA, JAM_UPLOAD
                ) AA
                LEFT JOIN (
                    SELECT 
                        PICO_NOPICK, 
                        PICO_KODETOKO, 
                        COUNT(PICO_BARCODEKOLI) AS JML_KOLI 
                    FROM 
                        IGRPWT.PICKING_CONTAINER 
                    WHERE 
                        PICO_RECORDID = '3' 
                    GROUP BY 
                        PICO_NOPICK, PICO_KODETOKO
                ) AS BB 
                ON 
                    NOPICKING||FMKCAB = BB.PICO_NOPICK||BB.PICO_KODETOKO 
                LEFT JOIN (
                    SELECT 
                        RPB_CREATE_DT AS TGL_DSPB,
                        TO_CHAR(RPB_CREATE_DT, 'HH24:MI:SS') AS JAM_DSPB,
                        COUNT(RPB_NOKOLI) AS JML_KOLI,
                        RPB_NODOKUMEN,
                        RPB_IDSURATJALAN,
                        PICO_NOSJ,
                        NONOPI,
                        KDTK,
                        PICO_NAMATOKO,
                        ZONA
                    FROM (
                        SELECT 
                            RPB_CREATE_DT,
                            TO_CHAR(RPB_CREATE_DT, 'HH24:MI:SS') AS JAM_DSPB,
                            RPB_NOKOLI,
                            COUNT(RPB_PLU1) AS ITEM_COUNT,
                            RPB_NODOKUMEN,
                            RPB_IDSURATJALAN,
                            RPB_KODEOMI
                        FROM 
                            IGRPWT.TBTR_REALPB 
                        WHERE 
                            TO_CHAR(RPB_CREATE_DT, 'DD-MM-YYYY') BETWEEN :start AND :end
                        GROUP BY 
                            RPB_CREATE_DT, TO_CHAR(RPB_CREATE_DT, 'HH24:MI:SS'), RPB_NOKOLI, RPB_NODOKUMEN, RPB_IDSURATJALAN, RPB_KODEOMI
                    ) AS CC
                    LEFT JOIN (
                        SELECT 
                            PICO_NOPICK AS NONOPI, 
                            PICO_KODETOKO AS KDTK,
                            PICO_NAMATOKO,
                            PICO_BARCODEKOLI, 
                            SUBSTRING(PICO_CONTAINERZONA FROM 5 FOR 6) AS ZONA, 
                            PICO_NOSJ 
                        FROM 
                            IGRPWT.PICKING_CONTAINER 
                        WHERE 
                            PICO_RECORDID = '3'
                    ) DD
                    ON 
                        RPB_NOKOLI||RPB_KODEOMI = PICO_BARCODEKOLI||KDTK 
                    GROUP BY 
                        RPB_CREATE_DT, JAM_DSPB, RPB_NODOKUMEN, RPB_IDSURATJALAN, PICO_NOSJ, NONOPI, KDTK, PICO_NAMATOKO, ZONA
                ) AS EE
                ON 
                    NOPICKING||FMKCAB||KODEZONA = NONOPI||KDTK||ZONA
                WHERE 
                    FMKCAB IN (
                        SELECT TKO_KODEOMI 
                        FROM IGRPWT.TBMASTER_TOKOIGR 
                        WHERE TKO_KODESBU = :kodeSBU
                    )
                    AND JAM_DSPB IS NOT NULL
                ORDER BY 
                    MULAI_PICK, SELESAI_PICK";

        $params = [
            'start' => $start,
            'end' => $end,
            'kodeSBU' => $kodeSBU
        ];

        $results = DB::select($sql, $params);

        return view('monitoring.partials.timepicking_h', [
            'data' => $results,
            'flag' => $flag,
            'start' => $start,
            'end' => $end
        ]);
    }
    

    public function monitoringpicking()
    {
        $today = Carbon::today()->toDateString();

        $query = "WITH prod AS (
                                SELECT 
                                    PRD_PRDCD,
                                    PRD_FRAC,
                                    PRC_MINORDER,
                                    PRC_GROUP
                                FROM IGRPWT.TBMASTER_PRODMAST 
                                LEFT JOIN IGRPWT.TBMASTER_PRODCRM 
                                    ON PRD_PRDCD = PRC_PLUIGR
                            ),
                            pbomi_agg AS (
                                SELECT 
                                    PBO_NOPB,
                                    PBO_NOPICKING,
                                    PBO_NOSJ,

                                    -- REALISASI 0
                                    COUNT(
                                        CASE 
                                            WHEN PBO_QTYREALISASI = 0
                                                AND PBO_QTYORDER >= COALESCE(NULLIF(PRC_MINORDER,''),'0')::integer
                                            THEN 1
                                        END
                                    ) AS REALISASI_0,

                                    -- SEBAGIAN
                                    COUNT(
                                        CASE 
                                            WHEN PBO_QTYREALISASI > 0
                                                AND PBO_QTYREALISASI <> PBO_QTYORDER
                                                AND PBO_QTYORDER >= COALESCE(NULLIF(PRC_MINORDER,''),'0')::integer
                                            THEN 1
                                        END
                                    ) AS SEBAGIAN

                                FROM IGRPWT.TBMASTER_PBOMI 
                                LEFT JOIN prod 
                                    ON SUBSTR(PBO_PLUIGR,1,6)||'0' = prod.PRD_PRDCD
                                    AND (
                                        (PBO_KODESBU = 'I' AND prod.PRC_GROUP = 'I')
                                        OR
                                        (PBO_KODESBU = 'O' AND prod.PRC_GROUP = 'O')
                                    )
                                WHERE DATE(PBO_CREATE_DT) = :today
                                AND PBO_RECORDID >= '2'
                                GROUP BY PBO_NOPB, PBO_NOPICKING, PBO_NOSJ
                            )
                            SELECT 
                                HPBI_CREATE_DT,
                                TKO_NAMASBU,
                                HPBI_NOPB,
                                HPBI_KODETOKO,
                                TKO_NAMAOMI,
                                HPBI_NOSJ,
                                HPBI_NOPICKING,
                                cc.REALISASI_0,
                                cc.SEBAGIAN
                            FROM IGRPWT.TBTR_HEADER_PBIDM h
                            LEFT JOIN IGRPWT.Tbmaster_Tokoigr t 
                                ON h.Hpbi_Kodetoko = t.Tko_Kodeomi
                            LEFT JOIN pbomi_agg cc 
                                ON h.HPBI_NOPB = cc.PBO_NOPB 
                                AND h.HPBI_NOPICKING = cc.PBO_NOPICKING
                            WHERE DATE(h.HPBI_CREATE_DT) = :today
                            AND h.HPBI_KODETOKO IN (
                                    SELECT TKO_KODEOMI 
                                    FROM IGRPWT.TBMASTER_TOKOIGR
                            )
                            ORDER BY h.HPBI_NOPICKING";

        $monitoringData = DB::select($query, ['today' => $today]);

        return view('monitoring.monitoringpicking', compact('monitoringData'));
    }

    
    public function monitoring_showItem(Request $request)
    {
        $noPick = $request->input('noPick');
        $kdToko = $request->input('kdToko');
        $nmToko = $request->input('nmToko');
        $statusToko = $request->input('statusToko');

        $today = Carbon::today()->toDateString();
        $ff = '';

        if ($statusToko == 'INDOMARET') {
            $ff = "AND PBO_QTYORDER >= ai.prc_minorder_I";
        } elseif ($statusToko == 'OMI') {
            $ff = "AND PBO_QTYORDER >= ao.prc_minorder_O";
        }

        $query = "WITH prodcrm_I AS (
                    SELECT DISTINCT ON (prc_pluigr) 
                        prc_pluigr, 
                        COALESCE(NULLIF(prc_minorder, ''), '0')::integer AS prc_minorder_I
                    FROM IGRPWT.tbmaster_prodcrm
                    WHERE prc_group = 'I'
                    ORDER BY prc_pluigr
                ),
                prodcrm_O AS (
                    SELECT DISTINCT ON (prc_pluigr) 
                        prc_pluigr, 
                        COALESCE(NULLIF(prc_minorder, ''), '0')::integer AS prc_minorder_O
                    FROM IGRPWT.tbmaster_prodcrm
                    WHERE prc_group = 'O'
                    ORDER BY prc_pluigr
                ),
                prodmast_dedup AS (
                    SELECT DISTINCT ON (prd_prdcd) 
                        prd_prdcd, 
                        prd_frac, 
                        prd_deskripsipanjang
                    FROM IGRPWT.tbmaster_prodmast
                    ORDER BY prd_prdcd
                ),
                idmora_dedup AS (
                    SELECT DISTINCT ON (prdcd) 
                        kodezona, 
                        SUBSTR(prdcd, 1, 6) || '0' AS prdcd_norm
                    FROM IGRPWT.DPD_IDM_ORA
                    ORDER BY prdcd
                )
                SELECT  
                        cc.kodezona,
                        pbo.pbo_nopicking,
                        SUBSTR(pbo.pbo_pluigr, 1, 6) || '0' AS plu,
                        bb.prd_deskripsipanjang,
                        pbo.pbo_qtyorder,
                        pbo.pbo_qtyrealisasi,
                        bb.prd_frac,
                        CASE 
                            WHEN pbo.pbo_kodesbu = 'I' THEN coalesce(ai.prc_minorder_I,0)
                            WHEN pbo.pbo_kodesbu = 'O' THEN coalesce(ao.prc_minorder_O,0)
                        END AS prc_minorder,

                        CASE
                            WHEN pbo.pbo_qtyrealisasi = 0 
                                AND pbo.pbo_kodesbu = 'I' 
                                THEN 'REALISASI 0'

                            WHEN pbo.pbo_qtyrealisasi = 0 
                                AND pbo.pbo_kodesbu = 'O' 
                                AND pbo.pbo_qtyorder >= coalesce(ao.prc_minorder_O,0)
                                THEN 'REALISASI 0'

                            WHEN pbo.pbo_qtyrealisasi > 0 
                                AND pbo.pbo_qtyrealisasi < pbo.pbo_qtyorder 
                                AND pbo.pbo_qtyorder >= ai.prc_minorder_I
                                AND pbo.pbo_kodesbu = 'I' 
                                THEN 'SEBAGIAN'

                            WHEN pbo.pbo_qtyrealisasi > 0 
                                AND pbo.pbo_qtyrealisasi <> pbo.pbo_qtyorder 
                                AND pbo.pbo_qtyorder >= coalesce(ao.prc_minorder_O,0)
                                AND pbo.pbo_kodesbu = 'O'
                                THEN 'SEBAGIAN'
                        END AS status
                FROM IGRPWT.tbmaster_pbomi pbo
                LEFT JOIN prodcrm_I ai 
                    ON SUBSTR(pbo.pbo_pluigr, 1, 6) || '0' = ai.prc_pluigr
                LEFT JOIN prodcrm_O ao 
                    ON SUBSTR(pbo.pbo_pluigr, 1, 6) || '0' = ao.prc_pluigr
                LEFT JOIN prodmast_dedup bb 
                    ON SUBSTR(pbo.pbo_pluigr, 1, 6) || '0' = bb.prd_prdcd
                LEFT JOIN idmora_dedup cc 
                    ON bb.prd_prdcd = cc.prdcd_norm
                WHERE date(pbo.pbo_create_dt) = :today
                AND pbo.pbo_nopicking = :noPick
                AND pbo.pbo_qtyrealisasi <> pbo.pbo_qtyorder
                AND pbo.pbo_recordid >= '2'
                {$ff}
                ORDER BY status, cc.kodezona";

        $itemData = DB::select($query, [
            'today' => $today, 
            'noPick' => $noPick
        ]);

        return view('monitoring.partials.item_details', compact('itemData', 'kdToko', 'nmToko', 'noPick'));
    }
}
