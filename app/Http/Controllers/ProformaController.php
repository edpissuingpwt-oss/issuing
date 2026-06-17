<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProformaController extends Controller
{
    public function proforma()
    {
        return view('retur.proforma');
    }

    public function getData(Request $request)
    {
        $start = \Carbon\Carbon::createFromFormat('d-m-Y', $request->tanggal_mulai)->format('Y-m-d');
        $end = \Carbon\Carbon::createFromFormat('d-m-Y', $request->tanggal_selesai)->format('Y-m-d');

        $sql = "SELECT 
                        CASE
                        WHEN w.istype = '01'
                        THEN 'P'
                        ELSE 'F'
                        END    AS istype,
                        to_char(w.TGL1, 'dd-Mon-yy') tanggal,
                        to_char(w.wt_create_dt, 'dd-Mon-yy') TGL_WT,
                        to_char(w.Wt_Modify_Dt, 'dd-Mon-yy') Tgl_Proses,
                        Case
                        When H.Bth_Pbr Is Null Then Bb.Bri_Id
                        Else H.Bth_Pbr
                        end as no_bpbr,
                        w.shop as kode_toko,
                        t.tko_namaomi as nama_toko,
                        w.docno as DOCNO,
                        w.prdcd AS pluidm,
                        p.prd_prdcd AS PLUIGR,
                        p.prd_deskripsipanjang AS DESKRIPSI,
                        w.qty AS QTY,
                        w.price AS PRICE,
                        w.gross AS gross,
                        w.ppn AS PPN,
                        w.gross + ppn AS netto,
                        l.LKS_KODERAK||'.'|| LKS_KODESUBRAK||'.'|| LKS_TIPERAK||'.'|| LKS_SHELVINGRAK||'.'|| LKS_NOURUT AS lokasi,
                        case when w.shop||w.docno||p.prd_prdcd||w.qty = h.bth_toko||h.bth_nonrb||h.btd_prdcd||h.btd_qty then 'FISIK TERPENUHI'
                        when w.shop||w.docno||p.prd_prdcd||w.qty = t.tko_kodeomi||bb.bri_nrb||bb.bri_prdcd||bri_qty then 'FISIK KURANG'
                        else 'FISIK SEBAGIAN TERPENUHI SEBAGIAN KURANG'
                        end as BEBAN,
                        w.gross + ppn AS NILAI_BEBAN
                        from
                        (Select * From igrpwt.Tbtr_Wt_Interface Where date(wt_modify_dt) BETWEEN :start AND :end and recid = 'P') w
                        Left Join 
                        (Select * From igrpwt.Tbmaster_Prodmast Where Prd_Prdcd Like '%0') P On Prd_Plumcg = Prdcd
                        Left Join
                        (Select * From igrpwt.Tbmaster_Lokasi Where Lpad(Lks_Koderak,1)='D' And Lks_Tiperak<>'S') L On Prd_Prdcd = Lks_Prdcd
                        Left Join
                        (Select * From igrpwt.Tbmaster_Tokoigr) T On Shop = Tko_Kodeomi
                        Left Join
                        (Select * From igrpwt.Tbtr_Batoko_H inner Join igrpwt.Tbtr_Batoko_D On Bth_Id = Btd_Id where bth_type = 'P') H On Shop || Docno || Prd_prdcd = Bth_Toko || Bth_Nonrb || Btd_Prdcd
                        Left Join
                        (SELECT * FROM igrpwt.TBTR_BEBANRETURIGR) bb on prd_prdcd || docno || tko_kodecustomer = bri_prdcd || bri_nrb || bri_member
                        Where Istype = '01' order by w.wt_modify_dt";

        $results = DB::select($sql, [
            'start' => $start,
            'end' => $end
        ]);

        return view('retur.proforma_table', [
            'data' => $results,
            'start' => $start,
            'end' => $end
        ]);
    }
}