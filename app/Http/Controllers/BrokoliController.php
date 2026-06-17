<?php

namespace App\Http\Controllers;

use App\Exports\BpbrExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

// use App\Exports\BpbrExport;
// use Maatwebsite\Excel\Facades\Excel;

class BrokoliController extends Controller
{
    public function brokoli()
    {
        return view('monitoring.brokoli');
    }

    public function brokoli_h(Request $request)
    {
        $flag = $request->input('flag');
        $tglMulai = $request->input('tanggal_mulai');

        $tglMulai = date("Y-m-d", strtotime($tglMulai));

        $queryOmi = "SELECT  HPBI_KODETOKO,RITASE,HARI_KIRIM,
                                    TKO_NAMAOMI,
                                    HPBI_NOPB, 
                                    HPBI_NOPICKING, 
                                    HPBI_NOSJ, 
                                    HPBI_RPHVALID, 
                                    COALESCE(HPBI_JUMLAHBRONJONG,'0') BRJ_SEND,
                                    COALESCE(HPBI_JUMLAHCONTAINER,'0') CONT_SEND,
                                    COALESCE(HPBI_BRONJONGSCAN,'0') BRJ_DSPB,
                                    COALESCE(PBO_QTYREALISASI,'0') CONT_DSPB,
                                    COALESCE(JML_KARDUS,'0') KARDUS_DSPB
                            FROM IGRPWT.TBTR_HEADER_PBIDM
                            LEFT JOIN (
                                SELECT cls_toko KODE_TOKO, 
                                    cls_kode || cls_group grup, 
                                    CASE 
                                        WHEN CLS_KODE IN ('A','B') 
                                                AND CAST(CLS_GROUP AS INT) % 2 = 0 THEN 'RIT 2' 
                                        WHEN CLS_KODE IN ('A','B') 
                                                AND CAST(CLS_GROUP AS INT) % 2 = 1 THEN 'RIT 1' 
                                        ELSE 'BELUM ADA RITASE' 
                                    END ritase,    
                                    cls_jarakkirim jarak, 
                                    CASE 
                                        WHEN cls_kode = 'A' THEN 'SENIN - RABU - JUMAT' 
                                        WHEN cls_kode = 'B' THEN 'SELASA - KAMIS - SABTU' 
                                        ELSE 'TOKO BARU/TOKO TUTUP SEMENTARA' 
                                    END hari_kirim
                                FROM igrpwt.cluster_idm
                            )CLS ON HPBI_KODETOKO = KODE_TOKO
                            LEFT JOIN (
                                select * from IGRPWT.tbmaster_pbomi 
                                where pbo_create_dt::date = ? 
                                and pbo_pluigr = '1702471'
                            )as koli 
                            ON HPBI_KODETOKO||HPBI_NOPB = PBO_KODEOMI||PBO_NOPB 
                            AND HPBI_NOPICKING = PBO_NOPICKING
                            LEFT JOIN IGRPWT.TBMASTER_TOKOIGR as tko 
                            ON HPBI_KODETOKO = TKO_KODEOMI
                            LEFT JOIN (
                                SELECT IKL_KODEIDM, IKL_NOPICK, SUM(KARDUS) JML_KARDUS,
                                    SUM(CONT) JML_CONTAINER
                                FROM (
                                    SELECT
                                        IKL_KODEIDM,
                                        IKL_NOPB,
                                        IKL_NOPICK,
                                        CASE WHEN IKL_KARDUS = 'Y' THEN 1 ELSE 0 END AS KARDUS,
                                        CASE WHEN IKL_KARDUS <> 'Y' THEN 1 ELSE 0 END AS CONT
                                    FROM IGRPWT.TBTR_IDMKOLI
                                    WHERE IKL_CREATE_DT::date = ?
                                ) as ikl 
                                GROUP BY IKL_KODEIDM, IKL_NOPICK
                            ) as ikl2 
                            ON HPBI_KODETOKO = IKL_KODEIDM 
                            AND HPBI_NOPICKING = IKL_NOPICK
                            WHERE HPBI_CREATE_DT::date = ?
                            AND hpbi_kodetoko LIKE 'O%'
                            ORDER BY HPBI_NOPICKING";
        
        $queryIdm = "SELECT  HPBI_KODETOKO,RITASE,HARI_KIRIM,
                                TKO_NAMAOMI,
                                HPBI_NOPB, 
                                HPBI_NOPICKING, 
                                HPBI_NOSJ, 
                                HPBI_RPHVALID, 
                                COALESCE(HPBI_JUMLAHBRONJONG,'0') BRJ_SEND,
                                COALESCE(HPBI_JUMLAHCONTAINER,'0') CONT_SEND,
                                COALESCE(HPBI_BRONJONGSCAN,'0') BRJ_DSPB,
                                COALESCE(PBO_QTYREALISASI,'0') CONT_DSPB,
                                COALESCE(JML_KARDUS,'0') KARDUS_DSPB
                                FROM IGRPWT.TBTR_HEADER_PBIDM
                                LEFT JOIN (SELECT cls_toko KODE_TOKO, 
                                                  cls_kode || cls_group grup, 
                                                  Case 
                                                      WHEN CLS_KODE IN ('A','B') 
                                                            AND CAST(CLS_GROUP AS INT) % 2 = 0
                                                      THEN 'RIT 2' 
                                                      WHEN CLS_KODE IN ('A','B') 
                                                            AND CAST(CLS_GROUP AS INT) % 2 = 1
                                                      THEN 'RIT 1' 
                                                      ELSE 'BELUM ADA RITASE' 
                                                 END ritase,    
                                                 cls_jarakkirim jarak, 
                                                 Case 
                                                 WHEN cls_kode = 'A'
                                                    Then 'SENIN - RABU - JUMAT' 
                                                 When cls_kode = 'B'
                                                    THEN 'SELASA - KAMIS - SABTU' 
                                                 ELSE 'TOKO BARU/TOKO TUTUP SEMENTARA' 
                                                 END hari_kirim, 
                                                 cls_kode, 
                                                 cls_group, 
                                                 cls_mobil, 
                                                 cls_paketipp 
                                                FROM igrpwt.cluster_idm)CLS ON HPBI_KODETOKO = KODE_TOKO
                                LEFT JOIN (select * from IGRPWT.tbmaster_pbomi where pbo_create_dt::date = ? and pbo_pluigr = '1372801')as koli ON HPBI_KODETOKO||HPBI_NOPB = PBO_KODEOMI||PBO_NOPB AND HPBI_NOPICKING = PBO_NOPICKING
                                LEFT JOIN IGRPWT.TBMASTER_TOKOIGR as tko ON HPBI_KODETOKO = TKO_KODEOMI
                                LEFT JOIN (SELECT IKL_KODEIDM, IKL_NOPICK, SUM(KARDUS) JML_KARDUS,
                                SUM(CONT) JML_CONTAINER
                                FROM (
                                SELECT
                                IKL_KODEIDM,
                                IKL_NOPB,
                                IKL_NOPICK,
                                CASE WHEN IKL_KARDUS = 'Y' THEN 1
                                ELSE 0
                                END AS KARDUS,
                                CASE WHEN IKL_KARDUS <> 'Y' THEN 1
                                ELSE 0
                                END AS CONT
                                FROM IGRPWT.TBTR_IDMKOLI
                                WHERE IKL_CREATE_DT::date = ? ORDER BY IKL_NOPICK) as ikl 
                                group by IKL_KODEIDM, IKL_NOPICK 
                                ORDER BY IKL_NOPICK) as ikl2 ON HPBI_KODETOKO = IKL_KODEIDM AND HPBI_NOPICKING = IKL_NOPICK
                                WHERE HPBI_CREATE_DT::date = ?
                                AND hpbi_kodetoko NOT LIKE 'O%'
                                ORDER BY HPBI_NOPICKING";

        if ($flag == 'omi') {
            $query = $queryOmi;
        } elseif ($flag == 'idm') {
            $query = $queryIdm;
        }

        $results = DB::connection('pgsql')->select($query, [$tglMulai, $tglMulai, $tglMulai]);

        $totals = [
            'hpbi_rphvalid' => array_sum(array_column($results, 'hpbi_rphvalid')),
            'brj_send'    => array_sum(array_column($results, 'brj_send')),
            'cont_send'  => array_sum(array_column($results, 'cont_send')),
            'brj_dspb'    => array_sum(array_column($results, 'brj_dspb')),
            'cont_dspb'    => array_sum(array_column($results, 'cont_dspb')),
            'kardus_dspb'    => array_sum(array_column($results, 'kardus_dspb')),
        ];

        return view('monitoring.brokoli_table', compact('results','totals'));
    }
}