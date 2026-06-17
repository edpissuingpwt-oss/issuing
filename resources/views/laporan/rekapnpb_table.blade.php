<div class="card-body">
    <table id="example1" class="table table-bordered table-striped">
        <thead class="bg-gradient-info">
            <tr>
                <th>tgl_dspb</th>
                <th>tgl_pb</th>
                <th>toko</th>
                <th>nama_toko</th>
                <th>no_pb</th>
                <th>no_dspb</th>
                <th>qty_koli</th>
                <th>rp_nilai</th>
                <th>rp_ppn</th>
                <th>total</th>
                <th>tipedspb</th>
            </tr>
        </thead>
        <tbody>
            @foreach($result as $row)
            <tr>
                <td>{{ $row->tgl_dspb }}</td>
                <td>{{ $row->tgl_pb }}</td>
                <td>{{ $row->toko }}</td>
                <td>{{ $row->nama_toko }}</td>
                <td>{{ $row->no_pb }}</td>
                <td>{{ $row->no_dspb }}</td>
                <td>{{ $row->qty_koli }}</td>
                <td>{{ $row->rp_nilai }}</td>
                <td>{{ $row->rp_ppn }}</td>
                <td>{{ $row->total }}</td>
                <td>{{ $row->tipedspb }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>