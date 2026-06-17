

<div class="card-body">
    <table id="example1" class="table table-bordered table-striped" style="width:100%">
        @if($flag == 'sales')
            <thead class="bg-gradient-info">
                <tr>
                        <th rowspan="2" class="text-center align-middle">NO.</th>
                        <th colspan="3" class="text-center align-middle">TOKO</th>
                        <th rowspan="2" class="text-center align-middle">TANGGAL STRUK</th>
                        <th rowspan="2" class="text-center align-middle">NO SPH</th>
                        <th rowspan="2" class="text-center align-middle">NO FAKTUR</th>
                        <th colspan="5" class="text-center align-middle">RUPIAH</th>
                    </tr>
                    <tr>
                        <th class="text-center">Member</th>
                        <th class="text-center">Kode</th>
                        <th class="text-center">Nama</th>

                        <th class="text-center">RPH STRUK</th>
                        <th class="text-center">PPN STRUK</th>
                        <th class="text-center">RPH DISTFEE</th>
                        <th class="text-center">PPN DISTFEE</th>
                        <th class="text-center">TOTAL</th>
                    </tr>
            </thead>
            <tbody>
                @php $no = 0; @endphp
                @foreach($result as $row)
                <tr>
                    <td class="text-center">{{ ++$no }}</td>
                    <td class="text-center">{{ $row->kodemember }}</td>
                    <td class="text-center">{{ $row->toko }}</td>
                    <td class="text-center">{{ $row->namatoko }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($row->tglstruk)->format('d-m-Y') }}</td>
                    <td class="text-center">{{ $row->nosph }}</td>
                    <td class="text-center">{{ $row->nofaktur }}</td>
                    <td style="text-align: right">{{ number_format(round($row->rpstruk), 0, ".", ",") }}</td>
                    <td style="text-align: right">{{ number_format(round($row->ppnstruk), 0, ".", ",") }}</td>
                    <td style="text-align: right">{{ number_format(round($row->distfee), 0, ".", ",") }}</td>
                    <td style="text-align: right">{{ number_format(round($row->ppndistfee), 0, ".", ",") }}</td>
                    <td style="text-align: right">{{ number_format(round($row->total), 0, ".", ",") }}</td>
                </tr>
                @endforeach
            </tbody>
        @elseif($flag == 'bkl')
            <thead class="bg-gradient-info">
                <tr>
                    <th rowspan="2" class="text-center align-middle">NO.</th>
                    <th colspan="3" class="text-center align-middle">TOKO</th>
                    <th rowspan="2" class="text-center align-middle">NO BUKTI</th>
                    <th rowspan="2" class="text-center align-middle">KODE SUPPLIER</th>
                    <th rowspan="2" class="text-center align-middle">NO DOC</th>
                    <th rowspan="2" class="text-center align-middle">TANGGAL STRUK</th>
                    <th rowspan="2" class="text-center align-middle">NO STRUK</th>
                    <th colspan="2" class="text-center align-middle">FAKTUR</th>
                    <th rowspan="2" class="text-center align-middle">TOTAL</th>
                </tr>
                <tr>
                        <th class="text-center">Member</th>
                        <th class="text-center">Kode</th>
                        <th class="text-center">Nama</th>

                        <th class="text-center">No Faktur</th>
                        <th class="text-center">No Seri</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 0; @endphp
                @foreach($result as $row)
                <tr>
                    <td class="text-center">{{ ++$no }}</td>
                    <td class="text-center">{{ $row->kode_member }}</td>
                    <td class="text-center">{{ $row->kodeomi }}</td>
                    <td class="text-center">{{ $row->namatoko }}</td>
                    <td class="text-center">{{ $row->nobukti }}</td>
                    <td class="text-center">{{ $row->kodesupp }}</td>
                    <td class="text-center">{{ $row->nodoc }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($row->tglstruk)->format('d-m-Y') }}</td>
                    <td class="text-center">{{ $row->nostruk }}</td>
                    <td class="text-center">{{ $row->nofaktur }}</td>
                    <td class="text-center">{{ $row->noseri_faktur }}</td>
                    <td style="text-align: right">{{ number_format(round($row->total), 0, ".", ",") }}</td>
                </tr>
                @endforeach
            </tbody>
        @endif
    </table>
</div>
