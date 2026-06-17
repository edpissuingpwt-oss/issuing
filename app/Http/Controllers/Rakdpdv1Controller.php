<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class Rakdpdv1Controller extends Controller
{
    public function rakdpdv1()
    {
        $query = "SELECT 
                                        PRD_PRDCD AS PLU_IGR,
                                        PRD_PLUMCG AS PLU_IDM,
                                        PRC_PLUOMI AS PLU_OMI,
                                        RAK_GUDANG,
                                        RAK_TOKO,
                                        prd_deskripsipanjang AS DESKRIPSI,
                                        prd_unit AS UNIT,
                                        prd_frac AS FRAC,
                                        minoridm as MINORIDM,
                                        minoromi as MINOROMI,
                                        CASE
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYYYYY' THEN 'NAS-IGR+IDM+OMI+MR.BRD+K.IGR+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYYYYN' THEN 'NAS-IGR+IDM+OMI+MR.BRD+K.IGR'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYYYNN' THEN 'NAS-IGR+IDM+OMI+MR.BRD'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYYNYY' THEN 'NAS-IGR+IDM+OMI+K.IGR+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYYNYN' THEN 'NAS-IGR+IDM+OMI+K.IGR'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYYNNY' THEN 'NAS-IGR+IDM+OMI+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYYNNN' THEN 'NAS-IGR+IDM+OMI'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYNYYY' THEN 'NAS-IGR+IDM+MR.BRD+K.IGR+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYNNYY' THEN 'NAS-IGR+IDM+K.IGR+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYNNYN' THEN 'NAS-IGR+IDM+K.IGR'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYNNNY' THEN 'NAS-IGR+IDM+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYNNNN' THEN 'NAS-IGR+IDM'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYNYNYY' THEN 'NAS-IGR+OMI+K.IGR+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYNYNYN' THEN 'NAS-IGR+OMI+K.IGR'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYNYNNY' THEN 'NAS-IGR+OMI+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYNYNNN' THEN 'NAS-IGR+OMI'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYNNYYN' THEN 'NAS-IGR+MR.BRD+K.IGR'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYNNYNN' THEN 'NAS-IGR+MR.BRD'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYNNNYY' THEN 'NAS-IGR+K.IGR+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYNNNYN' THEN 'NAS-IGR+K.IGR'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYNNNNY' THEN 'NAS-IGR+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYNNNNN' THEN 'NAS-IGR'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNYYNYY' THEN 'NAS-IDM+OMI+K.IGR+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNYYNYN' THEN 'NAS-IDM+OMI+K.IGR'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNYYNNY' THEN 'NAS-IDM+OMI+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNYYNNN' THEN 'NAS-IDM+OMI'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNYNNYY' THEN 'NAS-IDM+K.IGR+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNYNNYN' THEN 'NAS-IDM+K.IGR'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNYNNNY' THEN 'NAS-IDM+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNYNNNN' THEN 'NAS-IDM'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNNYYNN' THEN 'NAS-OMI+MR.BRD'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNNYNYN' THEN 'NAS-OMI+K.IGR'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNNYNNN' THEN 'NAS-OMI'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNNNYNN' THEN 'NAS-MR.BRD'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNNNNYN' THEN 'NAS-K.IGR'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNNNNNN' THEN 'NAS'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYYYNYY' THEN 'IGR+IDM+OMI+K.IGR+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYYYNYN' THEN 'IGR+IDM+OMI+K.IGR'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYYYNNY' THEN 'IGR+IDM+OMI+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYYYNNN' THEN 'IGR+IDM+OMI'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYYNNYY' THEN 'IGR+IDM+K.IGR+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYYNNYN' THEN 'IGR+IDM+K.IGR'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYYNNNY' THEN 'IGR+IDM+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYYNNNN' THEN 'IGR+IDM'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYNYNYY' THEN 'IGR+OMI+K.IGR+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYNYNYN' THEN 'IGR+OMI+K.IGR'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYNYNNN' THEN 'IGR+OMI'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYNNYYN' THEN 'IGR+MR.BRD+K.IGR'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYNNNYY' THEN 'IGR+K.IGR+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYNNNYN' THEN 'IGR+K.IGR'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYNNNNY' THEN 'IGR+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYNNNNN' THEN 'IGR'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NNYYNYY' THEN 'IDM+OMI+K.IGR+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NNYYNYN' THEN 'IDM+OMI+K.IGR'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NNYYNNY' THEN 'IDM+OMI+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NNYYNNN' THEN 'IDM+OMI'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NNYNNYY' THEN 'IDM+K.IGR+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NNYNNYN' THEN 'IDM+K.IGR'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NNYNNNY' THEN 'IDM+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NNYNNNN' THEN 'IDM'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NNNYNYN' THEN 'OMI+K.IGR'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NNNYNNN' THEN 'OMI'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NNNNNNN' THEN 'BELUM ADA FLAG'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYNYYYN' THEN 'IGR+OMI+MR.BRD+K.IGR'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYYYYYN' THEN 'IGR+IDM+OMI+MR.BRD+K.IGR'
                                            ELSE 'BELUM ADA FLAG'
                                        END AS FLAG,
                                        prd_kodetag as TAG_IGR,
                                        tagidm as TAG_IDM,
                                        tagomi as TAG_OMI,
                                        AD.LPP,
                                        AB.PLANO_GUDANG,
                                        AC.PLANO_TOKO,
                                        prd_avgcost as ACOST,
                                        prd_perlakuanbarang AS STATUS
                                    FROM (
                                        SELECT 
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
                                                WHEN prd_plumcg IN (SELECT Pluidm FROM IGRPWT.Depo_List_Idm) THEN 'Y' 
                                                ELSE 'N' 
                                            END AS Depo
                                        FROM 
                                            IGRPWT.Tbmaster_Prodmast 
                                        WHERE 
                                            prd_prdcd LIKE '%0' 
                                            AND prd_deskripsipanjang IS NOT NULL
                                    ) AS aa
                                    LEFT JOIN
                                    (select prc_pluigr,prc_pluomi,prc_minorder as minoromi,prc_minorderomi,prc_maxorderomi,prc_kodetag as tagomi
                                    from IGRPWT.tbmaster_prodcrm where prc_group = 'O') omi on prd_prdcd = omi.prc_pluigr
                                    left join
                                    (select prc_pluigr as pluigr,prc_pluidm,prc_minorder as minoridm,prc_kodetag tagidm from IGRPWT.tbmaster_prodcrm where prc_group = 'I') idm on prd_prdcd = idm.pluigr
                                    LEFT JOIN (
                                        SELECT 
                                            LKS_PRDCD, 
                                            LKS_KODERAK || '.' || LKS_KODESUBRAK || '.' || LKS_TIPERAK || '.' || LKS_SHELVINGRAK || '.' || LKS_NOURUT AS RAK_GUDANG,
                                            lks_qty AS PLANO_GUDANG 
                                        FROM 
                                            IGRPWT.Tbmaster_Lokasi 
                                        WHERE 
                                            SUBSTR(Lks_Koderak, 1, 1) = 'D' 
                                            AND Lks_Tiperak = 'B' 
                                            AND Lks_Prdcd IS NOT NULL
                                    ) AS AB ON AA.PRD_PRDCD = AB.LKS_PRDCD
                                    LEFT JOIN (
                                        SELECT 
                                            LKS_PRDCD, 
                                            LKS_KODERAK || '.' || LKS_KODESUBRAK || '.' || LKS_TIPERAK || '.' || LKS_SHELVINGRAK || '.' || LKS_NOURUT AS RAK_TOKO,
                                            lks_qty AS PLANO_TOKO 
                                        FROM 
                                            IGRPWT.Tbmaster_Lokasi 
                                        WHERE 
                                            SUBSTR(Lks_Koderak, 1, 1) IN ('R', 'O') 
                                            AND SUBSTR(Lks_Tiperak, 1, 1) IN ('B', 'I') 
                                            AND Lks_Prdcd IS NOT NULL
                                    ) AS AC ON AA.PRD_PRDCD = AC.LKS_PRDCD
                                    LEFT JOIN (
                                        SELECT 
                                            ST_PRDCD, 
                                            ST_SALDOAKHIR AS LPP,
                                            CASE
                                                  WHEN ST_SALDOAKHIR > 0 AND ST_SALES > 0
                                                  THEN ROUND((((COALESCE(ST_SALDOAWAL,0)+COALESCE(ST_SALDOAKHIR,0))/2)/COALESCE(ST_SALES,0))*(EXTRACT(DAY FROM CURRENT_DATE)))
                                                  ELSE 0
                                                END DSI
                                        FROM 
                                            IGRPWT.TBMASTER_STOCK 
                                        WHERE 
                                            ST_LOKASI = '01'
                                    ) AS AD ON AA.PRD_PRDCD = AD.ST_PRDCD 
                                    --WHERE 
                                        ---RAK_GUDANG IS NOT NULL 
                                    ORDER BY 
                                        1";
        
        $results = DB::select($query);

        return view('master.pludpdv1', compact('results'));
    }
}