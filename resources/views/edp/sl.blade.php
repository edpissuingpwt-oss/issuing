@extends('layouts.app') {{-- Asumsi Anda menggunakan layout bernama 'app' --}}
@section('title', 'LAPORAN SL HARIAN')
@section('content')
@push('styles')
<link rel="stylesheet" href="{{ asset('adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

<style>
/* Hilangkan sidebar adminlte */
.main-sidebar, .main-sidebar *, .nav-sidebar {
  display: none !important;
}
body.sidebar-mini .content-wrapper,
.content-wrapper {
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
/* Dark mode for tempus dominus month picker */
[data-theme="dark"] .tempus-dominus-widget {
    background-color: #1e1e2f !important;
    color: #f1f1f1 !important;
    border: 1px solid #444 !important;
}
[data-theme="dark"] .tempus-dominus-widget .table-condensed th,
[data-theme="dark"] .tempus-dominus-widget .table-condensed td {
    color: #f1f1f1 !important;
}
[data-theme="dark"] .tempus-dominus-widget .datepicker-months .table-condensed tbody span {
    background-color: #2a2a3d !important;
    color: #f1f1f1 !important;
    border: 1px solid transparent !important;
}
[data-theme="dark"] .tempus-dominus-widget .datepicker-months .table-condensed tbody span:hover {
    background-color: #007bff !important;
    color: #fff !important;
}
[data-theme="dark"] .tempus-dominus-widget .datepicker-months .table-condensed tbody span.active {
    background-color: linear-gradient(45deg, #007bff, #00c6ff) !important;
    color: #fff !important;
}
[data-theme="dark"] .tempus-dominus-widget .datepicker-months .table-condensed thead .picker-switch {
    background: linear-gradient(45deg, #1e3c72, #2a5298) !important;
    color: #fff !important;
}
[data-theme="dark"] .tempus-dominus-widget .datepicker-months .table-condensed thead th.prev,
[data-theme="dark"] .tempus-dominus-widget .datepicker-months .table-condensed thead th.next {
    background: #2a2a3d !important;
    color: #fff !important;
    border-radius: 0.5rem !important;
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
.modern-btn-excel {
    background: #008000;
    color: #fff;
    border: none;
    border-radius: 0.5rem;
    font-weight: 600;
}
.modern-btn-excel:hover {
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

/* Dark Mode Table */
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
[data-theme="dark"] table.dataTable tbody tr {
    border-bottom: 1px solid #333;
}
[data-theme="dark"] table.dataTable tbody tr:hover {
    background-color: #2d2d3f;
}
[data-theme="dark"] .dataTables_wrapper .dataTables_filter input,
[data-theme="dark"] .dataTables_wrapper .dataTables_length select {
    background-color: #1e1e2f;
    color: #fff;
    border: 1px solid #555;
}
[data-theme="dark"] .dataTables_wrapper .dataTables_paginate .paginate_button {
    color: #fff !important;
    background-color: #2e2e3e !important;
    border: 1px solid #555 !important;
}
[data-theme="dark"] .dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background-color: #444 !important;
    color: #fff !important;
}
[data-theme="dark"] .table-striped tbody tr:nth-of-type(odd) {
    background-color: #232334 !important;
}
[data-theme="dark"] .table-striped tbody tr:nth-of-type(even) {
    background-color: #1e1e2f !important;
}
[data-theme="dark"] .dataTables_wrapper .dataTables_length,
[data-theme="dark"] .dataTables_wrapper .dataTables_filter,
[data-theme="dark"] .dataTables_wrapper .dt-buttons {
    background-color: #1e1e2f !important;
    color: #fff !important;
    padding: 0.5rem;
    border-radius: 0.25rem;
}
[data-theme="dark"] .dataTables_wrapper .dataTables_filter input {
    background-color: #2b2b3c !important;
    color: #fff !important;
    border: 1px solid #555 !important;
}
[data-theme="dark"] .dataTables_wrapper .dataTables_length select {
    background-color: #2b2b3c !important;
    color: #fff !important;
    border: 1px solid #555 !important;
}
[data-theme="dark"] .dataTables_wrapper .dt-buttons .dt-button {
    background-color: #333 !important;
    color: #fff !important;
    border: 1px solid #555 !important;
}
[data-theme="dark"] .dt-button-info {
    background-color: #2b2b3c !important;
    color: #ffffff !important;
    border: 1px solid #555 !important;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
}
[data-theme="dark"] .dt-button-info h2 {
    color: #ffffff !important;
    border-bottom: 1px solid #666 !important;
}
[data-theme="dark"] .dt-button-info > div {
    color: #ffffff !important;
}

/* Radio button */
.form-check-input {
    transform: scale(1.2);
    margin-top: 2px;
}
.form-check-label {
    font-size: 0.95rem;
}
.radio-group .form-check {
    margin-right: 2rem;
}
.button-group .btn {
    margin-left: 1rem;
}

/* Header Modern */
.modern-header {
    background: linear-gradient(45deg, #007bff, #00c6ff);
    color: #fff !important;
    border-bottom: none;
    padding: 0.75rem 1rem;
    border-radius: 0.75rem 0.75rem 0 0;
}
[data-theme="dark"] .modern-header {
    background: linear-gradient(45deg, #1e3c72, #2a5298);
    color: #fff !important;
}
/* Picker Cards */
.picker-cards {
    display: flex;
    gap: 1rem;
}
.picker-card {
    cursor: pointer;
    transition: all 0.25s ease;
    border: 2px solid transparent;
    border-radius: 1rem;
    text-align: center;
    padding: 1.5rem 1rem;
    flex: 1;
    box-shadow: 0 4px 8px rgba(0,0,0,0.05);
}
.picker-card h5 {
    font-weight: 700;
    margin: 0;
    font-size: 1.2rem;
}
input#radioSuccess2:checked + .picker-card,
input#radioSuccess1:checked + .picker-card {
    background: linear-gradient(135deg, #007bff, #00c6ff);
    color: white;
    box-shadow: 0 6px 15px rgba(0, 123, 255, 0.4);
    border-color: #007bff;
}
input#radioSuccess2:not(:checked) + .picker-card,
input#radioSuccess1:not(:checked) + .picker-card {
    background: #f1f6ff;
    color: #007bff;
}
[data-theme="dark"] input#radioSuccess2:not(:checked) + .picker-card,
[data-theme="dark"] input#radioSuccess1:not(:checked) + .picker-card {
    background: #1f2a3a;
    color: #6cb4ff;
}
.picker-card:hover {
    transform: translateY(-4px);
}
.picker-cards input[type="radio"] {
    display: none;
}

</style>

@endpush

<section class="content">
    <div class="container-fluid">

        <div class="card card-success">

            <div class="card-header small-box-issuing-header text-white d-flex justify-content-between align-items-center">
                <h3 class="card-title">LAPORAN SL HARIAN</h3>
            </div>
            <form action="" method="post">
                <div class="card-body modern-body">
                    <div class="mb-4">
                    <label class="block text-lg font-semibold mb-3"></label>
                    <div class="picker-cards">

                        <!-- IDM -->
                        <input type="radio" name="r3" id="radioSuccess2" value="idm" checked>
                        <label for="radioSuccess2" class="picker-card">
                            <h5>
                                {{-- <i class="fas fa-copy"></i> --}}
                                 IDM </h5>
                        </label>

                        <!-- Pot -->
                        <input type="radio" name="r3" id="radioSuccess1" value="omi">
                        <label for="radioSuccess1" class="picker-card">
                            <h5>
                                {{-- <i class="fas fa-paste"></i> --}}
                                 OMI </h5>
                        </label>

                    </div>
                    </div>  
                

                    <div class="form-group">
                        <label>Tanggal :</label>
                        <div class="input-group date" id="reservationdate" data-target-input="nearest">
                            <input type="text" id="tanggal_mulai" class="form-control datetimepicker-input" data-target="#reservationdate" />
                            <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                <div class="input-group-text bg-primary text-white"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            
           <div class="card-footer modern-footer d-flex justify-content-end align-items-center button-group">
                {{-- Form Export Excel --}}
                {{-- <form id="export_excel_form" action="{{ route('bpbr.export') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="flag" id="export_flag">
                    <input type="hidden" name="tanggal_mulai" id="export_mulai">
                    <input type="hidden" name="tanggal_selesai" id="export_selesai">
                    <button type="submit" class="btn modern-btn-excel px-4">
                        <i class="fas fa-file-excel"></i> Excel
                    </button>
                </form> --}}

                {{-- Tombol aksi --}}
                <button type="button" class="btn modern-btn-submit px-4 ms-2" id="submit_form">
                    <i class="fas fa-paper-plane me-1"></i> Submit
                </button>
                <button type="button" class="btn modern-btn-clear px-4 ms-2" id="clear_form">
                    <i class="fas fa-times me-1"></i> Clear
                </button>
            </div>

        <div class="card" id="result_form" hidden>
            {{-- Data tabel akan dimuat di sini --}}
        </div>
    </div>
    </section>

@push('scripts')
    {{-- 1. Muat jQuery terlebih dahulu karena dibutuhkan oleh plugin lain --}}
    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    
    {{-- 2. Muat Moment.js, ini adalah dependensi utama untuk datepicker --}}
    <script src="{{ asset('adminlte/plugins/moment/moment.min.js') }}"></script>
    
    {{-- 3. Muat Tempus Dominus, plugin datepicker itu sendiri --}}
    <script src="{{ asset('adminlte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    
    {{-- Your existing DataTables scripts --}}
    <script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>


<script>
$(function () {
    // Inisialisasi datetimepicker
    $('#reservationdate').datetimepicker({
        format: 'DD-MM-YYYY'
    });
    $('#reservationdate2').datetimepicker({
        format: 'DD-MM-YYYY'
    });
});

// Fungsi untuk load tabel via AJAX
function loadTable(status, tanggal_mulai) {
    $.ajax({
        url: "{{ route('brokoli.table') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            flag: status,
            tanggal_mulai: tanggal_mulai,
        },
        success: function(data) {
            $('#result_form').html(data).prop('hidden', false);

            // Buat nama file
            let statusUpper = status.toUpperCase();
            let filenameExcel = 'Jumlah_Bronjong_' + statusUpper + '_' + tanggal_mulai ;

            // Hapus DataTable lama biar tidak double init
            if ($.fn.DataTable.isDataTable("#example1")) {
                $("#example1").DataTable().clear().destroy();
            }

            // Inisialisasi DataTables
            let table = $("#example1").DataTable({
                responsive: true,
                autoWidth: false,
                searching: true,
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
                        title: 'JUMLAH BRONJONG ' + statusUpper,
                        filename: filenameExcel
                    }
                ]
            });

            table.buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

            // Kalau mode autoExport, langsung download Excel
            // if (autoExport) {
            //     table.button('.buttons-excel').trigger();
            // }
        }
    });
}

// Tombol submit
document.getElementById('submit_form').addEventListener('click', function() {
    let status = $('input[name="r3"]:checked').val();
    let tanggal_mulai = $('#tanggal_mulai').val();

    if (status && tanggal_mulai ) {
        loadTable(status, tanggal_mulai);
    } else {
        alert('Harap isi semua kolom!');
    }
});

// // Tombol export excel (Laravel-Excel, bukan DataTables)
// $('#export_excel_form').on('submit', function (e) {
//     let status = $('input[name="r3"]:checked').val();
//     let tanggal_mulai = $('#tanggal_mulai').val();

//     if (!status || !tanggal_mulai) {
//         e.preventDefault();
//         alert('Harap isi semua kolom sebelum export!');
//         return false;
//     }

//     // Set hidden input sebelum submit
//     $('#export_flag').val(status);
//     $('#export_mulai').val(tanggal_mulai);
// });

// Tombol clear
document.getElementById('clear_form').addEventListener('click', function() {
    document.getElementById('result_form').hidden = true;
    $('[type="radio"]').prop('checked', false);
    $('#tanggal_mulai').val(null);
});
</script>

@endpush
@endsection