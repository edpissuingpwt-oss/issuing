<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class Rakdpdv2Controller extends Controller
{
    public function rakdpdv2()
    {
        $query = "SELECT 
                        PRD_KODEDIVISI AS div,
                        PRD_KODEDEPARTEMENT AS dep,
                        PRD_KODEKATEGORIBARANG AS kat,
                        PRD_PRDCD AS plu_igr,
                        IDM.PRC_PLUIDM AS PLU_IDM,
                        OMI.PRC_PLUOMI AS PLU_OMI,
                        PRD_DESKRIPSIPANJANG AS desk,
                        PRD_UNIT AS unit,
                        PRD_FRAC AS frac,
                        FLAG,
                        PRD_KODETAG AS tag_igr,
                        IDM.tagidm AS tag_idm,
                        OMI.tagomi AS tag_omi,
                        to_char(ROM_TGLDOKUMEN, 'dd-Mon-yy') AS retur_idm_pertama,
                        to_char(tglR, 'dd-Mon-yy') AS retur_idm_terakhir,
                        to_char(prd_tgldiscontinue, 'dd-Mon-yy') AS tgldiscontinue,
                        ST_AVGCOST AS acost,
                        --PKM_PKMT AS pkmt,
                        ST_SALDOAKHIR AS lpp,
                        CASE
                            WHEN PRD_UNIT = 'KG' THEN (ST_SALDOAKHIR * ST_AVGCOST) / 1000
                            ELSE ST_SALDOAKHIR * ST_AVGCOST
                        END AS RP_LPP,
                        --MSTD_TGLDOC AS bpb_terakhir_tgl,
                        --MSTD_QTY AS bpb_terakhir_qty,
                        --TOTAL AS bpb_terakhir_rph,
                        HGB_KODESUPPLIER AS kode,
                        SUP_NAMASUPPLIER AS supplier,
                        lokasi
                        FROM 
                        igrpwt.tbmaster_prodmast pm
                        LEFT JOIN igrpwt.tbmaster_prodcrm prc ON pm.PRD_PRDCD = prc.PRC_PLUIGR
                        --LEFT JOIN tmp.view_cek_flag vf ON prc.PRC_PLUIGR = vf.PLU_FLAG
                        LEFT JOIN igrpwt.tbmaster_stock st ON prc.PRC_PLUIGR = st.ST_PRDCD AND st.st_lokasi = '01'
                        LEFT JOIN igrpwt.tbmaster_kkpkm pkm ON prc.PRC_PLUIGR = pkm.PKM_PRDCD
                        left join (select rom_prdcd, max(rom_create_dt::date) tglR from igrpwt.tbtr_returomi group by rom_prdcd) bpbr ON pm.PRD_PRDCD =bpbr.rom_prdcd
                        left join (select  lks_prdcd, string_agg(lokasi, ' - ' order by lokasi) as lokasi 
                        from (
                        select lks_prdcd,
                        lks_koderak||'.'|| lks_kodesubrak||'.'|| lks_tiperak||'.'|| lks_shelvingrak||'.'|| lks_nourut as lokasi from igrpwt.tbmaster_lokasi) l group by lks_prdcd) lok
                        on pm.PRD_PRDCD=lok.lks_prdcd
                        LEFT JOIN (
                        SELECT 
                            ROM_PRDCD,
                            MIN(ROM_TGLDOKUMEN) AS ROM_TGLDOKUMEN
                        FROM igrpwt.TBTR_RETUROMI
                        WHERE ROM_MEMBER IN (
                            SELECT TKO_KODECUSTOMER 
                            FROM igrpwt.TBMASTER_TOKOIGR 
                            WHERE TKO_KODESBU = 'I'
                        )
                        AND ROM_PRDCD IN (
                            SELECT PRC_PLUIGR 
                            FROM igrpwt.TBMASTER_PRODCRM 
                            WHERE PRC_GROUP = 'I' 
                        --    AND PRC_KODETAG = 'R'
                        )
                        GROUP BY ROM_PRDCD
                        ) rom ON prc.PRC_PLUIGR = rom.ROM_PRDCD
                        LEFT JOIN (
                        SELECT 
                            MSTD_PRDCD,
                            MSTD_TGLDOC,
                            MSTD_QTY,
                            TOTAL
                        FROM (SELECT 
                            m.mstd_prdcd,
                            p.prd_deskripsipanjang,
                            p.prd_unit,
                            p.prd_frac,
                            p.prd_kodetag,
                            m.mstd_tgldoc,
                            m.mstd_nodoc,
                            m.mstd_kodesupplier,
                            s.sup_namasupplier,
                            m.mstd_qty,
                            m.mstd_qtybonus1,
                            TRUNC((m.mstd_gross - m.mstd_discrph) / NULLIF(m.mstd_qty, 0)) AS hpp,
                            m.mstd_gross - m.mstd_discrph AS total
                        FROM 
                            igrpwt.tbtr_mstran_d m
                            JOIN igrpwt.tbmaster_prodmast p ON m.mstd_prdcd = p.prd_prdcd
                            JOIN igrpwt.tbmaster_supplier s ON m.mstd_kodesupplier = s.sup_kodesupplier
                        WHERE 
                            CONCAT(m.mstd_prdcd, m.mstd_nodoc) IN (
                                SELECT CONCAT(mstd_prdcd, mstd_nodoc_max) 
                                FROM (
                                    SELECT 
                                        mstd_prdcd, 
                                        MAX(mstd_nodoc) AS mstd_nodoc_max
                                    FROM 
                                        igrpwt.tbtr_mstran_d
                                    WHERE 
                                        mstd_recordid IS NULL 
                                        AND mstd_typetrn = 'B'
                                    GROUP BY 
                                        mstd_prdcd
                                ) subquery
                            )) bpba
                        ) mstd ON prc.PRC_PLUIGR = mstd.MSTD_PRDCD
                        LEFT JOIN (
                        SELECT 
                            HGB_PRDCD,
                            HGB_KODESUPPLIER,
                            SUP_NAMASUPPLIER
                        FROM igrpwt.TBMASTER_HARGABELI hgb
                        LEFT JOIN igrpwt.TBMASTER_SUPPLIER sup ON hgb.HGB_KODESUPPLIER = sup.SUP_KODESUPPLIER
                        WHERE hgb.HGB_TIPE = '2'
                        ) hgb ON prc.PRC_PLUIGR = hgb.HGB_PRDCD
                        LEFT JOIN
                        (select prc_pluigr,prc_pluomi,prc_minorder as minoromi,prc_minorderomi,prc_maxorderomi,prc_kodetag as tagomi
                        from IGRPWT.tbmaster_prodcrm where prc_group = 'O') omi on prc.PRC_PLUIGR = omi.prc_pluigr
                        left join
                        (select prc_pluigr as pluigr,prc_pluidm,prc_minorder as minoridm,prc_kodetag tagidm from IGRPWT.tbmaster_prodcrm where prc_group = 'I') idm on prd_prdcd = idm.pluigr
                        left join ( 
                                        select plu, 
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
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NNNNNNN' THEN 'BELUM ADA FLAG'                  
                                        ELSE 'BELUM ADA FLAG'                  
                                        END AS FLAG   
                                        From 
                                        (select prd_prdcd plu, 
                                        coalesce(PRD_FLAGNAS,'N') AS NAS,                  
                                        coalesce(PRD_FLAGIGR,'N') AS IGR,                  
                                        coalesce(PRD_FLAGIDM,'N') AS IDM,                  
                                        coalesce(PRD_FLAGOMI,'N') AS OMI,                  
                                        coalesce(PRD_FLAGBRD,'N') AS BRD,                  
                                        coalesce(PRD_FLAGOBI,'N') AS K_IGR,                  
                                        case when prd_plumcg in (select PLUIDM from igrpwt.DEPO_LIST_IDM ) THEN 'Y' ELSE 'N' END AS DEPO  
                                        from igrpwt.tbmaster_prodmast where prd_prdcd like '%0') z
                                        )aa on plu=prc.prc_pluigr 
                        WHERE prc.PRC_GROUP = 'I'
                        --  AND  prc.PRC_KODETAG = 'R'
                        ORDER BY 
                        div, dep, kat, plu_igr";
        
        $results = DB::select($query);

        return view('master.pludpdv2', compact('results'));
    }
}