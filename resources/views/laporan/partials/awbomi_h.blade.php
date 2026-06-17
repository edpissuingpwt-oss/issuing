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
                        <th class="text-center">TANGGAL</th>
                        <th class="text-center">KODE TOKO</th>
                        <th class="text-center">NO PB</th>
                        <th class="text-center">NO DSPB</th>
                        <th style="text-align: center">ONGKIR</th>
                        <th class="text-center">AWB</th>
                        <th class="text-center">KETERANGAN</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $index => $row)
                        <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($row->tglpb)->format('d-M-Y') }}</td>
                        <td class="text-center">{{ $row->kodetoko }}</td>
                        <td class="text-center">{{ $row->nopb }}</td>
                        <td class="text-center">{{ $row->nodspb }}</td>
                        <td style="text-align: right">{{ number_format(round($row->ongkir), 0, ".", ",") }}</td>
                        <td class="text-center">{{ $row->noawb }}</td>
                        <td class="text-center">{{ $row->status }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@stack('styles')
