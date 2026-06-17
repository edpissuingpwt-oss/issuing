<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class AllproductdsiController extends Controller
{
    public function dsiall()
    {
        $query = "SELECT
                            ALAMAT,
                            PLUPROD AS PLUIGR,
                            PRD_PLUMCG AS PLUIDM,
                            PRD_DESKRIPSIPANJANG AS DESK,
                            PRD_FRAC AS FRAC,
                            FLAG,
                            COALESCE(DSI, 0) AS DSI,
                            COALESCE(LPA, 0) AS LPA,
                            COALESCE(LPP, 0) AS LPP,
                            ST_SALES,
                            PKMT,
                            MINOR,
                            HGB_KODESUPPLIER AS KODE_SUPPLIER,
                            SUP_NAMASUPPLIER AS NAMA_SUPPLIER,
                            HGB_HRGBELI AS HARGA_BELI,
                            HISTORY_PB_EPROC
                        FROM
                        (
                            SELECT 
                                PRD_PRDCD AS PLUPROD,
                                PRD_PLUMCG,
                                PRD_DESKRIPSIPANJANG,
                                PRD_FRAC 
                            FROM 
                                igrpwt.TBMASTER_PRODMAST 
                            WHERE 
                                PRD_PRDCD LIKE '%0' 
                        ) a
                        LEFT JOIN (SELECT * FROM (SELECT PRD_PRDCD AS PLU_FLAG,
                                CASE
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYYYYYY' THEN 'NAS-IGR+IDM+OMI+MR.BRD+K.IGR+DEPO'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYYYYYN' THEN 'NAS-IGR+IDM+OMI+MR.BRD+K.IGR'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYYYYNN' THEN 'NAS-IGR+IDM+OMI+MR.BRD'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYYYNYY' THEN 'NAS-IGR+IDM+OMI+K.IGR+DEPO'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYYYNYN' THEN 'NAS-IGR+IDM+OMI+K.IGR'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYYYNNY' THEN 'NAS-IGR+IDM+OMI+DEPO'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYYYNNN' THEN 'NAS-IGR+IDM+OMI'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYYNYYY' THEN 'NAS-IGR+IDM+MR.BRD+K.IGR+DEPO'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYYNNYY' THEN 'NAS-IGR+IDM+K.IGR+DEPO'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYYNNYN' THEN 'NAS-IGR+IDM+K.IGR'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYYNNNY' THEN 'NAS-IGR+IDM+DEPO'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYYNNNN' THEN 'NAS-IGR+IDM'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYNYNYY' THEN 'NAS-IGR+OMI+K.IGR+DEPO'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYNYNYN' THEN 'NAS-IGR+OMI+K.IGR'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYNYNNY' THEN 'NAS-IGR+OMI+DEPO'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYNYNNN' THEN 'NAS-IGR+OMI'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYNNYYN' THEN 'NAS-IGR+MR.BRD+K.IGR'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYNNYNN' THEN 'NAS-IGR+MR.BRD'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYNNNYY' THEN 'NAS-IGR+K.IGR+DEPO'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYNNNYN' THEN 'NAS-IGR+K.IGR'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYNNNNY' THEN 'NAS-IGR+DEPO'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYNNNNN' THEN 'NAS-IGR'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YNYYNYY' THEN 'NAS-IDM+OMI+K.IGR+DEPO'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YNYYNYN' THEN 'NAS-IDM+OMI+K.IGR'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YNYYNNY' THEN 'NAS-IDM+OMI+DEPO'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YNYYNNN' THEN 'NAS-IDM+OMI'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YNYNNYY' THEN 'NAS-IDM+K.IGR+DEPO'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YNYNNYN' THEN 'NAS-IDM+K.IGR'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YNYNNNY' THEN 'NAS-IDM+DEPO'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YNYNNNN' THEN 'NAS-IDM'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YNNYYNN' THEN 'NAS-OMI+MR.BRD'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YNNYNYN' THEN 'NAS-OMI+K.IGR'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YNNYNNN' THEN 'NAS-OMI'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YNNNYNN' THEN 'NAS-MR.BRD'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YNNNNYN' THEN 'NAS-K.IGR'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YNNNNNN' THEN 'NAS'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYYYYYY' THEN 'IGR+IDM+OMI+MR.BRD+K.IGR+DEPO'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYYYYYN' THEN 'IGR+IDM+OMI+MR.BRD+K.IGR'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYYYNYY' THEN 'IGR+IDM+OMI+K.IGR+DEPO'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYYYNYN' THEN 'IGR+IDM+OMI+K.IGR'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYYYNNY' THEN 'IGR+IDM+OMI+DEPO'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYYYNNN' THEN 'IGR+IDM+OMI'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYYNNYY' THEN 'IGR+IDM+K.IGR+DEPO'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYYNNYN' THEN 'IGR+IDM+K.IGR'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYYNNNY' THEN 'IGR+IDM+DEPO'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYYNNNN' THEN 'IGR+IDM'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYNYNYY' THEN 'IGR+OMI+K.IGR+DEPO'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYNYNYN' THEN 'IGR+OMI+K.IGR'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYNYNNN' THEN 'IGR+OMI'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYNNYYN' THEN 'IGR+MR.BRD+K.IGR'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYNNNYY' THEN 'IGR+K.IGR+DEPO'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYNNNYN' THEN 'IGR+K.IGR'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYNNNNY' THEN 'IGR+DEPO'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYNNNNN' THEN 'IGR'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NNYYNYY' THEN 'IDM+OMI+K.IGR+DEPO'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NNYYNYN' THEN 'IDM+OMI+K.IGR'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NNYYNNY' THEN 'IDM+OMI+DEPO'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NNYYNNN' THEN 'IDM+OMI'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NNYNNYY' THEN 'IDM+K.IGR+DEPO'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NNYNNYN' THEN 'IDM+K.IGR'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NNYNNNY' THEN 'IDM+DEPO'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NNYNNNN' THEN 'IDM'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NNNYNYN' THEN 'OMI+K.IGR'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NNNYNNN' THEN 'OMI'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NNNNNYN' THEN 'K.IGR'
                                WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NNNNNNN' THEN 'TIDAK PUNYA FLAG'
                                ELSE 'BELUM ADA FLAG'
                                END AS FLAG
                                FROM(SELECT prd_prdcd,prd_plumcg,
                                    COALESCE(PRD_FLAGNAS,'N') AS NAS,
                                    COALESCE(PRD_FLAGIGR,'N') AS IGR,
                                    COALESCE(PRD_FLAGIDM,'N') AS IDM,
                                    COALESCE(PRD_FLAGOMI,'N') AS OMI,
                                    COALESCE(PRD_FLAGBRD,'N') AS BRD,
                                    COALESCE(PRD_FLAGOBI,'N') AS K_IGR,
                                    case when prd_plumcg in (select PLUIDM from igrpwt.DEPO_LIST_IDM ) THEN 'Y' ELSE 'N' END AS DEPO
                                    FROM igrpwt.TBMASTER_PRODMAST WHERE PRD_PRDCD LIKE '%0' AND PRD_DESKRIPSIPANJANG IS NOT NULL) FLAGX)FLAGY) FLAG ON PLUPROD = FLAG.PLU_FLAG
    --                    LEFT JOIN
    --                    (
    --                        SELECT 
    --                            RSL_PRDCD AS PLURSL,
    --                            RSL_QTY_10 AS OCT,
    --                            RSL_QTY_09 AS SEPT,
    --                            RSL_QTY_08 AS AGST,
    --                            RSL_QTY_07 AS JUL,
    --                            RSL_QTY_06 AS JUN,
    --                            RSL_QTY_05 AS MEI,
    --                            RSL_QTY_04 AS APR,
    --                            RSL_QTY_03 AS MAR,
    --                            RSL_QTY_02 AS FEB,
    --                            RSL_QTY_01 AS JAN 
    --                        FROM 
    --                            igrpwt.TBTR_REKAPSALESBULANAN 
    --                        WHERE 
    --                            RSL_GROUP = '01' --MB
    --                            RSL_GROUP = '02' --OMI
    --                            RSL_GROUP = '03' --MM
    --                            RSL_GROUP = '04' --IDM
    --                    ) b ON PLUPROD = PLURSL
                        LEFT JOIN
                        (
                            SELECT 
                                PKM_PRDCD AS PLUPKM,
                                PKM_PKMT AS PKMT,
                                PKM_MINORDER AS MINOR 
                            FROM 
                                igrpwt.TBMASTER_KKPKM
                        ) c ON PLUPROD = PLUPKM
                        LEFT JOIN
                        (
                            SELECT 
                                HGB_PRDCD,
                                HGB_KODESUPPLIER,
                                SUP_NAMASUPPLIER,
                                HGB_HRGBELI 
                            FROM
                            (
                                SELECT 
                                    SUP_KODESUPPLIER,
                                    SUP_NAMASUPPLIER 
                                FROM 
                                    igrpwt.TBMASTER_SUPPLIER
                            ) d
                            RIGHT JOIN
                            (
                                SELECT 
                                    HGB_PRDCD,
                                    HGB_KODESUPPLIER,
                                    HGB_HRGBELI 
                                FROM 
                                    igrpwt.TBMASTER_HARGABELI 
                                WHERE 
                                    HGB_TIPE = '2'
                            ) e ON HGB_KODESUPPLIER = SUP_KODESUPPLIER
                        ) z ON HGB_PRDCD = PLUPROD
                        LEFT JOIN
                        (
                            SELECT 
                                PBP_PRDCD,
                                SUM(PBP_QTYPB) AS HISTORY_PB_EPROC 
                            FROM 
                                IGRPWT.TBTR_PB_PROCUREMENT 
                            GROUP BY 
                                PBP_PRDCD
                        ) f ON PLUPROD = PBP_PRDCD
                        LEFT JOIN 
                        (
                            SELECT 
                                ST_PRDCD,
                                ST_SALDOAKHIR AS LPP,
                                COALESCE(ST_SALDOAWAL, 0) AS LPA,
                                ST_SALES,
                                ROUND((((COALESCE(ST_SALDOAWAL, 0) + ST_SALDOAKHIR) / 2) / ST_SALES) * 
                            EXTRACT(DAY FROM (CURRENT_DATE - date_trunc('month', CURRENT_DATE) + INTERVAL '1 day'))) AS DSI
                            FROM 
                                igrpwt.TBMASTER_STOCK 
                            WHERE 
                                ST_LOKASI = '01' 
                                AND ST_SALES <> '0'
                        ) g ON PLUPROD = ST_PRDCD
                        LEFT JOIN
                        (
                            SELECT 
                                LKS_KODERAK || '.' || LKS_KODESUBRAK || '.' || LKS_TIPERAK || '.' || LKS_SHELVINGRAK || '.' || LKS_NOURUT AS ALAMAT,
                                LKS_PRDCD 
                            FROM 
                                igrpwt.TBMASTER_LOKASI 
                            WHERE 
                                LKS_KODERAK LIKE 'D%' 
                                AND LKS_TIPERAK = 'B'
                        ) h ON LKS_PRDCD = PLUPROD
                        LEFT JOIN (SELECT 
        sl_prdcd_po             AS sl_prdcd_po,
        sl_nama_barang          AS sl_nama_barang,

        -- periode 1 (3 bulan lalu)
        COUNT(CASE WHEN date_trunc('month', sl_tanggal_po) = date_trunc('month', CURRENT_DATE) - interval '3 month' THEN sl_nomor_po END)   AS sl_nomor_po,
        COUNT(CASE WHEN date_trunc('month', sl_tanggal_po) = date_trunc('month', CURRENT_DATE) - interval '3 month' THEN sl_nomor_bpb END) AS sl_nomor_bpb,
        SUM(CASE WHEN date_trunc('month', sl_tanggal_po) = date_trunc('month', CURRENT_DATE) - interval '3 month' THEN sl_qty_po ELSE 0 END)   AS sl_qty_po,
        SUM(CASE WHEN date_trunc('month', sl_tanggal_po) = date_trunc('month', CURRENT_DATE) - interval '3 month' THEN sl_rph_po ELSE 0 END)   AS sl_rph_po,
        SUM(CASE WHEN date_trunc('month', sl_tanggal_po) = date_trunc('month', CURRENT_DATE) - interval '3 month' THEN sl_qty_bpb ELSE 0 END)  AS sl_qty_bpb,
        SUM(CASE WHEN date_trunc('month', sl_tanggal_po) = date_trunc('month', CURRENT_DATE) - interval '3 month' THEN sl_rph_bpb ELSE 0 END)  AS sl_rph_bpb,

        -- periode 2 (2 bulan lalu)
        COUNT(CASE WHEN date_trunc('month', sl_tanggal_po) = date_trunc('month', CURRENT_DATE) - interval '2 month' THEN sl_nomor_po END)   AS sl_nomor_po2,
        COUNT(CASE WHEN date_trunc('month', sl_tanggal_po) = date_trunc('month', CURRENT_DATE) - interval '2 month' THEN sl_nomor_bpb END) AS sl_nomor_bpb2,
        SUM(CASE WHEN date_trunc('month', sl_tanggal_po) = date_trunc('month', CURRENT_DATE) - interval '2 month' THEN sl_qty_po ELSE 0 END)   AS sl_qty_po2,
        SUM(CASE WHEN date_trunc('month', sl_tanggal_po) = date_trunc('month', CURRENT_DATE) - interval '2 month' THEN sl_rph_po ELSE 0 END)   AS sl_rph_po2,
        SUM(CASE WHEN date_trunc('month', sl_tanggal_po) = date_trunc('month', CURRENT_DATE) - interval '2 month' THEN sl_qty_bpb ELSE 0 END)  AS sl_qty_bpb2,
        SUM(CASE WHEN date_trunc('month', sl_tanggal_po) = date_trunc('month', CURRENT_DATE) - interval '2 month' THEN sl_rph_bpb ELSE 0 END)  AS sl_rph_bpb2,

        -- periode 3 (1 bulan lalu)
        COUNT(CASE WHEN date_trunc('month', sl_tanggal_po) = date_trunc('month', CURRENT_DATE) - interval '1 month' THEN sl_nomor_po END)   AS sl_nomor_po3,
        COUNT(CASE WHEN date_trunc('month', sl_tanggal_po) = date_trunc('month', CURRENT_DATE) - interval '1 month' THEN sl_nomor_bpb END) AS sl_nomor_bpb3,
        SUM(CASE WHEN date_trunc('month', sl_tanggal_po) = date_trunc('month', CURRENT_DATE) - interval '1 month' THEN sl_qty_po ELSE 0 END)   AS sl_qty_po3,
        SUM(CASE WHEN date_trunc('month', sl_tanggal_po) = date_trunc('month', CURRENT_DATE) - interval '1 month' THEN sl_rph_po ELSE 0 END)   AS sl_rph_po3,
        SUM(CASE WHEN date_trunc('month', sl_tanggal_po) = date_trunc('month', CURRENT_DATE) - interval '1 month' THEN sl_qty_bpb ELSE 0 END)  AS sl_qty_bpb3,
        SUM(CASE WHEN date_trunc('month', sl_tanggal_po) = date_trunc('month', CURRENT_DATE) - interval '1 month' THEN sl_rph_bpb ELSE 0 END)  AS sl_rph_bpb3,

        MIN(sl_kode_supplier)   AS sl_kode_supplier,
        MIN(sl_nama_supplier)   AS sl_nama_supplier,
        MIN(sl_stock_qty)       AS sl_stock_qty,
        MIN(sl_lastcost)        AS sl_lastcost,
        MIN(sl_avgcost)         AS sl_avgcost
    FROM (
        SELECT 
            po.tpod_nopo   AS sl_nomor_po,
            poh.tpoh_tglpo AS sl_tanggal_po,
            mst.mstd_nodoc AS sl_nomor_bpb,
            mst.mstd_tgldoc AS sl_tanggal_bpb,
            po.tpod_prdcd AS sl_prdcd_po,
            prd.prd_deskripsipanjang AS sl_nama_barang,
            po.tpod_qtypo AS sl_qty_po,
            po.tpod_gross + po.tpod_ppn AS sl_rph_po,
            COALESCE(mst.mstd_qty,0) AS sl_qty_bpb,
            COALESCE(mst.mstd_gross - mst.mstd_discrph + mst.mstd_ppnrph,0) AS sl_rph_bpb,
            poh.tpoh_kodesupplier AS sl_kode_supplier,
            sup.sup_namasupplier AS sl_nama_supplier,
            stk.st_saldoakhir AS sl_stock_qty,
            stk.st_lastcost AS sl_lastcost,
            stk.st_avgcost AS sl_avgcost
        FROM igrpwt.tbtr_po_d po
        LEFT JOIN igrpwt.tbtr_mstran_d mst ON po.tpod_prdcd = mst.mstd_prdcd
            AND po.tpod_nopo = mst.mstd_nopo
        LEFT JOIN igrpwt.tbtr_po_h poh ON po.tpod_nopo = poh.tpoh_nopo
        LEFT JOIN igrpwt.tbmaster_prodmast prd ON po.tpod_prdcd = prd.prd_prdcd
        LEFT JOIN igrpwt.tbmaster_supplier sup ON poh.tpoh_kodesupplier = sup.sup_kodesupplier
        LEFT JOIN (SELECT * FROM igrpwt.tbmaster_stock WHERE st_lokasi = '01') stk ON po.tpod_prdcd = stk.st_prdcd
        WHERE mst.mstd_recordid IS NULL
        AND (po.tpod_recordid IS NULL OR po.tpod_recordid = '2')
    ) p
    WHERE sl_tanggal_po >= date_trunc('month', CURRENT_DATE) - interval '3 month'
    AND sl_tanggal_po <  date_trunc('month', CURRENT_DATE)
    GROUP BY sl_prdcd_po, sl_nama_barang
    ORDER BY sl_prdcd_po) sl ON PLUPROD = sl_prdcd_po
    WHERE ALAMAT IS NOT NULL";
        
        $dsiall = DB::select($query);

        return view('master.dsiall', compact('dsiall'));
    }
}