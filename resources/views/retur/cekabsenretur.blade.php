@extends('layouts.app')
@section('title', 'CEK USER ABSEN RETUR')
@section('content')
@push('styles')
<link rel="stylesheet" href="{{ asset('adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<style>
/* =========================
   1. HIDE SIDEBAR ADMINLTE
========================= */
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

/* =========================
   2. CARD & LAYOUT MODERN
========================= */
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
.modern-header {
    background: linear-gradient(45deg, #007bff, #00c6ff);
    color: #fff !important;
    border-bottom: none;
    padding: 0.75rem 1rem;
    border-radius: 0.75rem 0.75rem 0 0;
}

/* =========================
   3. BUTTON MODERN
========================= */
.modern-btn-submit, .modern-btn-clear {
    padding: 0.6rem 1.8rem;
    font-weight: 700;
    border-radius: 0.75rem;
    font-size: 1rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    border: none;
}
.modern-btn-submit {
    background: linear-gradient(45deg, #007bff, #00c6ff);
    color: white;
    box-shadow: 0 8px 20px rgba(0,123,255,0.4);
}
.modern-btn-submit:hover, .modern-btn-submit:focus {
    background: linear-gradient(45deg, #0056b3, #0099cc);
    box-shadow: 0 10px 25px rgba(0,86,179,0.6);
    outline: none;
    transform: translateY(-2px);
}
.modern-btn-clear {
    background: linear-gradient(45deg, #f44336, #e57373);
    color: white;
    box-shadow: 0 8px 20px rgba(244,67,54,0.4);
}
.modern-btn-clear:hover, .modern-btn-clear:focus {
    background: linear-gradient(45deg, #c62828, #ef5350);
    box-shadow: 0 10px 25px rgba(198,40,40,0.6);
    outline: none;
    transform: translateY(-2px);
}
.modern-btn-submit i, .modern-btn-clear i {
    font-size: 1.1rem;
}
.button-group {
    gap: 1rem;
}
.button-group .btn {
    margin-left: 1rem;
}

/* =========================
   4. RADIO & PICKER CARD
========================= */
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
input#radioOmi:checked + .picker-card,
input#radioIdm:checked + .picker-card {
    background: linear-gradient(135deg, #007bff, #00c6ff);
    color: white;
    box-shadow: 0 6px 15px rgba(0, 123, 255, 0.4);
    border-color: #007bff;
}
input#radioOmi:not(:checked) + .picker-card,
input#radioIdm:not(:checked) + .picker-card {
    background: #f1f6ff;
    color: #007bff;
}
.picker-card:hover {
    transform: translateY(-4px);
}
.picker-cards input[type="radio"] {
    display: none;
}

/* =========================
   5. TEMPUS DOMINUS DATEPICKER
========================= */
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

/* =========================
   6. DARK MODE
========================= */
[data-theme="dark"] .modern-body {
    background: #2a2a3d;
    color: #f1f1f1;
}
[data-theme="dark"] .modern-footer {
    background: #1e1e2f;
}
[data-theme="dark"] .modern-header {
    background: linear-gradient(45deg, #1e3c72, #2a5298);
    color: #fff !important;
}
[data-theme="dark"] input#radioOmi:not(:checked) + .picker-card,
[data-theme="dark"] input#radioIdm:not(:checked) + .picker-card {
    background: #1f2a3a;
    color: #6cb4ff;
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

/* =========================
   7. DATATABLES DARK MODE
========================= */
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


</style>
@endpush

<section class="content">
    <div class="container-fluid">

        <div class="card card-info">
            <div class="card-header small-box-issuing-header text-white d-flex justify-content-between align-items-center">
                <h3 class="card-title">CEK USER ABSEN RETUR</h3>
            </div>
            <form id="form_itempicking">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <label for="kodetoko">KODE TOKO :</label>
                            <input type="text" id="kodetoko" name="kodetoko" class="form-control" placeholder="Masukkan Kode Toko">
                        </div>
                    </div>
                </div>
            </form>
            <form id="form_itempicking">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <label for="nonrb">NO NRB :</label>
                            <input type="text" id="nonrb" name="nonrb" class="form-control" placeholder="Masukkan No NRB">
                        </div>
                    </div>
                </div>
            </form>
            <div class="card-footer modern-footer d-flex justify-content-end button-group">
                <button type="button" class="btn modern-btn-submit px-4 d-flex align-items-center" id="submit_form">
                    <i class="fas fa-paper-plane"></i> Submit
                </button>
                <button type="button" class="btn modern-btn-clear px-4 d-flex align-items-center" id="clear_form">
                    <i class="fas fa-times"></i> Clear
                </button>
            </div>
        </div>

        <div class="card" id="result_form" hidden>
        </div>
    </div>
</section>
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
    $('#submit_form').on('click', function () {
        let formData = {
            kodetoko: $('#kodetoko').val(),
            nonrb: $('#nonrb').val()
        };

        $.ajax({
            url: "{{ route('cekabsenretur.table') }}",
            type: "POST",
            data: formData,
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            success: function (data) {
                $('#result_form').html(data).prop('hidden', false);
            }
        });
    });

    $('#clear_form').on('click', function () {
        $('#result_form').prop('hidden', true).html('');
        $('#kodetoko').val('');
        $('#nonrb').val('');
    });
</script>
@endpush
