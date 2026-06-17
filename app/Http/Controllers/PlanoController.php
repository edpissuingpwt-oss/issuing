<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlanoController extends Controller
{
    public function plano()
    {
        return view('inventory.plano');
    }

    public function result(Request $request)
    {
        $flag = $request->input('flag');

        
        // === FLAG ===
        if ($flag == '1') {
            // Plano Minus Gudang
            $query = "SELECT 
                    -- display_toko,
                        display_gudang,
                        plu,
                        desk,
                        lpp_qty,
                        --plano_toko,
                        plano_gudang,
                        plano_storage,
                        round(selisih_qty) as selisih_qty,
                        round((plano_gudang - selisih_qty)) AS update_gudang,
                        ic.ket as ket,
                        lks_expdate
                    FROM (
                        SELECT 
                            DISPLAY_TOKO,
                            DISPLAY_GUDANG,
                            PRD_PRDCD AS PLU,
                            PRD_DESKRIPSIPANJANG AS DESK,
                            coalesce(ST_SALDOAKHIR, '0') AS LPP_QTY,
                            PLANO_TOKO,
                            PLANO_GUDANG,
                            PLANO_STORAGE,
                            ROUND(
                                coalesce(LKS_QTY, '0') + 
                                coalesce(PBO_QTYREALISASI, '0') + 
                                coalesce(QTY_ROM, '0') - 
                                coalesce(ST_SALDOAKHIR, '0'), 2
                            ) AS SELISIH_QTY,
                            gudang.lks_expdate
                        FROM igrpwt.tbmaster_prodmast
                        LEFT JOIN (
                            SELECT 
                                ST_PRDCD, 
                                coalesce(ST_SALDOAKHIR, '0') AS ST_SALDOAKHIR,
                                coalesce(ST_AVGCOST, '0') AS ST_AVGCOST
                            FROM igrpwt.TBMASTER_STOCK
                            WHERE ST_LOKASI = '01'
                            AND coalesce(ST_SALDOAKHIR, '0') <> '0'
                            AND coalesce(ST_AVGCOST, '0') <> '0'
                        ) STOCK ON PRD_PRDCD = ST_PRDCD
                        LEFT JOIN (
                            SELECT 
                                LKS_PRDCD,
                                SUM(LKS_QTY) AS LKS_QTY
                            FROM igrpwt.TBMASTER_LOKASI
                            WHERE LKS_PRDCD IS NOT NULL
                            GROUP BY LKS_PRDCD
                        ) LKS ON PRD_PRDCD = LKS.LKS_PRDCD
                        LEFT JOIN (
                            SELECT 
                                LKS_PRDCD AS LKS_TOKO,
                                LKS_KODERAK || '.' || LKS_KODESUBRAK || '.' || LKS_TIPERAK || '.' || LKS_SHELVINGRAK || '.' || LKS_NOURUT AS DISPLAY_TOKO,
                                LKS_QTY AS PLANO_TOKO
                            FROM igrpwt.TBMASTER_LOKASI
                            WHERE (LKS_KODERAK LIKE 'R%' OR LKS_KODERAK LIKE 'O%' OR LKS_KODERAK LIKE 'DKLIK%')
                            AND LKS_TIPERAK NOT IN ('S')
                            AND LKS_PRDCD IS NOT NULL
                        ) TKO ON PRD_PRDCD = TKO.LKS_TOKO
                        LEFT JOIN (
                            SELECT 
                                DISTINCT LKS_PRDCD AS LKS_GUDANG,
                                LKS_KODERAK || '.' || LKS_KODESUBRAK || '.' || LKS_TIPERAK || '.' || LKS_SHELVINGRAK || '.' || LKS_NOURUT AS DISPLAY_GUDANG,
                                LKS_QTY AS PLANO_GUDANG,
                                LKS_EXPDATE
                            FROM igrpwt.TBMASTER_LOKASI
                            WHERE LKS_KODERAK LIKE 'D%' 
                            --AND LKS_KODERAK NOT LIKE 'DKLIK%'
                            AND LKS_TIPERAK NOT IN ('S')
                            AND LKS_PRDCD IS NOT NULL
                        ) GUDANG ON PRD_PRDCD = GUDANG.LKS_GUDANG
                        LEFT JOIN (
                            SELECT 
                                LKS_PRDCD AS LKS_STORAGE,
                                SUM(LKS_QTY) AS PLANO_STORAGE
                            FROM igrpwt.TBMASTER_LOKASI
                            WHERE LKS_TIPERAK = 'S'
                            AND LKS_PRDCD IS NOT NULL
                            GROUP BY LKS_PRDCD
                        ) STORAGE ON PRD_PRDCD = STORAGE.LKS_STORAGE
                        LEFT JOIN (
                            SELECT 
                                SUBSTR(PBO_PLUIGR, 1, 6) || '0' AS PBO_PLUIGR,
                                SUM(PBO_QTYREALISASI) AS PBO_QTYREALISASI
                            FROM igrpwt.tbmaster_pbomi
                            WHERE PBO_NOKOLI IS NOT NULL
                            AND PBO_RECORDID = '4'
                            AND DATE(PBO_CREATE_DT) >=CURRENT_DATE - 7
                            AND NOT EXISTS (
                                SELECT 1
                                FROM igrpwt.tbtr_realpb
                                WHERE PBO_NOKOLI || PBO_KODEOMI || PBO_PLUIGR || PBO_QTYREALISASI = 
                                    RPB_NOKOLI || RPB_KODEOMI || RPB_PLU2 || RPB_QTYREALISASI
                            )
                            GROUP BY PBO_PLUIGR
                        ) PBO ON PRD_PRDCD = PBO.PBO_PLUIGR
                        LEFT JOIN (
                            SELECT 
                                rom_prdcd AS plu_rom,
                                SUM(rom_qty) AS qty_rom
                            FROM igrpwt.tbtr_returomi
                            WHERE date(rom_tgldokumen) >=current_date
                            AND rom_qty <> 0
                            AND ROM_KODETOKO IN (
                                SELECT TKO_KODEOMI
                                FROM igrpwt.tbmaster_tokoigr
                                WHERE TKO_KODESBU = 'O'
                            )
                            GROUP BY rom_prdcd
                        ) ROM ON PRD_PRDCD = ROM.plu_rom
                        WHERE 
                            PRD_PRDCD LIKE '%0'
                            AND PRD_UNIT <> 'KG'
                            --AND PRD_KODETAG NOT IN ('O', 'N', 'X')
                            AND coalesce (ST_SALDOAKHIR, '0') <> '0'
                            AND coalesce(ST_AVGCOST, '0') <> '0'
                    ) MAIN
                    left join
                    (select distinct
                        lso_prdcd,
                        'SO IC' as KET
                        from
                        igrpwt.tbtr_lokasi_soic
                    where
                        lso_tiperak = 'B'
                        and date(lso_create_dt)= current_date
                    )ic
                    on plu=ic.lso_prdcd
                    WHERE 
                        DISPLAY_GUDANG IS NOT NULL
                        and SELISIH_QTY < 0
                        and display_toko is null";
        } elseif ($flag == "2") {
            // Plano Minus Toko
            $query = "SELECT 
                            display_toko,
                            ---display_gudang,
                            plu,
                            desk,
                            lpp_qty,
                            plano_toko,
                            ---plano_gudang,
                            plano_storage,
                            round(selisih_qty) as selisih_qty,
                            ---(plano_gudang - selisih_qty) AS update_gudang,
                            ic.ket as ket,
                            round((plano_toko - selisih_qty)) AS update_toko
                        FROM (
                            SELECT 
                                DISPLAY_TOKO,
                                DISPLAY_GUDANG,
                                PRD_PRDCD AS PLU,
                                PRD_DESKRIPSIPANJANG AS DESK,
                                coalesce(ST_SALDOAKHIR, '0') AS LPP_QTY,
                                PLANO_TOKO,
                                PLANO_GUDANG,
                                PLANO_STORAGE,
                                ROUND(
                                    coalesce(LKS_QTY, '0') + 
                                    coalesce(PBO_QTYREALISASI, '0') + 
                                    coalesce(QTY_ROM, '0') - 
                                    coalesce(ST_SALDOAKHIR, '0'), 2
                                ) AS SELISIH_QTY
                            FROM igrpwt.tbmaster_prodmast
                            LEFT JOIN (
                                SELECT 
                                    ST_PRDCD, 
                                    coalesce(ST_SALDOAKHIR, '0') AS ST_SALDOAKHIR,
                                    coalesce(ST_AVGCOST, '0') AS ST_AVGCOST
                                FROM igrpwt.TBMASTER_STOCK
                                WHERE ST_LOKASI = '01'
                                AND coalesce(ST_SALDOAKHIR, '0') <> '0'
                                AND coalesce(ST_AVGCOST, '0') <> '0'
                            ) STOCK ON PRD_PRDCD = ST_PRDCD
                            LEFT JOIN (
                                SELECT 
                                    LKS_PRDCD,
                                    SUM(LKS_QTY) AS LKS_QTY
                                FROM igrpwt.TBMASTER_LOKASI
                                WHERE LKS_PRDCD IS NOT NULL
                                GROUP BY LKS_PRDCD
                            ) LKS ON PRD_PRDCD = LKS.LKS_PRDCD
                            LEFT JOIN (
                                SELECT 
                                    LKS_PRDCD AS LKS_TOKO,
                                    LKS_KODERAK || '.' || LKS_KODESUBRAK || '.' || LKS_TIPERAK || '.' || LKS_SHELVINGRAK || '.' || LKS_NOURUT AS DISPLAY_TOKO,
                                    LKS_QTY AS PLANO_TOKO
                                FROM igrpwt.TBMASTER_LOKASI
                                WHERE (LKS_KODERAK LIKE 'R%' OR LKS_KODERAK LIKE 'O%' OR LKS_KODERAK LIKE 'DKLIK%')
                                AND LKS_TIPERAK NOT IN ('S')
                                AND LKS_PRDCD IS NOT NULL
                            ) TKO ON PRD_PRDCD = TKO.LKS_TOKO
                            LEFT JOIN (
                                SELECT 
                                    DISTINCT LKS_PRDCD AS LKS_GUDANG,
                                    LKS_KODERAK || '.' || LKS_KODESUBRAK || '.' || LKS_TIPERAK || '.' || LKS_SHELVINGRAK || '.' || LKS_NOURUT AS DISPLAY_GUDANG,
                                    LKS_QTY AS PLANO_GUDANG
                                FROM igrpwt.TBMASTER_LOKASI
                                WHERE LKS_KODERAK LIKE 'D%' 
                                --AND LKS_KODERAK NOT LIKE 'DKLIK%'
                                AND LKS_TIPERAK NOT IN ('S')
                                AND LKS_PRDCD IS NOT NULL
                            ) GUDANG ON PRD_PRDCD = GUDANG.LKS_GUDANG
                            LEFT JOIN (
                                SELECT 
                                    LKS_PRDCD AS LKS_STORAGE,
                                    SUM(LKS_QTY) AS PLANO_STORAGE
                                FROM igrpwt.TBMASTER_LOKASI
                                WHERE LKS_TIPERAK = 'S'
                                AND LKS_PRDCD IS NOT NULL
                                GROUP BY LKS_PRDCD
                            ) STORAGE ON PRD_PRDCD = STORAGE.LKS_STORAGE
                            LEFT JOIN (
                                SELECT 
                                    SUBSTR(PBO_PLUIGR, 1, 6) || '0' AS PBO_PLUIGR,
                                    SUM(PBO_QTYREALISASI) AS PBO_QTYREALISASI
                                FROM igrpwt.tbmaster_pbomi
                                WHERE PBO_NOKOLI IS NOT NULL
                                AND PBO_RECORDID = '4'
                                AND DATE(PBO_CREATE_DT) >=CURRENT_DATE - 7
                                AND NOT EXISTS (
                                    SELECT 1
                                    FROM igrpwt.tbtr_realpb
                                    WHERE PBO_NOKOLI || PBO_KODEOMI || PBO_PLUIGR || PBO_QTYREALISASI = 
                                        RPB_NOKOLI || RPB_KODEOMI || RPB_PLU2 || RPB_QTYREALISASI
                                )
                                GROUP BY PBO_PLUIGR
                            ) PBO ON PRD_PRDCD = PBO.PBO_PLUIGR
                            LEFT JOIN (
                                SELECT 
                                    rom_prdcd AS plu_rom,
                                    SUM(rom_qty) AS qty_rom
                                FROM igrpwt.tbtr_returomi
                                WHERE date(rom_tgldokumen) >=current_date
                                AND rom_qty <> 0
                                AND ROM_KODETOKO IN (
                                    SELECT TKO_KODEOMI
                                    FROM igrpwt.tbmaster_tokoigr
                                    WHERE TKO_KODESBU = 'O'
                                )
                                GROUP BY rom_prdcd
                            ) ROM ON PRD_PRDCD = ROM.plu_rom
                            WHERE 
                                PRD_PRDCD LIKE '%0'
                                AND PRD_UNIT <> 'KG'
                                --AND PRD_KODETAG NOT IN ('O', 'N', 'X')
                                AND coalesce (ST_SALDOAKHIR, '0') <> '0'
                                AND coalesce(ST_AVGCOST, '0') <> '0'
                        ) MAIN
                        left join
                        (select distinct
                            lso_prdcd,
                            'SO IC' as KET
                            from
                            igrpwt.tbtr_lokasi_soic
                        where
                            lso_tiperak = 'B'
                            and date(lso_create_dt)= current_date
                        )ic
                        on plu=ic.lso_prdcd
                        WHERE 
                            DISPLAY_TOKO IS NOT NULL
                            and SELISIH_QTY < 0
                            and display_gudang is null";
        } elseif ($flag == "3") {
            // Plano Minus Toko dan Gudang
            $query = "SELECT 
                            display_toko,
                            display_gudang,
                            plu,
                            desk,
                            lpp_qty,
                            plano_toko,
                            plano_gudang,
                            plano_storage,
                            round(selisih_qty) as selisih_qty,
                            round((plano_gudang - selisih_qty)) AS update_gudang,
                            round((plano_toko - selisih_qty)) AS update_toko,
                            ic.ket as ket
                        FROM (
                            SELECT 
                                DISPLAY_TOKO,
                                DISPLAY_GUDANG,
                                PRD_PRDCD AS PLU,
                                PRD_DESKRIPSIPANJANG AS DESK,
                                coalesce(ST_SALDOAKHIR, '0') AS LPP_QTY,
                                PLANO_TOKO,
                                PLANO_GUDANG,
                                PLANO_STORAGE,
                                ROUND(
                                    coalesce(LKS_QTY, '0') + 
                                    coalesce(PBO_QTYREALISASI, '0') + 
                                    coalesce(QTY_ROM, '0') - 
                                    coalesce(ST_SALDOAKHIR, '0'), 2
                                ) AS SELISIH_QTY
                            FROM igrpwt.tbmaster_prodmast
                            LEFT JOIN (
                                SELECT 
                                    ST_PRDCD, 
                                    coalesce(ST_SALDOAKHIR, '0') AS ST_SALDOAKHIR,
                                    coalesce(ST_AVGCOST, '0') AS ST_AVGCOST
                                FROM igrpwt.TBMASTER_STOCK
                                WHERE ST_LOKASI = '01'
                                AND coalesce(ST_SALDOAKHIR, '0') <> '0'
                                AND coalesce(ST_AVGCOST, '0') <> '0'
                            ) STOCK ON PRD_PRDCD = ST_PRDCD
                            LEFT JOIN (
                                SELECT 
                                    LKS_PRDCD,
                                    SUM(LKS_QTY) AS LKS_QTY
                                FROM igrpwt.TBMASTER_LOKASI
                                WHERE LKS_PRDCD IS NOT NULL
                                GROUP BY LKS_PRDCD
                            ) LKS ON PRD_PRDCD = LKS.LKS_PRDCD
                            LEFT JOIN (
                                SELECT 
                                    LKS_PRDCD AS LKS_TOKO,
                                    LKS_KODERAK || '.' || LKS_KODESUBRAK || '.' || LKS_TIPERAK || '.' || LKS_SHELVINGRAK || '.' || LKS_NOURUT AS DISPLAY_TOKO,
                                    LKS_QTY AS PLANO_TOKO
                                FROM igrpwt.TBMASTER_LOKASI
                                WHERE (LKS_KODERAK LIKE 'R%' OR LKS_KODERAK LIKE 'O%' OR LKS_KODERAK LIKE 'DKLIK%')
                                AND LKS_TIPERAK NOT IN ('S')
                                AND LKS_PRDCD IS NOT NULL
                            ) TKO ON PRD_PRDCD = TKO.LKS_TOKO
                            LEFT JOIN (
                                SELECT 
                                    DISTINCT LKS_PRDCD AS LKS_GUDANG,
                                    LKS_KODERAK || '.' || LKS_KODESUBRAK || '.' || LKS_TIPERAK || '.' || LKS_SHELVINGRAK || '.' || LKS_NOURUT AS DISPLAY_GUDANG,
                                    LKS_QTY AS PLANO_GUDANG
                                FROM igrpwt.TBMASTER_LOKASI
                                WHERE LKS_KODERAK LIKE 'D%' 
                                --AND LKS_KODERAK NOT LIKE 'DKLIK%'
                                AND LKS_TIPERAK NOT IN ('S')
                                AND LKS_PRDCD IS NOT NULL
                            ) GUDANG ON PRD_PRDCD = GUDANG.LKS_GUDANG
                            LEFT JOIN (
                                SELECT 
                                    LKS_PRDCD AS LKS_STORAGE,
                                    SUM(LKS_QTY) AS PLANO_STORAGE
                                FROM igrpwt.TBMASTER_LOKASI
                                WHERE LKS_TIPERAK = 'S'
                                AND LKS_PRDCD IS NOT NULL
                                GROUP BY LKS_PRDCD
                            ) STORAGE ON PRD_PRDCD = STORAGE.LKS_STORAGE
                            LEFT JOIN (
                                SELECT 
                                    SUBSTR(PBO_PLUIGR, 1, 6) || '0' AS PBO_PLUIGR,
                                    SUM(PBO_QTYREALISASI) AS PBO_QTYREALISASI
                                FROM igrpwt.tbmaster_pbomi
                                WHERE PBO_NOKOLI IS NOT NULL
                                AND PBO_RECORDID = '4'
                                AND DATE(PBO_CREATE_DT) >=CURRENT_DATE - 7
                                AND NOT EXISTS (
                                    SELECT 1
                                    FROM igrpwt.tbtr_realpb
                                    WHERE PBO_NOKOLI || PBO_KODEOMI || PBO_PLUIGR || PBO_QTYREALISASI = 
                                        RPB_NOKOLI || RPB_KODEOMI || RPB_PLU2 || RPB_QTYREALISASI
                                )
                                GROUP BY PBO_PLUIGR
                            ) PBO ON PRD_PRDCD = PBO.PBO_PLUIGR
                            LEFT JOIN (
                                SELECT 
                                    rom_prdcd AS plu_rom,
                                    SUM(rom_qty) AS qty_rom
                                FROM igrpwt.tbtr_returomi
                                WHERE date(rom_tgldokumen) >=current_date
                                AND rom_qty <> 0
                                AND ROM_KODETOKO IN (
                                    SELECT TKO_KODEOMI
                                    FROM igrpwt.tbmaster_tokoigr
                                    WHERE TKO_KODESBU = 'O'
                                )
                                GROUP BY rom_prdcd
                            ) ROM ON PRD_PRDCD = ROM.plu_rom
                            WHERE 
                                PRD_PRDCD LIKE '%0'
                                AND PRD_UNIT <> 'KG'
                                --AND PRD_KODETAG NOT IN ('O', 'N', 'X')
                                AND coalesce (ST_SALDOAKHIR, '0') <> '0'
                                AND coalesce(ST_AVGCOST, '0') <> '0'
                        ) MAIN
                        left join
                        (select distinct
                            lso_prdcd,
                            'SO IC' as KET
                            from
                            igrpwt.tbtr_lokasi_soic
                        where
                            lso_tiperak = 'B'
                            and date(lso_create_dt)= current_date
                        )ic
                        on plu=ic.lso_prdcd
                        WHERE 
                            DISPLAY_TOKO || DISPLAY_GUDANG IS NOT NULL
                            and SELISIH_QTY < 0";
        } elseif ($flag == "4") {
            // Plano Plus Gudang
            $query = "SELECT 
                            display_toko,
                            display_gudang,
                            plu,
                            desk,
                            lpp_qty,
                            plano_toko,
                            plano_gudang,
                            plano_storage,
                            round(selisih_qty) as selisih_qty,
                            ic.ket as ket,
                            round((plano_gudang - selisih_qty)) AS update_gudang,
                            (plano_toko - selisih_qty) AS update_toko
                        FROM (
                            SELECT 
                                DISPLAY_TOKO,
                                DISPLAY_GUDANG,
                                PRD_PRDCD AS PLU,
                                PRD_DESKRIPSIPANJANG AS DESK,
                                coalesce(ST_SALDOAKHIR, '0') AS LPP_QTY,
                                PLANO_TOKO,
                                PLANO_GUDANG,
                                PLANO_STORAGE,
                                ROUND(
                                    coalesce(LKS_QTY, '0') + 
                                    coalesce(PBO_QTYREALISASI, '0') + 
                                    coalesce(QTY_ROM, '0') - 
                                    coalesce(ST_SALDOAKHIR, '0'), 2
                                ) AS SELISIH_QTY
                            FROM igrpwt.tbmaster_prodmast
                            LEFT JOIN (
                                SELECT 
                                    ST_PRDCD, 
                                    coalesce(ST_SALDOAKHIR, '0') AS ST_SALDOAKHIR,
                                    coalesce(ST_AVGCOST, '0') AS ST_AVGCOST
                                FROM igrpwt.TBMASTER_STOCK
                                WHERE ST_LOKASI = '01'
                                AND coalesce(ST_SALDOAKHIR, '0') <> '0'
                                AND coalesce(ST_AVGCOST, '0') <> '0'
                            ) STOCK ON PRD_PRDCD = ST_PRDCD
                            LEFT JOIN (
                                SELECT 
                                    LKS_PRDCD,
                                    SUM(LKS_QTY) AS LKS_QTY
                                FROM igrpwt.TBMASTER_LOKASI
                                WHERE LKS_PRDCD IS NOT NULL
                                GROUP BY LKS_PRDCD
                            ) LKS ON PRD_PRDCD = LKS.LKS_PRDCD
                            LEFT JOIN (
                                SELECT 
                                    LKS_PRDCD AS LKS_TOKO,
                                    LKS_KODERAK || '.' || LKS_KODESUBRAK || '.' || LKS_TIPERAK || '.' || LKS_SHELVINGRAK || '.' || LKS_NOURUT AS DISPLAY_TOKO,
                                    LKS_QTY AS PLANO_TOKO
                                FROM igrpwt.TBMASTER_LOKASI
                                WHERE (LKS_KODERAK LIKE 'R%' OR LKS_KODERAK LIKE 'O%' OR LKS_KODERAK LIKE 'DKLIK%')
                                AND LKS_TIPERAK NOT IN ('S')
                                AND LKS_PRDCD IS NOT NULL
                            ) TKO ON PRD_PRDCD = TKO.LKS_TOKO
                            LEFT JOIN (
                                SELECT 
                                    DISTINCT LKS_PRDCD AS LKS_GUDANG,
                                    LKS_KODERAK || '.' || LKS_KODESUBRAK || '.' || LKS_TIPERAK || '.' || LKS_SHELVINGRAK || '.' || LKS_NOURUT AS DISPLAY_GUDANG,
                                    LKS_QTY AS PLANO_GUDANG
                                FROM igrpwt.TBMASTER_LOKASI
                                WHERE LKS_KODERAK LIKE 'D%' 
                                --AND LKS_KODERAK NOT LIKE 'DKLIK%'
                                AND LKS_TIPERAK NOT IN ('S')
                                AND LKS_PRDCD IS NOT NULL
                            ) GUDANG ON PRD_PRDCD = GUDANG.LKS_GUDANG
                            LEFT JOIN (
                                SELECT 
                                    LKS_PRDCD AS LKS_STORAGE,
                                    SUM(LKS_QTY) AS PLANO_STORAGE
                                FROM igrpwt.TBMASTER_LOKASI
                                WHERE LKS_TIPERAK = 'S'
                                AND LKS_PRDCD IS NOT NULL
                                GROUP BY LKS_PRDCD
                            ) STORAGE ON PRD_PRDCD = STORAGE.LKS_STORAGE
                            LEFT JOIN (
                                SELECT 
                                    SUBSTR(PBO_PLUIGR, 1, 6) || '0' AS PBO_PLUIGR,
                                    SUM(PBO_QTYREALISASI) AS PBO_QTYREALISASI
                                FROM igrpwt.tbmaster_pbomi
                                WHERE PBO_NOKOLI IS NOT NULL
                                AND PBO_RECORDID = '4'
                                AND DATE(PBO_CREATE_DT) >=CURRENT_DATE - 7
                                AND NOT EXISTS (
                                    SELECT 1
                                    FROM igrpwt.tbtr_realpb
                                    WHERE PBO_NOKOLI || PBO_KODEOMI || PBO_PLUIGR || PBO_QTYREALISASI = 
                                        RPB_NOKOLI || RPB_KODEOMI || RPB_PLU2 || RPB_QTYREALISASI
                                )
                                GROUP BY PBO_PLUIGR
                            ) PBO ON PRD_PRDCD = PBO.PBO_PLUIGR
                            LEFT JOIN (
                                SELECT 
                                    rom_prdcd AS plu_rom,
                                    SUM(rom_qty) AS qty_rom
                                FROM igrpwt.tbtr_returomi
                                WHERE date(rom_tgldokumen) >=current_date
                                AND rom_qty <> 0
                                AND ROM_KODETOKO IN (
                                    SELECT TKO_KODEOMI
                                    FROM igrpwt.tbmaster_tokoigr
                                    WHERE TKO_KODESBU = 'O'
                                )
                                GROUP BY rom_prdcd
                            ) ROM ON PRD_PRDCD = ROM.plu_rom
                            WHERE 
                                PRD_PRDCD LIKE '%0'
                                AND PRD_UNIT <> 'KG'
                                --AND PRD_KODETAG NOT IN ('O', 'N', 'X')
                                AND coalesce (ST_SALDOAKHIR, '0') <> '0'
                                AND coalesce(ST_AVGCOST, '0') <> '0'
                        ) MAIN
                        left join
                        (select distinct
                            lso_prdcd,
                            'SO IC' as KET
                            from
                            igrpwt.tbtr_lokasi_soic
                        where
                            lso_tiperak = 'B'
                            and date(lso_create_dt)= current_date
                        )IC  
                        on plu=IC.lso_prdcd
                        WHERE 
                            DISPLAY_GUDANG IS NOT NULL
                            and SELISIH_QTY > 0
                            and display_toko is null";
        } elseif ($flag == "5") {
            // Plano Plus Toko
            $query = "SELECT 
                            display_toko,
                            ---display_gudang,
                            plu,
                            desk,
                            lpp_qty,
                            plano_toko,
                            ---plano_gudang,
                            plano_storage,
                            round(selisih_qty) as selisih_qty,
                            ---(plano_gudang - selisih_qty) AS update_gudang,
                            round((plano_toko - selisih_qty)) AS update_toko,
                            ic.ket as ket
                        FROM (
                            SELECT 
                                DISPLAY_TOKO,
                                DISPLAY_GUDANG,
                                PRD_PRDCD AS PLU,
                                PRD_DESKRIPSIPANJANG AS DESK,
                                coalesce(ST_SALDOAKHIR, '0') AS LPP_QTY,
                                PLANO_TOKO,
                                PLANO_GUDANG,
                                PLANO_STORAGE,
                                ROUND(
                                    coalesce(LKS_QTY, '0') + 
                                    coalesce(PBO_QTYREALISASI, '0') + 
                                    coalesce(QTY_ROM, '0') - 
                                    coalesce(ST_SALDOAKHIR, '0'), 2
                                ) AS SELISIH_QTY
                            FROM igrpwt.tbmaster_prodmast
                            LEFT JOIN (
                                SELECT 
                                    ST_PRDCD, 
                                    coalesce(ST_SALDOAKHIR, '0') AS ST_SALDOAKHIR,
                                    coalesce(ST_AVGCOST, '0') AS ST_AVGCOST
                                FROM igrpwt.TBMASTER_STOCK
                                WHERE ST_LOKASI = '01'
                                AND coalesce(ST_SALDOAKHIR, '0') <> '0'
                                AND coalesce(ST_AVGCOST, '0') <> '0'
                            ) STOCK ON PRD_PRDCD = ST_PRDCD
                            LEFT JOIN (
                                SELECT 
                                    LKS_PRDCD,
                                    SUM(LKS_QTY) AS LKS_QTY
                                FROM igrpwt.TBMASTER_LOKASI
                                WHERE LKS_PRDCD IS NOT NULL
                                GROUP BY LKS_PRDCD
                            ) LKS ON PRD_PRDCD = LKS.LKS_PRDCD
                            LEFT JOIN (
                                SELECT 
                                    LKS_PRDCD AS LKS_TOKO,
                                    LKS_KODERAK || '.' || LKS_KODESUBRAK || '.' || LKS_TIPERAK || '.' || LKS_SHELVINGRAK || '.' || LKS_NOURUT AS DISPLAY_TOKO,
                                    LKS_QTY AS PLANO_TOKO
                                FROM igrpwt.TBMASTER_LOKASI
                                WHERE (LKS_KODERAK LIKE 'R%' OR LKS_KODERAK LIKE 'O%' OR LKS_KODERAK LIKE 'DKLIK%')
                                AND LKS_TIPERAK NOT IN ('S')
                                AND LKS_PRDCD IS NOT NULL
                            ) TKO ON PRD_PRDCD = TKO.LKS_TOKO
                            LEFT JOIN (
                                SELECT 
                                    DISTINCT LKS_PRDCD AS LKS_GUDANG,
                                    LKS_KODERAK || '.' || LKS_KODESUBRAK || '.' || LKS_TIPERAK || '.' || LKS_SHELVINGRAK || '.' || LKS_NOURUT AS DISPLAY_GUDANG,
                                    LKS_QTY AS PLANO_GUDANG
                                FROM igrpwt.TBMASTER_LOKASI
                                WHERE LKS_KODERAK LIKE 'D%' 
                                --AND LKS_KODERAK NOT LIKE 'DKLIK%'
                                AND LKS_TIPERAK NOT IN ('S')
                                AND LKS_PRDCD IS NOT NULL
                            ) GUDANG ON PRD_PRDCD = GUDANG.LKS_GUDANG
                            LEFT JOIN (
                                SELECT 
                                    LKS_PRDCD AS LKS_STORAGE,
                                    SUM(LKS_QTY) AS PLANO_STORAGE
                                FROM igrpwt.TBMASTER_LOKASI
                                WHERE LKS_TIPERAK = 'S'
                                AND LKS_PRDCD IS NOT NULL
                                GROUP BY LKS_PRDCD
                            ) STORAGE ON PRD_PRDCD = STORAGE.LKS_STORAGE
                            LEFT JOIN (
                                SELECT 
                                    SUBSTR(PBO_PLUIGR, 1, 6) || '0' AS PBO_PLUIGR,
                                    SUM(PBO_QTYREALISASI) AS PBO_QTYREALISASI
                                FROM igrpwt.tbmaster_pbomi
                                WHERE PBO_NOKOLI IS NOT NULL
                                AND PBO_RECORDID = '4'
                                AND DATE(PBO_CREATE_DT) >=CURRENT_DATE - 7
                                AND NOT EXISTS (
                                    SELECT 1
                                    FROM igrpwt.tbtr_realpb
                                    WHERE PBO_NOKOLI || PBO_KODEOMI || PBO_PLUIGR || PBO_QTYREALISASI = 
                                        RPB_NOKOLI || RPB_KODEOMI || RPB_PLU2 || RPB_QTYREALISASI
                                )
                                GROUP BY PBO_PLUIGR
                            ) PBO ON PRD_PRDCD = PBO.PBO_PLUIGR
                            LEFT JOIN (
                                SELECT 
                                    rom_prdcd AS plu_rom,
                                    SUM(rom_qty) AS qty_rom
                                FROM igrpwt.tbtr_returomi
                                WHERE date(rom_tgldokumen) >=current_date
                                AND rom_qty <> 0
                                AND ROM_KODETOKO IN (
                                    SELECT TKO_KODEOMI
                                    FROM igrpwt.tbmaster_tokoigr
                                    WHERE TKO_KODESBU = 'O'
                                )
                                GROUP BY rom_prdcd
                            ) ROM ON PRD_PRDCD = ROM.plu_rom
                            WHERE 
                                PRD_PRDCD LIKE '%0'
                                AND PRD_UNIT <> 'KG'
                                --AND PRD_KODETAG NOT IN ('O', 'N', 'X')
                                AND coalesce (ST_SALDOAKHIR, '0') <> '0'
                                AND coalesce(ST_AVGCOST, '0') <> '0'
                        ) MAIN
                        left join
                        (select distinct
                            lso_prdcd,
                            'SO IC' as KET
                            from
                            igrpwt.tbtr_lokasi_soic
                        where
                            lso_tiperak = 'B'
                            and date(lso_create_dt)= current_date
                        )ic
                        on plu=ic.lso_prdcd
                        WHERE 
                            DISPLAY_TOKO IS NOT NULL
                            and SELISIH_QTY > 0
                            and display_gudang is null";
        } elseif ($flag == "6") {
            // Plano Plus Gudang dan Toko
            $query = "SELECT 
                            display_toko,
                            display_gudang,
                            plu,
                            desk,
                            lpp_qty,
                            plano_toko,
                            plano_gudang,
                            plano_storage,
                            round(selisih_qty) selisih_qty,
                            round((plano_gudang - selisih_qty)) AS update_gudang,
                            round((plano_toko - selisih_qty)) AS update_toko,
                            ic.ket as ket
                        FROM (
                            SELECT 
                                DISPLAY_TOKO,
                                DISPLAY_GUDANG,
                                PRD_PRDCD AS PLU,
                                PRD_DESKRIPSIPANJANG AS DESK,
                                coalesce(ST_SALDOAKHIR, '0') AS LPP_QTY,
                                PLANO_TOKO,
                                PLANO_GUDANG,
                                PLANO_STORAGE,
                                ROUND(
                                    coalesce(LKS_QTY, '0') + 
                                    coalesce(PBO_QTYREALISASI, '0') + 
                                    coalesce(QTY_ROM, '0') - 
                                    coalesce(ST_SALDOAKHIR, '0'), 2
                                ) AS SELISIH_QTY
                            FROM igrpwt.tbmaster_prodmast
                            LEFT JOIN (
                                SELECT 
                                    ST_PRDCD, 
                                    coalesce(ST_SALDOAKHIR, '0') AS ST_SALDOAKHIR,
                                    coalesce(ST_AVGCOST, '0') AS ST_AVGCOST
                                FROM igrpwt.TBMASTER_STOCK
                                WHERE ST_LOKASI = '01'
                                AND coalesce(ST_SALDOAKHIR, '0') <> '0'
                                AND coalesce(ST_AVGCOST, '0') <> '0'
                            ) STOCK ON PRD_PRDCD = ST_PRDCD
                            LEFT JOIN (
                                SELECT 
                                    LKS_PRDCD,
                                    SUM(LKS_QTY) AS LKS_QTY
                                FROM igrpwt.TBMASTER_LOKASI
                                WHERE LKS_PRDCD IS NOT NULL
                                GROUP BY LKS_PRDCD
                            ) LKS ON PRD_PRDCD = LKS.LKS_PRDCD
                            LEFT JOIN (
                                SELECT 
                                    LKS_PRDCD AS LKS_TOKO,
                                    LKS_KODERAK || '.' || LKS_KODESUBRAK || '.' || LKS_TIPERAK || '.' || LKS_SHELVINGRAK || '.' || LKS_NOURUT AS DISPLAY_TOKO,
                                    LKS_QTY AS PLANO_TOKO
                                FROM igrpwt.TBMASTER_LOKASI
                                WHERE (LKS_KODERAK LIKE 'R%' OR LKS_KODERAK LIKE 'O%' OR LKS_KODERAK LIKE 'DKLIK%')
                                AND LKS_TIPERAK NOT IN ('S')
                                AND LKS_PRDCD IS NOT NULL
                            ) TKO ON PRD_PRDCD = TKO.LKS_TOKO
                            LEFT JOIN (
                                SELECT 
                                    DISTINCT LKS_PRDCD AS LKS_GUDANG,
                                    LKS_KODERAK || '.' || LKS_KODESUBRAK || '.' || LKS_TIPERAK || '.' || LKS_SHELVINGRAK || '.' || LKS_NOURUT AS DISPLAY_GUDANG,
                                    LKS_QTY AS PLANO_GUDANG
                                FROM igrpwt.TBMASTER_LOKASI
                                WHERE LKS_KODERAK LIKE 'D%' 
                                --AND LKS_KODERAK NOT LIKE 'DKLIK%'
                                AND LKS_TIPERAK NOT IN ('S')
                                AND LKS_PRDCD IS NOT NULL
                            ) GUDANG ON PRD_PRDCD = GUDANG.LKS_GUDANG
                            LEFT JOIN (
                                SELECT 
                                    LKS_PRDCD AS LKS_STORAGE,
                                    SUM(LKS_QTY) AS PLANO_STORAGE
                                FROM igrpwt.TBMASTER_LOKASI
                                WHERE LKS_TIPERAK = 'S'
                                AND LKS_PRDCD IS NOT NULL
                                GROUP BY LKS_PRDCD
                            ) STORAGE ON PRD_PRDCD = STORAGE.LKS_STORAGE
                            LEFT JOIN (
                                SELECT 
                                    SUBSTR(PBO_PLUIGR, 1, 6) || '0' AS PBO_PLUIGR,
                                    SUM(PBO_QTYREALISASI) AS PBO_QTYREALISASI
                                FROM igrpwt.tbmaster_pbomi
                                WHERE PBO_NOKOLI IS NOT NULL
                                AND PBO_RECORDID = '4'
                                AND DATE(PBO_CREATE_DT) >=CURRENT_DATE - 7
                                AND NOT EXISTS (
                                    SELECT 1
                                    FROM igrpwt.tbtr_realpb
                                    WHERE PBO_NOKOLI || PBO_KODEOMI || PBO_PLUIGR || PBO_QTYREALISASI = 
                                        RPB_NOKOLI || RPB_KODEOMI || RPB_PLU2 || RPB_QTYREALISASI
                                )
                                GROUP BY PBO_PLUIGR
                            ) PBO ON PRD_PRDCD = PBO.PBO_PLUIGR
                            LEFT JOIN (
                                SELECT 
                                    rom_prdcd AS plu_rom,
                                    SUM(rom_qty) AS qty_rom
                                FROM igrpwt.tbtr_returomi
                                WHERE date(rom_tgldokumen) >=current_date
                                AND rom_qty <> 0
                                AND ROM_KODETOKO IN (
                                    SELECT TKO_KODEOMI
                                    FROM igrpwt.tbmaster_tokoigr
                                    WHERE TKO_KODESBU = 'O'
                                )
                                GROUP BY rom_prdcd
                            ) ROM ON PRD_PRDCD = ROM.plu_rom
                            WHERE 
                                PRD_PRDCD LIKE '%0'
                                AND PRD_UNIT <> 'KG'
                                --AND PRD_KODETAG NOT IN ('O', 'N', 'X')
                                AND coalesce (ST_SALDOAKHIR, '0') <> '0'
                                AND coalesce(ST_AVGCOST, '0') <> '0'
                        ) MAIN
                        left join
                        (select distinct
                            lso_prdcd,
                            'SO IC' as KET
                            from
                            igrpwt.tbtr_lokasi_soic
                        where
                            lso_tiperak = 'B'
                            and date(lso_create_dt)= current_date
                        )ic
                        on plu=ic.lso_prdcd
                        WHERE 
                            DISPLAY_TOKO || DISPLAY_GUDANG IS NOT NULL
                            and SELISIH_QTY > 0";
        } elseif ($flag == "7") {
            // Rekap
            $query = "WITH main_data AS (
                            SELECT 
                                PRD_PRDCD AS plu,
                                PRD_DESKRIPSIPANJANG AS desk,
                                coalesce(ST.ST_SALDOAKHIR, 0) AS lpp_qty,
                                TKO.display_toko,
                                GUDANG.display_gudang,
                                coalesce(TKO.plano_toko, 0) AS plano_toko,
                                coalesce(GUDANG.plano_gudang, 0) AS plano_gudang,
                                coalesce(STORAGE.plano_storage, 0) AS plano_storage,
                                ROUND(
                                    coalesce(LKS.LKS_QTY, 0)
                                    + coalesce(PBO.PBO_QTYREALISASI, 0)
                                    + coalesce(ROM.qty_rom, 0)
                                    - coalesce(ST.ST_SALDOAKHIR, 0), 2
                                ) AS selisih_qty,
                                ic.ket AS ket
                            FROM igrpwt.tbmaster_prodmast p
                            LEFT JOIN (
                                SELECT ST_PRDCD, ST_SALDOAKHIR, ST_AVGCOST
                                FROM igrpwt.TBMASTER_STOCK
                                WHERE ST_LOKASI = '01'
                                AND coalesce(ST_SALDOAKHIR, 0) <> 0
                                AND coalesce(ST_AVGCOST, 0) <> 0
                            ) ST ON p.PRD_PRDCD = ST.ST_PRDCD
                            LEFT JOIN (
                                SELECT LKS_PRDCD, SUM(LKS_QTY) AS LKS_QTY
                                FROM igrpwt.TBMASTER_LOKASI
                                WHERE LKS_PRDCD IS NOT NULL
                                GROUP BY LKS_PRDCD
                            ) LKS ON p.PRD_PRDCD = LKS.LKS_PRDCD
                            LEFT JOIN (
                                SELECT 
                                    LKS_PRDCD AS LKS_TOKO,
                                    LKS_KODERAK || '.' || LKS_KODESUBRAK || '.' || LKS_TIPERAK || '.' || 
                                    LKS_SHELVINGRAK || '.' || LKS_NOURUT AS display_toko,
                                    LKS_QTY AS plano_toko
                                FROM igrpwt.TBMASTER_LOKASI
                                WHERE (LKS_KODERAK LIKE 'R%' OR LKS_KODERAK LIKE 'O%' OR LKS_KODERAK LIKE 'DKLIK%')
                                AND LKS_TIPERAK <> 'S'
                                AND LKS_PRDCD IS NOT NULL
                            ) TKO ON p.PRD_PRDCD = TKO.LKS_TOKO
                            LEFT JOIN (
                                SELECT 
                                    LKS_PRDCD AS LKS_GUDANG,
                                    LKS_KODERAK || '.' || LKS_KODESUBRAK || '.' || LKS_TIPERAK || '.' || 
                                    LKS_SHELVINGRAK || '.' || LKS_NOURUT AS display_gudang,
                                    LKS_QTY AS plano_gudang
                                FROM igrpwt.TBMASTER_LOKASI
                                WHERE LKS_KODERAK LIKE 'D%'
                                AND LKS_TIPERAK <> 'S'
                                AND LKS_PRDCD IS NOT NULL
                            ) GUDANG ON p.PRD_PRDCD = GUDANG.LKS_GUDANG
                            LEFT JOIN (
                                SELECT LKS_PRDCD, SUM(LKS_QTY) AS plano_storage
                                FROM igrpwt.TBMASTER_LOKASI
                                WHERE LKS_TIPERAK = 'S'
                                GROUP BY LKS_PRDCD
                            ) STORAGE ON p.PRD_PRDCD = STORAGE.LKS_PRDCD
                            LEFT JOIN (
                                SELECT SUBSTR(PBO_PLUIGR, 1, 6) || '0' AS PBO_PLUIGR, SUM(PBO_QTYREALISASI) AS PBO_QTYREALISASI
                                FROM igrpwt.tbmaster_pbomi
                                WHERE PBO_NOKOLI IS NOT NULL
                                AND PBO_RECORDID = '4'
                                AND DATE(PBO_CREATE_DT) >= CURRENT_DATE - 7
                                AND NOT EXISTS (
                                    SELECT 1
                                    FROM igrpwt.tbtr_realpb
                                    WHERE PBO_NOKOLI || PBO_KODEOMI || PBO_PLUIGR || PBO_QTYREALISASI = 
                                            RPB_NOKOLI || RPB_KODEOMI || RPB_PLU2 || RPB_QTYREALISASI
                                )
                                GROUP BY PBO_PLUIGR
                            ) PBO ON p.PRD_PRDCD = PBO.PBO_PLUIGR
                            LEFT JOIN (
                                SELECT rom_prdcd, SUM(rom_qty) AS qty_rom
                                FROM igrpwt.tbtr_returomi
                                WHERE date(rom_tgldokumen) >= current_date
                                AND rom_qty <> 0
                                AND ROM_KODETOKO IN (
                                    SELECT TKO_KODEOMI FROM igrpwt.tbmaster_tokoigr WHERE TKO_KODESBU = 'O'
                                )
                                GROUP BY rom_prdcd
                            ) ROM ON p.PRD_PRDCD = ROM.rom_prdcd
                            LEFT JOIN (
                                SELECT DISTINCT lso_prdcd, 'SO IC' AS ket
                                FROM igrpwt.tbtr_lokasi_soic
                                WHERE lso_tiperak = 'B'
                                AND date(lso_create_dt) = current_date
                            ) ic ON p.PRD_PRDCD = ic.lso_prdcd
                            WHERE p.PRD_PRDCD LIKE '%0'
                            AND p.PRD_UNIT <> 'KG'
                            AND coalesce(ST.ST_SALDOAKHIR, 0) <> 0
                            AND coalesce(ST.ST_AVGCOST, 0) <> 0
                        )
                        SELECT 'Plano Minus Gudang' AS ket, COUNT(plu) AS total FROM main_data WHERE selisih_qty < 0 AND display_toko IS NULL
                        UNION ALL
                        SELECT 'Plano Minus Toko', COUNT(plu) FROM main_data WHERE selisih_qty < 0 AND display_gudang IS NULL
                        UNION ALL
                        SELECT 'Plano Plus Gudang', COUNT(plu) FROM main_data WHERE selisih_qty > 0 AND display_toko IS NULL
                        UNION ALL
                        SELECT 'Plano Plus Toko', COUNT(plu) FROM main_data WHERE selisih_qty > 0 AND display_gudang IS NULL
                        UNION ALL
                        SELECT 'Plano Plus Gudang & Toko', COUNT(plu) FROM main_data WHERE selisih_qty > 0 AND display_toko IS NOT NULL AND display_gudang IS NOT NULL
                        UNION ALL
                        SELECT 'Plano Minus Gudang & Toko', COUNT(plu) FROM main_data WHERE selisih_qty < 0 AND display_toko IS NOT NULL AND display_gudang IS NOT NULL";
        }
        

        $data = DB::select($query);

        return view('inventory.plano_result', [
            'data' => $data,
            'flag' => $flag
        ]);
    }
}
