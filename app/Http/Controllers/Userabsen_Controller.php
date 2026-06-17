<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Userabsen_Controller extends Controller
{
    public function cekuserabsen()
    {
        return view('retur.cekabsenretur');
    }

    public function getData(Request $request)
    {
        $kodetoko = $request->input('kodetoko');
        $nonrb = $request->input('nonrb');

        // TODO: isi query sesuai kebutuhan
        $query = "SELECT
                        sor_tglnrb as tglnrb,
                        sor_tglabsen as tglabsen,
                        sor_tglsortasi as tglsortasi,
                        sor_userabsen as user_absen,
                        sor_usersortasi as user_sortasi,
                        sor_kodetoko as kodetoko,
                        sor_nonrb as nonrb,
                        sor_prdcd as plu,
                        sor_qty_nrb as qtynrb,
                        sor_qty_fisik as qtyfisik,
                        sor_qty_bakurang as qtybakurang,
                        sor_qty_baik as qtybaik,
                        sor_qty_layakretur as qtylayakretur,
                        sor_qty_batolak as qtybatolak
                        from igrpwt.tbtr_sortasi_retur where sor_kodetoko = :kodetoko and sor_nonrb = :nonrb";

        $data = DB::select($query, [
            'kodetoko' => $kodetoko,
            'nonrb' => $nonrb
        ]);

        return view('retur.cekabsenretur_table', compact('data'));
    }
}
