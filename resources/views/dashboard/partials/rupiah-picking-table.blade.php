<div class="tab-pane fade show active" id="rphall" role="tabpanel">
    <div class="table-responsive" id="table-rphall">
        <table id="rphtable_all" class="table table-sm table-bordered table-striped" style="font-size: 11px;">
            <thead class="small-box-coba1">
                <tr>
                    <th scope="col" class="text-center">#</th>
                    <th scope="col" class="text-center">ZONA</th>
                    <th scope="col" class="text-center">JALUR</th>
                    <th scope="col" class="text-center">QTY ORDER</th>
                    <th scope="col" class="text-center">QTY REALISASI</th>
                    <th scope="col" class="text-center">% QTY</th>
                    <th scope="col" class="text-center">RPH ORDER</th>
                    <th scope="col" class="text-center">RPH REALISASI</th>
                    <th scope="col" class="text-center">% RPH</th>
                </tr>
            </thead>
            <tbody>
                @php 
                          $num = 0;
                          $sumQtyOrder = $sumQtyReal = $sumNilaiOrder = $sumNilaiReal = 0;
                @endphp
                @foreach ($rphpickall as $data)
                @php 
                            $num++;
                            $sumQtyOrder += $data->qtyorder;
                            $sumQtyReal += $data->qtyrealisasi;
                            $sumNilaiOrder += $data->nilaiorder;
                            $sumNilaiReal += $data->ttlnilai;
                @endphp
                <tr>
                    <td class="text-center">{{ $num }}</td>
                    <td class="text-center">{{ $data->zona }}</td>
                    <td class="text-center">{{ $data->jalur }}</td>
                    <td class="text-right">{{ number_format($data->qtyorder, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($data->qtyrealisasi, 0, ',', '.') }}</td>
                    <td class="text-right">{{ $data->slqty }}%</td>
                    <td class="text-right">{{ number_format($data->nilaiorder, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($data->ttlnilai, 0, ',', '.') }}</td>
                    <td class="text-right">{{ $data->slnilai }}%</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                @php
                    // Hitung SL QTY
                    $slQty = $sumQtyOrder > 0 ? ($sumQtyReal / $sumQtyOrder * 100) : 0;
                    $slQtyFormatted = $slQty == 100 ? '100' : number_format($slQty, 2);

                    // Hitung SL NILAI
                    $slNilai = $sumNilaiOrder > 0 ? ($sumNilaiReal / $sumNilaiOrder * 100) : 0;
                    $slNilaiFormatted = $slNilai == 100 ? '100' : number_format($slNilai, 2);
                @endphp
                <tr>
                <th colspan="3" class="text-center">TOTAL</th>
                <th class="text-right">{{ number_format($sumQtyOrder) }}</th>
                <th class="text-right">{{ number_format($sumQtyReal) }}</th>
                <th class="text-right">{{ $slQtyFormatted }}%</th>
                <th class="text-right">{{ number_format($sumNilaiOrder) }}</th>
                <th class="text-right">{{ number_format($sumNilaiReal) }}</th>
                <th class="text-right">{{ $slNilaiFormatted }}%</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="tab-pane fade" id="rphidm" role="tabpanel">
    <div class="table-responsive">
        <table id="rphtable_idm" class="table table-sm table-bordered table-striped" style="font-size: 11px;">
            <thead class="small-box-coba1">
                <tr>
                    <th scope="col" class="text-center">#</th>
                    <th scope="col" class="text-center">ZONA</th>
                    <th scope="col" class="text-center">JALUR</th>
                    <th scope="col" class="text-center">QTY ORDER</th>
                    <th scope="col" class="text-center">QTY REALISASI</th>
                    <th scope="col" class="text-center">% QTY</th>
                    <th scope="col" class="text-center">RPH ORDER</th>
                    <th scope="col" class="text-center">RPH REALISASI</th>
                    <th scope="col" class="text-center">% RPH</th>
                </tr>
            </thead>
            <tbody>
                @php 
                          $num = 0;
                          $sumQtyOrder = $sumQtyReal = $sumNilaiOrder = $sumNilaiReal = 0;
                @endphp
                @foreach ($rphpickidm as $data)
                @php 
                            $num++;
                            $sumQtyOrder += $data->qtyorder;
                            $sumQtyReal += $data->qtyrealisasi;
                            $sumNilaiOrder += $data->nilaiorder;
                            $sumNilaiReal += $data->ttlnilai;
                @endphp
                <tr>
                    <td class="text-center">{{ $num }}</td>
                    <td class="text-center">{{ $data->zona }}</td>
                    <td class="text-center">{{ $data->jalur }}</td>
                    <td class="text-right">{{ number_format($data->qtyorder, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($data->qtyrealisasi, 0, ',', '.') }}</td>
                    <td class="text-right">{{ $data->slqty }}%</td>
                    <td class="text-right">{{ number_format($data->nilaiorder, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($data->ttlnilai, 0, ',', '.') }}</td>
                    <td class="text-right">{{ $data->slnilai }}%</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                @php
                    // Hitung SL QTY
                    $slQty = $sumQtyOrder > 0 ? ($sumQtyReal / $sumQtyOrder * 100) : 0;
                    $slQtyFormatted = $slQty == 100 ? '100' : number_format($slQty, 2);

                    // Hitung SL NILAI
                    $slNilai = $sumNilaiOrder > 0 ? ($sumNilaiReal / $sumNilaiOrder * 100) : 0;
                    $slNilaiFormatted = $slNilai == 100 ? '100' : number_format($slNilai, 2);
                @endphp
                <th colspan="3" class="text-center">TOTAL</th>
                <th class="text-right">{{ number_format($sumQtyOrder) }}</th>
                <th class="text-right">{{ number_format($sumQtyReal) }}</th>
                <th class="text-right">{{ $slQtyFormatted }}%</th>
                <th class="text-right">{{ number_format($sumNilaiOrder) }}</th>
                <th class="text-right">{{ number_format($sumNilaiReal) }}</th>
                <th class="text-right">{{ $slNilaiFormatted }}%</th>
            </tfoot>
        </table>
    </div>
</div>

<div class="tab-pane fade" id="rphomi" role="tabpanel">
    <div class="table-responsive">
        <table id="rphtable_omi" class="table table-sm table-bordered table-striped" style="font-size: 11px;">
            <thead class="small-box-coba1">
                <tr>
                    <th scope="col" class="text-center">#</th>
                    <th scope="col" class="text-center">ZONA</th>
                    <th scope="col" class="text-center">JALUR</th>
                    <th scope="col" class="text-center">QTY ORDER</th>
                    <th scope="col" class="text-center">QTY REALISASI</th>
                    <th scope="col" class="text-center">% QTY</th>
                    <th scope="col" class="text-center">RPH ORDER</th>
                    <th scope="col" class="text-center">RPH REALISASI</th>
                    <th scope="col" class="text-center">% RPH</th>
                </tr>
            </thead>
            <tbody>
                @php 
                          $num = 0;
                          $sumQtyOrder = $sumQtyReal = $sumNilaiOrder = $sumNilaiReal = 0;
                @endphp
                @foreach ($rphpickomi as $data)
                @php 
                            $num++;
                            $sumQtyOrder += $data->qtyorder;
                            $sumQtyReal += $data->qtyrealisasi;
                            $sumNilaiOrder += $data->nilaiorder;
                            $sumNilaiReal += $data->ttlnilai;
                @endphp
                <tr>
                    <td class="text-center">{{ $num }}</td>
                    <td class="text-center">{{ $data->zona }}</td>
                    <td class="text-center">{{ $data->jalur }}</td>
                    <td class="text-right">{{ number_format($data->qtyorder, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($data->qtyrealisasi, 0, ',', '.') }}</td>
                    <td class="text-right">{{ $data->slqty }}%</td>
                    <td class="text-right">{{ number_format($data->nilaiorder, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($data->ttlnilai, 0, ',', '.') }}</td>
                    <td class="text-right">{{ $data->slnilai }}%</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                @php
                    // Hitung SL QTY
                    $slQty = $sumQtyOrder > 0 ? ($sumQtyReal / $sumQtyOrder * 100) : 0;
                    $slQtyFormatted = $slQty == 100 ? '100' : number_format($slQty, 2);

                    // Hitung SL NILAI
                    $slNilai = $sumNilaiOrder > 0 ? ($sumNilaiReal / $sumNilaiOrder * 100) : 0;
                    $slNilaiFormatted = $slNilai == 100 ? '100' : number_format($slNilai, 2);
                @endphp
                <th colspan="3" class="text-center">TOTAL</th>
                <th class="text-right">{{ number_format($sumQtyOrder) }}</th>
                <th class="text-right">{{ number_format($sumQtyReal) }}</th>
                <th class="text-right">{{ $slQtyFormatted }}%</th>
                <th class="text-right">{{ number_format($sumNilaiOrder) }}</th>
                <th class="text-right">{{ number_format($sumNilaiReal) }}</th>
                <th class="text-right">{{ $slNilaiFormatted }}%</th>
            </tfoot>
        </table>
    </div>
</div>
