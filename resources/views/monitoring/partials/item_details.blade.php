@push('styles')

<link rel="stylesheet" href="{{ asset('adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

{{-- <style>
    @media print {
    body {
        width: 80mm; /* A common width for thermal printers */
        margin: 0;
        padding: 0;
    }

    /* Additional styles for table, text, etc. */
}
</style> --}}

@endpush

<div class="card-body">
    <h6>TOKO : ( {{ $kdToko }} ) {{ $nmToko }}</h6>
    <h6>PICK : {{ $noPick }}</h6>
    <table id="example1" class="table table-bordered table-striped">
        <thead class="bg-gradient-info">
            <tr>
                <th>No</th>
                <th>ZONA</th>
                <th>PLU</th>
                <th>DESC</th>
                <th>ORDER</th>
                <th>REAL</th>
                <th>FRAC</th>
                <th>MINOR</th>
                <th>STATUS</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 0; @endphp
            @foreach ($itemData as $row)
                @php $no++; @endphp
                <tr>
                    <td>{{ $no }}</td>
                    <td>{{ $row->kodezona }}</td>
                    <td>{{ $row->plu }}</td>
                    <td>{{ $row->prd_deskripsipanjang }}</td>
                    <td>{{ $row->pbo_qtyorder }}</td>
                    <td>{{ $row->pbo_qtyrealisasi }}</td>
                    <td>{{ $row->prd_frac }}</td>
                    <td>{{ $row->prc_minorder }}</td>
                    <td>{{ $row->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
