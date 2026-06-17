<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class BatokoController extends Controller
{
    public function batoko()
    {
        return view('retur.batoko');
    }

    public function getData(Request $request)
    {
        $flag = $request->input('flag');
        $tglMulai = $request->input('tanggal_mulai');
        $tglSelesai = $request->input('tanggal_selesai');

        $tglMulai = date("Y-m-d", strtotime($tglMulai));
        $tglSelesai = date("Y-m-d", strtotime($tglSelesai));

        $detail = "SELECT          A.TOKO                 AS KODE_TOKO,
                                    B.TKO_NAMAOMI          AS NAMA_TOKO,
                                    A.NO_NRB               AS NO_NRB,
                                    BTH_NODOC				 AS BTH_NODOC,
                                    A.NO_BPBR              AS NO_BPBR,
                                    C.PLU_IDM              AS PLU_IDM,
                                    C.PRD_PRDCD            AS PRD_PRDCD,
                                    C.PRD_DESKRIPSIPANJANG AS DESKRIPSI,
                                    C.PRD_UNIT || '/' || C.PRD_FRAC             AS FRAC,
                                    C.PRD_PERLAKUANBARANG AS STATUS,
                                    TO_CHAR(A.TGL_PROSES, 'DD-MON-YY')           AS TGL_PROSES,
                                    A.QTY_NRB              AS DATA_NRB,
                                    A.FISIK                AS FISIK,
                                    A.FISIK_KURANG         AS FISIK_KURANG,
                                    A.TOLAK                AS TOLAK,
                                    A.NOMINAL              AS NOMINAL
                                    FROM (SELECT ROM_KODETOKO                      AS TOKO,
                                        ROM_NOREFERENSI                         AS NO_NRB,
                                        ROM_PRDCD                               AS PLU_IGR,
                                        ROM_CREATE_DT                           AS TGL_PROSES ,
                                        (ROM_QTYREALISASI + ROM_QTYSELISIH)    AS QTY_NRB,
                                        ROM_QTYREALISASI                        AS FISIK ,
                                        ROM_QTYTLR                              AS TOLAK ,
                                        ROM_QTYSELISIH                          AS FISIK_KURANG,
                                        ROM_NODOKUMEN                           AS NO_BPBR,
                                        BTH_NODOC								AS BTH_NODOC,
                                        (ROM_HRGSATUAN * ROM_QTYTLR) * (11/10) NOMINAL
                                        FROM IGRPWT.TBTR_RETUROMI
                                        LEFT JOIN IGRPWT.TBTR_BATOKO_H 
                                        ON BTH_PBR = ROM_NODOKUMEN AND
                                           BTH_TOKO = ROM_KODETOKO AND
                                           BTH_NONRB = ROM_NOREFERENSI::BIGINT
                                        WHERE ROM_QTY           <= ROM_QTYREALISASI
                                        AND ROM_RECORDID IS NULL
                                        AND DATE(ROM_CREATE_DT) BETWEEN ? AND ?
                                        AND CAST((ROM_HRGSATUAN * ROM_QTYTLR) * (11/10) AS TEXT) NOT LIKE '0' ) A
                                    LEFT JOIN (SELECT TKO_KODEOMI, 
                                        TKO_NAMAOMI 
                                        FROM IGRPWT.TBMASTER_TOKOIGR
                                        WHERE TKO_KODESBU = 'I')B on B.TKO_KODEOMI = A.TOKO
                                    LEFT JOIN (SELECT PRD_PRDCD,
                                        PRD_PLUMCG AS PLU_IDM,
                                        PRD_DESKRIPSIPANJANG,
                                        PRD_UNIT,
                                        PRD_FRAC,
                                        prd_perlakuanbarang
                                        FROM IGRPWT.TBMASTER_PRODMAST ) C ON A.PLU_IGR      = C.PRD_PRDCD
                                        WHERE bth_nodoc IS NOT NULL
                                        AND NOMINAL > 0
                                    ORDER BY TGL_PROSES DESC";
        
        $rekap = "SELECT bth_tgldoc,
                            bth_id,
                            bth_nodoc,
                            bth_type,
                            bth_toko,
                            tko_namaomi,
                            bth_nonrb,
                            TO_CHAR(bth_tglnrb, 'DD-MON-YY')           AS bth_tglnrb,
                            bth_pbr,
                            TO_CHAR(bth_tgpbr, 'DD-MON-YY')           AS bth_tgpbr,
                            bd.jumlahplu,
                            bth_dpp,
                            bth_ppn
                            from
                            (SELECT * FROM igrpwt.TBTR_BATOKO_H)bh
                            left join
                            (select btd_id,count(btd_prdcd) as jumlahplu from igrpwt.tbtr_batoko_d group by btd_id)bd on bth_id = btd_id
                            join igrpwt.tbmaster_tokoigr on bth_toko = tko_kodeomi
                            where date(bth_tgldoc)
                            BETWEEN ? AND ? ";

        if ($flag == 'detail') {
            $query = $detail;
        } elseif ($flag == 'rekap') {
            $query = $rekap;
        }

        $result = DB::connection('pgsql')->select($query, [$tglMulai, $tglSelesai]);

        return view('retur.batoko_table', compact('result','flag'));
    }
}