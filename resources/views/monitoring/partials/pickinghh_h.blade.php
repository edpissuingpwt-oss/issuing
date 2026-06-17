@push('styles')
@endpush

<div class="card modern-card shadow-lg border-0 mt-4">
    <div class="card-body modern-body">
            <div id="exportButtons"></div>
        <div class="table-responsive">
            <table id="example1" class="table table-bordered table-striped" style="width:100%">
                <thead class="bg-gradient-info">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">TOTAL TOKO</th>
                        <th class="text-center">PLU</th>
                        <th class="text-center">DESKRIPSI</th>
                        <th class="text-center">QTY ORDER</th>
                        <th class="text-center">LPP</th>
                    </tr>
                </thead>
                <tbody>
                     @foreach($results as $i => $row)
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td class="text-center">{{ $row->toko }}</td>
                            <td class="text-center">{{ $row->plu }}</td>
                            <td class="text-center">{{ $row->deskripsi }}</td>
                            <td style="text-align: right">{{ $row->qtypcs }}</td>
                            <td style="text-align: right">{{ $row->lpp }}</td>
                        </tr>
                     @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@stack('styles')

@push('scripts')
<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>


<script>
$(document).ready(function() {
    var today = new Date().toISOString().slice(0, 10);
    var exportFilename = 'PICKING_HH_' + '{{ strtoupper($flag) }}' + '_' + today;

    var table1 = $('#example1').DataTable({
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
                title: exportFilename,
                filename: exportFilename
            }
        ],
        scrollX: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
        language: {
            url: "{{ asset('adminlte/plugins/datatables/i18n/id.json') }}"
        }
    });
    table1.buttons().container().appendTo('#exportButtons');
});
</script>
@endpush
  @stack('scripts')
