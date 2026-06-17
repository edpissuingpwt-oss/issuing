<div class="card-body">
    <table id="example1" class="table table-bordered table-striped" style="width:100%">
        @if($flag == 'barcodekoli')
            <thead class="bg-gradient-info">
                <tr>
                    <th>No</th>
                    <th>TGL</th>
                    <th>KODE TOKO</th>
                    <th>NAMA TOKO</th>
                    <th>ZONA</th>
                    <th>BARCODE</th>
                    <th>RECID</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach($result as $row)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $row->tgl }}</td>
                        <td>{{ $row->kode_toko }}</td>
                        <td>{{ $row->nama_toko }}</td>
                        <td>{{ $row->zona }}</td>
                        <td>{{ $row->barcode }}</td>
                        <td>{{ $row->recid }}</td>
                    </tr>
                @endforeach
            </tbody>

        @elseif($flag == 'isikoli')
            <thead class="bg-gradient-info">
                <tr>
                    <th class="text-center align-middle">NO.</th>
                    <th class="text-center align-middle">PLU</th>
                    <th class="text-center align-middle">DESKRIPSI</th>
                    <th class="text-center align-middle">UNIT</th>
                    <th class="text-center align-middle">QTY</th>
                    <th class="text-center align-middle">RUPIAH</th>
                    <th class="text-center align-middle">PPN</th>
                    <th class="text-center align-middle">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach ($result as $row)
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td class="text-center">{{ $row->pbo_pluigr }}</td>
                        <td class="text-center">{{ $row->deskripsi }}</td>
                        <td class="text-center">{{ $row->unit }}</td>
                        <td class="text-right">{{ number_format($row->pbo_qtyrealisasi, 0) }}</td>
                        <td class="text-right">{{ number_format($row->nilaireal, 0) }}</td>
                        <td class="text-right">{{ number_format($row->ppnreal, 0) }}</td>
                        <td class="text-right">{{ number_format($row->total, 0) }}</td>
                    </tr>
                @endforeach
            </tbody>
        @endif
    </table>
</div>
