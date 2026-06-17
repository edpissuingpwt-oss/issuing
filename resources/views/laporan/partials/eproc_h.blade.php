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
                        <th class="text-center">NOPB</th>
                        <th class="text-center">KETERANGAN</th>
                        <th class="text-center">TGLPB</th>
                        <th class="text-center">PLU</th>
                        <th class="text-center">DESKRIPSI</th>
                        <th class="text-center">FRAC</th>
                        <th class="text-center">QTYPB</th>
                        <th class="text-center">GROSS</th>
                        <th class="text-center">PPN</th>
                        <th class="text-center">FLAG</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $index => $row)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">{{ $row->nopb }}</td>
                            <td class="text-center">{{ $row->keterangan }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($row->tglpb)->format('d-m-Y') }}</td>
                            <td class="text-center">{{ $row->plu }}</td>
                            <td class="text-center">{{ $row->desk }}</td>
                            <td class="text-center">{{ $row->frac }}</td>
                            <td style="text-align: right">{{ $row->qtypb }}</td>
                            <td style="text-align: right">{{ $row->gross }}</td>
                            <td style="text-align: right">{{ $row->ppn }}</td>
                            <td class="text-center">{{ $row->flag }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@stack('styles')
