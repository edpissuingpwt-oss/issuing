<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class PBMentahIDM_Controller extends Controller
{
    public function pbmentahidm()
    {
        $query = "SELECT                count(fdkcab) as toko,
                                        fdkode,
                                        prd_prdcd,
                                        prd_deskripsipanjang,
                                        prd_frac as frac,
                                        prd_unit as unit,
                                        round(sum(fdqtyb)) as qty,
                                        ROUND(SUM(fdqtyb) / prd_frac, 2) AS qty_ctn,
                                        ROUND(PRD_AVGCOST) ACOSTCTN,
                                        ROUND(PRD_AVGCOST/prd_frac) ACOSTPCS,
                                        ROUND(sum(fdqtyb))*ROUND(PRD_AVGCOST/prd_frac) TOTAL,
                                        st_saldoakhir as lpp,
                                        LKS_KODERAK || '.' || LKS_KODESUBRAK || '.' || LKS_TIPERAK || '.' || LKS_SHELVINGRAK || '.' || LKS_NOURUT AS rak,
                                        lks_qty as plano,
                                        CASE
                                                    WHEN lks_koderak IN ('D01','D02','D03','D04') THEN 'ZONA 1'
                                                    WHEN lks_koderak IN ('D05','D06','D07','D08') THEN 'ZONA 2'
                                                    WHEN lks_koderak IN ('D09','D10','D11','D12') THEN 'ZONA 3'
                                                    WHEN lks_koderak IN ('D13','D14') THEN 'ZONA 4'
                                                    WHEN lks_koderak IN ('D15','D16') THEN 'ZONA 5'
                                                    WHEN lks_koderak IN ('D17','D18') THEN 'ZONA 6'
                                                    WHEN lks_koderak IN ('D19','D20','D21','D22','D23','D24') THEN 'ZONA 7'
                                                    WHEN lks_koderak IN ('D25','D26','D27','D28','D29','D30') THEN 'ZONA 8'
                                                    WHEN lks_koderak IN ('D31','D32','D33','D34','D35','D36') THEN 'ZONA 9'
                                                    WHEN lks_koderak IN ('D37','D38','D39','D40','D41','D42') THEN 'ZONA A'
                                                    WHEN lks_koderak IN ('D43','D44','D45','D46','D47','D48') THEN 'ZONA B'
                                                    WHEN lks_koderak IN ('D49','D50','D51','D52','D53','D54') THEN 'ZONA C'
                                                    WHEN lks_koderak IN ('D55','D56') THEN 'ZONA D'
                                                    WHEN lks_koderak = 'D57' THEN 'ZONA E'
                                                    WHEN lks_koderak = 'DCONT' THEN 'ZONA Z'
                                                END AS zona
                                        from igrpwt.csv_pb_idm
                                                            left join igrpwt.tbmaster_prodcrm on fdkode = prc_pluidm
                                                            left join igrpwt.tbmaster_prodmast on prc_pluigr = prd_prdcd and prd_prdcd like '%0'
                                                            left join igrpwt.tbmaster_stock on prd_prdcd = st_prdcd and st_lokasi = '01'
                                                            left join igrpwt.tbmaster_lokasi on prd_prdcd = lks_prdcd and SUBSTR(Lks_Koderak, 1, 1) = 'D' AND Lks_Tiperak = 'B' AND Lks_Prdcd IS NOT NULL
                                                            where tgl_proses::date = current_date
                                                            group by fdkode,prd_prdcd,prd_deskripsipanjang,prd_frac,prd_unit,prd_avgcost,st_saldoakhir,lks_koderak,lks_kodesubrak,lks_tiperak,lks_shelvingrak,lks_nourut,lks_qty
                                                            order by 3";
        
        $results = DB::select($query);

        return view('master.pbmentahidm', compact('results'));
    }
}