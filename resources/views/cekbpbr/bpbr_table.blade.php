<div class="card-body">
    <table id="example1" class="table table-bordered table-striped">
        <thead class="bg-gradient-info">
            <tr>
                <th>No</th>
                <th>TANGGAL</th>
                <th>NO BPBR</th>
                <th>TIPE</th>
                <th>KODE TOKO</th>
                <th>NO NRB</th>
                <th>PLU</th>
                <th>DESKRIPSI</th>
                <th>BAIK</th>
                <th>TERIMA</th>
                <th>KURANG</th>
                <th>TOLAK</th>
                <th>STATUS</th>
                <th>TAG IDM</th>
                <th>TAG OMI</th>
                <th>SUPPLIER</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 0; @endphp
            @foreach($result as $row)
            <tr>
                <td>{{ ++$no }}</td>
                <td>{{ $row->tanggal }}</td>
                <td>{{ $row->nobpbr }}</td>
                <td>{{ $row->tipe }}</td>
                <td>{{ $row->kodetoko }}</td>
                <td>{{ $row->nonrb }}</td>
                <td>{{ $row->plu }}</td>
                <td>{{ $row->deskripsi }}</td>
                <td>{{ $row->baik }}</td>
                <td>{{ $row->terima }}</td>
                <td>{{ $row->kurang }}</td>
                <td>{{ $row->tolak }}</td>
                <td>{{ $row->sb }}</td>
                <td>{{ $row->tagidm }}</td>
                <td>{{ $row->tagomi }}</td>
                <td>{{ $row->supplier }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>