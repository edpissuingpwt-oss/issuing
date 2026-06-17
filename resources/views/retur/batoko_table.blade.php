

<div class="card-body">
    <table id="example1" class="table table-bordered table-striped" style="width:100%">
        @if($flag == 'detail')
            <thead class="bg-gradient-info">
                <tr>
                        <th rowspan="2" class="text-center align-middle">NO.</th>
                        <th colspan="2" class="text-center align-middle">TOKO</th>
                        <th colspan="3" class="text-center align-middle">NOMOR</th>
                        <th colspan="5" class="text-center align-middle">PRODUK</th>
                        <th rowspan="2" class="text-center align-middle">TANGGAL</th>
                        <th colspan="4" class="text-center align-middle">QTY</th>
                        <th rowspan="2" class="text-center align-middle">RUPIAH</th>
                    </tr>
                    <tr>
                        <th class="text-center">Kode</th>
                        <th class="text-center">Nama</th>

                        <th class="text-center">NRB</th>
                        <th class="text-center">BA</th>
                        <th class="text-center">BPBR</th>

                        <th class="text-center">PLUIDM</th>
                        <th class="text-center">PLUIGR</th>
                        <th class="text-center">Deskripsi</th>
                        <th class="text-center">Frac</th>
                        <th class="text-center">Status</th>

                        <th class="text-center">QTY NRB</th>
                        <th class="text-center">QTY FISIK</th>
                        <th class="text-center">SELISIH</th>
                        <th class="text-center">QTY TOLAK</th>
                    </tr>
            </thead>
            <tbody>
                @php $no = 0; @endphp
                @foreach($result as $row)
                <tr>
                    <td>{{ ++$no }}</td>
                    <td>{{ $row->kode_toko }}</td>
                    <td>{{ $row->nama_toko }}</td>
                    <td>{{ $row->no_nrb }}</td>
                    <td>{{ $row->bth_nodoc }}</td>
                    <td>{{ $row->no_bpbr }}</td>
                    <td>{{ $row->plu_idm }}</td>
                    <td>{{ $row->prd_prdcd }}</td>
                    <td class="text-left text-nowrap">{{ $row->deskripsi }}</td>
                    <td>{{ $row->frac }}</td>
                    <td>{{ $row->status }}</td>
                    <td class="text-center text-nowrap">{{ $row->tgl_proses }}</td>
                    <td>{{ $row->data_nrb }}</td>
                    <td>{{ $row->fisik }}</td>
                    <td>{{ $row->fisik_kurang }}</td>
                    <td>{{ $row->tolak }}</td>
                    <td style="text-align: right">{{ number_format(round($row->nominal), 0, ".", ",") }}</td>
                </tr>
                @endforeach
            </tbody>
        @elseif($flag == 'rekap')
            <thead class="bg-gradient-info">
                <tr>
                    <th>No</th>
                    <th>TGL DOC</th>
                    <th>NO DOC</th>
                    <th>TIPE</th>
                    <th>KODE TOKO</th>
                    <th>NAMA TOKO</th>
                    <th>NO NRB</th>
                    <th>TGL NRB</th>
                    <th>NO BPBR</th>
                    <th>TGL BPBR</th>
                    <th>ITEM</th>
                    <th>RUPIAH</th>
                    <th>PPN</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 0; @endphp
                @foreach($result as $row)
                <tr>
                    <td>{{ ++$no }}</td>
                    <td>{{ $row->bth_tgldoc }}</td>
                    <td>{{ $row->bth_nodoc }}</td>
                    <td>{{ $row->bth_type }}</td>
                    <td>{{ $row->bth_toko }}</td>
                    <td>{{ $row->tko_namaomi }}</td>
                    <td>{{ $row->bth_nonrb }}</td>
                    <td>{{ $row->bth_tglnrb }}</td>
                    <td>{{ $row->bth_pbr }}</td>
                    <td>{{ $row->bth_tgpbr }}</td>
                    <td>{{ $row->jumlahplu }}</td>
                    <td style="text-align: right">{{ number_format(round($row->bth_dpp), 0, ".", ",") }}</td>
                    <td style="text-align: right">{{ number_format(round($row->bth_ppn), 0, ".", ",") }}</td>
                </tr>
                @endforeach
            </tbody>
        @endif
    </table>
</div>
