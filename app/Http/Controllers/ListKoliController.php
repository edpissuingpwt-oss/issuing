<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ListKoliController extends Controller
{
    public function index()
    {
        return view('listkoli.index');
    }

    public function getTableData(Request $request)
    {
        $tanggal = $request->input('tanggal');

        // Use Laravel's Query Builder or raw SQL for the query
        $query = "SELECT 
            hpbi_create_dt,
            tko_namasbu,
            hpbi_nopb,
            hpbi_kodetoko,
            tko_namaomi,
            hpbi_nosj,
            hpbi_nopicking
            FROM IGRPWT.TBTR_HEADER_PBIDM 
            LEFT JOIN IGRPWT.Tbmaster_Tokoigr AS tko 
                ON Hpbi_Kodetoko = Tko_Kodeomi
            LEFT JOIN (
                SELECT 
                    PBO_NOPB,
                    PBO_NOPICKING,
                    PBO_NOSJ
                FROM IGRPWT.TBMASTER_PBOMI 
                FULL JOIN (
                    SELECT * FROM IGRPWT.TBMASTER_PRODMAST 
                    LEFT JOIN (SELECT PRC_PLUIGR, PRC_MINORDER FROM IGRPWT.TBMASTER_PRODCRM WHERE PRC_GROUP = 'O') AS prcO 
                        ON PRD_PRDCD = PRC_PLUIGR 
                    WHERE PRD_PRDCD LIKE '%0'
                ) AS prd ON SUBSTR(PBO_PLUIGR, 0, 6)||'0' = PRD_PRDCD 
                WHERE TO_CHAR(pbo_create_dt,'DD-MM-YYYY') = ?
                AND PBO_RECORDID >= '2' 
                GROUP BY PBO_NOPB, PBO_NOPICKING, PBO_NOSJ
            ) AS nopb ON HPBI_NOPB = PBO_NOPB AND HPBI_NOPICKING = PBO_NOPICKING
            WHERE TO_CHAR(HPBI_CREATE_DT,'DD-MM-YYYY') = ?
            AND HPBI_KODETOKO IN (SELECT TKO_KODEOMI FROM IGRPWT.TBMASTER_TOKOIGR) 
            ORDER BY HPBI_NOPICKING, HPBI_KODETOKO";

        $hasil = DB::connection('pgsql')->select($query, [$tanggal, $tanggal]);

        return view('listkoli.t_listkoli', ['hasil' => $hasil]);
    }

    public function getItemsData(Request $request)
    {
        $stt = $request->input('stt');
        $kdtoko = $request->input('kdtoko');
        $noPick = $request->input('noPick');
        
        $filter = '';
        if ($stt === 'INDOMARET') {
            $filter = "AND PBO_QTYORDER >= PRD_FRAC";
        } elseif ($stt === 'OMI') {
            $filter = "AND PBO_QTYORDER >= PRC_MINORDER";
        }

        $query = "SELECT NO_KOLI, kode_toko, no_pick,
                        CASE    WHEN zona LIKE 'DZ4%' THEN 'ZONA 4'
                                WHEN ZONA LIKE 'DZ5%' THEN 'ZONA 5'
                                WHEN ZONA LIKE 'DZ6%' THEN 'ZONA 6'
                                WHEN ZONA LIKE 'DZ7%' THEN 'ZONA 7'
                                WHEN ZONA LIKE 'DZ8%' THEN 'ZONA 8'
                                WHEN ZONA LIKE 'DZ9%' THEN 'ZONA 9'
                                WHEN ZONA LIKE 'DZA%' THEN 'ZONA A'
                                WHEN ZONA LIKE 'DZB%' THEN 'ZONA B'
                                WHEN ZONA LIKE 'DZC%' THEN 'ZONA C'
                                WHEN ZONA LIKE 'DZD%' THEN 'ZONA D'
                                WHEN ZONA LIKE 'DZE%' THEN 'ZONA E'
                                WHEN ZONA LIKE '%ONA 1%' THEN 'ZONA 1' 
                                WHEN ZONA LIKE '%ONA 2' THEN 'ZONA 2'
                                WHEN ZONA LIKE '%ONA 3%' THEN 'ZONA 3'
                                WHEN ZONA LIKE '%ONA 20' THEN 'ZONA 20'     
                        ELSE 'ZONA NON LOKASI'
                        END AS ZONA, JML_PLU
                        FROM (
                            SELECT 
                                pbo_nokoli AS no_koli,
                                pbo_kodeomi AS kode_toko,
                                ikl_nopick AS no_pick,
                                ikl_zona AS zona,
                                COUNT(pbo_pluigr) AS jml_plu
                            FROM IGRPWT.TBMASTER_PBOMI
                            LEFT JOIN IGRPWT.tbtr_idmkoli ikl 
                                ON pbo_nokoli = ikl_nokoli AND pbo_kodeomi = ikl_kodeidm AND pbo_nopicking = ikl_nopick
                            WHERE 
                                PBO_KODEOMI = ? AND
                                PBO_NOPICKING = ? AND
                                PBO_RECORDID::integer >= 4 AND 
                                PBO_QTYREALISASI <> 0
                                
                            GROUP BY pbo_nokoli, pbo_kodeomi, ikl_nopick, ikl_zona
                        ) AS data
                        ORDER BY ZONA, NO_KOLI";

        $hasil = DB::connection('pgsql')->select($query, [$kdtoko, $noPick]);

        // Pass all the data to the view
        $data = $request->all();
        $data['allData'] = $hasil;

        return view('listkoli.p_listkoli', $data);
    }

    public function cetak(Request $request)
    {
        // Ambil data kunci dari URL
        $tanggal = $request->input('tanggal');
        $noPB    = $request->input('nopb');
        $noSJ    = $request->input('noSJ');
        $noPick  = $request->input('noPick');
        $kdtoko  = $request->input('kdtoko');
        $nmtoko  = $request->input('nmtoko');

        // Panggil metode getItemsData untuk mengambil data yang sama
        $allData = $this->getItemsData($request)->getData()['allData'];

        // Pass all the data to the view
        return view('listkoli.cetaklistkoli', compact('tanggal', 'noPB', 'noSJ', 'noPick', 'kdtoko', 'nmtoko', 'allData'));
    }

}