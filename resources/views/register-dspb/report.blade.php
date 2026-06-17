<div class="page-card">

    <div class="report-header">

        <div class="page-title">
            Report Serah Terima DSPB
        </div>

        <div class="report-tools">

            {{-- FILTER TIPE OMI/IDM --}}
            <div class="pagination-limit">
                <label>Tipe</label>
                <select id="filterTipe" class="report-input" style="width:100px;">
                    <option value="">Semua</option>
                    <option value="IDM">IDM</option>
                    <option value="OMI">OMI</option>
                </select>
            </div>

            <div class="pagination-limit">
                <label>Show</label>
                <select id="entriesPerPage" class="report-input" style="width:70px;">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="-1">All</option>
                </select>
                <label>entries</label>
            </div>

            <input type="date"
                id="filterTanggal"
                class="report-input">

            <button type="button"
                    class="btn-export"
                    id="btnExport">

                <i class="fas fa-file-excel"></i>
                Export XLSX

            </button>

            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text"
                    id="searchReport"
                    placeholder="Cari kode toko / no SJ / nama checker">
            </div>

        </div>

        <div id="deleteLoading" style="display:none; margin-bottom:15px; background:#2a3942; color:#00d5a3; padding:12px; border-radius:12px;">
            <i class="fas fa-spinner fa-spin"></i> Menghapus data...
        </div>
        <div id="deleteSuccess" style="display:none; margin-bottom:15px; background:#00a884; color:white; padding:12px; border-radius:12px;">
            <i class="fas fa-check-circle"></i> Data berhasil dihapus
        </div>
        <div id="deleteError" style="display:none; margin-bottom:15px; background:#dc3545; color:white; padding:12px; border-radius:12px;">
            <i class="fas fa-times-circle"></i> Gagal menghapus data
        </div>

    </div>

    {{-- TABLE --}}
    <div class="table-wrapper">
        <table class="modern-table" id="reportTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tipe</th>
                    <th>Tanggal</th>
                    <th>Jam</th>
                    <th>Kode Toko</th>
                    <th>No SJ</th>
                    <th>Nama Checker</th>
                    <th>Total DSPB</th>
                    <th>Bronjong</th>
                    <th>Kontainer</th>
                    <th>Kardus</th>
                    <th>Susu</th>
                    <th>Rokok</th>
                    <th>Detail Rokok</th>
                    <th>Keterangan</th>
                    <th>Diubah Oleh</th>
                    <th width="120">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($register as $row)
                <tr data-id="{{ $row->id }}" data-tipe="{{ $row->tipe }}">
                    <td class="row-number">{{ $loop->iteration }}</td>
                    <td><span class="badge-tipe {{ strtolower($row->tipe) }}">{{ $row->tipe }}</span></td>
                    <td class="filter-date" data-date="{{ \Carbon\Carbon::parse($row->tanggal)->format('Y-m-d') }}">
                        {{ \Carbon\Carbon::parse($row->tanggal)->format('d/m/Y') }}
                    </td>
                    <td>{{ \Carbon\Carbon::parse($row->jam)->format('H:i') }}</td>
                    <td><span class="badge-toko">{{ $row->kode_toko }}</span></td>
                    <td><span class="badge-nrb">{{ $row->no_sj }}</span></td>
                    <td>{{ $row->nama_checker }}</td>
                    <td>{{ $row->total_dspb }}</td>
                    <td>{{ $row->bronjong }}</td>
                    <td>{{ $row->kontainer }}</td>
                    <td>{{ $row->kardus }}</td>
                    <td>{{ $row->susu }}</td>
                    <td>{{ $row->rokok }}</td>
                    <td>{{ $row->detail_rokok }}</td>
                    <td>{{ $row->keterangan }}</td>
                    <td>{{ $row->modify_by }}</td>
                    <td>
                        <div class="action-buttons">

                            <button type="button"
                                    class="btn-action btn-edit"
                                    data-id="{{ $row->id }}"
                                    data-tipe="{{ $row->tipe }}"
                                    data-kode_toko="{{ $row->kode_toko }}"
                                    data-no_sj="{{ $row->no_sj }}"
                                    data-nama_checker="{{ $row->nama_checker }}"
                                    data-total_dspb="{{ $row->total_dspb }}"
                                    data-bronjong="{{ $row->bronjong }}"
                                    data-kontainer="{{ $row->kontainer }}"
                                    data-kardus="{{ $row->kardus }}"
                                    data-susu="{{ $row->susu }}"
                                    data-rokok="{{ $row->rokok }}"
                                    data-detail_rokok="{{ $row->detail_rokok }}"
                                    data-keterangan="{{ $row->keterangan }}"
                                    data-modify_by="{{ $row->modify_by }}">
                                    

                                <i class="fas fa-edit"></i>

                            </button>

                            <button type="button"
                                    class="btn-action btn-delete"
                                    data-id="{{ $row->id }}"
                                    data-tipe="{{ $row->tipe }}">

                                <i class="fas fa-trash"></i>

                            </button>

                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- MODAL EDIT -->
        <div class="custom-modal" id="editModal">

            <div class="modal-content-custom">

                <div class="modal-header-custom">

                    <h3>Edit Data DSPB</h3>

                    <button type="button" class="modal-close" id="closeEditModal">
                        &times;
                    </button>

                </div>

                <form id="editForm">

                    @csrf

                    <input type="hidden" id="edit_id">

                    <div class="modal-grid">

                        <div class="form-group">
                            <label>Tipe</label>
                            <input type="text" id="edit_tipe" class="report-input" readonly>
                        </div>

                        <div class="form-group">
                            <label>Kode Toko</label>
                            <input type="text" id="edit_kode_toko" class="report-input" readonly>
                        </div>

                        <div class="form-group">
                            <label>No SJ</label>
                            <input type="text" id="edit_no_sj" class="report-input" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label>Nama Checker</label>
                            <input type="text" id="edit_nama_checker" class="report-input" readonly>
                        </div>

                        <div class="form-group">
                            <label>Total DSPB</label>
                            <input type="number" id="edit_total_dspb" class="report-input">
                        </div>

                        <div class="form-group">
                            <label>Bronjong</label>
                            <input type="number" id="edit_bronjong" class="report-input">
                        </div>

                        <div class="form-group">
                            <label>Kontainer</label>
                            <input type="number" id="edit_kontainer" class="report-input">
                        </div>

                        <div class="form-group">
                            <label>Kardus</label>
                            <input type="number" id="edit_kardus" class="report-input">
                        </div>

                        <div class="form-group">
                            <label>Susu</label>
                            <input type="number" id="edit_susu" class="report-input">
                        </div>

                        <div class="form-group">
                            <label>Rokok</label>
                            <input type="number" id="edit_rokok" class="report-input">
                        </div>

                    </div>

                    <div class="form-group" style="margin-top:15px;">
                        <label>Detail Rokok</label>
                        <textarea id="edit_detail_rokok" class="report-input" style="width:100%; min-height:80px;"></textarea>
                    </div>

                    <div class="form-group" style="margin-top:15px;">
                        <label>Keterangan</label>
                        <textarea id="edit_keterangan" class="report-input" style="width:100%; min-height:80px;"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Diubah Oleh</label>
                        <input type="text" id="edit_modify_by" class="report-input" placeholder="Nama user yang mengubah">
                    </div>

                    <div class="modal-footer-custom">

                        <button type="button" class="btn-pagination" id="cancelEdit">
                            Cancel
                        </button>

                        <button type="submit" class="btn-export">
                            <i class="fas fa-save"></i>
                            Simpan
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

    <div class="pagination-controls" style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
        <div class="pagination-info" style="color: #8696a0; font-size: 14px;"></div>
        <div class="pagination-buttons">
            <button id="prevPage" class="btn-pagination" disabled>&laquo; Prev</button>
            <span id="pageInfo" style="margin: 0 12px; color: white;"></span>
            <button id="nextPage" class="btn-pagination">Next &raquo;</button>
        </div>
    </div>

    <div class="custom-modal" id="deleteModal">
        <div class="modal-content-custom" style="max-width:450px; text-align:center;">

            <h3 style="color:white; margin-bottom:10px;">
                Konfirmasi Hapus
            </h3>

            <p style="color:#cbd5e1; margin-bottom:25px;">
                Data akan dihapus permanen. Lanjutkan?
            </p>

            <input type="hidden" id="delete_id">
            <input type="hidden" id="delete_tipe">

            <div style="display:flex; justify-content:center; gap:12px;">
                <button type="button" class="btn-pagination" id="cancelDelete">
                    Batal
                </button>

                <button type="button" class="btn-action btn-delete" id="confirmDeleteBtn">
                    Hapus
                </button>
            </div>

        </div>
    </div>
</div>

<style>
.page-card{
    background:#111b21;
    border-radius:24px;
    padding:24px;
    box-shadow:0 10px 30px rgba(0,0,0,.25);
    overflow:hidden;
}

/* HEADER */
.report-header{
    display:flex;
    flex-direction:column;
    gap:18px;
    margin-bottom:20px;
}

.page-title{
    font-size:24px;
    font-weight:700;
    color:#fff;
}

/* TOOLS */
.report-tools{
    display:flex;
    flex-wrap:wrap;
    gap:12px;
    align-items:center;
}

.pagination-limit{
    display:flex;
    align-items:center;
    gap:8px;
    color:#cbd5e1;
    font-size:13px;
}

.report-input{
    background:#1f2c34;
    border:1px solid #2f3e46;
    color:white;
    border-radius:12px;
    padding:10px 14px;
    outline:none;
    transition:.2s;
}

.report-input:focus{
    border-color:#00a884;
    box-shadow:0 0 0 3px rgba(0,168,132,.15);
}

/* SEARCH */
.search-box{
    position:relative;
    min-width:260px;
    flex:1;
}

.search-box i{
    position:absolute;
    left:14px;
    top:50%;
    transform:translateY(-50%);
    color:#8696a0;
    font-size:14px;
}

.search-box input{
    width:100%;
    background:#1f2c34;
    border:1px solid #2f3e46;
    color:white;
    border-radius:14px;
    padding:11px 14px 11px 40px;
    outline:none;
}

.search-box input:focus{
    border-color:#00a884;
}

#filterTanggal::-webkit-calendar-picker-indicator {
    filter: invert(1);
    cursor: pointer;
}
/* BUTTON */
.btn-export{
    background:linear-gradient(135deg,#00a884,#00c896);
    border:none;
    color:white;
    padding:10px 18px;
    border-radius:14px;
    cursor:pointer;
    font-weight:600;
    display:flex;
    align-items:center;
    gap:8px;
    transition:.2s;
}

.btn-export:hover{
    transform:translateY(-1px);
    opacity:.95;
}

.btn-pagination{
    background:#1f2c34;
    border:1px solid #2f3e46;
    color:white;
    padding:9px 16px;
    border-radius:12px;
    cursor:pointer;
    transition:.2s;
}

.btn-pagination:hover:not(:disabled){
    background:#00a884;
    border-color:#00a884;
}

.btn-pagination:disabled{
    opacity:.4;
    cursor:not-allowed;
}

/* TABLE */
.table-wrapper{
    overflow-x:auto;
    border-radius:18px;
    border:1px solid #24313a;
}

.modern-table{
    width:100%;
    border-collapse:collapse;
    min-width:1400px;
    background:#111b21;
}

.modern-table thead{
    background:#1f2c34;
}

.modern-table thead th{
    color:#e2e8f0;
    font-size:13px;
    font-weight:600;
    padding:16px 14px;
    text-align:left;
    border-bottom:1px solid #2f3e46;
    white-space:nowrap;
}

.modern-table tbody td{
    padding:14px;
    color:#d1d5db;
    border-bottom:1px solid #1f2c34;
    font-size:13px;
    vertical-align:middle;
}

.modern-table tbody tr{
    transition:.2s;
}

.modern-table tbody tr:hover{
    background:#1a252d;
}

/* BADGE */
.badge-tipe{
    padding:5px 12px;
    border-radius:999px;
    font-size:11px;
    font-weight:700;
    color:white;
    display:inline-block;
}

.badge-tipe.idm{
    background:#2563eb;
}

.badge-tipe.omi{
    background:#16a34a;
}

.badge-toko{
    background:#1e293b;
    color:#38bdf8;
    padding:5px 10px;
    border-radius:999px;
    font-size:12px;
    font-weight:600;
}

.badge-nrb{
    background:#1e293b;
    color:#22c55e;
    padding:5px 10px;
    border-radius:999px;
    font-size:12px;
    font-weight:600;
}

.action-buttons{
    display:flex;
    gap:8px;
}

.btn-action{
    width:36px;
    height:36px;
    border:none;
    border-radius:10px;
    cursor:pointer;
    color:white;
    display:flex;
    align-items:center;
    justify-content:center;
    transition:.2s;
}

.btn-edit{
    background:#2563eb;
}

.btn-edit:hover{
    background:#1d4ed8;
    transform:translateY(-1px);
}

.btn-delete{
    background:#dc2626;
}

.btn-delete:hover{
    background:#b91c1c;
    transform:translateY(-1px);
}

/* PAGINATION */
.pagination-controls{
    margin-top:20px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
    gap:12px;
}

.pagination-info{
    color:#94a3b8;
    font-size:13px;
}

.custom-modal {
    display: flex;              /* pastikan display flex */
    align-items: center;
    justify-content: center;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.65);
    opacity: 0;
    pointer-events: none;
    transition: .2s ease;
    z-index: 9999;
    padding: 20px;
}

.modal-content-custom{
    width:100%;
    max-width:1000px;
    background:#111b21;
    border-radius:24px;
    padding:24px;
    max-height:90vh;
    overflow-y:auto;
}

.modal-header-custom{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

.modal-header-custom h3{
    color:white;
    margin:0;
}

.modal-close{
    background:none;
    border:none;
    color:white;
    font-size:32px;
    cursor:pointer;
}

.modal-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:15px;
}

.form-group{
    display:flex;
    flex-direction:column;
    gap:8px;
}

.form-group label{
    color:#cbd5e1;
    font-size:13px;
}

.modal-footer-custom{
    margin-top:25px;
    display:flex;
    justify-content:flex-end;
    gap:12px;
}

.custom-modal.show {
    opacity: 1;
    pointer-events: auto;
}
/* Pastikan SweetAlert tampil di atas modal (z-index modal 9999) */
.swal2-container {
    z-index: 99999 !important;
}
/* MOBILE */
@media(max-width:768px){

    .report-tools{
        flex-direction:column;
        align-items:stretch;
    }

    .search-box{
        width:100%;
    }

    .btn-export{
        justify-content:center;
    }

    .pagination-controls{
        flex-direction:column;
    }

}
</style>

<script src="{{ asset('js/sweetalert.js') }}"></script>

<script>
(function () {
    let currentPage = 1;
    let rowsPerPage = 25;
    let allRows = [];
    let filteredRows = [];

    function initReport() {
        currentPage = 1;
        let val = $('#entriesPerPage').val();
        rowsPerPage = (val === '-1') ? 999999 : (parseInt(val) || 25);
        allRows = [];
        $('#reportTable tbody tr').each(function () { allRows.push($(this)); });
        applyFilterAndPaginate();

        $('#entriesPerPage').off('change').on('change', function () {
            let v = $(this).val();
            rowsPerPage = (v === '-1') ? filteredRows.length : parseInt(v);
            currentPage = 1;
            renderPage();
        });

        $('#prevPage').off('click').on('click', function () {
            if (currentPage > 1) { currentPage--; renderPage(); }
        });

        $('#nextPage').off('click').on('click', function () {
            let totalPages = Math.ceil(filteredRows.length / rowsPerPage);
            if (currentPage < totalPages) { currentPage++; renderPage(); }
        });

        $('#filterTanggal, #filterTipe, #searchReport').off('change keyup').on('change keyup', function () {
            applyFilterAndPaginate();
        });
    }

    function getFilteredRows() {
        let searchValue = $('#searchReport').val().toLowerCase();
        let tanggalValue = $('#filterTanggal').val();
        let tipeValue = $('#filterTipe').val();
        return allRows.filter(row => {
            let text = row.text().toLowerCase();
            let matchesSearch = searchValue === '' || text.includes(searchValue);
            let rowTanggal = row.find('.filter-date').data('date');
            let matchesDate = !tanggalValue || rowTanggal === tanggalValue;
            let rowTipe = row.data('tipe');
            let matchesTipe = !tipeValue || rowTipe === tipeValue;
            return matchesSearch && matchesDate && matchesTipe;
        });
    }

    function renderPage() {
        let start = (currentPage - 1) * rowsPerPage;
        let end = start + rowsPerPage;
        let rowsToShow = filteredRows.slice(start, end);
        $('#reportTable tbody tr').hide();
        rowsToShow.forEach(row => row.show());
        let totalPages = Math.ceil(filteredRows.length / rowsPerPage);
        let from = filteredRows.length === 0 ? 0 : start + 1;
        let to = Math.min(end, filteredRows.length);
        $('.pagination-info').text(`Showing ${from} to ${to} of ${filteredRows.length} entries`);
        $('#pageInfo').text(`Page ${currentPage} of ${totalPages || 1}`);
        $('#prevPage').prop('disabled', currentPage === 1);
        $('#nextPage').prop('disabled', currentPage >= totalPages);
    }

    function applyFilterAndPaginate() {
        filteredRows = getFilteredRows();
        filteredRows.forEach((row, idx) => { row.find('.row-number').text(idx + 1); });
        currentPage = 1;
        renderPage();
    }

    // ==================== EXPORT XLSX FILTER ====================
    $('#btnExport').off('click').on('click', function () {
        let tanggal = $('#filterTanggal').val();
        let search  = $('#searchReport').val();
        let tipe    = $('#filterTipe').val();

        let params = new URLSearchParams();

        if (tanggal !== '') {
            params.append('tanggal', tanggal);
        }

        if (search !== '') {
            params.append('search', search);
        }

        if (tipe !== '') {
            params.append('tipe', tipe);
        }

        let exportUrl = "{{ route('register-dspb.export') }}";

        if (params.toString() !== '') {
            exportUrl += '?' + params.toString();
        }

        window.location.href = exportUrl;
    });

    // =========================
    // MODAL EDIT (dengan uppercase pada nama_checker, keterangan)
    // =========================
    $(document).on('click', '.btn-edit', function () {
        $('#edit_id').val($(this).data('id'));
        $('#edit_tipe').val($(this).data('tipe'));
        $('#edit_kode_toko').val($(this).data('kode_toko'));
        $('#edit_no_sj').val($(this).data('no_sj'));
        
        let namaChecker = ($(this).data('nama_checker') || '').toUpperCase();
        $('#edit_nama_checker').val(namaChecker);
        
        $('#edit_total_dspb').val($(this).data('total_dspb'));
        $('#edit_bronjong').val($(this).data('bronjong'));
        $('#edit_kontainer').val($(this).data('kontainer'));
        $('#edit_kardus').val($(this).data('kardus'));
        $('#edit_susu').val($(this).data('susu'));
        $('#edit_rokok').val($(this).data('rokok'));
        $('#edit_detail_rokok').val($(this).data('detail_rokok'));
        
        let keterangan = ($(this).data('keterangan') || '').toUpperCase();
        $('#edit_keterangan').val(keterangan);
        
        $('#edit_modify_by').val('');
        
        $('#editModal').addClass('show');
    });

    // =========================
    // CLOSE MODAL EDIT (TAMBAHAN)
    // =========================
    $('#closeEditModal, #cancelEdit').on('click', function () {
        $('#editModal').removeClass('show');
    });

    $('#editModal').on('click', function (e) {
        if (e.target.id === 'editModal') {
            $('#editModal').removeClass('show');
        }
    });

    $(document).on('keydown', function (e) {
        if (e.key === 'Escape' && $('#editModal').hasClass('show')) {
            $('#editModal').removeClass('show');
        }
    });

    // =========================
    // UPDATE DATA (dengan validasi modify_by)
    // =========================
    $('#editForm').on('submit', function (e) {
        e.preventDefault();

        let modifyBy = $('#edit_modify_by').val().trim();
        if (modifyBy === '') {
            Swal.fire({
                icon: 'warning',
                title: 'Field Wajib Diisi',
                text: 'Kolom "Diubah Oleh" harus diisi sebelum menyimpan perubahan.',
                background: '#111b21',
                color: '#fff',
                confirmButtonColor: '#00a884'
            });
            return;
        }

        let id = $('#edit_id').val();
        $.ajax({
            url: "{{ url('register-dspb/update') }}/" + id,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                tipe: $('#edit_tipe').val(),
                kode_toko: $('#edit_kode_toko').val(),
                no_sj: $('#edit_no_sj').val(),
                nama_checker: $('#edit_nama_checker').val(),
                total_dspb: $('#edit_total_dspb').val(),
                bronjong: $('#edit_bronjong').val(),
                kontainer: $('#edit_kontainer').val(),
                kardus: $('#edit_kardus').val(),
                susu: $('#edit_susu').val(),
                rokok: $('#edit_rokok').val(),
                detail_rokok: $('#edit_detail_rokok').val(),
                keterangan: $('#edit_keterangan').val(),
                modify_by: modifyBy
            },
            beforeSend: function () {
                Swal.fire({ title: 'Menyimpan...', text: 'Sedang update data', allowOutsideClick: false, background: '#111b21', color: '#fff', didOpen: () => { Swal.showLoading(); } });
            },
            success: function () {
                const id = $('#edit_id').val();
                const row = $('tr[data-id="' + id + '"]');
                row.find('td:nth-child(8)').text($('#edit_total_dspb').val());
                row.find('td:nth-child(9)').text($('#edit_bronjong').val());
                row.find('td:nth-child(10)').text($('#edit_kontainer').val());
                row.find('td:nth-child(11)').text($('#edit_kardus').val());
                row.find('td:nth-child(12)').text($('#edit_susu').val());
                row.find('td:nth-child(13)').text($('#edit_rokok').val());
                row.find('td:nth-child(14)').text($('#edit_detail_rokok').val());
                row.find('td:nth-child(15)').text($('#edit_keterangan').val());
                row.find('td:nth-child(16)').text(modifyBy);
                $('#editModal').removeClass('show');
                Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Data berhasil diupdate', timer: 1800, showConfirmButton: false, background: '#111b21', color: '#fff' });
                applyFilterAndPaginate();
            },
            error: function (xhr) {
                console.log(xhr.responseText);
                Swal.fire({ icon: 'error', title: 'Gagal', text: 'Gagal update data', background: '#111b21', color: '#fff' });
            }
        });
    });

    // =========================
    // DELETE DATA
    // =========================
    $(document).on('click', '.btn-delete', function () {
        let id = $(this).data('id');
        let tipe = $(this).data('tipe');
        Swal.fire({
            title: 'Hapus data?',
            text: 'Data yang dihapus tidak dapat dikembalikan',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6b7280',
            background: '#111b21',
            color: '#fff'
        }).then((result) => {
            if (!result.isConfirmed) return;
            $.ajax({
                url: "{{ url('register-dspb/delete') }}/" + id,
                type: 'POST',
                data: { _token: '{{ csrf_token() }}', _method: 'DELETE', tipe: tipe },
                beforeSend: function () {
                    Swal.fire({ title: 'Deleting...', text: 'Sedang menghapus data', allowOutsideClick: false, background: '#111b21', color: '#fff', didOpen: () => { Swal.showLoading(); } });
                },
                success: function () {
                    $('tr[data-id="' + id + '"]').fadeOut(300, function () { $(this).remove(); });
                    allRows = [];
                    $('#reportTable tbody tr').each(function () { allRows.push($(this)); });
                    applyFilterAndPaginate();
                    Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Data berhasil dihapus', background: '#111b21', color: '#fff', confirmButtonColor: '#00a884' });
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                    Swal.fire({ icon: 'error', title: 'Gagal', text: 'Data gagal dihapus', background: '#111b21', color: '#fff', confirmButtonColor: '#dc3545' });
                }
            });
        });
    });

    $(document).ready(function () { initReport(); });
})();
</script>