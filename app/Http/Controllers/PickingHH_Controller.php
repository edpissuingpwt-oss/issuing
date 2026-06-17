<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PickingHH_Controller extends Controller
{
    public function pickinghh()
    {
        return view('monitoring.pickinghh');
    }

    public function pickinghh_h(Request $request)
    {
        try {
            $flag = $request->input('flag');

            if ($flag === 'zona20') {
                $query = "SELECT    count(fmkcab) as toko,
                            SUBSTR(prdcd, 1, 6) || '0' as plu,
                            desc2 deskripsi,
                            st_saldoakhir lpp,
                            sum(qtyo) qtypcs from igrpwt.hheld_idm_ora
                            left join igrpwt.tbmaster_stock on SUBSTR(prdcd, 1, 6) || '0' = st_prdcd and st_lokasi = '01'
                            left join igrpwt.tbmaster_prodmast on SUBSTR(prdcd, 1, 6) || '0' = prd_prdcd and prd_prdcd like '0%'
                            where tglupd::date = current_date
                            group by plu,kodezona,deskripsi,lpp"; // query zona 20
            } else {
                $query = "SELECT    count(ddp_kodetoko) as toko,
                            SUBSTR(ddp_prdcd, 1, 6) || '0' as plu,
                            kat_deskripsi deskripsi,
                            st_saldoakhir lpp,
                            sum(ddp_qtyorder) qtypcs from igrpwt.dcp_data_pot
                            left join igrpwt.tbmaster_stock on SUBSTR(ddp_prdcd, 1, 6) || '0' = st_prdcd and st_lokasi = '01'
                            left join igrpwt.konversi_atk on SUBSTR(ddp_prdcd, 1, 6) || '0' = kat_pluigr
                            where ddp_tglupload::date = current_date
                            group by plu,deskripsi,lpp"; // query pot
            }

            $results = DB::select($query);

            return view('monitoring.partials.pickinghh_h', compact('results', 'flag'));

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }
}