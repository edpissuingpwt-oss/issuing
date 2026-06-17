<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EDP_TolakanIDM extends Controller
{
    protected $filters = [
        '1' => "tlko_kettolakan = 'QTY ORDER < MINOR TOKO'",
        '2' => "tlko_kettolakan = 'AVG.COST IS NULL'",
        '3' => "TAG_IGR IN('A','H','O','N','T','V','I')",
        '4' => "tlko_kettolakan IN ('TIDAK PUNYA LOKASI','TIDAK PUNYA GROUP RAK','PLU TIDAK TERDAFTAR DI MASTER_LOKASI')",
        '5' => "plu IS NULL",
        '6' => "tlko_kettolakan ='STOCK EKONOMIS TIDAK MENCUKUPI'",
    ];

    public function index()
    {
        return view('edp.tolakanidm');
    }

    public function getData(Request $request)
    {
        $ket_id = $request->input('filter', '1');
        $filter = $this->filters[$ket_id] ?? $this->filters['1'];

        try {
            // Gunakan DB::select dengan binding (jika ada parameter dinamis)
            $data = DB::select("
                select
                        case
                            when TLKO_KETTOLAKAN in ('TIDAK PUNYA GROUP RAK',
                    'NOID BULKY DOUBLE',
                    'PECAHAN BULKY',
                    'NOID PIECES DOUBLE',
                    'NOID DOUBLE',
                    'PLU TIDAK TERDAFTAR DI MASTER_LOKASI',
                    'PLU TIDAK TERDAFTAR DI STMAST',
                    'QTY ORDER < MINOR TOKO',
                    'TIDAK PUNYA LOKASI',
                    'PLU TIDAK TERDAFTAR DI MASTER HARGABELI')
                    and po is null
                    and tag_idm not in ('T', 'V', 'O', 'N', 'H', 'A', 'X', 'G', 'F', 'R', 'I')
                    and (tag_idm not in ('T', 'V', 'O', 'N', 'H', 'A', 'X', 'G', 'F', 'R', 'I')
                    OR tag_igr not in ('T', 'V', 'O', 'N', 'H', 'A', 'X', 'G', 'F', 'R', 'I'))
                    and PO is NULL
                    then 'LJM'
                            else 'MD'
                        end as LJM_MD,
                        case
                            when TLKO_PLUOMI in ('10004337', '10000426', '10003814', '10004335', '10004336', '10004487', '20008785', '20069208') then 'AMDK'
                            when TLKO_PLUOMI in ('20010655', '20053745', '10000734', '20040511', '10000133', '20074006',
                    '10000360', '20020625', '10000132', '10019288', '20074000', '20031552', '20074005', '20066190', '20073997', '20036135',
                    '20046368', '10038971', '20031156', '20036130', '10019363', '20073998', '20074001', '20074002', '20066191', '20074004',
                    '20069566', '20069567', '20046757', '20016388', '20066192', '20076737', '20084925', '20084959', '20084961', '20084963') then 'BISKUIT'
                            when TLKO_PLUOMI in ('20053796', '10000073', '10000077', '10000097',
                    '10000149', '10000155', '10000425', '10003048', '10003163', '10003272', '10004067', '10004359', '10004714', '10005198', '10005481',
                    '10005482', '10005836', '10006651', '10008588', '10008683', '10010189', '10010683', '10011923', '10012667', '10025411', '10026612',
                    '10031825', '10032895', '10034123', '10037405', '10038692', '20001221', '20002523', '20002944', '20003466', '20006422', '20007307',
                    '20012007', '20012188', '20019253', '20020128', '20021324', '20021391', '20021392', '20021393', '20022015', '20022403', '20022903',
                    '20023765', '20027095', '20027166', '20027167', '20027185', '20029296', '20029782', '20030188', '20032749', '20032750', '20032806',
                    '20034336', '20034450', '20034536', '20034577', '20035998', '20036047', '20036081', '20036082', '20036892', '20036893', '20038429',
                    '20038430', '20040719', '20041621', '20043626', '20044267', '20045178', '20045418', '20045419', '20045422', '20046211', '20046548',
                    '20047432', '20047965', '20048847', '20048879', '20048880', '20048900', '20049226', '20051952', '20052059', '20052305', '20052306',
                    '20053803', '20054151', '20054951', '20054969', '20059240', '20061748', '20063156', '20067198', '20067473', '20067585', '20070687',
                    '20070688', '20071775', '20071827', '20072006', '20072666', '20072667', '20073425', '20073499', '20074348', '20074349', '20075624') then 'PASCA'
                            when TLKO_PLUOMI in ('10000689', '10000690',
                    '10000688', '20055829', '20043877', '20025487', '10000019', '10000020', '10000021', '10000070', '10000088', '10000102', '10000175',
                    '10000185', '10000206', '10000255', '10000461', '10000555', '10000562', '10000652', '10000665', '10000752', '10000753', '10000861',
                    '10000975', '10001094', '10001826', '10002047', '10002101', '10002246', '10002560', '10002595', '10003427', '10003517', '10003650',
                    '10003995', '10004669', '10004906', '10005073', '10005489', '10005491', '10005599', '10005600', '10006098', '10006890', '10007380',
                    '10007916', '10007970', '10007973', '10008581', '10008819', '10008882', '10010219', '10010220', '10012277', '10012742', '10015141',
                    '10015550', '10016170', '10020713', '10021003', '10021970', '10021972', '10022118', '10023789', '10024188', '10024189', '10025399',
                    '10029331', '10031572', '10033424', '10035165', '10035326', '10036640', '10036647', '10036938', '10036957', '10038277', '10038352',
                    '10038932', '10039897', '10039924', '20000862', '20003786', '20003910', '20003915', '20004229', '20008946', '20008947', '20009722',
                    '20009737', '20010272', '20012678', '20012681', '20013332', '20013433', '20014069', '20014071', '20014623', '20015235', '20015737',
                    '20015838', '20017349', '20018435', '20019673', '20019674', '20021029', '20021032', '20021178', '20021454', '20024644', '20024699',
                    '20024856', '20024857', '20025359', '20027448', '20027551', '20028585', '20028880', '20030547', '20032058', '20033450', '20034079',
                    '20034682', '20034684', '20034685', '20034686', '20035484', '20036157', '20036590', '20037153', '20037159', '20037565', '20039784',
                    '20040799', '20042370', '20042848', '20042859', '20042991', '20044334', '20045104', '20045132', '20045415', '20045674', '20045784',
                    '20046841', '20046842', '20047245', '20047616', '20047617', '20049190', '20052211', '20052296', '20052641', '20052934', '20053099',
                    '20053100', '20055077', '20055078', '20055311', '20055312', '20055801', '20057442', '20057444', '20058084', '20060207', '20062818',
                    '20062912', '20062913', '20063359', '20064556', '20065096', '20067173', '20067174', '20067228', '20067874', '20068905', '20069143',
                    '20069145', '20069149', '20069773', '20070033', '20070253', '20071780', '20071781', '20071980', '20072249', '20076768', '20079847') then 'PUASA'
                            when TLKO_PLUOMI in ('10000700', '10000772', '10010933', '10000704',
                    '10032795', '10032797', '10000352', '20052758', '10010946', '20015819', '10014154', '10036625', '20084983') then 'SYRUP'
                            else '-'
                        end as ITEM_TERKAIT,
                        r.RAKK,
                        flag.flag,
                        TLKO_PLUOMI,
                        PLU,
                        p.PRD_DESKRIPSIPANJANG,
                        p.FRAC,
                        m.TAG_IDM,
                        p.TAG_IGR,
                        k.PKM_PKMT,
                        k.PKM_PKM,
                        k.KOEF,
                        k.LT,
                        ITEM,
                        '' item_rill,
                        MINOR,
                        case
                            when PLU in ('1303350') then 'MINOR 1 KARTON (120 PCS)'
                            when PLU in ('1398740', '1383200') then 'MINOR 1 KARTON (288 PCS)'
                            when PLU in ('1382860') then 'MINOR 1.5 JUTA MIX'
                            when PLU in ('1383000', '1382960', '1382950', '1382980') then 'MINOR 1.5JT MIX ITEM'
                            when PLU in ('1360580', '1280900', '1274090', '1293900') then 'Minor 10 ctn'
                            when PLU in ('1382800', '1322580', '1322590', '1382790') then 'Minor 10 Ctn ( mix item lain )'
                            when PLU in ('1381230') then 'MINOR 100 CTN'
                            when PLU in ('1382260', '1382290', '1274680') then 'Minor 100 Ctn ( Mix item Lain )'
                            when PLU in ('1382300') then 'MINOR 100 CTN ( MIX PLU LAINNYA )'
                            when PLU in ('1529230') then 'MINOR 15 LUSIN'
                            when PLU in ('1277690', '1277730', '1278070', '1041980', '1038740', '1399040', '1277740') then 'Minor 1Jt ( Mix item lain )'
                            when PLU in ('1410500', '1345030', '1182780', '1306750', '1079250', '1302870', '1302850', '1403650', '1163880', '1300460',
                    '1357800', '1450710', '1163770', '1301060', '1300490', '1318770', '1558760', '1168530', '1168510', '1274750',
                    '1232280', '1300470', '1232290', '1300480', '1180730', '1285190', '1345040', '1180720', '1669450', '1318790', '1318780', '1300500', '1180710', '1347940', '1046520', '1063370', '1157110', '1383100', '1301170', '1202100',
                    '1303000', '0433950', '0543990', '1330100', '0898910', '1345050', '1163780', '1163870', '1301160', '0544160', '1339000', '1153900', '1145280', '1087790', '1087800', '1294260', '1318800')
                    then 'Minor 2.4Jt ( Mix Item Lain )'
                            when PLU in ('1382270') then 'MINOR 20 CTN'
                            when PLU in ('1311120', '1278250', '1278240', '1311130', '1381220', '1381160') then 'MINOR 20 CTN ( MIX ITEM LAIN )'
                            when PLU in ('1333910') then 'MINOR 2000 CTN ( NPK IDM )'
                            when PLU in ('1668360', '1311170') then 'Minor 25 Ctn ( Mix item lain )'
                            when PLU in ('1382660', '1356140', '1529820', '1382710') then 'MINOR 250 CTN (GABUNG DENGAN PO IDM)'
                            when PLU in ('1382570') then 'MINOR 3 CTN'
                            when PLU in ('1355980', '1382400', '1381180', '1311230') then 'Minor 30 Ctn ( Mix item Lain )'
                            when PLU in ('1356590', '1356580', '1356600') then 'MINOR 30 CTN ( MIX ITEM LAINNYA )'
                            when PLU in ('1188430') then 'MINOR 420 CTN'
                            when PLU in ('1329130', '1188550') then 'MINOR 50 CTN'
                            when PLU in ('1529710', '1529630', '1529620') then 'MINOR 50 CTN MIX ITEM'
                            when PLU in ('1381240', '1311150') then 'Minor 58 ctn ( Mix item lain )'
                            when PLU in ('1398800') then 'MINOR 6 CTN'
                            when PLU in ('1201830', '1255580') then 'Minor 7.5 Ton ( Mix item Lain )'
                            when PLU in ('1152510''1152520', '1310870', '1152480', '1652800', '1652790', '1152490', '1652780', '1534070', '1334320', '1310880', '1241180', '1554740', '1341180',
                    '1286560', '1217370', '1152500', '1437370', '1437350', '1437360', '1437380', '1534490', '1534080', '1290490', '1340820', '1340830', '1290500') then 'Minor 700-800 Ctn ( Mix item Lain )'
                            else ' '
                        end as KET_MINOR,
                        case
                            when PLU in ('0088010', '0087960', '0088000') then QTY_PB / 1000
                            else QTY_PB
                        end QTYPBb,
                        '0' QTYREAL,
                        case
                            when PLU in ('0088010', '0087960', '0088000') then RUPIAHPB / 1000
                            else RUPIAHPB
                        end RUPIAHPBb,
                        '0' RUPIAHREAL,
                        case
                            when PLU in ('0088010', '0087960', '0088000') then RUPIAHPB / 1000
                            else RUPIAHPB
                        end rp_selisih,
                        coalesce(s.SL_SUP,
                        0) SL_SUPLLIER,
                        n.MPLUS,
                        pl.LKS_MAXPLANO,
                        '' recid4,
                        lppawal.sth_saldoakhir as lpp_awal,
                        lppsekarang.st_saldoakhir as lpp_saatini,
                        vv.NPO,
                        s.po,
                        s.bpb,
                        pl.PLANODPD,
                        tk.PLANOTOKO,
                        case
                            when lppawal.sth_saldoakhir < QTY_PB then 'STOCK < PB'
                            else '-'
                        end as KET_STOCK,
                        '-' KET,
                        kph.ksl_mean as kphmean,
                        TLKO_KETTOLAKAN,
                        case
                            when PLU in ('0047180', '0053250', '0365020',
                    '0365030', '0543000', '0873150', '1116460', '1153700', '1153710', '1236280', '1246360', '1254410', '1271480', '1271490',
                    '1271530', '1311880', '1311900', '1311910', '1311920', '1311930', '1311940', '1311990', '1312000', '1312050', '1312060',
                    '1324710', '1347330', '1367890', '1393260', '1402850', '1430360', '1430370', '1430380', '1430400', '1430390', '1515080', '0009480', '0030680', '0009170', '0033580',
                    '1077740', '0032020', '1580770', '0873130', '0003040', '1481790', '1201240', '0030600', '1474100', '0009380', '1822660', '0003030',
                    '0009190', '0421700', '0032070', '1178690', '1064500', '1271520', '1259590', '1515990', '0539680', '1515990', '1831410', '1456630',
                    '1527420', '1527420', '1430440', '1831410', '1325580', '1831410', '1353150', '1325580') then 'ITEM SEASONAL'
                            when PLU in ('1372770', '1497620', '1514850', '1320300', '1320280', '1320290', '1498650', '1687410', '1687400', '1687390') then 'ITEM PROMO MARKETING'
                            when TLKO_KETTOLAKAN = 'QTY ORDER < MINOR TOKO' then 'QTY ORDER < MINOR TOKO'
                            when TAG_IDM in ('H', 'N', 'O', 'R', 'V') then 'TAG IDM DISCONTINUE (H,N,O,R,V)'
                            when TAG_IDM = 'I' then 'IDM TAG I'
                            when TAG_IDM = 'T' then 'IDM TAG T'
                            when TAG_IDM = 'G' then 'IDM TAG G'
                            when TAG_IGR in ('A', 'H', 'N', 'O', 'X') then 'TAG IGR DISCONTINUE (A,H,N,O,X)'
                            when TAG_IGR = 'G' then 'IGR TAG G'
                            when TAG_IGR = 'T' then 'IGR TAG T'
                            when TAG_IGR = 'I' then 'IGR TAG I'
                            else '-'
                        end as ITEM_SEASONAL,
                        p.DIV,
                        p.DEP,
                        p.KAT,
                        h.HGB_KODESUPPLIER,
                        h.SUP_NAMASUPPLIER
                    from
                        --MASTER--
                    (
                        select
                            SUBSTR(a.PLUU,
                            1,
                            6) || '0' as PLU,
                            a.TLKO_PLUOMI,
                            a.TLKO_KETTOLAKAN,
                            a.ITEM,
                            a.QTY_PB,
                            a.QTY_PB * coalesce(b.COSTT,
                            0) as RUPIAHPB
                        from
                            (
                            select
                                TLKO_PLUIGR as PLUU,
                                TLKO_PLUOMI,
                                TLKO_KETTOLAKAN,
                                tlko_ptag,
                                COUNT(TLKO_PLUOMI) as ITEM,
                                SUM(TLKO_QTYORDER) as QTY_PB
                            from
                                igrpwt.TBTR_TOLAKANPBOMI
                            where
                                DATE(tlko_tglpb) = CURRENT_DATE
                                and TLKO_KODEOMI in (
                                select
                                    TKO_KODEOMI
                                from
                                    igrpwt.tbmaster_tokoigr
                                where
                                    TKO_KODESBU = 'I'
                                )
                            group by
                                TLKO_PLUIGR,
                                TLKO_PLUOMI,
                                TLKO_KETTOLAKAN,
                                tlko_ptag
                        ) a
                        left join
                        (
                            select
                                TLKO_PLUOMI,
                                MAX(TLKO_LASTCOST) as COSTT
                            from
                                igrpwt.TBTR_TOLAKANPBOMI
                            where
                                DATE(TLKO_CREATE_DT) = CURRENT_DATE
                            group by
                                TLKO_PLUOMI
                        ) b
                    on
                            a.TLKO_PLUOMI = b.TLKO_PLUOMI)c
                    left join
                        (
                        select
                            PRD_PRDCD,
                            PRD_KODEDIVISI DIV,
                            PRD_KODEDEPARTEMENT DEP,
                            PRD_KODEKATEGORIBARANG KAT,
                            PRD_KODETAG as TAG_IGR,
                            PRD_MINORDER MINOR,
                            PRD_DESKRIPSIPANJANG,
                            PRD_KODESATUANJUAL2 || '/' || PRD_FRAC FRAC
                        from
                            igrpwt.TBMASTER_PRODMAST)p
                            on
                        PLU = p.PRD_PRDCD
                    left join
                        --PRODCRM--
                    (
                        select
                            PRC_PLUIDM,
                            PRC_PLUIGR,
                            PRC_KODETAG TAG_IDM
                        from
                            igrpwt.TBMASTER_PRODCRM
                        where
                            PRC_GROUP = 'I')m
                            on
                        PLU = m.PRC_PLUIGR
                    left join
                        --PKM--
                    (
                        select
                            PKM_PRDCD,
                            PKM_LEADTIME LT,
                            PKM_PKM,
                            PKM_PKMT,
                            PKM_KOEFISIEN KOEF
                        from
                            igrpwt.TBMASTER_KKPKM)k
                            on
                        PLU = k.PKM_PRDCD
                    left join
                        --M+--
                    (
                        select
                            PKMP_PRDCD,
                            PKMP_QTYMINOR MPLUS
                        from
                            igrpwt.tbmaster_pkmplus)n
                            on
                        PLU = n.PKMP_PRDCD
                        --SUP--
                    left join
                        (
                        select
                            HGB_PRDCD,
                            HGB_KODESUPPLIER,
                            SUP_NAMASUPPLIER
                        from
                            igrpwt.TBMASTER_HARGABELI,
                            igrpwt.TBMASTER_SUPPLIER
                        where
                            SUP_KODESUPPLIER = HGB_KODESUPPLIER
                            and HGB_TIPE = '2')h
                            on
                        PLU = h.HGB_PRDCD
                        --rak--
                    left join
                        (
                        select
                            LKS_PRDCD PLU_RAK,
                            LKS_KODERAK || '.' || LKS_KODESUBRAK || '.' || LKS_TIPERAK || '.' || LKS_SHELVINGRAK || '.' || LKS_NOURUT RAKK
                        from
                            igrpwt.TBMASTER_LOKASI
                        where
                            LKS_TIPERAK in ('N', 'B')
                                and LKS_KODERAK like 'D%'
                                and LKS_KODERAK not like 'DKLIK%')r
                                on
                        PLU = r.PLU_RAK
                    left join
                                (
                        select
                            TPOD_PRDCD PLU_PO,
                            SUM(TPOD_QTYPO) PO,
                            coalesce (SUM(TPOD_QTYPB),
                            0) BPB,
                            ROUND((coalesce (SUM(TPOD_QTYPB),
                            0))/(SUM(TPOD_QTYPO))* 100,
                            0) SL_SUP
                        from
                            igrpwt.TBTR_PO_D
                        where
                            date(TPOD_TGLPO) between (current_date-30) and current_date
                        group by
                            TPOD_PRDCD)s
                            on
                        PLU = s.PLU_PO
                        --PLANO DPD--
                    left join
                        (
                        select
                            LKS_PRDCD DPD,
                            LKS_QTY PLANODPD,
                            LKS_MAXPLANO
                        from
                            igrpwt.tbmaster_lokasi
                        where
                            LKS_PRDCD is not null
                            and LKS_KODERAK like 'D%'
                            and LKS_KODERAK not like 'DPST%'
                            and LKS_NOID is not null)pl
                        on
                        PLU = pl.DPD
                        --plano toko--
                    left join
                        (
                        select
                            LKS_PRDCD TOKO,
                            SUM(LKS_QTY) PLANOTOKO
                        from
                            igrpwt.tbmaster_lokasi
                        where
                            LKS_PRDCD is not null
                            and LKS_KODERAK like 'R%'
                        group by
                            LKS_PRDCD)tk
                            on
                        PLU = tk.TOKO
                        --QTYPB--
                    left join
                        (
                        select
                            PBD_PRDCD PLU_QTYPB,
                            SUM(PBD_QTYPB) QTYPB
                        from
                            igrpwt.TBTR_PB_D
                        where
                            date(PBD_CREATE_DT)= CURRENT_DATE
                        group by
                            PBD_PRDCD)q
                            on
                        PLU = q.PLU_QTYPB
                    left join
                        (
                        select
                            po.PLUPO,
                            MAX(po.NOPO) NPO,
                            MAX(po.TGLPO) TGPO
                        from
                            (
                            select
                                TPOD_PRDCD as PLUPO,
                                TPOH_NOPO as NOPO,
                                TPOD_TGLPO as TGLPO
                            from
                                igrpwt.tbtr_po_h
                            left join igrpwt.tbtr_po_d on
                                TPOD_NOPO = TPOH_NOPO
                            where
                                date(tpoh_tglpo)>= CURRENT_DATE
                                    and TPOH_RECORDID is null)po
                        group by
                            po.PLUPO)vv
                        on
                        PLU = vv.PLUPO
                        left join
                        (select sth_prdcd,sth_lokasi,sth_saldoakhir from igrpwt.tbtr_stockharian where sth_lokasi='01' and date(sth_create_dt)=current_date-1)lppawal
                        on PLU=lppawal.sth_prdcd
                        left join
                        (select st_prdcd,st_saldoakhir from igrpwt.tbmaster_stock where st_lokasi='01')lppsekarang
                        on PLU=lppsekarang.st_prdcd
                        left join
                        (select
                        a.PRD_PRDCD as PLU_FLAG,
                        case
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYYYYY' then 'NAS-IGR+IDM+OMI+MR.BRD+K.IGR+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYYYYN' then 'NAS-IGR+IDM+OMI+MR.BRD+K.IGR'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYYYNN' then 'NAS-IGR+IDM+OMI+MR.BRD'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYYNYY' then 'NAS-IGR+IDM+OMI+K.IGR+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYYNYN' then 'NAS-IGR+IDM+OMI+K.IGR'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYYNNY' then 'NAS-IGR+IDM+OMI+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYYNNN' then 'NAS-IGR+IDM+OMI'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYNYYY' then 'NAS-IGR+IDM+MR.BRD+K.IGR+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYNNYY' then 'NAS-IGR+IDM+K.IGR+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYNNYN' then 'NAS-IGR+IDM+K.IGR'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYNNNY' then 'NAS-IGR+IDM+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYNNNN' then 'NAS-IGR+IDM'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYNYNYY' then 'NAS-IGR+OMI+K.IGR+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYNYNYN' then 'NAS-IGR+OMI+K.IGR'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYNYNNY' then 'NAS-IGR+OMI+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYNYNNN' then 'NAS-IGR+OMI'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYNNYYN' then 'NAS-IGR+MR.BRD+K.IGR'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYNNYNN' then 'NAS-IGR+MR.BRD'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYNNNYY' then 'NAS-IGR+K.IGR+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYNNNYN' then 'NAS-IGR+K.IGR'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYNNNNY' then 'NAS-IGR+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYNNNNN' then 'NAS-IGR'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNYYNYY' then 'NAS-IDM+OMI+K.IGR+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNYYNYN' then 'NAS-IDM+OMI+K.IGR'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNYYNNY' then 'NAS-IDM+OMI+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNYYNNN' then 'NAS-IDM+OMI'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNYNNYY' then 'NAS-IDM+K.IGR+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNYNNYN' then 'NAS-IDM+K.IGR'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNYNNNY' then 'NAS-IDM+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNYNNNN' then 'NAS-IDM'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNNYYNN' then 'NAS-OMI+MR.BRD'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNNYNYN' then 'NAS-OMI+K.IGR'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNNYNNN' then 'NAS-OMI'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNNNYNN' then 'NAS-MR.BRD'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNNNNYN' then 'NAS-K.IGR'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNNNNNN' then 'NAS'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYYYYYY' then 'IGR+IDM+OMI+MR.BRD+K.IGR+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYYYYYN' then 'IGR+IDM+OMI+MR.BRD+K.IGR'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYYYNYY' then 'IGR+IDM+OMI+K.IGR+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYYYNYN' then 'IGR+IDM+OMI+K.IGR'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYYYNNY' then 'IGR+IDM+OMI+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYYYNNN' then 'IGR+IDM+OMI'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYYNNYY' then 'IGR+IDM+K.IGR+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYYNNYN' then 'IGR+IDM+K.IGR'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYYNNNY' then 'IGR+IDM+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYYNNNN' then 'IGR+IDM'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYNYNYY' then 'IGR+OMI+K.IGR+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYNYNYN' then 'IGR+OMI+K.IGR'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYNYNNN' then 'IGR+OMI'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYNNYYN' then 'IGR+MR.BRD+K.IGR'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYNNNYY' then 'IGR+K.IGR+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYNNNYN' then 'IGR+K.IGR'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYNNNNY' then 'IGR+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYNNNNN' then 'IGR'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NNYYNYY' then 'IDM+OMI+K.IGR+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NNYYNYN' then 'IDM+OMI+K.IGR'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NNYYNNY' then 'IDM+OMI+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NNYYNNN' then 'IDM+OMI'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NNYNNYY' then 'IDM+K.IGR+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NNYNNYN' then 'IDM+K.IGR'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NNYNNNY' then 'IDM+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NNYNNNN' then 'IDM'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NNNYNYN' then 'OMI+K.IGR'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NNNYNNN' then 'OMI'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NNNNNYN' then 'K.IGR'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NNNNNNN' then 'TIDAK PUNYA FLAG'
                            else 'BELUM ADA FLAG'
                        end as FLAG
                    from
                        (
                        select
                            prd_prdcd,
                            prd_plumcg,
                            coalesce(PRD_FLAGNAS,
                            'N') as NAS,
                            coalesce(PRD_FLAGIGR,
                            'N') as IGR,
                            coalesce(PRD_FLAGIDM,
                            'N') as IDM,
                            coalesce(PRD_FLAGOMI,
                            'N') as OMI,
                            coalesce(PRD_FLAGBRD,
                            'N') as BRD,
                            coalesce(PRD_FLAGOBI,
                            'N') as K_IGR,
                            case
                                when prd_plumcg in (
                                select
                                    PLUIDM
                                from
                                    igrpwt.DEPO_LIST_IDM ) then 'Y'
                                else 'N'
                            end as DEPO
                        from
                            igrpwt.TBMASTER_PRODMAST 
                        where
                            PRD_PRDCD like '%0'
                            and PRD_DESKRIPSIPANJANG is not null)a)flag
                            on plu=flag.PLU_FLAG
                            left join
                            (SELECT 
                    TO_CHAR(
                        TO_DATE(
                        CASE 
                            WHEN LENGTH(pid) = 5 THEN 
                            '01-' || LPAD(LEFT(pid, 1), 2, '0') || '-' || RIGHT(pid, 4)
                            WHEN LENGTH(pid) = 6 THEN 
                            '01-' || LEFT(pid, 2) || '-' || RIGHT(pid, 4)
                        END,
                        'DD-MM-YYYY'
                        ),
                        'Month YYYY'
                    ) AS bulan_tahun,
                    prdcd,
                    ksl_mean
                    FROM igrpwt.tbmaster_kph
                    WHERE 
                    TO_DATE(
                        CASE 
                        WHEN LENGTH(pid) = 5 THEN 
                            '01-' || LPAD(LEFT(pid, 1), 2, '0') || '-' || RIGHT(pid, 4)
                        WHEN LENGTH(pid) = 6 THEN 
                            '01-' || LEFT(pid, 2) || '-' || RIGHT(pid, 4)
                        END,
                        'DD-MM-YYYY'
                    ) = (
                        SELECT MAX(
                        TO_DATE(
                            CASE 
                            WHEN LENGTH(pid) = 5 THEN 
                                '01-' || LPAD(LEFT(pid, 1), 2, '0') || '-' || RIGHT(pid, 4)
                            WHEN LENGTH(pid) = 6 THEN 
                                '01-' || LEFT(pid, 2) || '-' || RIGHT(pid, 4)
                            END,
                            'DD-MM-YYYY'
                        )
                        )
                        FROM igrpwt.tbmaster_kph
                    )
                    )kph
                            on tlko_pluomi=kph.prdcd
                            WHERE $filter;
                                ");

            return view('edp.tolakanidm_table', compact('data'));
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getData2(Request $request)
    {
        $ket_id = $request->input('filter', '1');
        $filter = $this->filters[$ket_id] ?? $this->filters['1'];

        try {

            $data = retry(3, function () use ($filter) {
                return DB::select("
                    select
                        case
                            when TLKO_KETTOLAKAN in ('TIDAK PUNYA GROUP RAK',
                    'NOID BULKY DOUBLE',
                    'PECAHAN BULKY',
                    'NOID PIECES DOUBLE',
                    'NOID DOUBLE',
                    'PLU TIDAK TERDAFTAR DI MASTER_LOKASI',
                    'PLU TIDAK TERDAFTAR DI STMAST',
                    'QTY ORDER < MINOR TOKO',
                    'TIDAK PUNYA LOKASI',
                    'PLU TIDAK TERDAFTAR DI MASTER HARGABELI')
                    and po is null
                    and tag_idm not in ('T', 'V', 'O', 'N', 'H', 'A', 'X', 'G', 'F', 'R', 'I')
                    and (tag_idm not in ('T', 'V', 'O', 'N', 'H', 'A', 'X', 'G', 'F', 'R', 'I')
                    OR tag_igr not in ('T', 'V', 'O', 'N', 'H', 'A', 'X', 'G', 'F', 'R', 'I'))
                    and PO is NULL
                    then 'LJM'
                            else 'MD'
                        end as LJM_MD,
                        case
                            when TLKO_PLUOMI in ('10004337', '10000426', '10003814', '10004335', '10004336', '10004487', '20008785', '20069208') then 'AMDK'
                            when TLKO_PLUOMI in ('20010655', '20053745', '10000734', '20040511', '10000133', '20074006',
                    '10000360', '20020625', '10000132', '10019288', '20074000', '20031552', '20074005', '20066190', '20073997', '20036135',
                    '20046368', '10038971', '20031156', '20036130', '10019363', '20073998', '20074001', '20074002', '20066191', '20074004',
                    '20069566', '20069567', '20046757', '20016388', '20066192', '20076737', '20084925', '20084959', '20084961', '20084963') then 'BISKUIT'
                            when TLKO_PLUOMI in ('20053796', '10000073', '10000077', '10000097',
                    '10000149', '10000155', '10000425', '10003048', '10003163', '10003272', '10004067', '10004359', '10004714', '10005198', '10005481',
                    '10005482', '10005836', '10006651', '10008588', '10008683', '10010189', '10010683', '10011923', '10012667', '10025411', '10026612',
                    '10031825', '10032895', '10034123', '10037405', '10038692', '20001221', '20002523', '20002944', '20003466', '20006422', '20007307',
                    '20012007', '20012188', '20019253', '20020128', '20021324', '20021391', '20021392', '20021393', '20022015', '20022403', '20022903',
                    '20023765', '20027095', '20027166', '20027167', '20027185', '20029296', '20029782', '20030188', '20032749', '20032750', '20032806',
                    '20034336', '20034450', '20034536', '20034577', '20035998', '20036047', '20036081', '20036082', '20036892', '20036893', '20038429',
                    '20038430', '20040719', '20041621', '20043626', '20044267', '20045178', '20045418', '20045419', '20045422', '20046211', '20046548',
                    '20047432', '20047965', '20048847', '20048879', '20048880', '20048900', '20049226', '20051952', '20052059', '20052305', '20052306',
                    '20053803', '20054151', '20054951', '20054969', '20059240', '20061748', '20063156', '20067198', '20067473', '20067585', '20070687',
                    '20070688', '20071775', '20071827', '20072006', '20072666', '20072667', '20073425', '20073499', '20074348', '20074349', '20075624') then 'PASCA'
                            when TLKO_PLUOMI in ('10000689', '10000690',
                    '10000688', '20055829', '20043877', '20025487', '10000019', '10000020', '10000021', '10000070', '10000088', '10000102', '10000175',
                    '10000185', '10000206', '10000255', '10000461', '10000555', '10000562', '10000652', '10000665', '10000752', '10000753', '10000861',
                    '10000975', '10001094', '10001826', '10002047', '10002101', '10002246', '10002560', '10002595', '10003427', '10003517', '10003650',
                    '10003995', '10004669', '10004906', '10005073', '10005489', '10005491', '10005599', '10005600', '10006098', '10006890', '10007380',
                    '10007916', '10007970', '10007973', '10008581', '10008819', '10008882', '10010219', '10010220', '10012277', '10012742', '10015141',
                    '10015550', '10016170', '10020713', '10021003', '10021970', '10021972', '10022118', '10023789', '10024188', '10024189', '10025399',
                    '10029331', '10031572', '10033424', '10035165', '10035326', '10036640', '10036647', '10036938', '10036957', '10038277', '10038352',
                    '10038932', '10039897', '10039924', '20000862', '20003786', '20003910', '20003915', '20004229', '20008946', '20008947', '20009722',
                    '20009737', '20010272', '20012678', '20012681', '20013332', '20013433', '20014069', '20014071', '20014623', '20015235', '20015737',
                    '20015838', '20017349', '20018435', '20019673', '20019674', '20021029', '20021032', '20021178', '20021454', '20024644', '20024699',
                    '20024856', '20024857', '20025359', '20027448', '20027551', '20028585', '20028880', '20030547', '20032058', '20033450', '20034079',
                    '20034682', '20034684', '20034685', '20034686', '20035484', '20036157', '20036590', '20037153', '20037159', '20037565', '20039784',
                    '20040799', '20042370', '20042848', '20042859', '20042991', '20044334', '20045104', '20045132', '20045415', '20045674', '20045784',
                    '20046841', '20046842', '20047245', '20047616', '20047617', '20049190', '20052211', '20052296', '20052641', '20052934', '20053099',
                    '20053100', '20055077', '20055078', '20055311', '20055312', '20055801', '20057442', '20057444', '20058084', '20060207', '20062818',
                    '20062912', '20062913', '20063359', '20064556', '20065096', '20067173', '20067174', '20067228', '20067874', '20068905', '20069143',
                    '20069145', '20069149', '20069773', '20070033', '20070253', '20071780', '20071781', '20071980', '20072249', '20076768', '20079847') then 'PUASA'
                            when TLKO_PLUOMI in ('10000700', '10000772', '10010933', '10000704',
                    '10032795', '10032797', '10000352', '20052758', '10010946', '20015819', '10014154', '10036625', '20084983') then 'SYRUP'
                            else '-'
                        end as ITEM_TERKAIT,
                        r.RAKK,
                        flag.flag,
                        TLKO_PLUOMI,
                        PLU,
                        p.PRD_DESKRIPSIPANJANG,
                        p.FRAC,
                        m.TAG_IDM,
                        p.TAG_IGR,
                        k.PKM_PKMT,
                        k.PKM_PKM,
                        k.KOEF,
                        k.LT,
                        ITEM,
                        '' item_rill,
                        MINOR,
                        case
                            when PLU in ('1303350') then 'MINOR 1 KARTON (120 PCS)'
                            when PLU in ('1398740', '1383200') then 'MINOR 1 KARTON (288 PCS)'
                            when PLU in ('1382860') then 'MINOR 1.5 JUTA MIX'
                            when PLU in ('1383000', '1382960', '1382950', '1382980') then 'MINOR 1.5JT MIX ITEM'
                            when PLU in ('1360580', '1280900', '1274090', '1293900') then 'Minor 10 ctn'
                            when PLU in ('1382800', '1322580', '1322590', '1382790') then 'Minor 10 Ctn ( mix item lain )'
                            when PLU in ('1381230') then 'MINOR 100 CTN'
                            when PLU in ('1382260', '1382290', '1274680') then 'Minor 100 Ctn ( Mix item Lain )'
                            when PLU in ('1382300') then 'MINOR 100 CTN ( MIX PLU LAINNYA )'
                            when PLU in ('1529230') then 'MINOR 15 LUSIN'
                            when PLU in ('1277690', '1277730', '1278070', '1041980', '1038740', '1399040', '1277740') then 'Minor 1Jt ( Mix item lain )'
                            when PLU in ('1410500', '1345030', '1182780', '1306750', '1079250', '1302870', '1302850', '1403650', '1163880', '1300460',
                    '1357800', '1450710', '1163770', '1301060', '1300490', '1318770', '1558760', '1168530', '1168510', '1274750',
                    '1232280', '1300470', '1232290', '1300480', '1180730', '1285190', '1345040', '1180720', '1669450', '1318790', '1318780', '1300500', '1180710', '1347940', '1046520', '1063370', '1157110', '1383100', '1301170', '1202100',
                    '1303000', '0433950', '0543990', '1330100', '0898910', '1345050', '1163780', '1163870', '1301160', '0544160', '1339000', '1153900', '1145280', '1087790', '1087800', '1294260', '1318800')
                    then 'Minor 2.4Jt ( Mix Item Lain )'
                            when PLU in ('1382270') then 'MINOR 20 CTN'
                            when PLU in ('1311120', '1278250', '1278240', '1311130', '1381220', '1381160') then 'MINOR 20 CTN ( MIX ITEM LAIN )'
                            when PLU in ('1333910') then 'MINOR 2000 CTN ( NPK IDM )'
                            when PLU in ('1668360', '1311170') then 'Minor 25 Ctn ( Mix item lain )'
                            when PLU in ('1382660', '1356140', '1529820', '1382710') then 'MINOR 250 CTN (GABUNG DENGAN PO IDM)'
                            when PLU in ('1382570') then 'MINOR 3 CTN'
                            when PLU in ('1355980', '1382400', '1381180', '1311230') then 'Minor 30 Ctn ( Mix item Lain )'
                            when PLU in ('1356590', '1356580', '1356600') then 'MINOR 30 CTN ( MIX ITEM LAINNYA )'
                            when PLU in ('1188430') then 'MINOR 420 CTN'
                            when PLU in ('1329130', '1188550') then 'MINOR 50 CTN'
                            when PLU in ('1529710', '1529630', '1529620') then 'MINOR 50 CTN MIX ITEM'
                            when PLU in ('1381240', '1311150') then 'Minor 58 ctn ( Mix item lain )'
                            when PLU in ('1398800') then 'MINOR 6 CTN'
                            when PLU in ('1201830', '1255580') then 'Minor 7.5 Ton ( Mix item Lain )'
                            when PLU in ('1152510''1152520', '1310870', '1152480', '1652800', '1652790', '1152490', '1652780', '1534070', '1334320', '1310880', '1241180', '1554740', '1341180',
                    '1286560', '1217370', '1152500', '1437370', '1437350', '1437360', '1437380', '1534490', '1534080', '1290490', '1340820', '1340830', '1290500') then 'Minor 700-800 Ctn ( Mix item Lain )'
                            else ' '
                        end as KET_MINOR,
                        case
                            when PLU in ('0088010', '0087960', '0088000') then QTY_PB / 1000
                            else QTY_PB
                        end QTYPBb,
                        '0' QTYREAL,
                        case
                            when PLU in ('0088010', '0087960', '0088000') then RUPIAHPB / 1000
                            else RUPIAHPB
                        end RUPIAHPBb,
                        '0' RUPIAHREAL,
                        case
                            when PLU in ('0088010', '0087960', '0088000') then RUPIAHPB / 1000
                            else RUPIAHPB
                        end rp_selisih,
                        coalesce(s.SL_SUP,
                        0) SL_SUPLLIER,
                        n.MPLUS,
                        pl.LKS_MAXPLANO,
                        '' recid4,
                        lppawal.sth_saldoakhir as lpp_awal,
                        lppsekarang.st_saldoakhir as lpp_saatini,
                        vv.NPO,
                        s.po,
                        s.bpb,
                        pl.PLANODPD,
                        tk.PLANOTOKO,
                        case
                            when lppawal.sth_saldoakhir < QTY_PB then 'STOCK < PB'
                            else '-'
                        end as KET_STOCK,
                        '-' KET,
                        kph.ksl_mean as kphmean,
                        TLKO_KETTOLAKAN,
                        case
                            when PLU in ('0047180', '0053250', '0365020',
                    '0365030', '0543000', '0873150', '1116460', '1153700', '1153710', '1236280', '1246360', '1254410', '1271480', '1271490',
                    '1271530', '1311880', '1311900', '1311910', '1311920', '1311930', '1311940', '1311990', '1312000', '1312050', '1312060',
                    '1324710', '1347330', '1367890', '1393260', '1402850', '1430360', '1430370', '1430380', '1430400', '1430390', '1515080', '0009480', '0030680', '0009170', '0033580',
                    '1077740', '0032020', '1580770', '0873130', '0003040', '1481790', '1201240', '0030600', '1474100', '0009380', '1822660', '0003030',
                    '0009190', '0421700', '0032070', '1178690', '1064500', '1271520', '1259590', '1515990', '0539680', '1515990', '1831410', '1456630',
                    '1527420', '1527420', '1430440', '1831410', '1325580', '1831410', '1353150', '1325580') then 'ITEM SEASONAL'
                            when PLU in ('1372770', '1497620', '1514850', '1320300', '1320280', '1320290', '1498650', '1687410', '1687400', '1687390') then 'ITEM PROMO MARKETING'
                            when TLKO_KETTOLAKAN = 'QTY ORDER < MINOR TOKO' then 'QTY ORDER < MINOR TOKO'
                            when TAG_IDM in ('H', 'N', 'O', 'R', 'V') then 'TAG IDM DISCONTINUE (H,N,O,R,V)'
                            when TAG_IDM = 'I' then 'IDM TAG I'
                            when TAG_IDM = 'T' then 'IDM TAG T'
                            when TAG_IDM = 'G' then 'IDM TAG G'
                            when TAG_IGR in ('A', 'H', 'N', 'O', 'X') then 'TAG IGR DISCONTINUE (A,H,N,O,X)'
                            when TAG_IGR = 'G' then 'IGR TAG G'
                            when TAG_IGR = 'T' then 'IGR TAG T'
                            when TAG_IGR = 'I' then 'IGR TAG I'
                            else '-'
                        end as ITEM_SEASONAL,
                        p.DIV,
                        p.DEP,
                        p.KAT,
                        h.HGB_KODESUPPLIER,
                        h.SUP_NAMASUPPLIER
                    from
                        --MASTER--
                    (
                        select
                            SUBSTR(a.PLUU,
                            1,
                            6) || '0' as PLU,
                            a.TLKO_PLUOMI,
                            a.TLKO_KETTOLAKAN,
                            a.ITEM,
                            a.QTY_PB,
                            a.QTY_PB * coalesce(b.COSTT,
                            0) as RUPIAHPB
                        from
                            (
                            select
                                TLKO_PLUIGR as PLUU,
                                TLKO_PLUOMI,
                                TLKO_KETTOLAKAN,
                                tlko_ptag,
                                COUNT(TLKO_PLUOMI) as ITEM,
                                SUM(TLKO_QTYORDER) as QTY_PB
                            from
                                igrpwt.TBTR_TOLAKANPBOMI
                            where
                                DATE(tlko_tglpb) = CURRENT_DATE
                                and TLKO_KODEOMI in (
                                select
                                    TKO_KODEOMI
                                from
                                    igrpwt.tbmaster_tokoigr
                                where
                                    TKO_KODESBU = 'I'
                                )
                            group by
                                TLKO_PLUIGR,
                                TLKO_PLUOMI,
                                TLKO_KETTOLAKAN,
                                tlko_ptag
                        ) a
                        left join
                        (
                            select
                                TLKO_PLUOMI,
                                MAX(TLKO_LASTCOST) as COSTT
                            from
                                igrpwt.TBTR_TOLAKANPBOMI
                            where
                                DATE(TLKO_CREATE_DT) = CURRENT_DATE
                            group by
                                TLKO_PLUOMI
                        ) b
                    on
                            a.TLKO_PLUOMI = b.TLKO_PLUOMI)c
                    left join
                        (
                        select
                            PRD_PRDCD,
                            PRD_KODEDIVISI DIV,
                            PRD_KODEDEPARTEMENT DEP,
                            PRD_KODEKATEGORIBARANG KAT,
                            PRD_KODETAG as TAG_IGR,
                            PRD_MINORDER MINOR,
                            PRD_DESKRIPSIPANJANG,
                            PRD_KODESATUANJUAL2 || '/' || PRD_FRAC FRAC
                        from
                            igrpwt.TBMASTER_PRODMAST)p
                            on
                        PLU = p.PRD_PRDCD
                    left join
                        --PRODCRM--
                    (
                        select
                            PRC_PLUIDM,
                            PRC_PLUIGR,
                            PRC_KODETAG TAG_IDM
                        from
                            igrpwt.TBMASTER_PRODCRM
                        where
                            PRC_GROUP = 'I')m
                            on
                        PLU = m.PRC_PLUIGR
                    left join
                        --PKM--
                    (
                        select
                            PKM_PRDCD,
                            PKM_LEADTIME LT,
                            PKM_PKM,
                            PKM_PKMT,
                            PKM_KOEFISIEN KOEF
                        from
                            igrpwt.TBMASTER_KKPKM)k
                            on
                        PLU = k.PKM_PRDCD
                    left join
                        --M+--
                    (
                        select
                            PKMP_PRDCD,
                            PKMP_QTYMINOR MPLUS
                        from
                            igrpwt.tbmaster_pkmplus)n
                            on
                        PLU = n.PKMP_PRDCD
                        --SUP--
                    left join
                        (
                        select
                            HGB_PRDCD,
                            HGB_KODESUPPLIER,
                            SUP_NAMASUPPLIER
                        from
                            igrpwt.TBMASTER_HARGABELI,
                            igrpwt.TBMASTER_SUPPLIER
                        where
                            SUP_KODESUPPLIER = HGB_KODESUPPLIER
                            and HGB_TIPE = '2')h
                            on
                        PLU = h.HGB_PRDCD
                        --rak--
                    left join
                        (
                        select
                            LKS_PRDCD PLU_RAK,
                            LKS_KODERAK || '.' || LKS_KODESUBRAK || '.' || LKS_TIPERAK || '.' || LKS_SHELVINGRAK || '.' || LKS_NOURUT RAKK
                        from
                            igrpwt.TBMASTER_LOKASI
                        where
                            LKS_TIPERAK in ('N', 'B')
                                and LKS_KODERAK like 'D%'
                                and LKS_KODERAK not like 'DKLIK%')r
                                on
                        PLU = r.PLU_RAK
                    left join
                                (
                        select
                            TPOD_PRDCD PLU_PO,
                            SUM(TPOD_QTYPO) PO,
                            coalesce (SUM(TPOD_QTYPB),
                            0) BPB,
                            ROUND((coalesce (SUM(TPOD_QTYPB),
                            0))/(SUM(TPOD_QTYPO))* 100,
                            0) SL_SUP
                        from
                            igrpwt.TBTR_PO_D
                        where
                            date(TPOD_TGLPO) between (current_date-30) and current_date
                        group by
                            TPOD_PRDCD)s
                            on
                        PLU = s.PLU_PO
                        --PLANO DPD--
                    left join
                        (
                        select
                            LKS_PRDCD DPD,
                            LKS_QTY PLANODPD,
                            LKS_MAXPLANO
                        from
                            igrpwt.tbmaster_lokasi
                        where
                            LKS_PRDCD is not null
                            and LKS_KODERAK like 'D%'
                            and LKS_KODERAK not like 'DPST%'
                            and LKS_NOID is not null)pl
                        on
                        PLU = pl.DPD
                        --plano toko--
                    left join
                        (
                        select
                            LKS_PRDCD TOKO,
                            SUM(LKS_QTY) PLANOTOKO
                        from
                            igrpwt.tbmaster_lokasi
                        where
                            LKS_PRDCD is not null
                            and LKS_KODERAK like 'R%'
                        group by
                            LKS_PRDCD)tk
                            on
                        PLU = tk.TOKO
                        --QTYPB--
                    left join
                        (
                        select
                            PBD_PRDCD PLU_QTYPB,
                            SUM(PBD_QTYPB) QTYPB
                        from
                            igrpwt.TBTR_PB_D
                        where
                            date(PBD_CREATE_DT)= CURRENT_DATE
                        group by
                            PBD_PRDCD)q
                            on
                        PLU = q.PLU_QTYPB
                    left join
                        (
                        select
                            po.PLUPO,
                            MAX(po.NOPO) NPO,
                            MAX(po.TGLPO) TGPO
                        from
                            (
                            select
                                TPOD_PRDCD as PLUPO,
                                TPOH_NOPO as NOPO,
                                TPOD_TGLPO as TGLPO
                            from
                                igrpwt.tbtr_po_h
                            left join igrpwt.tbtr_po_d on
                                TPOD_NOPO = TPOH_NOPO
                            where
                                date(tpoh_tglpo)>= CURRENT_DATE
                                    and TPOH_RECORDID is null)po
                        group by
                            po.PLUPO)vv
                        on
                        PLU = vv.PLUPO
                        left join
                        (select sth_prdcd,sth_lokasi,sth_saldoakhir from igrpwt.tbtr_stockharian where sth_lokasi='01' and date(sth_create_dt)=current_date-1)lppawal
                        on PLU=lppawal.sth_prdcd
                        left join
                        (select st_prdcd,st_saldoakhir from igrpwt.tbmaster_stock where st_lokasi='01')lppsekarang
                        on PLU=lppsekarang.st_prdcd
                        left join
                        (select
                        a.PRD_PRDCD as PLU_FLAG,
                        case
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYYYYY' then 'NAS-IGR+IDM+OMI+MR.BRD+K.IGR+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYYYYN' then 'NAS-IGR+IDM+OMI+MR.BRD+K.IGR'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYYYNN' then 'NAS-IGR+IDM+OMI+MR.BRD'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYYNYY' then 'NAS-IGR+IDM+OMI+K.IGR+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYYNYN' then 'NAS-IGR+IDM+OMI+K.IGR'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYYNNY' then 'NAS-IGR+IDM+OMI+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYYNNN' then 'NAS-IGR+IDM+OMI'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYNYYY' then 'NAS-IGR+IDM+MR.BRD+K.IGR+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYNNYY' then 'NAS-IGR+IDM+K.IGR+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYNNYN' then 'NAS-IGR+IDM+K.IGR'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYNNNY' then 'NAS-IGR+IDM+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYNNNN' then 'NAS-IGR+IDM'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYNYNYY' then 'NAS-IGR+OMI+K.IGR+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYNYNYN' then 'NAS-IGR+OMI+K.IGR'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYNYNNY' then 'NAS-IGR+OMI+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYNYNNN' then 'NAS-IGR+OMI'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYNNYYN' then 'NAS-IGR+MR.BRD+K.IGR'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYNNYNN' then 'NAS-IGR+MR.BRD'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYNNNYY' then 'NAS-IGR+K.IGR+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYNNNYN' then 'NAS-IGR+K.IGR'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYNNNNY' then 'NAS-IGR+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYNNNNN' then 'NAS-IGR'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNYYNYY' then 'NAS-IDM+OMI+K.IGR+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNYYNYN' then 'NAS-IDM+OMI+K.IGR'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNYYNNY' then 'NAS-IDM+OMI+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNYYNNN' then 'NAS-IDM+OMI'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNYNNYY' then 'NAS-IDM+K.IGR+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNYNNYN' then 'NAS-IDM+K.IGR'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNYNNNY' then 'NAS-IDM+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNYNNNN' then 'NAS-IDM'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNNYYNN' then 'NAS-OMI+MR.BRD'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNNYNYN' then 'NAS-OMI+K.IGR'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNNYNNN' then 'NAS-OMI'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNNNYNN' then 'NAS-MR.BRD'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNNNNYN' then 'NAS-K.IGR'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNNNNNN' then 'NAS'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYYYYYY' then 'IGR+IDM+OMI+MR.BRD+K.IGR+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYYYYYN' then 'IGR+IDM+OMI+MR.BRD+K.IGR'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYYYNYY' then 'IGR+IDM+OMI+K.IGR+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYYYNYN' then 'IGR+IDM+OMI+K.IGR'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYYYNNY' then 'IGR+IDM+OMI+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYYYNNN' then 'IGR+IDM+OMI'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYYNNYY' then 'IGR+IDM+K.IGR+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYYNNYN' then 'IGR+IDM+K.IGR'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYYNNNY' then 'IGR+IDM+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYYNNNN' then 'IGR+IDM'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYNYNYY' then 'IGR+OMI+K.IGR+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYNYNYN' then 'IGR+OMI+K.IGR'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYNYNNN' then 'IGR+OMI'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYNNYYN' then 'IGR+MR.BRD+K.IGR'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYNNNYY' then 'IGR+K.IGR+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYNNNYN' then 'IGR+K.IGR'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYNNNNY' then 'IGR+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYNNNNN' then 'IGR'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NNYYNYY' then 'IDM+OMI+K.IGR+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NNYYNYN' then 'IDM+OMI+K.IGR'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NNYYNNY' then 'IDM+OMI+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NNYYNNN' then 'IDM+OMI'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NNYNNYY' then 'IDM+K.IGR+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NNYNNYN' then 'IDM+K.IGR'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NNYNNNY' then 'IDM+DEPO'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NNYNNNN' then 'IDM'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NNNYNYN' then 'OMI+K.IGR'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NNNYNNN' then 'OMI'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NNNNNYN' then 'K.IGR'
                            when NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NNNNNNN' then 'TIDAK PUNYA FLAG'
                            else 'BELUM ADA FLAG'
                        end as FLAG
                    from
                        (
                        select
                            prd_prdcd,
                            prd_plumcg,
                            coalesce(PRD_FLAGNAS,
                            'N') as NAS,
                            coalesce(PRD_FLAGIGR,
                            'N') as IGR,
                            coalesce(PRD_FLAGIDM,
                            'N') as IDM,
                            coalesce(PRD_FLAGOMI,
                            'N') as OMI,
                            coalesce(PRD_FLAGBRD,
                            'N') as BRD,
                            coalesce(PRD_FLAGOBI,
                            'N') as K_IGR,
                            case
                                when prd_plumcg in (
                                select
                                    PLUIDM
                                from
                                    igrpwt.DEPO_LIST_IDM ) then 'Y'
                                else 'N'
                            end as DEPO
                        from
                            igrpwt.TBMASTER_PRODMAST 
                        where
                            PRD_PRDCD like '%0'
                            and PRD_DESKRIPSIPANJANG is not null)a)flag
                            on plu=flag.PLU_FLAG
                            left join
                            (SELECT 
                    TO_CHAR(
                        TO_DATE(
                        CASE 
                            WHEN LENGTH(pid) = 5 THEN 
                            '01-' || LPAD(LEFT(pid, 1), 2, '0') || '-' || RIGHT(pid, 4)
                            WHEN LENGTH(pid) = 6 THEN 
                            '01-' || LEFT(pid, 2) || '-' || RIGHT(pid, 4)
                        END,
                        'DD-MM-YYYY'
                        ),
                        'Month YYYY'
                    ) AS bulan_tahun,
                    prdcd,
                    ksl_mean
                    FROM igrpwt.tbmaster_kph
                    WHERE 
                    TO_DATE(
                        CASE 
                        WHEN LENGTH(pid) = 5 THEN 
                            '01-' || LPAD(LEFT(pid, 1), 2, '0') || '-' || RIGHT(pid, 4)
                        WHEN LENGTH(pid) = 6 THEN 
                            '01-' || LEFT(pid, 2) || '-' || RIGHT(pid, 4)
                        END,
                        'DD-MM-YYYY'
                    ) = (
                        SELECT MAX(
                        TO_DATE(
                            CASE 
                            WHEN LENGTH(pid) = 5 THEN 
                                '01-' || LPAD(LEFT(pid, 1), 2, '0') || '-' || RIGHT(pid, 4)
                            WHEN LENGTH(pid) = 6 THEN 
                                '01-' || LEFT(pid, 2) || '-' || RIGHT(pid, 4)
                            END,
                            'DD-MM-YYYY'
                        )
                        )
                        FROM igrpwt.tbmaster_kph
                    )
                    )kph
                            on tlko_pluomi=kph.prdcd
                            WHERE $filter
                ");
            }, 200); // retry 3x, delay 200ms

            return view('edp.tolakanidm_table', compact('data'));

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
