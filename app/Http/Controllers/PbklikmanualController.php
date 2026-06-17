<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PbklikmanualController extends Controller
{
    public function pickingmanual()
    {
        return view('klik.pickingmanual');
    }

    public function getData(Request $request)
    {
        $start = \Carbon\Carbon::createFromFormat('d-m-Y', $request->tanggal_mulai)->format('Y-m-d');
        $end = \Carbon\Carbon::createFromFormat('d-m-Y', $request->tanggal_selesai)->format('Y-m-d');
        $notrans = $request->input('notrans');

        // Base SQL
        $sql = "SELECT
                    a.obi_tgltrans,
                    a.status,
                    a.pengiriman,
                    a.tipe,
                    a.obi_notrans as notrans,
                    a.OBI_KDMEMBER,
                    a.plu_igr,
                    a.prd_deskripsipanjang,
                    a.prd_frac,
                    a.obi_qtyorder qtypcs,
                    a.obi_qtyrealisasi,
                    dpd.alamat_dpd,
                    dpd.plano_dpd,
                    toko.alamat_toko,
                    toko.plano_toko,
                    stock.st_saldoakhir as lpp,
                    CASE 
                        WHEN prd_frac = 1 THEN 
                            a.obi_qtyorder::int || ' pcs'
                        ELSE
                            CASE 
                                WHEN (a.obi_qtyorder % prd_frac) = 0 THEN
                                    (a.obi_qtyorder / prd_frac)::int || ' ctn'
                                ELSE
                                    (a.obi_qtyorder / prd_frac)::int || ' ctn ' || (a.obi_qtyorder % prd_frac)::int || ' pcs'
                            END
                    END AS orderpb
                from (
                    select
                        OBI_TGLTRANS,
                        OBI_NOPB,
                        obi.recid,
                        case
                            when obi.recid = '1' then 'PICKING'
                            when obi.recid = '2' then 'SELESAI PICKING'
                            when obi.recid = '3' then 'SELESAI SET ONGKIR, SIAP DRAFT STRUK'
                            when obi.recid = '4' then 'SIAP KONFIRMASI PEMBAYARAN'
                            when obi.recid = '5' then 'SIAP STRUK'
                            when obi.recid = '6' then 'SELESAI STRUK'
                            when obi.recid = '7' then 'SELESAI PACKING, SET ONGKIR'
                            when obi.recid like 'B%' then 'BATAL'
                            else 'SIAP SEND HH'
                        end as STATUS,
                        OBI_NOTRANS,
                        OBI_KDMEMBER,
                        CUS_NAMAMEMBER,
                        PRD_KODEDIVISI,
                        substring(OBI_PRDCD,1,6) || '0' as plu_igr,
                        PRD_DESKRIPSIPANJANG,	
                        prd_frac,
                        OBI_HARGASATUAN,
                        OBI_QTYORDER,
                        OBI_QTYREALISASI,
                        OBI_RECID,
                        OBI_PICKER,
                        OBI_PICK_DT,
                        OBI_CLOSE_DT,
                        OBI_URUT_SCAN,
                        OBI_SCAN_DT || ' ' || TO_CHAR(OBI_SCAN_DT,'hh24:mi:ss') as OBI_SCAN_DT,
                        OBI_ONPICKING,
                        obi.tgl_pb,
                        pengiriman,
                        tipe
                    from IGRPWT.TBTR_OBI_D
                    left join IGRPWT.TBMASTER_PRODMAST on OBI_PRDCD = PRD_PRDCD
                    left join (
                        select
                            OBI_RECID as recid,
                            OBI_NOPB,
                            OBI_TGLPB as tgl_pb,
                            obi_tgltrans as tgl_trans,
                            OBI_NOTRANS as no_trans,
                            OBI_KDMEMBER,
                            OBI_TTLORDER,
                            OBI_TTLPPN,
                            OBI_TTLDISKON,
                            OBI_ITEMORDER,
                            CASE 
                                WHEN obi_kdekspedisi like '%Ambil%' THEN 'AMBIL DI TOKO'
                                WHEN obi_kdekspedisi like '%Indopaket%' THEN 'IPP'
                            END as pengiriman,
                            CASE 
                                WHEN obi_shippingservice = 'N' THEN 'NEXTDAY'
                                WHEN obi_shippingservice = 'S' THEN 'SAMEDAY'
                            END as tipe
                        from IGRPWT.tbtr_obi_h
                    ) obi on OBI_TGLTRANS = obi.tgl_trans and OBI_NOTRANS = obi.no_trans
                    left join IGRPWT.tbmaster_customer on OBI_KDMEMBER = CUS_KODEMEMBER
                    where obi.recid = '1'
                    and obi_tgltrans::DATE between :start and :end
                    -- notrans filter will be added dynamically
                    order by obi_tgltrans
                ) a
                left join (
                    select
                        lks_koderak || '.' || lks_kodesubrak || '.' || lks_tiperak || '.' ||
                        lks_shelvingrak || '.' || lks_nourut as alamat_dpd,
                        lks_prdcd,
                        lks_qty as plano_dpd
                    from IGRPWT.tbmaster_lokasi
                    where substring(LKS_KODERAK from 1 for 1) in ('D')
                    and LKS_KODERAK not like '%DTAGR%'
                    and substring(LKS_TIPERAK from 1 for 1) in ('B','I','N')
                ) dpd on plu_igr = dpd.lks_prdcd
                left join (
                    select
                        lks_koderak || '.' || lks_kodesubrak || '.' || lks_tiperak || '.' ||
                        lks_shelvingrak || '.' || lks_nourut as alamat_toko,
                        lks_prdcd,
                        lks_qty as plano_toko
                    from IGRPWT.tbmaster_lokasi
                    where substring(LKS_KODERAK from 1 for 1) in ('R','O','P','C')
                    and substring(LKS_TIPERAK from 1 for 1) in ('B','I')
                    and lks_prdcd is not null
                ) toko on plu_igr = toko.lks_prdcd
                left join (
                    select st_lokasi, st_prdcd, st_saldoakhir 
                    from IGRPWT.TBMASTER_STOCK 
                    where st_lokasi='01'
                ) stock on plu_igr=stock.st_prdcd
                ORDER BY obi_tgltrans, obi_notrans";

        // Tambahkan parameter dasar
        $params = [
            'start' => $start,
            'end'   => $end,
        ];

        // Jika notrans diisi, tambahkan filter
        if (!empty($notrans)) {
            // tambahkan kondisi ke SQL
            $sql = str_replace("-- notrans filter will be added dynamically", "and obi_notrans = :notrans", $sql);
            $params['notrans'] = $notrans;
        } else {
            // hapus komentar filter
            $sql = str_replace("-- notrans filter will be added dynamically", "", $sql);
        }

        $results = DB::select($sql, $params);

        return view('klik.pickingmanual_table', [
            'data'    => $results,
            'start'   => $start,
            'end'     => $end,
            'notrans' => $notrans,
        ]);
    }
}