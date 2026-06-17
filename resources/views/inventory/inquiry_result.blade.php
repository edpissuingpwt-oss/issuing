<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Inquiry {{ $suffik }}</title>

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

{{-- ========================================================
    QUERY 1 – DATA PRODUK
======================================================== --}}
<table>
    <thead>
        <tr>
            <th>PLU</th>
            <th>DESKRIPSI</th>
            <th>UNIT</th>
            <th>FRAC</th>
            <th>TAG</th>
            <th>LAST_BPB</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($q1 as $r)
            <tr>
                <td>{{ $r->plu }}</td>
                <td>{{ $r->desk }}</td>
                <td>{{ $r->unit }}</td>
                <td>{{ $r->frac }}</td>
                <td>{{ $r->tag }}</td>
                <td>{{ $r->last_bpb }}</td>
            </tr>
        @endforeach
    </tbody>
</table>


{{-- ========================================================
    QUERY 2 – FLAG
======================================================== --}}
<table>
    <thead>
        <tr><th>Flag</th></tr>
    </thead>
    <tbody>
        @foreach ($q2 as $r)
            <tr><td>{{ $r->flag }}</td></tr>
        @endforeach
    </tbody>
</table>


{{-- ========================================================
    QUERY 3 – PLANO
======================================================== --}}
<table>
    <thead>
        <tr>
            <th>NO</th>
            <th>LOKASI</th>
            <th>QTY</th>
            <th>EXPIRED</th>
            <th>USER</th>
            <th>CTN</th>
            <th>PCS</th>
        </tr>
    </thead>
    <tbody>
        @php $no=1; @endphp

        @foreach ($q3 as $r)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $r->lokasi }}</td>
                <td>{{ $r->qty_plano }}</td>
                <td>{{ $r->exp }}</td>
                <td>{{ $r->modif }}</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        @endforeach

        <tr>
            <td colspan="2"><strong>TOTAL</strong></td>
            <td><strong>{{ $total_qty }}</strong></td>
            <td colspan="4"></td>
        </tr>
    </tbody>
</table>


{{-- ========================================================
    LOST IN SPACE
======================================================== --}}
<strong>LOST IN SPACE</strong><br><br>

<table>
    <tbody>
        @for ($i=1; $i<=5; $i++)
            <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
        @endfor
        <tr>
            <td><strong>FISIK</strong></td>
            <td>&nbsp;</td>
            <td><strong>{{ $total_qty }}</strong></td>
        </tr>

        <tr>
            <td><strong>PICKING</strong></td>
            <td>&nbsp;</td>
            <td><strong>{{ $qty_picking }}</strong></td>
        </tr>

        @php $no=1; @endphp
        @foreach ($q8 as $row)
            <tr>
                <td><strong>LPP 0{{ $no++ }}</strong></td>
                <td>&nbsp;</td>
                <td><strong>{{ $row->stock }}</strong></td>
            </tr>
        @endforeach

        <tr>
            <td><strong>SELISIH</strong></td>
            <td>&nbsp;</td>
            <td><strong>{{ $selisih }}</strong></td>
        </tr>

        <tr>
            <td><strong>SONAS'18</strong></td>
            <td style="min-width:60px;">&nbsp;</td>
            <td><strong>{{ $sonas }}</strong></td>
        </tr>
    </tbody>
</table>

{{-- ========================================================
    HADIAH
======================================================== --}}
<strong>HADIAH</strong><br><br>

<table>
    <thead>
        <tr>
            <th>KODEPROMOSI</th>
            <th>NAMAPROMOSI</th>
            <th>TGLAWAL</th>
            <th>TGLAKHIR</th>
            <th>KETHADIAH</th>
            <th>ALOKASI</th>
            <th>KELUAR</th>
            <th>SISA</th>
        </tr>
    </thead>
    <tbody>
        @forelse($hadiah as $r)
            <tr>
                <td>{{ $r->gfh_kodepromosi }}</td>
                <td>{{ $r->gfh_namapromosi }}</td>
                <td>{{ $r->gfh_tglawal }}</td>
                <td>{{ $r->gfh_tglakhir }}</td>
                <td>{{ $r->gfh_kethadiah }}</td>
                <td>{{ $r->alokasi }}</td>
                <td>{{ $r->keluar }}</td>
                <td>{{ $r->sisa }}</td>
            </tr>
        @empty
            <tr><td colspan="8">Tidak ada data hadiah</td></tr>
        @endforelse
    </tbody>
</table>

</body>
</html>
