<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanseasonalController extends Controller
{
    public function laporanseasonal()
    {
        return view('seasonal.laporan');
    }

    public function getData(Request $request)
    {
        $start = \Carbon\Carbon::createFromFormat('d-m-Y', $request->tanggal_mulai)->format('Y-m-d');
        $end = \Carbon\Carbon::createFromFormat('d-m-Y', $request->tanggal_selesai)->format('Y-m-d');

        $sql = "SELECT DIV,DEPT,KAT,RAK,ZONA,PLUIDM,PLUIGR,DESCRIPTION_ITEM,TYPEE,FLAG,UNIT,FRAC,TAG_IGR,TAG_IDM,PLANO_DPD,LPP,TARGET_ALOKASI,
                                --TARGET_FINAL,
                                ACOST,PBRO,PBSEASONAL,QTYREAL,
                                --CASE WHEN PLUIGR IN ('0032020','1144730','1211940','1430360','1430400') THEN NPB_QTY2
                                --ELSE NPB_QTY END AS NPB_QTY,
                                --CASE WHEN PLUIGR IN ('0032020','1144730','1211940','1430360','1430400') THEN SELISIH_QTY2
                                --ELSE SELISIH_QTY END AS SELISIH_QTY,
                                --CASE WHEN PLUIGR IN ('0032020','1144730','1211940','1430360','1430400') THEN PERCENT_QTY2
                                --ELSE PERCENT_QTY END AS PERCENT_QTY,
                                --CASE WHEN PLUIGR IN ('0032020','1144730','1211940','1430360','1430400') THEN RPH_NPB2
                                --ELSE RPH_NPB END AS RPH_NPB,
                                --CASE WHEN PLUIGR IN ('0032020','1144730','1211940','1430360','1430400') THEN RPH_SELISIH2
                                --ELSE RPH_SELISIH END AS RPH_SELISIH,
                                --CASE WHEN PLUIGR IN ('0032020','1144730','1211940','1430360','1430400') THEN RPH_PERCENT2
                                --ELSE RPH_PERCENT END AS RPH_PERCENT,
                                NPB_QTY,
                                SELISIH_QTY,
                                PERCENT_QTY,
                                RPH_TARGET,
                                RPH_NPB,
                                RPH_SELISIH,
                                RPH_PERCENT,
                                PO_OUT,
                                KODE_SUPPLIER,
                                NAMA_SUPPLIER
                                FROM 
                                (SELECT 
                                PRD_KODEDIVISI AS DIV,
                                PRD_KODEDEPARTEMENT AS DEPT,
                                PRD_KODEKATEGORIBARANG AS KAT,
                                MASLOK.ALAMAT AS RAK,
                                CASE
                                    WHEN MASLOK.LKS_KODERAK IN('D01','D02','D03','D04') THEN 'ZONA1'
                                    WHEN MASLOK.LKS_KODERAK IN('D05','D06','D07','D08') THEN 'ZONA2'
                                    WHEN MASLOK.LKS_KODERAK IN('D09','D10','D11','D12') THEN 'ZONA3'
                                    WHEN MASLOK.LKS_KODERAK IN('D13','D14') THEN 'ZONA4'
                                    WHEN MASLOK.LKS_KODERAK IN('D15','D16') THEN 'ZONA5'
                                    WHEN MASLOK.LKS_KODERAK IN('D17','D18') THEN 'ZONA6'
                                    WHEN MASLOK.LKS_KODERAK IN('D19','D20','D21','D22','D23','D24') THEN 'ZONA7'
                                    WHEN MASLOK.LKS_KODERAK IN('D25','D26','D27','D28','D29','D30') THEN 'ZONA8'
                                    WHEN MASLOK.LKS_KODERAK IN('D31','D32','D33','D34','D35','D36') THEN 'ZONA9'
                                    WHEN MASLOK.LKS_KODERAK IN('D37','D38','D39','D40','D41','D42') THEN 'ZONAA'
                                    WHEN MASLOK.LKS_KODERAK IN('D43','D44','D45','D46','D47','D48') THEN 'ZONAB'
                                    WHEN MASLOK.LKS_KODERAK IN('D49','D50','D51','D52','D53','D54') THEN 'ZONAC'
                                    WHEN MASLOK.LKS_KODERAK IN('D55','D56') THEN 'ZONAD'
                                    WHEN MASLOK.LKS_KODERAK IN('D57') THEN 'ZONAE'
                                    ELSE ''
                                END AS ZONA,
                                PRD_PLUMCG AS PLUIDM,
                                PRD_PRDCD AS PLUIGR,
                                PRD_DESKRIPSIPANJANG AS DESCRIPTION_ITEM,
                                CASE
                                    WHEN PRD_KODEDIVISI = '1' AND PRD_KODEDEPARTEMENT = '04' AND PRD_KODEKATEGORIBARANG = '05' THEN 'AMDK'
                                    WHEN PRD_KODEDIVISI = '1' THEN 'FOOD'
                                    WHEN PRD_KODEDIVISI = '2' THEN 'NONFOOD'
                                    WHEN PRD_KODEDIVISI = '3' THEN 'GMS'
                                    ELSE ''
                                END AS TYPEE,
                                CASE
                                    WHEN PRD_FLAGIDM = 'Y' AND PRD_FLAGIGR = 'Y' AND PRD_FLAGOMI = 'Y' THEN 'IGR+IDM+OMI'
                                    WHEN PRD_FLAGIDM = 'Y' AND PRD_FLAGIGR = 'N' AND PRD_FLAGOMI = 'N' THEN 'IDM ONLY'
                                    WHEN PRD_FLAGIDM = 'Y' AND PRD_FLAGIGR = 'N' AND PRD_FLAGOMI = 'Y' THEN 'IDM+OMI'
                                    WHEN PRD_FLAGIDM = 'Y' AND PRD_FLAGIGR = 'Y' AND PRD_FLAGOMI = 'N' THEN 'IGR+IDM'
                                    WHEN PRD_FLAGIDM = 'N' AND PRD_FLAGIGR = 'Y' AND PRD_FLAGOMI = 'Y' THEN 'IGR+OMI'
                                    WHEN PRD_FLAGIDM = 'N' AND PRD_FLAGIGR = 'Y' AND PRD_FLAGOMI = 'N' THEN 'IGR ONLY'
                                    WHEN PRD_FLAGIDM = 'N' AND PRD_FLAGIGR = 'N' AND PRD_FLAGOMI = 'Y' THEN 'OMI ONLY'
                                    WHEN PRD_FLAGIDM = 'N' AND PRD_FLAGIGR = 'N' AND PRD_FLAGOMI = 'Y' THEN 'NULL'
                                END AS FLAG,
                                PRD_UNIT AS UNIT,
                                PRD_FRAC AS FRAC,
                                PRD_KODETAG AS TAG_IGR,
                                PRC_KODETAG AS TAG_IDM,
                                COALESCE(MASLOK.LKS_QTY,0) AS PLANO_DPD,
                                COALESCE(ST_SALDOAKHIR,0) AS LPP,
                                TS_TARGET AS TARGET_ALOKASI,
                                TS_FINAL AS TARGET_FINAL,
                                TS_TARGET2 AS TARGET_ALOKASI2,
                                TS_FINAL2 AS TARGET_FINAL2,
                                COALESCE(ST_AVGCOST,0) AS ACOST,
                                coalesce(RSEASONAL.PBRO,'0') pbro,
                                coalesce(RSEASONAL2.PBRO2,'0') pbro2,
                                --  CASE WHEN PRD_PRDCD IN ('0032020','1144730','1211940','1430360','1430400') THEN coalesce(RSEASONAL2.PBSEASONAL2,'0')
                                --  ELSE coalesce(RSEASONAL.PBSEASONAL,'0') END AS pbseasonal,
                                coalesce(RSEASONAL.PBSEASONAL,'0') AS pbseasonal,
                                coalesce(RSEASONAL2.PBSEASONAL2,'0') pbseasonal2,
                                --  CASE WHEN PRD_PRDCD IN ('0032020','1144730','1211940','1430360','1430400') THEN coalesce(RSEASONAL2.QTYREAL2,'0')
                                --  ELSE coalesce(RSEASONAL.QTYREAL,'0') END AS qtyreal,
                                coalesce(RSEASONAL.QTYREAL,'0') AS qtyreal,
                                coalesce(RSEASONAL2.QTYREAL2,'0') qtyreal2,
                                CASE
                                    WHEN RSEASONAL.QTYREAL - PBRO IS NULL THEN 0
                                    ELSE RSEASONAL.QTYREAL - PBRO
                                END AS NPB_QTY,
                                CASE
                                    WHEN RSEASONAL2.QTYREAL2 - PBRO IS NULL THEN 0
                                    ELSE RSEASONAL2.QTYREAL2 - PBRO
                                END AS NPB_QTY2,
                                CASE
                                    WHEN (RSEASONAL.QTYREAL - PBRO) - TS_TARGET IS NULL THEN 0 - TS_TARGET
                                    ELSE (RSEASONAL.QTYREAL - PBRO) - TS_TARGET
                                END AS SELISIH_QTY,
                                CASE
                                    WHEN (RSEASONAL2.QTYREAL2 - PBRO) - TS_TARGET IS NULL THEN 0 - TS_TARGET
                                    ELSE (RSEASONAL2.QTYREAL2 - PBRO) - TS_TARGET
                                END AS SELISIH_QTY2,
                                CASE 
                                    WHEN TS_TARGET = 0 THEN 0
                                    ELSE ((RSEASONAL.QTYREAL - PBRO) / TS_TARGET * 100)
                                END AS PERCENT_QTY,
                                CASE 
                                        WHEN TS_TARGET = 0 THEN 0
                                        ELSE ((RSEASONAL2.QTYREAL2 - PBRO) / TS_TARGET * 100)
                                END AS PERCENT_QTY2,
                                COALESCE(cast((ST_AVGCOST * TS_TARGET) as integer),0) AS RPH_TARGET,
                                CASE 
                                    WHEN  ((RSEASONAL.QTYREAL - PBRO) * ST_AVGCOST) IS NULL THEN 0
                                    ELSE  ((RSEASONAL.QTYREAL - PBRO) * ST_AVGCOST)
                                END AS RPH_NPB,
                                CASE 
                                    WHEN  ((RSEASONAL2.QTYREAL2 - PBRO) * ST_AVGCOST) IS NULL THEN 0
                                    ELSE  ((RSEASONAL2.QTYREAL2 - PBRO) * ST_AVGCOST)
                                END AS RPH_NPB2,
                                CASE
                                    WHEN  ((RSEASONAL.QTYREAL - PBRO) * ST_AVGCOST) -  (ST_AVGCOST * TS_TARGET) IS NULL 
                                    THEN COALESCE(cast (0 -  (ST_AVGCOST * TS_TARGET) as integer),0)
                                    Else COALESCE(cast(((Rseasonal.Qtyreal - Pbro) * St_Avgcost) -  (St_Avgcost * Ts_Target) as integer),0)
                                End As RPH_SELISIH,
                                CASE
                                    WHEN  ((RSEASONAL2.QTYREAL2 - PBRO) * ST_AVGCOST) -  (ST_AVGCOST * TS_TARGET) IS NULL 
                                    THEN COALESCE(cast (0 -  (ST_AVGCOST * TS_TARGET) as integer),0)
                                    Else COALESCE(cast(((Rseasonal2.Qtyreal2 - Pbro) * St_Avgcost) -  (St_Avgcost * Ts_Target) as integer),0)
                                End As RPH_SELISIH2,
                                CASE 
                                        WHEN (St_AvgCost * TS_Target) = 0 THEN 0
                                        ELSE ((RSEASONAL.QTYREAL - PBRO) * St_AvgCost) / (St_AvgCost * TS_Target) * 100
                                    END AS RPH_PERCENT,
                                Case 
                                    When  ( ((Rseasonal2.Qtyreal2 - Pbro) * St_Avgcost)/  (St_Avgcost * Ts_Target) * 100) Is Null Then 0
                                    ELSE  ( ((Rseasonal2.Qtyreal2 - Pbro) * St_Avgcost)/  (St_Avgcost * Ts_Target) * 100)
                                END AS RPH_PERCENT2,
                                CASE
                                    WHEN out_qty IS NULL THEN 0
                                    ELSE out_qty
                                END AS PO_OUT,
                                SUP_KODESUPPLIER AS KODE_SUPPLIER,
                                SUP_NAMASUPPLIER AS NAMA_SUPPLIER
                                FROM
                                    (SELECT 
                                    PRD_KODEDIVISI,
                                    PRD_KODEDEPARTEMENT,
                                    PRD_KODEKATEGORIBARANG,
                                    PRD_KODETAG,
                                    PRD_PRDCD,
                                    PRD_PLUMCG,
                                    PRD_DESKRIPSIPANJANG,
                                    PRD_UNIT,
                                    PRD_FRAC,
                                    PRD_FLAGIDM,
                                    PRD_FLAGIGR,
                                    PRD_FLAGOMI
                                    FROM IGRPWT.TBMASTER_PRODMAST WHERE PRD_PRDCD IN(SELECT DISTINCT TS_PLUIGR FROM IGRPWT.TARGET_SEASONAL)) PRDMST
                                LEFT JOIN
                                    (SELECT 
                                    LKS_KODERAK,
                                    LKS_KODERAK || '.' || LKS_KODESUBRAK || '.' || LKS_TIPERAK || '.' || LKS_SHELVINGRAK || '.' || LKS_NOURUT AS ALAMAT,
                                    LKS_PRDCD,
                                    LKS_QTY
                                    FROM IGRPWT.TBMASTER_LOKASI WHERE LKS_KODERAK LIKE 'D%' AND LKS_KODERAK <> 'DKLIK' AND LKS_TIPERAK = 'B') MASLOK
                                ON PRD_PRDCD = LKS_PRDCD
                                LEFT JOIN
                                    (SELECT 
                                    ST_PRDCD,
                                    ST_AVGCOST,
                                    ST_SALDOAKHIR
                                    FROM IGRPWT.TBMASTER_STOCK WHERE ST_LOKASI = '01') MSTOCK
                                ON PRD_PRDCD = ST_PRDCD
                                LEFT JOIN
                                    (SELECT 
                                    PRC_PLUIDM,
                                    PRC_PLUIGR,
                                    PRC_KODETAG
                                    FROM IGRPWT.TBMASTER_PRODCRM WHERE PRC_GROUP = 'I') CRM
                                ON PRD_PRDCD = PRC_PLUIGR
                                LEFT JOIN (
                                    SELECT 
                                        d.TPOD_PRDCD AS out_plu,
                                        m.PRD_PLUMCG AS plu_idm,
                                        SUM(d.TPOD_QTYPO) AS out_qty
                                    FROM IGRPWT.TBTR_PO_H h
                                    JOIN IGRPWT.TBTR_PO_D d ON h.TPOH_NOPO = d.TPOD_NOPO
                                    LEFT JOIN IGRPWT.TBMASTER_PRODMAST m 
                                        ON d.TPOD_PRDCD = m.PRD_PRDCD 
                                        AND m.PRD_PRDCD LIKE '%0'
                                    WHERE (DATE(h.TPOH_TGLPO) + h.TPOH_JWPB) >= CURRENT_DATE
                                    AND (h.TPOH_RECORDID IS NULL OR h.TPOH_RECORDID = 'X')
                                    GROUP BY d.TPOD_PRDCD, m.PRD_PLUMCG
                                    ) AS po 
                                    ON PRD_PRDCD = po.out_plu
                                LEFT JOIN
                                    (SELECT 
                                    PKM_PRDCD,
                                    PKM_KODESUPPLIER
                                    FROM IGRPWT.TBMASTER_KKPKM) PKM
                                ON PRD_PRDCD = PKM_PRDCD
                                LEFT JOIN
                                    (SELECT 
                                    SUP_KODESUPPLIER,
                                    SUP_NAMASUPPLIER
                                    FROM IGRPWT.TBMASTER_SUPPLIER) SUPP
                                ON PKM_KODESUPPLIER = SUP_KODESUPPLIER
                                LEFT JOIN
                                    (SELECT  TS_PLUIDM AS PLUIDM,
                                        TS_PLUIGR AS PLUIGR,
                                        SUM(TS_TARGET) AS TS_TARGET,
                                        SUM(TS_TARGET) + COALESCE(SUM(TS_TAMBAHAN),0) AS TS_FINAL,
                                        COALESCE(PBRO,0) AS PBRO,
                                        COALESCE(PBSEASONAL,0) AS PBSEASONAL,
                                        COALESCE(QTYREAL,0) AS QTYREAL
                                        FROM IGRPWT.TARGET_SEASONAL 
                                        LEFT JOIN
                                        (SELECT 
                                        RS_PLUIDM,
                                        RS_PLUIGR,
                                        COALESCE(SUM(RS_QTY_ORDER),0) AS PBRO,
                                        COALESCE(SUM(RS_QTY_SEASONAL),0) AS PBSEASONAL,
                                        COALESCE(SUM(RS_QTY_REAL),0) AS QTYREAL
                                        FROM IGRPWT.REALISASI_SEASONAL
                                        WHERE date(RS_CREATE_DT) BETWEEN :start and :end ---------- >>>>>>>>>>>>>>> GANTI TANGGAL <<<<<<<<<<<<
                                        GROUP BY RS_PLUIDM,RS_PLUIGR
                                        ORDER BY RS_PLUIDM) RS ON TS_PLUIGR = RS_PLUIGR
                                        group by TS_PLUIDM, TS_PLUIGR,PBRO,PBSEASONAL,QTYREAL) RSEASONAL
                                        ON PRD_PRDCD = RSEASONAL.PLUIGR
                                    LEFT JOIN
                                    (SELECT  TS_PLUIDM AS PLUIDM,
                                        TS_PLUIGR AS PLUIGR,
                                        SUM(TS_TARGET) AS TS_TARGET2,
                                        SUM(TS_TARGET) + COALESCE(SUM(TS_TAMBAHAN),0) AS TS_FINAL2,
                                        COALESCE(PBRO,0) AS PBRO2,
                                        COALESCE(PBSEASONAL,0) AS PBSEASONAL2,
                                        COALESCE(QTYREAL,0) AS QTYREAL2
                                        FROM IGRPWT.HISTORY_TARGET_SEASONAL 
                                        LEFT JOIN
                                        (SELECT 
                                        RS_PLUIDM,
                                        RS_PLUIGR,
                                        COALESCE(SUM(RS_QTY_ORDER),0) AS PBRO,
                                        COALESCE(SUM(RS_QTY_SEASONAL),0) AS PBSEASONAL,
                                        COALESCE(SUM(RS_QTY_REAL),0) AS QTYREAL
                                        FROM IGRPWT.HISTORY_REALISASI_SEASONAL
                                        
                                        WHERE date(RS_CREATE_DT) BETWEEN :start and :end ---------- >>>>>>>>>>>>>>> GANTI TANGGAL <<<<<<<<<<<<
                                        
                                        GROUP BY RS_PLUIDM,RS_PLUIGR
                                        ORDER BY RS_PLUIDM) RS ON TS_PLUIGR = RS_PLUIGR
                                        group by TS_PLUIDM, TS_PLUIGR,PBRO,PBSEASONAL,QTYREAL) RSEASONAL2
                                        ON PRD_PRDCD = RSEASONAL2.PLUIGR    
                                ORDER BY 7 ASC) FINAL";

        $results = DB::select($sql, [
            'start' => $start,
            'end' => $end
        ]);

        return view('seasonal.laporan_table', [
            'data' => $results,
            'start' => $start,
            'end' => $end
        ]);
    }
}