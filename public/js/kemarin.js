    document.addEventListener('DOMContentLoaded', function () {

        function updateClock() {
            const now = new Date();

            const jam = String(now.getHours()).padStart(2, '0');
            const menit = String(now.getMinutes()).padStart(2, '0');
            const detik = String(now.getSeconds()).padStart(2, '0');

            document.getElementById('live-clock').innerHTML =
                `${jam}:${menit}:${detik}`;
        }

        setInterval(updateClock, 1000);
        updateClock();

    });
$(document).ready(function() {
    
    // ==================== GLOBAL FUNCTIONS ====================
    
    // Show Toast Notification
    window.showToast = function(message, type) {

        let toastContainer = document.getElementById('toast-container');

        if (!toastContainer) {

            toastContainer = document.createElement('div');

            toastContainer.id = 'toast-container';

            toastContainer.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
            `;

            document.body.appendChild(toastContainer);
        }

        const toast = document.createElement('div');

        toast.className = 'toast show';

        toast.style.cssText = `
            background-color: ${type === 'success'
                ? '#28a745'
                : '#dc3545'};
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 10000;
            animation: slideIn 0.3s ease;
        `;

        toast.innerHTML = `
            <i class="fas ${type === 'success'
                ? 'fa-check-circle'
                : 'fa-exclamation-circle'} mr-2"></i>
            ${message}
        `;

        toastContainer.appendChild(toast);

        setTimeout(() => {

            toast.style.animation = 'slideOut 0.3s ease';

            setTimeout(() => {
                toast.remove();
            }, 300);

        }, 3000);

    };
    

    // Export to Excel
    window.exportToExcel = function(tableId, sheetName) {

        const table = document.getElementById(tableId);

        if (!table) {
            showToast('Table tidak ditemukan!', 'error');
            return;
        }

        try {

            // =========================
            // DETECT SEARCH INPUT
            // =========================

            let searchValue = '';

            // cari input search terdekat
            const searchInput = table
                .closest('.card, .tab-pane, body')
                .querySelector('input[type="search"], input.form-control');

            if (searchInput) {
                searchValue = searchInput.value.trim();
            }

            const isSearching = searchValue !== '';

            // =========================
            // CLONE TABLE
            // =========================

            const cloneTable = table.cloneNode(true);

            const originalRows = table.querySelectorAll('tbody tr');
            const cloneRows = cloneTable.querySelectorAll('tbody tr');

            // =========================
            // JIKA SEARCH AKTIF
            // export hanya visible rows
            // =========================

            if (isSearching) {

                cloneRows.forEach((row, index) => {

                    const originalRow = originalRows[index];

                    const isHidden =
                        originalRow.style.display === 'none' ||
                        window.getComputedStyle(originalRow).display === 'none';

                    if (isHidden) {
                        row.remove();
                    }
                });

            } else {

                // =========================
                // JIKA TIDAK SEARCH
                // tampilkan semua rows
                // =========================

                cloneRows.forEach(row => {
                    row.style.display = '';
                });
            }

            // =========================
            // HAPUS KOLOM ACTION
            // =========================

            $(cloneTable).find('th:last-child, td:last-child').each(function() {

                const text = $(this).text().trim().toLowerCase();

                if (
                    text === '' ||
                    text === 'action' ||
                    text === 'aksi'
                ) {
                    $(this).remove();
                }
            });

            // =========================
            // EXPORT XLSX
            // =========================

            const wb = XLSX.utils.book_new();

            const ws = XLSX.utils.table_to_sheet(cloneTable, {
                raw: true
            });

            ws['!cols'] = [
                { wch: 5 },
                { wch: 15 },
                { wch: 35 },
                { wch: 15 },
                { wch: 15 },
                { wch: 10 },
                { wch: 15 },
                { wch: 15 },
                { wch: 10 },
                { wch: 20 },
                { wch: 20 },
                { wch: 12 }
            ];

            XLSX.utils.book_append_sheet(wb, ws, sheetName);

            const today = new Date();

            const dateStr =
                today.getFullYear() + '-' +
                String(today.getMonth() + 1).padStart(2, '0') + '-' +
                String(today.getDate()).padStart(2, '0');

            const fileName = sheetName + '_' + dateStr + '.xlsx';

            XLSX.writeFile(wb, fileName);

            showToast('Export Excel berhasil!', 'success');

        } catch (err) {

            console.error('Export error:', err);

            showToast('Gagal export Excel!', 'error');
        }
    };
    
    // Copy Table to Clipboard
    window.copyTable = async function(tableId) {
        const table = document.getElementById(tableId);
        if (!table) {
            showToast('Table tidak ditemukan!', 'error');
            return;
        }
        
        try {
            let copyText = '';
            
            // Header
            const headers = table.querySelectorAll('thead th');
            const headerTexts = Array.from(headers).map(th => th.innerText.trim());
            copyText += headerTexts.join('\t') + '\n';
            
            // Data rows (hanya yang visible)
            const rows = table.querySelectorAll('tbody tr:not([style*="display: none"])');
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                const rowText = Array.from(cells).map(td => td.innerText.trim()).join('\t');
                copyText += rowText + '\n';
            });
            
            await navigator.clipboard.writeText(copyText);
            showToast('Data berhasil disalin ke clipboard!', 'success');
        } catch (err) {
            fallbackCopyTable(copyText);
        }
    };
    
    function fallbackCopyTable(text) {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.style.position = 'fixed';
        textarea.style.top = '-9999px';
        document.body.appendChild(textarea);
        textarea.select();
        try {
            document.execCommand('copy');
            showToast('Data berhasil disalin ke clipboard!', 'success');
        } catch (err) {
            showToast('Gagal menyalin data!', 'error');
        }
        document.body.removeChild(textarea);
    }
    
    // ==================== MANUAL PAGINATION CLASS ====================
    class ManualPagination {
        constructor(tableId, searchInputId, lengthSelectId, infoId, paginationId) {
            this.tableId = tableId;
            this.searchInputId = searchInputId;
            this.lengthSelectId = lengthSelectId;
            this.infoId = infoId;
            this.paginationId = paginationId;
            this.currentPage = 1;
            this.rowsPerPage = 10;
            this.searchTerm = '';
            this.rows = [];
            
            this.init();
        }
        
        init() {
            this.updateRows();
            this.attachEvents();
            this.displayRows();
        }
        
        updateRows() {
            const table = document.getElementById(this.tableId);
            if (!table) return;
            
            const tbody = table.querySelector('tbody');
            if (!tbody) return;
            
            // Hanya ambil row dengan class data-row atau semua tr selain di tfoot
            this.rows = Array.from(tbody.querySelectorAll('tr'));
        }
        
        attachEvents() {
            const searchInput = document.getElementById(this.searchInputId);
            const lengthSelect = document.getElementById(this.lengthSelectId);
            
            if (searchInput && !searchInput.hasAttribute('data-listener')) {
                searchInput.setAttribute('data-listener', 'true');
                searchInput.addEventListener('input', (e) => {
                    this.searchTerm = e.target.value.toLowerCase();
                    this.currentPage = 1;
                    this.displayRows();
                });
            }
            
            if (lengthSelect && !lengthSelect.hasAttribute('data-listener')) {
                lengthSelect.setAttribute('data-listener', 'true');
                lengthSelect.addEventListener('change', (e) => {
                    this.rowsPerPage = parseInt(e.target.value);
                    this.currentPage = 1;
                    this.displayRows();
                });
            }
        }
        
        filterRows() {
            if (!this.searchTerm) return this.rows;
            
            return this.rows.filter(row => {
                const text = row.textContent.toLowerCase();
                return text.includes(this.searchTerm);
            });
        }
        
        displayRows() {
            const filteredRows = this.filterRows();
            const totalFiltered = filteredRows.length;
            const totalPages = Math.ceil(totalFiltered / this.rowsPerPage) || 1;
            
            // Sembunyikan semua row
            this.rows.forEach(row => row.style.display = 'none');
            
            // Tampilkan row yang sesuai
            const start = (this.currentPage - 1) * this.rowsPerPage;
            const end = Math.min(start + this.rowsPerPage, totalFiltered);
            const rowsToShow = filteredRows.slice(start, end);
            
            rowsToShow.forEach(row => row.style.display = '');
            
            // Update info
            const info = document.getElementById(this.infoId);
            if (info) {
                const startNum = totalFiltered === 0 ? 0 : start + 1;
                info.textContent = `Showing ${startNum} to ${end} of ${totalFiltered} entries`;
            }
            
            // Update nomor urut (jika kolom pertama adalah nomor)
            rowsToShow.forEach((row, idx) => {
                const firstCell = row.cells[0];
                if (firstCell && firstCell.classList.contains('text-center')) {
                    firstCell.textContent = start + idx + 1;
                }
            });
            
            // Update pagination
            this.updatePagination(totalPages);
        }
        
        updatePagination(totalPages) {
            const pagination = document.getElementById(this.paginationId);
            if (!pagination) return;
            
            pagination.innerHTML = '';
            
            if (totalPages <= 1) return;
            
            // Previous button
            const prevLi = document.createElement('li');
            prevLi.className = `page-item ${this.currentPage === 1 ? 'disabled' : ''}`;
            prevLi.innerHTML = `<a class="page-link" href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>`;
            prevLi.addEventListener('click', (e) => {
                e.preventDefault();
                if (this.currentPage > 1) {
                    this.currentPage--;
                    this.displayRows();
                }
            });
            pagination.appendChild(prevLi);
            
            // Page numbers
            let startPage = Math.max(1, this.currentPage - 2);
            let endPage = Math.min(totalPages, this.currentPage + 2);
            
            if (startPage > 1) {
                const firstLi = document.createElement('li');
                firstLi.className = 'page-item';
                firstLi.innerHTML = `<a class="page-link" href="#">1</a>`;
                firstLi.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.currentPage = 1;
                    this.displayRows();
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
                pageLi.className = `page-item ${i === this.currentPage ? 'active' : ''}`;
                pageLi.innerHTML = `<a class="page-link" href="#">${i}</a>`;
                pageLi.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.currentPage = i;
                    this.displayRows();
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
                    this.currentPage = totalPages;
                    this.displayRows();
                });
                pagination.appendChild(lastLi);
            }
            
            // Next button
            const nextLi = document.createElement('li');
            nextLi.className = `page-item ${this.currentPage === totalPages ? 'disabled' : ''}`;
            nextLi.innerHTML = `<a class="page-link" href="#" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>`;
            nextLi.addEventListener('click', (e) => {
                e.preventDefault();
                if (this.currentPage < totalPages) {
                    this.currentPage++;
                    this.displayRows();
                }
            });
            pagination.appendChild(nextLi);
        }
        
        refresh() {
            this.updateRows();
            this.currentPage = 1;
            this.searchTerm = '';
            const searchInput = document.getElementById(this.searchInputId);
            if (searchInput) searchInput.value = '';
            this.displayRows();
        }
    }
    
    // Store pagination instances
    window.pbmentahPagination = {};
    window.pbslPagination = {};
    

    // Initialize PBMentah Tables
    function initPbmentahTables(type) {

        setTimeout(() => {

            if (type === 'idm') {

                // PB MENTAH IDM
                if (
                    $('#pbmi-table .data-row').length > 0 ||
                    $('#pbmi-table tbody tr').length > 0
                ) {

                    if (window.pbmentahPagination.pbmi) {

                        window.pbmentahPagination.pbmi.refresh();

                    } else {

                        window.pbmentahPagination.pbmi =
                            new ManualPagination(
                                'pbmi-table',
                                'pbmiSearch',
                                'pbmiLength',
                                'pbmiInfo',
                                'pbmiPagination'
                            );
                    }
                }

            } else if (type === 'omi') {

                // PB MENTAH OMI
                if (
                    $('#pbmo-table .data-row').length > 0 ||
                    $('#pbmo-table tbody tr').length > 0
                ) {

                    if (window.pbmentahPagination.pbmo) {

                        window.pbmentahPagination.pbmo.refresh();

                    } else {

                        window.pbmentahPagination.pbmo =
                            new ManualPagination(
                                'pbmo-table',
                                'pbmoSearch',
                                'pbmoLength',
                                'pbmoInfo',
                                'pbmoPagination'
                            );
                    }
                }
            }

        }, 200);
    }

        // Initialize PBSL Tables
    function initPbslTables(type) {
        setTimeout(() => {
            if (type === 'idm') {
                // PB IDM Table
                if ($('#pbi-table .data-row').length > 0 || $('#pbi-table tbody tr').length > 0) {
                    if (window.pbslPagination.pbi) {
                        window.pbslPagination.pbi.refresh();
                    } else {
                        window.pbslPagination.pbi = new ManualPagination('pbi-table', 'pbiSearch', 'pbiLength', 'pbiInfo', 'pbiPagination');
                    }
                }
                // SL IDM Table
                if ($('#sli-table tbody tr').length > 0) {
                    if (window.pbslPagination.sli) {
                        window.pbslPagination.sli.refresh();
                    } else {
                        window.pbslPagination.sli = new ManualPagination('sli-table', 'sliSearch', 'sliLength', 'sliInfo', 'sliPagination');
                    }
                }
            } else if (type === 'omi') {
                // PB OMI Table
                if ($('#pbo-table tbody tr').length > 0) {
                    if (window.pbslPagination.pbo) {
                        window.pbslPagination.pbo.refresh();
                    } else {
                        window.pbslPagination.pbo = new ManualPagination('pbo-table', 'pboSearch', 'pboLength', 'pboInfo', 'pboPagination');
                    }
                }
                // SL OMI Table
                if ($('#slo-table tbody tr').length > 0) {
                    if (window.pbslPagination.slo) {
                        window.pbslPagination.slo.refresh();
                    } else {
                        window.pbslPagination.slo = new ManualPagination('slo-table', 'sloSearch', 'sloLength', 'sloInfo', 'sloPagination');
                    }
                }
            }
        }, 200);
    }
    
    // ==================== REFRESH FUNCTIONS ====================
    
    // Refresh SL Harian
    function refreshSL() {
        var $btn = $('.refresh-btn[data-tab="sl"]');

        $btn.addClass('loading');

        $.ajax({
            url: "/kemarin/sl-harian?t=" + Date.now(),
            method: "GET",

            success: function(response) {

                $('#sl-all').html(response.html_all);
                $('#sl-idm').html(response.html_idm);
                $('#sl-omi').html(response.html_omi);

            },

            complete: function() {
                $btn.removeClass('loading');
            },

            error: function(xhr) {
                console.log(xhr.responseText);
                alert('Gagal refresh SL Harian');
                $btn.removeClass('loading');
            }
        });
    }
    
    // Refresh PBMentah
    function refreshPbmentah() {
        var $btn = $('.refresh-btn[data-tab="pbmentah"]');
        var activeSubTab = $('#pbmentahSubTabs .nav-link.active').attr('href');
        var type = 'idm';
        
        if (activeSubTab === '#pbmentah-omi') type = 'omi';
        
        var containerId = '#pbmentah-' + type + '-container';
        var url = "/kemarin/pb-mentah/" + type;
        
        $(containerId).data('loaded', false);
        $btn.addClass('loading');
        
        $.ajax({
            url: url + '?t=' + Date.now(),
            method: 'GET',
            success: function(response) {
                $(containerId).html(response.html);
                $(containerId).data('loaded', true);
                // Re-initialize pagination
                initPbmentahTables(type);
            },
            complete: function() {
                $btn.removeClass('loading');
            },
            error: function() {
                $(containerId).html('<div class="alert alert-danger">Gagal memuat data</div>');
                $btn.removeClass('loading');
            }
        });
    }

    // Refresh PBSL
    function refreshPbsl() {
        var $btn = $('.refresh-btn[data-tab="pbsl"]');
        var activeSubTab = $('#pbslSubTabs .nav-link.active').attr('href');
        var type = 'idm';
        
        if (activeSubTab === '#pbsl-omi') type = 'omi';
        
        var containerId = '#pbsl-' + type + '-container';
        var url = "/kemarin/pb-sl/" + type;
        
        $(containerId).data('loaded', false);
        $btn.addClass('loading');
        
        $.ajax({
            url: url + '?t=' + Date.now(),
            method: 'GET',
            success: function(response) {
                $(containerId).html(response.html);
                $(containerId).data('loaded', true);
                // Re-initialize pagination
                initPbslTables(type);
            },
            complete: function() {
                $btn.removeClass('loading');
            },
            error: function() {
                $(containerId).html('<div class="alert alert-danger">Gagal memuat data</div>');
                $btn.removeClass('loading');
            }
        });
    }
    
    // Refresh Rupiah Picking
    function refreshRupiah() {
        var $btn = $('.refresh-btn[data-tab="rupiah"]');
        var activeSubTab = $('#rupiahSubTabs .nav-link.active').attr('href');
        var type = 'all';
        
        if (activeSubTab === '#rupiah-idm') type = 'idm';
        else if (activeSubTab === '#rupiah-omi') type = 'omi';
        
        var containerId = '#rupiah-' + type + '-container';
        var url = "/kemarin/rupiah-picking/" + type;
        
        $(containerId).data('loaded', false);
        $btn.addClass('loading');
        
        $.ajax({
            url: url + '?t=' + Date.now(),
            method: 'GET',
            success: function(response) {
                $(containerId).html(response.html);
                $(containerId).data('loaded', true);
            },
            complete: function() {
                $btn.removeClass('loading');
            },
            error: function() {
                $(containerId).html('<div class="alert alert-danger">Gagal memuat data</div>');
                $btn.removeClass('loading');
            }
        });
    }
    
    // Refresh Monitoring Picking
    function refreshPicking() {
        var $btn = $('.refresh-btn[data-tab="picking"]');
        $('#picking-container').data('loaded', false);
        $btn.addClass('loading');
        
        $.ajax({
            url: "/kemarin/monitor-picking?t=" + Date.now(),
            method: 'GET',
            success: function(response) {
                $('#picking-container').html(response.html);
                $('#picking-container').data('loaded', true);
            },
            complete: function() {
                $btn.removeClass('loading');
            },
            error: function() {
                $('#picking-container').html('<div class="alert alert-danger">Gagal memuat data</div>');
                $btn.removeClass('loading');
            }
        });
    }
    
    // Refresh Monitoring Loading
    function refreshLoading() {
        var $btn = $('.refresh-btn[data-tab="loading"]');
        $('#loading-container').data('loaded', false);
        $btn.addClass('loading');
        
        $.ajax({
            url: "/kemarin/monitor-loading?t=" + Date.now(),
            method: 'GET',
            success: function(response) {
                $('#loading-container').html(response.html);
                $('#loading-container').data('loaded', true);
            },
            complete: function() {
                $btn.removeClass('loading');
            },
            error: function() {
                $('#loading-container').html('<div class="alert alert-danger">Gagal memuat data</div>');
                $btn.removeClass('loading');
            }
        });
    }
    
    // ==================== LOAD FUNCTIONS ====================
    function loadPbmentah(subTab) {
        var containerId = '', url = '';
        if (subTab === 'idm') { 
            containerId = '#pbmentah-idm-container'; 
            url = "/kemarin/pb-mentah/idm"; 
        }
        else if (subTab === 'omi') { 
            containerId = '#pbmentah-omi-container'; 
            url = "/kemarin/pb-mentah/omi"; 
        }
        
        if ($(containerId).data('loaded')) return;
        
        $.ajax({
            url: url, 
            method: 'GET',
            success: function(response) { 
                $(containerId).html(response.html); 
                $(containerId).data('loaded', true);
                // Initialize pagination after content loaded
                initPbmentahTables(subTab);
            },
            error: function() { 
                $(containerId).html('<div class="alert alert-danger">Gagal memuat data</div>'); 
            }
        });
    }

    function loadPbsl(subTab) {
        var containerId = '', url = '';
        if (subTab === 'idm') { 
            containerId = '#pbsl-idm-container'; 
            url = "/kemarin/pb-sl/idm"; 
        }
        else if (subTab === 'omi') { 
            containerId = '#pbsl-omi-container'; 
            url = "/kemarin/pb-sl/omi"; 
        }
        
        if ($(containerId).data('loaded')) return;
        
        $.ajax({
            url: url, 
            method: 'GET',
            success: function(response) { 
                $(containerId).html(response.html); 
                $(containerId).data('loaded', true);
                // Initialize pagination after content loaded
                initPbslTables(subTab);
            },
            error: function() { 
                $(containerId).html('<div class="alert alert-danger">Gagal memuat data</div>'); 
            }
        });
    }

    function loadRupiah(subTab) {
        var containerId = '', url = '', type = '';
        if (subTab === 'all') { 
            containerId = '#rupiah-all-container'; 
            url = "/kemarin/rupiah-picking/all";
            type = 'all';
        } else if (subTab === 'idm') { 
            containerId = '#rupiah-idm-container'; 
            url = "/kemarin/rupiah-picking/idm";
            type = 'idm';
        } else if (subTab === 'omi') { 
            containerId = '#rupiah-omi-container'; 
            url = "/kemarin/rupiah-picking/omi";
            type = 'omi';
        }
        
        if ($(containerId).data('loaded')) return;
        
        $.ajax({
            url: url, 
            method: 'GET',
            success: function(response) { 
                $(containerId).html(response.html); 
                $(containerId).data('loaded', true);
            },
            error: function() { 
                $(containerId).html('<div class="alert alert-danger">Gagal memuat data</div>'); 
            }
        });
    }
    
    function loadPicking() {
        if ($('#picking-container').data('loaded')) return;
        $.ajax({
            url: "/kemarin/monitor-picking", method: 'GET',
            success: function(response) { $('#picking-container').html(response.html).data('loaded', true); },
            error: function() { $('#picking-container').html('<div class="alert alert-danger">Gagal memuat data</div>'); }
        });
    }
    
    function loadLoading() {
        if ($('#loading-container').data('loaded')) return;
        $.ajax({
            url: "/kemarin/monitor-loading", method: 'GET',
            success: function(response) { $('#loading-container').html(response.html).data('loaded', true); },
            error: function() { $('#loading-container').html('<div class="alert alert-danger">Gagal memuat data</div>'); }
        });
    }
    
    // ==================== EVENT HANDLERS ====================
    
    // Tombol Refresh
    $('.refresh-btn').on('click', function() {
        var tab = $(this).data('tab');
        if (tab === 'sl') refreshSL();
        else if (tab === 'pbmentah') refreshPbmentah();
        else if (tab === 'pbsl') refreshPbsl();
        else if (tab === 'rupiah') refreshRupiah();
        else if (tab === 'picking') refreshPicking();
        else if (tab === 'loading') refreshLoading();
    });
    
    // Main tab change
    $('#mainTabs a').on('shown.bs.tab', function(e) {
        var target = $(e.target).attr('href');
        if (target === '#pbmentah') {
            var activeSubTab = $('#pbmentahSubTabs .nav-link.active').attr('href');
            if (activeSubTab === '#pbmentah-idm') loadPbmentah('idm');
            else if (activeSubTab === '#pbmentah-omi') loadPbmentah('omi');
        } else if (target === '#pbsl') {
            var activeSubTab = $('#pbslSubTabs .nav-link.active').attr('href');
            if (activeSubTab === '#pbsl-idm') loadPbsl('idm');
            else if (activeSubTab === '#pbsl-omi') loadPbsl('omi');
        } else if (target === '#rupiah') {
            var activeSubTab = $('#rupiahSubTabs .nav-link.active').attr('href');
            if (activeSubTab === '#rupiah-all') loadRupiah('all');
            else if (activeSubTab === '#rupiah-idm') loadRupiah('idm');
            else if (activeSubTab === '#rupiah-omi') loadRupiah('omi');
        } else if (target === '#picking') {
            loadPicking();
        } else if (target === '#loading') {
            loadLoading();
        }
    });
    
    // Sub tab change PBMentah
    $('#pbmentahSubTabs a').on('shown.bs.tab', function(e) {
        var target = $(e.target).attr('href');
        if (target === '#pbmentah-idm') loadPbmentah('idm');
        else if (target === '#pbmentah-omi') loadPbmentah('omi');
    });

    // Sub tab change PBSL
    $('#pbslSubTabs a').on('shown.bs.tab', function(e) {
        var target = $(e.target).attr('href');
        if (target === '#pbsl-idm') loadPbsl('idm');
        else if (target === '#pbsl-omi') loadPbsl('omi');
    });
    
    // Sub tab change Rupiah
    $('#rupiahSubTabs a').on('shown.bs.tab', function(e) {
        var target = $(e.target).attr('href');
        if (target === '#rupiah-all') loadRupiah('all');
        else if (target === '#rupiah-idm') loadRupiah('idm');
        else if (target === '#rupiah-omi') loadRupiah('omi');
    });
    
    // Load data pertama kali
    loadRupiah('all');
    
    // Auto refresh stats setiap 30 detik
    function refreshStats() {
        $.ajax({
            url: "/kemarin/refresh-stats", method: 'GET',
            success: function(data) {
                $('#stat-pbidm').text(data.pbidm);
                $('#stat-pbomi').text(data.pbomi);
                $('#stat-wt').text(data.wt);
                $('#stat-sph').text(data.sph);
            }
        });
    }
    setInterval(refreshStats, 30000);
    
    // Add animation styles
    $('<style>').text(`
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
    `).appendTo('head');
});

// ==================== COPY FUNCTION dengan FALLBACK ====================
function copyRowsData() {
    var $activeTable = $('#sl .tab-pane.active table');
    
    if ($activeTable.length === 0) {
        alert('Tabel tidak ditemukan');
        return;
    }
    
    var copyText = '';
    copyText += 'Tanggal\tRPH Order\tRPH Realisasi\tSL %\n';
    
    $activeTable.find('tbody tr:visible').each(function() {
        var $row = $(this);
        var $cols = $row.find('td');
        
        if ($cols.length >= 4) {
            var tanggal = $cols.eq(0).text().trim();
            var rphOrder = $cols.eq(1).text().trim().replace(/\./g, '');
            var rphRealisasi = $cols.eq(2).text().trim().replace(/\./g, '');
            var slPercent = $cols.eq(3).text().trim().replace('%', '');
            
            copyText += tanggal + '\t' + rphOrder + '\t' + rphRealisasi + '\t' + slPercent + '\n';
        }
    });
    
    if (copyText === 'Tanggal\tRPH Order\tRPH Realisasi\tSL %\n') {
        alert('Tidak ada data untuk dicopy');
        return;
    }
    
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(copyText).then(function() {
            showCopySuccess();
        }).catch(function(err) {
            fallbackCopy(copyText);
        });
    } else {
        fallbackCopy(copyText);
    }
}

function fallbackCopy(text) {
    var textarea = document.createElement('textarea');
    textarea.value = text;
    textarea.style.position = 'fixed';
    textarea.style.top = '-9999px';
    textarea.style.left = '-9999px';
    document.body.appendChild(textarea);
    textarea.select();
    textarea.setSelectionRange(0, textarea.value.length);
    
    try {
        var success = document.execCommand('copy');
        if (success) {
            showCopySuccess();
        } else {
            alert('Gagal menyalin data. Silakan copy manual.');
        }
    } catch (err) {
        console.error('Fallback copy failed:', err);
        alert('Gagal menyalin data. Silakan copy manual.');
    }
    
    document.body.removeChild(textarea);
}

function showCopySuccess() {
    var $btn = $('.copy-rows-btn');
    var originalHtml = $btn.html();
    $btn.html('<i class="fas fa-check"></i> Tersalin!');
    $btn.removeClass('btn-outline-secondary').addClass('btn-success');
    setTimeout(function() {
        $btn.html(originalHtml);
        $btn.removeClass('btn-success').addClass('btn-outline-secondary');
    }, 2000);
}

$(document).on('click', '.copy-rows-btn', function() {
    copyRowsData();
});

// Fungsi copy untuk PB Mentah IDM
function copyPBMIDM(btnElement) {

    var $activeTable = $('#pbmi-table');
    var $btn = $(btnElement);
    var originalHtml = $btn.html();

    if ($activeTable.length === 0) {
        showToast('Tabel PB Mentah IDM tidak ditemukan!', 'error');
        return;
    }

    // =========================
    // DETECT SEARCH
    // =========================

    var searchValue = $('#pbmiSearch').val()
        ? $('#pbmiSearch').val().trim()
        : '';

    var isSearching = searchValue !== '';

    var copyText = '';

    // =========================
    // HEADER
    // =========================

    $activeTable.find('thead th').each(function() {
        copyText += $(this).text().trim() + '\t';
    });

    copyText = copyText.slice(0, -1) + '\n';

    // =========================
    // ROWS
    // =========================

    if (isSearching) {

        // HANYA HASIL SEARCH (VISIBLE)

        $activeTable.find('tbody tr:visible').each(function() {

            $(this).find('td').each(function() {
                copyText += $(this).text().trim() + '\t';
            });

            copyText = copyText.slice(0, -1) + '\n';
        });

    } else {

        // SEMUA ROWS

        $activeTable.find('tbody tr').each(function() {

            $(this).find('td').each(function() {
                copyText += $(this).text().trim() + '\t';
            });

            copyText = copyText.slice(0, -1) + '\n';
        });
    }

    // =========================
    // VALIDASI DATA
    // =========================

    if (
        copyText.trim() === '' ||
        copyText === '\n'
    ) {
        showToast('Tidak ada data untuk dicopy', 'error');
        return;
    }

    // =========================
    // COPY
    // =========================

    var textarea = document.createElement('textarea');

    textarea.value = copyText;

    textarea.style.position = 'fixed';
    textarea.style.top = '-9999px';
    textarea.style.left = '-9999px';

    document.body.appendChild(textarea);

    textarea.select();
    textarea.setSelectionRange(0, textarea.value.length);

    try {

        var success = document.execCommand('copy');

        if (success) {

            $btn.html('<i class="fas fa-check"></i> Tersalin!');
            $btn.removeClass('btn-primary').addClass('btn-success');

            setTimeout(function() {

                $btn.html(originalHtml);
                $btn.removeClass('btn-success').addClass('btn-primary');

            }, 2000);

            showToast('PB Mentah IDM berhasil disalin!', 'success');

        } else {

            showToast('Gagal menyalin data PB Mentah IDM', 'error');
        }

    } catch (err) {

        console.error('Fallback copy failed:', err);

        showToast('Gagal menyalin data PB Mentah IDM', 'error');
    }

    document.body.removeChild(textarea);
}

// Fungsi copy untuk PB Mentah IDM
function copyPBMOMI(btnElement) {

    var $activeTable = $('#pbmo-table');
    var $btn = $(btnElement);
    var originalHtml = $btn.html();

    if ($activeTable.length === 0) {
        showToast('Tabel PB Mentah OMI tidak ditemukan!', 'error');
        return;
    }

    // =========================
    // DETECT SEARCH
    // =========================

    var searchValue = $('#pbmoSearch').val()
        ? $('#pbmoSearch').val().trim()
        : '';

    var isSearching = searchValue !== '';

    var copyText = '';

    // =========================
    // HEADER
    // =========================

    $activeTable.find('thead th').each(function() {
        copyText += $(this).text().trim() + '\t';
    });

    copyText = copyText.slice(0, -1) + '\n';

    // =========================
    // ROWS
    // =========================

    if (isSearching) {

        // HANYA HASIL SEARCH (VISIBLE)

        $activeTable.find('tbody tr:visible').each(function() {

            $(this).find('td').each(function() {
                copyText += $(this).text().trim() + '\t';
            });

            copyText = copyText.slice(0, -1) + '\n';
        });

    } else {

        // SEMUA ROWS

        $activeTable.find('tbody tr').each(function() {

            $(this).find('td').each(function() {
                copyText += $(this).text().trim() + '\t';
            });

            copyText = copyText.slice(0, -1) + '\n';
        });
    }

    // =========================
    // VALIDASI DATA
    // =========================

    if (
        copyText.trim() === '' ||
        copyText === '\n'
    ) {
        showToast('Tidak ada data untuk dicopy', 'error');
        return;
    }

    // =========================
    // COPY
    // =========================

    var textarea = document.createElement('textarea');

    textarea.value = copyText;

    textarea.style.position = 'fixed';
    textarea.style.top = '-9999px';
    textarea.style.left = '-9999px';

    document.body.appendChild(textarea);

    textarea.select();
    textarea.setSelectionRange(0, textarea.value.length);

    try {

        var success = document.execCommand('copy');

        if (success) {

            $btn.html('<i class="fas fa-check"></i> Tersalin!');
            $btn.removeClass('btn-primary').addClass('btn-success');

            setTimeout(function() {

                $btn.html(originalHtml);
                $btn.removeClass('btn-success').addClass('btn-primary');

            }, 2000);

            showToast('PB Mentah OMI berhasil disalin!', 'success');

        } else {

            showToast('Gagal menyalin data PB Mentah OMI', 'error');
        }

    } catch (err) {

        console.error('Fallback copy failed:', err);

        showToast('Gagal menyalin data PB Mentah IDM', 'error');
    }

    document.body.removeChild(textarea);
}

// Fungsi copy untuk PB IDM
function copyPBIDM(btnElement) {

    var $activeTable = $('#pbi-table');
    var $btn = $(btnElement);
    var originalHtml = $btn.html();

    if ($activeTable.length === 0) {
        showToast('Tabel PB IDM tidak ditemukan!', 'error');
        return;
    }

    // =========================
    // DETECT SEARCH
    // =========================

    var searchValue = $('#pbiSearch').val()
        ? $('#pbiSearch').val().trim()
        : '';

    var isSearching = searchValue !== '';

    var copyText = '';

    // =========================
    // HEADER
    // =========================

    $activeTable.find('thead th').each(function() {
        copyText += $(this).text().trim() + '\t';
    });

    copyText = copyText.slice(0, -1) + '\n';

    // =========================
    // ROWS
    // =========================

    if (isSearching) {

        // HANYA HASIL SEARCH

        $activeTable.find('tbody tr:visible').each(function() {

            $(this).find('td').each(function() {
                copyText += $(this).text().trim() + '\t';
            });

            copyText = copyText.slice(0, -1) + '\n';
        });

    } else {

        // SEMUA ROWS

        $activeTable.find('tbody tr').each(function() {

            $(this).find('td').each(function() {
                copyText += $(this).text().trim() + '\t';
            });

            copyText = copyText.slice(0, -1) + '\n';
        });
    }

    // =========================
    // VALIDASI
    // =========================

    if (
        copyText.trim() === '' ||
        copyText === '\n'
    ) {
        showToast('Tidak ada data untuk dicopy', 'error');
        return;
    }

    // =========================
    // COPY
    // =========================

    var textarea = document.createElement('textarea');

    textarea.value = copyText;

    textarea.style.position = 'fixed';
    textarea.style.top = '-9999px';
    textarea.style.left = '-9999px';

    document.body.appendChild(textarea);

    textarea.select();
    textarea.setSelectionRange(0, textarea.value.length);

    try {

        var success = document.execCommand('copy');

        if (success) {

            $btn.html('<i class="fas fa-check"></i> Tersalin!');
            $btn.removeClass('btn-primary').addClass('btn-success');

            setTimeout(function() {

                $btn.html(originalHtml);
                $btn.removeClass('btn-success').addClass('btn-primary');

            }, 2000);

            showToast('PB IDM berhasil disalin!', 'success');

        } else {

            showToast('Gagal menyalin data PB IDM', 'error');
        }

    } catch (err) {

        console.error('Fallback copy failed:', err);

        showToast('Gagal menyalin data PB IDM', 'error');
    }

    document.body.removeChild(textarea);
}

/// Fungsi copy untuk SL IDM
function copySLIDM(btnElement) {

    var $activeTable = $('#sli-table');
    var $btn = $(btnElement);
    var originalHtml = $btn.html();

    if ($activeTable.length === 0) {
        showToast('Tabel SL IDM tidak ditemukan!', 'error');
        return;
    }

    var searchValue = $('#sliSearch').val()
        ? $('#sliSearch').val().trim()
        : '';

    var isSearching = searchValue !== '';

    var copyText = '';

    // HEADER
    $activeTable.find('thead th').each(function() {
        copyText += $(this).text().trim() + '\t';
    });

    copyText = copyText.slice(0, -1) + '\n';

    // ROWS
    if (isSearching) {

        $activeTable.find('tbody tr:visible').each(function() {

            $(this).find('td').each(function() {
                copyText += $(this).text().trim() + '\t';
            });

            copyText = copyText.slice(0, -1) + '\n';
        });

    } else {

        $activeTable.find('tbody tr').each(function() {

            $(this).find('td').each(function() {
                copyText += $(this).text().trim() + '\t';
            });

            copyText = copyText.slice(0, -1) + '\n';
        });
    }

    if (copyText.trim() === '') {
        showToast('Tidak ada data untuk dicopy', 'error');
        return;
    }

    var textarea = document.createElement('textarea');

    textarea.value = copyText;
    textarea.style.position = 'fixed';
    textarea.style.top = '-9999px';
    textarea.style.left = '-9999px';

    document.body.appendChild(textarea);

    textarea.select();
    textarea.setSelectionRange(0, textarea.value.length);

    try {

        var success = document.execCommand('copy');

        if (success) {

            $btn.html('<i class="fas fa-check"></i> Tersalin!');
            $btn.removeClass('btn-primary').addClass('btn-success');

            setTimeout(function() {

                $btn.html(originalHtml);
                $btn.removeClass('btn-success').addClass('btn-primary');

            }, 2000);

            showToast('SL IDM berhasil disalin!', 'success');

        } else {

            showToast('Gagal menyalin data SL IDM', 'error');
        }

    } catch (err) {

        console.error('Fallback copy failed:', err);

        showToast('Gagal menyalin data SL IDM', 'error');
    }

    document.body.removeChild(textarea);
}


// Fungsi copy untuk PB OMI
function copyPBOMI(btnElement) {

    var $activeTable = $('#pbo-table');
    var $btn = $(btnElement);
    var originalHtml = $btn.html();

    if ($activeTable.length === 0) {
        showToast('Tabel PB OMI tidak ditemukan!', 'error');
        return;
    }

    var searchValue = $('#pboSearch').val()
        ? $('#pboSearch').val().trim()
        : '';

    var isSearching = searchValue !== '';

    var copyText = '';

    // HEADER
    $activeTable.find('thead th').each(function() {
        copyText += $(this).text().trim() + '\t';
    });

    copyText = copyText.slice(0, -1) + '\n';

    // ROWS
    if (isSearching) {

        $activeTable.find('tbody tr:visible').each(function() {

            $(this).find('td').each(function() {
                copyText += $(this).text().trim() + '\t';
            });

            copyText = copyText.slice(0, -1) + '\n';
        });

    } else {

        $activeTable.find('tbody tr').each(function() {

            $(this).find('td').each(function() {
                copyText += $(this).text().trim() + '\t';
            });

            copyText = copyText.slice(0, -1) + '\n';
        });
    }

    if (copyText.trim() === '') {
        showToast('Tidak ada data untuk dicopy', 'error');
        return;
    }

    var textarea = document.createElement('textarea');

    textarea.value = copyText;
    textarea.style.position = 'fixed';
    textarea.style.top = '-9999px';
    textarea.style.left = '-9999px';

    document.body.appendChild(textarea);

    textarea.select();
    textarea.setSelectionRange(0, textarea.value.length);

    try {

        var success = document.execCommand('copy');

        if (success) {

            $btn.html('<i class="fas fa-check"></i> Tersalin!');
            $btn.removeClass('btn-primary').addClass('btn-success');

            setTimeout(function() {

                $btn.html(originalHtml);
                $btn.removeClass('btn-success').addClass('btn-primary');

            }, 2000);

            showToast('PB OMI berhasil disalin!', 'success');

        } else {

            showToast('Gagal menyalin data PB OMI', 'error');
        }

    } catch (err) {

        console.error('Fallback copy failed:', err);

        showToast('Gagal menyalin data PB OMI', 'error');
    }

    document.body.removeChild(textarea);
}


// Fungsi copy untuk SL OMI
function copySLOMI(btnElement) {

    var $activeTable = $('#slo-table');
    var $btn = $(btnElement);
    var originalHtml = $btn.html();

    if ($activeTable.length === 0) {
        showToast('Tabel SL OMI tidak ditemukan!', 'error');
        return;
    }

    var searchValue = $('#sloSearch').val()
        ? $('#sloSearch').val().trim()
        : '';

    var isSearching = searchValue !== '';

    var copyText = '';

    // HEADER
    $activeTable.find('thead th').each(function() {
        copyText += $(this).text().trim() + '\t';
    });

    copyText = copyText.slice(0, -1) + '\n';

    // ROWS
    if (isSearching) {

        $activeTable.find('tbody tr:visible').each(function() {

            $(this).find('td').each(function() {
                copyText += $(this).text().trim() + '\t';
            });

            copyText = copyText.slice(0, -1) + '\n';
        });

    } else {

        $activeTable.find('tbody tr').each(function() {

            $(this).find('td').each(function() {
                copyText += $(this).text().trim() + '\t';
            });

            copyText = copyText.slice(0, -1) + '\n';
        });
    }

    if (copyText.trim() === '') {
        showToast('Tidak ada data untuk dicopy', 'error');
        return;
    }

    var textarea = document.createElement('textarea');

    textarea.value = copyText;
    textarea.style.position = 'fixed';
    textarea.style.top = '-9999px';
    textarea.style.left = '-9999px';

    document.body.appendChild(textarea);

    textarea.select();
    textarea.setSelectionRange(0, textarea.value.length);

    try {

        var success = document.execCommand('copy');

        if (success) {

            $btn.html('<i class="fas fa-check"></i> Tersalin!');
            $btn.removeClass('btn-primary').addClass('btn-success');

            setTimeout(function() {

                $btn.html(originalHtml);
                $btn.removeClass('btn-success').addClass('btn-primary');

            }, 2000);

            showToast('SL OMI berhasil disalin!', 'success');

        } else {

            showToast('Gagal menyalin data SL OMI', 'error');
        }

    } catch (err) {

        console.error('Fallback copy failed:', err);

        showToast('Gagal menyalin data SL OMI', 'error');
    }

    document.body.removeChild(textarea);
}

// Fungsi copy untuk Rupiah Picking
function copyRupiahTable(type) {
    var tableId = 'rupiah-table-' + type;
    var $activeTable = $('#' + tableId);
    var $btn = $('.copy-rupiah-btn');
    
    if ($activeTable.length === 0) {
        showToast('Tabel Rupiah Picking ' + type.toUpperCase() + ' tidak ditemukan!', 'error');
        return;
    }
    
    var copyText = '';
    
    // Header
    $activeTable.find('thead th').each(function() {
        copyText += $(this).text().trim() + '\t';
    });
    copyText = copyText.slice(0, -1) + '\n';
    
    // Data rows (hanya yang visible dari tbody)
    $activeTable.find('tbody tr.data-row:visible').each(function() {
        $(this).find('td').each(function() {
            var text = $(this).text().trim();
            copyText += text + '\t';
        });
        copyText = copyText.slice(0, -1) + '\n';
    });
    
    // Jika tidak ada data rows, coba ambil semua tr dari tbody
    if ($activeTable.find('tbody tr.data-row:visible').length === 0) {
        $activeTable.find('tbody tr:visible').each(function() {
            $(this).find('td').each(function() {
                copyText += $(this).text().trim() + '\t';
            });
            copyText = copyText.slice(0, -1) + '\n';
        });
    }
    
    
    // Ambil data dari tfoot (baris TOTAL)
    $activeTable.find('tfoot tr:visible').each(function() {
        var $cells = $(this).find('th, td');
        var cellIndex = 0;
        
        $cells.each(function() {
            var text = $(this).text().trim();
            
            if (cellIndex === 0) {
                // Tulis TOTAL terlebih dahulu
                copyText += text;
                // Kemudian tambahkan 2 tab untuk cell kosong
                copyText += '\t\t';
            } else {
                copyText += text;
                // Tambahkan tab setelah setiap cell kecuali cell terakhir
                if (cellIndex < $cells.length - 1) {
                    copyText += '\t';
                }
            }
            
            cellIndex++;
        });
        copyText += '\n';
    });
    
    if (copyText === $activeTable.find('thead th').text().trim() + '\n') {
        showToast('Tidak ada data untuk dicopy', 'error');
        return;
    }
    
    // Fallback copy menggunakan textarea
    var textarea = document.createElement('textarea');
    textarea.value = copyText;
    textarea.style.position = 'fixed';
    textarea.style.top = '-9999px';
    textarea.style.left = '-9999px';
    document.body.appendChild(textarea);
    textarea.select();
    textarea.setSelectionRange(0, textarea.value.length);
    
    try {
        var success = document.execCommand('copy');
        if (success) {
            // Ubah tombol menjadi "Tersalin!"
            var originalHtml = $btn.html();
            $btn.html('<i class="fas fa-check"></i> Tersalin!');
            $btn.removeClass('btn-primary').addClass('btn-success');
            setTimeout(function() {
                $btn.html(originalHtml);
                $btn.removeClass('btn-success').addClass('btn-primary');
            }, 2000);
            showToast('Data Rupiah Picking ' + type.toUpperCase() + ' berhasil disalin!', 'success');
        } else {
            showToast('Gagal menyalin data Rupiah Picking', 'error');
        }
    } catch (err) {
        console.error('Fallback copy failed:', err);
        showToast('Gagal menyalin data Rupiah Picking', 'error');
    }
    
    document.body.removeChild(textarea);
}