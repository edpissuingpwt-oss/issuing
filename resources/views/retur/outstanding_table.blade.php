@push('styles')
@endpush

<div class="card-body">
    <table id="example1" class="table table-bordered table-striped" style="width:100%">
    @if($flag == 'reguler')
        <thead class="bg-gradient-info">
            <tr>
                <th>No</th>
                <th>TANGGAL</th>
                <th>TIPE</th>
                <th>KODE</th>
                <th>NAMA TOKO</th>
                <th>NO NRB</th>
				<th>PLU IDM</th>
				<th>PLU IGR</th>
                <th>DESKRIPSI</th>
                <th>QTY</th>
                <th>PRICE</th>
                <th>NETTO</th>
                <th>PPN</th>
                <th>GROSS</th>
                <th>LOKASI</th>
                <th>STATUS</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 0; @endphp
            @foreach($result as $row)
            <tr>
                <td>{{ ++$no }}</td>
                <td>{{ $row->tanggal1 }}</td>
                <td>{{ $row->typer }}</td>
                <td>{{ $row->shop }}</td>
                <td>{{ $row->tko_namaomi }}</td>
                <td>{{ $row->docno }}</td>
                <td>{{ $row->pluidm }}</td>
                <td>{{ $row->prd_prdcd }}</td>
                <td>{{ $row->prd_deskripsipanjang }}</td>
                <td>{{ $row->qty }}</td>
                <td class="text-right">{{ number_format($row->price) }}</td>
                <td class="text-right">{{ number_format($row->netto) }}</td>
                <td class="text-right">{{ number_format($row->ppn) }}</td>
                <td class="text-right">{{ number_format($row->gross) }}</td>
                <td>{{ $row->lokasi }}</td>
                <td>{{ $row->status }}</td>
            </tr>
            @endforeach
        </tbody>
    @elseif($flag == 'proforma')
        <thead class="bg-gradient-info">
            <tr>
               <th>No</th>
                <th>TANGGAL</th>
                <th>TIPE</th>
                <th>KODE</th>
                <th>NAMA TOKO</th>
                <th>NO NRB</th>
				<th>PLU IDM</th>
				<th>PLU IGR</th>
                <th>DESKRIPSI</th>
                <th>QTY</th>
                <th>PRICE</th>
                <th>NETTO</th>
                <th>PPN</th>
                <th>GROSS</th>
                <th>LOKASI</th>
                <th>STATUS</th> 
            </tr>
        </thead>
        <tbody>
            @php $no = 0; @endphp
            @foreach($result as $row)
            <tr>
                <td>{{ ++$no }}</td>
                <td>{{ $row->tanggal1 }}</td>
                <td>{{ $row->typer }}</td>
                <td>{{ $row->shop }}</td>
                <td>{{ $row->tko_namaomi }}</td>
                <td>{{ $row->docno }}</td>
                <td>{{ $row->pluidm }}</td>
                <td>{{ $row->prd_prdcd }}</td>
                <td>{{ $row->prd_deskripsipanjang }}</td>
                <td>{{ $row->qty }}</td>
                <td class="text-right">{{ number_format($row->price) }}</td>
                <td class="text-right">{{ number_format($row->netto) }}</td>
                <td class="text-right">{{ number_format($row->ppn) }}</td>
                <td class="text-right">{{ number_format($row->gross) }}</td>
                <td>{{ $row->lokasi }}</td>
                <td>{{ $row->status }}</td>
            </tr>
            @endforeach
        </tbody>
    @elseif($flag == 'other')
        <thead class="bg-gradient-info">
            <tr>
                <th>No</th>
                <th>TANGGAL</th>
                <th>TIPE</th>
                <th>KODE</th>
                <th>NAMA TOKO</th>
                <th>NO NRB</th>
				<th>PLU IDM</th>
				<th>PLU IGR</th>
                <th>DESKRIPSI</th>
                <th>QTY</th>
                <th>PRICE</th>
                <th>NETTO</th>
                <th>PPN</th>
                <th>GROSS</th>
                <th>LOKASI</th>
                <th>STATUS</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 0; @endphp
            @foreach($result as $row)
            <tr>
               <td>{{ ++$no }}</td>
                <td>{{ $row->tanggal1 }}</td>
                <td>{{ $row->typer }}</td>
                <td>{{ $row->shop }}</td>
                <td>{{ $row->tko_namaomi }}</td>
                <td>{{ $row->docno }}</td>
                <td>{{ $row->pluidm }}</td>
                <td>{{ $row->prd_prdcd }}</td>
                <td>{{ $row->prd_deskripsipanjang }}</td>
                <td>{{ $row->qty }}</td>
                <td class="text-right">{{ number_format($row->price) }}</td>
                <td class="text-right">{{ number_format($row->netto) }}</td>
                <td class="text-right">{{ number_format($row->ppn) }}</td>
                <td class="text-right">{{ number_format($row->gross) }}</td>
                <td>{{ $row->lokasi }}</td>
                <td>{{ $row->status }}</td>
            </tr>
            @endforeach
        </tbody>
    @endif
    </table>
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
    var exportFilename = '_' + '{{ strtoupper($flag) }}' + '_' + today;

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
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Print',
                className: 'btn btn-light btn-sm border-primary text-primary'
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