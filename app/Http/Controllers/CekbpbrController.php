<?php

namespace App\Http\Controllers;

use App\Exports\BpbrExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

// use App\Exports\BpbrExport;
// use Maatwebsite\Excel\Facades\Excel;

class CekbpbrController extends Controller
{
    public function index()
    {
        return view('cekbpbr.index');
    }

    public function getData(Request $request)
    {
        $flag = $request->input('flag');
        $tglMulai = $request->input('tanggal_mulai');
        $tglSelesai = $request->input('tanggal_selesai');

        $tglMulai = date("Y-m-d", strtotime($tglMulai));
        $tglSelesai = date("Y-m-d", strtotime($tglSelesai));

        $queryOmi = "SELECT NOBPBR,
                            to_char(TANGGAL, 'dd-Mon-yy') TANGGAL,
                            '' as tipe,
                            flagtk,
                            KODETOKO,
                            NONRB,
                            PLU,
                            DESKRIPSI,
                            FISIK,
                            BAIK,
                            TERIMA,
                            KURANG,
                            TOLAK,
                            SB,
                            TAGIDM,
                            TAGOMI,
                            supplier
                            FROM (
                            select rom_nodokumen as nobpbr,
                            rom_tgldokumen as tanggal,
                            flagtk,
                            rom_kodetoko as kodetoko,
                            rom_noreferensi as nonrb,
                            rom_prdcd as plu,
                            deskripsi,
                            rom_qty+rom_qtytlr as qtynrb,
                            rom_qtyrealisasi as fisik,
                            rom_qtyselisih as kurang,
                            rom_qtymlj as baik,
                            rom_qtytlj as terima,
                            rom_qtytlr-rom_qtyselisih as tolak,
                            SB,
                            TAG_IDM as TAGIDM,
                            TAG_OMI as TAGOMI,
                            kodesp,
                            supplier
                            from IGRPWT.tbtr_returomi
                            LEFT JOIN
                            (SELECT PRD_PRDCD,PRD_DESKRIPSIPANJANG DESKRIPSI FROM IGRPWT.TBMASTER_PRODMAST WHERE PRD_PRDCD LIKE '%0') PRD ON ROM_PRDCD = PRD_PRDCD 
                            LEFT JOIN
                            (SELECT HGB_PRDCD,HGB_STATUSBARANG as sb,hgb_kodesupplier as kodesp FROM IGRPWT.TBMASTER_HARGABELI)HGB ON ROM_PRDCD = HGB_PRDCD
                            LEFT JOIN
                            (select PRC_PLUIGR AS PLUI,prc_kodetag TAG_IDM from IGRPWT.tbmaster_prodcrm where prc_group = 'I' and prc_pluigr is not null) prci ON ROM_PRDCD = PLUI
                            LEFT JOIN
                            (select PRC_PLUIGR AS PLUO,prc_kodetag TAG_OMI from IGRPWT.tbmaster_prodcrm where prc_group = 'O' and prc_pluigr is not null) prco ON ROM_PRDCD = PLUO
                            left join
                            (select sup_kodesupplier as kodesp2, sup_namasupplier as supplier  from IGRPWT.tbmaster_supplier ) sp on kodesp = kodesp2
                            LEFT JOIN
                            (SELECT TKO_KODEOMI,TKO_KODESBU flagtk FROM IGRPWT.TBMASTER_TOKOIGR)flagtk ON rom_kodetoko = tko_kodeomi
                            )
                            retur
                            where tanggal::date BETWEEN ? AND ?
                            and flagtk = 'O'
                            order by 2,1";
        
        $queryIdm = "SELECT NOBPBR,
                            to_char(TANGGAL, 'dd-Mon-yy') TANGGAL,
                            wt.typer tipe,
                            flagtk,
                            KODETOKO,
                            NONRB,
                            PLU,
                            DESKRIPSI,
                            FISIK,
                            BAIK,
                            TERIMA,
                            KURANG,
                            TOLAK,
                            SB,
                            TAGIDM,
                            TAGOMI,
                            supplier
                            FROM (
                            select rom_nodokumen as nobpbr,
                            rom_tgldokumen as tanggal,
                            flagtk,
                            rom_kodetoko as kodetoko,
                            rom_noreferensi as nonrb,
                            rom_prdcd as plu,
                            deskripsi,
                            rom_qty+rom_qtytlr as qtynrb,
                            rom_qtyrealisasi as fisik,
                            rom_qtyselisih as kurang,
                            rom_qtymlj as baik,
                            rom_qtytlj as terima,
                            rom_qtytlr-rom_qtyselisih as tolak,
                            SB,
                            TAG_IDM as TAGIDM,
                            TAG_OMI as TAGOMI,
                            kodesp,
                            supplier
                            from IGRPWT.tbtr_returomi
                            LEFT JOIN
                            (SELECT PRD_PRDCD,PRD_DESKRIPSIPANJANG DESKRIPSI FROM IGRPWT.TBMASTER_PRODMAST WHERE PRD_PRDCD LIKE '%0') PRD ON ROM_PRDCD = PRD_PRDCD 
                            LEFT JOIN
                            (SELECT HGB_PRDCD,HGB_STATUSBARANG as sb,hgb_kodesupplier as kodesp FROM IGRPWT.TBMASTER_HARGABELI)HGB ON ROM_PRDCD = HGB_PRDCD
                            LEFT JOIN
                            (select PRC_PLUIGR AS PLUI,prc_kodetag TAG_IDM from IGRPWT.tbmaster_prodcrm where prc_group = 'I' and prc_pluigr is not null) prci ON ROM_PRDCD = PLUI
                            LEFT JOIN
                            (select PRC_PLUIGR AS PLUO,prc_kodetag TAG_OMI from IGRPWT.tbmaster_prodcrm where prc_group = 'O' and prc_pluigr is not null) prco ON ROM_PRDCD = PLUO
                            left join
                            (select sup_kodesupplier as kodesp2, sup_namasupplier as supplier  from IGRPWT.tbmaster_supplier ) sp on kodesp = kodesp2
                            LEFT JOIN
                            (SELECT TKO_KODEOMI,TKO_KODESBU flagtk FROM IGRPWT.TBMASTER_TOKOIGR)flagtk ON rom_kodetoko = tko_kodeomi
                            )
                            retur
                            LEFT JOIN
                            (SELECT  distinct w.shop as kodetoko1,
                                w.docno as docno,
                                SUBSTRING(KETERANGAN, 10) AS typer,
                                to_char(w.TGL1, 'dd-Mon-yy') tanggal11,
                                w.TGL1 AS tanggal1,
                                t.tko_namaomi,
                                w.prdcd AS pluidm,
                                p.prd_prdcd as pluigr,
                                p.prd_deskripsipanjang,
                                w.qty,
                                w.price,
                                w.gross AS netto,
                                w.ppn,
                                w.gross + ppn AS gross,
                                l.LKS_KODERAK||'.'|| LKS_KODESUBRAK||'.'|| LKS_TIPERAK||'.'|| LKS_SHELVINGRAK||'.'|| LKS_NOURUT lokasi,
                                CASE 
                                WHEN W.RECID = 'N' THEN 'BELUM ABSEN'
                                WHEN W.RECID = 'A' THEN 'SUDAH ABSEN'
                                WHEN W.RECID = 'S' THEN 'SUDAH SORTASI'
                                WHEN W.RECID = 'P' THEN 'SELESAI PROSES'
                                END AS STATUS
                                FROM igrpwt.tbtr_wt_interface w
                                left join (SELECT * FROM igrpwt.tbmaster_prodmast WHERE prd_prdcd LIKE '%0') p on w.prdcd = p.prd_plumcg
                                left join (select * from igrpwt.tbmaster_lokasi where lpad(LKS_KODERAK,1)='D' and LKS_TIPERAK<>'S' and lks_koderak not in ('DTAGR','DHDH','HDH')) l on p.PRD_PRDCD   = l.LKS_PRDCD
                                left join igrpwt.tbmaster_tokoigr t on w.shop   = t.tko_kodeomi
                                WHERE w.recid  = 'P'
                                ORDER BY w.tgl1,
                                    w.shop,
                                    w.docno) WT ON retur.kodetoko||retur.nonrb||retur.plu = wt.kodetoko1||wt.docno||wt.pluigr
                            where tanggal::date BETWEEN ? AND ?
                            and flagtk = 'I'
                            order by 2,1";

        if ($flag == 'omi') {
            $query = $queryOmi;
        } elseif ($flag == 'idm') {
            $query = $queryIdm;
        }

        $result = DB::connection('pgsql')->select($query, [$tglMulai, $tglSelesai]);

        return view('cekbpbr.bpbr_table', compact('result'));
    }

    public function export(Request $request)
    {
        $flag = $request->input('flag');
        $tglMulai = date("Y-m-d", strtotime($request->input('tanggal_mulai')));
        $tglSelesai = date("Y-m-d", strtotime($request->input('tanggal_selesai')));

        // Format nama file pakai tanggal mulai & tanggal selesai
        $fileName = "LAPORAN_BPBR_" . strtoupper($flag) . "_{$tglMulai}_-_" . $tglSelesai . ".xlsx";

        return Excel::download(
            new BpbrExport($flag, $tglMulai, $tglSelesai),
            $fileName
        );
    }
    
}