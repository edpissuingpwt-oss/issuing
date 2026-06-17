@extends('layouts.app')
@section('title', 'PB IDM MENTAH (SEBELUM UPLOAD)')
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
        <div class="card-header modern-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">
                {{-- <i class="fas fa-table"></i> --}}
                PB IDM MENTAH (SEBELUM UPLOAD)
            </h3>
            <div id="exportButtons2"></div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="dspb" class="table table-bordered table-striped" style="width:100%">
                    <thead class="bg-gradient-info">
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">JUMLAH TOKO</th>
                            <th class="text-center">PLU IGR</th>
                            <th class="text-center">ZONA</th>
                            <th class="text-center">ALAMAT</th>
                            <th class="text-center">DESKRIPSI</th>
                            <th class="text-center">FRAC</th>
                            <th class="text-center">LPP</th>
                            <th class="text-center">PLANO</th>
                            <th class="text-center">QTY PCS</th>
                            <th class="text-center">QTY CTN</th>
                            <th class="text-center">ACOST PCS</th>
                            <th class="text-center">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalrph = 0 @endphp
                        @foreach($results as $i => $row)
                            @php
                                    $totalrph += $row->total;
                                @endphp
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td class="text-center">{{ $row->toko }}</td>
                            <td class="text-center">{{ $row->prd_prdcd }}</td>
                            <td class="text-center">{{ $row->zona }}</td>
                            <td class="text-center">{{ $row->rak }}</td>
                            <td class="text-center">{{ $row->prd_deskripsipanjang }}</td>
                            <td class="text-center">{{ $row->frac }}</td>
                            <td class="text-center">{{ $row->lpp }}</td>
                            <td class="text-center">{{ $row->plano }}</td>
                            <td style="text-align: right">{{ number_format(round($row->qty), 0, ".", ",") }}</td>
                            <td style="text-align: right">{{ number_format(round($row->qty_ctn), 0, ".", ",") }}</td>
                            <td style="text-align: right">{{ number_format(round($row->acostpcs), 0, ".", ",") }}</td>
                            <td style="text-align: right">{{ number_format(round($row->total), 0, ".", ",") }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="font-weight-bold bg-light">
                            <th colspan="12" class="text-center">TOTAL</th>
                            <td class="text-right">{{ number_format($totalrph) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection


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

        // Tabel 2: PB IDM TODAY (PBI)
        var table2 = $('#dspb').DataTable({
            dom: '<"d-flex justify-content-between align-items-center mb-2"lfB>rtip',
            buttons: [
                {
                    extend: 'copy',
                    text: '<i class="fas fa-copy"></i> Copy',
                    className: 'btn btn-light btn-sm border-primary text-primary'
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'btn btn-light btn-sm border-success text-success',
                    title: 'PB IDM MENTAH ' + today,
                    filename: 'PB_IDM_MENTAH_' + today,

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
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Print',
                    className: 'btn btn-light btn-sm border-primary text-primary'
                },
                {
                    text: '<i class="fas fa-sync-alt"></i> Refresh',
                    className: 'btn btn-light btn-sm border-info text-info',
                    action: function (e, dt, node, config) {
                        // Memuat ulang seluruh halaman
                        window.location.reload();
                    }
                }
            ],
            scrollX: true,
            pageLength: 15,
            lengthMenu: [[15, 40, 60, -1], [15, 40, 60, "Semua"]],
            language: {
                url: "{{ asset('adminlte/plugins/datatables/i18n/id.json') }}"
            }
        });
        table2.buttons().container().appendTo('#exportButtons2');
    });
</script>
@endpush
