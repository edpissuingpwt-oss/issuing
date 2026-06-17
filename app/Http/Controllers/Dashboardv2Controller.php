<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardV2Controller extends Controller
{
    // Cache duration
    const CACHE_SHORT = 300;      // 5 menit
    const CACHE_MEDIUM = 1800;    // 30 menit
    const CACHE_LONG = 7200;      // 2 jam

    /**
     * Dashboard V2 - Optimized with Caching
     */
    public function index()
    {
        // Get all data with caching
        $stats = $this->getCachedStats();
        $slHarian = $this->getCachedSLHarian();
        
        return view('dashboardv2.index', compact('stats', 'slHarian'));
    }

    /**
     * Get all stats in one cached query
     */
    private function getCachedStats()
    {
        return Cache::remember('dashboardv2_all_stats', self::CACHE_SHORT, function() {
            // PB IDM
            $pbIdm = DB::selectOne("
                SELECT COUNT(DISTINCT HPBI_KODETOKO) AS total
                FROM IGRPWT.TBTR_HEADER_PBIDM AS pb
                JOIN IGRPWT.TBMASTER_TOKOIGR AS tko ON pb.HPBI_KODETOKO = tko.TKO_KODEOMI
                WHERE tko.TKO_KODESBU = 'I' AND pb.HPBI_CREATE_DT::date = CURRENT_DATE
            ")?->total ?? 0;

            // PB OMI
            $pbOmi = DB::selectOne("
                SELECT COUNT(DISTINCT HPBI_KODETOKO) AS total
                FROM IGRPWT.TBTR_HEADER_PBIDM AS pb
                JOIN IGRPWT.TBMASTER_TOKOIGR AS tko ON pb.HPBI_KODETOKO = tko.TKO_KODEOMI
                WHERE tko.TKO_KODESBU = 'O' AND pb.HPBI_CREATE_DT::date = CURRENT_DATE
            ")?->total ?? 0;

            // WT InTransit
            $wtSales = DB::selectOne("
                SELECT COUNT(*) AS total
                FROM (
                    SELECT DISTINCT pb.RPB_IDSURATJALAN
                    FROM IGRPWT.TBTR_REALPB pb
                    JOIN IGRPWT.TBMASTER_TOKOIGR tko ON pb.RPB_KODEOMI = tko.TKO_KODEOMI
                    WHERE pb.RPB_FLAG <> '5' AND tko.TKO_KODESBU = 'I'
                ) t
            ")?->total ?? 0;

            // SPH Pending
            $sphPending = DB::selectOne("
                SELECT COUNT(DISTINCT RPB_IDSURATJALAN) AS total
                FROM IGRPWT.TBTR_REALPB AS pb
                JOIN IGRPWT.TBMASTER_TOKOIGR AS tko ON pb.RPB_KODEOMI = tko.TKO_KODEOMI
                WHERE pb.RPB_FLAG <> '5' AND tko.TKO_KODESBU = 'O'
            ")?->total ?? 0;

            return [
                'pbidm' => $pbIdm,
                'pbomi' => $pbOmi,
                'wt' => $wtSales,
                'sph' => $sphPending,
            ];
        });
    }

    /**
     * Get SL Harian data with caching (ALL, IDM, OMI)
     */
    private function getCachedSLHarian()
    {
        return Cache::remember('dashboardv2_sl_harian', self::CACHE_MEDIUM, function() {
            // SL All
            $servicelevelall = DB::select("
                SELECT * FROM (
                    SELECT
                        pbo_create_dt::date AS tanggal_sort,
                        TO_CHAR(pbo_create_dt::date, 'DD-MON-YYYY') AS tanggal,
                        SUM(PBO_NILAIORDER) AS rphorder,
                        COALESCE(
                            SUM(CASE WHEN PBO_NOKOLI IS NOT NULL THEN PBO_TTLNILAI ELSE 0 END), 
                            0
                        ) AS rphrealisasi,
                        ROUND(
                            (COALESCE(SUM(CASE WHEN PBO_NOKOLI IS NOT NULL THEN PBO_TTLNILAI ELSE 0 END), 0)::NUMERIC 
                            / NULLIF(SUM(PBO_NILAIORDER), 0)::NUMERIC) * 100, 2
                        ) AS slharian
                    FROM IGRPWT.TBMASTER_PBOMI
                    WHERE pbo_create_dt::date BETWEEN CURRENT_DATE - INTERVAL '30 days' AND CURRENT_DATE
                    GROUP BY pbo_create_dt::date
                    ORDER BY pbo_create_dt::date DESC
                ) AA WHERE RPHORDER > 800000
            ");

            // SL IDM
            $servicelevelidm = DB::select("
                SELECT * FROM (
                    SELECT
                        pbo_create_dt::date AS tanggal_sort,
                        TO_CHAR(pbo_create_dt::date, 'DD-MON-YYYY') AS tanggal,
                        SUM(PBO_NILAIORDER) AS rphorder,
                        COALESCE(
                            SUM(CASE WHEN PBO_NOKOLI IS NOT NULL THEN PBO_TTLNILAI ELSE 0 END), 
                            0
                        ) AS rphrealisasi,
                        ROUND(
                            (COALESCE(SUM(CASE WHEN PBO_NOKOLI IS NOT NULL THEN PBO_TTLNILAI ELSE 0 END), 0)::NUMERIC 
                            / NULLIF(SUM(PBO_NILAIORDER), 0)::NUMERIC) * 100, 2
                        ) AS slharian
                    FROM IGRPWT.TBMASTER_PBOMI
                    WHERE pbo_create_dt::date BETWEEN CURRENT_DATE - INTERVAL '30 days' AND CURRENT_DATE
                    AND PBO_KODESBU = 'I'
                    AND PBO_CREATE_BY NOT IN ('INS','TSW')
                    GROUP BY pbo_create_dt::date
                    ORDER BY pbo_create_dt::date DESC
                ) aa WHERE RPHORDER > 800000
            ");

            // SL OMI
            $servicelevelomi = DB::select("
                SELECT * FROM (
                    SELECT
                        pbo_create_dt::date AS tanggal_sort,
                        TO_CHAR(pbo_create_dt::date, 'DD-MON-YYYY') AS tanggal,
                        SUM(PBO_NILAIORDER) AS rphorder,
                        COALESCE(
                            SUM(CASE WHEN PBO_NOKOLI IS NOT NULL THEN PBO_TTLNILAI ELSE 0 END), 
                            0
                        ) AS rphrealisasi,
                        ROUND(
                            (COALESCE(SUM(CASE WHEN PBO_NOKOLI IS NOT NULL THEN PBO_TTLNILAI ELSE 0 END), 0)::NUMERIC 
                            / NULLIF(SUM(PBO_NILAIORDER), 0)::NUMERIC) * 100, 2
                        ) AS slharian
                    FROM IGRPWT.TBMASTER_PBOMI
                    WHERE pbo_create_dt::date BETWEEN CURRENT_DATE - INTERVAL '30 days' AND CURRENT_DATE
                    AND PBO_KODESBU = 'O'
                    AND PBO_CREATE_BY NOT IN ('INS','TSW')
                    GROUP BY pbo_create_dt::date
                    ORDER BY pbo_create_dt::date DESC
                ) aa
            ");

            return [
                'all' => $servicelevelall,
                'idm' => $servicelevelidm,
                'omi' => $servicelevelomi,
            ];
        });
    }

    // ==================== PBSL (PB & SL) METHODS ====================

    /**
     * Get PB Mentah for IDM (with caching)
     */
    public function pbMentahIdm()
    {
        $html = Cache::remember('dashboardv2_pbmentah_idm', self::CACHE_SHORT, function() {
            // Query PB IDM - SAMA PERSIS DENGAN ASLINYA
            $query = "SELECT                count(fdkcab) as toko,
                                        fdkode,
                                        prd_prdcd,
                                        prd_deskripsipanjang,
                                        prd_frac as frac,
                                        prd_unit as unit,
                                        round(sum(fdqtyb)) as qty,
                                        ROUND(SUM(fdqtyb) / prd_frac, 2) AS qty_ctn,
                                        ROUND(PRD_AVGCOST) ACOSTCTN,
                                        ROUND(PRD_AVGCOST/prd_frac) ACOSTPCS,
                                        ROUND(sum(fdqtyb))*ROUND(PRD_AVGCOST/prd_frac) TOTAL,
                                        st_saldoakhir as lpp,
                                        LKS_KODERAK || '.' || LKS_KODESUBRAK || '.' || LKS_TIPERAK || '.' || LKS_SHELVINGRAK || '.' || LKS_NOURUT AS rak,
                                        lks_qty as plano,
                                        CASE
                                                    WHEN lks_koderak IN ('D01','D02','D03','D04') THEN 'ZONA 1'
                                                    WHEN lks_koderak IN ('D05','D06','D07','D08') THEN 'ZONA 2'
                                                    WHEN lks_koderak IN ('D09','D10','D11','D12') THEN 'ZONA 3'
                                                    WHEN lks_koderak IN ('D13','D14') THEN 'ZONA 4'
                                                    WHEN lks_koderak IN ('D15','D16') THEN 'ZONA 5'
                                                    WHEN lks_koderak IN ('D17','D18') THEN 'ZONA 6'
                                                    WHEN lks_koderak IN ('D19','D20','D21','D22','D23','D24') THEN 'ZONA 7'
                                                    WHEN lks_koderak IN ('D25','D26','D27','D28','D29','D30') THEN 'ZONA 8'
                                                    WHEN lks_koderak IN ('D31','D32','D33','D34','D35','D36') THEN 'ZONA 9'
                                                    WHEN lks_koderak IN ('D37','D38','D39','D40','D41','D42') THEN 'ZONA A'
                                                    WHEN lks_koderak IN ('D43','D44','D45','D46','D47','D48') THEN 'ZONA B'
                                                    WHEN lks_koderak IN ('D49','D50','D51','D52','D53','D54') THEN 'ZONA C'
                                                    WHEN lks_koderak IN ('D55','D56') THEN 'ZONA D'
                                                    WHEN lks_koderak = 'D57' THEN 'ZONA E'
                                                    WHEN lks_koderak = 'DCONT' THEN 'ZONA Z'
                                                END AS zona
                                        from igrpwt.csv_pb_idm
                                                            left join igrpwt.tbmaster_prodcrm on fdkode = prc_pluidm
                                                            left join igrpwt.tbmaster_prodmast on prc_pluigr = prd_prdcd and prd_prdcd like '%0'
                                                            left join igrpwt.tbmaster_stock on prd_prdcd = st_prdcd and st_lokasi = '01'
                                                            left join igrpwt.tbmaster_lokasi on prd_prdcd = lks_prdcd and SUBSTR(Lks_Koderak, 1, 1) = 'D' AND Lks_Tiperak = 'B' AND Lks_Prdcd IS NOT NULL
                                                            where tgl_proses::date = current_date
                                                            group by fdkode,prd_prdcd,prd_deskripsipanjang,prd_frac,prd_unit,prd_avgcost,st_saldoakhir,lks_koderak,lks_kodesubrak,lks_tiperak,lks_shelvingrak,lks_nourut,lks_qty
                                                            order by 3";

            
            $pbmi = DB::select($query);

            return view('dashboardv2.partials.pb-mentah-idm', compact('pbmi'))->render();
        });
        
        return response()->json(['html' => $html]);
    }

    /**
     * Get PB & SL Data for OMI (with caching)
     */
    public function pbMentahOmi()
    {
        $html = Cache::remember('dashboardv2_pbmentah_omi', self::CACHE_SHORT, function() {
            // Query PB OMI - SAMA PERSIS DENGAN ASLINYA
            $query = "SELECT count(fdkcab) as toko,
                                        fdkode pluomi,
                                        pluigr,
                                        prd_deskripsipanjang,
                                        prd_frac as frac,
                                        prd_unit as unit,
                                        round(sum(fdqtyb)) as qty,
                                        ROUND(SUM(fdqtyb) / prd_frac, 2) AS qty_ctn,
                                        ROUND(PRD_AVGCOST) ACOSTCTN,
                                        ROUND(PRD_AVGCOST/prd_frac) ACOSTPCS,
                                        ROUND(sum(fdqtyb))*ROUND(PRD_AVGCOST/prd_frac) TOTAL,
                                        st_saldoakhir as lpp,
                                        LKS_KODERAK || '.' || LKS_KODESUBRAK || '.' || LKS_TIPERAK || '.' || LKS_SHELVINGRAK || '.' || LKS_NOURUT AS rak,
                                        lks_qty as plano,
                                        CASE
                                                    WHEN lks_koderak IN ('D01','D02','D03','D04') THEN 'ZONA 1'
                                                    WHEN lks_koderak IN ('D05','D06','D07','D08') THEN 'ZONA 2'
                                                    WHEN lks_koderak IN ('D09','D10','D11','D12') THEN 'ZONA 3'
                                                    WHEN lks_koderak IN ('D13','D14') THEN 'ZONA 4'
                                                    WHEN lks_koderak IN ('D15','D16') THEN 'ZONA 5'
                                                    WHEN lks_koderak IN ('D17','D18') THEN 'ZONA 6'
                                                    WHEN lks_koderak IN ('D19','D20','D21','D22','D23','D24') THEN 'ZONA 7'
                                                    WHEN lks_koderak IN ('D25','D26','D27','D28','D29','D30') THEN 'ZONA 8'
                                                    WHEN lks_koderak IN ('D31','D32','D33','D34','D35','D36') THEN 'ZONA 9'
                                                    WHEN lks_koderak IN ('D37','D38','D39','D40','D41','D42') THEN 'ZONA A'
                                                    WHEN lks_koderak IN ('D43','D44','D45','D46','D47','D48') THEN 'ZONA B'
                                                    WHEN lks_koderak IN ('D49','D50','D51','D52','D53','D54') THEN 'ZONA C'
                                                    WHEN lks_koderak IN ('D55','D56') THEN 'ZONA D'
                                                    WHEN lks_koderak = 'D57' THEN 'ZONA E'
                                                    WHEN lks_koderak = 'DCONT' THEN 'ZONA Z'
                                                END AS zona from igrpwt.dbf_ddo
                                        left join igrpwt.tbmaster_prodcrm on pluigr = prc_pluigr and prc_group = 'O'
                                        left join igrpwt.tbmaster_prodmast on pluigr = prd_prdcd and prd_prdcd like '%0'
                                        left join igrpwt.tbmaster_stock on pluigr = st_prdcd and st_lokasi = '01'
                                        left join igrpwt.tbmaster_lokasi on pluigr = lks_prdcd and SUBSTR(Lks_Koderak, 1, 1) = 'D' AND Lks_Tiperak = 'B' AND Lks_Prdcd IS NOT NULL
                                        where pluigr is not null
                                        group by fdkode,pluigr,prd_deskripsipanjang,prd_unit,prd_frac,prc_minorder,prd_avgcost,st_saldoakhir,lks_koderak,lks_kodesubrak,lks_tiperak,lks_shelvingrak,lks_nourut,lks_qty
                                        order by 2";

            $pbmo = DB::select($query);

            return view('dashboardv2.partials.pb-mentah-omi', compact('pbmo'))->render();
        });
        
        return response()->json(['html' => $html]);
    }

    // ==================== PBSL (PB & SL) METHODS ====================

    /**
     * Get PB & SL Data for IDM (with caching)
     */
    public function pbSlIdm()
    {
        $html = Cache::remember('dashboardv2_pbsl_idm', self::CACHE_SHORT, function() {
            // Query PB IDM - SAMA PERSIS DENGAN ASLINYA
            $query = "SELECT
                        AA.KDTK AS KODE,
                        TKO_NAMAOMI AS NAMA_TOKO,
                        AA.ITEMPB AS ITEMP,
                        AB.ITEMR AS ITEMR,
                        ROUND(COALESCE(SUM(AB.ITEMR)::numeric / NULLIF(SUM(AA.ITEMPB), 0), 0) * 100) AS ITEMPC,
                        AA.QTYPB AS QTYP,
                        AB.QTYR AS QTYR,
                        ROUND(COALESCE(SUM(AB.QTYR)::numeric / NULLIF(SUM(AA.QTYPB), 0), 0) * 100) AS QTYPC,
                        AA.RPHPB AS RPHP,
                        AB.RPHR AS RPHR,
                        ROUND((AB.RPHR/AA.RPHPB)*100) AS RPHPC
                        FROM
                        (SELECT
                        PBO_KODEOMI AS KDTK,
                        COUNT(PBO_PLUIGR) AS ITEMPB,
                        SUM(PBO_QTYORDER) AS QTYPB,
                        SUM(PBO_NILAIORDER) AS RPHPB
                        FROM IGRPWT.TBMASTER_PBOMI WHERE date(PBO_CREATE_DT) = current_date
                        AND PBO_KODEOMI IN(SELECT TKO_KODEOMI FROM IGRPWT.TBMASTER_TOKOIGR WHERE TKO_KODESBU = 'I') 
                        group by PBO_KODEOMI) AA
                        LEFT JOIN
                        (SELECT
                        PBO_KODEOMI,
                        COUNT(PBO_NOKOLI) AS ITEMR,
                        SUM(CASE WHEN pbo_ttlnilai != 0 AND pbo_hrgsatuan != 0 THEN pbo_ttlnilai / pbo_hrgsatuan ELSE 0 END) AS QTYR,
                        SUM(PBO_TTLNILAI) AS RPHR
                        FROM IGRPWT.TBMASTER_PBOMI WHERE date(PBO_CREATE_DT) = current_date AND PBO_NOKOLI IS NOT NULL
                        AND PBO_KODEOMI IN(SELECT TKO_KODEOMI FROM IGRPWT.TBMASTER_TOKOIGR WHERE TKO_KODESBU = 'I') 
                        group by PBO_KODEOMI) AB
                        ON AA.KDTK = AB.PBO_KODEOMI
                        LEFT JOIN
                        (SELECT * FROM IGRPWT.TBMASTER_TOKOIGR WHERE TKO_KODESBU = 'I') as tko
                        ON AA.KDTK = TKO_KODEOMI
                        WHERE AA.ITEMPB > 50 AND AA.QTYPB > 50
                        GROUP BY AA.KDTK,TKO.TKO_NAMAOMI,AA.ITEMPB,AB.ITEMR,AA.QTYPB,AB.QTYR,AA.RPHPB,AB.RPHR
                        ORDER BY AA.KDTK";

            // Query SL IDM - SAMA PERSIS DENGAN ASLINYA
            $query2 = "SELECT SUBSTRING(pbo_pluigr FROM 1 FOR 6) || '0' AS plu,
                deskripsi,
                bb.jml_toko,
                bb.qtyo,
                coalesce((bb.qtyr),0) as qtyr,
                (SUM(bb.qtyr) / NULLIF(SUM(bb.qtyo), 0)) * 100 AS slqty,
                bb.nilaio,
                bb.ttlnilai,
                (SUM(bb.ttlnilai) / NULLIF(SUM(bb.nilaio), 0)) * 100 AS slnilai
                FROM (
                    SELECT 
                    pbo_pluigr,
                    pbo_kodedivisi as div,
                    pbo_kodedepartemen as dept,
                    pbo_kodekategoribrg as katb,
                    count(pbo_kodeomi) jml_toko,
                    sum(pbo_qtyorder) as qtyo,
                    SUM(
                                                      CASE 
                                                        WHEN pbo_ttlnilai != 0 AND pbo_hrgsatuan != 0 AND PBO_NOKOLI IS NOT NULL
                                                        THEN pbo_ttlnilai / pbo_hrgsatuan 
                                                        ELSE 0 
                                                      END
                                                    ) AS qtyr,
                    sum(pbo_nilaiorder) as nilaio,
                    COALESCE(
                                                            SUM(CASE WHEN PBO_NOKOLI IS NOT NULL THEN PBO_TTLNILAI ELSE 0 END), 
                                                            0
                                                        ) AS ttlnilai
                    FROM igrpwt.tbmaster_pbomi
                    WHERE DATE(PBO_CREATE_DT) = CURRENT_DATE
                    AND pbo_kodesbu = 'I'
                    AND PBO_CREATE_BY NOT IN ('INS','TSW')
                    GROUP BY pbo_pluigr, div, dept, katb, pbo_hrgsatuan
                    ORDER BY 1
                ) bb
                LEFT JOIN (
                    SELECT prd_prdcd as plu2, prd_deskripsipanjang as deskripsi 
                    FROM igrpwt.tbmaster_prodmast
                ) aa 
                ON pbo_pluigr = aa.plu2
                GROUP BY bb.pbo_pluigr, aa.deskripsi, bb.jml_toko, bb.qtyo, bb.qtyr, bb.nilaio, bb.ttlnilai
                ORDER BY plu";
            
            $pbi = DB::select($query);
            $sli = DB::select($query2);

            return view('dashboardv2.partials.pb-sl-idm', compact('pbi', 'sli'))->render();
        });
        
        return response()->json(['html' => $html]);
    }

    /**
     * Get PB & SL Data for OMI (with caching)
     */
    public function pbSlOmi()
    {
        $html = Cache::remember('dashboardv2_pbsl_omi', self::CACHE_SHORT, function() {
            // Query PB OMI - SAMA PERSIS DENGAN ASLINYA
            $query = "SELECT
                        AA.KDTK AS KODE,
                        TKO_NAMAOMI AS NAMA_TOKO,
                        AA.ITEMPB AS ITEMP,
                        AB.ITEMR AS ITEMR,
                        ROUND(COALESCE(SUM(AB.ITEMR)::numeric / NULLIF(SUM(AA.ITEMPB), 0), 0) * 100) AS ITEMPC,
                        AA.QTYPB AS QTYP,
                        AB.QTYR AS QTYR,
                        ROUND(COALESCE(SUM(AB.QTYR)::numeric / NULLIF(SUM(AA.QTYPB), 0), 0) * 100) AS QTYPC,
                        AA.RPHPB AS RPHP,
                        AB.RPHR AS RPHR,
                        ROUND(COALESCE(AB.RPHR::numeric / NULLIF(AA.RPHPB, 0), 0) * 100) AS RPHPC
                        FROM
                        (SELECT
                        PBO_KODEOMI AS KDTK,
                        COUNT(PBO_PLUIGR) AS ITEMPB,
                        SUM(PBO_QTYORDER) AS QTYPB,
                        SUM(PBO_NILAIORDER) AS RPHPB
                        FROM IGRPWT.TBMASTER_PBOMI WHERE date(PBO_CREATE_DT) = current_date
                        AND PBO_KODEOMI IN(SELECT TKO_KODEOMI FROM IGRPWT.TBMASTER_TOKOIGR WHERE TKO_KODESBU = 'O')
                        AND PBO_CREATE_BY NOT IN ('INS','TSW') 
                        group by PBO_KODEOMI) AA
                        LEFT JOIN
                        (SELECT
                        PBO_KODEOMI,
                        COUNT(PBO_NOKOLI) AS ITEMR,
                        SUM(CASE WHEN pbo_ttlnilai != 0 AND pbo_hrgsatuan != 0 THEN pbo_ttlnilai / pbo_hrgsatuan ELSE 0 END) AS QTYR,
                        SUM(PBO_TTLNILAI) AS RPHR
                        FROM IGRPWT.TBMASTER_PBOMI WHERE date(PBO_CREATE_DT) = current_date AND PBO_NOKOLI IS NOT NULL
                        AND PBO_KODEOMI IN(SELECT TKO_KODEOMI FROM IGRPWT.TBMASTER_TOKOIGR WHERE TKO_KODESBU = 'O') 
                        AND PBO_CREATE_BY NOT IN ('INS','TSW')
                        group by PBO_KODEOMI) AB
                        ON AA.KDTK = AB.PBO_KODEOMI
                        LEFT JOIN
                        (SELECT * FROM IGRPWT.TBMASTER_TOKOIGR WHERE TKO_KODESBU = 'O') as tko
                        ON AA.KDTK = TKO_KODEOMI
                        -- WHERE AA.ITEMPB > 5 AND AA.QTYPB > 5
                        GROUP BY AA.KDTK,TKO.TKO_NAMAOMI,AA.ITEMPB,AB.ITEMR,AA.QTYPB,AB.QTYR,AA.RPHPB,AB.RPHR
                        ORDER BY AA.KDTK";

            // Query SL OMI - SAMA PERSIS DENGAN ASLINYA
            $query2 = "SELECT SUBSTRING(pbo_pluigr FROM 1 FOR 6) || '0' AS plu,
                deskripsi,
                bb.jml_toko,
                bb.qtyo,
                coalesce((bb.qtyr),0) as qtyr,
                (SUM(bb.qtyr) / NULLIF(SUM(bb.qtyo), 0)) * 100 AS slqty,
                bb.nilaio,
                bb.ttlnilai,
                (SUM(bb.ttlnilai) / NULLIF(SUM(bb.nilaio), 0)) * 100 AS slnilai
                FROM (
                    SELECT 
                    pbo_pluigr,
                    pbo_kodedivisi as div,
                    pbo_kodedepartemen as dept,
                    pbo_kodekategoribrg as katb,
                    count(pbo_kodeomi) jml_toko,
                    sum(pbo_qtyorder) as qtyo,
                    SUM(
                                                      CASE 
                                                        WHEN pbo_ttlnilai != 0 AND pbo_hrgsatuan != 0 AND PBO_NOKOLI IS NOT NULL
                                                        THEN pbo_ttlnilai / pbo_hrgsatuan 
                                                        ELSE 0 
                                                      END
                                                    ) AS qtyr,
                    sum(pbo_nilaiorder) as nilaio,
                    COALESCE(
                                                            SUM(CASE WHEN PBO_NOKOLI IS NOT NULL THEN PBO_TTLNILAI ELSE 0 END), 
                                                            0
                                                        ) AS ttlnilai
                    FROM igrpwt.tbmaster_pbomi
                    WHERE DATE(PBO_CREATE_DT) = CURRENT_DATE
                    AND pbo_kodesbu = 'O'
                    AND PBO_CREATE_BY NOT IN ('INS','TSW')
                    GROUP BY pbo_pluigr, div, dept, katb, pbo_hrgsatuan
                    ORDER BY 1
                ) bb
                LEFT JOIN (
                    SELECT prd_prdcd as plu2, prd_deskripsipanjang as deskripsi 
                    FROM igrpwt.tbmaster_prodmast
                ) aa 
                ON pbo_pluigr = aa.plu2
                GROUP BY bb.pbo_pluigr, aa.deskripsi, bb.jml_toko, bb.qtyo, bb.qtyr, bb.nilaio, bb.ttlnilai
                ORDER BY plu";
            
            $pbo = DB::select($query);
            $slo = DB::select($query2);

            return view('dashboardv2.partials.pb-sl-omi', compact('pbo', 'slo'))->render();
        });
        
        return response()->json(['html' => $html]);
    }

    /**
     * Refresh PBMentah IDM cache
     */
    public function refreshPbMentahIdm()
    {
        Cache::forget('dashboardv2_pbmentah_idm');
        return $this->pbMentahIdm();
    }

    /**
     * Refresh PBMentah OMI cache
     */
    public function refreshPbMentahOmi()
    {
        Cache::forget('dashboardv2_pbmentah_omi');
        return $this->pbMentahOmi();
    }

    /**
     * Refresh PBSL IDM cache
     */
    public function refreshPbSlIdm()
    {
        Cache::forget('dashboardv2_pbsl_idm');
        return $this->pbSlIdm();
    }

    /**
     * Refresh PBSL OMI cache
     */
    public function refreshPbSlOmi()
    {
        Cache::forget('dashboardv2_pbsl_omi');
        return $this->pbSlOmi();
    }

    // ==================== AJAX ENDPOINTS ====================

    /**
     * Refresh stats via AJAX
     */
    public function refreshStats()
    {
        Cache::forget('dashboardv2_all_stats');
        return response()->json($this->getCachedStats());
    }

    /**
     * Get SL Harian via AJAX (refresh)
     */
    public function getSlHarian()
    {
        Cache::forget('dashboardv2_sl_harian');
        $slHarian = $this->getCachedSLHarian();
        
        return response()->json([
            'html_all' => view('dashboardv2.partials.sl-harian', ['data' => $slHarian['all'] ?? [], 'title' => 'ALL'])->render(),
            'html_idm' => view('dashboardv2.partials.sl-harian', ['data' => $slHarian['idm'] ?? [], 'title' => 'IDM'])->render(),
            'html_omi' => view('dashboardv2.partials.sl-harian', ['data' => $slHarian['omi'] ?? [], 'title' => 'OMI'])->render(),
        ]);
    }

    /**
     * Get Rupiah Picking ALL data
     */
    public function getRupiahPickingAll()
    {
        $html = Cache::remember('dashboardv2_rupiah_all', self::CACHE_SHORT, function() {
            $data = DB::select("WITH
                                        -- Ambil data PB OMI
                                        pbomi_raw AS (
                                            SELECT 
                                                pbo_pluigr,
                                                SUBSTRING(pbo_pluigr FROM 1 FOR 6) || '0' AS plu_konversi,
                                                SUM(pbo_qtyorder) AS qtyorder,
                                                ROUND(SUM(CASE WHEN pbo_ttlnilai != 0 AND pbo_hrgsatuan != 0 AND PBO_NOKOLI IS NOT NULL THEN pbo_ttlnilai / pbo_hrgsatuan ELSE 0 END)) AS qtyrealisasi,
                                                SUM(pbo_nilaiorder) AS nilaiorder,
                                                -- SUM(pbo_ttlnilai) AS ttlnilai,
                                                COALESCE(
                                                            SUM(CASE WHEN PBO_NOKOLI IS NOT NULL THEN PBO_TTLNILAI ELSE 0 END), 
                                                            0
                                                        ) AS ttlnilai
                                            FROM IGRPWT.tbmaster_pbomi
                                            WHERE DATE_TRUNC('day', pbo_create_dt) = CURRENT_DATE 
                                            AND pbo_qtyorder IS NOT NULL
                                            AND (PBO_NOKOLI IS NULL OR PBO_NOKOLI NOT ILIKE 'Q%')
                                            AND PBO_CREATE_BY NOT IN ('INS','TSW')
                                            GROUP BY pbo_pluigr, pbo_hrgsatuan
                                        ),
                                        -- Lokasi + Zona
                                        lokasi_zona AS (
                                            SELECT 
                                                lks.lks_prdcd AS plu,
                                                lks.lks_koderak as koderak,
                                                lks.lks_kodesubrak as kodesubrak,
                                                lks.lks_tiperak as tiperak,
                                                lks.lks_shelvingrak as shelvingrak,
                                                lks.lks_nourut as nourut,
                                                CASE
                                                    WHEN lks.lks_koderak IN ('D01','D02','D03','D04') THEN 'ZONA 1'
                                                    WHEN lks.lks_koderak IN ('D05','D06','D07','D08') THEN 'ZONA 2'
                                                    WHEN lks.lks_koderak IN ('D09','D10','D11','D12') THEN 'ZONA 3'
                                                    WHEN lks.lks_koderak IN ('D13','D14') THEN 'ZONA 4'
                                                    WHEN lks.lks_koderak IN ('D15','D16') THEN 'ZONA 5'
                                                    WHEN lks.lks_koderak IN ('D17','D18') THEN 'ZONA 6'
                                                    WHEN lks.lks_koderak IN ('D19','D20','D21','D22','D23','D24') THEN 'ZONA 7'
                                                    WHEN lks.lks_koderak IN ('D25','D26','D27','D28','D29','D30') THEN 'ZONA 8'
                                                    WHEN lks.lks_koderak IN ('D31','D32','D33','D34','D35','D36') THEN 'ZONA 9'
                                                    WHEN lks.lks_koderak IN ('D37','D38','D39','D40','D41','D42') THEN 'ZONA A'
                                                    WHEN lks.lks_koderak IN ('D43','D44','D45','D46','D47','D48') THEN 'ZONA B'
                                                    WHEN lks.lks_koderak IN ('D49','D50','D51','D52','D53','D54') THEN 'ZONA C'
                                                    WHEN lks.lks_koderak IN ('D55','D56') THEN 'ZONA D'
                                                    WHEN lks.lks_koderak = 'D57' THEN 'ZONA E'
                                                    WHEN lks.lks_koderak = 'DCONT' THEN 'ZONA Z'
                                                    WHEN lks.lks_koderak = 'D99' THEN 'ZONA 20'
                                                END AS zona,
                                                CASE
                                                    WHEN zon.zon_rak IN ('D19','D20','D25','D26','D31','D32','D37','D38','D43','D44','D49','D50','D55') THEN 'JALUR 1'
                                                    WHEN zon.zon_rak IN ('D21','D22','D27','D28','D33','D34','D39','D40','D45','D46','D51','D52','D56') THEN 'JALUR 2'
                                                    WHEN zon.zon_rak IN ('D23','D24','D29','D30','D35','D36','D41','D42','D47','D48','D53','D54') THEN 'JALUR 3'
                                                    WHEN zon.zon_rak IS NULL AND lks.lks_koderak = 'DCONT' THEN 'CONTAINER'
                                                    WHEN zon.zon_rak IN ('D01','D02','D03','D04','D05','D06','D07','D08','D09','D10','D11','D12') THEN 'BULKY'
                                                    WHEN zon.zon_rak IN ('D13','D14','D15','D16','D17','D18') THEN 'FRAC'
                                                    WHEN zon.zon_rak = 'D57' THEN 'ROKOK'
                                                    WHEN zon.zon_rak = 'D99' THEN 'BAYANGAN'
                                                END AS jalur
                                            FROM IGRPWT.tbmaster_lokasi lks
                                            LEFT JOIN IGRPWT.zona_idm zon ON lks.lks_koderak = zon.zon_rak
                                            WHERE lks.lks_koderak NOT IN ('DTAGR','D999','DKLIK')
                                            AND lks.lks_koderak NOT LIKE ANY(ARRAY['P%','O%','R%','F%','E%','X%','H%'])
                                            AND lks.lks_tiperak != 'S'
                                            AND (
                                                    CASE
                                                        WHEN lks.lks_koderak IN ('D01','D02','D03','D04') THEN 'ZONA 1'
                                                        WHEN lks.lks_koderak IN ('D05','D06','D07','D08') THEN 'ZONA 2'
                                                        WHEN lks.lks_koderak IN ('D09','D10','D11','D12') THEN 'ZONA 3'
                                                        WHEN lks.lks_koderak IN ('D13','D14') THEN 'ZONA 4'
                                                        WHEN lks.lks_koderak IN ('D15','D16') THEN 'ZONA 5'
                                                        WHEN lks.lks_koderak IN ('D17','D18') THEN 'ZONA 6'
                                                        WHEN lks.lks_koderak IN ('D19','D20','D21','D22','D23','D24') THEN 'ZONA 7'
                                                        WHEN lks.lks_koderak IN ('D25','D26','D27','D28','D29','D30') THEN 'ZONA 8'
                                                        WHEN lks.lks_koderak IN ('D31','D32','D33','D34','D35','D36') THEN 'ZONA 9'
                                                        WHEN lks.lks_koderak IN ('D37','D38','D39','D40','D41','D42') THEN 'ZONA A'
                                                        WHEN lks.lks_koderak IN ('D43','D44','D45','D46','D47','D48') THEN 'ZONA B'
                                                        WHEN lks.lks_koderak IN ('D49','D50','D51','D52','D53','D54') THEN 'ZONA C'
                                                        WHEN lks.lks_koderak IN ('D55','D56') THEN 'ZONA D'
                                                        WHEN lks.lks_koderak = 'D57' THEN 'ZONA E'
                                                        WHEN lks.lks_koderak = 'DCONT' THEN 'ZONA Z'
                                                        WHEN lks.lks_koderak = 'D99' THEN 'ZONA 20'
                                                    END
                                                ) IS NOT NULL
                                            AND lks.lks_prdcd IS NOT NULL
                                        ),
                                        -- Gabungkan PB dan Zona + ATK
                                        pb_zona AS (
                                            SELECT
                                                CASE 
                                                    WHEN pbo.plu_konversi IN (SELECT kat_pluigr FROM IGRPWT.konversi_atk)
                                                        THEN 'ZONA X'
                                                    WHEN COALESCE(lok.zona, 'ZONA Z') = 'ZONA Z'
                                                        THEN 'ZONA Z'
                                                    ELSE lok.zona
                                                END AS zona,
                                                CASE
                                                    WHEN pbo.plu_konversi IN (SELECT kat_pluigr FROM IGRPWT.konversi_atk)
                                                        THEN 'POT'
                                                    ELSE COALESCE(lok.jalur, 'TIDAK PUNYA LOKASI')
                                                END AS jalur,
                                                SUM(pbo.qtyorder) AS qtyorder,
                                                ROUND(SUM(pbo.qtyrealisasi)) AS qtyrealisasi,
                                                CASE 
                                                    WHEN (
                                                        COALESCE(SUM(pbo.qtyrealisasi), 0)::NUMERIC 
                                                        / NULLIF(SUM(pbo.qtyorder), 0)::NUMERIC
                                                    ) * 100 = 100
                                                    THEN '100'
                                                    ELSE TO_CHAR(
                                                        ROUND(
                                                            (
                                                                COALESCE(SUM(pbo.qtyrealisasi), 0)::NUMERIC 
                                                                / NULLIF(SUM(pbo.qtyorder), 0)::NUMERIC
                                                            ) * 100,
                                                            2
                                                        ),
                                                        'FM999999990.00'
                                                    )
                                                END AS slqty,
                                                SUM(pbo.nilaiorder) AS nilaiorder,
                                                SUM(pbo.ttlnilai) AS ttlnilai,
                                                CASE 
                                                    WHEN (
                                                        COALESCE(SUM(pbo.ttlnilai), 0)::NUMERIC 
                                                        / NULLIF(SUM(pbo.nilaiorder), 0)::NUMERIC
                                                    ) * 100 = 100
                                                    THEN '100'
                                                    ELSE TO_CHAR(
                                                        ROUND(
                                                            (
                                                                COALESCE(SUM(pbo.ttlnilai), 0)::NUMERIC 
                                                                / NULLIF(SUM(pbo.nilaiorder), 0)::NUMERIC
                                                            ) * 100,
                                                            2
                                                        ),
                                                        'FM999999990.00'
                                                    )
                                                END AS slnilai
                                            FROM pbomi_raw pbo
                                            LEFT JOIN lokasi_zona lok ON lok.plu = pbo.plu_konversi
                                            GROUP BY 1, 2
                                        )
                                        -- Final result
                                        SELECT * FROM pb_zona where jalur <> 'TIDAK PUNYA LOKASI'
                                        ORDER BY 
                                                CASE 
                                                    WHEN zona = 'ZONA 20' THEN 9999
                                                    WHEN zona = 'ZONA X' THEN 9999
                                                    WHEN zona = 'ZONA Z' THEN 9999
                                                    WHEN zona ~ '^ZONA [0-9]+$'
                                                        THEN CAST(SUBSTRING(zona FROM '[0-9]+') AS INT)
                                                    ELSE 9000
                                                END,
                                                zona,
                                                jalur");
            return view('dashboardv2.partials.rupiah-table', ['data' => $data, 'type' => 'all'])->render();
        });
        
        return response()->json(['html' => $html]);
    }

    /**
     * Get Rupiah Picking IDM data
     */
    public function getRupiahPickingIdm()
    {
        $html = Cache::remember('dashboardv2_rupiah_idm', self::CACHE_SHORT, function() {
            $data = DB::select("WITH
                                        -- Ambil data PB OMI
                                        pbomi_raw AS (
                                            SELECT 
                                                pbo_pluigr,
                                                SUBSTRING(pbo_pluigr FROM 1 FOR 6) || '0' AS plu_konversi,
                                                SUM(pbo_qtyorder) AS qtyorder,
                                                ROUND(SUM(CASE WHEN pbo_ttlnilai != 0 AND pbo_hrgsatuan != 0 AND PBO_NOKOLI IS NOT NULL THEN pbo_ttlnilai / pbo_hrgsatuan ELSE 0 END)) AS qtyrealisasi,
                                                SUM(pbo_nilaiorder) AS nilaiorder,
                                                -- SUM(pbo_ttlnilai) AS ttlnilai
                                                COALESCE(
                                                            SUM(CASE WHEN PBO_NOKOLI IS NOT NULL THEN PBO_TTLNILAI ELSE 0 END), 
                                                            0
                                                        ) AS ttlnilai
                                            FROM IGRPWT.tbmaster_pbomi
                                            WHERE DATE_TRUNC('day', pbo_create_dt) = CURRENT_DATE 
                                            AND pbo_qtyorder IS NOT NULL
                                            AND PBO_KODESBU = 'I'
                                            AND (PBO_NOKOLI IS NULL OR PBO_NOKOLI NOT ILIKE 'Q%')
                                            AND PBO_CREATE_BY NOT IN ('INS','TSW')
                                            GROUP BY pbo_pluigr, pbo_hrgsatuan
                                        ),
                                        -- Lokasi + Zona
                                        lokasi_zona AS (
                                            SELECT 
                                                lks.lks_prdcd AS plu,
                                                lks.lks_koderak as koderak,
                                                lks.lks_kodesubrak as kodesubrak,
                                                lks.lks_tiperak as tiperak,
                                                lks.lks_shelvingrak as shelvingrak,
                                                lks.lks_nourut as nourut,
                                                CASE
                                                    WHEN lks.lks_koderak IN ('D01','D02','D03','D04') THEN 'ZONA 1'
                                                    WHEN lks.lks_koderak IN ('D05','D06','D07','D08') THEN 'ZONA 2'
                                                    WHEN lks.lks_koderak IN ('D09','D10','D11','D12') THEN 'ZONA 3'
                                                    WHEN lks.lks_koderak IN ('D13','D14') THEN 'ZONA 4'
                                                    WHEN lks.lks_koderak IN ('D15','D16') THEN 'ZONA 5'
                                                    WHEN lks.lks_koderak IN ('D17','D18') THEN 'ZONA 6'
                                                    WHEN lks.lks_koderak IN ('D19','D20','D21','D22','D23','D24') THEN 'ZONA 7'
                                                    WHEN lks.lks_koderak IN ('D25','D26','D27','D28','D29','D30') THEN 'ZONA 8'
                                                    WHEN lks.lks_koderak IN ('D31','D32','D33','D34','D35','D36') THEN 'ZONA 9'
                                                    WHEN lks.lks_koderak IN ('D37','D38','D39','D40','D41','D42') THEN 'ZONA A'
                                                    WHEN lks.lks_koderak IN ('D43','D44','D45','D46','D47','D48') THEN 'ZONA B'
                                                    WHEN lks.lks_koderak IN ('D49','D50','D51','D52','D53','D54') THEN 'ZONA C'
                                                    WHEN lks.lks_koderak IN ('D55','D56') THEN 'ZONA D'
                                                    WHEN lks.lks_koderak = 'D57' THEN 'ZONA E'
                                                    WHEN lks.lks_koderak = 'DCONT' THEN 'ZONA Z'
                                                    WHEN lks.lks_koderak = 'D99' THEN 'ZONA 20'
                                                END AS zona,
                                                CASE
                                                    WHEN zon.zon_rak IN ('D19','D20','D25','D26','D31','D32','D37','D38','D43','D44','D49','D50','D55') THEN 'JALUR 1'
                                                    WHEN zon.zon_rak IN ('D21','D22','D27','D28','D33','D34','D39','D40','D45','D46','D51','D52','D56') THEN 'JALUR 2'
                                                    WHEN zon.zon_rak IN ('D23','D24','D29','D30','D35','D36','D41','D42','D47','D48','D53','D54') THEN 'JALUR 3'
                                                    WHEN zon.zon_rak IS NULL AND lks.lks_koderak = 'DCONT' THEN 'CONTAINER'
                                                    WHEN zon.zon_rak IN ('D01','D02','D03','D04','D05','D06','D07','D08','D09','D10','D11','D12') THEN 'BULKY'
                                                    WHEN zon.zon_rak IN ('D13','D14','D15','D16','D17','D18') THEN 'FRAC'
                                                    WHEN zon.zon_rak = 'D57' THEN 'ROKOK'
                                                    WHEN zon.zon_rak = 'D99' THEN 'BAYANGAN'
                                                END AS jalur
                                            FROM IGRPWT.tbmaster_lokasi lks
                                            LEFT JOIN IGRPWT.zona_idm zon ON lks.lks_koderak = zon.zon_rak
                                            WHERE lks.lks_koderak NOT IN ('DTAGR','D999','DKLIK')
                                            AND lks.lks_koderak NOT LIKE ANY(ARRAY['P%','O%','R%','F%','E%','X%','H%'])
                                            AND lks.lks_tiperak != 'S'
                                            AND (
                                                    CASE
                                                        WHEN lks.lks_koderak IN ('D01','D02','D03','D04') THEN 'ZONA 1'
                                                        WHEN lks.lks_koderak IN ('D05','D06','D07','D08') THEN 'ZONA 2'
                                                        WHEN lks.lks_koderak IN ('D09','D10','D11','D12') THEN 'ZONA 3'
                                                        WHEN lks.lks_koderak IN ('D13','D14') THEN 'ZONA 4'
                                                        WHEN lks.lks_koderak IN ('D15','D16') THEN 'ZONA 5'
                                                        WHEN lks.lks_koderak IN ('D17','D18') THEN 'ZONA 6'
                                                        WHEN lks.lks_koderak IN ('D19','D20','D21','D22','D23','D24') THEN 'ZONA 7'
                                                        WHEN lks.lks_koderak IN ('D25','D26','D27','D28','D29','D30') THEN 'ZONA 8'
                                                        WHEN lks.lks_koderak IN ('D31','D32','D33','D34','D35','D36') THEN 'ZONA 9'
                                                        WHEN lks.lks_koderak IN ('D37','D38','D39','D40','D41','D42') THEN 'ZONA A'
                                                        WHEN lks.lks_koderak IN ('D43','D44','D45','D46','D47','D48') THEN 'ZONA B'
                                                        WHEN lks.lks_koderak IN ('D49','D50','D51','D52','D53','D54') THEN 'ZONA C'
                                                        WHEN lks.lks_koderak IN ('D55','D56') THEN 'ZONA D'
                                                        WHEN lks.lks_koderak = 'D57' THEN 'ZONA E'
                                                        WHEN lks.lks_koderak = 'DCONT' THEN 'ZONA Z'
                                                        WHEN lks.lks_koderak = 'D99' THEN 'ZONA 20'
                                                    END
                                                ) IS NOT NULL
                                            AND lks.lks_prdcd IS NOT NULL
                                        ),
                                        -- Gabungkan PB dan Zona + ATK
                                        pb_zona AS (
                                            SELECT
                                                CASE 
                                                    WHEN pbo.plu_konversi IN (SELECT kat_pluigr FROM IGRPWT.konversi_atk)
                                                        THEN 'ZONA X'
                                                    WHEN COALESCE(lok.zona, 'ZONA Z') = 'ZONA Z'
                                                        THEN 'ZONA Z'
                                                    ELSE lok.zona
                                                END AS zona,
                                                CASE
                                                    WHEN pbo.plu_konversi IN (SELECT kat_pluigr FROM IGRPWT.konversi_atk)
                                                        THEN 'POT'
                                                    ELSE COALESCE(lok.jalur, 'TIDAK PUNYA LOKASI')
                                                END AS jalur,
                                                SUM(pbo.qtyorder) AS qtyorder,
                                                SUM(pbo.qtyrealisasi) AS qtyrealisasi,
                                                CASE 
                                                    WHEN (
                                                        COALESCE(SUM(pbo.qtyrealisasi), 0)::NUMERIC 
                                                        / NULLIF(SUM(pbo.qtyorder), 0)::NUMERIC
                                                    ) * 100 = 100
                                                    THEN '100'
                                                    ELSE TO_CHAR(
                                                        ROUND(
                                                            (
                                                                COALESCE(SUM(pbo.qtyrealisasi), 0)::NUMERIC 
                                                                / NULLIF(SUM(pbo.qtyorder), 0)::NUMERIC
                                                            ) * 100,
                                                            2
                                                        ),
                                                        'FM999999990.00'
                                                    )
                                                END AS slqty,
                                                SUM(pbo.nilaiorder) AS nilaiorder,
                                                SUM(pbo.ttlnilai) AS ttlnilai,
                                                CASE 
                                                    WHEN (
                                                        COALESCE(SUM(pbo.ttlnilai), 0)::NUMERIC 
                                                        / NULLIF(SUM(pbo.nilaiorder), 0)::NUMERIC
                                                    ) * 100 = 100
                                                    THEN '100'
                                                    ELSE TO_CHAR(
                                                        ROUND(
                                                            (
                                                                COALESCE(SUM(pbo.ttlnilai), 0)::NUMERIC 
                                                                / NULLIF(SUM(pbo.nilaiorder), 0)::NUMERIC
                                                            ) * 100,
                                                            2
                                                        ),
                                                        'FM999999990.00'
                                                    )
                                                END AS slnilai
                                            FROM pbomi_raw pbo
                                            LEFT JOIN lokasi_zona lok ON lok.plu = pbo.plu_konversi
                                            GROUP BY 1, 2
                                        )
                                        -- Final result
                                        SELECT * FROM pb_zona where jalur <> 'TIDAK PUNYA LOKASI'
                                        ORDER BY 
                                                CASE 
                                                    WHEN zona = 'ZONA 20' THEN 9999
                                                    WHEN zona = 'ZONA X' THEN 9999
                                                    WHEN zona = 'ZONA Z' THEN 9999
                                                    WHEN zona ~ '^ZONA [0-9]+$'
                                                        THEN CAST(SUBSTRING(zona FROM '[0-9]+') AS INT)
                                                    ELSE 9000
                                                END,
                                                zona,
                                                jalur");
            return view('dashboardv2.partials.rupiah-table', ['data' => $data, 'type' => 'idm'])->render();
        });
        
        return response()->json(['html' => $html]);
    }

    /**
     * Get Rupiah Picking OMI data
     */
    public function getRupiahPickingOmi()
    {
        $html = Cache::remember('dashboardv2_rupiah_omi', self::CACHE_SHORT, function() {
            $data = DB::select("WITH
                                        -- Ambil data PB OMI
                                        pbomi_raw AS (
                                            SELECT 
                                                pbo_pluigr,
                                                SUBSTRING(pbo_pluigr FROM 1 FOR 6) || '0' AS plu_konversi,
                                                SUM(pbo_qtyorder) AS qtyorder,
                                                ROUND(SUM(CASE WHEN pbo_ttlnilai != 0 AND pbo_hrgsatuan != 0 AND PBO_NOKOLI IS NOT NULL THEN pbo_ttlnilai / pbo_hrgsatuan ELSE 0 END)) AS qtyrealisasi,
                                                SUM(pbo_nilaiorder) AS nilaiorder,
                                                -- SUM(pbo_ttlnilai) AS ttlnilai
                                                COALESCE(
                                                            SUM(CASE WHEN PBO_NOKOLI IS NOT NULL THEN PBO_TTLNILAI ELSE 0 END), 
                                                            0
                                                        ) AS ttlnilai
                                            FROM IGRPWT.tbmaster_pbomi
                                            WHERE DATE_TRUNC('day', pbo_create_dt) = CURRENT_DATE 
                                            AND pbo_qtyorder IS NOT NULL
                                            AND PBO_KODESBU = 'O'
                                            AND (PBO_NOKOLI IS NULL OR PBO_NOKOLI NOT ILIKE 'Q%')
                                            AND PBO_CREATE_BY NOT IN ('INS','TSW')
                                            GROUP BY pbo_pluigr, pbo_hrgsatuan
                                        ),
                                        -- Lokasi + Zona
                                        lokasi_zona AS (
                                            SELECT 
                                                lks.lks_prdcd AS plu,
                                                lks.lks_koderak as koderak,
                                                lks.lks_kodesubrak as kodesubrak,
                                                lks.lks_tiperak as tiperak,
                                                lks.lks_shelvingrak as shelvingrak,
                                                lks.lks_nourut as nourut,
                                                CASE
                                                    WHEN lks.lks_koderak IN ('D01','D02','D03','D04') THEN 'ZONA 1'
                                                    WHEN lks.lks_koderak IN ('D05','D06','D07','D08') THEN 'ZONA 2'
                                                    WHEN lks.lks_koderak IN ('D09','D10','D11','D12') THEN 'ZONA 3'
                                                    WHEN lks.lks_koderak IN ('D13','D14') THEN 'ZONA 4'
                                                    WHEN lks.lks_koderak IN ('D15','D16') THEN 'ZONA 5'
                                                    WHEN lks.lks_koderak IN ('D17','D18') THEN 'ZONA 6'
                                                    WHEN lks.lks_koderak IN ('D19','D20','D21','D22','D23','D24') THEN 'ZONA 7'
                                                    WHEN lks.lks_koderak IN ('D25','D26','D27','D28','D29','D30') THEN 'ZONA 8'
                                                    WHEN lks.lks_koderak IN ('D31','D32','D33','D34','D35','D36') THEN 'ZONA 9'
                                                    WHEN lks.lks_koderak IN ('D37','D38','D39','D40','D41','D42') THEN 'ZONA A'
                                                    WHEN lks.lks_koderak IN ('D43','D44','D45','D46','D47','D48') THEN 'ZONA B'
                                                    WHEN lks.lks_koderak IN ('D49','D50','D51','D52','D53','D54') THEN 'ZONA C'
                                                    WHEN lks.lks_koderak IN ('D55','D56') THEN 'ZONA D'
                                                    WHEN lks.lks_koderak = 'D57' THEN 'ZONA E'
                                                    WHEN lks.lks_koderak = 'DCONT' THEN 'ZONA Z'
                                                    WHEN lks.lks_koderak = 'D99' THEN 'ZONA 20'
                                                END AS zona,
                                                CASE
                                                    WHEN zon.zon_rak IN ('D19','D20','D25','D26','D31','D32','D37','D38','D43','D44','D49','D50','D55') THEN 'JALUR 1'
                                                    WHEN zon.zon_rak IN ('D21','D22','D27','D28','D33','D34','D39','D40','D45','D46','D51','D52','D56') THEN 'JALUR 2'
                                                    WHEN zon.zon_rak IN ('D23','D24','D29','D30','D35','D36','D41','D42','D47','D48','D53','D54') THEN 'JALUR 3'
                                                    WHEN zon.zon_rak IS NULL AND lks.lks_koderak = 'DCONT' THEN 'CONTAINER'
                                                    WHEN zon.zon_rak IN ('D01','D02','D03','D04','D05','D06','D07','D08','D09','D10','D11','D12') THEN 'BULKY'
                                                    WHEN zon.zon_rak IN ('D13','D14','D15','D16','D17','D18') THEN 'FRAC'
                                                    WHEN zon.zon_rak = 'D57' THEN 'ROKOK'
                                                    WHEN zon.zon_rak = 'D99' THEN 'BAYANGAN'
                                                END AS jalur
                                            FROM IGRPWT.tbmaster_lokasi lks
                                            LEFT JOIN IGRPWT.zona_idm zon ON lks.lks_koderak = zon.zon_rak
                                            WHERE lks.lks_koderak NOT IN ('DTAGR','D999','DKLIK')
                                            AND lks.lks_koderak NOT LIKE ANY(ARRAY['P%','O%','R%','F%','E%','X%','H%'])
                                            AND lks.lks_tiperak != 'S'
                                            AND (
                                                    CASE
                                                        WHEN lks.lks_koderak IN ('D01','D02','D03','D04') THEN 'ZONA 1'
                                                        WHEN lks.lks_koderak IN ('D05','D06','D07','D08') THEN 'ZONA 2'
                                                        WHEN lks.lks_koderak IN ('D09','D10','D11','D12') THEN 'ZONA 3'
                                                        WHEN lks.lks_koderak IN ('D13','D14') THEN 'ZONA 4'
                                                        WHEN lks.lks_koderak IN ('D15','D16') THEN 'ZONA 5'
                                                        WHEN lks.lks_koderak IN ('D17','D18') THEN 'ZONA 6'
                                                        WHEN lks.lks_koderak IN ('D19','D20','D21','D22','D23','D24') THEN 'ZONA 7'
                                                        WHEN lks.lks_koderak IN ('D25','D26','D27','D28','D29','D30') THEN 'ZONA 8'
                                                        WHEN lks.lks_koderak IN ('D31','D32','D33','D34','D35','D36') THEN 'ZONA 9'
                                                        WHEN lks.lks_koderak IN ('D37','D38','D39','D40','D41','D42') THEN 'ZONA A'
                                                        WHEN lks.lks_koderak IN ('D43','D44','D45','D46','D47','D48') THEN 'ZONA B'
                                                        WHEN lks.lks_koderak IN ('D49','D50','D51','D52','D53','D54') THEN 'ZONA C'
                                                        WHEN lks.lks_koderak IN ('D55','D56') THEN 'ZONA D'
                                                        WHEN lks.lks_koderak = 'D57' THEN 'ZONA E'
                                                        WHEN lks.lks_koderak = 'DCONT' THEN 'ZONA Z'
                                                        WHEN lks.lks_koderak = 'D99' THEN 'ZONA 20'
                                                    END
                                                ) IS NOT NULL
                                            AND lks.lks_prdcd IS NOT NULL
                                        ),
                                        -- Gabungkan PB dan Zona + ATK
                                        pb_zona AS (
                                            SELECT
                                                CASE 
                                                    WHEN pbo.plu_konversi IN (SELECT kat_pluigr FROM IGRPWT.konversi_atk)
                                                        THEN 'ZONA X'
                                                    WHEN COALESCE(lok.zona, 'ZONA Z') = 'ZONA Z'
                                                        THEN 'ZONA Z'
                                                    ELSE lok.zona
                                                END AS zona,
                                                CASE
                                                    WHEN pbo.plu_konversi IN (SELECT kat_pluigr FROM IGRPWT.konversi_atk)
                                                        THEN 'POT'
                                                    ELSE COALESCE(lok.jalur, 'TIDAK PUNYA LOKASI')
                                                END AS jalur,
                                                SUM(pbo.qtyorder) AS qtyorder,
                                                SUM(pbo.qtyrealisasi) AS qtyrealisasi,
                                                CASE 
                                                    WHEN (
                                                        COALESCE(SUM(pbo.qtyrealisasi), 0)::NUMERIC 
                                                        / NULLIF(SUM(pbo.qtyorder), 0)::NUMERIC
                                                    ) * 100 = 100
                                                    THEN '100'
                                                    ELSE TO_CHAR(
                                                        ROUND(
                                                            (
                                                                COALESCE(SUM(pbo.qtyrealisasi), 0)::NUMERIC 
                                                                / NULLIF(SUM(pbo.qtyorder), 0)::NUMERIC
                                                            ) * 100,
                                                            2
                                                        ),
                                                        'FM999999990.00'
                                                    )
                                                END AS slqty,
                                                SUM(pbo.nilaiorder) AS nilaiorder,
                                                SUM(pbo.ttlnilai) AS ttlnilai,
                                                CASE 
                                                    WHEN (
                                                        COALESCE(SUM(pbo.ttlnilai), 0)::NUMERIC 
                                                        / NULLIF(SUM(pbo.nilaiorder), 0)::NUMERIC
                                                    ) * 100 = 100
                                                    THEN '100'
                                                    ELSE TO_CHAR(
                                                        ROUND(
                                                            (
                                                                COALESCE(SUM(pbo.ttlnilai), 0)::NUMERIC 
                                                                / NULLIF(SUM(pbo.nilaiorder), 0)::NUMERIC
                                                            ) * 100,
                                                            2
                                                        ),
                                                        'FM999999990.00'
                                                    )
                                                END AS slnilai
                                            FROM pbomi_raw pbo
                                            LEFT JOIN lokasi_zona lok ON lok.plu = pbo.plu_konversi
                                            GROUP BY 1, 2
                                        )
                                        -- Final result
                                        SELECT * FROM pb_zona where jalur <> 'TIDAK PUNYA LOKASI'
                                        ORDER BY 
                                                CASE 
                                                    WHEN zona = 'ZONA 20' THEN 9999
                                                    WHEN zona = 'ZONA X' THEN 9999
                                                    WHEN zona = 'ZONA Z' THEN 9999
                                                    WHEN zona ~ '^ZONA [0-9]+$'
                                                        THEN CAST(SUBSTRING(zona FROM '[0-9]+') AS INT)
                                                    ELSE 9000
                                                END,
                                                zona,
                                                jalur");
            return view('dashboardv2.partials.rupiah-table', ['data' => $data, 'type' => 'omi'])->render();
        });
        
        return response()->json(['html' => $html]);
    }

    /**
     * Get Monitoring Picking data
     */
    public function getMonitorPicking()
    {
        $html = Cache::remember('dashboardv2_monitor_picking', self::CACHE_SHORT, function() {
            $monitorPicking = DB::select("SELECT  
                                                    zona,
                                                    jalur,
                                                    COALESCE(picking, 0) || '/' || totaltokopick AS picking,
                                                    COALESCE(scanning, 0) || '/' || totaltokoscan AS scanning,
                                                    CASE 
                                                        WHEN picking < totaltokopick AND scanning = totaltokoscan THEN 'PICKING'
                                                        WHEN scanning < totaltokoscan THEN 'SCANNING'
                                                        WHEN scanning IS NULL THEN 'BLM MULAI'
                                                        ELSE 'CLOSE'
                                                        END AS ket,
                                                    ROUND((COALESCE(picking, 0)::NUMERIC / NULLIF(totaltokopick, 0)) * 100) AS sl_picking,
                                                    ROUND((COALESCE(scanning, 0)::NUMERIC / NULLIF(totaltokoscan, 0)) * 100) AS sl_scanning,
                                                    LEAST(
                                                                ROUND((COALESCE(picking, 0)::NUMERIC / NULLIF(totaltokopick, 0)) * 100),
                                                                ROUND((COALESCE(scanning, 0)::NUMERIC / NULLIF(totaltokoscan, 0)) * 100)
                                                            ) AS sl
                                                    FROM (
                                            SELECT 
                                                p.zona,
                                                p.jalur,
                                                p.totalpick AS totaltokopick,
                                                COALESCE(d.totaltokoscan, 0) AS totaltokoscan,
                                                p.picking AS picking,
                                                COALESCE(d.tokoscan, 0) AS scanning
                                            FROM (
                                                SELECT  
                                                    pia_kodezona AS zona,
                                                    CASE
                                                        WHEN pia_grouprak = 'HZ201' THEN 'BAYANGAN'
                                                        WHEN pia_grouprak = 'DZD01' THEN 'COUNTER 1'
                                                        WHEN pia_grouprak = 'DZD02' THEN 'COUNTER 2'
                                                        WHEN pia_grouprak = 'DZE01' THEN 'ROKOK'
                                                        WHEN pia_grouprak IN ('DZ101','DZ201','DZ301') THEN 'BULKY'
                                                        WHEN pia_grouprak IN ('DZ401','DZ501','DZ601','DZ701','DZ801','DZ901','DZA01','DZB01','DZC01') THEN 'JALUR 1'
                                                        WHEN pia_grouprak IN ('DZ702','DZ802','DZ902','DZA02','DZB02','DZC02') THEN 'JALUR 2'
                                                        WHEN pia_grouprak IN ('DZ703','DZ803','DZ903','DZA03','DZB03','DZC03') THEN 'JALUR 3'
                                                        ELSE 'LAINNYA'
                                                    END AS jalur,
                                                    pia_grouprak,
                                                    COUNT(pia_nopick) AS totalpick,
                                                    SUM(CASE WHEN pia_recordid = '1' THEN 1 ELSE 0 END) AS picking
                                                FROM igrpwt.picking_antrian
                                                WHERE pia_tglpick::date = CURRENT_DATE
                                                AND PIA_KODEZONA IN ('ZONA 1', 'ZONA 2', 'ZONA 3', 'ZONA 4', 'ZONA 5', 'ZONA 6','ZONA 7', 'ZONA 8', 'ZONA 9', 'ZONA A', 'ZONA B', 'ZONA C','ZONA D', 'ZONA E', 'ZONA 20')
                                                GROUP BY pia_grouprak,pia_kodezona
                                            ) p
                                            LEFT JOIN (
                                                SELECT 
                                                    dca_grouprak,
                                                    COUNT(dca_nopicking) AS totaltokoscan,
                                                    SUM(CASE WHEN dca_flag = '3' and dca_nopicking is not null THEN 1 ELSE 0 END) AS tokoscan
                                                FROM igrpwt.dcp_antrian
                                                WHERE dca_tgl::date = CURRENT_DATE
                                                GROUP BY dca_grouprak
                                            ) d
                                                ON p.pia_grouprak = d.dca_grouprak
                                            ) aa
                                            UNION ALL

                                            SELECT zona,
                                                jalur,
                                                COALESCE(picking, 0) || '/' || totaltoko AS tete,
                                                COALESCE(scanning, 0) || '/' || totaltoko AS tete2,
                                                CASE 
                                                    WHEN picking < totaltoko THEN 'SCANNING'
                                                    WHEN picking IS NULL THEN 'BLM MULAI'
                                                    ELSE 'CLOSE'
                                                    END AS ket,
                                                ROUND((COALESCE(picking, 0)::NUMERIC / NULLIF(totaltoko, 0)) * 100) AS sl_picking,
                                                ROUND((COALESCE(picking, 0)::NUMERIC / NULLIF(totaltoko, 0)) * 100) AS sl_scanning,
                                                ROUND((COALESCE(picking, 0)::NUMERIC / NULLIF(totaltoko, 0)) * 100) AS sl
                                                FROM (
                                            SELECT 
                                                'ZONA XXX' AS zona,
                                                'POT' AS jalur,
                                                COUNT(hdp_flag) AS totaltoko,
                                                SUM(CASE WHEN hdp_flag > '2' THEN 1 ELSE 0 END) AS picking,
                                                COUNT(hdp_flag) AS totalscan,
                                                SUM(CASE WHEN hdp_flag > '2' THEN 1 ELSE 0 END) AS scanning
                                            FROM igrpwt.tbtr_header_pot 
                                            WHERE hdp_create_dt::date = CURRENT_DATE
                                            HAVING COUNT(hdp_flag) > 0
                                            ORDER BY zona, jalur) AB
            ");
            return view('dashboardv2.partials.picking-table', compact('monitorPicking'))->render();
        });
        
        return response()->json(['html' => $html]);
    }

    /**
     * Get Monitoring Loading data
     */
    public function getMonitorLoading()
    {
        $html = Cache::remember('dashboardv2_monitor_loading', self::CACHE_SHORT, function() {
            $monitorLoading = DB::select("WITH base_data_idm AS (
                                                            SELECT 
                                                                DATE(hpbi_create_dt) AS tgl,
                                                                hpbi_flag,
                                                                hpbi_kodetoko,
                                                                CASE 
                                                                    WHEN hpbi_kodetoko LIKE 'O%' THEN 'OMI'
                                                                    ELSE 'IDM'
                                                                END AS tipe_toko
                                                            FROM igrpwt.tbtr_header_pbidm
                                                            WHERE DATE(hpbi_create_dt) = CURRENT_DATE 
                                                        ),
                                                        summary_ab_idm AS (
                                                            SELECT 
                                                                tgl,
                                                                tipe_toko,
                                                                COUNT(hpbi_flag) AS total_sj,
                                                                COUNT(DISTINCT hpbi_kodetoko) AS total_toko
                                                            FROM base_data_idm
                                                            GROUP BY tgl, tipe_toko
                                                        ),
                                                        summary_bb_idm AS (
                                                            SELECT 
                                                                tgl,
                                                                tipe_toko,
                                                                COUNT(hpbi_flag) AS selesai_sj,
                                                                COUNT(DISTINCT hpbi_kodetoko) AS selesai_toko
                                                            FROM base_data_idm
                                                            WHERE hpbi_flag = '5'
                                                            GROUP BY tgl, tipe_toko
                                                        ),

                                                        -- TABEL KEDUA: PBPOT
                                                        base_data_pot AS (
                                                            SELECT 
                                                                DATE(hdp_create_dt) AS tgl,
                                                                hdp_flag,
                                                                hdp_kodetoko,
                                                                'POT' AS tipe_toko
                                                            FROM igrpwt.tbtr_header_pot
                                                            WHERE DATE(hdp_create_dt) = CURRENT_DATE 
                                                        ),
                                                        summary_ab_pot AS (
                                                            SELECT 
                                                                tgl,
                                                                tipe_toko,
                                                                COUNT(hdp_flag) AS total_sj,
                                                                COUNT(DISTINCT hdp_kodetoko) AS total_toko
                                                            FROM base_data_pot
                                                            GROUP BY tgl, tipe_toko
                                                        ),
                                                        summary_bb_pot AS (
                                                            SELECT 
                                                                tgl,
                                                                tipe_toko,
                                                                COUNT(hdp_flag) AS selesai_sj,
                                                                COUNT(DISTINCT hdp_kodetoko) AS selesai_toko
                                                            FROM base_data_pot
                                                            WHERE hdp_flag = '5'
                                                            GROUP BY tgl, tipe_toko
                                                        )

                                                        -- GABUNGKAN HASIL DUA SUMBER
                                                        SELECT 
                                                            ab.tipe_toko,
                                                            COALESCE(bb.selesai_sj, 0) || '/' || ab.total_sj AS totalsj,
                                                            ROUND((COALESCE(bb.selesai_sj, 0)::NUMERIC / NULLIF(ab.total_sj, 0)) * 100) AS slsj,
                                                            CASE 
                                                                WHEN bb.selesai_sj IS NULL THEN 'BLM MULAI'
                                                                WHEN bb.selesai_sj < ab.total_sj THEN 'ON PROCESS'
                                                                ELSE 'DONE/PRAGAT'
                                                            END AS status,
                                                            COALESCE(bb.selesai_toko, 0) || '/' || ab.total_toko AS totaltoko,
                                                            ROUND((COALESCE(bb.selesai_toko, 0)::NUMERIC / NULLIF(ab.total_toko, 0)) * 100) AS sltoko
                                                        FROM summary_ab_idm ab
                                                        LEFT JOIN summary_bb_idm bb 
                                                            ON ab.tgl = bb.tgl AND ab.tipe_toko = bb.tipe_toko

                                                        UNION ALL

                                                        SELECT 
                                                            ab.tipe_toko,
                                                            COALESCE(bb.selesai_sj, 0) || '/' || ab.total_sj AS totalsj,
                                                            ROUND((COALESCE(bb.selesai_sj, 0)::NUMERIC / NULLIF(ab.total_sj, 0)) * 100) AS slsj,
                                                            CASE 
                                                                WHEN bb.selesai_sj IS NULL THEN 'BLM MULAI'
                                                                WHEN bb.selesai_sj < ab.total_sj THEN 'ON PROCESS'
                                                                ELSE 'DONE/PRAGAT'
                                                            END AS status,
                                                            COALESCE(bb.selesai_toko, 0) || '/' || ab.total_toko AS totaltoko,
                                                            ROUND((COALESCE(bb.selesai_toko, 0)::NUMERIC / NULLIF(ab.total_toko, 0)) * 100) AS sltoko
                                                        FROM summary_ab_pot ab
                                                        LEFT JOIN summary_bb_pot bb 
                                                            ON ab.tgl = bb.tgl AND ab.tipe_toko = bb.tipe_toko
            ");
            return view('dashboardv2.partials.loading-table', compact('monitorLoading'))->render();
        });
        
        return response()->json(['html' => $html]);
    }
}   