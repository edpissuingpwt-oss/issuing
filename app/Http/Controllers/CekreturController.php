<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class CekreturController extends Controller
{
    public function itemretur()
    {
        $query = "SELECT 
                        lks_koderak || '.' || lks_kodesubrak || '.' || lks_tiperak || '.' || lks_shelvingrak || '.' || lks_nourut AS alamat, 
                        prdm.tag_igr,
                        prc.tag_idm,
                        prdm.prd_prdcd,
                        prc.pluidm,
                        prdm.prd_deskripsipanjang AS deskripsi,
                        prdm.frac,
                        hgb3.hgb_kodesupplier,
                        hgb3.sup_namasupplier,
                        hgb3.harga_beli,
                        lok.qty_dpd,
                        st.st_saldoakhir AS lpp,
                        prdm.minor_igr,
                        prc.minor_idm,
                        pbo.pb AS permintaan_pbidm
                    FROM (
                        SELECT
                            prd_kodedivisi AS div,
                            prd_kodedepartement AS dep,
                            prd_kodekategoribarang AS kat,
                            prd_kodetag AS tag_igr,
                            prd_prdcd,
                            prd_plumcg,
                            prd_deskripsipanjang,
                            prd_frac AS frac,
                            prd_unit AS unit,
                            prd_minorder AS minor_igr,
                            prd_flagomi,
                            prd_flagidm,
                            prd_flagigr
                        FROM IGRPWT.tbmaster_prodmast 
                        WHERE prd_prdcd LIKE '%0' 
                        AND prd_flagidm = 'Y'
                    ) prdm
                    LEFT JOIN (
                        SELECT 
                            lks_koderak,
                            lks_kodesubrak,
                            lks_tiperak,
                            lks_shelvingrak,
                            lks_nourut,
                            lks_prdcd,
                            lks_qty AS qty_dpd
                        FROM IGRPWT.tbmaster_lokasi 
                        WHERE lks_koderak LIKE 'D%' 
                        AND lks_tiperak = 'B' 
                        AND lks_koderak NOT LIKE '%DKLIK%' 
                        AND lks_shelvingrak NOT LIKE '%SC%'
                    ) lok ON prdm.prd_prdcd = lok.lks_prdcd
                    LEFT JOIN (
                        SELECT 
                            prc_pluidm AS pluidm,
                            prc_pluigr AS pluigr,
                            prc_pluomi AS idmomiplu,
                            prc_minorder AS minor_idm,
                            prc_kodetag AS tag_idm,
                            prc_group AS group_idm
                        FROM IGRPWT.tbmaster_prodcrm 
                        WHERE prc_group = 'I'
                    ) prc ON prdm.prd_prdcd = prc.pluigr
                    LEFT JOIN (
                        SELECT 
                            st_prdcd,
                            st_saldoakhir
                        FROM IGRPWT.tbmaster_stock 
                        WHERE st_lokasi = '01'
                    ) st ON prdm.prd_prdcd = st.st_prdcd
                    LEFT JOIN (
                        SELECT 
                            pbo_pluigr,
                            SUM(pbo_qtyorder) AS pb
                        FROM IGRPWT.tbmaster_pbomi 
                        WHERE pbo_nopb NOT IN ('0','1','4') 
                        AND pbo_kodesbu = 'I'
                        GROUP BY pbo_pluigr
                    ) pbo ON prdm.prd_prdcd = LEFT(pbo.pbo_pluigr,6) || '0'
                    LEFT JOIN (
                        SELECT 
                            hgb2.hgb_prdcd,
                            hgb2.hgb_kodesupplier,
                            sup.sup_namasupplier,
                            hgb2.hgb_hrgbeli AS harga_beli
                        FROM IGRPWT.tbmaster_hargabeli hgb2
                        JOIN IGRPWT.tbmaster_supplier sup 
                        ON sup.sup_kodesupplier = hgb2.hgb_kodesupplier
                        WHERE hgb2.hgb_tipe = '2'
                    ) hgb3 ON prdm.prd_prdcd = hgb3.hgb_prdcd
                    WHERE hgb3.hgb_kodesupplier IS NULL 
                    AND hgb3.sup_namasupplier IS NULL 
                    AND hgb3.harga_beli IS NULL 
                    AND prdm.prd_deskripsipanjang <> 'NULL'
                    ORDER BY PRD_PRDCD";
        
        $results = DB::select($query);

        return view('retur.cekitem', compact('results'));
    }
}