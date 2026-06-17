<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Laporan_SLOMI extends Controller
{
    public function slomi()
    {
        return view('laporan.slomi');
    }

    public function getData(Request $request)
    {
        $start = \Carbon\Carbon::createFromFormat('d-m-Y', $request->tanggal_mulai)->format('Y-m-d');
        $end = \Carbon\Carbon::createFromFormat('d-m-Y', $request->tanggal_selesai)->format('Y-m-d');

        $sql = "select 
                        tko_kodeomi as kodeomi,
                        tko_namaomi as namaomi,
                        pbo_nopb as nomorpb,
                        to_char(pbo_create_dt, 'DD-MON-YYYY') as tglpb,  
                        count(pbo_pluigr) as itemorder,
                        sum(pbo_qtyorder) as qtyorder,
                        sum(pbo_nilaiorder) as rphorder,
                        sum(pbo_ppnorder) as ppnorder,
                        sum((pbo_nilaiorder + pbo_ppnorder) + (pbo_distributionfee * 1.11)) as ttlorder,
                        itemtolakan,qtytolakan,totaltolakan,
                        count(case when pbo_qtyrealisasi > 0 then pbo_pluigr end) as itempick,
                        sum(pbo_qtyrealisasi) as qtypick,
                        sum(pbo_ttlnilai) as rphpick,
                        sum(pbo_ttlppn) as ppnpick,
                        sum((pbo_ttlnilai + pbo_ttlppn) + (pbo_distributionfee * 1.11)) as ttlpick,
                        count(rpb_plu2) as itemdsp,
                        sum(rpb_qtyrealisasi) as qtydsp,
                        sum(rpb_ttlnilai) as rphdsp,
                        sum(rpb_ttlppn) as ppndsp,
                        sum((rpb_ttlnilai + rpb_ttlppn) + (rpb_distributionfee * 1.11)) as ttldsp
                    from igrpwt.tbmaster_pbomi
                    left join ( select tlko_dt,kodetoko,nopb,count(pluigr) as itemtolakan,
                                    SUM(
                            case 
                                when pluigr in ('0088000', '0088010', '0087960') 
                                then coalesce(tlko_qtyorder, 0)/ 1000
                                else coalesce(tlko_qtyorder, 0)
                                end
                            ) as qtytolakan,
                                    SUM(
                            case 
                                when pluigr in ('0088000', '0088010', '0087960') 
                                then coalesce(tlko_qtyorder, 0)/ 1000 * coalesce(tlko_nilai, 0) 
                                else coalesce(tlko_qtyorder, 0) * coalesce(tlko_nilai, 0)
                            end
                        ) as totaltolakan from (
                            SELECT 
                                                t.tlko_tglpb AS tanggal,
                                                t.tlko_create_dt AS tlko_dt,
                                                t.TLKO_NOPB AS nopb,
                                                TKOIGR.TKO_KODESBU AS kodesbu,
                                                TKOIGR.TKO_KODEOMI AS kodetoko,
                                                TKOIGR.TKO_NAMAOMI AS namatoko,
                                                DIV,DEPT,KATB,
                                                SUBSTR(t.TLKO_PLUIGR, 1, 6) || '0' AS pluigr,
                                                t.TLKO_PLUOMI AS pluomi,
                                                t.TLKO_DESC AS desk,
                                                f.PRD_FRAC as frac,
                                                pcs.acostpcs as acostpcs,
                                                s.ST_SALDOAKHIR AS lpp,
                                                LASTBPB,
                                                f.PRD_KODETAG AS tagigr,
                                                TAGIDM.TAGIDM,
                                                TAGOMI.TAGIDM AS tagomi,
                                                f.FLAG,
                                                t.TLKO_KETTOLAKAN,
                                                t.TLKO_QTYORDER,
                                                po.out_qty,
                                                p.PKMP_MPLUSI,
                                                tlko_nilai
                                            FROM IGRPWT.TBTR_TOLAKANPBOMI t
                                            LEFT JOIN IGRPWT.TBMASTER_STOCK s 
                                                ON SUBSTR(t.TLKO_PLUIGR, 1, 6) || '0' = s.ST_PRDCD 
                                            AND s.ST_LOKASI = '01'
                                            LEFT JOIN (
                                                SELECT 
                                                    TKO_KODESBU,
                                                    TKO_KODEOMI,
                                                    TKO_NAMAOMI
                                                FROM IGRPWT.TBMASTER_TOKOIGR
                                            ) AS TKOIGR 
                                                ON t.TLKO_KODEOMI = TKOIGR.TKO_KODEOMI
                                            LEFT JOIN (
                                                SELECT 
                                                    PRC_GROUP,
                                                    PRC_PLUIGR,
                                                    PRC_KODETAG AS TAGIDM
                                                FROM IGRPWT.TBMASTER_PRODCRM
                                                WHERE PRC_GROUP = 'I'
                                            ) AS TAGIDM 
                                                ON t.TLKO_PLUIGR = TAGIDM.PRC_PLUIGR
                                            LEFT JOIN (
                                                SELECT 
                                                    PRC_GROUP,
                                                    PRC_PLUIGR,
                                                    PRC_KODETAG AS TAGIDM
                                                FROM IGRPWT.TBMASTER_PRODCRM
                                                WHERE PRC_GROUP IN ('O', 'F')
                                            ) AS TAGOMI 
                                                ON t.TLKO_PLUIGR = TAGOMI.PRC_PLUIGR
                                            LEFT JOIN IGRPWT.TBMASTER_PKMPLUS p 
                                                ON SUBSTR(t.TLKO_PLUIGR, 1, 6) || '0' = p.PKMP_PRDCD
                                            LEFT JOIN (select mstd_prdcd AS PLUBPBAKHIR,min(mstd_tgldoc)as FIRSTBPB,max(mstd_tgldoc)as LASTBPB
                                                        from igrpwt.tbtr_mstran_d where mstd_typetrn='B' group by mstd_prdcd) mstd 
                                                        on SUBSTR(t.TLKO_PLUIGR, 1, 6) || '0' = mstd.PLUBPBAKHIR
                                            LEFT JOIN (
                                                SELECT 
                                                    PRD_PRDCD AS PLU_FLAG,
                                                    PRD_KODEDIVISI AS DIV,
                                                    PRD_KODEDEPARTEMENT AS DEPT,
                                                    prd_kodekategoribarang AS KATB,
                                                    PRD_PLUMCG AS PLU_IDM,
                                                    PRD_KODETAG,
                                                    PRD_FRAC,
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
                                                FROM (
                                                    SELECT PRD_KODEDIVISI,PRD_KODEDEPARTEMENT,prd_kodekategoribarang,
                                                        PRD_PRDCD,
                                                        PRD_PLUMCG,
                                                        PRD_KODETAG,
                                                        PRD_FRAC,
                                                        COALESCE(PRD_FLAGNAS, 'N') AS NAS,
                                                        COALESCE(PRD_FLAGIGR, 'N') AS IGR,
                                                        COALESCE(PRD_FLAGIDM, 'N') AS IDM,
                                                        COALESCE(PRD_FLAGOMI, 'N') AS OMI,
                                                        COALESCE(PRD_FLAGBRD, 'N') AS BRD,
                                                        COALESCE(PRD_FLAGOBI, 'N') AS K_IGR,
                                                        CASE 
                                                            WHEN PRD_PLUMCG IN (SELECT PLUIDM FROM IGRPWT.DEPO_LIST_IDM) THEN 'Y' 
                                                            ELSE 'N' 
                                                        END AS DEPO
                                                    FROM IGRPWT.TBMASTER_PRODMAST 
                                                    WHERE PRD_PRDCD LIKE '%0' 
                                                    AND PRD_DESKRIPSIPANJANG IS NOT NULL
                                                ) AS FLAGX
                                            ) AS f 
                                                ON SUBSTR(t.TLKO_PLUIGR, 1, 6) || '0' = f.PLU_FLAG
                                            LEFT JOIN (
                                                SELECT 
                                                    d.TPOD_PRDCD AS out_plu,
                                                    m.PRD_PLUMCG AS pluidm,
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
                                                ON SUBSTR(t.TLKO_PLUIGR, 1, 6) || '0' = po.out_plu
                                            LEFT JOIN (select prd_prdcd plupcs, prd_avgcost acostpcs from igrpwt.tbmaster_prodmast where prd_prdcd like '%1') pcs
                                                ON SUBSTR(t.TLKO_PLUIGR, 1, 6) || '0' = SUBSTR(pcs.plupcs, 1, 6) || '0'
                                            where 
                                                    date_trunc('day', tlko_create_dt) between :start and :end
                        ) as tol group by tlko_dt,kodetoko,nopb ) tol2
                        on date_trunc('day', pbo_create_dt) = tlko_dt
                    and pbo_kodeomi = kodetoko
                    and pbo_nopb = nopb
                    left join igrpwt.tbtr_realpb 
                        on pbo_pluigr = rpb_plu2
                    and pbo_kodeomi = rpb_kodeomi
                    and pbo_nopb = rpb_nodokumen
                    and pbo_nokoli = rpb_nokoli
                    left join igrpwt.tbmaster_prodcrm 
                        on prc_pluomi = pbo_pluomi
                    left join igrpwt.tbmaster_prodmast 
                        on substring(pbo_pluigr, 1, 6) || '0' = prd_prdcd
                    left join igrpwt.tbmaster_tokoigr 
                        on tko_kodeomi = pbo_kodeomi
                    where 
                        date_trunc('day', pbo_create_dt) between :start and :end
                        and substring(pbo_nopb, 1, 1) in ('6', 'S', 'K')
                    group by 
                        tko_kodeomi, tko_namaomi, pbo_nopb, to_char(pbo_create_dt, 'DD-MON-YYYY'), itemtolakan, qtytolakan, totaltolakan
                    order by 
                        tglpb,kodeomi, nomorpb";

        $results = DB::select($sql, [
            'start' => $start,
            'end' => $end
        ]);

        return view('laporan.slomi_table', [
            'data' => $results,
            'start' => $start,
            'end' => $end
        ]);
    }
}