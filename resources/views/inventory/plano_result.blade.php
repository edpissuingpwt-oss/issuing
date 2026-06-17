<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    @php
        $flagNames = [
            '1' => 'Plano Minus Gudang',
            '2' => 'Plano Minus Toko',
            '3' => 'Plano Minus Gudang dan Toko',
            '4' => 'Plano Plus Gudang',
            '5' => 'Plano Plus Toko',
            '6' => 'Plano Plus Gudang dan Toko',
            '7' => 'Rekap Plano',
        ];
    @endphp

    <title>{{ $flagNames[$flag] ?? '' }}</title>

    <style>
        body {
            font-family: Calibri, Arial, sans-serif;
            font-size: 13px;
            padding: 20px;
        }

        table {
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid black;
            font-size: 13px;
        }

        th, td {
            padding: 4px 6px;
            text-align: center;
        }

        .btn-print, .btn-back {
            padding: 6px 10px;
            margin-bottom: 10px;
            font-size: 13px;
        }

        /* ============================== PRINT STYLE ============================== */
        @media print {

            .btn-print, .btn-back {
                display: none !important;
            }

            body {
                margin: 0 !important;
                padding: 0 !important;
                -webkit-print-color-adjust: exact !important;
            }

            @page {
                size: A4 portrait;
                margin: 10mm;
            }

            table {
                width: 100% !important;
                font-size: 12px !important;
            }
        }
    </style>
</head>

<body>

<button class="btn-print" onclick="window.print()">Print this page</button>
<button class="btn-back" onclick="history.back()">Go Back</button>

<div>
    <strong style="font-size: 22px;">{{ $flagNames[$flag] ?? '' }}</strong><br><br>
</div>

<table>
    <thead>
        @if ($flag == 1)
            <tr>
                <th>No.</th>
                <th>Display Gudang</th>
                <th>PLU</th>
                <th>Deskripsi</th>
                <th>LPP</th>
                <th>Plano Gudang</th>
                <th>Plano Storage</th>
                <th>Selisih Qty</th>
                <th>Update Gudang</th>
                <th>IC</th>
            </tr>
        @elseif ($flag == 2)
            <tr>
                <th>No.</th>
                <th>Display Toko</th>
                <th>PLU</th>
                <th>Deskripsi</th>
                <th>LPP</th>
                <th>Plano Toko</th>
                <th>Plano Storage</th>
                <th>Selisih Qty</th>
                <th>Update Toko</th>
            </tr>
        @elseif ($flag == 3)
            <tr>
                <th>No.</th>
                <th>Display Gudang</th>
                <th>Display Toko</th>
                <th>PLU</th>
                <th>Deskripsi</th>
                <th>LPP</th>
                <th>Plano Gudang</th>
                <th>Plano Toko</th>
                <th>Plano Storage</th>
                <th>Selisih Qty</th>
                <th>Update Gudang</th>
                <th>Update Toko</th>
                <th>IC</th>
            </tr>
        @elseif ($flag == 4)
            <tr>
                <th>No.</th>
                <th>Display Toko</th>
                <th>PLU</th>
                <th>Deskripsi</th>
                <th>LPP</th>
                <th>Plano Toko</th>
                <th>Plano Storage</th>
                <th>Selisih Qty</th>
                <th>Update Toko</th>
            </tr>
        @elseif ($flag == 5)
            <tr>
                <th>No.</th>
                <th>Display Toko</th>
                <th>PLU</th>
                <th>Deskripsi</th>
                <th>LPP</th>
                <th>Plano Toko</th>
                <th>Plano Storage</th>
                <th>Selisih Qty</th>
                <th>Update Toko</th>
            </tr>
        @elseif ($flag == 6)
            <tr>
                <th>No.</th>
                <th>Display Toko</th>
                <th>Display Gudang</th>
                <th>PLU</th>
                <th>Deskripsi</th>
                <th>LPP</th>
                <th>Plano Gudang</th>
                <th>Plano Toko</th>
                <th>Plano Storage</th>
                <th>Selisih Qty</th>
                <th>Update Gudang</th>
                <th>Update Toko</th>
                <th>IC</th>
            </tr>
        @elseif ($flag == 7)
            <tr>
                <th>No</th>
                <th>Keterangan</th>
                <th>Total Item</th>
            </tr>
        @endif
    </thead>

    <tbody>
        @forelse ($data as $row)
            @if ($flag == 1)    
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row->display_gudang }}</td>
                    <td>{{ $row->plu }}</td>
                    <td>{{ $row->desk }}</td>
                    <td>{{ $row->lpp_qty }}</td>
                    <td>{{ $row->plano_gudang }}</td>
                    <td>{{ $row->plano_storage }}</td>
                    <td>{{ $row->selisih_qty }}</td>
                    <td>{{ $row->update_gudang }}</td>
                    <td>{{ $row->ket }}</td>
                </tr>
            @elseif ($flag == 2)    
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row->display_toko }}</td>
                    <td>{{ $row->plu }}</td>
                    <td>{{ $row->desk }}</td>
                    <td>{{ $row->lpp_qty }}</td>
                    <td>{{ $row->plano_toko }}</td>
                    <td>{{ $row->plano_storage }}</td>
                    <td>{{ $row->selisih_qty }}</td>
                    <td>{{ $row->update_toko }}</td>
                </tr>
            @elseif ($flag == 3)    
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row->display_gudang }}</td>
                    <td>{{ $row->display_toko }}</td>
                    <td>{{ $row->plu }}</td>
                    <td>{{ $row->desk }}</td>
                    <td>{{ $row->lpp_qty }}</td>
                    <td>{{ $row->plano_gudang }}</td>
                    <td>{{ $row->plano_toko }}</td>
                    <td>{{ $row->plano_storage }}</td>
                    <td>{{ $row->selisih_qty }}</td>
                    <td>{{ $row->update_gudang }}</td>
                    <td>{{ $row->update_toko }}</td>
                    <td>{{ $row->ket }}</td>
                </tr>
            @elseif ($flag == 4)    
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row->display_gudang }}</td>
                    <td>{{ $row->plu }}</td>
                    <td>{{ $row->desk }}</td>
                    <td>{{ $row->lpp_qty }}</td>
                    <td>{{ $row->plano_gudang }}</td>
                    <td>{{ $row->plano_storage }}</td>
                    <td>{{ $row->selisih_qty }}</td>
                    <td>{{ $row->update_gudang }}</td>
                    <td>{{ $row->ket }}</td>
                </tr>
            @elseif ($flag == 5)    
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row->display_toko }}</td>
                    <td>{{ $row->plu }}</td>
                    <td>{{ $row->desk }}</td>
                    <td>{{ $row->lpp_qty }}</td>
                    <td>{{ $row->plano_toko }}</td>
                    <td>{{ $row->plano_storage }}</td>
                    <td>{{ $row->selisih_qty }}</td>
                    <td>{{ $row->update_toko }}</td>
                </tr>
            @elseif ($flag == 6)    
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row->display_gudang }}</td>
                    <td>{{ $row->display_toko }}</td>
                    <td>{{ $row->plu }}</td>
                    <td>{{ $row->desk }}</td>
                    <td>{{ $row->lpp_qty }}</td>
                    <td>{{ $row->plano_gudang }}</td>
                    <td>{{ $row->plano_toko }}</td>
                    <td>{{ $row->plano_storage }}</td>
                    <td>{{ $row->selisih_qty }}</td>
                    <td>{{ $row->update_gudang }}</td>
                    <td>{{ $row->update_toko }}</td>
                    <td>{{ $row->ket }}</td>
                </tr>
            @elseif ($flag == 7)    
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row->ket }}</td>
                    <td>{{ $row->total }}</td>
                </tr>
            @endif
        @empty
        @endforelse
    </tbody>
</table>

</body>
</html>
