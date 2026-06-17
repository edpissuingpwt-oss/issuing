<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RegisterNrbExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data)->map(function ($row) {

            return [
                'Tanggal' => \Carbon\Carbon::parse($row->tanggal)->format('d/m/Y'),
                'Jam' => \Carbon\Carbon::parse($row->jam)->format('H:i'),
                'Toko'          => $row->toko,
                'Nama Toko'     => $row->nama_toko,
                'NRB'           => $row->nrb,
                'Koli'          => $row->koli,
                'Palet'         => $row->palet,
                'Nama Register' => $row->nama_register,
                'Modify By'     => $row->modify_by,
                'Keterangan'    => $row->keterangan,
            ];

        });
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Jam',
            'Toko',
            'Nama Toko',
            'NRB',
            'Koli',
            'Palet',
            'Nama Register',
            'Modify By',
            'Keterangan'
        ];
    }
}