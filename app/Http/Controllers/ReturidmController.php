<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\View;

class ReturidmController extends Controller
{
    public function returidm()
    {
        return view('retur.returidm');
    }

    public function getData(Request $request)
    {
        $flag = $request->input('flag');
        $tglMulai = $request->input('tanggal_mulai');
        $tglSelesai = $request->input('tanggal_selesai');

        $tglMulai = date("Y-m-d", strtotime($tglMulai));
        $tglSelesai = date("Y-m-d", strtotime($tglSelesai));

        $belumabsen = "SELECT   p_id,
                                recid,
                                docno,
                                Shop,
                            Count(Qty) As Item,
                            Round(Sum (Gross + Ppn),0) As Total,
                            to_char(Tgl1, 'dd-Mon-yy') Tgl_Pembuatan_File,
                            to_char(WT_CREATE_DT, 'dd-Mon-yy') File_Masuk_Igr,
                            NM_WT,
                            CASE WHEN NM_WT LIKE 'WT_R%' THEN 'WEB SERVICE' ELSE 'MANUAL' END KETERANGAN
                            from (select * from igrpwt.tbtr_wt_interface where recid = 'N') as wt
                                left join (select * from igrpwt.tbmaster_prodmast where prd_prdcd like '%0') as prd
                            on prd_plumcg = prdcd
                                Left Join (Select * From igrpwt.Tbmaster_Prodcrm Where Prc_Group = 'I') as prc
                            on prc_pluidm = prdcd GROUP BY p_id, recid, docno, Shop, Tgl1, Wt_Create_Dt, NM_WT, CASE WHEN NM_WT LIKE 'WT_R%' THEN 'WEB SERVICE' ELSE 'MANUAL' END order by p_id";
        
        $selesaiabsen = "SELECT recid,retur,wt_create_dt,
                                case when retur = 'RETUR PROFORMA' then null
                                    else lar_create_dt end as lar_create_dt,
                                kodetoko,nonrb,jml_item,rupiah,ppn
                                 FROM (
                                        SELECT
                                            DISTINCT DOCNO AS NONRB,
                                            SUBSTRING(KETERANGAN, 10) AS RETUR,
                                            SHOP AS KODETOKO,
                                            to_char(WT_CREATE_DT, 'dd-Mon-yy') WT_CREATE_DT,
                                            recid,
                                            COUNT(PRDCD) AS JML_ITEM,
                                            SUM(GROSS) AS RUPIAH,
                                            SUM(PPN) AS PPN
                                        FROM IGRPWT.TBTR_WT_INTERFACE
                                        WHERE RECID = 'A'
                                        GROUP BY NONRB, KODETOKO, WT_CREATE_DT, recid, keterangan
                                    ) AA
                                    LEFT JOIN (
                                        SELECT LAR_USERID, LAR_TOKO, LAR_NONRB, to_char(LAR_CREATE_DT, 'dd-Mon-yy') as LAR_CREATE_DT FROM IGRPWT.LOG_ABSENSI_RETUR
                                    ) BB ON AA.KODETOKO || AA.NONRB = BB.LAR_TOKO || LAR_NONRB
                                    order by lar_create_dt";

        $selesaisortasi = "SELECT   to_char(sor_tglclose, 'dd-Mon-yy') tgl_sortasi,
                                    ab.shop as kodetoko,
                                    ab.docno as docno,
                                    prd_prdcd as PLU,
                                    Prd_Deskripsipanjang As Ket,
                                    sor_qty_nrb as QTY,
                                    sor_qty_fisik as fisik,
                                    sor_qty_bakurang as kurang,
                                    sor_qty_baik as baik,
                                    sor_qty_layakretur as layak,
                                    sor_qty_batolak as tidak_layak,
                                    prd_perlakuanbarang as statuss,
                                    prd_kodetag as tag_igr,
                                    prc_kodetag as tag_idm,
                                    sor_tglexpdate as exp_date
                                    From
                                    (Select * From IGRPWT.Tbtr_Wt_Interface Where Recid = 'S') Ab
                                    left join
                                    (Select * From IGRPWT.Tbmaster_Prodmast Where Prd_Prdcd Like '%0') as prd
                                    on prdcd = prd_plumcg
                                    left join
                                    (Select * From IGRPWT.Tbmaster_Prodcrm Where Prc_Group = 'I') as prc
                                    On Prdcd = Prc_Pluidm
                                    Left Join
                                    (Select * From IGRPWT.Tbtr_Sortasi_Retur Where Sor_Modify_By Is Not Null And sor_tglclose::date Between ? AND ? ) as sor
                                    On Shop = Sor_Kodetoko And Docno = Sor_Nonrb and prdcd = sor_pluidm
                                    WHERE sor_tglclose::date BETWEEN ? AND ?
                                    ORDER BY 1,2,3";

        $selesaiproses = "SELECT        rom_kodetoko,
                                        rom_namatoko,
                                        to_char(rom_tgldokumen, 'dd-Mon-yy') rom_tgldokumen,
                                        rom_nodokumen,
                                        rom_noreferensi,
                                        to_char(rom_tglreferensi, 'dd-Mon-yy') rom_tglreferensi,
                                        rom_div,
                                        rom_dept,
                                        rom_katb,
                                        rom_prdcd,
                                        rom_nama_barang,
                                        rom_unit,
                                        rom_frac,
                                        rom_kodetag,
                                        kodetag_idm,
                                        SUM(coalesce(rom_qty,0)) as rom_qty,
                                        SUM(coalesce(rom_netto,0)) as rom_netto,
                                        rom_kodesupplier,
                                        rom_namasupplier
                                        FROM
                                        (SELECT r.rom_tgldokumen,
                                    r.rom_nodokumen,
                                    r.rom_noreferensi,
                                    r.rom_tglreferensi as rom_tglreferensi,
                                    r.rom_kodetoko,
                                    i.tko_namaomi as rom_namatoko,
                                    i.tko_kodesbu rom_kodesbu,
                                    r.rom_member,
                                    p.prd_kodedivisi AS rom_div ,
                                    p.prd_kodedepartement AS rom_dept,
                                    p.prd_kodekategoribarang AS rom_katb,
                                    r.rom_prdcd,
                                    p.prd_deskripsipanjang AS rom_nama_barang,
                                    p.prd_unit AS rom_unit,
                                    p.prd_frac AS rom_frac,
                                    coalesce(p.prd_kodetag,' ') AS rom_kodetag,
                                    coalesce(prc.prc_kodetag,' ') AS kodetag_idm,
                                    r.rom_flagbkp,
                                    r.rom_qty,
                                    r.rom_avgcost AS rom_harga_satuan,
                                    r.rom_ttlcost AS rom_netto,
                                    CASE
                                        WHEN r.rom_flagbkp = 'Y'
                                        THEN r.rom_ttlcost /10 * 11
                                        ELSE r.rom_ttlcost
                                    END AS rom_gross,
                                    s.hgb_kodesupplier AS rom_kodesupplier,
                                    s.sup_namasupplier AS rom_namasupplier
                                    FROM igrpwt.tbtr_returomi r
                                    LEFT JOIN igrpwt.tbmaster_prodmast p ON r.rom_prdcd  = p.prd_prdcd
                                    LEFT JOIN igrpwt.tbmaster_tokoigr i ON r.rom_kodetoko = i.tko_kodeomi
                                    LEFT JOIN igrpwt.tbmaster_prodcrm prc on r.rom_prdcd = prc.prc_pluigr and prc_group = 'I'
                                    LEFT JOIN (SELECT h.hgb_prdcd,
                                                h.hgb_kodesupplier,
                                                s.sup_namasupplier
                                                FROM igrpwt.tbmaster_hargabeli h
                                                LEFT JOIN igrpwt.tbmaster_supplier s ON h.hgb_kodesupplier = s.sup_kodesupplier
                                                WHERE h.hgb_tipe      ='2'
                                                AND h.hgb_recordid   IS NULL) s ON r.rom_prdcd  = s.hgb_prdcd
                                    where tko_kodesbu = 'I') rtr
                                        WHERE DATE(rom_tgldokumen) BETWEEN ? AND ?
                                        and rom_prdcd IS NOT NULL
                                        group by rom_kodetoko,rom_namatoko,rom_tgldokumen,rom_nodokumen,rom_noreferensi,rom_tglreferensi,rom_div,rom_dept,rom_katb,rom_prdcd,rom_nama_barang,rom_unit,rom_frac,rom_kodetag,kodetag_idm,rom_kodesupplier,rom_namasupplier
                                        order by rom_nodokumen";

        // Tentukan query sesuai flag
        switch ($flag) {
            case 'belumabsen':
                $result = DB::connection('pgsql')->select($belumabsen);
                break;
            case 'selesaiabsen':
                $result = DB::connection('pgsql')->select($selesaiabsen);
                break;
            case 'selesaisortasi':
                $result = DB::connection('pgsql')->select($selesaisortasi, [$tglMulai, $tglSelesai, $tglMulai, $tglSelesai]);
                break;
            case 'selesaiproses':
                $result = DB::connection('pgsql')->select($selesaiproses, [$tglMulai, $tglSelesai]);
                break;
            default:
                $result = [];
        }


        return view('retur.returidm_table', compact('result', 'flag', 'tglMulai', 'tglSelesai'));
    }
}