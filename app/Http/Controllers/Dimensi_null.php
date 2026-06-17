<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class Dimensi_null extends Controller
{
    public function dimensinull()
    {
        $query = "select distinct dn_plu as dn_plu,
                            dn_divisi,
                            dn_departement,
                            dn_kategori,
                            dn_des,
                            dn_frac,
                            dn_flag_jual,
                            dn_tag,
                            dn_tinggi,
                            dn_lebar,
                            dn_panjang from (
                            select * from igrpwt.tbhistory_dimensi_null)aa";
        
        $results = DB::select($query);

        return view('laporan.dimensinull', compact('results'));
    }
}