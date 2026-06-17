@push('styles')
@endpush

<div class="card modern-card shadow-lg border-0 mt-4">
    <div class="card-body modern-body">
            <div id="exportButtons"></div>
        <div class="table-responsive">
            <table id="example1" class="table table-bordered table-striped" style="width:100%">
                <thead class="bg-gradient-info">
                    <tr>
                        <th>Kode</th>
                        <th>Awal</th>
                        <th>Akhir</th>
                        <th>Div</th>
                        <th>Dep</th>
                        <th>Kat</th>
                        <th>Pluigr</th>
                        <th>Pluidm</th>
                        <th>Desk</th>
                        <th>Frac</th>
                        <th>Tag_igr</th>
                        <th>Tag_idm</th>
                        <th>Acost</th>
                        <th>Qty_pb</th>
                        <th>Rp_pb</th>
                        <th>Qty_real</th>
                        <th>Rp_real</th>
                        <th>Margin_pb</th>
                        <th>Margin_real</th>
                        <th>Pm</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $index => $row)
                        <tr>
                        <td>{{ $row->kode }}</td>
                        <td>{{ $row->awal }}</td>
                        <td>{{ $row->akhir }}</td>
                        <td>{{ $row->div }}</td>
                        <td>{{ $row->dep }}</td>
                        <td>{{ $row->kat }}</td>
                        <td>{{ $row->pluigr }}</td>
                        <td>{{ $row->pluidm }}</td>
                        <td>{{ $row->desk }}</td>
                        <td>{{ $row->frac }}</td>
                        <td>{{ $row->tag_igr }}</td>
                        <td>{{ $row->tag_idm }}</td>
                        <td style="text-align: right">{{ number_format(round($row->acost), 0, ".", ",") }}</td>
                        <td style="text-align: right">{{ number_format(round($row->qty_pb), 0, ".", ",") }}</td>
                        <td style="text-align: right">{{ number_format(round($row->rp_pb), 0, ".", ",") }}</td>
                        <td style="text-align: right">{{ number_format(round($row->qty_real), 0, ".", ",") }}</td>
                        <td style="text-align: right">{{ number_format(round($row->rp_real), 0, ".", ",") }}</td>
                        <td style="text-align: right">{{ number_format(round($row->margin_pb), 0, ".", ",") }}</td>
                        <td style="text-align: right">{{ number_format(round($row->margin_real), 0, ".", ",") }}</td>
                        <td>{{ $row->pm }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@stack('styles')
