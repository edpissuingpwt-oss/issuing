<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LppnolController extends Controller
{
    public function lppnol(Request $request)
    {

        $query = "SELECT LOKASI,PLU,DESK,UNIT,FRAC,CASE                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYYYYYY' THEN 'NAS-IGR+IDM+OMI+MR.BRD+K.IGR+DEPO'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYYYYYN' THEN 'NAS-IGR+IDM+OMI+MR.BRD+K.IGR'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYYYYNN' THEN 'NAS-IGR+IDM+OMI+MR.BRD'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYYYNYY' THEN 'NAS-IGR+IDM+OMI+K.IGR+DEPO'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYYYNYN' THEN 'NAS-IGR+IDM+OMI+K.IGR'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYYYNNY' THEN 'NAS-IGR+IDM+OMI+DEPO'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYYYNNN' THEN 'NAS-IGR+IDM+OMI'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYYNYYY' THEN 'NAS-IGR+IDM+MR.BRD+K.IGR+DEPO'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYYNNYY' THEN 'NAS-IGR+IDM+K.IGR+DEPO'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYYNNYN' THEN 'NAS-IGR+IDM+K.IGR'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYYNNNY' THEN 'NAS-IGR+IDM+DEPO'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYYNNNN' THEN 'NAS-IGR+IDM'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYNYNYY' THEN 'NAS-IGR+OMI+K.IGR+DEPO'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYNYNYN' THEN 'NAS-IGR+OMI+K.IGR'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYNYNNY' THEN 'NAS-IGR+OMI+DEPO'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYNYNNN' THEN 'NAS-IGR+OMI'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYNNYYN' THEN 'NAS-IGR+MR.BRD+K.IGR'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYNNYNN' THEN 'NAS-IGR+MR.BRD'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYNNNYY' THEN 'NAS-IGR+K.IGR+DEPO'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYNNNYN' THEN 'NAS-IGR+K.IGR'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYNNNNY' THEN 'NAS-IGR+DEPO'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYNNNNN' THEN 'NAS-IGR'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YNYYNYY' THEN 'NAS-IDM+OMI+K.IGR+DEPO'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YNYYNYN' THEN 'NAS-IDM+OMI+K.IGR'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YNYYNNY' THEN 'NAS-IDM+OMI+DEPO'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YNYYNNN' THEN 'NAS-IDM+OMI'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YNYNNYY' THEN 'NAS-IDM+K.IGR+DEPO'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YNYNNYN' THEN 'NAS-IDM+K.IGR'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YNYNNNY' THEN 'NAS-IDM+DEPO'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YNYNNNN' THEN 'NAS-IDM'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YNNYYNN' THEN 'NAS-OMI+MR.BRD'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YNNYNYN' THEN 'NAS-OMI+K.IGR'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YNNYNNN' THEN 'NAS-OMI'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YNNNYNN' THEN 'NAS-MR.BRD'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YNNNNYN' THEN 'NAS-K.IGR'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YNNNNNN' THEN 'NAS'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYYYNYY' THEN 'IGR+IDM+OMI+K.IGR+DEPO'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYYYNYN' THEN 'IGR+IDM+OMI+K.IGR'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYYYNNY' THEN 'IGR+IDM+OMI+DEPO'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYYYNNN' THEN 'IGR+IDM+OMI'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYYNNYY' THEN 'IGR+IDM+K.IGR+DEPO'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYYNNYN' THEN 'IGR+IDM+K.IGR'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYYNNNY' THEN 'IGR+IDM+DEPO'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYYNNNN' THEN 'IGR+IDM'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYNYNYY' THEN 'IGR+OMI+K.IGR+DEPO'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYNYNYN' THEN 'IGR+OMI+K.IGR'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYNYNNN' THEN 'IGR+OMI'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYNNYYN' THEN 'IGR+MR.BRD+K.IGR'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYNNNYY' THEN 'IGR+K.IGR+DEPO'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYNNNYN' THEN 'IGR+K.IGR'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYNNNNY' THEN 'IGR+DEPO'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYNNNNN' THEN 'IGR'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NNYYNYY' THEN 'IDM+OMI+K.IGR+DEPO'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NNYYNYN' THEN 'IDM+OMI+K.IGR'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NNYYNNY' THEN 'IDM+OMI+DEPO'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NNYYNNN' THEN 'IDM+OMI'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NNYNNYY' THEN 'IDM+K.IGR+DEPO'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NNYNNYN' THEN 'IDM+K.IGR'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NNYNNNY' THEN 'IDM+DEPO'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NNYNNNN' THEN 'IDM'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NNNYNYN' THEN 'OMI+K.IGR'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NNNYNNN' THEN 'OMI'                  
                                        WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NNNNNNN' THEN 'BELUM ADA FLAG'                  
                                        ELSE 'BELUM ADA FLAG'                  
                                        END AS FLAG,
                                        LPP,PLANO,NOID
                                        FROM
                                        (
SELECT  LKS_KODERAK || '.' || LKS_KODESUBRAK|| '.' || LKS_TIPERAK|| '.'|| LKS_SHELVINGRAK|| '.' ||  LKS_NOURUT  AS LOKASI,
        prd_prdcd as plu,prd_deskripsipanjang as desk,prd_unit as unit,prd_frac as frac,
        COALESCE(PRD_FLAGNAS, 'N') AS NAS,
        COALESCE(PRD_FLAGIGR, 'N') AS IGR,
        COALESCE(PRD_FLAGIDM, 'N') AS IDM,
        COALESCE(PRD_FLAGOMI, 'N') AS OMI,
        COALESCE(PRD_FLAGBRD, 'N') AS BRD,
        COALESCE(PRD_FLAGOBI, 'N') AS K_IGR,
        case when prd_plumcg in (select PLUIDM from igrpwt.DEPO_LIST_IDM ) THEN 'Y' ELSE 'N' END AS DEPO,
        ST_SALDOAKHIR AS LPP, 
        LKS_QTY AS PLANO,
        LKS_NOID AS NOID
        FROM IGRPWT.TBMASTER_PRODMAST
LEFT JOIN (SELECT ST_PRDCD, ST_SALDOAKHIR, LKS.* FROM IGRPWT.TBMASTER_STOCK
            LEFT JOIN (SELECT * FROM IGRPWT.TBMASTER_LOKASI WHERE LKS_KODERAK ~ '^(D|R|P)')LKS ON ST_PRDCD = LKS_PRDCD
            WHERE ST_LOKASI = '01') ST ON PRD_PRDCD = ST_PRDCD
WHERE PRD_PRDCD LIKE '%0' AND LKS_QTY <> '0' AND ST_SALDOAKHIR = '0' ) qq order by 2";
        

        $results = DB::connection('pgsql')->select($query);

        return view('master.lppnol', compact('results'));
    }
}