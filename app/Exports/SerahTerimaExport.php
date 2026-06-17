<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class SerahTerimaExport implements FromCollection, WithHeadings, WithMapping
{
    protected $data;
    
    public function __construct($data)
    {
        $this->data = $data;
    }
    
    public function collection()
    {
        return $this->data;
    }
    
    public function headings(): array
    {
        return [
            'Tipe', 'Tanggal', 'Jam', 'Kode Toko', 'No SJ', 'Nama Checker',
            'Total DSPB', 'Bronjong', 'Kontainer', 'Kardus', 'Susu', 'Rokok',
            'Detail Rokok', 'Keterangan'
        ];
    }
    
    public function map($row): array
    {
        return [
            $row->tipe,
            Carbon::parse($row->tanggal)->format('d/m/Y'), // Format tgl: dd/mm/yyyy
            Carbon::parse($row->jam)->format('H:i'),      // Format jam: 24 jam
            $row->kode_toko,
            $row->no_sj,
            $row->nama_checker,
            $row->total_dspb,
            $row->bronjong,
            $row->kontainer,
            $row->kardus,
            $row->susu,
            $row->rokok,
            $row->detail_rokok,
            $row->keterangan,
        ];
    }
}