@push('styles')
@endpush

<div class="card modern-card shadow-lg border-0 mt-4">
    <div class="card-body modern-body">
            <div id="exportButtons"></div>
        <div class="table-responsive">
            <table id="example1" class="table table-bordered table-striped" style="width:100%">
                <thead class="bg-gradient-info">
                    <tr>
                        <th>No</th>
                        <th>IS_TYPE</th>
                        <th>TANGGAL</th>
                        <th>TGL WT</th>
                        <th>TGL PROSES</th>
                        <th>NO BPBR</th>
                        <th>KODE TOKO</th>
                        <th>NAMA TOKO</th>
                        <th>NO NRB</th>
                        <th>PLU IDM</th>
                        <th>PLU IGR</th>
                        <th>DESKRIPSI</th>
                        <th>QTY</th>
                        <th>PRICE</th>
                        <th>GROSS</th>
                        <th>PPN</th>
                        <th>NETTO</th>
                        <th>LOKASI</th>
                        <th>BEBAN</th>
                        <th>NILAI BEBAN</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $index => $row)
                        <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $row->istype }}</td>
                        <td>{{ $row->tanggal }}</td>
                        <td>{{ $row->tgl_wt }}</td>
                        <td>{{ $row->tgl_proses }}</td>
                        <td>{{ $row->no_bpbr }}</td>
                        <td>{{ $row->kode_toko }}</td>
                        <td>{{ $row->nama_toko }}</td>
                        <td>{{ $row->docno }}</td>
                        <td>{{ $row->pluidm }}</td>
                        <td>{{ $row->pluigr }}</td>
                        <td>{{ $row->deskripsi }}</td>
                        <td>{{ $row->qty }}</td>
                        <td style="text-align: right">{{ number_format(round($row->price), 0, ".", ",") }}</td>
                        <td style="text-align: right">{{ number_format(round($row->gross), 0, ".", ",") }}</td>
                        <td style="text-align: right">{{ number_format(round($row->ppn), 0, ".", ",") }}</td>
                        <td style="text-align: right">{{ number_format(round($row->netto), 0, ".", ",") }}</td>
                        <td>{{ $row->lokasi }}</td>
                        <td>{{ $row->beban }}</td>
                        <td style="text-align: right">{{ number_format(round($row->nilai_beban), 0, ".", ",") }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@stack('styles')
