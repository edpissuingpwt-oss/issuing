<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EDP_SLHarian extends Controller
{
    public function slharian()
    {
        return view('edp.slharian');
    }

    public function getData(Request $request)
    {
        $request->validate([
            'tanggal_mulai' => 'required|date_format:d-m-Y',
            'toko' => 'required|in:IDM,OMI'
        ]);

        $tanggal = \Carbon\Carbon::createFromFormat('d-m-Y', $request->tanggal_mulai)
                    ->format('Ymd');

        $toko = $request->toko;
        $ip = $toko == 'IDM' ? 'JOB-IDM' : 'JOB-OMI';
        $sbu = $toko == 'IDM' ? 'I' : 'O';
        $rpb_user = $toko == 'IDM' ? 'IDM' : 'OMI';

        $query = "WITH tgl AS (
                                    SELECT ? AS tgl
                                )
                                SELECT
                                -- ================= JAM =================
                                q1.req,
                                q2.start_pick,
                                q3.end_scan,
                                q4.end_dspb,

                                -- ================= RUPIAH =================
                                q5.tgl_upload,
                                q5.rp_pb AS rp_pb_rupiah,
                                q5.rp_tolakan_md,
                                q6.rp_tag_all,
                                q6.rp_tag_md,
                                q6.rp_tag_ljm,
                                q5.rp_tolakan_ljm,
                                q5.rp_upload_dpd,
                                q5.rp_sl_dspb,
                                q5.rp_tidakpick,
                                q5.rp_sebagian,
                                q5.qty_stockekonomis,
                                q5.qty_ahot,
                                q5.persenqtyekonomis,
                                q5.persenqtyahot,

                                -- ================= REKAP =================
                                q7.toko,
                                q8.item_pb,
                                q8.qty_pb,
                                q8.rp_pb AS rp_pb_rumus,
                                q9.item_tolakan,
                                q9.qty_tolakan,
                                q9.rp_tolakan,
                                q10.item_upload,
                                q10.qty_upload,
                                q10.rp_upload,
                                q10.item_dspb,
                                q10.qty_dspb,
                                q10.rp_dspb

                                FROM tgl

                                -- ================= JAM =================
                                CROSS JOIN LATERAL (
                                                        SELECT CASE 
                                                                    WHEN ? = 'OMI' THEN 'CEK EMAIL'
                                                                    ELSE TO_CHAR(MIN(jki_firstrequest), 'HH24:mi')
                                                                END AS req
                                                        FROM igrpwt.JADWAL_KIRIM_IDM
                                                        WHERE to_char(jki_create_dt, 'YYYYMMDD') = tgl.tgl
                                                        AND jki_firstrequest IS NOT NULL
                                ) q1

                                CROSS JOIN LATERAL (
                                    SELECT LEFT(MIN(JAM_PICKING),5) AS start_pick
                                                        FROM igrpwt.dpd_idm_ora
                                                        WHERE tglupd::date = TO_DATE(tgl.tgl, 'YYYYMMDD')
                                                        AND TRNRAK <> 'BYPASS'
                                                        AND JAM_PICKING > '07:50:00'
                                                        AND fmksbu = ?
                                ) q2

                                CROSS JOIN LATERAL (
                                    SELECT              LEFT(
                                                            MAX(
                                                                CASE 
                                                                    WHEN pbo_userupdatechecker IS NOT NULL
                                                                    AND pbo_userupdatechecker <> 'OMI'
                                                                    THEN pbo_jamupdatechecker 
                                                                END
                                                            ), 
                                                        5) AS end_scan
                                                        FROM igrpwt.TBMASTER_PBOMI
                                                        WHERE to_char(PBO_CREATE_DT, 'YYYYMMDD') = tgl.tgl
                                                        AND PBO_RECORDID >= '4'
                                                        AND PBO_NOKOLI IS NOT NULL
                                                        AND PBO_KODESBU = ?
                                ) q3

                                CROSS JOIN LATERAL (
                                    SELECT TO_CHAR(MAX(RPB_CREATE_DT), 'HH24:mi') AS end_dspb
                                                        FROM igrpwt.TBTR_REALPB
                                                        WHERE to_char(RPB_CREATE_DT, 'YYYYMMDD') = tgl.tgl
                                                        AND RPB_CREATE_BY = ?
                                ) q4

                                -- ================= RUPIAH =================
                                CROSS JOIN LATERAL (
                                    SELECT TGL_UPLOAD, 
                                                                    Sum(QTY_PB) AS QTY_PB, 
                                                                    Sum(RP_PB) AS RP_PB,
                                                                    Sum(QTY_TOLAKAN_MD) AS QTY_TOLAKAN_MD, 
                                                                    Sum(RP_TOLAKAN_MD) AS RP_TOLAKAN_MD, 
                                                                    Sum(QTY_TOLAKAN_MD2) AS QTY_TOLAKAN_MD2, 
                                                                    Sum(RP_TOLAKAN_MD2) AS RP_TOLAKAN_MD2, 
                                                                    Sum(QTY_TOLAKAN_LJM) AS QTY_TOLAKAN_LJM, 
                                                                    Sum(RP_TOLAKAN_LJM) AS RP_TOLAKAN_LJM, 
                                                                    Sum(RP_UPLOAD_DPD) AS RP_UPLOAD_DPD, 
                                                                    Sum(RP_SL_DSPB) AS RP_SL_DSPB, 
                                                                    Sum(RP_TidakPick) AS RP_TidakPick, 
                                                                    Sum(RP_Sebagian) AS RP_Sebagian, 
                                                                    Sum(QTY_StockEkonomis) AS QTY_StockEkonomis, 
                                                                    Sum(QTY_AHOT) AS QTY_AHOT, 
                                                                    ROUND(Sum(RP_TOLAKAN_MD) / SUM(RP_PB)*100,2) AS PersenTolakanMD, 
                                                                    ROUND(Sum(RP_TOLAKAN_MD2) / SUM(RP_PB)*100,2) AS PersenTolakanMD2, 
                                                                    ROUND(Sum(RP_TOLAKAN_LJM) / SUM(RP_PB)*100,2) AS PersenTolakanLJM, 
                                                                    ROUND(Sum(RP_SL_DSPB) / SUM(RP_PB)*100,2) AS PersenRPSLDSPB, 
                                                                    CASE WHEN SUM(RP_UPLOAD_DPD) = 0 THEN 0 ELSE ROUND(Sum(RP_SL_DSPB) / SUM(RP_UPLOAD_DPD)*100,2) END AS PersenRPSLPicking, 
                                                                    CASE WHEN SUM(RP_UPLOAD_DPD) = 0 THEN 0 ELSE ROUND(Sum(RP_TidakPick) / SUM(RP_UPLOAD_DPD)*100,2) END AS PersenRPTidakPick, 
                                                                    CASE WHEN SUM(RP_UPLOAD_DPD) = 0 THEN 0 ELSE ROUND(Sum(RP_Sebagian) / SUM(RP_UPLOAD_DPD)*100,2) END AS PersenRPSebagian, 
                                                                    ROUND(Sum(QTY_StockEkonomis) / SUM(QTY_PB+QTY_TOLAKAN_MD+QTY_TOLAKAN_LJM)*100,2) AS PersenQtyEkonomis, 
                                                                    ROUND(Sum(QTY_AHOT) / SUM(QTY_PB+QTY_TOLAKAN_MD+QTY_TOLAKAN_LJM)*100,2) AS PersenQtyAHOT 
                                                                FROM 
                                                                ( 
                                                                SELECT PB.TGL_UPLOAD, 
                                                                    Sum(PB.QTY_PB) AS QTY_PB, 
                                                                    Sum(PB.RP_PB) AS RP_PB, 
                                                                    0 AS QTY_TOLAKAN_MD, 
                                                                    0 AS RP_TOLAKAN_MD, 
                                                                    0 AS QTY_TOLAKAN_MD2, 
                                                                    0 AS RP_TOLAKAN_MD2, 
                                                                    0 AS QTY_TOLAKAN_LJM, 
                                                                    0 AS RP_TOLAKAN_LJM, 
                                                                    Sum(PB.Rp3Plus) AS RP_UPLOAD_DPD, 
                                                                    Sum(PB.Rp4) AS RP_SL_DSPB, 
                                                                    Sum(CASE WHEN STATUS_PICK = 'NOL' THEN RP_Selisih ELSE 0 END) AS RP_TidakPick, 
                                                                    Sum(CASE WHEN STATUS_PICK = 'SEBAGIAN' THEN RP_Selisih ELSE 0 END) AS RP_Sebagian, 
                                                                    0 AS QTY_StockEkonomis, 
                                                                    SUM(CASE WHEN REGEXP_LIKE(TAGIGR,'A|H|O|T') OR  REGEXP_LIKE(TAGIDM,'A|H|O|T') THEN QTY_PB ELSE 0 END) AS QTY_AHOT 
                                                                FROM IGRPWT.SL_PB AS PB 
                                                                WHERE PB.TGL_UPLOAD::date = TO_DATE(tgl.tgl, 'YYYYMMDD')
                                                                and ip = '$ip'
                                                                GROUP BY TGL_UPLOAD 
                                                                UNION ALL 
                                                                SELECT TLK.TGL_UPLOAD, 
                                                                    0 AS QTY_PB, 
                                                                    Sum(RP_PB) AS RP_PB, 
                                                                    Sum(CASE WHEN TLK.PIC = 'MD' THEN TLK.QTY_PB ELSE 0 END * CASE STATUS_TOLAKAN WHEN 'PLU TIDAK TERDAFTAR DI TBMASTER_PRODCRM' THEN 0 WHEN 'PLU IGR PADA PRODCRM TIDAK ADA DI PRODMAST' THEN 0 ELSE 1 END) AS QTY_TOLAKAN_MD, 
                                                                    Sum(CASE WHEN TLK.PIC = 'MD' THEN TLK.RP_PB ELSE 0 END * CASE STATUS_TOLAKAN WHEN 'PLU TIDAK TERDAFTAR DI TBMASTER_PRODCRM' THEN 0 WHEN 'PLU IGR PADA PRODCRM TIDAK ADA DI PRODMAST' THEN 0 ELSE 1 END) AS RP_TOLAKAN_MD, 
                                                                    Sum(CASE WHEN TLK.PIC = 'MD' THEN TLK.QTY_PB ELSE 0 END * CASE STATUS_TOLAKAN WHEN 'PLU TIDAK TERDAFTAR DI TBMASTER_PRODCRM' THEN 1 WHEN 'PLU IGR PADA PRODCRM TIDAK ADA DI PRODMAST' THEN 1 ELSE 0 END) AS QTY_TOLAKAN_MD2, 
                                                                    Sum(CASE WHEN TLK.PIC = 'MD' THEN TLK.RP_PB ELSE 0 END * CASE STATUS_TOLAKAN WHEN 'PLU TIDAK TERDAFTAR DI TBMASTER_PRODCRM' THEN 1 WHEN 'PLU IGR PADA PRODCRM TIDAK ADA DI PRODMAST' THEN 1 ELSE 0 END) AS RP_TOLAKAN_MD2, 
                                                                    Sum(CASE WHEN TLK.PIC = 'LJM' THEN TLK.QTY_PB ELSE 0 END) AS QTY_TOLAKAN_LJM, 
                                                                    Sum(CASE WHEN TLK.PIC = 'LJM' THEN TLK.RP_PB ELSE 0 END) AS RP_TOLAKAN_LJM, 
                                                                    0 AS RP_UPLOAD_DPD, 
                                                                    0 AS RP_SL_DSPB, 
                                                                    0 AS RP_TidakPick, 
                                                                    0 AS RP_Sebagian, 
                                                                    SUM(CASE WHEN TLK.STATUS_TOLAKAN LIKE 'STOCK EKONOMIS TIDAK MENCUKUPI%' THEN QTY_PB ELSE 0 END) AS QTY_StockEkonomis, 
                                                                    0 AS QTY_AHOT 
                                                                FROM IGRPWT.SL_TOLAKAN TLK   
                                                                WHERE TLK.TGL_UPLOAD::date = TO_DATE(tgl.tgl, 'YYYYMMDD')
                                                                AND ip = '$ip'
                                                                GROUP BY TGL_UPLOAD 
                                                                ) as SUMM 
                                                                GROUP BY TGL_UPLOAD
                                ) q5

                                CROSS JOIN LATERAL (
                                    select coalesce(sum(rp_selisih),0) as rp_tag_all,
                                                        COALESCE(SUM(CASE WHEN pic = 'LJM' THEN rp_selisih END), 0) AS rp_tag_ljm,
                                                        COALESCE(SUM(CASE WHEN pic = 'MD' THEN rp_selisih END), 0) AS rp_tag_md
                                                        from (
                                                        SELECT TGL_UPLOAD, 
                                                                    PIC, 
                                                                    RAK, 
                                                                    FLAG, 
                                                                    PLUIDM, 
                                                                    PLUIGR, 
                                                                    DESKRIPSI, 
                                                                    SATUAN, 
                                                                    CASE WHEN SUBSTR(PLUIGR,1,6)||'0' IN ('1609710','1622050','1651220','1735440','1735450','1735460','1759600','1803830','1833150','1992880','1993350') THEN 'ONESHOOT'
                                                                    ELSE TAGIGR END AS TAGIGR, 
                                                                    TAGIDM, 
                                                                    COALESCE(PKMT,0) as PKMT, 
                                                                    COALESCE(PKM,0) as PKM, 
                                                                    COALESCE(KOEF,0) as KOEF, 
                                                                    COALESCE(LT,0) as LT, 
                                                                    COALESCE(TOKO_ORDER,0) as TOKO_ORDER, 
                                                                    COALESCE(TOKO_REAL,0) as TOKO_REAL, 
                                                                    COALESCE(QTY_PB,0) as QTY_PB, 
                                                                    COALESCE(QTY_REAL,0) as QTY_REAL, 
                                                                    CASE WHEN COALESCE(QTY_EKO,0) > 0 AND STATUS_TOLAKAN LIKE 'STOCK EKONOMIS TIDAK MENCUKUPI%' 
                                                                        THEN COALESCE(QTY_PB,0) - COALESCE(QTY_EKO,0) 
                                                                        ELSE COALESCE(QTY_PB,0) 
                                                                    END AS QTY_TOLAK, 
                                                                    COALESCE(RP_PB,0) as RP_PB, 
                                                                    COALESCE(RP_REAL,0) as RP_REAL, 
                                                                    COALESCE(RP_SELISIH,0) as RP_SELISIH, 
                                                                    COALESCE(MPLUS,0) as MPLUS, 
                                                                    COALESCE(MAXPLANO,0) as MAXPLANO, 
                                                                    COALESCE(MAXPLANOTOKO,0) as MAXPLANOTOKO, 
                                                                    COALESCE(MAXPLANODPD,0) as MAXPLANODPD, 
                                                                    COALESCE(LPP,0) as LPP, 
                                                                    COALESCE(QTY_EKO,0) AS QTY_EKO, 
                                                                    COALESCE(PO_OUT,0) as PO_OUT, 
                                                                    STATUS_STOCK, 
                                                                    COALESCE(PLANO_DPD,0) as PLANO_DPD, 
                                                                    COALESCE(PLANO_TOKO,0) as PLANO_TOKO, 
                                                                    STATUS_PICK, 
                                                                    COALESCE(KPHMEAN,0) as KPHMEAN, 
                                                                    STATUS_TOLAKAN, 
                                                                    MINOR, 
                                                                    MINJUAL 
                                                                FROM IGRPWT.SL_TOLAKAN 
                                                                WHERE TGL_UPLOAD::date = TO_DATE(tgl.tgl, 'YYYYMMDD')
                                                                AND ip = '$ip'
                                                                ORDER BY TGL_UPLOAD,rak,pluigr,pluidm) sq6
                                                                where tagigr in ('T','V','O','N','A','H','X','I','G','U','ONESHOOT') 
                                ) q6

                                -- ================= REKAP =================
                                CROSS JOIN LATERAL (
                                    SELECT 
                                        CASE 
                                            WHEN ? = 'IDM' THEN (
                                                SELECT COUNT(DISTINCT JKI_TOKO)
                                                FROM IGRPWT.JADWAL_KIRIM_IDM
                                                WHERE DATE(JKI_TANGGAL) = TO_DATE(tgl.tgl, 'YYYYMMDD')
                                                AND JKI_FIRSTREQUEST IS NOT NULL
                                            )

                                            WHEN ? = 'OMI' THEN (
                                                SELECT COUNT(HPBI_KODETOKO)
                                                FROM IGRPWT.TBTR_HEADER_PBIDM
                                                WHERE HPBI_CREATE_DT::DATE = TO_DATE(tgl.tgl, 'YYYYMMDD')
                                                AND HPBI_KODETOKO LIKE 'O%'
                                            )

                                            ELSE 0
                                        END AS toko
                                ) q7

                                CROSS JOIN LATERAL (
                                    SELECT 
                                                                    count(pluigr) item_pb,
                                                                    sum(qty_pb) qty_pb, 
                                                                    COALESCE(SUM(CASE WHEN tagigr in ('T','V','O','N','A','H','X','I','G','U','ONESHOOT') AND STATUS_TOLAKAN IS NOT NULL THEN 0 ELSE rp_pb END), 0) AS rp_pb
                                                                    FROM (
                                                                    SELECT TGL_UPLOAD, 
                                                                                RAK, 
                                                                                FLAG, 
                                                                                PLUIDM, 
                                                                                SUBSTR(PLUIGR,1,6)||'0' as PLUIGR, 
                                                                                DESKRIPSI, 
                                                                                SATUAN, 
                                                                                CASE WHEN SUBSTR(PLUIGR,1,6)||'0' IN ('1609710','1622050','1651220','1735440','1735450','1735460','1759600','1803830','1833150','1992880','1993350') THEN 'ONESHOOT'
                                                                                ELSE TAGIGR END AS TAGIGR, 
                                                                                TAGIDM TAGIDM, 
                                                                                COALESCE(PKMT,0) as PKMT, 
                                                                                COALESCE(PKM,0) as PKM, 
                                                                                COALESCE(KOEF,0) as KOEF, 
                                                                                COALESCE(LT,0) as LT, 
                                                                                COALESCE(Max(TOKO_ORDER),0) as TOKO_ORDER, 
                                                                                COALESCE(Max(TOKO_REAL),0) as TOKO_REAL, 
                                                                                COALESCE(Sum(QTY_PB),0) as QTY_PB, 
                                                                                COALESCE(Sum(QTY_REAL),0) as QTY_REAL, 
                                                                                COALESCE(Sum(RP_PB),0) as RP_PB, 
                                                                                COALESCE(Sum(RP4),0) as RP_REAL, 
                                                                                COALESCE(Sum(RP_SELISIH),0) as RP_SELISIH, 
                                                                                COALESCE(Max(MPLUS),0) as MPLUS, 
                                                                                COALESCE(Max(MAXPLANO),0) as MAXPLANO, 
                                                                                COALESCE(Max(MAXPLANOTOKO),0) as MAXPLANOTOKO, 
                                                                                COALESCE(Max(MAXPLANODPD),0) as MAXPLANODPD, 
                                                                                COALESCE(Sum(RP3PLUS),0) as RP3PLUS, 
                                                                                COALESCE(Sum(QTY4),0) as QTY4, 
                                                                                COALESCE(Sum(RP4),0) as RP4, 
                                                                                COALESCE(Max(LPP),0) as LPP, 
                                                                                COALESCE(Sum(QTY_EKO),0) as QTY_EKO, 
                                                                                COALESCE(Max(PO_OUT),0) as PO_OUT,  
                                                                                Max(STATUS_STOCK) as STATUS_STOCK, 
                                                                                COALESCE(Max(PLANO_DPD),0) as PLANO_DPD, 
                                                                                COALESCE(Max(PLANO_TOKO),0) as PLANO_TOKO, 
                                                                                CASE WHEN Max(STATUS_PICK) = 'FULL' AND Max(STATUS_TOLAKAN) LIKE 'STOCK EKONOMIS TIDAK MENCUKUPI%' THEN 'SEBAGIAN' ELSE Max(STATUS_PICK) END as STATUS_PICK, 
                                                                                COALESCE(Max(KPHMEAN),0) as KPHMEAN, 
                                                                                Max(STATUS_TOLAKAN) as STATUS_TOLAKAN 
                                                                            FROM 
                                                                            ( 
                                                                            SELECT TGL_UPLOAD, 
                                                                                RAK, 
                                                                                FLAG, 
                                                                                PLUIDM, 
                                                                                PLUIGR, 
                                                                                DESKRIPSI, 
                                                                                SATUAN, 
                                                                                CASE WHEN SUBSTR(PLUIGR,1,6)||'0' IN ('1609710','1622050','1651220','1735440','1735450','1735460','1759600','1803830','1833150','1992880','1993350') THEN 'ONESHOOT'
                                                                                ELSE TAGIGR END AS TAGIGR, 
                                                                                TAGIDM, 
                                                                                PKMT, 
                                                                                PKM, 
                                                                                KOEF, 
                                                                                LT, 
                                                                                TOKO_ORDER, 
                                                                                TOKO_REAL, 
                                                                                QTY_PB QTY_PB, 
                                                                                QTY_REAL, 
                                                                                RP_PB RP_PB, 
                                                                                RP_REAL, 
                                                                                RP_SELISIH, 
                                                                                MPLUS, 
                                                                                MAXPLANO, 
                                                                                MAXPLANOTOKO, 
                                                                                MAXPLANODPD, 
                                                                                RP3PLUS, 
                                                                                QTY4, 
                                                                                RP4, 
                                                                                LPP, 
                                                                                QTY_EKO AS QTY_EKO, 
                                                                                PO_OUT, 
                                                                                STATUS_STOCK, 
                                                                                PLANO_DPD, 
                                                                                PLANO_TOKO, 
                                                                                STATUS_PICK, 
                                                                                KPHMEAN, 
                                                                                STATUS_TOLAKAN 
                                                                            From IGRPWT.Sl_Pb 
                                                                            WHERE TGL_UPLOAD::date = TO_DATE(tgl.tgl, 'YYYYMMDD')
                                                                            and ip = '$ip'
                                                                            UNION ALL 
                                                                            SELECT TGL_UPLOAD, 
                                                                                RAK, 
                                                                                FLAG, 
                                                                                PLUIDM, 
                                                                                PLUIGR, 
                                                                                DESKRIPSI, 
                                                                                SATUAN, 
                                                                                CASE WHEN SUBSTR(PLUIGR,1,6)||'0' IN ('1609710','1622050','1651220','1735440','1735450','1735460','1759600','1803830','1833150','1992880','1993350') THEN 'ONESHOOT'
                                                                                ELSE TAGIGR END AS TAGIGR, 
                                                                                TAGIDM, 
                                                                                PKMT, 
                                                                                PKM, 
                                                                                KOEF, 
                                                                                LT, 
                                                                                TOKO_ORDER, 
                                                                                TOKO_REAL, 
                                                                                QTY_PB, 
                                                                                QTY_REAL, 
                                                                                RP_PB, 
                                                                                RP_REAL, 
                                                                                RP_SELISIH, 
                                                                                MPLUS, 
                                                                                MAXPLANO, 
                                                                                MAXPLANOTOKO, 
                                                                                MAXPLANODPD, 
                                                                                0 AS RP3PLUS, 
                                                                                0 AS QTY4, 
                                                                                0 AS RP4, 
                                                                                LPP, 
                                                                                QTY_PB AS QTY_EKO, 
                                                                                PO_OUT, 
                                                                                STATUS_STOCK, 
                                                                                PLANO_DPD, 
                                                                                PLANO_TOKO, 
                                                                                STATUS_PICK, 
                                                                                KPHMEAN, 
                                                                                STATUS_TOLAKAN 
                                                                            From IGRPWT.Sl_Tolakan 
                                                                            WHERE TGL_UPLOAD::date = TO_DATE(tgl.tgl, 'YYYYMMDD')
                                                                            and ip = '$ip'
                                                                            AND NOT REGEXP_LIKE(STATUS_TOLAKAN,'PLU TIDAK TERDAFTAR DI TBMASTER_PRODCRM|PLU IGR PADA PRODCRM TIDAK ADA DI PRODMAST|PLU IDM TIDAK MEMPUNYAI PLU INDOGROSIR') 
                                                                            AND status_tolakan NOT LIKE '%STOCK EKONOMIS%' 
                                                                            ) AS UPD 
                                                                            GROUP BY TGL_UPLOAD,  
                                                                                RAK,  
                                                                                FLAG,  
                                                                                PLUIDM,  
                                                                                SUBSTR(PLUIGR,1,6)||'0',  
                                                                                DESKRIPSI,  
                                                                                SATUAN,  
                                                                                TAGIGR,  
                                                                                TAGIDM,  
                                                                                COALESCE(PKMT,0),  
                                                                                COALESCE(PKM,0),  
                                                                                COALESCE(KOEF,0),  
                                                                                COALESCE(LT,0) 
                                                                            UNION 
                                                                            SELECT TGL_UPLOAD,  
                                                                                RAK,  
                                                                                FLAG,  
                                                                                PLUIDM,  
                                                                                SUBSTR(PLUIGR,1,6)||'0' as PLUIGR,  
                                                                                DESKRIPSI,  
                                                                                SATUAN,  
                                                                                CASE WHEN SUBSTR(PLUIGR,1,6)||'0' IN ('1609710','1622050','1651220','1735440','1735450','1735460','1759600','1803830','1833150','1992880','1993350') THEN 'ONESHOOT'
                                                                                ELSE TAGIGR END AS TAGIGR,  
                                                                                TAGIDM,  
                                                                                PKMT,  
                                                                                PKM,  
                                                                                KOEF,  
                                                                                LT,  
                                                                                TOKO_ORDER,  
                                                                                TOKO_REAL,  
                                                                                QTY_PB,  
                                                                                QTY_REAL,  
                                                                                RP_PB,  
                                                                                RP_REAL,  
                                                                                RP_SELISIH,  
                                                                                MPLUS,  
                                                                                MAXPLANO,  
                                                                                MAXPLANOTOKO, 
                                                                                MAXPLANODPD, 
                                                                                0 AS RP3PLUS,  
                                                                                0 AS QTY4,  
                                                                                0 AS RP4,  
                                                                                LPP,  
                                                                                QTY_PB AS QTY_EKO,  
                                                                                PO_OUT,  
                                                                                STATUS_STOCK,  
                                                                                PLANO_DPD,  
                                                                                PLANO_TOKO,  
                                                                                STATUS_PICK,  
                                                                                KPHMEAN,  
                                                                                STATUS_TOLAKAN  
                                                                            From IGRPWT.Sl_Tolakan  
                                                                            WHERE TGL_UPLOAD::date = TO_DATE(tgl.tgl, 'YYYYMMDD')
                                                                            and ip = '$ip'
                                                                            AND status_tolakan LIKE '%STOCK EKONOMIS%' 
                                                                            ) sq8
                                ) q8

                                CROSS JOIN LATERAL (
                                    SELECT 
                                                                            COUNT(PLUIGR) AS item_tolakan,
                                                                            SUM(QTY_TOLAK) AS qty_tolakan,
                                                                            SUM(
                                                                                CASE 
                                                                                    WHEN TAGIGR IN ('T','V','O','N','A','H','X','I','G','U','ONESHOOT')
                                                                                    THEN 0
                                                                                    ELSE RP_SELISIH
                                                                                END
                                                                            ) AS rp_tolakan
                                                                        FROM (
                                                                            SELECT 
                                                                                TGL_UPLOAD, PIC, RAK, FLAG, PLUIDM, PLUIGR, DESKRIPSI, SATUAN,

                                                                                CASE 
                                                                                    WHEN SUBSTR(PLUIGR,1,6)||'0' IN 
                                                                                    ('1609710','1622050','1651220','1735440','1735450','1735460',
                                                                                    '1759600','1803830','1833150','1992880','1993350') 
                                                                                    THEN 'ONESHOOT'
                                                                                    ELSE TAGIGR 
                                                                                END AS TAGIGR,

                                                                                TAGIDM,
                                                                                COALESCE(QTY_PB,0) AS QTY_PB,
                                                                                COALESCE(QTY_EKO,0) AS QTY_EKO,

                                                                                CASE 
                                                                                    WHEN COALESCE(QTY_EKO,0) > 0 
                                                                                        AND STATUS_TOLAKAN LIKE 'STOCK EKONOMIS TIDAK MENCUKUPI%' 
                                                                                    THEN COALESCE(QTY_PB,0) - COALESCE(QTY_EKO,0)
                                                                                    ELSE COALESCE(QTY_PB,0)
                                                                                END AS QTY_TOLAK,

                                                                                COALESCE(RP_SELISIH,0) AS RP_SELISIH,
                                                                                STATUS_TOLAKAN,
                                                                                MINOR,
                                                                                MINJUAL

                                                                            FROM IGRPWT.SL_TOLAKAN
                                                                            WHERE TGL_UPLOAD::date = TO_DATE(tgl.tgl, 'YYYYMMDD')
                                                                            AND ip = '$ip'
                                                                        ) sq9
                                ) q9

                                CROSS JOIN LATERAL (
                                    SELECT 
                                                                    count(pluigr) item_upload,
                                                                    sum(qty_pb) qty_upload,
                                                                    coalesce(sum(rp_pb),0) as rp_upload,
                                                                    COUNT(pluigr) FILTER (WHERE qty_real <> 0) AS item_dspb,
                                                                    sum(qty_real) qty_dspb,
                                                                    coalesce(sum(rp_real),0) as rp_dspb
                                                                    FROM (
                                                                    SELECT TGL_UPLOAD, 
                                                                                RAK, 
                                                                                FLAG, 
                                                                                PLUIDM, 
                                                                                PLUIGR, 
                                                                                DESKRIPSI, 
                                                                                SATUAN, 
                                                                                TAGIGR, 
                                                                                TAGIDM, 
                                                                                COALESCE(PKMT,0) as PKMT, 
                                                                                COALESCE(PKM,0) as PKM, 
                                                                                COALESCE(KOEF,0) as KOEF, 
                                                                                COALESCE(LT,0) as LT, 
                                                                                COALESCE(TOKO_ORDER,0) as TOKO_ORDER, 
                                                                                COALESCE(TOKO_REAL,0) as TOKO_REAL, 
                                                                                COALESCE(QTY_PB,0) as QTY_PB, 
                                                                                COALESCE(QTY_REAL,0) as QTY_REAL, 
                                                                                COALESCE(RP_PB,0) as RP_PB, 
                                                                                COALESCE(RP_REAL,0) as RP_REAL, 
                                                                                COALESCE(RP_SELISIH,0) as RP_SELISIH, 
                                                                                COALESCE(MPLUS,0) as MPLUS, 
                                                                                COALESCE(MAXPLANO,0) as MAXPLANO, 
                                                                                COALESCE(MAXPLANOTOKO,0) as MAXPLANOTOKO, 
                                                                                COALESCE(MAXPLANODPD,0) as MAXPLANODPD, 
                                                                                COALESCE(RP3PLUS,0) as RP3PLUS, 
                                                                                COALESCE(QTY4,0) as QTY4, 
                                                                                COALESCE(RP4,0) as RP4, 
                                                                                COALESCE(LPP,0) as LPP, 
                                                                                COALESCE(QTY_EKO,0) AS QTY_EKO, 
                                                                                COALESCE(PO_OUT,0) as PO_OUT, 
                                                                                STATUS_STOCK, 
                                                                                COALESCE(PLANO_DPD,0) as PLANO_DPD, 
                                                                                COALESCE(PLANO_TOKO,0) as PLANO_TOKO, 
                                                                                STATUS_PICK, 
                                                                                COALESCE(KPHMEAN,0) as KPHMEAN, 
                                                                                STATUS_TOLAKAN 
                                                                            FROM IGRPWT.SL_PB 
                                                                            WHERE TGL_UPLOAD::date = TO_DATE(tgl.tgl, 'YYYYMMDD')
                                                                            AND Rp3Plus > 0 
                                                                            and ip = '$ip'
                                                                            ORDER BY TGL_UPLOAD,rak,PLUIGR,PLUIDM) sq10
                                ) q10";


        $data = DB::selectOne($query, [
                $tanggal,   // 1 (WITH tgl)
                $toko,      // 2 (CASE WHEN ? = 'OMI')
                $sbu,       // 3 (fmksbu)
                $sbu,       // 4 (PBO_KODESBU)
                $rpb_user,  // 4 (RPB_CREATE_BY)
                $toko,      // 5 (HPBI filter)
                $toko       // 6 (HPBI filter)
        ]);

        return view('edp.slharian_table', [
            'data' => $data,
            'toko' => $toko
        ]);
    }
}
