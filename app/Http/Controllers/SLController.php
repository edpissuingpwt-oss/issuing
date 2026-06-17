<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SLController extends Controller
{
    public function sl()
    {
        $listplu = DB::connection('pgsql')->table(DB::raw("(select prd_prdcd, 
            prd_kodetag, 
            prd_deskripsipanjang
            from igrpwt.tbmaster_prodmast
            where prd_prdcd like '%0'
            and prd_flagigr = 'Y') pr"))
        ->select(DB::raw("*"))
        ->get();

        return view('edp.sl', compact('listplu'));
    }

    public function result(Request $request)
    {
        $plu = trim($request->plu);

        if (!$plu) {
            return redirect()->back()->with('error', 'PLU wajib diisi');
        }

        // Format jadi 7 digit
        $suffik = str_pad($plu, 7, '0', STR_PAD_LEFT);

        /* ======================================================
         * QUERY 1 – Data Master Produk
         * ====================================================== */
        $q1 = DB::select("
            SELECT DISTINCT prd_prdcd AS plu,
                PRD_DESKRIPSIPENDEK AS desk,
                prd_unit AS unit,
                prd_frac AS frac,
                prd_kodetag AS tag,
                MAX(mstd_tgldoc) AS last_bpb
            FROM igrpwt.Tbmaster_Prodmast
            LEFT JOIN igrpwt.tbtr_mstran_d ON prd_prdcd = mstd_prdcd
            WHERE prd_prdcd LIKE '%0'
            AND prd_prdcd = ?
            AND MSTD_TYPETRN = 'B'
            GROUP BY prd_prdcd, PRD_DESKRIPSIPENDEK, prd_unit, prd_frac, prd_kodetag
        ", [$suffik]);

        /* ======================================================
         * QUERY 2 – Flag IDM/IGR
         * ====================================================== */
        $q2 = DB::select("
            SELECT st_prdcd plu_flag,
            CASE
                WHEN prd_flagidm = 'Y' AND prd_flagigr = 'N' THEN 'IDM ONLY'
                WHEN prd_flagidm = 'N' AND prd_flagigr = 'Y' THEN 'IGR ONLY'
                ELSE 'IGR+IDM' END AS flag
            FROM igrpwt.tbmaster_stock
            LEFT JOIN igrpwt.tbmaster_prodmast ON st_prdcd = prd_prdcd
            WHERE st_lokasi = '01' AND st_prdcd = ?
        ", [$suffik]);

        /* ======================================================
         * QUERY 3 – Lokasi Plano + Expired + User
         * ====================================================== */
        $q3 = DB::select("
            SELECT 
            lks_koderak || '.' || lks_kodesubrak || '.' || lks_tiperak || '.' || lks_shelvingrak || '.' || Lks_Nourut AS lokasi,
            Lks_Qty AS qty_plano,
            COALESCE(to_char(lks_expdate, 'DD-MON-YYYY'), '0') AS exp,
            lks_modify_by as modif
            FROM igrpwt.Tbmaster_Lokasi 
            WHERE lks_prdcd = ?
            ORDER BY lks_koderak, lks_kodesubrak, lks_tiperak, lks_shelvingrak, lks_nourut
        ", [$suffik]);

        $total_qty = array_sum(array_column($q3, 'qty_plano'));


        /* ======================================================
         * QUERY 4 – Picking PB OMI 7 Hari
         * ====================================================== */
        $q4 = DB::selectOne("
            SELECT SUM(PBO_QTYREALISASI) AS qty_realisasi
            FROM igrpwt.tbmaster_pbomi
            WHERE PBO_NOKOLI IS NOT NULL
            AND PBO_RECORDID = '4'
            AND PBO_CREATE_DT::date >= (CURRENT_DATE - INTERVAL '7 days')
            AND LEFT(PBO_PLUIGR, 6) || '0' = ?
            AND NOT EXISTS (
                SELECT 1
                FROM igrpwt.tbtr_realpb
                WHERE PBO_NOKOLI || PBO_KODEOMI || PBO_PLUIGR || PBO_QTYREALISASI =
                RPB_NOKOLI || RPB_KODEOMI || RPB_PLU2 || RPB_QTYREALISASI
            )
        ", [$suffik]);

        $qty_picking = $q4->qty_realisasi ?? 0;


        /* ======================================================
         * QUERY 5 – LPP & Intransit
         * ====================================================== */
        $q5 = DB::selectOne("
            SELECT st_saldoakhir, -st_intransit AS intransit
            FROM igrpwt.Tbmaster_Stock
            WHERE St_Lokasi = '01' AND st_prdcd = ?
        ", [$suffik]);

        $lpp = $q5->st_saldoakhir ?? 0;
        $intransit = $q5->intransit ?? 0;

        /* ======================================================
         * QUERY 6 – SONAS
         * ====================================================== */
        $q6 = DB::selectOne("
            SELECT SONAS_17
            FROM (
                SELECT A.SOP_PRDCD,
                    SUM(COALESCE(A.selisih_so, 0) + COALESCE(B.ADJ_QTY, 0)) AS SONAS_17
                FROM (
                    SELECT SOP_PRDCD,
                        COALESCE(SUM(COALESCE(SOP_QTYSO, 0) - COALESCE(SOP_QTYLPP, 0)), 0) AS selisih_so
                    FROM igrpwt.tbtr_ba_stockopname
                    WHERE SOP_TGLSO = '2023-07-16'
                    AND SOP_LOKASI = '01'
                    GROUP BY SOP_PRDCD
                ) A
                LEFT JOIN (
                    SELECT ADJ_PRDCD,
                        COALESCE(SUM(ADJ_QTY), 0) AS ADJ_QTY
                    FROM igrpwt.TBTR_ADJUSTSO
                    WHERE ADJ_TGLSO = '2023-07-16'
                    AND ADJ_LOKASI = '01'
                    GROUP BY ADJ_PRDCD
                ) B ON A.SOP_PRDCD = B.ADJ_PRDCD
                GROUP BY A.SOP_PRDCD
            ) AS x
            WHERE SOP_PRDCD = ?
        ", [$suffik]);

        $sonas = $q6->sonas_17 ?? 0;


        /* ======================================================
         * QUERY 7 – Intransit Klik (Optional)
         * ====================================================== */
        $q7 = DB::selectOne("
            SELECT SUM(OBI_QTYINTRANSIT) AS intransitklik 
            FROM igrpwt.TBTR_OBI_D 
            WHERE OBI_PRDCD LIKE ?
        ", [substr($suffik, 0, 6) . '%']);

        $intransit_klik = $q7->intransitklik ?? 0;


        /* ======================================================
         * QUERY 8 – LPP per LOKASI (01,02,03)
         * ====================================================== */
        $q8 = DB::select("
            SELECT St_Lokasi, st_saldoakhir AS stock
            FROM igrpwt.Tbmaster_Stock
            WHERE St_Lokasi IN ('01','02','03') AND st_prdcd = ?
            ORDER BY ST_LOKASI ASC
        ", [$suffik]);


        /* ======================================================
         * QUERY HADIAH
         * ====================================================== */
        $hadiah = DB::select("
            SELECT
                A.GFH_KODEPROMOSI,
                A.GFH_NAMAPROMOSI,
                A.GFH_TGLAWAL,
                A.GFH_TGLAKHIR,
                A.GFH_KETHADIAH,
                COALESCE(C.GFA_ALOKASIJUMLAH, '0') AS alokasi,
                COALESCE(B.keluar, '0') AS keluar,
                SUM(COALESCE(C.GFA_ALOKASIJUMLAH, 0) - COALESCE(B.keluar, 0)) AS sisa
            FROM igrpwt.tbtr_gift_hdr A
            LEFT JOIN (
                SELECT kd_promosi, SUM(jmlh_hadiah) AS keluar
                FROM igrpwt.m_gift_h
                GROUP BY kd_promosi
            ) B ON A.GFH_KODEPROMOSI = B.kd_promosi
            LEFT JOIN igrpwt.TBTR_GIFT_ALOKASI C ON A.GFH_KODEPROMOSI = C.GFA_KODEPROMOSI
            WHERE A.GFH_KETHADIAH = ?
            AND CURRENT_DATE BETWEEN A.GFH_TGLAWAL AND A.GFH_TGLAKHIR
            GROUP BY
                A.GFH_KODEPROMOSI,
                A.GFH_NAMAPROMOSI,
                A.GFH_TGLAWAL,
                A.GFH_TGLAKHIR,
                A.GFH_KETHADIAH,
                C.GFA_ALOKASIJUMLAH,
                B.keluar
            ORDER BY A.GFH_KETHADIAH, A.GFH_TGLAKHIR DESC
        ", [$suffik]);


        /* ======================================================
         * Hitung Selisih
         * ====================================================== */
        $selisih = ($total_qty + $qty_picking - $lpp);

        return view('edp.sl_result', compact(
            'plu', 'suffik', 'q1', 'q2', 'q3', 'total_qty',
            'qty_picking', 'lpp', 'intransit', 'sonas',
            'intransit_klik', 'q8', 'selisih', 'hadiah'
        ));
    }
}
