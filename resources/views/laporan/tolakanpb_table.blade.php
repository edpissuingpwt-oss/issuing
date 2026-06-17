<div class="card-body">
    <div class="table-responsive">
        <table id="example1" class="table table-striped table-bordered table-hover align-middle w-100">
            <thead class="bg-gradient-info text-uppercase text-center">
                @if ($jenisLaporan == 1)
                    <tr class="bg-gradient-info">
                        <th rowspan="2" class="text-center">#</th>
                        <th colspan="11" class="text-center">Produk</th>
                        <th rowspan="2" class="text-center">Keterangan Tolakan</th>
                        <th rowspan="2" class="text-center">Acost Pcs</th>
                        <th rowspan="2" class="text-center">LPP</th>
                        <th rowspan="2" class="text-center">PO OUT</th>
                        <th colspan="6" class="text-center">Summary</th>
                        <th rowspan="2" class="text-center">BTB TERAKHIR</th>
                        <th rowspan="2" class="text-center">Keterangan</th>
                    </tr>
                    <tr class="bg-gradient-info">
                        <th class="text-center text-nowrap">DIV</th>
                        <th class="text-center text-nowrap">DEPT</th>
                        <th class="text-center text-nowrap">KAT</th>
                        <th class="text-center text-nowrap">PLU OMI</th>
                        <th class="text-center text-nowrap">PLU IGR</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center text-nowrap">Frac</th>
                        <th class="text-center">Tag IGR</th>
                        <th class="text-center">Tag IDM</th>
                        <th class="text-center">Tag OMI</th>
                        <th class="text-center">Flag</th>

                        <th class="text-center text-nowrap">Qty M+i</th>
                        <th class="text-center text-nowrap">Qty Order</th>
                        <th class="text-center text-nowrap">Rph Order</th>
                        <th class="text-center">Toko</th>
                        <th class="text-center text-nowrap">No. PB</th>
                        <th class="text-center text-nowrap">Hari / Tanggal</th>
                    </tr>
                @elseif ($jenisLaporan == 2)
                    <tr class="bg-gradient-info">
                        <th rowspan="2" class="text-center">#</th>
                        <th colspan="3" class="text-center">Toko</th>
                        <th colspan="11" class="text-center">Produk</th>
                        <th rowspan="2" class="text-center">Keterangan Tolakan</th>
                        <th rowspan="2" class="text-center">Acost Pcs</th>
                        <th rowspan="2" class="text-center">LPP</th>
                        <th rowspan="2" class="text-center">QTY ORDER</th>
                        <th rowspan="2" class="text-center">RPH ORDER</th>
                        <th rowspan="2" class="text-center">BTB TERAKHIR</th>
                        <th rowspan="2" class="text-center">Keterangan</th>
                    </tr>
                    <tr class="bg-gradient-info">
                        <th class="text-center text-nowrap">Kode</th>
                        <th class="text-center text-nowrap">Nama</th>
                        <th class="text-center text-nowrap">No. PB</th>

                        <th class="text-center text-nowrap">DIV</th>
                        <th class="text-center text-nowrap">DEPT</th>
                        <th class="text-center text-nowrap">KAT</th>
                        <th class="text-center text-nowrap">PLU OMI</th>
                        <th class="text-center text-nowrap">PLU IGR</th>
                        <th class="text-center text-nowrap">Nama</th>
                        <th class="text-center text-nowrap">Frac</th>
                        <th class="text-center text-nowrap">Tag IGR</th>
                        <th class="text-center text-nowrap">Tag IDM</th>
                        <th class="text-center text-nowrap">Tag OMI</th>
                        <th class="text-center text-nowrap">Flag</th>
                    </tr>
                @endif
            </thead>

            <tbody>
                @php
                    $totalQtyOrder = 0;
                    $totalRphOrder = 0;
                @endphp

                @forelse ($data as $row)
                    @php
                        $nilai = $row->tlko_nilai ?? 0;
                        $qty = $row->tlko_qtyorder ?? 0;
                        $totalRphOrder += $nilai;
                        $totalQtyOrder += $qty;
                    @endphp

                    @if ($jenisLaporan == 1)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $row->div }}</td>
                            <td class="text-center">{{ $row->dept }}</td>
                            <td class="text-center">{{ $row->katb }}</td>
                            <td class="text-center">{{ $row->pluomi }}</td>
                            <td class="text-center">{{ $row->pluigr }}</td>
                            <td class="text-left text-nowrap">{{ $row->desk }}</td>
                            <td class="text-center">{{ $row->frac }}</td>
                            <td class="text-center">{{ $row->tagigr }}</td>
                            <td class="text-center">{{ $row->tagidm }}</td>
                            <td class="text-center">{{ $row->tagomi }}</td>
                            <td class="text-left">{{ $row->flag }}</td>
                            <td class="text-left text-nowrap">{{ $row->tlko_kettolakan }}</td>
                            <td class="text-right">{{ number_format($row->acostpcs, 0) }}</td>
                            <td class="text-right">{{ number_format($row->tlko_lpp, 0) }}</td>
                            <td class="text-right">{{ number_format($row->out_qty, 0) }}</td>
                            <td class="text-right">{{ number_format($row->pkmp_mplusi, 0) }}</td>
                            <td class="text-right">{{ number_format($row->tlko_qtyorder, 0) }}</td>
                            <td class="text-right">{{ number_format($row->tlko_nilai, 0) }}</td>
                            <td class="text-center">{{ $row->tlko_kode_omi }}</td>
                            <td class="text-center">{{ $row->tlko_nopb }}</td>
                            <td class="text-center">{{ $row->tlko_tanggal }}</td>
                            <td class="text-center">{{ $row->lastbpb }}</td>
                            <td class="text-center">&nbsp;</td>
                        </tr>
                    @elseif ($jenisLaporan == 2)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $row->kodetoko }}</td>
                            <td>{{ $row->namatoko }}</td>
                            <td>{{ $row->nopb }}</td>
                            <td class="text-center">{{ $row->div }}</td>
                            <td class="text-center">{{ $row->dept }}</td>
                            <td class="text-center">{{ $row->katb }}</td>
                            <td class="text-center">{{ $row->pluomi }}</td>
                            <td class="text-center">{{ $row->pluigr }}</td>
                            <td class="text-left text-nowrap">{{ $row->desk }}</td>
                            <td class="text-center">{{ $row->frac }}</td>
                            <td>{{ $row->tagigr }}</td>
                            <td>{{ $row->tagidm }}</td>
                            <td>{{ $row->tagomi }}</td>
                            <td>{{ $row->flag }}</td>
                            <td>{{ $row->tlko_kettolakan }}</td>
                            <td class="text-right">{{ number_format($row->acostpcs, 0) }}</td>
                            <td class="text-end">{{ number_format($row->tlko_lpp, 0) }}</td>
                            <td class="text-right">{{ number_format($row->tlko_qtyorder, 0) }}</td>
                            <td class="text-right">{{ number_format($row->tlko_nilai, 0) }}</td>
                            <td class="text-center">{{ $row->lastbpb }}</td>
                            <td>&nbsp;</td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="15" class="text-center text-muted">
                            <i class="fas fa-exclamation-circle me-1"></i>
                            Tidak ada data untuk periode <b>{{ $tanggalMulai }}</b> s.d. <b>{{ $tanggalSelesai }}</b>
                        </td>
                    </tr>
                @endforelse
            </tbody>

            <tfoot class="bg-gradient-info">
                @if ($jenisLaporan == 1)
                    <tr>
                        <td colspan="18" class="text-center text-bold">TOTAL</td>
                        <td class="text-right text-bold">{{ number_format($totalRphOrder, 0) }}</td>
                        <td colspan="5"></td>
                    </tr>
                @elseif ($jenisLaporan == 2)
                    <tr>
                        <td colspan="18" class="text-center text-bold">TOTAL</td>
                        <td class="text-right text-bold">{{ number_format($totalQtyOrder, 0) }}</td>
                        <td class="text-right text-bold">{{ number_format($totalRphOrder, 0) }}</td>
                        <td colspan="2"></td>
                    </tr>
                @endif
            </tfoot>
        </table>
    </div>
</div>
