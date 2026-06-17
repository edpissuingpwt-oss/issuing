<div class="table-responsive">
    <div class="col-sm-12 col-md-4 text-end">
       <button class="btn btn-sm btn-primary copy-rows-btn" data-title="{{ $title }}">
            <i class="fas fa-copy"></i> Copy
       </button>
    </div>
    <table class="table table-bordered table-striped table-hover" id="sl-table-{{ $title }}">
        <thead>
            @php
                $headerClass = match(strtoupper($title)) {
                    'ALL' => 'bg-danger text-white',
                    'IDM' => 'bg-primary text-white',
                    'OMI' => 'bg-success text-white',
                    default => 'bg-secondary text-white'
                };
            @endphp
            <tr class="{{ $headerClass }}">
                <th width="15%" class="text-center">TANGGAL</th>
                <th width="30%" class="text-center">RUPIAH ORDER</th>
                <th width="30%" class="text-center">RUPIAH REALISASI</th>
                <th width="25%" class="text-center">% SL</th>
            </tr>
        </thead>
        <tbody id="sl-tbody-{{ $title }}">
            @forelse($data as $index => $sl)
            <tr data-index="{{ $index }}">
                <td class="text-center">{{ $sl->tanggal ?? $sl->tanggal_sort ?? '-' }}</td>
                <td class="text-right">{{ number_format($sl->rphorder ?? 0, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($sl->rphrealisasi ?? 0, 0, ',', '.') }}</td>
                <td class="text-center">
                    @php
                        $slPercent = $sl->slharian ?? 0;
                        $badgeClass = $slPercent >= 90 ? 'success' : ($slPercent >= 60 ? 'warning' : 'danger');
                    @endphp
                    <span class="badge badge-{{ $badgeClass }} badge-pill px-3 py-2">
                        {{ number_format($slPercent, 2) }}%
                    </span>
                </td>
            </tr>
            @empty
            <tr id="no-data-{{ $title }}">
                <td colspan="4" class="text-center">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Manual Pagination Controls -->
<div class="row mt-3 align-items-center">
    <div class="col-sm-12 col-md-4">
        <div class="dataTables_info" id="info-{{ $title }}" role="status" aria-live="polite">
            Menampilkan 1-7 dari {{ count($data) }} data
        </div>
    </div>
    <div class="col-sm-12 col-md-4 text-center">
        <div class="dataTables_paginate paging_simple_numbers" id="paginate-{{ $title }}">
            <ul class="pagination justify-content-center">
                <li class="paginate_button page-item previous" id="prev-{{ $title }}">
                    <a href="#" aria-controls="sl-table-{{ $title }}" data-dt-idx="0" tabindex="0" class="page-link">Sebelumnya</a>
                </li>
                <li class="paginate_button page-item active" id="page-1-{{ $title }}">
                    <a href="#" aria-controls="sl-table-{{ $title }}" data-dt-idx="1" tabindex="0" class="page-link">1</a>
                </li>
                <li class="paginate_button page-item next" id="next-{{ $title }}">
                    <a href="#" aria-controls="sl-table-{{ $title }}" data-dt-idx="2" tabindex="0" class="page-link">Selanjutnya</a>
                </li>
            </ul>
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
$(document).ready(function() {
    var tableId = '{{ $title }}';
    var rowsPerPage = 7;
    var currentPage = 1;
    var $rows = $('#sl-tbody-' + tableId + ' tr:not(#no-data-' + tableId + ')');
    var rowCount = $rows.length;
    var pageCount = Math.ceil(rowCount / rowsPerPage);
    
    function showPage(page) {
        if (page < 1) page = 1;
        if (page > pageCount) page = pageCount;
        
        var start = (page - 1) * rowsPerPage;
        var end = start + rowsPerPage;
        
        $rows.hide();
        $rows.slice(start, end).show();
        
        currentPage = page;
        
        var startNum = start + 1;
        var endNum = Math.min(end, rowCount);
        $('#info-' + tableId).html('Menampilkan ' + startNum + ' sampai ' + endNum + ' dari ' + rowCount + ' data');
        
        updatePaginationButtons(page);
    }
    
    function updatePaginationButtons(page) {
        var $paginationDiv = $('#paginate-' + tableId);
        var html = '<ul class="pagination justify-content-center">';
        
        html += '<li class="paginate_button page-item ' + (page <= 1 ? 'disabled' : '') + '">';
        html += '<a href="#" class="page-link" data-page="prev">Sebelumnya</a></li>';
        
        var startPage = Math.max(1, page - 2);
        var endPage = Math.min(pageCount, page + 2);
        
        if (startPage > 1) {
            html += '<li class="paginate_button page-item"><a href="#" class="page-link" data-page="1">1</a></li>';
            if (startPage > 2) html += '<li class="paginate_button page-item disabled"><span class="page-link">...</span></li>';
        }
        
        for (var i = startPage; i <= endPage; i++) {
            html += '<li class="paginate_button page-item ' + (i === page ? 'active' : '') + '">';
            html += '<a href="#" class="page-link" data-page="' + i + '">' + i + '</a></li>';
        }
        
        if (endPage < pageCount) {
            if (endPage < pageCount - 1) html += '<li class="paginate_button page-item disabled"><span class="page-link">...</span></li>';
            html += '<li class="paginate_button page-item"><a href="#" class="page-link" data-page="' + pageCount + '">' + pageCount + '</a></li>';
        }
        
        html += '<li class="paginate_button page-item ' + (page >= pageCount ? 'disabled' : '') + '">';
        html += '<a href="#" class="page-link" data-page="next">Selanjutnya</a></li>';
        
        html += '</ul>';
        $paginationDiv.html(html);
        
        $paginationDiv.find('a.page-link').on('click', function(e) {
            e.preventDefault();
            var pageNum = $(this).data('page');
            if (pageNum === 'prev') showPage(currentPage - 1);
            else if (pageNum === 'next') showPage(currentPage + 1);
            else if (typeof pageNum === 'number') showPage(pageNum);
        });
    }
    
    if (rowCount > rowsPerPage) {
        showPage(1);
    } else {
        $('#pagination-' + tableId).hide();
        if (rowCount > 0) {
            $('#info-' + tableId).html('Menampilkan 1 sampai ' + rowCount + ' dari ' + rowCount + ' data');
        }
    }
});
</script>
@endpush