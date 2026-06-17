<div class="card-body">
    <table id="example1" class="table table-bordered table-striped">
        <thead class="bg-gradient-info">
                    <tr>
                        <th rowspan="2" class="text-center align-middle">No</th>
                        <th rowspan="2" class="text-center align-middle">KODE TOKO</th>
                        <th rowspan="2" class="text-center align-middle">NAMA TOKO</th>
                        <th rowspan="2" class="text-center align-middle">JADWAL</th>
                        <th rowspan="2" class="text-center align-middle">RIT</th>
                        <th rowspan="2" class="text-center align-middle">NO PB</th>
                        <th rowspan="2" class="text-center align-middle">NO PICKING</th>
                        <th rowspan="2" class="text-center align-middle">NO SJ</th>
                        <th rowspan="2" class="text-center align-middle">RPH</th>
                        <th colspan="2" class="text-center align-middle">SEND JALUR</th>
                        <th colspan="3" class="text-center align-middle">DSPB</th>
                    </tr>
                    <tr>
                        <th class="text-center">Bronjong</th>
                        <th class="text-center">Container</th>
                        <th class="text-center">Bronjong</th>
                        <th class="text-center">Container</th>
                        <th class="text-center">Kardus</th>
                    </tr>
        </thead>
        <tbody>
                    @php $no = 0; @endphp
                    @foreach ($results as $row)
                        @php $no++; @endphp
                        <tr>
                            <td class="text-center">{{ $no }}</td>
                            <td class="text-center">{{ $row->hpbi_kodetoko }}</td>
                            <td class="text-center">{{ $row->tko_namaomi }}</td>
                            <td class="text-center">{{ $row->hari_kirim }}</td>
                            <td class="text-center">{{ $row->ritase }}</td>
                            <td class="text-right">{{ $row->hpbi_nopb }}</td>
                            <td class="text-right">{{ $row->hpbi_nopicking }}</td>
                            <td class="text-right">{{ $row->hpbi_nosj }}</td>
                            <td class="text-right">{{ number_format($row->hpbi_rphvalid) }}</td>
                            <td class="text-right">{{ $row->brj_send }}</td>
                            <td class="text-right">{{ $row->cont_send }}</td>
                            <td class="text-right">{{ $row->brj_dspb }}</td>
                            <td class="text-right">{{ $row->cont_dspb }}</td>
                            <td class="text-right">{{ $row->kardus_dspb }}</td>
                        </tr>
                    @endforeach
        </tbody>
        <tfoot>
                        <tr>
                            <th colspan="8" style="font-weight: bold;" class="text-center">TOTAL</th>
                            <th class="text-right">{{ number_format($totals['hpbi_rphvalid']) }}</th>
                            <th class="text-right">{{ number_format($totals['brj_send']) }}</th>
                            <th class="text-right">{{ number_format($totals['cont_send']) }}</th>
                            <th class="text-right">{{ number_format($totals['brj_dspb']) }}</th>
                            <th class="text-right">{{ number_format($totals['cont_dspb']) }}</th>
                            <th class="text-right">{{ number_format($totals['kardus_dspb']) }}</th>
                        </tr>
        </tfoot>
    </table>
</div>