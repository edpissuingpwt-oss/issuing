<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EDP_DataPBIDM extends Controller
{
    public function datapbidm()
    {
        return view('edp.datapbidm');
    }

    public function getData(Request $request)
    {
        $start = \Carbon\Carbon::createFromFormat('d-m-Y', $request->tanggal_mulai)->format('Y-m-d');
        $end = \Carbon\Carbon::createFromFormat('d-m-Y', $request->tanggal_selesai)->format('Y-m-d');

        $sql = "SELECT 
                        kode, awal, akhir, div, dep, kat, pluigr, pluidm, desk, frac, tag_igr, tag_idm, acost, 
                        qty_pb, rp_pb, qty_real, rp_real, (qty_pb*rp_margin) margin_pb, (qty_real*rp_margin) margin_real, pm 
                        from ( 
                        select kode, awal,akhir, div, dep, kat, pluigr, pluidm, desk, frac, tag_igr, tag_idm, acost, 
                        sum(qty_pb) qty_pb, round(sum(rp_pb)) rp_pb, sum(qty_ril) qty_real, round(sum(rp_ril)) rp_real, mgn_margin pm, round((acost*mgn_margin)/100) rp_margin 
                        from (select prd_kodeigr kode, 
                        case when awal is null then awalT else awal end as AWAL, case when akhir is null then akhirT else akhir end as akhir, 
                        PRD_PRDCD pluigr, prc_pluidm pluidm, PRD_DESKRIPSIPANJANG DESK, PRD_FRAC FRAC,PRD_KODETAG TAG_IGR, prc_kodetag TAG_IDM, st_avgcost AS acost, 
                        coalesce(QTY_PB,0)+coalesce(QTY_tlk,0) QTY_PB, QTY_RIL, coalesce(RP_PB,0)+coalesce(RP_tlk,0) rp_pb, RP_RIL, 
                        case when prd_kodedepartement='06' and prd_kodekategoribarang not in ('01') then 'X' 
                        when prd_kodedepartement not in ('05','06') then 'X' 
                        when prd_kodedepartement in (select dep_kodedepartement from igrpwt.tbmaster_departement td where dep_kodedepartement not in (select mgn_kodedepartement from igrpwt.tbmaster_marginidm tm)) then 'X' 
                        else prd_kodekategoribarang end as kaat, 
                        case when prd_kodedepartement in (select dep_kodedepartement from igrpwt.tbmaster_departement td where dep_kodedepartement not in (select mgn_kodedepartement from igrpwt.tbmaster_marginidm tm)) then 'X' 
                        else prd_kodedepartement end as deep,prd_kodedivisi div, prd_kodedepartement dep, prd_kodekategoribarang kat 
                        from igrpwt.tbmaster_prodmast tp 
                        LEFT JOIN (select * from igrpwt.TBMASTER_PRODCRM where prc_group ='I') pp ON PRC_PLUIGR=PRD_PRDCD 
                        left join (select * from igrpwt.tbmaster_stock ts where st_lokasi ='01') st on st_prdcd=PRD_PRDCD 
                        left join (select min(pbo_create_dt::DATE) awal, max(pbo_create_dt::DATE) akhir, 
                        SUBSTR(PBO_PLUIGR,1,6)||0 PLUI, PBO_PLUOMI PLUIDM, ROUND(SUM(pbo_qtyorder)) AS QTY_PB, 
                        ROUND(SUM((CASE WHEN PBO_nokoli is not null THEN PBO_QTYREALISASI ELSE 0 END))) AS QTY_RIL, 
                        ROUND(SUM(PBO_QTYORDER*PBO_HRGSATUAN)) AS RP_PB, ROUND(SUM((CASE WHEN PBO_nokoli is not null THEN PBO_TTLNILAI ELSE 0 end))) AS RP_RIL 
                        from igrpwt.tbmaster_pbomi tp where pbo_kodesbu ='I' 
                        and pbo_create_dt::DATE between :start and :end -- GANTI SEMINGGU
                        group by SUBSTR(PBO_PLUIGR,1,6)||0, PBO_PLUOMI) pbo on pluidm=prc_pluidm 
                        left join (select min(TLKO_CREATE_DT::DATE) awalT, max(TLKO_CREATE_DT::DATE) akhirT, SUBSTR(TLKO_PLUIGR,1,6)||'0' PLUt, TLKO_PLUOMI PLUIt, 
                        ROUND(SUM(TLKO_QTYORDER)) QTY_tlk, ROUND(SUM(TLKO_QTYORDER*TLKO_NILAI)) RP_tlk 
                        FROM igrpwt.TBTR_TOLAKANPBOMI WHERE TLKO_CREATE_DT::DATE BETWEEN :start and :end -- GANTI SEMINGGU
                        and tlko_kodeomi in (select tko_kodeomi from igrpwt.tbmaster_tokoigr tt where tko_kodesbu in ('I')) 
                        GROUP BY SUBSTR(TLKO_PLUIGR,1,6)||'0', TLKO_PLUOMI, TLKO_DESC, tlko_kettolakan) tlk on pluit=prc_pluidm 
                        where prd_prdcd like '%0' and PRD_FLAGIDM='Y')xc 
                        left join igrpwt.tbmaster_marginidm tm on deep||'.'||kaat=mgn_kodedepartement||'.'||mgn_kodekategori 
                        group by kode, div, dep, kat, pluigr, pluidm, desk, frac, tag_igr, tag_idm, acost, mgn_margin,kaat, 
                        deep||'.'||kaat, mgn_kodedepartement||'.'||mgn_kodekategori, awal,akhir) ABC";

        $results = DB::select($sql, [
            'start' => $start,
            'end' => $end
        ]);

        return view('edp.datapbidm_table', [
            'data' => $results,
            'start' => $start,
            'end' => $end
        ]);
    }
}