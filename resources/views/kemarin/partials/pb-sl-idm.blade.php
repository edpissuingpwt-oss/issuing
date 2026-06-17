<div class="card">
    {{-- PBIDM TODAY--}}
    <div class="card-header bg-gradient-primary text-white">
        <h5 class="mb-0">PB IDM TODAY</h5>
    </div>

    <div class="card-body">
        {{-- Search Box dan Entries di kiri, Export Buttons di kanan --}}
        <div class="row mb-3 align-items-center">
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-2">
                    <div class="input-group" style="width: 250px;">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input type="text" id="pbiSearch" class="form-control" placeholder="Cari data...">
                    </div>
                    <div class="d-inline-block ml-2">
                        <select id="pbiLength" class="form-control form-control-sm d-inline-block" style="width: auto;">
                            <option value="5">5 entries</option>
                            <option value="10" selected>10 entries</option>
                            <option value="25">25 entries</option>
                            <option value="50">50 entries</option>
                            <option value="100">100 entries</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6 text-right">
                <button type="button" class="btn btn-sm btn-success" onclick="exportToExcel('pbi-table', 'PB_IDM')">
                    <i class="fas fa-file-excel"></i> Excel
                </button>
                <button type="button" class="btn btn-sm btn-primary" onclick="copyPBIDM(this)">
                    <i class="fas fa-copy"></i> Copy
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table id="pbi-table" class="table table-bordered table-striped" style="width:100%">
                <thead class="bg-gradient-primary">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">KODE</th>
                        <th class="text-center">NAMA TOKO</th>
                        <th class="text-center">ITEM PB</th>
                        <th class="text-center">ITEM REAL</th>
                        <th class="text-center">%</th>
                        <th class="text-center">QTY PB</th>
                        <th class="text-center">QTY REAL</th>
                        <th class="text-center">%</th>
                        <th class="text-center">RPH PB</th>
                        <th class="text-center">RPH REAL</th>
                        <th class="text-center">%</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $rphData = [[], [], [], [], [], []];
                    @endphp
                    @foreach($pbi as $i => $row)
                    <tr class="data-row">
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td class="text-center">{{ $row->kode }}</td>
                        <td class="text-center">{{ $row->nama_toko }}</td>
                        <td class="text-right">{{ number_format($row->itemp) }}</td>
                        <td class="text-right">{{ number_format($row->itemr) }}</td>
                        <td class="text-right">{{ $row->itempc }}%</td>
                        <td class="text-right">{{ number_format($row->qtyp) }}</td>
                        <td class="text-right">{{ number_format($row->qtyr) }}</td>
                        <td class="text-right">{{ $row->qtypc }}%</td>
                        <td class="text-right">{{ number_format($row->rphp) }}</td>
                        <td class="text-right">{{ number_format($row->rphr) }}</td>
                        <td class="text-right">{{ $row->rphpc }}%</td>
                        @php
                            array_push($rphData[0], $row->itemp);
                            array_push($rphData[1], $row->itemr);
                            array_push($rphData[2], $row->qtyp);
                            array_push($rphData[3], $row->qtyr);
                            array_push($rphData[4], $row->rphp);
                            array_push($rphData[5], $row->rphr);
                        @endphp
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="table-active font-weight-bold">
                        <th colspan="3" class="text-center">TOTAL</th>
                        <th class="text-right">{{ number_format(array_sum($rphData[0])) }}</th>
                        <th class="text-right">{{ number_format(array_sum($rphData[1])) }}</th>
                        <th class="text-right">
                            @if(array_sum($rphData[0]) != 0)
                                {{ round(array_sum($rphData[1]) / array_sum($rphData[0]) * 100) }}%
                            @else
                                N/A
                            @endif
                        </th>
                        <th class="text-right">{{ number_format(array_sum($rphData[2])) }}</th>
                        <th class="text-right">{{ number_format(array_sum($rphData[3])) }}</th>
                        <th class="text-right">
                            @if(array_sum($rphData[2]) != 0)
                                {{ round(array_sum($rphData[3]) / array_sum($rphData[2]) * 100) }}%
                            @else
                                N/A
                            @endif
                        </th>
                        <th class="text-right">{{ number_format(array_sum($rphData[4])) }}</th>
                        <th class="text-right">{{ number_format(array_sum($rphData[5])) }}</th>
                        <th class="text-right">
                            @if(array_sum($rphData[4]) != 0)
                                {{ round(array_sum($rphData[5]) / array_sum($rphData[4]) * 100) }}%
                            @else
                                N/A
                            @endif
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        {{-- Pagination --}}
        <div class="row mt-3">
            <div class="col-sm-12 col-md-6">
                <div id="pbiInfo" class="dataTables_info">Showing 0 to 0 of 0 entries</div>
            </div>
            <div class="col-sm-12 col-md-6">
                <nav>
                    <ul class="pagination justify-content-end" id="pbiPagination"></ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    {{-- SERVICE LEVEL IDM TODAY --}}
    <div class="card-header bg-gradient-primary text-white">
        <h5 class="mb-0">SERVICE LEVEL IDM TODAY</h5>
    </div>

    <div class="card-body">
        {{-- Search Box dan Entries di kiri, Export Buttons di kanan --}}
        <div class="row mb-3 align-items-center">
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-2">
                    <div class="input-group" style="width: 250px;">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input type="text" id="sliSearch" class="form-control" placeholder="Cari data...">
                    </div>
                    <div class="d-inline-block ml-2">
                        <select id="sliLength" class="form-control form-control-sm d-inline-block" style="width: auto;">
                            <option value="5">5 entries</option>
                            <option value="10" selected>10 entries</option>
                            <option value="25">25 entries</option>
                            <option value="50">50 entries</option>
                            <option value="100">100 entries</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6 text-right">
                <button type="button" class="btn btn-sm btn-success" onclick="exportToExcel('sli-table', 'SERVICE_LEVEL_IDM')">
                    <i class="fas fa-file-excel"></i> Excel
                </button>
                <button type="button" class="btn btn-sm btn-primary" onclick="copySLIDM(this)">
                    <i class="fas fa-copy"></i> Copy
                </button>
            </div>
        </div>


        <div class="table-responsive">
            <table id="sli-table" class="table table-bordered table-striped" style="width:100%">
                <thead class="bg-gradient-primary">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">PLU</th>
                        <th class="text-center">DESKRIPSI</th>
                        <th class="text-center">TOKO ORDER</th>
                        <th class="text-center">QTY ORDER</th>
                        <th class="text-center">QTY REALISASI</th>
                        <th class="text-center">%QTY</th>
                        <th class="text-center">RPH ORDER</th>
                        <th class="text-center">RPH REALISASI</th>
                        <th class="text-center">%NILAI</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($sli as $index => $row)
                    <tr class="data-row">
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center">{{ $row->plu }}</td>
                        <td class="text-center">{{ $row->deskripsi }}</td>
                        <td class="text-right">{{ number_format(round($row->jml_toko), 0, ".", ",") }}</td>
                        <td class="text-right">{{ number_format(round($row->qtyo), 0, ".", ",") }}</td>
                        <td class="text-right">{{ number_format(round($row->qtyr), 0, ".", ",") }}</td>
                        <td class="text-right">{{ number_format(round($row->slqty), 0, ".", ",") }}%</td>
                        <td class="text-right">{{ number_format(round($row->nilaio), 0, ".", ",") }}</td>
                        <td class="text-right">{{ number_format(round($row->ttlnilai), 0, ".", ",") }}</td>
                        <td class="text-right">{{ number_format(round($row->slnilai), 0, ".", ",") }}%</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        <div class="row mt-3">
            <div class="col-sm-12 col-md-6">
                <div id="sliInfo" class="dataTables_info">Showing 0 to 0 of 0 entries</div>
            </div>
            <div class="col-sm-12 col-md-6">
                <nav>
                    <ul class="pagination justify-content-end" id="sliPagination"></ul>
                </nav>
            </div>
        </div>
    </div>
</div>

@push('scripts')

@endpush