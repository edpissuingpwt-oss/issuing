@push('styles')
@endpush

<div class="card modern-card shadow-lg border-0 mt-4">
    <div class="card-body modern-body">
            <div id="exportButtons"></div>
        <div class="table-responsive">
        @php
            $totalItemOrder = function($row) {
                return ($row->itemorder ?? 0) + ($row->itemtolakan ?? 0);
            };
            $totalQtyOrder = function($row) {
                return ($row->qtyorder ?? 0) + ($row->qtytolakan ?? 0);
            };
            $totalRphOrder = function($row) {
                return ($row->rphorder ?? 0) + ($row->totaltolakan ?? 0);
            };
        @endphp
            <table id="example1" class="table table-bordered table-striped" style="width:100%">
                <thead class="bg-gradient-info">
                    <tr>
                        <th rowspan="2"style="text-align: center;">No</th>
                        <th rowspan="2"style="text-align: center;">Tanggal</th>
                        <th colspan="3"style="text-align: center;">OMI</th>
                        <th colspan="3"style="text-align: center;">PB</th>
                        <th colspan="3"style="text-align: center;">Upload</th>
                        <th colspan="3"style="text-align: center;">Picking</th>
                        <th colspan="3"style="text-align: center;">DSP</th>
                        <th colspan="4"style="text-align: center;">%SL</th>
                    </tr>
                    <tr>
                        <th class="text-center">Kode</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center">No PB</th>

                        <th class="text-center">Item</th>
                        <th class="text-center">Qty</th>
                        <th class="text-center">Rupiah</th>

                        <th class="text-center">Item</th>
                        <th class="text-center">Qty</th>
                        <th class="text-center">Rupiah</th>

                        <th class="text-center">Item</th>
                        <th class="text-center">Qty</th>
                        <th class="text-center">Rupiah</th>

                        <th class="text-center">Item</th>
                        <th class="text-center">Qty</th>
                        <th class="text-center">Rupiah</th>

                        <th class="text-center">%Qty Pick</th>
                        <th class="text-center">%Rph Pick</th>
                        <th class="text-center">%Qty DSP</th>
                        <th class="text-center">%Rph DSP</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $index => $row)
                        <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center">{{ $row->tglpb }}</td>

                        <td class="text-center">{{ $row->kodeomi }}</td>
                        <td class="text-center">{{ $row->namaomi }}</td>
                        <td class="text-center">{{ $row->nomorpb }}</td>

                        <td class="text-right">{{ number_format($totalItemOrder($row), 0) }}</td>
                        <td class="text-right">{{ number_format($totalQtyOrder($row), 0) }}</td>
                        <td class="text-right">{{ number_format($totalRphOrder($row), 0) }}</td>
                        
                        <td class="text-right">{{ number_format($row->itemorder, 0) }}</td>
                        <td class="text-right">{{ number_format($row->qtyorder, 0) }}</td>
                        <td class="text-right">{{ number_format($row->rphorder, 0) }}</td>

                        <td class="text-right">{{ number_format($row->itempick, 0) }}</td>
                        <td class="text-right">{{ number_format($row->qtypick, 0) }}</td>
                        <td class="text-right">{{ number_format($row->rphpick, 0) }}</td>

                        <td class="text-right">{{ number_format($row->itemdsp, 0) }}</td>
                        <td class="text-right">{{ number_format($row->qtydsp, 0) }}</td>
                        <td class="text-right">{{ number_format($row->rphdsp, 0) }}</td>
                        
                        <td class="text-right">
                            {{ number_format(($row->qtydsp / max($row->qtyorder, 1)) * 100, 2, '.', ',') }}
                        </td>
                        <td class="text-right">
                            {{ number_format(($row->rphdsp / max($row->rphorder, 1)) * 100, 2, '.', ',') }}
                        </td>
                        <td class="text-right">
                            {{ number_format(($row->qtydsp / max($totalQtyOrder($row), 1)) * 100, 2, '.', ',') }}
                        </td>
                        <td class="text-right">
                            {{ number_format(($row->rphdsp / max($totalRphOrder($row), 1)) * 100, 2, '.', ',') }}
                        </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@stack('styles')
