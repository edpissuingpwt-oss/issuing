@push('styles')
<style>
    .main-sidebar, .main-sidebar *, .nav-sidebar {
        display: none !important;
    }
    body.sidebar-mini .content-wrapper, .content-wrapper, .wrapper {
        margin-left: 0 !important;
        width: 100% !important;
    }
    .wrapper {
    margin-left: 0 !important;
    }

    /* Tempus Dominus modern style */
    .tempus-dominus-widget {
        z-index: 2050 !important;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25) !important;
        border-radius: 0.75rem !important;
        background: #ffffff !important;
        border: none !important;
        padding: 0.5rem;
    }
    .tempus-dominus-widget .datepicker-days .table-condensed thead tr:first-child th {
        background: linear-gradient(45deg, #007bff, #00c6ff);
        color: #fff !important;
        font-weight: 600;
        border-radius: 0.5rem 0.5rem 0 0;
    }
    .tempus-dominus-widget .day:hover {
        background: #007bff !important;
        color: #fff !important;
        border-radius: 50% !important;
        transition: all 0.2s ease-in-out;
    }
    .tempus-dominus-widget .day.active {
        background: linear-gradient(45deg, #007bff, #00c6ff) !important;
        color: #fff !important;
        font-weight: 600;
        border-radius: 50% !important;
    }

    /* Kartu modern */
    .modern-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }
    .card-header.bg-gradient-primary {
        background: linear-gradient(45deg, #007bff, #00c6ff);
    }
    .card-title {
        font-size: 1.25rem;
        font-weight: 600;
    }
    .modern-body {
        background: var(--card-bg);
        padding: 1.5rem;
    }
    .modern-footer {
        background: var(--footer-bg);
        border-top: 1px solid rgba(0,0,0,0.05);
        padding: 1rem 1.5rem;
    }

    /* Input datepicker */
    .modern-datepicker .form-control {
        border-radius: 0.5rem 0 0 0.5rem;
        border: 1px solid #dee2e6;
    }
    .modern-datepicker .input-group-text {
        border-radius: 0 0.5rem 0.5rem 0;
        cursor: pointer;
        transition: background 0.2s;
    }
    .modern-datepicker .input-group-text:hover {
        background: #0056b3 !important;
    }

    /* Tombol */
    .modern-btn-submit {
        background: linear-gradient(45deg, #007bff, #00c6ff);
        color: #fff;
        border: none;
        border-radius: 0.5rem;
        font-weight: 600;
    }
    .modern-btn-submit:hover {
        background: linear-gradient(45deg, #0056b3, #0099cc);
    }
    .modern-btn-clear {
        background: #f44336;
        color: #fff;
        border: none;
        border-radius: 0.5rem;
        font-weight: 600;
    }
    .modern-btn-clear:hover {
        background: #c62828;
    }

    /* Dark Mode */
    [data-theme="dark"] .modern-body {
        background: #2a2a3d;
        color: #f1f1f1;
    }
    [data-theme="dark"] .modern-footer {
        background: #1e1e2f;
    }
    [data-theme="dark"] .modern-datepicker .form-control {
        background: #1e1e2f;
        color: #fff;
        border-color: #444;
    }

    /* Radio button */
    .form-check-input {
        transform: scale(1.2);
        margin-top: 2px;
    }
    .form-check-label {
        font-size: 0.95rem;
    }

    /* Tambah jarak antar radio button */
    .radio-group .form-check {
        margin-right: 2rem; /* jarak horizontal antar OMI dan IDM */
    }
    /* Tambah jarak antar tombol */
    .button-group .btn {
        margin-left: 1rem; /* jarak antar Submit dan Clear */
    }
    /* Header Modern */
    .modern-header {
        background: linear-gradient(45deg, #007bff, #00c6ff);
        color: #fff !important; /* teks tetap putih */
        border-bottom: none;
        padding: 0.75rem 1rem;
        border-radius: 0.75rem 0.75rem 0 0;
    }

    /* Dark Mode Header */
    [data-theme="dark"] .modern-header {
        background: linear-gradient(45deg, #1e3c72, #2a5298); /* biru gelap */
        color: #fff !important;
    }

    [data-theme="dark"] .modern-select {
        background-color: #2c2f33;
        color: #e0e0e0;
        border-color: #444c57;
    }

    [data-theme="dark"] .modern-select:focus {
        border-color: #9ecbff;
        box-shadow: 0 0 0 0.2rem rgba(158, 203, 255, 0.25);
    }

    [data-theme="dark"] .form-control {
        background-color: #2c2f33;
        color: #f1f1f1;
        border-color: #444;
    }
    /* ========== DARK MODE TABLE BACKGROUND & TEXT ========== */

    [data-theme="dark"] table.dataTable,
    [data-theme="dark"] table.dataTable thead,
    [data-theme="dark"] table.dataTable tbody,
    [data-theme="dark"] table.dataTable tfoot,
    [data-theme="dark"] table.dataTable th,
    [data-theme="dark"] table.dataTable td {
    background-color: #1e1e2f !important;
    color: #ffffff !important;
    border-color: #444 !important;
    }

    /* Optional: border between rows */
    [data-theme="dark"] table.dataTable tbody tr {
    border-bottom: 1px solid #333;
    }

    /* Optional: hover effect */
    [data-theme="dark"] table.dataTable tbody tr:hover {
    background-color: #2d2d3f;
    }

    /* Search input & select */
    [data-theme="dark"] .dataTables_wrapper .dataTables_filter input,
    [data-theme="dark"] .dataTables_wrapper .dataTables_length select {
    background-color: #1e1e2f;
    color: #fff;
    border: 1px solid #555;
    }

    /* Page buttons */
    [data-theme="dark"] .dataTables_wrapper .dataTables_paginate .paginate_button {
    color: #fff !important;
    background-color: #2e2e3e !important;
    border: 1px solid #555 !important;
    }
    [data-theme="dark"] .dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background-color: #444 !important;
    color: #fff !important;
    }
    /* Overwrite Bootstrap striped background di dark mode */
    [data-theme="dark"] .table-striped tbody tr:nth-of-type(odd) {
    background-color: #232334 !important;
    }

    [data-theme="dark"] .table-striped tbody tr:nth-of-type(even) {
    background-color: #1e1e2f !important;
    }

    /* ========== DARK MODE - DATATABLE HEADER TOOLS (filter, length, buttons) ========== */
    [data-theme="dark"] .dataTables_wrapper .dataTables_length,
    [data-theme="dark"] .dataTables_wrapper .dataTables_filter,
    [data-theme="dark"] .dataTables_wrapper .dt-buttons {
    background-color: #1e1e2f !important;
    color: #fff !important;
    padding: 0.5rem;
    border-radius: 0.25rem;
    }

    /* Search input */
    [data-theme="dark"] .dataTables_wrapper .dataTables_filter input {
    background-color: #2b2b3c !important;
    color: #fff !important;
    border: 1px solid #555 !important;
    }

    /* Select "Tampilkan" */
    [data-theme="dark"] .dataTables_wrapper .dataTables_length select {
    background-color: #2b2b3c !important;
    color: #fff !important;
    border: 1px solid #555 !important;
    }

    /* Button Export Excel/Print */
    [data-theme="dark"] .dataTables_wrapper .dt-buttons .dt-button {
    background-color: #333 !important;
    color: #fff !important;
    border: 1px solid #555 !important;
    }
    /* ===== DARK MODE FIX: DataTables Copy Notification (Popup) ===== */
    [data-theme="dark"] .dt-button-info {
    background-color: #2b2b3c !important;
    color: #ffffff !important;
    border: 1px solid #555 !important;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    }

    /* Judul (h2) di popup */
    [data-theme="dark"] .dt-button-info h2 {
    color: #ffffff !important;
    border-bottom: 1px solid #666 !important;
    }

    /* Teks konten (biasanya span atau div di bawah h2) */
    [data-theme="dark"] .dt-button-info > div {
    color: #ffffff !important;
    }
    
</style>
@endpush

<div class="card modern-card shadow-lg border-0 mt-4">
    <div class="card-body modern-body">
            <div id="exportButtons"></div>
        <div class="table-responsive">
            <table id="example1" class="table table-bordered table-striped" style="width:100%">
                @if($jenis == 'stock')
                <thead class="bg-gradient-info">
                    <tr>
                        <th class="text-center">CABANG_IGR</th>
                        <th class="text-center">CAB</th>
                        <th class="text-center">TANGGAL</th>
                        <th class="text-center">PLU_IGR</th>
                        <th class="text-center">PLU_MCG</th>
                        <th class="text-center">DESKRIPSI</th>
                        <th class="text-center">FRAC</th>
                        <th class="text-center">ACOST</th>
                        <th class="text-center">LPP_QTY</th>
                        <th class="text-center">LPP_RPH</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 0; @endphp
                    @foreach($data as $row)
                        @php $no++; @endphp
                        <tr>
                            <td class="text-center">{{ $row->cabang_igr }}</td>
                            <td class="text-center">{{ $row->cab }}</td>
                            <td class="text-center">{{ $row->tanggal }}</td>
                            <td class="text-center">{{ $row->plu_igr }}</td>
                            <td class="text-center">{{ $row->plu_mcg }}</td>
                            <td class="text-center">{{ $row->deskripsi }}</td>
                            <td class="text-center">{{ $row->frac }}</td>
                            <td class="text-center">{{ $row->acost }}</td>
                            <td class="text-center">{{ $row->lpp_qty }}</td>
                            <td class="text-center">{{ $row->lpp_rph }}</td>
                        </tr>
                    @endforeach
                </tbody>
                @elseif($jenis == 'rekap')
                <thead class="bg-gradient-info">
                    <tr>
                        <th class="text-center">PLU_IGR</th>
                        <th class="text-center">PLU_IDM</th>
                        <th class="text-center">DESK</th>
                        <th class="text-center">SALDO_AKHIR</th>
                        <th class="text-center">SUM_QTY_PB</th>
                        <th class="text-center">SUM_QTY_REAL</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $i => $row)
                        <tr>
                            <td class="text-center">{{ $row->plu_igr }}</td>
                            <td class="text-center">{{ $row->plu_idm }}</td>
                            <td class="text-center">{{ $row->desk }}</td>
                            <td class="text-center">{{ $row->saldo_akhir }}</td>
                            <td class="text-center">{{ $row->sum_qty_pb }}</td>
                            <td class="text-center">{{ $row->sum_qty_real }}</td>
                        </tr>
                    @endforeach
                </tbody>
                @elseif($jenis == 'detail')
                <thead class="bg-gradient-info">
                    <tr>
                        <th class="text-center">KODE_IGR</th>
                        <th class="text-center">CAB</th>
                        <th class="text-center">TGLPB</th>
                        <th class="text-center">KODE_CUST</th>
                        <th class="text-center">KODE_TOKO</th>
                        <th class="text-center">PLUIDM</th>
                        <th class="text-center">PLUIGR</th>
                        <th class="text-center">DESK</th>
                        <th class="text-center">FRAC</th>
                        <th class="text-center">UNIT</th>
                        <th class="text-center">HPP</th>
                        <th class="text-center">QTY_PB</th>
                        <th class="text-center">QTY_REAL</th>
                        <th class="text-center">RPH_ORDER</th>
                        <th class="text-center">RPH_REAL</th>
                        <th class="text-center">JENIS_TOKO</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $i => $row)
                        <tr>
                            <td class="text-center">{{ $row->kode_igr }}</td>
                            <td class="text-center">{{ $row->cab }}</td>
                            <td class="text-center">{{ $row->tglpb }}</td>
                            <td class="text-center">{{ $row->kode_cust }}</td>
                            <td class="text-center">{{ $row->kode_toko }}</td>
                            <td class="text-center">{{ $row->pluidm }}</td>
                            <td class="text-center">{{ $row->plu }}</td>
                            <td class="text-center">{{ $row->desk }}</td>
                            <td class="text-center">{{ $row->frac }}</td>
                            <td class="text-center">{{ $row->unit }}</td>
                            <td style="text-align: right">{{ $row->hpp }}</td>
                            <td style="text-align: right">{{ $row->qty_pb }}</td>
                            <td style="text-align: right">{{ $row->qty_real }}</td>
                            <td style="text-align: right">{{ number_format($row->rph_order) }}</td>
                            <td style="text-align: right">{{ number_format($row->rph_real) }}</td>
                            <td class="text-center">{{ $row->jenis_toko }}</td>
                        </tr>
                    @endforeach
                </tbody>
                @endif
            </table>
        </div>
    </div>
</div>
@stack('styles')
