<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;


class TolakanpbController extends Controller
{   
    public function tolakanpb(Request $request): Response
    {        
        $tolakanidm = DB::table(DB::raw("(SELECT DISTINCT tlko_kettolakan
                FROM   igrpwt.tbtr_tolakanpbomi
                WHERE  date(tlko_create_dt) > date(current_date) - 60
                ORDER  BY tlko_kettolakan) as tlki"))
        ->select(DB::raw("*"))
        ->get();

        // dd($div);

        return response()
            ->view("laporan.tolakanpb", [
                "tolakanidm" => $tolakanidm
            ]);
    }

    public function getData(Request $request)
    {
        // Ambil parameter dari form/AJAX
        $jenisLaporan = $request->jenis;              // 1 = per produk, 2 = per produk per toko
        $kodeTolakan  = $request->keterangan ?? 'All';
        $jenisToko = strtoupper(trim($request->jenis_toko ?? 'OMI_IDM'));
        $tanggalMulaiInput   = $request->tanggal_mulai;
        $tanggalSelesaiInput = $request->tanggal_selesai;
        $pluigr = trim($request->pluigr ?? '');
        

        // Validasi input wajib
        if (!$tanggalMulaiInput || !$tanggalSelesaiInput) {
            return response()->json(['error' => 'Tanggal awal dan akhir harus diisi.'], 400);
        }

        // Format tanggal agar sesuai PostgreSQL
        $tanggalMulai   = date("d-M-Y", strtotime($tanggalMulaiInput));
        $tanggalSelesai = date("d-M-Y", strtotime($tanggalSelesaiInput));

        // Tentukan kolom tanggal berdasarkan jenis toko
        if ($jenisToko === 'OMI') {
            $kolomTanggal = 'tlko_create_dt';
        } elseif ($jenisToko === 'IDM') {
            $kolomTanggal = 'tlko_tglpb';
        } else {
            // Default fallback (jika nanti ada tambahan tipe)
            $kolomTanggal = 'tlko_tglpb';
        }

        // === Query dasar ===
        $baseQuery = "SELECT 
                            t.tlko_tglpb AS tanggal,
                            t.tlko_create_dt AS tanggal2,
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
                            COALESCE(t.TLKO_QTYORDER, 0) * COALESCE(t.TLKO_LASTCOST, 0) AS tlko_nilai
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
                        WHERE date($kolomTanggal) BETWEEN to_date(?, 'DD-MON-YYYY')
                        AND to_date(?, 'DD-MON-YYYY')";

        $bindings = [$tanggalMulai, $tanggalSelesai];

         // === Filter tambahan ===
        if ($kodeTolakan != 'All') {
            $baseQuery .= " AND t.TLKO_KETTOLAKAN = ?";
            $bindings[] = $kodeTolakan;
        }

        /* FILTER PLUIGR KHUSUS JENIS LAPORAN 2 */
        if ($jenisLaporan == "2" && !empty($pluigr)) {
            $baseQuery .= " 
                AND (
                    SUBSTR(t.TLKO_PLUIGR, 1, 6) || '0' ILIKE ?
                    OR t.TLKO_PLUOMI ILIKE ?
                )
            ";

            $bindings[] = "%{$pluigr}%";
            $bindings[] = "%{$pluigr}%";
        }

        if ($jenisToko == 'OMI') {
            $baseQuery .= " AND TKOIGR.TKO_KODESBU = 'O'";
        } elseif ($jenisToko == 'IDM') {
            $baseQuery .= " AND TKOIGR.TKO_KODESBU= 'I'";
        }

        // === Jenis laporan ===
        if ($jenisLaporan == "1") {
            // Laporan per produk
            $query = "SELECT DIV,DEPT,KATB,
                            pluomi, pluigr, desk, frac, acostpcs, out_qty,
                            tagigr, tagidm, tagomi, flag, tlko_kettolakan, pkmp_mplusi,
                            MIN(lpp) AS tlko_lpp,
                            SUM(tlko_qtyorder) AS tlko_qtyorder,
                            SUM(tlko_nilai) AS tlko_nilai,
                            COUNT(DISTINCT(kodetoko)) AS tlko_kode_omi,
                            COUNT(DISTINCT(kodetoko || nopb)) AS tlko_nopb,
                            COUNT(DISTINCT(tanggal)) AS tlko_tanggal,
                            to_char(LASTBPB, 'dd-Mon-yy') as LASTBPB
                        FROM ($baseQuery) t1
                        WHERE pluomi IS NOT NULL
                        GROUP BY div, dept, katb, pluomi, pluigr, desk,  tagigr, flag, acostpcs, out_qty, tlko_kettolakan, tagidm, tagomi, pkmp_mplusi, frac, LASTBPB
                        ORDER BY tlko_kettolakan, pluomi";
        } elseif ($jenisLaporan == "2") {
            // Laporan per produk per toko
            $query = "SELECT            kodetoko,
                                        namatoko,
                                        nopb,
                                        DIV,DEPT,KATB,
                                        pluomi,
                                        pluigr,
                                        desk,
                                        frac,
                                        acostpcs,
                                        tagigr,
                                        tagidm,
                                        tagomi,
                                        flag,
                                        tlko_kettolakan,
                                        to_char(LASTBPB, 'dd-Mon-yy') as LASTBPB,
                                        MIN(lpp)                    AS tlko_lpp,
                                        SUM(tlko_qtyorder)               AS tlko_qtyorder,
                                        SUM(tlko_nilai)                  AS tlko_nilai
                        FROM ($baseQuery) t1
                        WHERE pluomi IS NOT NULL
                        GROUP  BY       kodetoko,
                                        namatoko,
                                        nopb,
                                        div,
                                        dept,
                                        katb,
                                        pluomi,
                                        pluigr,
                                        desk,
                                        frac,
                                        tagigr,
                                        tagidm,
                                        tagomi,
                                        flag,
                                        acostpcs,
                                        tlko_kettolakan,
                                        LASTBPB
                        ORDER  BY       tlko_kettolakan,
                                        pluomi";
        }
        else {
            return response()->json([
                'error' => 'Jenis laporan tidak valid.'
            ], 400);
        }

        // dd($jenisToko, $baseQuery, $bindings);
        
        // === Jalankan query ===
        $data = DB::select($query, $bindings);

        // === Kembalikan view HTML ===
        return view('laporan.tolakanpb_table', [
            'data' => $data,
            'jenisLaporan' => $jenisLaporan,
            'kodeTolakan' => $kodeTolakan,
            'jenisToko' => $jenisToko,
            'tanggalMulai' => $tanggalMulaiInput,
            'tanggalSelesai' => $tanggalSelesaiInput,
            'pluigr' => $pluigr
        ]);
    }

}
