<div class="page-card">

    <div class="report-header">

        <div class="page-title">
            Report Register NRB IDM
        </div>

        <div class="report-tools">

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
                    placeholder="Cari toko / NRB / nama">

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

        <table class="modern-table"
               id="reportTable">

            <thead>

                <tr>

                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Jam</th>
                    <th>Kode Toko</th>
                    <th>Nama Toko</th>
                    <th>NRB</th>
                    <th>Koli</th>
                    <th>Palet</th>
                    <th>Nama Register</th>
                    <th>Diubah Oleh</th>
                    <th>Keterangan</th>
                    <th style="width:120px;">Aksi</th>

                </tr>

            </thead>

            <tbody>

                @foreach($register as $row)

                    <tr data-id="{{ $row->id }}">

                        <td>{{ $loop->iteration }}</td>

                        <td class="filter-date"
                            data-date="{{ \Carbon\Carbon::parse($row->tanggal)->format('Y-m-d') }}">

                            {{ \Carbon\Carbon::parse($row->tanggal)->format('d/m/Y') }}

                        </td>

                        <td>{{ \Carbon\Carbon::parse($row->jam)->format('H:i') }}</td>

                        <td>
                            <span class="badge-toko">
                                {{ $row->toko }}
                            </span>
                        </td>

                        <td>{{ $row->nama_toko }}</td>

                        <td><span class="badge-nrb">{{ $row->nrb }}</span></td>

                        <td>{{ $row->koli }}</td>

                        <td>

                            <span class="badge-palet">
                                {{ $row->palet }}
                            </span>

                        </td>

                        <td>{{ $row->nama_register }}</td>

                        <td>{{ $row->modify_by }}</td>

                        <td>
                            @php
                                $ket = $row->keterangan;
                                $badgeClass = 'badge-ket';
                                if (str_contains($ket, 'CONTAINER')) $badgeClass = 'badge-ket container';
                                elseif (str_contains($ket, 'CONT')) $badgeClass = 'badge-ket container';
                                elseif (str_contains($ket, 'ROKOK')) $badgeClass = 'badge-ket rokok';
                                elseif (str_contains($ket, 'REGULER')) $badgeClass = 'badge-ket reguler';
                                else $badgeClass = 'badge-ket';
                            @endphp
                            <span class="{{ $badgeClass }}">{{ $ket }}</span>
                        </td>

                        <td>

                            <div class="action-group">

                                {{-- EDIT --}}
                                <button class="btn-action btn-edit"
                                        onclick="editData('{{ $row->id }}')">

                                    <i class="fas fa-edit"></i>

                                </button>

                                {{-- DELETE --}}
                                <button class="btn-action btn-delete"
                                        onclick="deleteData('{{ $row->id }}')">

                                    <i class="fas fa-trash"></i>

                                </button>

                            </div>

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>
    {{-- MODAL EDIT --}}
    <div class="modal-edit"
        id="editModal">

        <div class="modal-box">

            <div class="modal-header">

                <div class="modal-title">
                    Edit Register NRB
                </div>

                <button class="modal-close"
                        onclick="closeModal()">

                    <i class="fas fa-times"></i>

                </button>

            </div>

            <form id="formEdit">

                @csrf

                <input type="hidden"
                    id="edit_id">

                <div class="form-group">

                    <label class="form-label">
                        Kode Toko
                    </label>

                    <input type="text"
                        id="edit_toko"
                        class="form-control"
                        readonly>

                </div>

                <div class="form-group">

                    <label class="form-label">
                        No NRB
                    </label>

                    <input type="text"
                        id="edit_nrb"
                        class="form-control number-only">

                </div>

                <div class="form-group">

                    <label class="form-label">
                        Koli
                    </label>

                    <input type="text"
                        id="edit_koli"
                        class="form-control number-only">

                </div>

                <div class="form-group">

                    <label class="form-label">
                        Palet
                    </label>

                    <select id="edit_palet"
                            class="form-control">

                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                        <option value="E">E</option>
                        <option value="F">F</option>

                    </select>

                </div>

                <div class="form-group">

                    <label class="form-label">
                        Nama Register
                    </label>

                    <input type="text"
                        id="edit_nama"
                        class="form-control"
                        readonly>

                </div>

                <div class="form-group">
                    <label class="form-label">
                        Diubah Oleh (Modify By)
                    </label>
                    <input type="text"
                        id="edit_modify_by"
                        class="form-control"
                        required>
                </div>

                <div class="form-group">

                    <label class="form-label">
                        Keterangan
                    </label>

                    <select id="edit_keterangan"
                            class="form-control">

                        <option value="CONTAINER">
                            CONTAINER
                        </option>

                        <option value="ROKOK">
                            ROKOK
                        </option>

                        <option value="REGULER">
                            REGULER
                        </option>

                    </select>

                </div>

                <button type="submit"
                        class="btn-submit"
                        id="btnUpdate">

                    Update Data

                </button>

            </form>

        </div>

    </div>

    <!-- NOTIFIKASI UPDATE -->
    <div id="updateLoading" style="display:none; margin-top:15px; background:#2a3942; color:#00d5a3; padding:12px; border-radius:12px;">
        <i class="fas fa-spinner fa-spin"></i> Mengupdate data...
    </div>
    <div id="updateSuccess" style="display:none; margin-top:15px; background:#00a884; color:white; padding:12px; border-radius:12px;">
        <i class="fas fa-check-circle"></i> Data berhasil diupdate
    </div>
    <div id="updateError" style="display:none; margin-top:15px; background:#dc3545; color:white; padding:12px; border-radius:12px;">
        <i class="fas fa-times-circle"></i> Gagal mengupdate data
    </div>

    <div class="pagination-controls" style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
        <div class="pagination-info" style="color: #8696a0; font-size: 14px;"></div>
        <div class="pagination-buttons">
            <button id="prevPage" class="btn-pagination" disabled>&laquo; Prev</button>
            <span id="pageInfo" style="margin: 0 12px; color: white;"></span>
            <button id="nextPage" class="btn-pagination">Next &raquo;</button>
        </div>
    </div>

</div>


<style>

/* =========================================
    REPORT HEADER
========================================= */

.report-header{

    display:flex;
    justify-content:space-between;
    align-items:center;

    gap:20px;

    margin-bottom:20px;

    flex-wrap:wrap;
}

.report-tools{

    display:flex;
    align-items:center;
    gap:12px;

    flex-wrap:wrap;
}

.pagination-limit {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #2a3942;
    padding: 0 12px;
    border-radius: 12px;
    height: 44px;
    color: white;
}
.pagination-limit select {
    background: #1f2a30;
    border: none;
    color: white;
    border-radius: 8px;
    padding: 6px;
}

/* =========================================
    INPUT
========================================= */

.report-input{

    height:44px;

    background:#2a3942;

    border:none;
    outline:none;

    border-radius:12px;

    color:white;

    padding:0 14px;

    font-size:14px;
}

/* =========================================
    SEARCH
========================================= */

.search-box{

    width:320px;
    max-width:100%;

    height:44px;

    background:#2a3942;

    border-radius:12px;

    display:flex;
    align-items:center;

    padding:0 14px;
    gap:12px;
}

.search-box i{

    color:#8696a0;
}

.search-box input{

    flex:1;

    background:transparent;

    border:none;
    outline:none;

    color:white;
}

.search-box input::placeholder{

    color:#8696a0;
}

#filterTanggal::-webkit-calendar-picker-indicator {
    filter: invert(1);
    cursor: pointer;
}
/* =========================================
    TABLE
========================================= */

.table-wrapper{

    overflow:auto;

    border-radius:18px;

    border:1px solid rgba(255,255,255,.05);
}

.modern-table{

    width:100%;

    border-collapse:collapse;

    min-width:1100px;
}

.modern-table thead{

    background:#233138;

    position:sticky;
    top:0;

    z-index:10;
}

.modern-table th{

    padding:16px 14px;

    text-align:left;

    color:#d1d7db;

    font-size:13px;
    font-weight:600;

    white-space:nowrap;
}

.modern-table td{

    padding:14px;

    border-top:
        1px solid rgba(255,255,255,.04);

    color:#e9edef;

    font-size:14px;

    white-space:nowrap;
}

.modern-table tbody tr{

    transition:.2s;
}

.modern-table tbody tr:hover{

    background:#233138;
}

/* =========================================
    BADGE
========================================= */

.badge-toko{

    background:#00a884;

    color:white;

    padding:6px 10px;

    border-radius:999px;

    font-size:12px;
    font-weight:600;
}

.badge-palet{

    background:#3b82f6;

    color:white;

    padding:6px 10px;

    border-radius:999px;

    font-size:12px;
    font-weight:600;
}

.badge-ket{

    background:#374151;

    color:white;

    padding:6px 10px;

    border-radius:999px;

    font-size:12px;
    font-weight:600;
}
.badge-ket.container {
    background: hsl(189, 100%, 50%); /* biru */
}
.badge-ket.rokok {
    background: #ef4444; /* merah */
}
.badge-ket.reguler {
    background: #22c55e; /* hijau */
}
.badge-nrb {
    background: #00a884;   /* hijau seperti badge toko */
    color: white;
    padding: 6px 10px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
}
.btn-pagination {
    background: #2a3942;
    border: none;
    color: white;
    padding: 8px 16px;
    border-radius: 8px;
    cursor: pointer;
    transition: 0.2s;
}
.btn-pagination:hover:not(:disabled) {
    background: #3b82f6;
}
.btn-pagination:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
.btn-export{

    height:44px;

    padding:0 18px;

    border-radius:12px;

    background:#16a34a;

    color:white;

    display:flex;
    align-items:center;
    gap:10px;

    font-size:14px;
    font-weight:600;

    transition:.25s;
}

.btn-export:hover{

    opacity:.9;
}

.action-group{

    display:flex;
    align-items:center;
    gap:8px;
}

.btn-action{

    width:36px;
    height:36px;

    border:none;

    border-radius:10px;

    cursor:pointer;

    color:white;

    transition:.2s;
}

.btn-edit{

    background:#3b82f6;
}

.btn-delete{

    background:#ef4444;
}

.btn-action:hover{

    transform:scale(1.05);
}
.modal-edit{

    position:fixed;

    inset:0;

    background:rgba(0,0,0,.6);

    display:none;

    align-items:center;
    justify-content:center;

    z-index:99999;

    backdrop-filter:blur(4px);
}

.modal-box{

    width:100%;
    max-width:550px;

    background:#202c33;

    border-radius:24px;

    padding:24px;

    border:
        1px solid rgba(255,255,255,.05);

    animation:modalShow .25s ease;
}

@keyframes modalShow{

    from{

        opacity:0;
        transform:translateY(20px);
    }

    to{

        opacity:1;
        transform:translateY(0);
    }
}

.modal-header{

    display:flex;
    justify-content:space-between;
    align-items:center;

    margin-bottom:20px;
}

.modal-title{

    color:white;

    font-size:22px;
    font-weight:700;
}

.modal-close{

    width:40px;
    height:40px;

    border:none;

    border-radius:12px;

    background:#2a3942;

    color:white;

    cursor:pointer;
}

</style>

<script src="{{ asset('js/sweetalert.js') }}"></script>

<script>
function deleteData(id) {
    if (!confirm('Hapus data ini?')) {
        return;
    }
    $('#deleteSuccess, #deleteError').hide();
    $('#deleteLoading').show();

    $.ajax({
        url: '/register-nrb/delete/' + id,
        type: 'DELETE',
        data: { _token: '{{ csrf_token() }}' },
        success: function(response) {
            $('#deleteLoading').hide();
            $('#deleteSuccess').fadeIn();

            // Hapus baris berdasarkan data-id (tambahkan atribut data-id pada tr)
            var $row = $('#reportTable tbody tr[data-id="' + id + '"]');
            $row.remove();

            // Perbarui nomor urut pada kolom pertama (class row-number)
            $('#reportTable tbody tr').each(function(index) {
                $(this).find('.row-number').text(index + 1);
            });

            // Perbarui allRows dan re-pagination
            allRows = [];
            $('#reportTable tbody tr').each(function() { allRows.push($(this)); });
            applyFilterAndPaginate();

            // Hilangkan pesan sukses setelah 3 detik
            setTimeout(function() { $('#deleteSuccess').fadeOut(); }, 3000);
        },
        error: function(xhr) {
            $('#deleteLoading').hide();
            let errorMsg = xhr.responseJSON?.message || 'Gagal menghapus data';
            $('#deleteError').html('<i class="fas fa-times-circle"></i> ' + errorMsg).fadeIn();
            setTimeout(function() { $('#deleteError').fadeOut(); }, 3000);
        }
    });
}

function editData(id) {
    $('#updateSuccess, #updateError').hide();
    $('#updateLoading').hide();
    console.log('Update sukses, menampilkan notifikasi');
    $.ajax({
        url: '/register-nrb/edit/' + id,
        type: 'GET',
        success: function(data) {
            $('#edit_id').val(data.id);
            $('#edit_toko').val(data.toko);
            $('#edit_nrb').val(data.nrb);
            $('#edit_koli').val(data.koli);
            $('#edit_palet').val(data.palet);
            $('#edit_nama').val(data.nama_register);
            $('#edit_keterangan').val(data.keterangan);
            $('#edit_modify_by').val('');
            $('#editModal').css('display', 'flex');
        }
    });
}

function closeModal() {
    $('#editModal').hide();
}

// ==================== CLOSE MODAL DENGAN ESC ====================
$(document).on('keydown', function(e) {

    if (e.key === "Escape" || e.keyCode === 27) {

        if ($('#editModal').is(':visible')) {

            closeModal();

        }

    }

});

// ==================== CLOSE MODAL KLIK AREA LUAR ====================
$('#editModal').on('mousedown', function(e) {

    // jika klik langsung area overlay/modal background
    if (e.target === this) {

        closeModal();

    }

});

$('#formEdit').submit(function(e) {
    e.preventDefault();
    let id = $('#edit_id').val();

    // Sembunyikan notifikasi sebelumnya
    $('#updateSuccess, #updateError').hide();
    $('#updateLoading').show();
    $('#btnUpdate').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Updating...');

    $.ajax({
        url: '/register-nrb/update/' + id,
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            kode_toko: $('#edit_toko').val(),
            no_nrb: $('#edit_nrb').val(),
            koli: $('#edit_koli').val(),
            palet: $('#edit_palet').val(),
            nama_register: $('#edit_nama').val(),
            keterangan: $('#edit_keterangan').val(),
            modify_by: $('#edit_modify_by').val()
        },
        success: function(response) {
            console.log('Update sukses', response);
            alert('Data berhasil diupdate');
            $('#updateLoading').hide();
            
            // Pastikan elemen updateSuccess ada, lalu tampilkan
            if ($('#updateSuccess').length) {
                $('#updateSuccess').fadeIn();
            } else {
                console.error('Elemen #updateSuccess tidak ditemukan');
            }
            
            $('#btnUpdate').prop('disabled', false).html('Update Data');
            $('#editModal').hide();

            // Rest of your update row code...
            try {

                const $row = $('#reportTable tbody tr[data-id="' + id + '"]');

                if ($row.length) {

                    // KODE TOKO
                    $row.find('.badge-toko')
                        .text($('#edit_toko').val());

                    // NRB
                    $row.find('.badge-nrb')
                        .text($('#edit_nrb').val());

                    // KOLI
                    // sekarang posisi kolom ke-7
                    $row.find('td:nth-child(7)')
                        .text($('#edit_koli').val());

                    // PALET
                    $row.find('.badge-palet')
                        .text($('#edit_palet').val());

                    // NAMA REGISTER
                    // sekarang posisi kolom ke-9
                    $row.find('td:nth-child(9)')
                        .text($('#edit_nama').val());

                    // MODIFY BY
                    // sekarang posisi kolom ke-10
                    $row.find('td:nth-child(10)')
                        .text($('#edit_modify_by').val());

                    // KETERANGAN
                    const newKet = $('#edit_keterangan').val();

                    let badgeClass = 'badge-ket';

                    if (newKet.includes('CONTAINER') || newKet.includes('CONT')) {

                        badgeClass = 'badge-ket container';

                    } else if (newKet.includes('ROKOK')) {

                        badgeClass = 'badge-ket rokok';

                    } else if (newKet.includes('REGULER')) {

                        badgeClass = 'badge-ket reguler';

                    }

                    // sekarang posisi kolom ke-11
                    $row.find('td:nth-child(11) .badge-ket')
                        .attr('class', badgeClass)
                        .text(newKet);

                    // REFRESH DATA PAGINATION
                    allRows = [];

                    $('#reportTable tbody tr').each(function () {

                        allRows.push($(this));

                    });

                    applyFilterAndPaginate();
                }

            } catch(e) {

                console.error('Error update baris:', e);

            }

            setTimeout(function() {

                $('#updateSuccess').fadeOut();

            }, 3000);

        },
        error: function(xhr) {
            $('#updateLoading').hide();
            let errorMsg = xhr.responseJSON?.message || 'Gagal mengupdate data';
            $('#updateError').html('<i class="fas fa-times-circle"></i> ' + errorMsg).fadeIn();
            $('#btnUpdate').prop('disabled', false).html('Update Data');
            setTimeout(function() {
                $('#updateError').fadeOut();
            }, 3000);
        }
    });
});

// ==================== CLIENT-SIDE PAGINATION ====================
let currentPage = 1;
let rowsPerPage = 25;
let allRows = [];
let filteredRows = [];

function initPagination() {
    console.log("initPagination dipanggil"); // untuk debug

    currentPage = 1;
    let val = $('#entriesPerPage').val();
    rowsPerPage = (val === '-1') ? 999999 : (parseInt(val) || 25);

    allRows = [];
    $('#reportTable tbody tr').each(function() { allRows.push($(this)); });

    applyFilterAndPaginate();

    // Event handlers (gunakan off agar tidak double)
    $('#entriesPerPage').off('change').on('change', function() {
        let v = $(this).val();
        rowsPerPage = (v === '-1') ? filteredRows.length : parseInt(v);
        currentPage = 1;
        renderPage();
    });

    $('#prevPage').off('click').on('click', function() {
        if (currentPage > 1) { currentPage--; renderPage(); }
    });

    $('#nextPage').off('click').on('click', function() {
        let totalPages = Math.ceil(filteredRows.length / rowsPerPage);
        if (currentPage < totalPages) { currentPage++; renderPage(); }
    });

    $('#filterTanggal').off('change').on('change', function() {
        let tanggal = $(this).val();
        let exportUrl = "{{ route('register-nrb.export') }}";
        if (tanggal) exportUrl += '?tanggal=' + tanggal;
        $('#btnExport').attr('href', exportUrl);
        applyFilterAndPaginate();
    });

    $('#searchReport').off('keyup').on('keyup', function() {
        applyFilterAndPaginate();
    });
}

// ==================== EXPORT XLSX FILTER ====================
$('#btnExport').off('click').on('click', function () {

    let tanggal = $('#filterTanggal').val();
    let search  = $('#searchReport').val();

    let params = new URLSearchParams();

    // FILTER TANGGAL
    if (tanggal !== '') {

        params.append('tanggal', tanggal);

    }

    // FILTER SEARCH
    if (search !== '') {

        params.append('search', search);

    }

    // URL EXPORT
    let exportUrl = "{{ route('register-nrb.export') }}";

    // TAMBAH QUERY STRING
    if (params.toString() !== '') {

        exportUrl += '?' + params.toString();

    }

    // DOWNLOAD FILE
    window.location.href = exportUrl;

});

function getFilteredRows() {
    let searchValue = $('#searchReport').val().toLowerCase();
    let tanggalValue = $('#filterTanggal').val();
    return allRows.filter(row => {
        let text = row.text().toLowerCase();
        let matchesSearch = searchValue === '' || text.indexOf(searchValue) > -1;
        let rowTanggal = row.find('.filter-date').data('date');
        let matchesDate = tanggalValue === '' || rowTanggal === tanggalValue;
        return matchesSearch && matchesDate;
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
    $('#nextPage').prop('disabled', currentPage === totalPages || totalPages === 0);
}

function applyFilterAndPaginate() {
    filteredRows = getFilteredRows();
    currentPage = 1;
    renderPage();
}

// Jalankan initPagination segera setelah script di-load (baik refresh biasa maupun AJAX)
initPagination();
</script>