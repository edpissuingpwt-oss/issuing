
<div class="card-body">
    <table id="example1" class="table table-bordered table-striped" style="width:100%">
    @if($flag == 'belumabsen')
        <thead class="bg-gradient-info">
            <tr>
                <th>No</th>
                <th>P_ID</th>
                <th>RECID</th>
                <th>DOCNO</th>
                <th>SHOP</th>
                <th>ITEM</th>
                <th>TOTAL</th>
                <th>TGL_PEMBUATAN_FILE</th>
                <th>FILE_MASUK_IGR</th>
                <th>NM_WT</th>
                <th>KETERANGAN</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 0; @endphp
            @foreach($result as $row)
            <tr>
                <td>{{ ++$no }}</td>
                <td>{{ $row->p_id }}</td>
                <td>{{ $row->recid }}</td>
                <td>{{ $row->docno }}</td>
                <td>{{ $row->shop }}</td>
                <td>{{ $row->item }}</td>
                <td>{{ $row->total }}</td>
                <td>{{ $row->tgl_pembuatan_file }}</td>
                <td>{{ $row->file_masuk_igr }}</td>
                <td>{{ $row->nm_wt }}</td>
                <td>{{ $row->keterangan }}</td>
            </tr>
            @endforeach
        </tbody>
    @elseif($flag == 'selesaiabsen')
        <thead class="bg-gradient-info">
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">RECID</th>
                <th class="text-center">TIPE RETUR</th>
                <th class="text-center">TANGGAL</th>
                <th class="text-center">TANGGAL ABSEN</th>
                <th class="text-center">TOKO</th>
                <th class="text-center">NRB</th>
                <th class="text-center">JUMLAH ITEM</th>   
                <th class="text-center">GROSS</th>
                <th class="text-center">PPN</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 0; @endphp
            @foreach($result as $row)
            <tr>
                <td class="text-center">{{ ++$no }}</td>
                <td class="text-center">{{ $row->recid }}</td>
                <td class="text-center">{{ $row->retur }}</td>
                <td class="text-center">{{ $row->wt_create_dt }}</td>
                <td class="text-center">{{ $row->lar_create_dt }}</td>
                <td class="text-center">{{ $row->kodetoko }}</td>
                <td class="text-center">{{ $row->nonrb }}</td>
                <td class="text-center">{{ $row->jml_item }}</td>
                <td class="text-right">{{ number_format($row->rupiah) }}</td>
                <td class="text-right">{{ number_format($row->ppn) }}</td>
            </tr>
            @endforeach
        </tbody>
    @elseif($flag == 'selesaisortasi')
        <thead class="bg-gradient-info">
            <tr>
                    <th>No</th>
                    <th>TGL SORTASI</th>
                    <th>KODE TOKO</th>
                    <th>NRB</th>
                    <th>PLU</th>
                    <th>DESKRIPSI</th>
                    <th>QTY</th>
                    <th>FISIK</th>
                    <th>KURANG</th>
                    <th>BAIK</th>
                    <th>LAYAK</th>
                    <th>TIDAK LAYAK</th>
                    <th>STATUS</th>
                    <th>TAG IGR</th>
                    <th>TAG IDM</th>
                    <th>EXP DATE</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 0; @endphp
            @foreach($result as $row)
            <tr>
                <td>{{ ++$no }}</td>
                <td>{{ $row->tgl_sortasi }}</td>
                <td>{{ $row->kodetoko }}</td>
                <td>{{ $row->docno }}</td>
                <td>{{ $row->plu }}</td>
                <td>{{ $row->ket }}</td>
                <td>{{ $row->qty }}</td>
                <td>{{ $row->fisik }}</td>
                <td>{{ $row->kurang }}</td>
                <td>{{ $row->baik }}</td>
                <td>{{ $row->layak }}</td>
                <td>{{ $row->tidak_layak }}</td>
                <td>{{ $row->statuss }}</td>
                <td>{{ $row->tag_igr }}</td>
                <td>{{ $row->tag_idm }}</td>
                <td>{{ $row->exp_date }}</td>
            </tr>
            @endforeach
        </tbody>
    @elseif($flag == 'selesaiproses')
        <thead class="bg-gradient-info">
            <tr>
                    <tr>
                        <th rowspan="2" class="text-center align-middle">NO.</th>
                        <th colspan="2" class="text-center align-middle">TOKO</th>
                        <th colspan="2" class="text-center align-middle">BPBR</th>
                        <th colspan="2" class="text-center align-middle">NRB IDM</th>
                        <th colspan="4" class="text-center align-middle">PRODUK</th>
                        <th colspan="2" class="text-center align-middle">TAG</th>
                        <th rowspan="2" class="text-center align-middle">QTY</th>
                        <th rowspan="2" class="text-center align-middle">RUPIAH</th>
                        <th colspan="2" class="text-center align-middle">Supplier</th>
                        <th rowspan="2" class="text-center align-middle">Keterangan</th>
                    </tr>
                    <tr>
                        <th class="text-center">Kode</th>
                        <th class="text-center">Nama</th>

                        <th class="text-center">Tanggal</th>
                        <th class="text-center">Nomor</th>

                        <th class="text-center">Tanggal</th>
                        <th class="text-center">Nomor</th>

                        <th class="text-center">PLU</th>
                        <th class="text-center">Deskripsi</th>
                        <th class="text-center">Unit</th>
                        <th class="text-center">Frac</th>
                        
                        <th class="text-center">IGR</th>
                        <th class="text-center">IDM</th>

                        <th class="text-center">Kode</th>
                        <th class="text-center">Nama</th>
                    </tr>
            </tr>
        </thead>
        <tbody>
            @php $no = 0; @endphp
                @foreach ($result as $row)
            @php $no++; @endphp
            <tr>
                <td class="text-center">{{ $no }}</td>
                <td class="text-center">{{ $row->rom_kodetoko }}</td>
                <td class="text-center">{{ $row->rom_namatoko }}</td>
                <td class="text-center text-nowrap">{{ $row->rom_tgldokumen }}</td>
                <td class="text-center">{{ $row->rom_nodokumen }}</td>
                <td class="text-center text-nowrap">{{ $row->rom_tglreferensi }}</td>
                <td class="text-center">{{ $row->rom_noreferensi }}</td>
                <td class="text-center">{{ $row->rom_prdcd }}</td>
                <td class="text-left text-nowrap">{{ $row->rom_nama_barang }}</td>
                <td class="text-center">{{ $row->rom_unit }}</td>
                <td class="text-center">{{ $row->rom_frac }}</td>
                <td class="text-center">{{ $row->rom_kodetag }}</td>
                <td class="text-center">{{ $row->kodetag_idm }}</td>
                <td class="text-right">{{ number_format($row->rom_qty,0) }}</td>
                <td class="text-right">{{ number_format($row->rom_netto,0) }}</td>
                <td class="text-center">{{ $row->rom_kodesupplier }}</td>
                <td class="text-left text-nowrap">{{ $row->rom_namasupplier }}</td>
                <td class="text-right">&nbsp;</td>
            </tr>
            @endforeach
        </tbody>
    @endif
    </table>
</div>
