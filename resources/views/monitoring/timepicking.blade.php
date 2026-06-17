@extends('layouts.app')
@section('title', 'Time Picking')
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

    <div class="card modern-card shadow-lg border-0">
    <div class="card-header modern-header d-flex justify-content-between align-items-center">
        <h3 class="card-title fw-bold mb-0">
            <i class="fas fa-clock me-2"></i> Time Picking
        </h3>
    </div>
    <form id="form_timepicking">
        <div class="card-body modern-body">


            <div class="mb-4">
                    <label class="block text-lg font-semibold mb-3"></label>
                    <div class="picker-cards">

                        <!-- Reguler -->
                        <input type="radio" name="status" id="radioOmi" value="omi" checked>
                        <label for="radioOmi" class="picker-card">
                            <h5>
                                {{-- <i class="fas fa-copy"></i>  --}}
                                TOKO OMI </h5>
                        </label>

                        <!-- Pot -->
                        <input type="radio" name="status" id="radioIdm" value="idm">
                        <label for="radioIdm" class="picker-card">
                            <h5>
                                {{-- <i class="fas fa-paste"></i> --}}
                                TOKO IDM </h5>
                        </label>

                    </div>
                </div>

            <!-- Dari Tanggal -->
            <div class="mb-4">
                <label for="tanggal_mulai_input" class="form-label fw-semibold">Dari Tanggal:</label>
                <div class="input-group modern-datepicker" id="tanggal_mulai_picker" data-target-input="nearest">
                    <input type="text" id="tanggal_mulai_input" name="tanggal_mulai"
                        class="form-control datetimepicker-input shadow-sm"
                        data-target="#tanggal_mulai_picker"
                        placeholder="Pilih tanggal mulai"/>
                    <div class="input-group-append" data-target="#tanggal_mulai_picker" data-toggle="datetimepicker">
                        <span class="input-group-text bg-primary text-white">
                            <i class="fa fa-calendar"></i>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Sampai Tanggal -->
            <div class="mb-4">
                <label for="tanggal_selesai_input" class="form-label fw-semibold">Sampai Tanggal:</label>
                <div class="input-group modern-datepicker" id="tanggal_selesai_picker" data-target-input="nearest">
                    <input type="text" id="tanggal_selesai_input" name="tanggal_selesai"
                        class="form-control datetimepicker-input shadow-sm"
                        data-target="#tanggal_selesai_picker"
                        placeholder="Pilih tanggal selesai"/>
                    <div class="input-group-append" data-target="#tanggal_selesai_picker" data-toggle="datetimepicker">
                        <span class="input-group-text bg-primary text-white">
                            <i class="fa fa-calendar"></i>
                        </span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Footer -->
        <div class="card-footer modern-footer d-flex justify-content-end button-group">
            <button type="button" class="btn modern-btn-submit px-4" id="submit_form">
                <i class="fas fa-paper-plane me-1"></i> Submit
            </button>
            <button type="button" class="btn modern-btn-clear px-4" id="clear_form">
                <i class="fas fa-times me-1"></i> Clear
            </button>
        </div>
    </form>
</div>

<!-- Result -->
<div class="card modern-card mt-4" id="result_form" hidden></div>

</div>
</section>

@endsection

@push('scripts')
<script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/moment/locale/id.js') }}"></script>
<script src="{{ asset('adminlte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>

<script>
// Inisialisasi datetimepicker
function setupDatepicker(selector) {
    
    $(selector).datetimepicker({
        locale: 'id',
        format: 'DD-MM-YYYY',
        showClose: true,
        showClear: true,
        icons: {
            time: 'far fa-clock',
            date: 'far fa-calendar',
            up: 'fas fa-chevron-up',
            down: 'fas fa-chevron-down',
            previous: 'fas fa-chevron-left',
            next: 'fas fa-chevron-right',
            today: 'fas fa-calendar-day',
            clear: 'far fa-trash-alt',
            close: 'fas fa-times'
        }
    });
}

$(document).ready(function () {
    setupDatepicker('#tanggal_mulai_picker');
    setupDatepicker('#tanggal_selesai_picker');

    // Sinkronisasi tanggal mulai & selesai
    $("#tanggal_mulai_picker").on("change.datetimepicker", function (e) {
        if (e.date) {
            $('#tanggal_selesai_picker').datetimepicker('minDate', e.date);
        } else {
            $('#tanggal_selesai_picker').datetimepicker('minDate', false); // reset batas
        }
    });

    $("#tanggal_selesai_picker").on("change.datetimepicker", function (e) {
        if (e.date) {
            $('#tanggal_mulai_picker').datetimepicker('maxDate', e.date);
        } else {
            $('#tanggal_mulai_picker').datetimepicker('maxDate', false); // reset batas
        }
    });

    $('#submit_form').on('click', function () {
        let status = $('input[name="status"]:checked').val();
        let tanggal_mulai = $('#tanggal_mulai_input').val();
        let tanggal_selesai = $('#tanggal_selesai_input').val();

        // Validasi
        if (!status) {
            alert('Silakan pilih status (OMI atau IDM) terlebih dahulu.');
            return; // hentikan proses
        }
        if (!tanggal_mulai) {
            alert('Silakan pilih tanggal mulai.');
            return;
        }
        if (!tanggal_selesai) {
            alert('Silakan pilih tanggal selesai.');
            return;
        }

        // Jika lolos validasi → jalankan AJAX
        $.ajax({
            url: "{{ route('timepicking.fetch') }}",
            type: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                flag: status,
                tanggal_mulai: tanggal_mulai,
                tanggal_selesai: tanggal_selesai
            },
            success: function (response) {
                $('#result_form').html(response).prop('hidden', false);
                if ($.fn.DataTable.isDataTable('#example1')) {
                    $('#example1').DataTable().clear().destroy();
                }
                $('#example1').DataTable({
                    responsive: true,
                    autoWidth: false,
                    paging: true,
                    searching: true
                });
            }
        });
    });

    $('#clear_form').on('click', function () {
        $('input[name="status"]').prop('checked', false);
        $('#tanggal_mulai_input, #tanggal_selesai_input').val('');
        $('#tanggal_mulai_picker').datetimepicker('maxDate', false);
        $('#tanggal_selesai_picker').datetimepicker('minDate', false);
        $('#result_form').prop('hidden', true).html('');
    });
});
</script>
@endpush
