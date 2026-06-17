@extends('layouts.app') {{-- Asumsi Anda menggunakan layout bernama 'app' --}}
@section('title', 'RETUR IDM')
@section('content')
@push('styles')
<link rel="stylesheet" href="{{ asset('adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
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

    .status-option {
        cursor: pointer;
        transition: all 0.25s ease-in-out;
        border: 2px solid #e0e0e0;
        border-radius: 0.75rem;
        min-height: 120px;
        background: #fff;
        color: #333;
    }

    .status-option:hover {
        background-color: #f8f9fa;
        transform: scale(1.03);
    }

    /* Saat dipilih */
    input[type="radio"]:checked + .status-option {
        border: 2px solid #28a745;
        background: #28a745;
        color: #fff;
        box-shadow: 0 0 12px rgba(40, 167, 69, 0.3);
    }

    input[type="radio"]:checked + .status-option .icon i,
    input[type="radio"]:checked + .status-option h6 {
        color: #fff !important;
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

</style>
@endpush

<section class="content">
    <div class="container-fluid">

        <div class="card card-success">

            <div class="card-header small-box-issuing-header text-white d-flex justify-content-between align-items-center">
                <h3 class="card-title">RETUR IDM</h3>
            </div>
            <form action="" method="post">
                <div class="card-body">
                    <div class="form-group clearfix">
                        <h6><strong>Status :</strong></h6>
                        <div class="row text-center">

                        <!-- Belum Absen -->
                        <div class="col-md-3 mb-3">
                            <input type="radio" name="status" id="status1" value="belumabsen" hidden>
                            <label for="status1" class="status-option w-100 d-flex flex-column align-items-center justify-content-center p-3">
                            <div class="icon mb-2"><i class="fas fa-user-clock fa-2x"></i></div>
                            <h6 class="mb-0">Belum Absen</h6>
                            </label>
                        </div>

                        <!-- Selesai Absen -->
                        <div class="col-md-3 mb-3">
                            <input type="radio" name="status" id="status2" value="selesaiabsen" hidden>
                            <label for="status2" class="status-option w-100 d-flex flex-column align-items-center justify-content-center p-3">
                            <div class="icon mb-2"><i class="fas fa-user-check fa-2x"></i></div>
                            <h6 class="mb-0">Selesai Absen</h6>
                            </label>
                        </div>

                        <!-- Selesai Sortasi -->
                        <div class="col-md-3 mb-3">
                            <input type="radio" name="status" id="status3" value="selesaisortasi" hidden>
                            <label for="status3" class="status-option w-100 d-flex flex-column align-items-center justify-content-center p-3">
                            <div class="icon mb-2"><i class="fas fa-box-open fa-2x"></i></div>
                            <h6 class="mb-0">Selesai Sortasi</h6>
                            </label>
                        </div>

                        <!-- Selesai Proses -->
                        <div class="col-md-3 mb-3">
                            <input type="radio" name="status" id="status4" value="selesaiproses" hidden>
                            <label for="status4" class="status-option w-100 d-flex flex-column align-items-center justify-content-center p-3">
                            <div class="icon mb-2"><i class="fas fa-check-circle fa-2x"></i></div>
                            <h6 class="mb-0">Selesai Proses</h6>
                            </label>
                        </div>

                        </div>
                    </div>


                    <div class="form-group">
                        <label>Dari Tanggal :</label>
                        <div class="input-group date" id="reservationdate" data-target-input="nearest">
                            <input type="text" id="tanggal_mulai" class="form-control datetimepicker-input" data-target="#reservationdate" />
                            <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Sampai Tanggal :</label>
                        <div class="input-group date" id="reservationdate2" data-target-input="nearest">
                            <input type="text" id="tanggal_selesai" class="form-control datetimepicker-input" data-target="#reservationdate2" />
                            <div class="input-group-append" data-target="#reservationdate2" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="card-footer modern-footer d-flex justify-content-end button-group">
                <button type="button" class="btn modern-btn-submit px-4" id="submit_form">
                    <i class="fas fa-paper-plane me-1"></i> Submit
                </button>
                <button type="button" class="btn modern-btn-clear px-4" id="clear_form">
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
        // Inisialisasi datepicker
        $('#reservationdate').datetimepicker({
            format: 'DD-MM-YYYY'
        });
        $('#reservationdate2').datetimepicker({
            format: 'DD-MM-YYYY'
        });
    });

    // Tambahkan efek active pada card status
    $(document).on('change', 'input[name="status"]', function () {
        // hilangkan active di semua card
        $('.status-option').removeClass('active');

        // tambahkan active hanya di label yg terpilih
        if ($(this).is(':checked')) {
            $(this).closest('label').addClass('active');
        }

        // logika enable/disable datepicker
        let status = $(this).val();
        if (status === 'belumabsen' || status === 'selesaiabsen') {
            $('#tanggal_mulai, #tanggal_selesai').prop('disabled', true).val('');
        } else {
            $('#tanggal_mulai, #tanggal_selesai').prop('disabled', false);
        }
    });

    document.getElementById('submit_form').addEventListener('click', function () {
        let status = $('input[name="status"]:checked').val();
        let tanggal_mulai = $('#tanggal_mulai').val();
        let tanggal_selesai = $('#tanggal_selesai').val();

        if (!status) {
            alert('Harap pilih status!');
            return;
        }

        // validasi khusus untuk status yg butuh tanggal
        if ((status !== 'belumabsen' && status !== 'selesaiabsen') && (!tanggal_mulai || !tanggal_selesai)) {
            alert('Harap isi tanggal mulai & tanggal selesai!');
            return;
        }

        $.ajax({
            url: "{{ route('returidm.table') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                flag: status,
                tanggal_mulai: tanggal_mulai,
                tanggal_selesai: tanggal_selesai
            },
            success: function (data) {
                $('#result_form').html(data);
                document.getElementById('result_form').hidden = false;

                // Ambil nama status terpilih dari label <h6>
                let selectedStatus = $('input[name="status"]:checked').attr('id');
                let statusText = $('label[for="' + selectedStatus + '"] h6').text().trim();


                // Ambil tanggal hari ini (format ddMMyyyy)
                let today = new Date();
                let dd = String(today.getDate()).padStart(2, '0');
                let mm = String(today.getMonth() + 1).padStart(2, '0'); // Januari = 0
                let yyyy = today.getFullYear();
                let todayStr = dd + mm + yyyy;

                // Inisialisasi ulang DataTables
                $("#example1").DataTable({
                    "destroy": true, // penting supaya bisa re-init setelah AJAX
                    "responsive": true,
                    "autoWidth": false,
                    "searching": true,
                    "buttons": [
                        {
                            extend: 'copy',
                            text: '<i class="fas fa-copy"></i> Copy',
                            className: 'btn btn-light btn-sm border-primary text-primary'
                        },
                        {
                            extend: 'excel',
                            text: '<i class="fas fa-file-excel"></i> Excel',
                            className: 'btn btn-light btn-sm border-success text-success',
                            title: statusText + " " + todayStr,
                            filename: statusText.replace(/\s+/g, '_') + "_" + todayStr
                        }
                    ]
                }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            }

        });
    });

    document.getElementById('clear_form').addEventListener('click', function () {
        document.getElementById('result_form').hidden = true;
        $('[type="radio"]').prop('checked', false);
        $('.status-option').removeClass('active'); // reset card
        $('#tanggal_mulai, #tanggal_selesai').prop('disabled', false).val('');
    });
</script>

@endpush
@endsection