@extends('layouts.app')
@section('title', 'ALOKASI BELUM TERPENUHI')
@section('content')
@push('styles')
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

<style>
    /* Styling tombol DataTables */
    .dt-buttons .btn {
        margin-right: 5px;
        font-size: 0.9rem;
        padding: 6px 12px;
    }

    /* Biar icon + teks jelas */
    .dt-buttons .btn i {
        margin-right: 4px;
    }

    /* Styling length menu ("Show entries") */
    .dataTables_length label,
    .dataTables_length select {
        font-size: 0.95rem;
    }

    /* Styling search box */
    .dataTables_filter label,
    .dataTables_filter input {
        font-size: 0.95rem;
    }

    /* Biar header tabel lebih tegas */
    table.dataTable thead th {
        font-size: 0.95rem;
        white-space: nowrap;
    }
</style>
@endpush

<div class="container-fluid">
    <div class="card">
        <div class="card-header small-box-issuing-header text-white d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">
                {{-- <i class="fas fa-table"></i> --}}
                ALOKASI BELUM TERPENUHI
            </h3>
            <div id="exportButtons2"></div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="piutang" class="table table-bordered table-striped" style="width:100%">
                    <thead class="bg-gradient-info">
                        <tr>
                            <th rowspan="2"style="text-align: center;">NO</th>
                            <th rowspan="2"style="text-align: center;">KODETOKO</th>
                            <th colspan="4" style="text-align: center;">PRODUK</th>
                            <th colspan="3" style="text-align: center;">QTY</th>
                            <th colspan="3" style="text-align: center;">RUPIAH</th>
                        </tr>
                        <tr>
                            <th>PLU IDM</th>
                            <th>PLU IGR</th>
                            <th>DESKRIPSI</th>
                            <th>FRAC</th>

                            <th>TARGET</th>
                            <th>REALISASI</th>
                            <th>SELISIH</th>

                            <th>TARGET</th>
                            <th>REALISASI</th>
                            <th>SELISIH</th>
                    </thead>
                    <tbody>
                        @php 
                        $totaltarget = 0;
                        $totalreal = 0;
                        $totalblm = 0;
                        @endphp
                        @foreach($results as $i => $row)
                            @php
                                    $totaltarget += $row->rph_target;
                                    $totalreal += $row->rph_real;
                                    $totalblm += $row->rph_selisih;
                                @endphp
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td class="text-center">{{ $row->kode_toko }}</td>
                            <td class="text-center">{{ $row->pluidm }}</td>
                            <td class="text-center">{{ $row->pluigr }}</td>
                            <td class="text-center">{{ $row->desk }}</td>
                            <td class="text-center">{{ $row->frac }}</td>

                            <td class="text-center">{{ $row->target_seasonal }}</td>
                            <td class="text-center">{{ $row->qty_realisasi }}</td>
                            <td class="text-center">{{ $row->belum_alokasi }}</td>

                            <td style="text-align: right">{{ number_format(round($row->rph_target), 0, ".", ",") }}</td>
                            <td style="text-align: right">{{ number_format(round($row->rph_real), 0, ".", ",") }}</td>
                            <td style="text-align: right">{{ number_format(round($row->rph_selisih), 0, ".", ",") }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="font-weight-bold bg-light">
                            <td colspan="9" class="text-center">TOTAL</td>
                            <td class="text-right">{{ number_format($totaltarget) }}</td>
                            <td class="text-right">{{ number_format($totalreal) }}</td>
                            <td class="text-right">{{ number_format($totalblm) }}</td>
                        </tr>
                    </tfoot>
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
        var today = new Date().toISOString().slice(0, 10);

        // Tabel 2: PB IDM TODAY (PBI)
        var table2 = $('#piutang').DataTable({
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
                    title: 'ALOKASI BELUM TERPENUHI',
                    filename: 'ALOKASI_BLM_TERPENUHI' + today
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
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
            language: {
                url: "{{ asset('adminlte/plugins/datatables/i18n/id.json') }}"
            }
        });
        table2.buttons().container().appendTo('#exportButtons2');
    });
</script>
@endpush
