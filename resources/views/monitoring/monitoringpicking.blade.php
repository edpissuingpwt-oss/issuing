@extends('layouts.app') 
@section('title', 'MONITORING PICKING')
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

@section('content')
    <div class="container-fluid">
    <div class="card modern-card shadow-lg border-0">
        <div class="card-header modern-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">
                {{-- <i class="fas fa-table"></i>  --}}
                MONITORING PICKING
            </h3>
            <div id="exportButtons2"></div>
        </div>
            <div class="card-body">
                <table id="example2" class="table table-bordered table-striped">
                    <thead class="bg-gradient-info">
                        <tr>
                            <th>No</th>
                            <th>TOKO</th>
                            <th>NO PB</th>
                            <th>KODE TOKO</th>
                            <th>NAMA TOKO</th>
                            <th>NO SJ</th>
                            <th>NO PICKING</th>
                            <th>REALISASI 0</th>
                            <th>SEBAGIAN</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($monitoringData as $index => $row)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $row->tko_namasbu }}</td>
                                <td>{{ $row->hpbi_nopb }}</td>
                                <td>{{ $row->hpbi_kodetoko }}</td>
                                <td>{{ $row->tko_namaomi }}</td>
                                <td>{{ $row->hpbi_nosj }}</td>
                                <td>{{ $row->hpbi_nopicking }}</td>
                                <td>{{ $row->realisasi_0 }}</td>
                                <td>{{ $row->sebagian }}</td>
                                <td class="project-actions">
                                    <button type="button" class="btn btn-primary btn-sm printer" data-toggle="modal" data-target="#modal-xl">
                                        <i class="fas fa-eye"></i>
                                        Lihat Item
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="modal-xl">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Realisasi 0 & Sebagian</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="modal-content-data"></div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <div id="dynamic-buttons-container"></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
{{-- Muat jQuery terlebih dahulu --}}
<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
{{-- Kemudian, muat pustaka DataTables inti --}}
<script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
{{-- Muat ekstensi DataTables --}}
<script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

{{-- Muat pustaka Buttons dan dependensinya --}}
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

<script>
    $(document).ready(function(){
        // Auto-reload the page every 60 seconds
        // window.setTimeout(function() {
        //     window.location.reload();
        // }, 60000);

        // ==========================================================
        // DataTables Initialization for the MAIN table (#example2)
        // ==========================================================
        $("#example2").DataTable({
            "responsive": true,
            "lengthChange": true, // Mengaktifkan menu "Show entries"
            "autoWidth": false,
            "paging": true, // Mengaktifkan pagination
            "searching": true, // Mengaktifkan search box
            "pageLength": 10 // Menampilkan 10 baris per halaman
        });

        // ==========================================================
        // Logic for the MODAL table (#example1)
        // ==========================================================
        $('#example2').on('click', '.printer', function() {
            let parent = this.parentElement.parentElement;
            let stt = parent.children[1].innerText;
            let kdToko = parent.children[3].innerText;
            let nmToko = parent.children[4].innerText;
            let noPick = parent.children[6].innerText;

            $.ajax({
                url: "{{ url('m_picking/show-item') }}",
                type: "POST",
                data: {
                    noPick: noPick,
                    kdToko: kdToko,
                    nmToko: nmToko,
                    statusToko: stt,
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    $('#modal-content-data').html(data);

                    if ($.fn.DataTable.isDataTable('#example1')) {
                        $('#example1').DataTable().destroy();
                    }
                    $('#dynamic-buttons-container').empty();

                    const table = $("#example1").DataTable({
                        "responsive": true,
                        "lengthChange": false,
                        "autoWidth": false,
                        "buttons": [{
                            extend: 'print',
                            text: '<i class="fas fa-print"></i> Print',
                            title: 'Item Realisasi Nol & Sebagian',
                            customize: function (win) {
                                $(win.document.body).find('h1').text('Item Realisasi Nol & Sebagian');
                                $(win.document.body).find('table').addClass('table-bordered').removeClass('table-hover');
                            }
                        }]
                    });

                    table.buttons().container().appendTo($('#dynamic-buttons-container'));
                    $('#modal-xl').modal('show');
                }
            });
        });

        // Destroy the modal's DataTable instance when the modal is closed
        $('#modal-xl').on('hidden.bs.modal', function () {
            if ($.fn.DataTable.isDataTable('#example1')) {
                $('#example1').DataTable().destroy();
            }
        });

    });
</script>
@endpush

@endsection