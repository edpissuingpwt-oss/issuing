<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BpbrExport implements FromCollection, WithHeadings, WithMapping
{
    protected $flag, $tglMulai, $tglSelesai;

    public function __construct($flag, $tglMulai, $tglSelesai)
    {
        $this->flag = $flag;
        $this->tglMulai = $tglMulai;
        $this->tglSelesai = $tglSelesai;
    }

    public function collection()
    {
        $queryOmi = "
                        SELECT
                            TO_CHAR(tanggal, 'dd-Mon-yy') AS tanggal,
                            nobpbr,
                            '' AS tipe,
                            kodetoko,
                            nonrb,
                            plu,
                            deskripsi,
                            baik,
                            terima,
                            kurang,
                            tolak,
                            sb AS status,
                            tagidm,
                            tagomi,
                            supplier
                        FROM (
                            SELECT
                                rom_nodokumen AS nobpbr,
                                rom_tgldokumen AS tanggal,
                                flagtk,
                                rom_kodetoko AS kodetoko,
                                rom_noreferensi AS nonrb,
                                rom_prdcd AS plu,
                                deskripsi,
                                rom_qty + rom_qtytlr AS qtynrb,
                                rom_qtyrealisasi AS fisik,
                                rom_qtyselisih AS kurang,
                                rom_qtymlj AS baik,
                                rom_qtytlj AS terima,
                                rom_qtytlr - rom_qtyselisih AS tolak,
                                sb,
                                tag_idm AS tagidm,
                                tag_omi AS tagomi,
                                kodesp,
                                supplier
                            FROM IGRPWT.tbtr_returomi

                            LEFT JOIN (
                                SELECT
                                    PRD_PRDCD,
                                    PRD_DESKRIPSIPANJANG AS deskripsi
                                FROM IGRPWT.TBMASTER_PRODMAST
                                WHERE PRD_PRDCD LIKE '%0'
                            ) PRD
                                ON ROM_PRDCD = PRD_PRDCD

                            LEFT JOIN (
                                SELECT
                                    HGB_PRDCD,
                                    HGB_STATUSBARANG AS sb,
                                    hgb_kodesupplier AS kodesp
                                FROM IGRPWT.TBMASTER_HARGABELI
                            ) HGB
                                ON ROM_PRDCD = HGB_PRDCD

                            LEFT JOIN (
                                SELECT
                                    PRC_PLUIGR AS plui,
                                    prc_kodetag AS tag_idm
                                FROM IGRPWT.tbmaster_prodcrm
                                WHERE prc_group = 'I'
                                AND prc_pluigr IS NOT NULL
                            ) prci
                                ON ROM_PRDCD = plui

                            LEFT JOIN (
                                SELECT
                                    PRC_PLUIGR AS pluo,
                                    prc_kodetag AS tag_omi
                                FROM IGRPWT.tbmaster_prodcrm
                                WHERE prc_group = 'O'
                                AND prc_pluigr IS NOT NULL
                            ) prco
                                ON ROM_PRDCD = pluo

                            LEFT JOIN (
                                SELECT
                                    sup_kodesupplier AS kodesp2,
                                    sup_namasupplier AS supplier
                                FROM IGRPWT.tbmaster_supplier
                            ) sp
                                ON kodesp = kodesp2

                            LEFT JOIN (
                                SELECT
                                    TKO_KODEOMI,
                                    TKO_KODESBU AS flagtk
                                FROM IGRPWT.TBMASTER_TOKOIGR
                            ) flagtk
                                ON rom_kodetoko = tko_kodeomi

                        ) retur

                        WHERE tanggal::date BETWEEN ? AND ?
                        AND flagtk = 'O'

                        ORDER BY 2,1
                        ";

        $queryIdm = "SELECT * FROM ( SELECT NOBPBR,
                            to_char(TANGGAL, 'dd-Mon-yy') TANGGAL,
                            wt.typer tipe,
                            flagtk,
                            KODETOKO,
                            NONRB,
                            PLU,
                            DESKRIPSI,
                            FISIK,
                            BAIK,
                            TERIMA,
                            KURANG,
                            TOLAK,
                            SB,
                            TAGIDM,
                            TAGOMI,
                            supplier
                            FROM (
                            select rom_nodokumen as nobpbr,
                            rom_tgldokumen as tanggal,
                            flagtk,
                            rom_kodetoko as kodetoko,
                            rom_noreferensi as nonrb,
                            rom_prdcd as plu,
                            deskripsi,
                            rom_qty+rom_qtytlr as qtynrb,
                            rom_qtyrealisasi as fisik,
                            rom_qtyselisih as kurang,
                            rom_qtymlj as baik,
                            rom_qtytlj as terima,
                            rom_qtytlr-rom_qtyselisih as tolak,
                            SB,
                            TAG_IDM as TAGIDM,
                            TAG_OMI as TAGOMI,
                            kodesp,
                            supplier
                            from IGRPWT.tbtr_returomi
                            LEFT JOIN
                            (SELECT PRD_PRDCD,PRD_DESKRIPSIPANJANG DESKRIPSI FROM IGRPWT.TBMASTER_PRODMAST WHERE PRD_PRDCD LIKE '%0') PRD ON ROM_PRDCD = PRD_PRDCD 
                            LEFT JOIN
                            (SELECT HGB_PRDCD,HGB_STATUSBARANG as sb,hgb_kodesupplier as kodesp FROM IGRPWT.TBMASTER_HARGABELI)HGB ON ROM_PRDCD = HGB_PRDCD
                            LEFT JOIN
                            (select PRC_PLUIGR AS PLUI,prc_kodetag TAG_IDM from IGRPWT.tbmaster_prodcrm where prc_group = 'I' and prc_pluigr is not null) prci ON ROM_PRDCD = PLUI
                            LEFT JOIN
                            (select PRC_PLUIGR AS PLUO,prc_kodetag TAG_OMI from IGRPWT.tbmaster_prodcrm where prc_group = 'O' and prc_pluigr is not null) prco ON ROM_PRDCD = PLUO
                            left join
                            (select sup_kodesupplier as kodesp2, sup_namasupplier as supplier  from IGRPWT.tbmaster_supplier ) sp on kodesp = kodesp2
                            LEFT JOIN
                            (SELECT TKO_KODEOMI,TKO_KODESBU flagtk FROM IGRPWT.TBMASTER_TOKOIGR)flagtk ON rom_kodetoko = tko_kodeomi
                            )
                            retur
                            LEFT JOIN
                            (SELECT  distinct w.shop as kodetoko1,
                                w.docno as docno,
                                SUBSTRING(KETERANGAN, 10) AS typer,
                                to_char(w.TGL1, 'dd-Mon-yy') tanggal11,
                                w.TGL1 AS tanggal1,
                                t.tko_namaomi,
                                w.prdcd AS pluidm,
                                p.prd_prdcd as pluigr,
                                p.prd_deskripsipanjang,
                                w.qty,
                                w.price,
                                w.gross AS netto,
                                w.ppn,
                                w.gross + ppn AS gross,
                                l.LKS_KODERAK||'.'|| LKS_KODESUBRAK||'.'|| LKS_TIPERAK||'.'|| LKS_SHELVINGRAK||'.'|| LKS_NOURUT lokasi,
                                CASE 
                                WHEN W.RECID = 'N' THEN 'BELUM ABSEN'
                                WHEN W.RECID = 'A' THEN 'SUDAH ABSEN'
                                WHEN W.RECID = 'S' THEN 'SUDAH SORTASI'
                                WHEN W.RECID = 'P' THEN 'SELESAI PROSES'
                                END AS STATUS
                                FROM igrpwt.tbtr_wt_interface w
                                left join (SELECT * FROM igrpwt.tbmaster_prodmast WHERE prd_prdcd LIKE '%0') p on w.prdcd = p.prd_plumcg
                                left join (select * from igrpwt.tbmaster_lokasi where lpad(LKS_KODERAK,1)='D' and LKS_TIPERAK<>'S' and lks_koderak not in ('DTAGR','DHDH','HDH')) l on p.PRD_PRDCD   = l.LKS_PRDCD
                                left join igrpwt.tbmaster_tokoigr t on w.shop   = t.tko_kodeomi
                                WHERE w.recid  = 'P'
                                ORDER BY w.tgl1,
                                    w.shop,
                                    w.docno) WT ON retur.kodetoko||retur.nonrb||retur.plu = wt.kodetoko1||wt.docno||wt.pluigr
                            where tanggal::date BETWEEN ? AND ?
                            and flagtk = 'I'
                            order by 2,1) finalresult";

        $query = $this->flag === 'omi' ? $queryOmi : $queryIdm;

        return collect(DB::connection('pgsql')->select($query, [
            $this->tglMulai,
            $this->tglSelesai
        ]));
    }

    public function map($row): array
    {
        $row = (array) $row;

        return [
            $row['tanggal'] ?? '',
            $row['nobpbr'] ?? '',
            $row['tipe'] ?? '',
            $row['kodetoko'] ?? '',
            $row['nonrb'] ?? '',
            $row['plu'] ?? '',
            $row['deskripsi'] ?? '',
            ($row['baik'] === null || $row['baik'] == 0) ? '0' : $row['baik'],
            ($row['terima'] === null || $row['terima'] == 0) ? '0' : $row['terima'],
            ($row['kurang'] === null || $row['kurang'] == 0) ? '0' : $row['kurang'],
            ($row['tolak'] === null || $row['tolak'] == 0) ? '0' : $row['tolak'],
            $row['status'] ?? '',
            $row['tagidm'] ?? '',
            $row['tagomi'] ?? '',
            $row['supplier'] ?? '',
        ];
    }

    public function headings(): array
    {
        return [
            'TANGGAL',
            'NO BPBR',
            'TIPE',
            'KODE TOKO',
            'NO NRB',
            'PLU',
            'DESKRIPSI',
            'BAIK',
            'TERIMA',
            'KURANG',
            'TOLAK',
            'SB',
            'TAG IDM',
            'TAG OMI',
            'SUPPLIER',
        ];
    }
}
