<?php

namespace App\Http\Controllers;

use App\Exports\BpbrExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

// use App\Exports\BpbrExport;
// use Maatwebsite\Excel\Facades\Excel;

class RekapnpbController extends Controller
{
    public function rekapnpb()
    {
        return view('laporan.rekapnpb');
    }

    public function getData(Request $request)
    {
        $flag = $request->input('flag');
        $tglMulai = $request->input('tanggal_mulai');
        $tglSelesai = $request->input('tanggal_selesai');

        $tglMulai = date("Y-m-d", strtotime($tglMulai));
        $tglSelesai = date("Y-m-d", strtotime($tglSelesai));

        $queryNpb = "SELECT  
                            TO_CHAR(TGL_DSPB, 'DD-MM-YYYY') AS TGL_DSPB,
                            TO_CHAR(TGL_PB, 'DD-MM-YYYY') AS TGL_PB,     
                            TOKO,
                            NAMA_TOKO,
                            NO_PB,
                            NO_DSPB,
                            RPB_FLAG,
                            COUNT(NOKOLI) AS QTY_KOLI,
                            SUM(RP_NILAI) AS RP_NILAI,
                            SUM(RP_PPN) AS RP_PPN,
                            SUM(TOTAL) AS TOTAL,
                            TIPEDSPB
                        FROM (
                            SELECT DISTINCT 
                                RPB_NOKOLI AS NOKOLI,
                                RPB_IDSURATJALAN AS NO_DSPB,
                                RPB_CREATE_DT AS TGL_DSPB,
                                RPB_TGLDOKUMEN AS TGL_PB,
                                RPB_KODEOMI AS TOKO,
                                TKO_NAMAOMI AS NAMA_TOKO,
                                RPB_NODOKUMEN AS NO_PB,
                                RPB_FLAG,
                                ROUND(SUM(RPB_TTLNILAI)) AS RP_NILAI,
                                ROUND(SUM(RPB_TTLPPN)) AS RP_PPN,
                                ROUND(SUM(RPB_TTLNILAI) + SUM(RPB_TTLPPN)) AS TOTAL,
                                CASE 
                                    WHEN RPB_NOKOLI LIKE '0C%' THEN '0C'
                                    WHEN RPB_NOKOLI LIKE '07%' THEN 'POT'
                                    ELSE 'DRY'
                                END AS TIPEDSPB
                            FROM igrpwt.TBTR_REALPB 
                            JOIN igrpwt.TBMASTER_TOKOIGR ON RPB_KODEOMI = TKO_KODEOMI
                            GROUP BY 
                                RPB_NOKOLI, 
                                RPB_IDSURATJALAN, 
                                RPB_CREATE_DT, 
                                RPB_TGLDOKUMEN,
                                RPB_FLAG,
                                RPB_KODEOMI,
                                TKO_NAMAOMI,
                                RPB_NODOKUMEN
                        ) RPB
                            -- WHERE TIPEDSPB = 'POT'              ---- NPT
                            WHERE TIPEDSPB <> 'POT'             ---- NPB
                            AND TGL_DSPB::date between ? AND ?  ---- GANTI TANGGAL / BULAN
                        GROUP BY 
                            TGL_DSPB,
                            TGL_PB,
                            TOKO,
                            NAMA_TOKO,
                            NO_DSPB,
                            RPB_FLAG,
                            NO_PB,
                            TIPEDSPB
                        ORDER BY NO_DSPB";
        
        $queryNpt = "SELECT  
                            TO_CHAR(TGL_DSPB, 'DD-MM-YYYY') AS TGL_DSPB,
                            TO_CHAR(TGL_PB, 'DD-MM-YYYY') AS TGL_PB,     
                            TOKO,
                            NAMA_TOKO,
                            NO_PB,
                            NO_DSPB,
                            RPB_FLAG,
                            COUNT(NOKOLI) AS QTY_KOLI,
                            SUM(RP_NILAI) AS RP_NILAI,
                            SUM(RP_PPN) AS RP_PPN,
                            SUM(TOTAL) AS TOTAL,
                            TIPEDSPB
                        FROM (
                            SELECT DISTINCT 
                                RPB_NOKOLI AS NOKOLI,
                                RPB_IDSURATJALAN AS NO_DSPB,
                                RPB_CREATE_DT AS TGL_DSPB,
                                RPB_TGLDOKUMEN AS TGL_PB,
                                RPB_KODEOMI AS TOKO,
                                TKO_NAMAOMI AS NAMA_TOKO,
                                RPB_NODOKUMEN AS NO_PB,
                                RPB_FLAG,
                                ROUND(SUM(RPB_TTLNILAI)) AS RP_NILAI,
                                ROUND(SUM(RPB_TTLPPN)) AS RP_PPN,
                                ROUND(SUM(RPB_TTLNILAI) + SUM(RPB_TTLPPN)) AS TOTAL,
                                CASE 
                                    WHEN RPB_NOKOLI LIKE '0C%' THEN '0C'
                                    WHEN RPB_NOKOLI LIKE '07%' THEN 'POT'
                                    ELSE 'DRY'
                                END AS TIPEDSPB
                            FROM igrpwt.TBTR_REALPB 
                            JOIN igrpwt.TBMASTER_TOKOIGR ON RPB_KODEOMI = TKO_KODEOMI
                            GROUP BY 
                                RPB_NOKOLI, 
                                RPB_IDSURATJALAN, 
                                RPB_CREATE_DT, 
                                RPB_TGLDOKUMEN,
                                RPB_FLAG,
                                RPB_KODEOMI,
                                TKO_NAMAOMI,
                                RPB_NODOKUMEN
                        ) RPB
                            WHERE TIPEDSPB = 'POT'              ---- NPT
                        --    WHERE TIPEDSPB <> 'POT'             ---- NPB
                            AND TGL_DSPB::date between ? and ? ---- GANTI TANGGAL / BULAN
                        --    AND TOKO = 'TH4T'
                        GROUP BY 
                            TGL_DSPB,
                            TGL_PB,
                            TOKO,
                            NAMA_TOKO,
                            NO_DSPB,
                            RPB_FLAG,
                            NO_PB,
                            TIPEDSPB
                        ORDER BY NO_DSPB";

        if ($flag == 'npb') {
            $query = $queryNpb;
        } elseif ($flag == 'npt') {
            $query = $queryNpt;
        }

        $result = DB::connection('pgsql')->select($query, [$tglMulai,$tglSelesai]);

        return view('laporan.rekapnpb_table', compact('result'));
    }

    // public function export(Request $request)
    // {
    //     $flag = $request->input('flag');
    //     $tglMulai = date("Y-m-d", strtotime($request->input('tanggal_mulai')));
    //     $tglSelesai = date("Y-m-d", strtotime($request->input('tanggal_selesai')));

    //     // Format nama file pakai tanggal mulai & tanggal selesai
    //     $fileName = "REKAP_" . strtoupper($flag) . "_{$tglMulai}_-_" . $tglSelesai . ".xlsx";

    //     return Excel::download(
    //         new BpbrExport($flag, $tglMulai, $tglSelesai),
    //         $fileName
    //     );
    // }
    
}