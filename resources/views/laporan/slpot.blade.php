@extends('layouts.app')
@section('title', 'LAPORAN SERVICE LEVEL POT')

@push('styles')
<link rel="stylesheet" href="{{ asset('adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

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
    
    
</style>
@endpush

@section('content')
<section class="content">
<div class="container-fluid">

    <div class="card modern-card shadow-lg border-0">
    <div class="card-header modern-header text-white rounded-top">
        <h3 class="card-title fw-bold mb-0">
            {{-- <i class="fas fa-filter me-2"></i>  --}}
            LAPORAN SERVICE LEVEL POT
        </h3>
    </div>

    <form id="form_slpot">
        <div class="card-body modern-body">

            <!-- Jenis -->
            <div class="mb-4">
                <label for="jenis_select" class="form-label fw-semibold">Jenis :</label>
                <select id="jenis_select" name="jenis" class="form-control shadow-sm modern-select">
                    <option value="" disabled selected>Pilih Jenis</option>
                    <option value="tolak">Tolakan</option>
                    <option value="real">Realisasi</option>
                </select>
            </div>


            <!-- Tanggal -->
            <div class="mb-4">
                <label for="tanggal_input" class="form-label fw-semibold">Tanggal :</label>
                <div class="input-group modern-datepicker" id="tanggal_picker" data-target-input="nearest">
                    <input type="text" id="tanggal_input" name="tanggal"
                        class="form-control datetimepicker-input shadow-sm"
                        data-target="#tanggal_picker"
                        placeholder="Pilih tanggal"/>
                    <div class="input-group-append" data-target="#tanggal_picker" data-toggle="datetimepicker">
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
<script src="{{ asset('adminlte/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

<script>
    function getJenis() {
        const val = $('#jenis_select').val(); // idm / omi
        return val ? val.toUpperCase() : '';
    }

    function formatTanggalExcel(tgl) {
        // tgl = DD-MM-YYYY
        let parts = tgl.split('-'); // [DD, MM, YYYY]

        let day   = parts[0];
        let month = parseInt(parts[1], 10);
        let year  = parts[2];

        const bulan = ['Jan','Feb','Mar','Apr','May','Jun',
                    'Jul','Aug','Sep','Oct','Nov','Dec'];

        return `${day}-${bulan[month - 1]}-${year}`;
    }
    

$(document).ready(function () {
    // Inisialisasi Datepicker
    $('#tanggal_picker').datetimepicker({
        locale: 'id',
        format: 'DD-MM-YYYY',
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

    // Tombol Submit
    $('#submit_form').on('click', function () {
        let jenis = $('#jenis_select').val();
        let tanggal = $('#tanggal_input').val();

        if (!jenis) {
            alert('Silakan pilih jenis terlebih dahulu.');
            return;
        }
        if (!tanggal) {
            alert('Silakan pilih tanggal terlebih dahulu.');
            return;
        }

        $.ajax({
            url: "{{ route('slpot.fetch') }}",
            type: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                jenis: jenis,
                tanggal: tanggal
            },
            success: function (response) {
                $('#result_form').html(response).prop('hidden', false);

                // Reset DataTable jika sudah ada
                if ($.fn.dataTable && $.fn.dataTable.isDataTable('#example1')) {
                    $('#example1').DataTable().clear().destroy();
                }

                // var today = new Date().toISOString().slice(0, 10);
                let tglExcel = formatTanggalExcel(tanggal);
                // Inisialisasi DataTable dengan tombol styled export
                var table = $('#example1').DataTable({
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
                            title: function () {
                                    return 'SL POT ' + getJenis() + '_' + formatTanggalExcel(tanggal);
                                },
                            filename: function () {
                                    return 'SL POT ' + getJenis() + '_' + formatTanggalExcel(tanggal);
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

                // Pindahkan tombol export ke container custom jika mau
                table.buttons().container().appendTo('#exportButtons');
            }
        });
    });

    // Tombol Clear
    $('#clear_form').on('click', function () {
        $('#jenis_select').val('');
        $('#tanggal_input').val('');
        $('#result_form').prop('hidden', true).html('');
    });
});
</script>


@endpush
