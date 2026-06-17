<div class="card">

    <div class="card-header bg-gradient-primary text-white">
        <h5 class="mb-0">PB MENTAH IDM</h5>
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
                        <input type="text" id="pbmiSearch" class="form-control" placeholder="Cari data...">
                    </div>
                    <div class="d-inline-block ml-2">
                        <select id="pbmiLength" class="form-control form-control-sm d-inline-block" style="width: auto;">
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
                <button type="button" class="btn btn-sm btn-success" onclick="exportToExcel('pbmi-table', 'PB_MENTAH_IDM')">
                    <i class="fas fa-file-excel"></i> Excel
                </button>
                <button type="button" class="btn btn-sm btn-primary" onclick="copyPBMIDM(this)">
                    <i class="fas fa-copy"></i> Copy
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table id="pbmi-table" class="table table-bordered table-striped" style="width:100%">
                <thead class="bg-gradient-primary">
                    <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">JUMLAH TOKO</th>
                            <th class="text-center">PLU IGR</th>
                            <th class="text-center">ZONA</th>
                            <th class="text-center">ALAMAT</th>
                            <th class="text-center">DESKRIPSI</th>
                            <th class="text-center">FRAC</th>
                            <th class="text-center">LPP</th>
                            <th class="text-center">PLANO</th>
                            <th class="text-center">QTY PCS</th>
                            <th class="text-center">QTY CTN</th>
                            <th class="text-center">ACOST PCS</th>
                            <th class="text-center">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalrph = 0 @endphp
                    @foreach($pbmi as $i => $row)
                            @php
                                    $totalrph += $row->total;
                                @endphp
                    <tr class="data-row">
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td class="text-center">{{ $row->toko }}</td>
                            <td class="text-center">{{ $row->prd_prdcd }}</td>
                            <td class="text-center">{{ $row->zona }}</td>
                            <td class="text-center">{{ $row->rak }}</td>
                            <td class="text-center">{{ $row->prd_deskripsipanjang }}</td>
                            <td class="text-center">{{ $row->frac }}</td>
                            <td class="text-center">{{ $row->lpp }}</td>
                            <td class="text-center">{{ $row->plano }}</td>
                            <td style="text-align: right">{{ number_format(round($row->qty), 0, ".", ",") }}</td>
                            <td style="text-align: right">{{ number_format(round($row->qty_ctn), 0, ".", ",") }}</td>
                            <td style="text-align: right">{{ number_format(round($row->acostpcs), 0, ".", ",") }}</td>
                            <td style="text-align: right">{{ number_format(round($row->total), 0, ".", ",") }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="table-active font-weight-bold">
                            <th colspan="12" class="text-center">TOTAL</th>
                            <td class="text-right">{{ number_format($totalrph) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        {{-- Pagination --}}
        <div class="row mt-3">
            <div class="col-sm-12 col-md-6">
                <div id="pbmiInfo" class="dataTables_info">Showing 0 to 0 of 0 entries</div>
            </div>
            <div class="col-sm-12 col-md-6">
                <nav>
                    <ul class="pagination justify-content-end" id="pbmiPagination"></ul>
                </nav>
            </div>
        </div>
    </div>
</div>


@push('scripts')
<script>
// Fungsi untuk copy table ke clipboard
function copyTable(tableId) {
    const table = document.getElementById(tableId);
    const range = document.createRange();
    range.selectNode(table);
    window.getSelection().removeAllRanges();
    window.getSelection().addRange(range);
    
    try {
        document.execCommand('copy');
        showToast('Data berhasil disalin ke clipboard!', 'success');
    } catch (err) {
        showToast('Gagal menyalin data!', 'error');
    }
    
    window.getSelection().removeAllRanges();
}

// Fungsi untuk export ke Excel
function exportToExcel(tableId, sheetName) {
    const table = document.getElementById(tableId);
    const wb = XLSX.utils.book_new();
    const ws = XLSX.utils.table_to_sheet(table, { raw: true });
    
    // Set lebar kolom
    ws['!cols'] = [
        {wch:5}, {wch:10}, {wch:30}, {wch:12}, {wch:12}, 
        {wch:8}, {wch:12}, {wch:12}, {wch:8}, {wch:15}, 
        {wch:15}, {wch:8}
    ];
    
    XLSX.utils.book_append_sheet(wb, ws, sheetName);
    
    const today = new Date();
    const dateStr = today.getFullYear() + '-' + 
                    String(today.getMonth() + 1).padStart(2, '0') + '-' + 
                    String(today.getDate()).padStart(2, '0');
    const fileName = sheetName + '_' + dateStr + '.xlsx';
    
    XLSX.writeFile(wb, fileName);
    showToast('Export Excel berhasil!', 'success');
}

// Fungsi show toast sederhana
function showToast(message, type) {
    // Cek apakah toast container ada
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999;';
        document.body.appendChild(toastContainer);
    }
    
    const toast = document.createElement('div');
    toast.className = 'toast show';
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    toast.style.cssText = 'background-color: ' + (type === 'success' ? '#28a745' : '#dc3545') + '; color: white; padding: 12px 20px; border-radius: 8px; margin-bottom: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); animation: slideIn 0.3s ease;';
    
    toast.innerHTML = '<i class="fas ' + (type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle') + ' mr-2"></i> ' + message;
    
    toastContainer.appendChild(toast);
    
    setTimeout(function() {
        toast.style.animation = 'slideOut 0.3s ease';
        setTimeout(function() {
            toast.remove();
        }, 300);
    }, 3000);
}

// Style untuk animasi toast
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
`;
document.head.appendChild(style);

// Manual Pagination Function
function setupManualPagination(tableId, searchInputId, lengthSelectId, infoId, paginationId) {
    const table = document.getElementById(tableId);
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr.data-row'));
    const totalRows = rows.length;
    
    let currentPage = 1;
    let rowsPerPage = parseInt(document.getElementById(lengthSelectId).value);
    let searchTerm = '';
    
    function filterRows() {
        if (!searchTerm) return rows;
        
        return rows.filter(row => {
            const text = row.textContent.toLowerCase();
            return text.includes(searchTerm.toLowerCase());
        });
    }
    
    function displayRows() {
        const filteredRows = filterRows();
        const totalFiltered = filteredRows.length;
        const totalPages = Math.ceil(totalFiltered / rowsPerPage);
        
        // Sembunyikan semua row
        rows.forEach(row => row.style.display = 'none');
        
        // Tampilkan row yang sesuai dengan halaman saat ini
        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        const rowsToShow = filteredRows.slice(start, end);
        
        rowsToShow.forEach(row => row.style.display = '');
        
        // Update info
        const info = document.getElementById(infoId);
        const startNum = totalFiltered === 0 ? 0 : start + 1;
        const endNum = Math.min(end, totalFiltered);
        info.textContent = `Showing ${startNum} to ${endNum} of ${totalFiltered} entries`;
        
        // Update pagination
        updatePagination(totalPages, currentPage, paginationId, function(page) {
            currentPage = page;
            displayRows();
        });
        
        // Update nomor urut
        rowsToShow.forEach((row, idx) => {
            const firstCell = row.cells[0];
            firstCell.textContent = start + idx + 1;
            firstCell.className = 'text-center';
        });
    }
    
    // Search event
    document.getElementById(searchInputId).addEventListener('keyup', function(e) {
        searchTerm = e.target.value;
        currentPage = 1;
        displayRows();
    });
    
    // Rows per page change
    document.getElementById(lengthSelectId).addEventListener('change', function(e) {
        rowsPerPage = parseInt(e.target.value);
        currentPage = 1;
        displayRows();
    });
    
    // Initial display
    displayRows();
}

function updatePagination(totalPages, currentPage, paginationId, callback) {
    const pagination = document.getElementById(paginationId);
    pagination.innerHTML = '';
    
    if (totalPages <= 1) return;
    
    // Previous button
    const prevLi = document.createElement('li');
    prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
    prevLi.innerHTML = `<a class="page-link" href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>`;
    prevLi.addEventListener('click', (e) => {
        e.preventDefault();
        if (currentPage > 1) {
            callback(currentPage - 1);
        }
    });
    pagination.appendChild(prevLi);
    
    // Page numbers
    const startPage = Math.max(1, currentPage - 2);
    const endPage = Math.min(totalPages, currentPage + 2);
    
    if (startPage > 1) {
        const firstLi = document.createElement('li');
        firstLi.className = 'page-item';
        firstLi.innerHTML = `<a class="page-link" href="#">1</a>`;
        firstLi.addEventListener('click', (e) => {
            e.preventDefault();
            callback(1);
        });
        pagination.appendChild(firstLi);
        
        if (startPage > 2) {
            const dotsLi = document.createElement('li');
            dotsLi.className = 'page-item disabled';
            dotsLi.innerHTML = '<span class="page-link">...</span>';
            pagination.appendChild(dotsLi);
        }
    }
    
    for (let i = startPage; i <= endPage; i++) {
        const pageLi = document.createElement('li');
        pageLi.className = `page-item ${i === currentPage ? 'active' : ''}`;
        pageLi.innerHTML = `<a class="page-link" href="#">${i}</a>`;
        pageLi.addEventListener('click', (e) => {
            e.preventDefault();
            callback(i);
        });
        pagination.appendChild(pageLi);
    }
    
    if (endPage < totalPages) {
        if (endPage < totalPages - 1) {
            const dotsLi = document.createElement('li');
            dotsLi.className = 'page-item disabled';
            dotsLi.innerHTML = '<span class="page-link">...</span>';
            pagination.appendChild(dotsLi);
        }
        
        const lastLi = document.createElement('li');
        lastLi.className = 'page-item';
        lastLi.innerHTML = `<a class="page-link" href="#">${totalPages}</a>`;
        lastLi.addEventListener('click', (e) => {
            e.preventDefault();
            callback(totalPages);
        });
        pagination.appendChild(lastLi);
    }
    
    // Next button
    const nextLi = document.createElement('li');
    nextLi.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
    nextLi.innerHTML = `<a class="page-link" href="#" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>`;
    nextLi.addEventListener('click', (e) => {
        e.preventDefault();
        if (currentPage < totalPages) {
            callback(currentPage + 1);
        }
    });
    pagination.appendChild(nextLi);
}

// Inisialisasi saat tab ditampilkan
$(document).ready(function() {
    function initTables() {
        if ($('#pbmi-table .data-row').length > 0) {
            setupManualPagination('pbmi-table', 'pbmiSearch', 'pbmiLength', 'pbmiInfo', 'pbmiPagination');
        }
    }
    
    // Cek apakah tab aktif
    var pbmIdmTab = document.getElementById('pbmentah-idm');
    if (pbmIdmTab && (pbmIdmTab.classList.contains('active') || pbmIdmTab.classList.contains('show'))) {
        setTimeout(initTables, 100);
    }
    
    // Observer untuk tab
    if (pbmIdmTab) {
        var observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.attributeName === 'class') {
                    if (pbmIdmTab.classList.contains('active') || pbmIdmTab.classList.contains('show')) {
                        setTimeout(initTables, 100);
                        observer.disconnect();
                    }
                }
            });
        });
        observer.observe(pbmIdmTab, { attributes: true });
    }
});
</script>
@endpush