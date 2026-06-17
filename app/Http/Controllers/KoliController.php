<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\View;

class KoliController extends Controller
{
    public function koli()
    {
        return view('monitoring.koli');
    }

    public function getData(Request $request)
    {
        $flag = $request->input('flag');
        $noPick = $request->input('noPick');
        $noKoli = $request->input('noKoli');

        $barcodekoli = "SELECT
                                pico_tglpick as TGL,
                                pico_recordid as RECID,
                                substr(pico_containerzona, 5,7) as ZONA,
                                pico_kodetoko as KODE_TOKO,
                                pico_namatoko as NAMA_TOKO,
                                pico_barcodekoli as BARCODE
                                from igrpwt.picking_container where pico_nopick = ?";

        $isikoli = "SELECT
                            PBO_CREATE_DT AS TANGGAL,
                            PBO_RECORDID AS RECID,
                            PBO_NOSJ,
                            PBO_NOKOLI AS NO_KOLI,
                            PBO_NOPICKING AS NO_PICK,
                            PBO_NOURUT,
                            PBO_KODEMEMBER AS KODE_MEMBER,
                            PBO_KODEOMI AS KODE_TOKO,
                            PBO_PLUIGR,
                            DESKRIPSI,
                            FRAC,
                            UNIT,
                            PBO_QTYREALISASI,
                            pbo_ttlnilai as nilaireal,
                            pbo_ttlppn as ppn,
                            case when pbo_kodesbu = 'O' and prd_flagbkp1 = 'Y' and prd_flagbkp2 <> 'Y' then '0'
                            else pbo_ttlppn end as ppnreal,
                            pbo_ttlnilai 
                            + (CASE 
                                WHEN prd_flagbkp1 = 'Y' AND prd_flagbkp2 <> 'Y' THEN 0
                                ELSE pbo_ttlppn 
                            END) AS TOTAL
                        FROM
                        (
                            SELECT * FROM IGRPWT.TBMASTER_PBOMI
                        ) AS PBOMI
                        FULL JOIN
                        (
                            SELECT 
                                PRD_PRDCD AS PLU, 
                                PRD_DESKRIPSIPANJANG AS DESKRIPSI,
                                PRD_FRAC AS FRAC,
                                PRD_UNIT AS UNIT,
                                PRD_AVGCOST AS ACOST,
                                prd_flagbkp1,
                                prd_flagbkp2
                            FROM 
                                IGRPWT.TBMASTER_PRODMAST
                        ) AS PRODMAST
                        ON PBOMI.PBO_PLUIGR = PRODMAST.PLU
                        WHERE 
                            PBOMI.PBO_NOPICKING = ?
                            AND PBOMI.PBO_NOKOLI = ?";


        // Tentukan query sesuai flag
        switch ($flag) {
            case 'barcodekoli':
                $result = DB::connection('pgsql')->select($barcodekoli, [$noPick]);
                break;
            case 'isikoli':
                $result = DB::connection('pgsql')->select($isikoli, [$noPick, $noKoli]);
                break;
            default:
                $result = [];
        }


        return view('monitoring.koli_table', compact('result', 'flag', 'noPick', 'noKoli'));
    }
}