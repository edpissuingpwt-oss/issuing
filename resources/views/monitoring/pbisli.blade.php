@extends('layouts.app')
@section('title', 'PB & SL IDM TODAY')
@section('content')
@push('styles')
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

<style>
    .main-sidebar, .main-sidebar *, .nav-sidebar {
        display: none !important;
    }
    body.sidebar-mini .content-wrapper, .content-wrapper, .wrapper {
        margin-left: 0 !important;
        width: 100% !important;
    }
    .wrapper {
    margin-left: 0 !important;
    }

    /* Tempus Dominus modern style */
    .tempus-dominus-widget {
        z-index: 2050 !important;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25) !important;
        border-radius: 0.75rem !important;
        background: #ffffff !important;
        border: none !important;
        padding: 0.5rem;
    }
    .tempus-dominus-widget .datepicker-days .table-condensed thead tr:first-child th {
        background: linear-gradient(45deg, #007bff, #00c6ff);
        color: #fff !important;
        font-weight: 600;
        border-radius: 0.5rem 0.5rem 0 0;
    }
    .tempus-dominus-widget .day:hover {
        background: #007bff !important;
        color: #fff !important;
        border-radius: 50% !important;
        transition: all 0.2s ease-in-out;
    }
    .tempus-dominus-widget .day.active {
        background: linear-gradient(45deg, #007bff, #00c6ff) !important;
        color: #fff !important;
        font-weight: 600;
        border-radius: 50% !important;
    }

    /* Kartu modern */
    .modern-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }
    .card-header.bg-gradient-primary {
        background: linear-gradient(45deg, #007bff, #00c6ff);
    }
    .card-title {
        font-size: 1.25rem;
        font-weight: 600;
    }
    .modern-body {
        background: var(--card-bg);
        padding: 1.5rem;
    }
    .modern-footer {
        background: var(--footer-bg);
        border-top: 1px solid rgba(0,0,0,0.05);
        padding: 1rem 1.5rem;
    }

    /* Input datepicker */
    .modern-datepicker .form-control {
        border-radius: 0.5rem 0 0 0.5rem;
        border: 1px solid #dee2e6;
    }
    .modern-datepicker .input-group-text {
        border-radius: 0 0.5rem 0.5rem 0;
        cursor: pointer;
        transition: background 0.2s;
    }
    .modern-datepicker .input-group-text:hover {
        background: #0056b3 !important;
    }

    /* Tombol */
    .modern-btn-submit {
        background: linear-gradient(45deg, #007bff, #00c6ff);
        color: #fff;
        border: none;
        border-radius: 0.5rem;
        font-weight: 600;
    }
    .modern-btn-submit:hover {
        background: linear-gradient(45deg, #0056b3, #0099cc);
    }
    .modern-btn-clear {
        background: #f44336;
        color: #fff;
        border: none;
        border-radius: 0.5rem;
        font-weight: 600;
    }
    .modern-btn-clear:hover {
        background: #c62828;
    }

    /* Dark Mode */
    [data-theme="dark"] .modern-body {
        background: #2a2a3d;
        color: #f1f1f1;
    }
    [data-theme="dark"] .modern-footer {
        background: #1e1e2f;
    }
    [data-theme="dark"] .modern-datepicker .form-control {
        background: #1e1e2f;
        color: #fff;
        border-color: #444;
    }

    /* Radio button */
    .form-check-input {
        transform: scale(1.2);
        margin-top: 2px;
    }
    .form-check-label {
        font-size: 0.95rem;
    }

    /* Tambah jarak antar radio button */
    .radio-group .form-check {
        margin-right: 2rem; /* jarak horizontal antar OMI dan IDM */
    }
    /* Tambah jarak antar tombol */
    .button-group .btn {
        margin-left: 1rem; /* jarak antar Submit dan Clear */
    }
    /* Header Modern */
    .modern-header {
        background: linear-gradient(45deg, #007bff, #00c6ff);
        color: #fff !important; /* teks tetap putih */
        border-bottom: none;
        padding: 0.75rem 1rem;
        border-radius: 0.75rem 0.75rem 0 0;
    }

    /* Dark Mode Header */
    [data-theme="dark"] .modern-header {
        background: linear-gradient(45deg, #1e3c72, #2a5298); /* biru gelap */
        color: #fff !important;
    }

    [data-theme="dark"] .modern-select {
        background-color: #2c2f33;
        color: #e0e0e0;
        border-color: #444c57;
    }

    [data-theme="dark"] .modern-select:focus {
        border-color: #9ecbff;
        box-shadow: 0 0 0 0.2rem rgba(158, 203, 255, 0.25);
    }

    [data-theme="dark"] .form-control {
        background-color: #2c2f33;
        color: #f1f1f1;
        border-color: #444;
    }
    /* ========== DARK MODE TABLE BACKGROUND & TEXT ========== */

    [data-theme="dark"] table.dataTable,
    [data-theme="dark"] table.dataTable thead,
    [data-theme="dark"] table.dataTable tbody,
    [data-theme="dark"] table.dataTable tfoot,
    [data-theme="dark"] table.dataTable th,
    [data-theme="dark"] table.dataTable td {
    background-color: #1e1e2f !important;
    color: #ffffff !important;
    border-color: #444 !important;
    }

    /* Optional: border between rows */
    [data-theme="dark"] table.dataTable tbody tr {
    border-bottom: 1px solid #333;
    }

    /* Optional: hover effect */
    [data-theme="dark"] table.dataTable tbody tr:hover {
    background-color: #2d2d3f;
    }

    /* Search input & select */
    [data-theme="dark"] .dataTables_wrapper .dataTables_filter input,
    [data-theme="dark"] .dataTables_wrapper .dataTables_length select {
    background-color: #1e1e2f;
    color: #fff;
    border: 1px solid #555;
    }

    /* Page buttons */
    [data-theme="dark"] .dataTables_wrapper .dataTables_paginate .paginate_button {
    color: #fff !important;
    background-color: #2e2e3e !important;
    border: 1px solid #555 !important;
    }
    [data-theme="dark"] .dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background-color: #444 !important;
    color: #fff !important;
    }
    /* Overwrite Bootstrap striped background di dark mode */
    [data-theme="dark"] .table-striped tbody tr:nth-of-type(odd) {
    background-color: #232334 !important;
    }

    [data-theme="dark"] .table-striped tbody tr:nth-of-type(even) {
    background-color: #1e1e2f !important;
    }

    /* ========== DARK MODE - DATATABLE HEADER TOOLS (filter, length, buttons) ========== */
    [data-theme="dark"] .dataTables_wrapper .dataTables_length,
    [data-theme="dark"] .dataTables_wrapper .dataTables_filter,
    [data-theme="dark"] .dataTables_wrapper .dt-buttons {
    background-color: #1e1e2f !important;
    color: #fff !important;
    padding: 0.5rem;
    border-radius: 0.25rem;
    }

    /* Search input */
    [data-theme="dark"] .dataTables_wrapper .dataTables_filter input {
    background-color: #2b2b3c !important;
    color: #fff !important;
    border: 1px solid #555 !important;
    }

    /* Select "Tampilkan" */
    [data-theme="dark"] .dataTables_wrapper .dataTables_length select {
    background-color: #2b2b3c !important;
    color: #fff !important;
    border: 1px solid #555 !important;
    }

    /* Button Export Excel/Print */
    [data-theme="dark"] .dataTables_wrapper .dt-buttons .dt-button {
    background-color: #333 !important;
    color: #fff !important;
    border: 1px solid #555 !important;
    }
    /* ===== DARK MODE FIX: DataTables Copy Notification (Popup) ===== */
    [data-theme="dark"] .dt-button-info {
    background-color: #2b2b3c !important;
    color: #ffffff !important;
    border: 1px solid #555 !important;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    }

    /* Judul (h2) di popup */
    [data-theme="dark"] .dt-button-info h2 {
    color: #ffffff !important;
    border-bottom: 1px solid #666 !important;
    }

    /* Teks konten (biasanya span atau div di bawah h2) */
    [data-theme="dark"] .dt-button-info > div {
    color: #ffffff !important;
    }
    


</style>
@endpush

<div class="container-fluid">
    <div class="card modern-card shadow-lg border-0">
        {{-- PBIDM TODAY--}}
        <div class="card-header modern-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">
                {{-- <i class="fas fa-table"></i> --}}
                PB IDM TODAY
            </h3>
            <div id="exportButtons2"></div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="pbi" class="table table-bordered table-striped" style="width:100%">
                    <thead class="bg-gradient-info">
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">KODE</th>
                            <th class="text-center">NAMA TOKO</th>
                            <th class="text-right">ITEM PB</th>
                            <th class="text-right">ITEM REAL</th>
                            <th class="text-right">%</th>
                            <th class="text-right">QTY PB</th>
                            <th class="text-right">QTY REAL</th>
                            <th class="text-right">%</th>
                            <th class="text-right">RPH PB</th>
                            <th class="text-right">RPH REAL</th>
                            <th class="text-right">%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $rphData = [[], [], [], [], [], []];
                        @endphp
                        @foreach($pbi as $i => $row)
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td class="text-center">{{ $row->kode }}</td>
                            <td class="text-center">{{ $row->nama_toko }}</td>
                            <td class="text-right">{{ number_format($row->itemp) }}</td>
                            <td class="text-right">{{ number_format($row->itemr) }}</td>
                            <td class="text-right">{{ $row->itempc }}%</td>
                            <td class="text-right">{{ number_format($row->qtyp) }}</td>
                            <td class="text-right">{{ number_format($row->qtyr) }}</td>
                            <td class="text-right">{{ $row->qtypc }}%</td>
                            <td class="text-right">{{ number_format($row->rphp) }}</td>
                            <td class="text-right">{{ number_format($row->rphr) }}</td>
                            <td class="text-right">{{ $row->rphpc }}%</td>

                            @php
                                array_push($rphData[0], $row->itemp);
                                array_push($rphData[1], $row->itemr);
                                array_push($rphData[2], $row->qtyp);
                                array_push($rphData[3], $row->qtyr);
                                array_push($rphData[4], $row->rphp);
                                array_push($rphData[5], $row->rphr);
                            @endphp
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-center">TOTAL</th>
                            <th class="text-right">{{ number_format(array_sum($rphData[0])) }}</th>
                            <th class="text-right">{{ number_format(array_sum($rphData[1])) }}</th>
                            <th class="text-right">
                                @if(array_sum($rphData[0]) != 0)
                                    {{ round(array_sum($rphData[1]) / array_sum($rphData[0]) * 100) }}%
                                @else
                                    N/A
                                @endif
                            </th>
                            <th class="text-right">{{ number_format(array_sum($rphData[2])) }}</th>
                            <th class="text-right">{{ number_format(array_sum($rphData[3])) }}</th>
                            <th class="text-right">
                                @if(array_sum($rphData[2]) != 0)
                                    {{ round(array_sum($rphData[3]) / array_sum($rphData[2]) * 100) }}%
                                @else
                                    N/A
                                @endif
                            </th>
                            <th class="text-right">{{ number_format(array_sum($rphData[4])) }}</th>
                            <th class="text-right">{{ number_format(array_sum($rphData[5])) }}</th>
                            <th class="text-right">
                                @if(array_sum($rphData[4]) != 0)
                                    {{ round(array_sum($rphData[5]) / array_sum($rphData[4]) * 100) }}%
                                @else
                                    N/A
                                @endif
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        {{-- SERVICE LEVEL IDM TODAY --}}
        <div class="card-header modern-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">
                {{-- <i class="fas fa-calendar-day"></i> --}}
                SERVICE LEVEL IDM TODAY
            </h3>
            <div id="exportButtons"></div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="sli" class="table table-bordered table-striped" style="width:100%">
                    <thead class="bg-gradient-info">
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">PLU</th>
                            <th class="text-center">DESKRIPSI</th>
                            <th class="text-right">TOKO ORDER</th>
                            <th class="text-right">QTY ORDER</th>
                            <th class="text-right">QTY REALISASI</th>
                            <th class="text-right">%QTY</th>
                            <th class="text-right">RPH ORDER</th>
                            <th class="text-right">RPH REALISASI</th>
                            <th class="text-right">%NILAI</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($sli as $index => $row)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">{{ $row->plu }}</td>
                            <td class="text-center">{{ $row->deskripsi }}</td>
                            <td class="text-right">{{ number_format(round($row->jml_toko), 0, ".", ",") }}</td>
                            <td class="text-right">{{ number_format(round($row->qtyo), 0, ".", ",") }}</td>
                            <td class="text-right">{{ number_format(round($row->qtyr), 0, ".", ",") }}</td>
                            <td class="text-right">{{ number_format(round($row->slqty), 0, ".", ",") }}%</td>
                            <td class="text-right">{{ number_format(round($row->nilaio), 0, ".", ",") }}</td>
                            <td class="text-right">{{ number_format(round($row->ttlnilai), 0, ".", ",") }}</td>
                            <td class="text-right">{{ number_format(round($row->slnilai), 0, ".", ",") }}%</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
{{-- DataTables Bootstrap 4 + Buttons CSS --}}
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush

@push('scripts')
{{-- DataTables + Buttons --}}
<script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

<script src="{{ asset('adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>

<!-- JSZip & PDFMake -->
<script src="{{ asset('adminlte/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/pdfmake/vfs_fonts.js') }}"></script>

<!-- Buttons Extensions -->
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

<script>
    $(document).ready(function() {
        // Ambil tanggal hari ini dalam format YYYY-MM-DD
        var d = new Date();
        var today = String(d.getDate()).padStart(2, '0') + '-' +
                    String(d.getMonth() + 1).padStart(2, '0') + '-' +
                    d.getFullYear();

        // Tabel 1: SERVICE LEVEL IDM TODAY (SLI)
        var table1 = $('#sli').DataTable({
            dom: '<"d-flex justify-content-between align-items-center mb-2"lfB>rtip',
            buttons: [
                {
                    extend: 'copy',
                    text: '<i class="fas fa-copy"></i> Copy',
                    className: 'btn btn-light btn-sm border-primary text-primary'
                },
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'btn btn-light btn-sm border-success text-success',
                    title: 'SERVICE LEVEL IDM ' + today,
                    filename: 'SERVICE_LEVEL_IDM_' + today,

                    customize: function (xlsx) {
                        var styles = xlsx.xl['styles.xml'];

                        /* =========================
                        STYLE: GOLD + BOLD + CENTER
                        ========================== */

                        // 1. Tambah font BOLD
                        var fonts = $('fonts', styles);
                        fonts.append(`
                            <font>
                                <b/>
                                <sz val="14"/>
                                <color rgb="000000"/>
                                <name val="Calibri"/>
                            </font>
                        `);
                        var fontId = fonts.children().length - 1;

                        // 2. Tambah fill GOLD
                        var fills = $('fills', styles);
                        fills.append(`
                            <fill>
                                <patternFill patternType="solid">
                                    <fgColor rgb="FFD700"/>
                                    <bgColor indexed="64"/>
                                </patternFill>
                            </fill>
                        `);
                        var fillId = fills.children().length - 1;

                        // 3. Tambah alignment CENTER
                        var cellXfs = $('cellXfs', styles);
                        cellXfs.append(`
                            <xf numFmtId="0" fontId="${fontId}" fillId="${fillId}" borderId="0" applyFont="1" applyFill="1" applyAlignment="1">
                                <alignment horizontal="center" vertical="center"/>
                            </xf>
                        `);
                        var styleId = cellXfs.children().length - 1;

                        /* =========================
                        APPLY KE TITLE SAJA (A1)
                        ========================== */
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                        $('c[r="A1"]', sheet).attr('s', styleId);
                    }
                }
            ],
            scrollX: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
            language: {
                url: "{{ asset('adminlte/plugins/datatables/i18n/id.json') }}"
            }
        });
        table1.buttons().container().appendTo('#exportButtons');

        // Tabel 2: PB IDM TODAY (PBI)
        var table2 = $('#pbi').DataTable({
            dom: '<"d-flex justify-content-between align-items-center mb-2"lfB>rtip',
            buttons: [
                {
                    extend: 'copy',
                    text: '<i class="fas fa-copy"></i> Copy',
                    className: 'btn btn-light btn-sm border-primary text-primary'
                },
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'btn btn-light btn-sm border-success text-success',
                    title: 'PB IDM ' + today,
                    filename: 'PB_IDM_' + today,

                    customize: function (xlsx) {
                        var styles = xlsx.xl['styles.xml'];

                        /* =========================
                        STYLE: GOLD + BOLD + CENTER
                        ========================== */

                        // 1. Tambah font BOLD
                        var fonts = $('fonts', styles);
                        fonts.append(`
                            <font>
                                <b/>
                                <sz val="14"/>
                                <color rgb="000000"/>
                                <name val="Calibri"/>
                            </font>
                        `);
                        var fontId = fonts.children().length - 1;

                        // 2. Tambah fill GOLD
                        var fills = $('fills', styles);
                        fills.append(`
                            <fill>
                                <patternFill patternType="solid">
                                    <fgColor rgb="FFD700"/>
                                    <bgColor indexed="64"/>
                                </patternFill>
                            </fill>
                        `);
                        var fillId = fills.children().length - 1;

                        // 3. Tambah alignment CENTER
                        var cellXfs = $('cellXfs', styles);
                        cellXfs.append(`
                            <xf numFmtId="0" fontId="${fontId}" fillId="${fillId}" borderId="0" applyFont="1" applyFill="1" applyAlignment="1">
                                <alignment horizontal="center" vertical="center"/>
                            </xf>
                        `);
                        var styleId = cellXfs.children().length - 1;

                        /* =========================
                        APPLY KE TITLE SAJA (A1)
                        ========================== */
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                        $('c[r="A1"]', sheet).attr('s', styleId);
                    }
                }
            ],
            scrollX: true,
            pageLength: 5,
            lengthMenu: [[5, 10, 25, -1], [5, 10, 25, "Semua"]],
            language: {
                url: "{{ asset('adminlte/plugins/datatables/i18n/id.json') }}"
            }
        });
        table2.buttons().container().appendTo('#exportButtons2');
    });
</script>
@endpush
