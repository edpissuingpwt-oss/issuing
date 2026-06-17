@push('styles')
@endpush

<div class="card modern-card shadow-lg border-0 mt-4">
    <div class="card-body modern-body">
            <div id="exportButtons"></div>
        <div class="table-responsive">
            <table id="example1" class="table table-bordered table-striped" style="width:100%">
                <thead class="bg-gradient-info">
                    <tr>
                        <th>No.</th>
                        <th>LJM/MD</th>
                        <th>ITEM TERKAIT</th>
                        <th>RAK</th>
                        <th>FLAG</th>
                        <th>PLUIDM</th>
                        <th>PLUIGR</th>
                        <th>DESKRIPSI</th>
                        <th>FRAC</th>
                        <th>TAGIDM</th>
                        <th>TAGIGR</th>
                        <th>PKMT</th>
                        <th>PKM</th>
                        <th>KOEF</th>
                        <th>LT</th>
                        <th>ITEM</th>
                        <th>ITEM RIIL</th>
                        <th>MINOR</th>
                        <th>KET MINOR</th>
                        <th>QTY_PB</th>
                        <th>QTY_RIL</th>
                        <th>RP_PB</th>
                        <th>RP_RIL</th>
                        <th>RP_SELISIH</th>
                        <th>SL SUPPLIER%</th>
                        <th>M+</th>
                        <th>MAX PLANO</th>
                        <th>RECID 4</th>
                        <th>LPP AWAL</th>
                        <th>LPP AKHIR</th>
                        <th>NO PO</th>
                        <th>PO OUT</th>
                        <th>PB HARI INI</th>
                        <th>PLANO DPD</th>
                        <th>PLANO TOKO</th>
                        <th>KET_STOCK</th>
                        <th>KETERANGAN</th>
                        <th>KPHMEAN</th>
                        <th>TOLAKAN</th>
                        <th>KETERANGAN ITEM</th>
                        <th>DIV</th>
                        <th>DEP</th>
                        <th>KAT</th>
                        <th>KODE SUP</th>
                        <th>NAMA SUP</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $index => $row)
                        <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $row->ljm_md }}</td>
                        <td>{{ $row->item_terkait }}</td>
                        <td>{{ $row->rakk }}</td>
                        <td>{{ $row->flag }}</td>
                        <td>{{ $row->tlko_pluomi }}</td>
                        <td>{{ $row->plu }}</td>
                        <td>{{ $row->prd_deskripsipanjang }}</td>
                        <td>{{ $row->frac }}</td>
                        <td>{{ $row->tag_idm }}</td>
                        <td>{{ $row->tag_igr }}</td>
                        <td>{{ $row->pkm_pkmt }}</td>
                        <td>{{ $row->pkm_pkm }}</td>
                        <td>{{ $row->koef }}</td>
                        <td>{{ $row->lt }}</td>
                        <td>{{ $row->item }}</td>
                        <td>{{ $row->item_rill }}</td>
                        <td>{{ $row->minor }}</td>
                        <td>{{ $row->ket_minor }}</td>
                        <td>{{ $row->qtypbb }}</td>
                        <td>{{ $row->qtyreal }}</td>
                        <td>{{ $row->rupiahpbb }}</td>
                        <td>{{ $row->rupiahreal }}</td>
                        <td>{{ $row->rp_selisih }}</td>
                        <td>{{ $row->sl_supllier }}</td>
                        <td>{{ $row->mplus }}</td>
                        <td>{{ $row->lks_maxplano }}</td>
                        <td>{{ $row->recid4 }}</td>
                        <td>{{ $row->lpp_awal }}</td>
                        <td>{{ $row->lpp_saatini }}</td>
                        <td>{{ $row->npo }}</td>
                        <td>{{ $row->po }}</td>
                        <td>{{ $row->bpb }}</td>
                        <td>{{ $row->planodpd }}</td>
                        <td>{{ $row->planotoko }}</td>
                        <td>{{ $row->ket_stock }}</td>
                        <td>{{ $row->ket }}</td>
                        <td>{{ $row->kphmean }}</td>
                        <td>{{ $row->tlko_kettolakan }}</td>
                        <td>{{ $row->item_seasonal }}</td>
                        <td>{{ $row->div }}</td>
                        <td>{{ $row->dep }}</td>
                        <td>{{ $row->kat }}</td>
                        <td>{{ $row->hgb_kodesupplier }}</td>
                        <td>{{ $row->sup_namasupplier }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@stack('styles')
