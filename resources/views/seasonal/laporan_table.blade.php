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
                        <th>DIV</th>
                        <th>DEPT</th>
                        <th>KAT</th>
                        <th>RAK</th>
                        <th>ZONA</th>
                        <th>PLU IDM</th>
                        <th>PLU IGR</th>
                        <th>DESKRIPSI</th>
                        <th>TIPE</th>
                        <th>FLAG</th>
                        <th>UNIT</th>
                        <th>FRAC</th>
                        <th>TAG_IGR</th>
                        <th>TAG_IDM</th>
                        <th>PLANO_DPD</th>
                        <th>LPP</th>
                        <th>TARGET_ALOKASI</th>
                        <th>ACOST</th>
                        <th>PBRO</th>
                        <th>PBSEASONAL</th>
                        <th>QTYREAL</th>
                        <th>NPB_QTY</th>
                        <th>SELISIH_QTY</th>
                        <th>PERCENT_QTY</th>
                        <th>RPH_TARGET</th>
                        <th>RPH_NPB</th>
                        <th>RPH_SELISIH</th>
                        <th>RPH_PERCENT</th>
                        <th>PO_OUT</th>
                        <th>KODE_SUPPLIER</th>
                        <th>NAMA_SUPPLIER</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $index => $row)
                        <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $row->div }}</td>
                        <td>{{ $row->dept }}</td>
                        <td>{{ $row->kat }}</td>
                        <td>{{ $row->rak }}</td>
                        <td>{{ $row->zona }}</td>
                        <td>{{ $row->pluidm }}</td>
                        <td>{{ $row->pluigr }}</td>
                        <td>{{ $row->description_item }}</td>
                        <td>{{ $row->typee }}</td>
                        <td>{{ $row->flag }}</td>
                        <td>{{ $row->unit }}</td>
                        <td>{{ $row->frac }}</td>
                        <td>{{ $row->tag_igr }}</td>
                        <td>{{ $row->tag_idm }}</td>
                        <td>{{ $row->plano_dpd }}</td>
                        <td>{{ $row->lpp }}</td>
                        <td>{{ $row->target_alokasi }}</td>
                        <td>{{ $row->acost}}</td>
                        <td>{{ $row->pbro}}</td>
                        <td>{{ $row->pbseasonal}}</td>
                        <td>{{ $row->qtyreal}}</td>
                        <td>{{ $row->npb_qty}}</td>
                        <td>{{ $row->selisih_qty}}</td>
                        <td>{{ $row->percent_qty}}</td>
                        <td>{{ $row->rph_target}}</td>
                        <td>{{ $row->rph_npb}}</td>
                        <td>{{ $row->rph_selisih}}</td>
                        <td>{{ $row->rph_percent}}</td>
                        <td>{{ $row->po_out}}</td>
                        <td>{{ $row->kode_supplier }}</td>
                        <td>{{ $row->nama_supplier }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@stack('styles')
