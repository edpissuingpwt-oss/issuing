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
                        <th>Tgl</th>
                        <th>No Trans</th>
                        <th>Type</th>
                        <th>Rak Gudang</th>
                        <th>Rak Toko</th>
                        <th>Plu</th>
                        <th>Desk</th>
                        <th>Frac</th>
                        <th>Qty Order (Pcs)</th>
                        <th>LPP</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $index => $row)
                        <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $row->obi_tgltrans }}</td>
                        <td>{{ $row->notrans }}</td>
                        <td>{{ $row->tipe }}</td>
                        <td>{{ $row->alamat_dpd }}</td>
                        <td>{{ $row->alamat_toko }}</td>
                        <td>{{ $row->plu_igr }}</td>
                        <td>{{ $row->prd_deskripsipanjang }}</td>
                        <td>{{ $row->prd_frac }}</td>
                        <td style="text-align: right;">{{ $row->qtypcs }}</td>
                        <td style="text-align: right;">{{ $row->lpp }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@stack('styles')
