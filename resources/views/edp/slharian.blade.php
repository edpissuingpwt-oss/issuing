@extends('layouts.app')
@section('title', 'LAPORAN SERVICE LEVEL')

@push('styles')
<link rel="stylesheet" href="{{ asset('adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush

@section('content')
<section class="content">
<div class="container-fluid">

    <!-- CARD FORM -->
    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title mb-0">LAPORAN SERVICE LEVEL</h3>
        </div>

        <form id="form_itemcp1">
            <div class="card-body">

                <!-- Jenis -->
                <div class="form-group">
                    <label>Toko</label>
                    <select name="toko" id="toko_select" class="form-control">
                        <option value="IDM">IDM</option>
                        <option value="OMI">OMI</option>
                    </select>
                </div>

                <!-- Tanggal -->
                <div class="form-group">
                    <label>Tanggal</label>
                    <div class="input-group date" id="tanggal_picker" data-target-input="nearest">
                        <input type="text" id="tanggal_input" name="tanggal_mulai"
                               class="form-control datetimepicker-input"
                               data-target="#tanggal_picker"
                               placeholder="Pilih tanggal"/>
                        <div class="input-group-append" data-target="#tanggal_picker" data-toggle="datetimepicker">
                            <div class="input-group-text bg-primary text-white">
                                <i class="fa fa-calendar"></i>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Footer -->
            <div class="card-footer text-right">
                <button type="button" class="btn btn-primary" id="submit_form">
                    Submit
                </button>
                <button type="button" class="btn btn-danger" id="clear_form">
                    Clear
                </button>
            </div>
        </form>
    </div>

    <!-- RESULT -->
    <div class="card mt-4" id="result_form" hidden></div>

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

<script>
$(document).ready(function () {

    // Datepicker
    $('#tanggal_picker').datetimepicker({
        locale: 'id',
        format: 'DD-MM-YYYY'
    });

    function formatTanggalExcel(tgl) {
        let parts = tgl.split('-');
        let bulan = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        return parts[0] + '-' + bulan[parseInt(parts[1]) - 1] + '-' + parts[2];
    }

    // SUBMIT
    $('#submit_form').click(function () {

        let toko = $('#toko_select').val();
        let tanggal = $('#tanggal_input').val();

        if (!toko) {
            alert('Pilih toko terlebih dahulu');
            return;
        }

        if (!tanggal) {
            alert('Pilih tanggal terlebih dahulu');
            return;
        }

        $.ajax({
                url: "{{ route('slharian.table') }}",
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    toko: toko,
                    tanggal_mulai: tanggal
                },
            success: function (response) {

                $('#result_form').html(response).prop('hidden', false);

                // Reset DataTable
                if ($.fn.DataTable.isDataTable('#example1')) {
                    $('#example1').DataTable().destroy();
                }

                // Init DataTable
                $('#example1').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy',
                        {
                            extend: 'excelHtml5',
                            title: 'SL HARIAN ' + jenis.toUpperCase() + '_' + formatTanggalExcel(tanggal),
                            filename: 'SLHARIAN_' + jenis + '_' + tanggal
                        }
                    ],
                    scrollX: true
                });
            }
        });

    });

    // CLEAR
    $('#clear_form').click(function () {
        $('#jenis_select').val('');
        $('#tanggal_input').val('');
        $('#result_form').html('').prop('hidden', true);
    });

});
</script>
@endpush