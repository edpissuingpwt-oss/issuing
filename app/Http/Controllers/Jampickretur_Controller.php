<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Jampickretur_Controller extends Controller
{
    public function cekjampick()
    {
        return view('retur.cekjampick');
    }

    public function getData(Request $request)
    {
        $nokoli = $request->input('nokoli');

        // TODO: isi query sesuai kebutuhan
        $query = "SELECT
                        PBO_NOKOLI NOKOLI,
                        PBO_TGLPB TANGGAL,
                        PBO_NOPB NPB,
                        PBO_NOSTRUK STRUK,
                        PBO_KODEOMI KODETOKO,
                        PBO_PLUIGR PLU,
                        PRD_DESKRIPSIPANJANG DESKRIPSI,
                        PBO_QTYORDER QTYORDER,
                        PBO_QTYREALISASI REALISASI,
                        PBO_USERUPDATECHECKER CHECKER,
                    PBO_JAMUPDATECHECKER JAM_PICKING
                    from
                        igrpwt.TBMASTER_PBOMI
                    left join igrpwt.TBMASTER_PRODMAST on
                        PBO_PLUIGR = PRD_PRDCD
                    where
                        PBO_NOKOLI = :nokoli";

        $data = DB::select($query, [
            'nokoli' => $nokoli
        ]);

        return view('retur.cekjampick_table', compact('data'));
    }
}
