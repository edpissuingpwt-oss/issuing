@extends('layouts.app')
@section('title', 'LAPORAN TOLAKAN IDM')

@push('styles')
<link rel="stylesheet" href="{{ asset('adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
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
.header-bold {
    font-weight: 800 !important; /* benar-benar tebal */
    font-size: 1rem;
    background: linear-gradient(90deg, #11998e, #11998e);
    color: #fff !important;
    border-top-left-radius: 1rem !important;
    border-top-right-radius: 1rem !important;
    letter-spacing: 0.5px;
    text-shadow: 0 1px 2px rgba(0,0,0,0.1);
}
.card {
    border-radius: 1rem;
}
.form-select {
    border-radius: 0.75rem;
}
.text-bold {
    font-weight: 800 !important; /* benar-benar tebal */
    font-size: 1rem;
    color: #fff !important;
    border-top-left-radius: 1rem !important;
    border-top-right-radius: 1rem !important;
    letter-spacing: 0.5px;
    text-shadow: 0 1px 2px rgba(0,0,0,0.1);
}
</style>
@endpush

@section('content')
<section class="content">
<div class="container-fluid">

    <div class="card modern-card shadow-lg border-0">
    <div class="card-header modern-header text-white rounded-top text-center fw-bold">
        <h3 class="card-title fw-bold mb-0">
            {{-- <i class="fas fa-filter me-2"></i>  --}}
            LAPORAN TOLAKAN IDM
        </h3>
    </div>

    <form id="form_slpot">
        <div class="card-body modern-body">

            <!-- Keterangan & Jenis Laporan dalam 1 baris -->
            <div class="row mb-4">
                <div class="col-md-12">
                        <div class="card-body">
                            <select class="form-control form-select" name="filter" id="filter">
                                <option value="1">QTY ORDER < MINOR TOKO</option>
                                <option value="2">AVG.COST IS NULL</option>
                                <option value="3">TAG_IGR IN('A','H','O','N','T','V','I')</option>
                                <option value="4">TIDAK PUNYA LOKASI</option>
                                <option value="5">PLU NULL</option>
                                <option value="6">BARKOS</option>
                            </select>
                        </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="card-footer modern-footer d-flex justify-content-end button-group">
            <button type="button" class="btn modern-btn-submit px-4" id="submit_form">
                <i class="fas fa-paper-plane me-1"></i> Submit
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
<script src="{{ asset('adminlte/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

<script>
$(function () {
    // -------------------------
    // Safety check plugin
    // -------------------------
    if (typeof $.fn.datetimepicker === 'undefined') {
        console.error('Datetimepicker plugin tidak ditemukan. Pastikan file tempusdominus-bootstrap-4.min.js dimuat dan jquery ada sebelum plugin.');
        return;
    }

    var startWrapper = $('#reservationdate');
    var startInput   = $('#tanggal_mulai');

    // helper untuk destroy jika sudah di-init sebelumnya (beberapa versi plugin menyimpan data berbeda)
    try {
        if (startWrapper && startWrapper.data('DateTimePicker')) {
            startWrapper.datetimepicker('destroy');
            console.log('Destroyed existing startPicker (DateTimePicker)');
        }
    } catch(e){ /* ignore */ }

    try {
        if (startWrapper && startWrapper.data('datetimepicker')) {
            startWrapper.datetimepicker('destroy');
            console.log('Destroyed existing startPicker (datetimepicker)');
        }
    } catch(e){ /* ignore */ }

    // -------------------------
    // Common options
    // -------------------------
    var commonOpts = {
        format: 'DD-MM-YYYY',
        locale: 'id',
        allowInputToggle: true, // memastikan klik input membuka widget
        icons: {
            time: 'far fa-clock',
            date: 'far fa-calendar',
            up: 'fas fa-chevron-up',
            down: 'fas fa-chevron-down',
            previous: 'fas fa-chevron-left',
            next: 'fas fa-chevron-right',
            today: 'far fa-calendar-check',
            clear: 'far fa-trash-alt',
            close: 'far fa-times-circle'
        }
    };

    // -------------------------
    // Init pickers (defensif)
    // -------------------------
    try {
        startWrapper.datetimepicker($.extend({}, commonOpts, { useCurrent: true }));
    } catch (e) {
        console.error('Gagal inisialisasi startPicker:', e);
    }


    // Pastikan z-index widget tinggi ketika muncul (mengatasi overlay)
    function bumpZindex() {
        try {
            $('.tempus-dominus-widget, .bootstrap-datetimepicker-widget, .xdsoft_datetimepicker').css('z-index', 99999);
        } catch(e){ /* ignore */ }
    }

    startWrapper.on('show.datetimepicker shown.datetimepicker datetimepicker.show dp.show', function(){
        bumpZindex();
    });

    // -------------------------
    // Sync range (fallback untuk beberapa event name)
    // -------------------------
    function onStartChange(e) {
        try {
            if (e && e.date) {
                endWrapper.datetimepicker('minDate', e.date);
            } else {
                // jika kosong, reset minDate
                endWrapper.datetimepicker('minDate', false);
            }
        } catch(err) {
            console.warn('onStartChange error:', err);
        }
    }
    function onEndChange(e) {
        try {
            if (e && e.date) {
                startWrapper.datetimepicker('maxDate', e.date);
            } else {
                startWrapper.datetimepicker('maxDate', false);
            }
        } catch(err) {
            console.warn('onEndChange error:', err);
        }
    }

    // dua variasi event name untuk kompatibilitas plugin berbeda
    startWrapper.on('change.datetimepicker dp.change', onStartChange);

    // -------------------------
    // Force-open on input focus / click (lebih handal)
    // -------------------------
    startInput.on('focus click', function () {
        try {
            startWrapper.datetimepicker('show');
        } catch(e){
            // fallback: trigger append click (versi HTML lama)
            startWrapper.find('.input-group-append').trigger('click');
        }
    });

    // -------------------------
    // Parse manual typing on blur (validasi)
    // -------------------------
    startInput.on('blur', function(){
        var v = $(this).val();
        if (!v) {
            try { startWrapper.datetimepicker('clear'); } catch(e){}
            return;
        }
        var m = moment(v, 'DD-MM-YYYY', true);
        if (m.isValid()) {
            try { startWrapper.datetimepicker('date', m); } catch(e){
                try { startWrapper.data('DateTimePicker') && startWrapper.data('DateTimePicker').date(m); } catch(e2){}
            }
        } else {
            console.warn('Tanggal mulai tidak valid:', v);
        }
    });

    // -------------------------
    // Submit button (tetap sama)
    // -------------------------
    $('#submit_form').on('click', function () {
        let filter = $('#filter').val();


        $('#submit_form').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...');
        $('#result_form').html('<div class="p-4 text-center text-muted"><i class="fas fa-spinner fa-spin fa-2x mb-3"></i><br>Memuat data...</div>').prop('hidden', false);

        $.ajax({
            url: "{{ route('tolakanidm.table') }}",
            type: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                filter: filter
            },
            success: function (response) {
                $('#result_form').html(response).prop('hidden', false);

                if ($('#example1').length) {
                    if ($.fn.dataTable.isDataTable('#example1')) {
                        $('#example1').DataTable().destroy();
                    }
                    var today = moment().format('YYYYMMDD_HHmmss');
                    $('#example1').DataTable({
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
                                title: 'Laporan Tolakan PB',
                                filename: 'Laporan_Tolakan_PB_' + today
                            }
                        ],
                        scrollX: true,
                        pageLength: 10,
                        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
                        language: {
                            url: "{{ asset('adminlte/plugins/datatables/i18n/id.json') }}"
                        }
                    });
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText || error);
                $('#result_form').html('<div class="alert alert-danger mt-3">Terjadi kesalahan saat memuat data. Silakan coba lagi.</div>');
            },
            complete: function () {
                $('#submit_form').prop('disabled', false).html('<i class="fas fa-paper-plane me-1"></i> Submit');
            }
        });
    });

}); // ready
</script>
@endpush
