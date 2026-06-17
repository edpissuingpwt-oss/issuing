<div class="card">
    {{-- PB OMI TODAY--}}
    <div class="card-header bg-gradient-success text-white">
        <h5 class="mb-0">PB OMI TODAY</h5>
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
                        <input type="text" id="pboSearch" class="form-control" placeholder="Cari data...">
                    </div>
                    <div class="d-inline-block ml-2">
                        <select id="pboLength" class="form-control form-control-sm d-inline-block" style="width: auto;">
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
                <button type="button" class="btn btn-sm btn-success" onclick="exportToExcel('pbo-table', 'PB_OMI')">
                    <i class="fas fa-file-excel"></i> Excel
                </button>
                <button type="button" class="btn btn-sm btn-primary" onclick="copyPBOMI(this)">
                    <i class="fas fa-copy"></i> Copy
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table id="pbo-table" class="table table-bordered table-striped" style="width:100%">
                <thead class="bg-gradient-success">
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
                    @foreach($pbo as $o => $row)
                    <tr class="data-row">
                        <td class="text-center">{{ $o + 1 }}</td>
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
                            $rphData[0][] = $row->itemp;
                            $rphData[1][] = $row->itemr;
                            $rphData[2][] = $row->qtyp;
                            $rphData[3][] = $row->qtyr;
                            $rphData[4][] = $row->rphp;
                            $rphData[5][] = $row->rphr;
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
                <div id="pboInfo" class="dataTables_info">Showing 0 to 0 of 0 entries</div>
            </div>
            <div class="col-sm-12 col-md-6">
                <nav>
                    <ul class="pagination justify-content-end" id="pboPagination"></ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    {{-- SERVICE LEVEL OMI TODAY --}}
    <div class="card-header bg-gradient-success text-white">
        <h5 class="mb-0">SERVICE LEVEL OMI TODAY</h5>
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
                        <input type="text" id="sloSearch" class="form-control" placeholder="Cari data...">
                    </div>
                    <div class="d-inline-block ml-2">
                        <select id="sloLength" class="form-control form-control-sm d-inline-block" style="width: auto;">
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
                <button type="button" class="btn btn-sm btn-success" onclick="exportToExcel('slo-table', 'SERVICE_LEVEL_OMI')">
                    <i class="fas fa-file-excel"></i> Excel
                </button>
                <button type="button" class="btn btn-sm btn-primary" onclick="copySLOMI(this)">
                    <i class="fas fa-copy"></i> Copy
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table id="slo-table" class="table table-bordered table-striped" style="width:100%">
                <thead class="bg-gradient-success">
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
                @foreach($slo as $index => $row)
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
                <div id="sloInfo" class="dataTables_info">Showing 0 to 0 of 0 entries</div>
            </div>
            <div class="col-sm-12 col-md-6">
                <nav>
                    <ul class="pagination justify-content-end" id="sloPagination"></ul>
                </nav>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

<script>
// Menunggu event global dari index
$(document).ready(function() {
    // Fungsi untuk menginisialisasi manual pagination untuk OMI
    function initOMIManualPagination() {
        // PB OMI Table
        if ($('#pbo-table .data-row').length > 0) {
            if (window.pbslPagination && window.pbslPagination.pbo) {
                window.pbslPagination.pbo.refresh();
            } else if (window.ManualPagination) {
                if (!window.pbslPagination) window.pbslPagination = {};
                window.pbslPagination.pbo = new ManualPagination('pbo-table', 'pboSearch', 'pboLength', 'pboInfo', 'pboPagination');
            }
        }
        
        // SL OMI Table
        if ($('#slo-table .data-row').length > 0) {
            if (window.pbslPagination && window.pbslPagination.slo) {
                window.pbslPagination.slo.refresh();
            } else if (window.ManualPagination) {
                if (!window.pbslPagination) window.pbslPagination = {};
                window.pbslPagination.slo = new ManualPagination('slo-table', 'sloSearch', 'sloLength', 'sloInfo', 'sloPagination');
            }
        }
    }
    
    // Cek apakah tab OMI aktif
    var pbslOmiTab = document.getElementById('pbsl-omi');
    if (pbslOmiTab) {
        var observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.attributeName === 'class') {
                    if (pbslOmiTab.classList.contains('active') || pbslOmiTab.classList.contains('show')) {
                        setTimeout(function() {
                            initOMIManualPagination();
                        }, 200);
                        observer.disconnect();
                    }
                }
            });
        });
        observer.observe(pbslOmiTab, { attributes: true });
        
        // Jika tab sudah aktif
        if (pbslOmiTab.classList.contains('active') || pbslOmiTab.classList.contains('show')) {
            setTimeout(function() {
                initOMIManualPagination();
            }, 200);
        }
    }
});
</script>
@endpush