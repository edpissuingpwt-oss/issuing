<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function slpot()
    {
        return view('laporan.slpot');
    }

    public function slpot_h(Request $request)
    {
        $jenis = $request->input('jenis');
        $tanggal = $request->input('tanggal');

        if (!$jenis || !$tanggal) {
            return response()->json(['error' => 'Jenis dan tanggal wajib diisi'], 400);
        }

        if ($jenis === 'tolak') {
            $query = "SELECT 
                            hdp_tgltransaksi tgl_pb,
                            hdp_kodetoko kode_toko,
                            hdp_nopb no_pb,
                            tlko_pluigr pluigr,
                            tlko_pluomi pluidm,
                            prd_deskripsipanjang desk,
                            tlko_ptag kodetag,
                            tlko_kettolakan keterangan,
                            tlko_qtyorder qtyorder
                        FROM 
                            igrpwt.tbtr_tolakanpbomi
                        LEFT JOIN 
                            igrpwt.tbtr_header_pot ON hdp_kodetoko = tlko_kodeomi AND hdp_nopb = tlko_nopb
                        LEFT JOIN 
                            igrpwt.tbmaster_prodmast ON tlko_pluomi = prd_plumcg AND prd_prdcd LIKE '%0'
                        WHERE 
                            TO_CHAR(hdp_tgltransaksi, 'DD-MM-YYYY') = :tanggal
                    ";
        } elseif ($jenis === 'real') {
            $query = "SELECT 
                            hdp_tgltransaksi tgl_trans,
                            hdp_kodetoko kode_toko,
                            hdp_nopb no_pb,
                            pbo_nokoli no_koli,
                            pbo_pluigr pluigr,
                            pbo_pluomi pluidm,
                            prd_deskripsipanjang desk,
                            pbo_qtyorder qtyorder, 
                            pbo_qtyrealisasi qtyreal,
                            pbo_ttlnilai dpp,
                            pbo_ttlppn ppn
                        FROM 
                            igrpwt.tbmaster_pbomi
                        LEFT JOIN 
                            igrpwt.tbtr_header_pot ON pbo_kodeomi = hdp_kodetoko AND pbo_nopb = hdp_nopb
                        LEFT JOIN 
                            igrpwt.tbmaster_prodmast ON pbo_pluomi = prd_plumcg AND prd_prdcd LIKE '%0'
                        WHERE 
                            TO_CHAR(hdp_tgltransaksi, 'DD-MM-YYYY') = :tanggal
                        ORDER BY 
                            hdp_kodetoko, hdp_nopb
                    ";
        } else {
            return response()->json(['error' => 'Jenis tidak valid'], 400);
        }

        $results = DB::select($query, ['tanggal' => $tanggal]);

        return view('laporan.partials.slpot_h', [
            'data' => $results,
            'jenis' => $jenis,
            'tanggal' => $tanggal,
        ]);
    }

    public function wtidm()
    {
        return view('laporan.wtidm');
    }

    public function wtidm_h(Request $request)
    {
        try {
            $flag = $request->input('flag');

            if ($flag === 'reguler') {
                $query = "SELECT
                        'GI47' AS supco,
                        rpb_idsuratjalan AS nosj,
                        rpb_create_dt AS tglsj,
                        TO_CHAR(rpb_create_dt, 'HH24:MI:SS') AS jamsj,
                        rpb_kodeomi AS toko,
                        'G309' AS kcab,
                        tko_namaomi AS namatoko,
                        rpb_nodokumen AS nopb,
                        item,
                        qty,
                        rpdspb,
                        ppndspb,
                        total,
                        NPB_FILE,
                        NPB_JENIS
                    FROM (
                        SELECT rpb_idsuratjalan, rpb_kodeomi, rpb_create_dt, rpb_nodokumen,  
                            COUNT(rpb_plu1) AS item,
                            SUM(rpb_qtyrealisasi) AS qty,
                            SUM(rpb_ttlnilai) AS rpdspb,
                            SUM(rpb_ttlppn) AS ppndspb,
                            SUM(rpb_ttlnilai) + SUM(rpb_ttlppn) AS total
                        FROM igrpwt.tbtr_realpb
                        WHERE rpb_flag = '4'
                        AND rpb_kodeomi NOT LIKE 'O%'
                        AND rpb_kodeomi NOT LIKE 'M%'
                        GROUP BY rpb_idsuratjalan, rpb_kodeomi, rpb_create_dt, rpb_nodokumen
                    ) AS realpb  
                    LEFT JOIN igrpwt.TBMASTER_TOKOIGR TKO
                    ON realpb.rpb_kodeomi = TKO.TKO_KODEOMI
                    LEFT JOIN igrpwt.LOG_NPB log_npb
                    ON log_npb.NPB_KODETOKO = realpb.rpb_kodeomi
                    AND log_npb.NPB_NODSPB = realpb.rpb_idsuratjalan
                    WHERE log_npb.NPB_JENIS = 'DRY'
                    ORDER BY rpb_create_dt"; // query reguler
            } else {
                $query = "SELECT
                                'GI47' AS supco,
                                rpb_idsuratjalan AS nosj,
                                rpb_create_dt AS tglsj,
                                TO_CHAR(rpb_create_dt, 'HH24:MI:SS') AS jamsj,
                                rpb_kodeomi AS toko,
                                'G309' AS kcab,
                                tko_namaomi AS namatoko,
                                rpb_nodokumen AS nopb,
                                item,
                                qty,
                                rpdspb,
                                ppndspb,
                                total,
                                NPB_FILE
                            FROM (
                                SELECT rpb_idsuratjalan, rpb_kodeomi, rpb_create_dt, rpb_nodokumen,  
                                    COUNT(rpb_plu1) AS item,
                                    SUM(rpb_qtyrealisasi) AS qty,
                                    SUM(rpb_ttlnilai) AS rpdspb,
                                    SUM(rpb_ttlppn) AS ppndspb,
                                    SUM(rpb_ttlnilai) + SUM(rpb_ttlppn) AS total
                                FROM igrpwt.tbtr_realpb
                                WHERE rpb_flag = '4'
                                AND rpb_kodeomi NOT LIKE 'O%'
                                AND rpb_kodeomi NOT LIKE 'M%'
                                GROUP BY rpb_idsuratjalan, rpb_kodeomi, rpb_create_dt, rpb_nodokumen
                            ) AS realpb  
                            LEFT JOIN igrpwt.TBMASTER_TOKOIGR TKO
                            ON realpb.rpb_kodeomi = TKO.TKO_KODEOMI
                            LEFT JOIN igrpwt.LOG_NPB log_npb
                            ON log_npb.NPB_KODETOKO = realpb.rpb_kodeomi
                            AND log_npb.NPB_NODSPB = realpb.rpb_idsuratjalan
                            WHERE rpb_nodokumen LIKE '0%'
                            ORDER BY rpb_create_dt"; // query pot
            }

            $results = DB::select($query);

            return view('laporan.partials.wtidm_h', compact('results', 'flag'));

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }

    public function sphomi()
    {
        $query = "SELECT        'GI47' AS supco,
                                suratjalan AS nosj,
                                tanggal AS tglsj,
                                TO_CHAR(tanggal, 'HH24:MI:SS') AS jamsj,
                                tko_kodesbu as kodesbu,
                                kode_toko as toko,
                                namatoko,
                                nopb,
                                sum(item) as item,
                                sum(qty) as qty,
                                SUM(RP_NILAI) AS rpdspb,
                                SUM(RP_PPN) AS ppndspb,
                                sum(RPB_RPH_DF) as rphdf,
                                sum(RPB_RPH_DF_PPN) as ppndf,
                                SUM(TOTAL) AS total,
                                ongkir,
                                noawb
        FROM(
        SELECT DISTINCT NOKOLI,
                suratjalan,
                tanggal,
                kode_toko,
                namatoko,
                tko_kodesbu,
                no_pb,
                COUNT(pluigr) AS item,
                sum(qtyreal) as qty,
                ROUND(SUM(nilaireal)) AS RP_NILAI,
                ROUND(SUM(ppnreal)) AS RP_PPN,
                ROUND(SUM(df)) AS RPB_RPH_DF,
                ROUND(SUM(dfppn)) as RPB_RPH_DF_PPN,
                ROUND(SUM(nilaireal) + SUM(ppnreal) + SUM(df) + SUM(dfppn)) AS TOTAL
                FROM (
        SELECT rpb_create_dt as tanggal,
                rpb_kodeomi as kode_toko,
                tko_namaomi as namatoko,
                tko_kodesbu,
                rpb_nodokumen as no_pb,
                rpb_nokoli as nokoli,
                rpb_idsuratjalan as suratjalan,
                rpb_plu1 as pluomi,
                rpb_plu2 as pluigr,
                prd_deskripsipanjang as desk,
                prd_flagbkp1 as flagbkp1,
                prd_flagbkp2 as flagbkp2,
                rpb_qtyorder as qtyorder,
                rpb_qtyrealisasi as qtyreal,
                rpb_nilaiorder as nilaiorder,
                rpb_ppnorder as ppnorder,
                rpb_ttlnilai as nilaireal,
                case when prd_flagbkp1 = 'Y' and prd_flagbkp2 <> 'Y' then '0'
                else rpb_ttlppn end as ppnreal,
                rpb_distributionfee as df,
                rpb_distributionfee * 11/100 as dfppn
        FROM igrpwt.TBTR_REALPB 
        JOIN igrpwt.TBMASTER_TOKOIGR ON RPB_KODEOMI = TKO_KODEOMI
        LEFT JOIN igrpwt.TBMASTER_PRODMAST ON RPB_PLU2 = PRD_PRDCD AND PRD_PRDCD LIKE '%1'
        WHERE RPB_FLAG <> '5')base
        group by nokoli,suratjalan,tanggal,kode_toko,namatoko,no_pb,tko_kodesbu)AA
        LEFT JOIN (SELECT * FROM igrpwt.IGRPWT.TBTR_IPP_OMI) ipp
        ON AA.kode_toko||AA.no_pb = ipp.kodetoko||ipp.nopb
        where tko_kodesbu = 'O'
        group by suratjalan,tanggal,tko_kodesbu,kode_toko,namatoko,no_pb,ipp.nopb,ongkir,noawb";
        
        $sphomi = DB::select($query);

        return view('laporan.sphomi', compact('sphomi'));
    }

    public function awbomi()
    {
        return view('laporan.awbomi');
    }

    public function awbomi_h(Request $request)
    {
        $start = \Carbon\Carbon::createFromFormat('d-m-Y', $request->tanggal_mulai)->format('Y-m-d');
        $end = \Carbon\Carbon::createFromFormat('d-m-Y', $request->tanggal_selesai)->format('Y-m-d');

        $sql = "SELECT kodetoko,nopb,tglpb,nodspb,ongkir,noawb,
                        case when nopb like 'K%' then 'JIKA AWB KOSONG = PB YCG/KIRIM SENDIRI'
                        else status end as status
                        FROM IGRPWT.TBTR_IPP_OMI 
                        WHERE create_dt::date BETWEEN :start AND :end";

        $results = DB::select($sql, [
            'start' => $start,
            'end' => $end
        ]);

        return view('laporan.partials.awbomi_h', [
            'data' => $results,
            'start' => $start,
            'end' => $end
        ]);
    }


    public function eproc()
    {
        return view('laporan.eproc');
    }

    public function eproc_h(Request $request)
    {
        // Ambil 1 tanggal start
        $start = \Carbon\Carbon::createFromFormat('d-m-Y', $request->tanggal_mulai)->format('d-m-Y');

        $sql = "SELECT pbp_nopb nopb, 
                        pbp_keterangan keterangan, 
                        pbp_tglpb tglpb, 
                        pbp_prdcd plu, 
                        prd_deskripsipanjang desk,
                        pbp_frac frac,
                        pbp_qtypb qtypb,
                        pbp_gross gross,
                        pbp_ppn ppn,
                        case when pbp_flag is null then 'BLM TERUPLOAD' 
                        else 'BERHASIL UPLOAD' 
                        END flag
                        from igrpwt.tbtr_pb_procurement
                        left join igrpwt.tbmaster_prodmast on pbp_prdcd = prd_prdcd and prd_prdcd like '%0'
                        where to_char(pbp_tglpb, 'DD-MM-YYYY')= :start and pbp_qtypb <> '0'";

        $results = DB::select($sql, [
            'start' => $start
        ]);

        return view('laporan.partials.eproc_h', [
            'data' => $results,
            'start' => $start
        ]);
    }

    public function sarana()
    {
        return view('laporan.sarana');
    }

    public function sarana_h(Request $request)
    {
        
        // Use Carbon to set the start and end of the day
        $startDateInput = \Carbon\Carbon::parse($request->input('start_date'))->startOfDay();
        $endDateInput = \Carbon\Carbon::parse($request->input('end_date'))->endOfDay();

        $flag = $request->input('flag');

        if (!$flag || !$startDateInput || !$endDateInput) {
            return response()->json(['error' => 'Jenis dan tanggal wajib diisi'], 400);
        }

        if ($flag === 'idm') {
            $query = "WITH pbomi AS (
                            SELECT
                                pbo_kodeomi,
                                pbo_nopb,
                                pbo_nopicking,
                                SUM(pbo_ttlnilai) AS rupiah,
                                SUM(CASE WHEN pbo_pluigr = '1372801' THEN pbo_qtyrealisasi ELSE 0 END) AS qtyrealis
                            FROM igrpwt.tbmaster_pbomi
                            WHERE pbo_create_dt >= :start_date
                            AND pbo_create_dt < :end_date
                            AND pbo_recordid >= '4'
                            AND pbo_kodesbu = 'I'
                            GROUP BY pbo_kodeomi, pbo_nopb, pbo_nopicking
                        ),
                        idm_koli AS (
                            SELECT
                                ikl_kodeidm,
                                ikl_nopick,
                                SUM(CASE WHEN ikl_kardus = 'Y' THEN 1 ELSE 0 END) AS jml_kardus,
                                SUM(CASE WHEN ikl_kardus <> 'Y' THEN 1 ELSE 0 END) AS jml_container
                            FROM igrpwt.TBTR_IDMKOLI
                            WHERE ikl_create_dt >= :start_date
                            AND ikl_create_dt < :end_date
                            AND ikl_kodeidm NOT LIKE 'O%'
                            GROUP BY ikl_kodeidm, ikl_nopick
                        ),
                        base_data AS (
                            SELECT
                                hpbi.hpbi_tgltransaksi AS tanggal,
                                hpbi.hpbi_kodetoko AS kode_toko,
                                hpbi.hpbi_nopicking AS nopicking,
                                hpbi.hpbi_jumlahbronjong AS jumlah_bronjong,
                                hpbi.hpbi_bronjongscan AS bronjong_scan,
                                COALESCE(p.rupiah, 0) AS rph,
                                COALESCE(p.qtyrealis, 0) AS cont_dspb,
                                COALESCE(i.jml_kardus, 0) AS kardus_dspb,
                                COALESCE(i.jml_container, 0) AS cont_send
                            FROM igrpwt.TBTR_HEADER_PBIDM hpbi
                            LEFT JOIN pbomi p
                            ON hpbi.hpbi_kodetoko = p.pbo_kodeomi
                            AND hpbi.hpbi_nopb = p.pbo_nopb
                            AND hpbi.hpbi_nopicking = p.pbo_nopicking
                            LEFT JOIN idm_koli i
                            ON hpbi.hpbi_kodetoko = i.ikl_kodeidm
                            AND hpbi.hpbi_nopicking = i.ikl_nopick
                            WHERE hpbi.hpbi_create_dt >= :start_date
                            AND hpbi.hpbi_create_dt < :end_date
                            AND hpbi.hpbi_kodetoko NOT LIKE 'O%'
                        )
                        SELECT
                            tanggal,
                            COUNT(DISTINCT kode_toko) AS jml_toko,
                            SUM(cont_send) AS jumlah_container,
                            SUM(kardus_dspb) AS jumlah_kardus,
                            SUM(bronjong_scan) AS jumlah_bronjong,
                            CAST(SUM(rph) AS BIGINT) AS total_dspb,
                            (SUM(rph) / NULLIF(COUNT(DISTINCT kode_toko), 0)) AS avg_dspb_perhari,
                            (SUM(cont_send) / NULLIF(COUNT(DISTINCT kode_toko), 0)) AS avg_container_pertoko_perhari,
                            (SUM(kardus_dspb) / NULLIF(COUNT(DISTINCT kode_toko), 0)) AS avg_kardus_pertoko_perhari,
                            (SUM(bronjong_scan)::numeric / NULLIF(COUNT(DISTINCT kode_toko), 0)) AS avg_bronjong_pertoko_perhari
                        FROM base_data
                        GROUP BY tanggal
                        ORDER BY tanggal";
                        
        } elseif ($flag === 'omi') {
            $query = "WITH pbomi AS (
                            SELECT
                                pbo_kodeomi,
                                pbo_nopb,
                                pbo_nopicking,
                                SUM(pbo_ttlnilai) AS rupiah,
                                SUM(CASE WHEN pbo_pluigr = '1702471' THEN pbo_qtyrealisasi ELSE 0 END) AS qtyrealis
                            FROM igrpwt.tbmaster_pbomi
                            WHERE pbo_create_dt >= :start_date
                            AND pbo_create_dt < :end_date
                            AND pbo_recordid >= '4'
                            AND pbo_kodesbu = 'O'
                            GROUP BY pbo_kodeomi, pbo_nopb, pbo_nopicking
                        ),
                        idm_koli AS (
                            SELECT
                                ikl_kodeidm,
                                ikl_nopick,
                                SUM(CASE WHEN ikl_kardus = 'Y' THEN 1 ELSE 0 END) AS jml_kardus,
                                SUM(CASE WHEN ikl_kardus <> 'Y' THEN 1 ELSE 0 END) AS jml_container
                            FROM igrpwt.TBTR_IDMKOLI
                            WHERE ikl_create_dt >= :start_date
                            AND ikl_create_dt < :end_date
                            AND ikl_kodeidm LIKE 'O%'
                            GROUP BY ikl_kodeidm, ikl_nopick
                        ),
                        base_data AS (
                            SELECT
                                hpbi.hpbi_tgltransaksi AS tanggal,
                                hpbi.hpbi_kodetoko AS kode_toko,
                                hpbi.hpbi_nopicking AS nopicking,
                                hpbi.hpbi_jumlahbronjong AS jumlah_bronjong,
                                hpbi.hpbi_bronjongscan AS bronjong_scan,
                                COALESCE(p.rupiah, 0) AS rph,
                                COALESCE(p.qtyrealis, 0) AS cont_dspb,
                                COALESCE(i.jml_kardus, 0) AS kardus_dspb,
                                COALESCE(i.jml_container, 0) AS cont_send
                            FROM igrpwt.TBTR_HEADER_PBIDM hpbi
                            LEFT JOIN pbomi p
                            ON hpbi.hpbi_kodetoko = p.pbo_kodeomi
                            AND hpbi.hpbi_nopb = p.pbo_nopb
                            AND hpbi.hpbi_nopicking = p.pbo_nopicking
                            LEFT JOIN idm_koli i
                            ON hpbi.hpbi_kodetoko = i.ikl_kodeidm
                            AND hpbi.hpbi_nopicking = i.ikl_nopick
                            WHERE hpbi.hpbi_create_dt >= :start_date
                            AND hpbi.hpbi_create_dt < :end_date
                            AND hpbi.hpbi_kodetoko LIKE 'O%'
                        )
                        SELECT
                            tanggal,
                            COUNT(DISTINCT kode_toko) AS jml_toko,
                            SUM(cont_send) AS jumlah_container,
                            SUM(kardus_dspb) AS jumlah_kardus,
                            SUM(bronjong_scan) AS jumlah_bronjong,
                            CAST(SUM(rph) AS BIGINT) AS total_dspb,
                            (SUM(rph) / NULLIF(COUNT(DISTINCT kode_toko), 0)) AS avg_dspb_perhari,
                            (SUM(cont_send) / NULLIF(COUNT(DISTINCT kode_toko), 0)) AS avg_container_pertoko_perhari,
                            (SUM(kardus_dspb) / NULLIF(COUNT(DISTINCT kode_toko), 0)) AS avg_kardus_pertoko_perhari,
                            (SUM(bronjong_scan)::numeric / NULLIF(COUNT(DISTINCT kode_toko), 0)) AS avg_bronjong_pertoko_perhari
                        FROM base_data
                        GROUP BY tanggal
                        ORDER BY tanggal";
        } else {
            return response()->json(['error' => 'Jenis tidak valid'], 400);
        }

        $results = DB::select($query, [
            'start_date' => $startDateInput->toDateString(),
            'end_date' => $endDateInput->toDateString()
        ]);

        $totals = [
            'jumlah_container' => array_sum(array_column($results, 'jumlah_container')),
            'jumlah_kardus'    => array_sum(array_column($results, 'jumlah_kardus')),
            'jumlah_bronjong'  => array_sum(array_column($results, 'jumlah_bronjong')),
            'total_dspb'    => array_sum(array_column($results, 'total_dspb')),
        ];

        return view('laporan.partials.sarana_h', [
            'data' => $results,
            'start_date' => $startDateInput->toDateString(), // Mengirimkan tanggal mulai bulan
            'flag' => $flag,
            'totals' => $totals
        ]);
    }

    public function stock_eko()
    {
        $query = "WITH lokasi_agg AS (
                                        SELECT
                                            Lks_Prdcd,
                                            SUM(CASE WHEN Lks_Tiperak = 'B' AND Lks_Koderak LIKE 'D%' THEN Lks_Qty ELSE 0 END) AS Qtydpd,
                                            SUM(CASE WHEN Lks_Tiperak = 'B' AND Lks_Koderak LIKE 'R%' THEN Lks_Qty ELSE 0 END) AS Qtytoko,
                                            SUM(CASE WHEN Lks_Tiperak LIKE 'I%' THEN Lks_Qty ELSE 0 END) AS Qtyc,
                                            SUM(CASE WHEN Lks_Tiperak LIKE 'S%' THEN Lks_Qty ELSE 0 END) AS Qtys
                                        FROM igrpwt.Tbmaster_Lokasi
                                        WHERE Lks_Prdcd IS NOT NULL
                                        GROUP BY Lks_Prdcd
                                    )
                                    SELECT 
                                        TGLPB,
                                        PLUIGR,
                                        PLUOMI,
                                        ITEM,
                                        KETERANGAN,
                                        Permintaan,
                                        Lpp,
                                        st.st_saldoakhir,
                                        COALESCE(loc.Qtydpd, 0) AS Qty_Dpd,
                                        CASE
                                            WHEN loc.Qtytoko = 0 AND loc.Qtyc > 0 THEN loc.Qtyc
                                            WHEN loc.Qtytoko = 0 AND loc.Qtyc = 0 THEN 0
                                            WHEN loc.Qtytoko > 0 AND loc.Qtyc > 0 THEN loc.Qtytoko + loc.Qtyc
                                            ELSE loc.Qtytoko
                                        END AS Qty_Toko,
                                        COALESCE(loc.Qtys, 0) AS Qty_Storage
                                    FROM (
                                        SELECT 
                                            TLKO_TGLPB AS TGLPB,
                                            TLKO_PLUIGR AS PLUIGR,
                                            TLKO_PLUOMI AS PLUOMI,
                                            TLKO_DESC AS ITEM,
                                            TLKO_KETTOLAKAN AS KETERANGAN,
                                            SUM(TLKO_Qtyorder) AS Permintaan,
                                            MAX(TLKO_Lpp) AS Lpp
                                        FROM igrpwt.TBTR_TOLAKANPBOMI
                                        WHERE TLKO_KETTOLAKAN LIKE '%STOCK%'
                                        AND DATE(TLKO_Create_Dt) >= CURRENT_DATE
                                        GROUP BY TLKO_TGLPB, TLKO_PLUIGR, TLKO_PLUOMI, TLKO_DESC, TLKO_KETTOLAKAN
                                    ) AS tlko
                                    LEFT JOIN lokasi_agg AS loc
                                        ON loc.Lks_Prdcd = tlko.PLUIGR
                                    LEFT JOIN igrpwt.tbmaster_stock AS st
                                        ON st.st_prdcd = tlko.PLUIGR
                                    AND st.st_lokasi = '01'
                                    ORDER BY TGLPB";
        
        $stockeko = DB::select($query);

        return view('laporan.stockeko', compact('stockeko'));
    }

    public function stock_pot()
    {
        $query = "SELECT    PLUIGR,
                            rak_gudang AS ALAMAT_GUDANG,
                            rak_toko AS ALAMAT_TOKO,
                            DESK,
                            LPP,
                            plano_gudang,
                            plano_toko
                        FROM( select DISTINCT kat_pluigr PLUIGR,kat_deskripsi DESK,ST_SALDOAKHIR LPP from igrpwt.konversi_atk
                        LEFT JOIN igrpwt.tbmaster_stock ON ST_prdcd = KAT_pluigr
                        AND ST_LOKASI='01')st
                        LEFT JOIN (
                            SELECT 
                                LKS_PRDCD, 
                                LKS_KODERAK || '.' || LKS_KODESUBRAK || '.' || LKS_TIPERAK || '.' || LKS_SHELVINGRAK || '.' || LKS_NOURUT AS RAK_GUDANG,
                                lks_qty AS PLANO_GUDANG 
                            FROM 
                                igrpwt.Tbmaster_Lokasi 
                            WHERE 
                                SUBSTR(Lks_Koderak, 1, 1) = 'D' 
                                AND Lks_Tiperak = 'B' 
                                AND Lks_Prdcd IS NOT NULL
                        ) AS AB ON PLUIGR = AB.LKS_PRDCD
                        LEFT JOIN (
                            SELECT 
                                LKS_PRDCD, 
                                LKS_KODERAK || '.' || LKS_KODESUBRAK || '.' || LKS_TIPERAK || '.' || LKS_SHELVINGRAK || '.' || LKS_NOURUT AS RAK_TOKO,
                                lks_qty AS PLANO_TOKO 
                            FROM 
                                igrpwt.Tbmaster_Lokasi 
                            WHERE 
                                SUBSTR(Lks_Koderak, 1, 1) IN ('R', 'O') 
                                AND SUBSTR(Lks_Tiperak, 1, 1) IN ('B', 'I') 
                                AND Lks_Prdcd IS NOT NULL
                        ) AS AC ON PLUIGR = AC.LKS_PRDCD
                        WHERE LPP IS NOT NULL";
        
        $stockpot = DB::select($query);

        return view('laporan.stockpot', compact('stockpot'));
    }

    public function stock50()
    {
        $query = "SELECT 
                        LKS_KODERAK || '.'|| LKS_KODESUBRAK || '.'|| LKS_TIPERAK || '.'|| LKS_SHELVINGRAK AS ALAMAT,
                        LKS_PRDCD AS PLU,
                        PRD_DESKRIPSIPANJANG AS DESK,
                        ST_SALDOAKHIR AS LPP,
                        LKS_QTY AS QTY_PLANO,
                        LKS_MAXPLANO AS MAX_PLANO,
                        LKS_MAXDISPLAY AS MAX_DISPLAY,
                        CASE
                            WHEN LKS_KODERAK IN('D01','D02','D03','D04','D05','D06') THEN 'ZONA1'
                            WHEN LKS_KODERAK IN('D07','D08','D09','D10','D11','D12') THEN 'ZONA2'
                            WHEN LKS_KODERAK IN('D13','D14','D15','D16') THEN 'ZONA3'
                            WHEN LKS_KODERAK IN('D17','D18','D19') THEN 'ZONA4'
                            WHEN LKS_KODERAK IN('D20','D21','D22','D23','D24','D25') THEN 'ZONA5'
                            WHEN LKS_KODERAK IN('D26','D27','D28','D29') THEN 'ZONA6'
                            WHEN LKS_KODERAK IN('D30','D31','D32','D33') THEN 'ZONA7'
                            WHEN LKS_KODERAK IN('D34','D35','D36','D37','D38','D39') THEN 'ZONA8'
                            WHEN LKS_KODERAK IN('D39','D40') THEN 'ZONA9'
                            WHEN LKS_KODERAK IN ('D41') THEN 'ZONA10'
                            ELSE ''
                        END AS ZONA,
                        CASE
                        WHEN LKS_QTY = 0 THEN 'PLANO KOSONG'
                        WHEN (LKS_QTY/LKS_MAXPLANO)*100 < 50 THEN 'DIBAWAH 50%'
                        WHEN (LKS_QTY/LKS_MAXPLANO)*100 >= 50 THEN 'DIATAS 50%'
                        END AS KETERANGAN
                        FROM igrpwt.TBMASTER_LOKASI 
                        INNER JOIN (SELECT PRD_PRDCD, PRD_DESKRIPSIPANJANG FROM igrpwt.TBMASTER_PRODMAST WHERE PRD_PRDCD LIKE '%0')a ON LKS_PRDCD = PRD_PRDCD
                        LEFT JOIN (SELECT ST_PRDCD, ST_SALDOAKHIR FROM igrpwt.TBMASTER_STOCK WHERE ST_LOKASI='01')b ON LKS_PRDCD = ST_PRDCD
                        WHERE LKS_KODERAK BETWEEN 'D01' AND 'D41' AND LKS_TIPERAK='B' AND LKS_QTY <> 0 AND LKS_MAXPLANO<>0
                        ORDER BY 9 DESC";
        
        $stock50 = DB::select($query);

        return view('laporan.stock50', compact('stock50'));
    }

    public function dsieproc()
    {
        $query = "SELECT
                        ALAMAT,
                        PLUPROD AS PLUIGR,
                        PRD_PLUMCG AS PLUIDM,
                        PRD_DESKRIPSIPANJANG AS DESK,
                        PRD_FRAC AS FRAC,
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
                            PRD_FLAG_PROCUREMENT = 'Y' 
                            AND PRD_PRDCD LIKE '%0' 
                            AND PRD_FLAGIDM = 'Y'
                    ) a
                    LEFT JOIN
                    (
                        SELECT 
                            RSL_PRDCD AS PLURSL,
                            RSL_QTY_10 AS OCT,
                            RSL_QTY_09 AS SEPT,
                            RSL_QTY_08 AS AGST,
                            RSL_QTY_07 AS JUL,
                            RSL_QTY_06 AS JUN,
                            RSL_QTY_05 AS MEI,
                            RSL_QTY_04 AS APR,
                            RSL_QTY_03 AS MAR,
                            RSL_QTY_02 AS FEB,
                            RSL_QTY_01 AS JAN 
                        FROM 
                            igrpwt.TBTR_REKAPSALESBULANAN 
                        WHERE 
                            RSL_GROUP = '04'
                    ) b ON PLUPROD = PLURSL
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
                    ) h ON LKS_PRDCD = PLUPROD";
        
        $dsieproc = DB::select($query);

        return view('laporan.dsieproc', compact('dsieproc'));
    }
}