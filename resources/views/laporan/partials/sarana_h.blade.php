@push('styles')
@endpush

<div class="card modern-card shadow-lg border-0 mt-4">
    <div class="card-body modern-body">
            <div id="exportButtons"></div>
        <div class="table-responsive">
            <table id="example1" class="table table-bordered table-striped" style="width:100%">
                <thead class="bg-gradient-info">
                    <tr>
                        <th class="text-center">TANGGAL</th>
                        <th class="text-center">JUMLAH TOKO</th>
                        <th class="text-center">JUMLAH CONTAINER</th>
                        <th class="text-center">JUMLAH KARDUS</th>
                        <th class="text-center">JUMLAH BRONJONG</th>
                        <th class="text-center">TOTAL DSPB</th>
                        <th class="text-center">AVG DSPB PERTOKO PERHARI</th>
                        <th class="text-center">AVG CONTAINER PERTOKO PERHARI</th>
                        <th class="text-center">AVG KARDUS PERTOKO PERHARI</th>
                        <th class="text-center">AVG BRONJONG PERTOKO PERHARI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $row)
                        <tr>
                            <td class="text-center">{{ \Carbon\Carbon::parse($row->tanggal)->format('d-M-Y') }}</td>
                            <td class="text-right">{{ $row->jml_toko }}</td>
                            <td class="text-right">{{ $row->jumlah_container }}</td>
                            <td class="text-right">{{ $row->jumlah_kardus }}</td>
                            <td class="text-right">{{ $row->jumlah_bronjong }}</td>
                            <td class="text-right">{{ number_format($row->total_dspb) }}</td>
                            <td class="text-right">{{ number_format($row->avg_dspb_perhari) }}</td>
                            <td class="text-right">{{ number_format($row->avg_container_pertoko_perhari, 2, ',', '.') }}</td>
                            <td class="text-right">{{ number_format($row->avg_kardus_pertoko_perhari, 2, ',', '.') }}</td>
                            <td class="text-right">{{ number_format($row->avg_bronjong_pertoko_perhari, 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                        <tr>
                            <th colspan="2" style="font-weight: bold;">TOTAL</th>
                            <th class="text-right">{{ number_format($totals['jumlah_container']) }}</th>
                            <th class="text-right">{{ number_format($totals['jumlah_kardus']) }}</th>
                            <th class="text-right">{{ number_format($totals['jumlah_bronjong']) }}</th>
                            <th class="text-right">{{ number_format($totals['total_dspb']) }}</th>
                            <th colspan="4"></th>
                        </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@stack('styles')
