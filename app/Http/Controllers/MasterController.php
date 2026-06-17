<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class MasterController extends Controller
{
    public function tokoIdm()
    {
        $tokoIdm = DB::select("SELECT *
                                        FROM (
                                            SELECT 
                                                cls_kode || cls_group AS cluster_idm,
                                                cls_toko AS kode_toko,
                                                cls_jarakkirim AS jarak_kirim,
                                                cls_mobil AS mobil,
                                                Case 
                                                            WHEN cls_kode = 'A'
                                                                Then 'SENIN - RABU - JUMAT' 
                                                            When cls_kode = 'B'
                                                                THEN 'SELASA - KAMIS - SABTU' 
                                                            ELSE 'TOKO BARU BELUM ADA JADWAL/TOKO TUTUP SEMENTARA' 
                                                            END hari_kirim,
                                                Case 
                                                                WHEN CLS_KODE IN ('A','B') 
                                                                        AND CAST(CLS_GROUP AS INT) % 2 = 0
                                                                THEN 'RIT 2' 
                                                                WHEN CLS_KODE IN ('A','B') 
                                                                        AND CAST(CLS_GROUP AS INT) % 2 = 1
                                                                THEN 'RIT 1' 
                                                                ELSE 'TOKO BARU BELUM ADA RITASE/TOKO TUTUP SEMENTARA' 
                                                            END ritase,
                                                Case 
                                                            WHEN cls_toko in ('F2LA',
																				'F4EW',
																				'FD8I',
																				'FP37',
																				'FQ8Z',
																				'FUT4',
                                                                                'FKPL',
																				'T2JE',
																				'T2W6',
																				'T3PT',
																				'T49C',
																				'T4UU',
																				'T5DE',
																				'T60X',
																				'T6A4',
																				'T72X',
																				'T87R',
																				'T9WU',
																				'TAWK',
																				'TCLW',
																				'TEEI',
																				'TFE0',
																				'TG4F',
																				'TGDJ',
																				'TGID',
																				'TJCY',
																				'TK4X',
																				'TKFI',
																				'TM4N',
																				'TQN7',
																				'TS69',
																				'TS75',
																				'TSGW',
																				'TTH5',
																				'TU27',
																				'TVQM',
																				'TWHV'
																				) THEN 'AGP'
                                                            WHEN cls_toko in ('T4T8',
																				'TD8P',
																				'TRB7') 
																			  THEN 'DKI'
                                                            WHEN cls_toko in ('F5W1',
																				'FK93',
																				'T67E',
																				'T8SE',
																				'TF8F',
																				'TO74',
																				'TSM3',
																				'TYVX'
																				) THEN 'GHO'
                                                            WHEN cls_toko in ('FSA5',
																				'THHS',
																				'TI5P',
																				'TL5Q',
																				'TUM8'
																				) THEN 'IRH'
                                                            WHEN cls_toko in ('F2R2',
																				'TCBO',
																				'TH4T'
																				) THEN 'IWH'
                                                            WHEN cls_toko in ('F0KA',
																				'F27K',
																				'F2J1',
																				'F2L2',
																				'F2P2',
																				'F2TT',
																				'F33T',
																				'F3CN',
																				'F42J',
																				'F4HQ',
																				'F5NW',
																				'F5RT',
																				'F632',
																				'F66P',
																				'F8U8',
																				'F9FJ',
																				'FEQ7',
																				'FG3N',
																				'FM6D',
																				'FQ4T',
																				'FTA7',
																				'FUD5',
																				'FYD7',
																				'T09H',
																				'T0BD',
																				'T1OK',
																				'T1QF',
																				'T21P',
																				'T2HD',
																				'T2W8',
																				'T3TZ',
																				'T4C5',
																				'T4S9',
																				'T4YJ',
																				'T5AU',
																				'T5JM',
																				'T6AW',
																				'T8JO',
																				'T94E',
																				'T9DB',
																				'T9U5',
																				'T9YF',
																				'TBYM',
																				'TDHU',
																				'TF1M',
																				'TG7X',
																				'TG89',
																				'THJ3',
																				'TKG4',
																				'TL8S',
																				'TMC9',
																				'TN1V',
																				'TRA4',
																				'TW2K',
																				'TY7Y'
																				) THEN 'PJO'
                                                            WHEN cls_toko in ('F09U',
																				'F55B',
																				'F5Z7',
																				'F6E3',
																				'F9LJ',
																				'FF47',
																				'FGED',
																				'FH7U',
																				'FI4Q',
																				'FW4L',
																				'FXJO',
																				'T0VM',
																				'T2TQ',
																				'T3HU',
																				'T3K4',
																				'T4Z6',
																				'T5YM',
																				'T6KM',
																				'T71S',
																				'T77V',
																				'T79U',
																				'T7MS',
																				'T83W',
																				'T870',
                                                                                'T8E9',
																				'T8RS',
																				'T9J1',
																				'T9P2',
																				'TAE4',
																				'TCO1',
                                                                                'TD4A',
																				'TG0B',
																				'TING',
																				'TKB6',
																				'TL94',
																				'TM78',
																				'TNVF',
																				'TPLE',
																				'TPLP',
																				'TST6',
																				'TU3W',
																				'TWDA',
																				'TYN5'
																				) THEN 'RDO'
                                                            WHEN cls_toko in ('F2PP',
																				'T4YM',
																				'T5SN',
																				'T8XL',
																				'TKL5',
																				'TS65',
																				'TY52',
																				'TZSB'
																				) THEN 'SKA'
                                                            ELSE 'UNKNOWN'
                                                        END AM,
                                                CASE    WHEN cls_toko in ('F2J1',
                                                                            'F2L2',
                                                                            'F8U8',
                                                                            'T1QF',
                                                                            'T21P',
                                                                            'T4S9',
                                                                            'T9DB',
                                                                            'TG7X',
                                                                            'TG89',
                                                                            'TW2K',
                                                                            'TY7Y'
																			) THEN 'AGS'
                                                        WHEN cls_toko in ('F2LA',
                                                                            'F4EW',
                                                                            'FD8I',
                                                                            'T2W6',
                                                                            'T60X',
                                                                            'T6A4',
                                                                            'T72X',
                                                                            'TCLW',
                                                                            'TG4F',
                                                                            'TK4X',
                                                                            'TQN7',
                                                                            'TVQM'
																			) THEN 'AMA'
                                                        WHEN cls_toko in ('F4HQ',
                                                                            'F5NW',
                                                                            'F632',
                                                                            'F9FJ',
                                                                            'FTA7',
                                                                            'T2W8',
                                                                            'T3TZ',
                                                                            'T5AU',
                                                                            'T5JM',
                                                                            'THJ3',
                                                                            'TMC9'
																			) THEN 'AMF'
                                                        WHEN cls_toko in ('F2R2',
                                                                            'TCBO',
                                                                            'TH4T'
																			) THEN 'AYU'
                                                        WHEN cls_toko in ('F2PP',
																			'T4YM',
																			'T8XL',
																			'TS65',
																			'TY52',
																			'TZSB'
																			) THEN 'BIO'
                                                        WHEN cls_toko in ('T5SN',
																			'TKL5'
																			) THEN 'FWN'
                                                        WHEN cls_toko in ('T2JE',
																			'T3PT',
																			'T49C',
																			'T87R',
																			'TEEI',
																			'TS75',
																			'TU27'
																			) THEN 'IPO'
                                                        WHEN cls_toko in ('F0KA',
																			'F3CN',
																			'F42J',
																			'FG3N',
																			'FM6D',
																			'FQ4T',
																			'FUD5',
																			'FYD7',
																			'T09H',
																			'TF1M',
																			'TRA4'
																			) THEN 'MJB'
                                                        WHEN cls_toko in ('T0VM',
                                                                            'T3K4',
                                                                            'T4Z6',
                                                                            'T79U',
                                                                            'TD4A',
                                                                            'TKB6',
                                                                            'TL94',
                                                                            'TPLE'
																			) THEN 'MLK'
                                                        WHEN cls_toko in ('FKPL',
                                                                            'T9WU',
                                                                            'TFE0',
                                                                            'TJCY',
                                                                            'TM4N',
                                                                            'TS69',
                                                                            'TSGW',
                                                                            'TWHV'
                                                                            ) THEN 'MSI'
                                                        WHEN cls_toko in ('FP37',
																			'FQ8Z',
																			'FUT4',
																			'T4UU',
																			'T5DE',
																			'TAWK',
																			'TGDJ',
																			'TGID',
																			'TKFI',
																			'TTH5') THEN 'MSN'
                                                        WHEN cls_toko in ('TF8F',
                                                                            'TSM3',
                                                                            'TYVX'
                                                                            ) THEN 'RHO'
                                                        WHEN cls_toko in ('T4T8',
																			'TD8P',
																			'TRB7'
																			) THEN 'SBR'
                                                        WHEN cls_toko in ('F5W1',
                                                                            'FK93',
                                                                            'T67E',
                                                                            'T8SE',
                                                                            'TO74'
																			) THEN 'SFA'
                                                        WHEN cls_toko in ('F2P2',
																			'F33T',
																			'FEQ7',
																			'T2HD',
																			'T4YJ',
																			'T6AW',
																			'T9YF',
																			'TBYM',
																			'TDHU',
																			'TL8S',
																			'TN1V'
																			) THEN 'SFN'
                                                        WHEN cls_toko in ('F55B',
                                                                            'F6E3',
                                                                            'F9LJ',
                                                                            'FH7U',
                                                                            'T3HU',
                                                                            'T9J1',
                                                                            'TG0B',
                                                                            'TM78',
                                                                            'TNVF',
                                                                            'TWDA'
																			) THEN 'SJO'
                                                        WHEN cls_toko in ('FSA5',
																			'THHS',
																			'TI5P',
																			'TL5Q',
																			'TUM8'
																			) THEN 'STH'
                                                        WHEN cls_toko in ('F09U',
                                                                            'T9P2',
                                                                            'FXJO',
																			'T77V',
																			'T7MS',
																			'TAE4',
																			'TCO1',
																			'TING',
																			'TST6'
																			) THEN 'TJO'
                                                        WHEN cls_toko in ('FI4Q',
                                                                            'FW4L',
                                                                            'T2TQ',
                                                                            'T5YM',
                                                                            'T6KM',
                                                                            'TU3W'
																			) THEN 'TSO'
                                                        WHEN cls_toko in ('F27K',
																			'F2TT',
																			'F5RT',
																			'F66P',
																			'T0BD',
																			'T1OK',
																			'T4C5',
																			'T8JO',
																			'T94E',
																			'T9U5',
																			'TKG4'
																			) THEN 'YPS'
                                                        WHEN cls_toko in ('F5Z7',
                                                                            'FF47',
                                                                            'FGED',
                                                                            'T71S',
                                                                            'T83W',
                                                                            'T870',
                                                                            'T8E9',
                                                                            'T8RS',
                                                                            'TPLP',
                                                                            'TYN5'
																			) THEN 'ZEN'
                                                        ELSE 'UNKNOWN'
                                                    END ASS
                                            FROM igrpwt.cluster_idm
                                        ) AS AA
                                        LEFT JOIN (
                                            SELECT 
                                                tko_kodeomi,
                                                tko_namaomi AS nama,
                                                tko_kodecustomer AS kode_member,
                                                tko_tglgo as tanggal_go
                                            FROM igrpwt.tbmaster_tokoigr
                                        ) AS BB
                                        ON AA.kode_toko = BB.tko_kodeomi
                                        ORDER BY kode_toko");
        return view('master.tokoidm', compact('tokoIdm'));
    }

    public function tokoOmi()
    {
        $tokoOmi = DB::select("SELECT
                                    CUS_KODEMEMBER,
                                    Cus_Namamember,
                                    Tko_Kodeomi,
                                    Tko_Namaomi,
                                    CUS_ALAMATMEMBER1,
                                    Cus_Npwp,
                                    CUS_ALAMATEMAIL,
                                    CUS_NOKTP,
                                    CASE 
                                        WHEN CUS_FLAGPKP='Y' THEN 'OMI PKP' ELSE 'OMI NON PKP' 
                                    END AS keterangan
                                FROM igrpwt.Tbmaster_Customer 
                                INNER JOIN igrpwt.Tbmaster_Tokoigr
                                    ON Cus_Kodemember = Tko_Kodecustomer
                                WHERE Tko_Kodesbu='O'");
        return view('master.tokoomi', compact('tokoOmi'));
    }

    public function pludpd()
    {
        $pludpd = DB::select("SELECT 
                                        PRD_PRDCD AS PLU_IGR,
                                        PRD_PLUMCG AS PLU_IDM,
                                        PRC_PLUOMI AS PLU_OMI,
                                        RAK_GUDANG,
                                        RAK_TOKO,
                                        prd_deskripsipanjang AS DESKRIPSI,
                                        prd_unit AS UNIT,
                                        prd_frac AS FRAC,
                                        pkm_mindisplay as MINDIS,
                                        prd_minorder as MINOR,
                                        minoridm as MINORIDM,
                                        minoromi as MINOROMI,
                                        CASE
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYYYYY' THEN 'NAS-IGR+IDM+OMI+MR.BRD+K.IGR+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYYYYN' THEN 'NAS-IGR+IDM+OMI+MR.BRD+K.IGR'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYYYNN' THEN 'NAS-IGR+IDM+OMI+MR.BRD'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYYNYY' THEN 'NAS-IGR+IDM+OMI+K.IGR+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYYNYN' THEN 'NAS-IGR+IDM+OMI+K.IGR'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYYNNY' THEN 'NAS-IGR+IDM+OMI+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYYNNN' THEN 'NAS-IGR+IDM+OMI'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYNYYY' THEN 'NAS-IGR+IDM+MR.BRD+K.IGR+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYNNYY' THEN 'NAS-IGR+IDM+K.IGR+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYNNYN' THEN 'NAS-IGR+IDM+K.IGR'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYNNNY' THEN 'NAS-IGR+IDM+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYYNNNN' THEN 'NAS-IGR+IDM'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYNYNYY' THEN 'NAS-IGR+OMI+K.IGR+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYNYNYN' THEN 'NAS-IGR+OMI+K.IGR'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYNYNNY' THEN 'NAS-IGR+OMI+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYNYNNN' THEN 'NAS-IGR+OMI'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYNNYYN' THEN 'NAS-IGR+MR.BRD+K.IGR'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYNNYNN' THEN 'NAS-IGR+MR.BRD'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYNNNYY' THEN 'NAS-IGR+K.IGR+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYNNNYN' THEN 'NAS-IGR+K.IGR'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYNNNNY' THEN 'NAS-IGR+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YYNNNNN' THEN 'NAS-IGR'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNYYNYY' THEN 'NAS-IDM+OMI+K.IGR+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNYYNYN' THEN 'NAS-IDM+OMI+K.IGR'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNYYNNY' THEN 'NAS-IDM+OMI+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNYYNNN' THEN 'NAS-IDM+OMI'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNYNNYY' THEN 'NAS-IDM+K.IGR+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNYNNYN' THEN 'NAS-IDM+K.IGR'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNYNNNY' THEN 'NAS-IDM+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNYNNNN' THEN 'NAS-IDM'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNNYYNN' THEN 'NAS-OMI+MR.BRD'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNNYNYN' THEN 'NAS-OMI+K.IGR'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNNYNNN' THEN 'NAS-OMI'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNNNYNN' THEN 'NAS-MR.BRD'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNNNNYN' THEN 'NAS-K.IGR'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'YNNNNNN' THEN 'NAS'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYYYNYY' THEN 'IGR+IDM+OMI+K.IGR+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYYYNYN' THEN 'IGR+IDM+OMI+K.IGR'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYYYNNY' THEN 'IGR+IDM+OMI+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYYYNNN' THEN 'IGR+IDM+OMI'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYYNNYY' THEN 'IGR+IDM+K.IGR+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYYNNYN' THEN 'IGR+IDM+K.IGR'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYYNNNY' THEN 'IGR+IDM+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYYNNNN' THEN 'IGR+IDM'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYNYNYY' THEN 'IGR+OMI+K.IGR+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYNYNYN' THEN 'IGR+OMI+K.IGR'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYNYNNN' THEN 'IGR+OMI'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYNNYYN' THEN 'IGR+MR.BRD+K.IGR'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYNNNYY' THEN 'IGR+K.IGR+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYNNNYN' THEN 'IGR+K.IGR'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYNNNNY' THEN 'IGR+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYNNNNN' THEN 'IGR'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NNYYNYY' THEN 'IDM+OMI+K.IGR+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NNYYNYN' THEN 'IDM+OMI+K.IGR'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NNYYNNY' THEN 'IDM+OMI+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NNYYNNN' THEN 'IDM+OMI'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NNYNNYY' THEN 'IDM+K.IGR+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NNYNNYN' THEN 'IDM+K.IGR'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NNYNNNY' THEN 'IDM+DEPO'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NNYNNNN' THEN 'IDM'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NNNYNYN' THEN 'OMI+K.IGR'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NNNYNNN' THEN 'OMI'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NNNNNNN' THEN 'BELUM ADA FLAG'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYNYYYN' THEN 'IGR+OMI+MR.BRD+K.IGR'
                                            WHEN NAS || IGR || IDM || OMI || BRD || K_IGR || DEPO = 'NYYYYYN' THEN 'IGR+IDM+OMI+MR.BRD+K.IGR'
                                            ELSE 'BELUM ADA FLAG'
                                        END AS FLAG,
                                        prd_kodetag as TAG_IGR,
                                        tagidm as TAG_IDM,
                                        tagomi as TAG_OMI,
                                        AD.LPP,
                                        AB.PLANO_GUDANG,
                                        AC.PLANO_TOKO,
                                        prd_avgcost as ACOST,
                                        prd_perlakuanbarang AS STATUS,
                                        COALESCE(PKM_PKM,0) PKM,
                                        COALESCE(PKM_PKMT,0) PKMT,
                                        COALESCE(PKMP_MPLUSI,0) MPLUSI,
                                        COALESCE(PKMP_MPLUSO,0) MPLUSO,
                                        COALESCE(PKMP_MPLUSK,0) MPLUSK,
                                        COALESCE(PKMP_MPLUST,0) MPLUST,
                                        DSI,
                                        round(((coalesce(avgsls.sls_a,0) + coalesce(avgsls.sls_b,0) + coalesce(avgsls.sls_c,0))/3)) TOTAVGSLS,
                                        round(coalesce(rkp.rekap_idm,0),0) AVGIDM,
                                        COALESCE(out_qty,0) POOUT,
                                        COALESCE(RECID4.qty_realisasi,0) qtyrecid4
                                    FROM (
                                        SELECT 
                                            prd_prdcd,
                                            prd_deskripsipanjang,
                                            prd_plumcg,
                                            prd_unit,
                                            prd_frac,
                                            prd_perlakuanbarang,
                                            prd_kodetag,
                                            prd_avgcost,
                                            prd_minorder,
                                            COALESCE(PRD_FLAGNAS, 'N') AS NAS,
                                            COALESCE(PRD_FLAGIGR, 'N') AS IGR,
                                            COALESCE(PRD_FLAGIDM, 'N') AS IDM,
                                            COALESCE(PRD_FLAGOMI, 'N') AS OMI,
                                            COALESCE(PRD_FLAGBRD, 'N') AS BRD,
                                            COALESCE(PRD_FLAGOBI, 'N') AS K_IGR,
                                            CASE 
                                                WHEN prd_plumcg IN (SELECT Pluidm FROM IGRPWT.Depo_List_Idm) THEN 'Y' 
                                                ELSE 'N' 
                                            END AS Depo
                                        FROM 
                                            IGRPWT.Tbmaster_Prodmast 
                                        WHERE 
                                            prd_prdcd LIKE '%0' 
                                            AND prd_deskripsipanjang IS NOT NULL
                                    ) AS aa
                                    LEFT JOIN
                                    (select prc_pluigr,prc_pluomi,prc_minorder as minoromi,prc_minorderomi,prc_maxorderomi,prc_kodetag as tagomi
                                    from IGRPWT.tbmaster_prodcrm where prc_group = 'O') omi on prd_prdcd = omi.prc_pluigr
                                    left join
                                    (select prc_pluigr as pluigr,prc_pluidm,prc_minorder as minoridm,prc_kodetag tagidm from IGRPWT.tbmaster_prodcrm where prc_group = 'I') idm on prd_prdcd = idm.pluigr
                                    LEFT JOIN (
                                        SELECT 
                                            LKS_PRDCD, 
                                            LKS_KODERAK || '.' || LKS_KODESUBRAK || '.' || LKS_TIPERAK || '.' || LKS_SHELVINGRAK || '.' || LKS_NOURUT AS RAK_GUDANG,
                                            lks_qty AS PLANO_GUDANG 
                                        FROM 
                                            IGRPWT.Tbmaster_Lokasi 
                                        WHERE 
                                            SUBSTR(Lks_Koderak, 1, 1) = 'D' 
                                            AND Lks_Tiperak = 'B' 
                                            AND Lks_Prdcd IS NOT NULL
                                    ) AS AB ON AA.PRD_PRDCD = AB.LKS_PRDCD
                                    LEFT JOIN (
                                        SELECT 
                                            LKS_PRDCD, 
                                            LKS_KODERAK || '.' || LKS_KODESUBRAK || '.' || LKS_TIPERAK || '.' || LKS_SHELVINGRAK || '.' || LKS_NOURUT AS RAK_TOKO,
                                            lks_qty AS PLANO_TOKO 
                                        FROM 
                                            IGRPWT.Tbmaster_Lokasi 
                                        WHERE 
                                            SUBSTR(Lks_Koderak, 1, 1) IN ('R', 'O') 
                                            AND SUBSTR(Lks_Tiperak, 1, 1) IN ('B', 'I') 
                                            AND Lks_Prdcd IS NOT NULL
                                    ) AS AC ON AA.PRD_PRDCD = AC.LKS_PRDCD
                                    LEFT JOIN (
                                        SELECT 
                                            ST_PRDCD, 
                                            ST_SALDOAKHIR AS LPP,
                                            CASE
                                                  WHEN ST_SALDOAKHIR > 0 AND ST_SALES > 0
                                                  THEN ROUND((((COALESCE(ST_SALDOAWAL,0)+COALESCE(ST_SALDOAKHIR,0))/2)/COALESCE(ST_SALES,0))*(EXTRACT(DAY FROM CURRENT_DATE)))
                                                  ELSE 0
                                                END DSI
                                        FROM 
                                            IGRPWT.TBMASTER_STOCK 
                                        WHERE 
                                            ST_LOKASI = '01'
                                    ) AS AD ON AA.PRD_PRDCD = AD.ST_PRDCD 
                                    LEFT JOIN (SELECT * FROM igrpwt.TBMASTER_KKPKM) km ON AA.PRD_PRDCD = km.PKM_PRDCD
                                    LEFT JOIN (SELECT PKMP_PRDCD, PKMP_QTYMINOR,
                                                                    PKMP_MPLUSI,
                                                                    PKMP_MPLUSO,
                                                                    PKMP_MPLUSK,
                                                                    PKMP_MPLUST 
                                                                    FROM igrpwt.TBMASTER_PKMPLUS) mp ON AA.PRD_PRDCD = mp.PKMP_PRDCD
                                    LEFT JOIN (SELECT sls_prdcd AS plusls,
                                                        -- 3 bulan lalu
                                                        SUM(CASE WHEN date_trunc('month', sls_periode) = date_trunc('month', current_date) - interval '3 month'
                                                            THEN (COALESCE(sls_qtyomi,0)+COALESCE(sls_qtynomi,0)+COALESCE(sls_qtyidm,0)) END) AS sls_a,
                                                        SUM(CASE WHEN date_trunc('month', sls_periode) = date_trunc('month', current_date) - interval '3 month'
                                                            THEN (COALESCE(sls_netomi,0)+COALESCE(sls_netnomi,0)+COALESCE(sls_netidm,0)) END) AS rph_a,
                                                        -- 2 bulan lalu
                                                        SUM(CASE WHEN date_trunc('month', sls_periode) = date_trunc('month', current_date) - interval '2 month'
                                                            THEN (COALESCE(sls_qtyomi,0)+COALESCE(sls_qtynomi,0)+COALESCE(sls_qtyidm,0)) END) AS sls_b,
                                                        SUM(CASE WHEN date_trunc('month', sls_periode) = date_trunc('month', current_date) - interval '2 month'
                                                            THEN (COALESCE(sls_netomi,0)+COALESCE(sls_netnomi,0)+COALESCE(sls_netidm,0)) END) AS rph_b,
                                                        -- 1 bulan lalu
                                                        SUM(CASE WHEN date_trunc('month', sls_periode) = date_trunc('month', current_date) - interval '1 month'
                                                            THEN (COALESCE(sls_qtyomi,0)+COALESCE(sls_qtynomi,0)+COALESCE(sls_qtyidm,0)) END) AS sls_c,
                                                        SUM(CASE WHEN date_trunc('month', sls_periode) = date_trunc('month', current_date) - interval '1 month'
                                                            THEN (COALESCE(sls_netomi,0)+COALESCE(sls_netnomi,0)+COALESCE(sls_netidm,0)) END) AS rph_c,
                                                        -- bulan ini
                                                        SUM(CASE WHEN date_trunc('month', sls_periode) = date_trunc('month', current_date)
                                                            THEN (COALESCE(sls_qtyomi,0)+COALESCE(sls_qtynomi,0)+COALESCE(sls_qtyidm,0)) END) AS sls_bln_ini,
                                                        SUM(CASE WHEN date_trunc('month', sls_periode) = date_trunc('month', current_date)
                                                            THEN (COALESCE(sls_netomi,0)+COALESCE(sls_netnomi,0)+COALESCE(sls_netidm,0)) END) AS rph_bln_ini
                                                        FROM igrpwt.tbtr_sumsales
                                                        WHERE sls_periode BETWEEN date_trunc('month', current_date) - interval '3 month' AND current_date
                                                        GROUP BY sls_prdcd) avgsls on AA.PRD_PRDCD = avgsls.plusls
                                    left join (WITH bulan_referensi AS (
                                                                            -- Ambil 3 bulan terakhir sebelum bulan ini
                                                                            SELECT to_char(d::date, 'MM')::int AS bulan,
                                                                                   to_char(d::date, 'YYYY')::int AS tahun
                                                                            FROM generate_series(
                                                                                     date_trunc('month', current_date) - interval '3 month',
                                                                                     date_trunc('month', current_date) - interval '1 month',
                                                                                     interval '1 month'
                                                                                 ) d
                                                                            ),
                                                                            unpivot AS (
                                                                                SELECT rsl_prdcd,
                                                                                   rsl_group,
                                                                                   gs.bulan,
                                                                                   gs.tahun,
                                                                                   CASE gs.bulan
                                                                                       WHEN 1  THEN coalesce(rsl_qty_01,0)
                                                                                       WHEN 2  THEN coalesce(rsl_qty_02,0)
                                                                                       WHEN 3  THEN coalesce(rsl_qty_03,0)
                                                                                       WHEN 4  THEN coalesce(rsl_qty_04,0)
                                                                                       WHEN 5  THEN coalesce(rsl_qty_05,0)
                                                                                       WHEN 6  THEN coalesce(rsl_qty_06,0)
                                                                                       WHEN 7  THEN coalesce(rsl_qty_07,0)
                                                                                       WHEN 8  THEN coalesce(rsl_qty_08,0)
                                                                                       WHEN 9  THEN coalesce(rsl_qty_09,0)
                                                                                       WHEN 10 THEN coalesce(rsl_qty_10,0)
                                                                                       WHEN 11 THEN coalesce(rsl_qty_11,0)
                                                                                       WHEN 12 THEN coalesce(rsl_qty_12,0)
                                                                                   END AS qty
                                                                                FROM igrpwt.tbtr_rekapsalesbulanan r
                                                                                CROSS JOIN bulan_referensi gs
                                                                            )
                                                                            SELECT rsl_prdcd,
                                                                               SUM(CASE WHEN rsl_group = '01' THEN qty END) / 3 AS rekap_biru,
                                                                               SUM(CASE WHEN rsl_group = '02' THEN qty END) / 3 AS rekap_omi,
                                                                               SUM(CASE WHEN rsl_group = '03' THEN qty END) / 3 AS rekap_merah,
                                                                               SUM(CASE WHEN rsl_group = '04' THEN qty END) / 3 AS rekap_idm
                                                                            FROM unpivot
                                                                            GROUP BY rsl_prdcd
                                                                            ORDER BY rsl_prdcd) rkp on AA.PRD_PRDCD = rkp.rsl_prdcd
                                    left join (SELECT TPOD_PRDCD AS out_plu,   
                                                        SUM(TPOD_QTYPO)  AS out_qty   
                                                        FROM igrpwt.tbtr_po_h   
                                                        JOIN igrpwt.tbtr_po_d   ON tpoh_nopo                       =tpod_nopo   
                                                        WHERE (TO_DATE(TO_CHAR(TPOH_TGLPO, 'DD-MON-YYYY'), 'DD-MON-YYYY') + TPOH_JWPB) >= CURRENT_DATE
                                                        AND (tpoh_recordid                 IS NULL OR TPOH_RECORDID = 'X')
                                                        GROUP BY TPOD_PRDCD) POOUT on AA.PRD_PRDCD = POOUT.out_plu
                                    LEFT JOIN (SELECT  
                                                substr(a.PBO_PLUIGR, 1, 6) || '0' AS pluigr,
                                                SUM(a.PBO_QTYREALISASI) AS qty_realisasi
                                            FROM igrpwt.tbmaster_pbomi a
                                            LEFT JOIN igrpwt.tbtr_realpb b
                                                ON  a.PBO_NOKOLI = b.RPB_NOKOLI
                                                AND a.PBO_KODEOMI = b.RPB_KODEOMI
                                                AND a.PBO_PLUIGR  = b.RPB_PLU2
                                                AND a.PBO_QTYREALISASI = b.RPB_QTYREALISASI
                                            WHERE a.PBO_NOKOLI IS NOT NULL
                                            AND a.PBO_RECORDID = '4'
                                            AND a.PBO_CREATE_DT::date >= (CURRENT_DATE - INTERVAL '7 days')
                                            AND b.RPB_NOKOLI IS NULL  -- data yang belum ada di realpb
                                            GROUP BY 1) RECID4 on AA.PRD_PRDCD = substr(RECID4.pluigr, 1, 6) || '0'
                                    WHERE 
                                        RAK_GUDANG IS NOT NULL 
                                    ORDER BY 
                                        1");
        return view('master.pludpd', compact('pludpd'));
    }
    
    public function pluomi()
    {
        $pluomi = DB::select("SELECT    prd_kodedivisi div, prc_pluigr plu_igr,prc_pluomi  plu_omi,prd_deskripsipanjang deskripsi, 
                                        prd_unit unit,prd_frac frac,prd_kodetag tag_igr,prc_kodetag tag_omi,flag,LOKASI,QTY_PLANO,LKS_NOID,PRD_PERLAKUANBARANG STAT 
                                        from igrpwt.tbmaster_prodcrm  
                                        left join igrpwt.tbmaster_prodmast on prd_prdcd=prc_pluigr 
                                        LEFT JOIN ( 
                                        select lks_prdcd PLU_LOK,lks_koderak||'.'||lks_kodesubrak||'.'||lks_tiperak||'.'||lks_shelvingrak||'.'||LKS_NOURUT LOKASI, 
                                        LKS_QTY QTY_PLANO,LKS_NOID 
                                        from igrpwt.tbmaster_lokasi where lks_koderak like 'D%' and lks_noid is not null) lok ON PLU_LOK=prc_pluigr 
                                        left join ( 
                                        select plu, 
                                        CASE                  
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
                                        END AS FLAG   
                                        From 
                                        (select prd_prdcd plu, 
                                        coalesce(PRD_FLAGNAS,'N') AS NAS,                  
                                        coalesce(PRD_FLAGIGR,'N') AS IGR,                  
                                        coalesce(PRD_FLAGIDM,'N') AS IDM,                  
                                        coalesce(PRD_FLAGOMI,'N') AS OMI,                  
                                        coalesce(PRD_FLAGBRD,'N') AS BRD,                  
                                        coalesce(PRD_FLAGOBI,'N') AS K_IGR,                  
                                        case when prd_plumcg in (select PLUIDM from igrpwt.DEPO_LIST_IDM ) THEN 'Y' ELSE 'N' END AS DEPO  
                                        from igrpwt.tbmaster_prodmast where prd_prdcd like '%0') z
                                        )aa on plu=prc_pluigr 
                                        where prc_group='O' AND LOKASI LIKE 'D%' ORDER BY LOKASI");
        return view('master.pluomi', compact('pluomi'));
    }
}
