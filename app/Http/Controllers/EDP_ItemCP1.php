<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EDP_ItemCP1 extends Controller
{
    public function itemcp1()
    {
        return view('edp.itemcp1');
    }

    public function getData(Request $request)
    {
        $jenis = $request->input('jenis');


        if ($jenis === 'stock') {
            $query = "SELECT 
                            m.PRD_KODEIGR AS CABANG_IGR,
                            'PWT' AS CAB,
                            TO_CHAR(CURRENT_DATE, 'DD-MM-YYYY') AS TANGGAL,
                            m.PRD_PRDCD AS PLU_igr,
                            m.PRD_PLUMCG AS PLU_mcg,
                            m.prd_deskripsipanjang AS deskripsi,
                            m.prd_unit || '/' || m.prd_frac  AS frac,
                            st.st_avgcost as acost,
                            st.ST_SALDOAKHIR AS LPP_Qty,
                            (st.st_saldoakhir * st.st_avgcost) as lpp_rph
                        FROM IGRPWT.TBMASTER_PRODMAST m
                        LEFT JOIN (
                            SELECT ST_PRDCD, ST_AVGCOST, ST_SALDOAKHIR 
                            FROM IGRPWT.TBMASTER_STOCK 
                            WHERE ST_LOKASI = '01'
                        ) st ON m.PRD_PRDCD = st.ST_PRDCD
                        left join (SELECT * FROM (SELECT PRD_PRDCD AS PLU_FLAG,
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
                                                                    case when prd_plumcg in (select PLUIDM from IGRPWT.DEPO_LIST_IDM ) THEN 'Y' ELSE 'N' END AS DEPO
                                                                    FROM IGRPWT.TBMASTER_PRODMAST WHERE PRD_PRDCD LIKE '%0' AND PRD_DESKRIPSIPANJANG IS NOT NULL) FLAGX)FLAGY) FLAG ON m.prd_prdcd = FLAG.PLU_FLAG 
                        WHERE m.PRD_PRDCD IN ('1735440','1735450','1735460')
                        AND m.PRD_PRDCD LIKE '%0'
                        GROUP BY 
                            m.PRD_PRDCD, 
                            m.PRD_PLUMCG, 
                            st.ST_SALDOAKHIR, 
                            m.PRD_KODEIGR,
                            flag,
                            st.ST_AVGCOST";
        } elseif ($jenis === 'rekap') {
            $query = "SELECT PRD_PRDCD AS PLU_IGR, 
                            PRD_PLUMCG AS PLU_IDM,
                            PRD_DESKRIPSIPANJANG AS DESK,
                            --ST_TRFIN AS SALDO_AWAL,
                            ST_SALDOAKHIR AS SALDO_AKHIR, 
                            COALESCE(SUM(RPB_QTYORDER),0) AS SUM_QTY_PB, 
                            COALESCE(SUM(RPB_QTYREALISASI),0) AS SUM_QTY_REAL
                            FROM IGRPWT.TBMASTER_PRODMAST
                            LEFT JOIN IGRPWT.TBMASTER_STOCK ON PRD_PRDCD = ST_PRDCD AND ST_LOKASI = '01'
                            LEFT JOIN IGRPWT.TBTR_REALPB ON PRD_PLUMCG = RPB_PLU1 AND RPB_TGLDOKUMEN BETWEEN '2026-02-01' AND CURRENT_DATE -1
                            WHERE PRD_PRDCD IN ('1735440','1735450','1735460')
                            GROUP BY PRD_PRDCD,PRD_PLUMCG,PRD_DESKRIPSIPANJANG,ST_TRFIN,ST_SALDOAKHIR
                            ORDER BY 1";
        } elseif ($jenis === 'detail') {
            $query = "SELECT  RPB_KODEIGR AS KODE_IGR,
                                        'PWT' AS CAB,
                                        TO_CHAR(RPB_TGLDOKUMEN, 'DD-MM-YYYY') AS TGLPB,
                                        RPB_KODECUSTOMER AS KODE_CUST,
                                        RPB_KODEOMI AS KODE_TOKO,
                                        RPB_PLU1 AS PLUIDM,
                                        SUBSTRING(RPB_PLU2 FROM 1 FOR 6) || '0' AS PLU,
                                        PRD_DESKRIPSIPANJANG AS DESK,
                                        PRD_FRAC AS FRAC,
                                        PRD_UNIT AS UNIT,
                                        RPB_HRGSATUAN AS HPP,
                                        RPB_QTYORDER AS QTY_PB,
                                        RPB_QTYREALISASI AS QTY_REAL,
                                        rpb_nilaiorder AS RPH_ORDER,
                                        RPB_TTLNILAI AS RPH_REAL,
                                        case when RPB_KODEOMI LIKE 'F%' THEN 'FRANCHISE'
                                        WHEN RPB_KODEOMI LIKE 'T%' THEN 'REGULER'
                                        END AS JENIS_TOKO
                                        FROM IGRPWT.TBTR_REALPB
                                LEFT JOIN
                                (SELECT 
                                        substr(prd_prdcd,1,6) || '0' AS prd_prdcd,
                                        prd_deskripsipanjang,
                                        prd_plumcg,
                                        prd_unit,
                                        prd_frac,
                                        prd_perlakuanbarang,
                                        prd_kodetag,
                                        prd_avgcost,
                                        COALESCE(PRD_FLAGNAS, 'N') AS NAS,
                                        COALESCE(PRD_FLAGIGR, 'N') AS IGR,
                                        COALESCE(PRD_FLAGIDM, 'N') AS IDM,
                                        COALESCE(PRD_FLAGOMI, 'N') AS OMI,
                                        COALESCE(PRD_FLAGBRD, 'N') AS BRD,
                                        COALESCE(PRD_FLAGOBI, 'N') AS K_IGR,
                                        CASE 
                                            WHEN prd_plumcg IN (SELECT Pluidm FROM igrpwt.Depo_List_Idm) THEN 'Y' 
                                            ELSE 'N' 
                                        END AS Depo
                                    FROM 
                                        IGRPWT.Tbmaster_Prodmast 
                                    WHERE 
                                        prd_prdcd LIKE '%0' 
                                        AND prd_deskripsipanjang IS NOT NULL) PRD ON RPB_PLU1 = PRD_PLUMCG
                                left join (
                                    select prc_pluigr as pluigr,
                                        MAX(prc_pluidm) as prc_pluidm,
                                        MAX(prc_minorder) as minoridm,
                                        MAX(prc_kodetag) as tagidm
                                    from IGRPWT.tbmaster_prodcrm
                                    where prc_group = 'I'
                                    group by prc_pluigr
                                ) idm on RPB_PLU1 = idm.pluigr
                                        WHERE RPB_PLU1 IN ('20140820','20140822','20140823')
                                        AND RPB_TGLDOKUMEN BETWEEN '2026-02-01' AND current_date -1 
                                ORDER BY 3,7 asc";
        } else {
            return response()->json(['error' => 'Jenis tidak valid'], 400);
        }

        $results = DB::select($query);

        return view('edp.itemcp1_table', [
            'data' => $results,
            'jenis' => $jenis
        ]);
    }
}
