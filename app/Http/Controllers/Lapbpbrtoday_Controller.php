<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class Lapbpbrtoday_Controller extends Controller
{
    public function bpbrtoday()
    {
        $query = "SELECT 
                                r.rom_kodetoko,
                                i.tko_namaomi AS rom_namatoko,
                                to_char(r.rom_tgldokumen, 'dd-Mon-yy') AS rom_tgldokumen,
                                r.rom_nodokumen,
                                r.rom_noreferensi,
                                to_char(r.rom_tglreferensi, 'dd-Mon-yy') AS rom_tglreferensi,
                                r.rom_member,
                                p.prd_plumcg,
                                r.rom_prdcd,
                                p.prd_deskripsipanjang AS rom_nama_barang,
                                p.prd_unit AS rom_unit,
                                p.prd_frac AS rom_frac,
                                COALESCE(p.prd_kodetag, ' ') AS rom_kodetag,
                                r.rom_qty,
                                r.rom_ttlnilai AS gross,
                                case when rom_persenppn = '11' then r.rom_ttlnilai * 0.11
                                else  r.rom_ttlnilai * 0 end as ppn,
                            --    r.rom_ttlnilai * 0.11 as PPN,
                            --  r.rom_ttlnilai + r.ppn as total,
                                r.rom_ttlnilai +
                                case when rom_persenppn = '11' then r.rom_ttlnilai * 0.11
                                else  r.rom_ttlnilai * 0 end as total,
                                case when r.rom_qty='0' then 'RETUR FISIK TOLAKAN'
                                else 'RETUR FISIK TERIMA'
                                end as ket
                            FROM 
                                igrpwt.tbtr_returomi r
                            LEFT JOIN 
                                igrpwt.tbmaster_prodmast p ON r.rom_prdcd::text = p.prd_prdcd::text  -- Ensure both are text
                            LEFT JOIN 
                                igrpwt.tbmaster_tokoigr i ON r.rom_kodetoko = i.tko_kodeomi
                            LEFT JOIN (
                                SELECT 
                                    h.hgb_prdcd,
                                    h.hgb_kodesupplier,
                                    s.sup_namasupplier
                                FROM 
                                    igrpwt.tbmaster_hargabeli h
                                LEFT JOIN 
                                    igrpwt.tbmaster_supplier s ON h.hgb_kodesupplier = s.sup_kodesupplier
                                WHERE 
                                    h.hgb_tipe = '2'
                                    AND h.hgb_recordid IS NULL
                            ) s ON r.rom_prdcd::text = s.hgb_prdcd::text  -- Ensure both are text
                            LEFT JOIN (
                                SELECT DISTINCT 
                                    shop, docno, keterangan 
                                FROM 
                                    igrpwt.TBTR_WT_INTERFACE
                            ) w ON r.rom_kodetoko::text = w.shop::text 
                                AND r.rom_noreferensi::text = w.docno::text
                            WHERE 
                                r.rom_tgldokumen=CURRENT_DATE
                                and r.rom_kodetoko in 
                            (select tko_kodeomi from igrpwt.TBMASTER_TOKOIGR where tko_kodesbu='I')";
        
        $results = DB::select($query);

        return view('retur.bpbrtoday', compact('results'));
    }
}