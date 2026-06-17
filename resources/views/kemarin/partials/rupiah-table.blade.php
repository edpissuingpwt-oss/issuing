<div class="d-flex justify-content-between align-items-center mb-3">
    <button type="button" class="btn btn-sm btn-primary copy-rupiah-btn" onclick="copyRupiahTable('{{ $type ?? 'all' }}')">
        <i class="fas fa-copy"></i> Copy
    </button>
</div>

<div class="table-responsive">
    <table id="rupiah-table-{{ $type ?? 'all' }}" class="table table-bordered table-striped" style="width:100%">
        @php
            $theadClass = match(strtoupper($type ?? 'ALL')) {
                'ALL' => 'bg-gradient-danger text-white',
                'IDM' => 'bg-gradient-primary text-white',
                'OMI' => 'bg-gradient-success text-white',
                default => 'bg-gradient-secondary text-white'
            };
        @endphp
        <thead class="{{ $theadClass }}">
            <tr>
                <th class="text-center">ZONA</th>
                <th class="text-center">JALUR</th>
                <th class="text-center">QTY ORDER</th>
                <th class="text-center">QTY REAL</th>
                <th class="text-center">SL QTY</th>
                <th class="text-center">RPH ORDER</th>
                <th class="text-center">RPH REAL</th>
                <th class="text-center">SL NILAI</th>
            </tr>
        </thead>
        <tbody>
            @php
                // Inisialisasi variabel untuk total
                $totalQtyOrder = 0;
                $totalQtyReal = 0;
                $totalRphOrder = 0;
                $totalRphReal = 0;
            @endphp
            
            @forelse($data as $row)
            @php
                // Akumulasi total
                $totalQtyOrder += $row->qtyorder ?? 0;
                $totalQtyReal += $row->qtyrealisasi ?? 0;
                $totalRphOrder += $row->nilaiorder ?? 0;
                $totalRphReal += $row->ttlnilai ?? 0;
            @endphp
            <tr class="data-row">
                <td class="text-center">{{ $row->zona ?? '-' }}</td>
                <td class="text-center">{{ $row->jalur ?? '-' }}</td>
                <td class="text-right">{{ number_format($row->qtyorder ?? 0, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($row->qtyrealisasi ?? 0, 0, ',', '.') }}</td>
                <td class="text-right">
                    @php
                        // Ambil nilai SL QTY dan bersihkan dari karakter % dan koma
                        $slQtyRaw = $row->slqty ?? '0';
                        // Hapus tanda % dan konversi koma ke titik jika perlu
                        $slQtyClean = str_replace(['%', ','], '', $slQtyRaw);
                        $slQtyClean = floatval($slQtyClean);
                        
                        // Tentukan warna berdasarkan nilai
                        if ($slQtyClean >= 90) {
                            $badgeClass = 'success'; // Hijau
                        } elseif ($slQtyClean >= 60) {
                            $badgeClass = 'warning'; // Kuning
                        } else {
                            $badgeClass = 'danger'; // Merah
                        }
                    @endphp
                    <span class="badge badge-{{ $badgeClass }} badge-pill px-3 py-2">
                        {{ $row->slqty ?? '0' }}
                    </span>
                </td>
                <td class="text-right">{{ number_format($row->nilaiorder ?? 0, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($row->ttlnilai ?? 0, 0, ',', '.') }}</td>
                <td class="text-right">
                    @php
                        // Ambil nilai SL NILAI dan bersihkan dari karakter % dan koma
                        $slNilaiRaw = $row->slnilai ?? '0';
                        // Hapus tanda % dan konversi koma ke titik jika perlu
                        $slNilaiClean = str_replace(['%', ','], '', $slNilaiRaw);
                        $slNilaiClean = floatval($slNilaiClean);
                        
                        // Tentukan warna berdasarkan nilai
                        if ($slNilaiClean >= 90) {
                            $badgeClassNilai = 'success'; // Hijau
                        } elseif ($slNilaiClean >= 60) {
                            $badgeClassNilai = 'warning'; // Kuning
                        } else {
                            $badgeClassNilai = 'danger'; // Merah
                        }
                    @endphp
                    <span class="badge badge-{{ $badgeClassNilai }} badge-pill px-3 py-2">
                        {{ $row->slnilai ?? '0' }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
        @if(count($data) > 0)
        <tfoot class="table-active font-weight-bold">
            <tr>
                <th colspan="2" class="text-center">TOTAL</th>
                <th class="text-right">{{ number_format($totalQtyOrder, 0, ',', '.') }}</th>
                <th class="text-right">{{ number_format($totalQtyReal, 0, ',', '.') }}</th>
                <th class="text-right">
                    @php
                        // Hitung persentase TOTAL QTY dengan 2 desimal
                        $totalSlQty = $totalQtyOrder > 0 ? ($totalQtyReal / $totalQtyOrder) * 100 : 0;
                        $totalSlQtyDisplay = round($totalSlQty, 2);
                        
                        // Tentukan warna berdasarkan nilai
                        if ($totalSlQtyDisplay >= 90) {
                            $totalBadgeClass = 'success';
                        } elseif ($totalSlQtyDisplay >= 60) {
                            $totalBadgeClass = 'warning';
                        } else {
                            $totalBadgeClass = 'danger';
                        }
                    @endphp
                    <span class="badge badge-{{ $totalBadgeClass }} badge-pill px-3 py-2">
                        {{ number_format($totalSlQtyDisplay, 2) }}%
                    </span>
                </th>
                <th class="text-right">{{ number_format($totalRphOrder, 0, ',', '.') }}</th>
                <th class="text-right">{{ number_format($totalRphReal, 0, ',', '.') }}</th>
                <th class="text-right">
                    @php
                        // Hitung persentase TOTAL RPH dengan 2 desimal
                        $totalSlNilai = $totalRphOrder > 0 ? ($totalRphReal / $totalRphOrder) * 100 : 0;
                        $totalSlNilaiDisplay = round($totalSlNilai, 2);
                        
                        // Tentukan warna berdasarkan nilai
                        if ($totalSlNilaiDisplay >= 90) {
                            $totalBadgeClassNilai = 'success';
                        } elseif ($totalSlNilaiDisplay >= 60) {
                            $totalBadgeClassNilai = 'warning';
                        } else {
                            $totalBadgeClassNilai = 'danger';
                        }
                    @endphp
                    <span class="badge badge-{{ $totalBadgeClassNilai }} badge-pill px-3 py-2">
                        {{ number_format($totalSlNilaiDisplay, 2) }}%
                    </span>
                </th>
            </tr>
        </tfoot>
        @endif
    </table>
</div>