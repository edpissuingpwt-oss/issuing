<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OutstandingController extends Controller
{
    public function outstandingretur()
    {
        return view('retur.outstanding');
    }

    public function getData(Request $request)
    {
        $flag = $request->input('flag');

        $reguler = "SELECT  distinct w.shop,
                                w.docno,
                                SUBSTRING(KETERANGAN, 10) AS typer,
                                to_char(w.TGL1, 'dd-Mon-yy') tanggal1,
                                w.TGL1 AS tanggal,
                                t.tko_namaomi,
                                w.prdcd AS pluidm,
                                p.prd_prdcd,
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
                                left join (select * from igrpwt.tbmaster_lokasi where lpad(LKS_KODERAK,1)='D' and LKS_TIPERAK<>'S' and lks_koderak not in ('DTAGR','DHDH')) l on p.PRD_PRDCD   = l.LKS_PRDCD
                                left join igrpwt.tbmaster_tokoigr t on w.shop   = t.tko_kodeomi
                                WHERE w.recid  <> 'P' AND keterangan like '%REGULER'
                                ORDER BY w.tgl1,
                                    w.shop,
                                    w.docno";
        
        $proforma = "SELECT  distinct w.shop,
                                w.docno,
                                SUBSTRING(KETERANGAN, 10) AS typer,
                                to_char(w.TGL1, 'dd-Mon-yy') tanggal1,
                                w.TGL1 AS tanggal,
                                t.tko_namaomi,
                                w.prdcd AS pluidm,
                                p.prd_prdcd,
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
                                left join (select * from igrpwt.tbmaster_lokasi where lpad(LKS_KODERAK,1)='D' and LKS_TIPERAK<>'S' and lks_koderak not in ('DTAGR','DHDH')) l on p.PRD_PRDCD   = l.LKS_PRDCD
                                left join igrpwt.tbmaster_tokoigr t on w.shop   = t.tko_kodeomi
                                WHERE w.recid  <> 'P' AND istype = '01'
                                ORDER BY w.tgl1,
                                    w.shop,
                                    w.docno";

        $other = "SELECT  distinct w.shop,
                                w.docno,
                                SUBSTRING(KETERANGAN, 10) AS typer,
                                to_char(w.TGL1, 'dd-Mon-yy') tanggal1,
                                w.TGL1 AS tanggal,
                                t.tko_namaomi,
                                w.prdcd AS pluidm,
                                p.prd_prdcd,
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
                                left join (select * from igrpwt.tbmaster_lokasi where lpad(LKS_KODERAK,1)='D' and LKS_TIPERAK<>'S' and lks_koderak not in ('DTAGR','DHDH')) l on p.PRD_PRDCD   = l.LKS_PRDCD
                                left join igrpwt.tbmaster_tokoigr t on w.shop   = t.tko_kodeomi
                                WHERE w.recid  <> 'P' AND keterangan not like '%REGULER' AND istype <> '01'
                                ORDER BY w.tgl1,
                                    w.shop,
                                    w.docno";

        // Tentukan query sesuai flag
        switch ($flag) {
            case 'reguler':
                $result = DB::connection('pgsql')->select($reguler);
                break;
            case 'proforma':
                $result = DB::connection('pgsql')->select($proforma);
                break;
            case 'other':
                $result = DB::connection('pgsql')->select($other);
                break;
            default:
                $result = [];
        }


        return view('retur.outstanding_table', compact('result', 'flag'));
    }
}