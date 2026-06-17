<div class="card-body">
    <table id="example1" class="table table-bordered table-striped">
        <thead class="bg-gradient-info">
            <tr>
                <th>No</th>
                <th>RECID</th>
                <th>TKO_KODEOMI</th>
                <th>TKO_KODECUSTOMER</th>
                <th>DOCNO</th>
                <th>ITEM</th>
                <th style="text-align: center">TRPT_SALESVALUE</th>
                <th style="text-align: center">TRPT_NETSALES</th>
                <th>TGL_INVOICE</th>
                <th>TGL_PROSES</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 0; 
            @endphp
            @foreach($result as $row)
            <tr>
                <td>{{ ++$no }}</td>
                <td>{{ $row->recid }}</td>
                <td>{{ $row->tko_kodeomi }}</td>
                <td>{{ $row->tko_kodecustomer }}</td>
                <td>{{ $row->docno }}</td>
                <td>{{ $row->item }}</td>
                <td style="text-align: right">{{ number_format(round($row->trpt_salesvalue), 0, ".", ",") }}</td>
                <td style="text-align: right">{{ number_format(round($row->trpt_netsales), 0, ".", ",") }}</td>
                <td>{{ $row->tgl_invoice }}</td>
                <td>{{ $row->tgl_proses }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>