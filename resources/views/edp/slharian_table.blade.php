<style>
    :root {
        --bg-card: #ffffff;
        --bg-hover: #f8f9fa;
        --text-main: #212529;
        --text-muted: #6c757d;
        --border-color: #e5e7eb;
    }

    /* DARK MODE */
    [data-theme="dark"] {
        --bg-card: #1e1e2d;
        --bg-hover: #2a2a3b;
        --text-main: #e4e6eb;
        --text-muted: #a1a1aa;
        --border-color: #3a3a4d;
    }
    
    .table td {
        padding: 2px 6px !important;
        vertical-align: middle;
    }

    .table td:first-child {
        white-space: nowrap;
        width: 1%;
    }

    .bg-idm {
        background-color: #0d6efd;
        color: white;
    }

    .bg-omi {
        background-color: #198754; /* hijau */
        color: white;
    }

    .section-title {
        font-size: 13px;
        font-weight: 800;
        color: var(--text-muted);
        margin-top: 8px;
        margin-bottom: 4px;
        text-transform: uppercase;
        text-align: center;
    }

    .card {
        background: var(--bg-card);
        color: var(--text-main);
    }

    .row-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 4px 6px;
        border-bottom: 1px solid var(--border-color);
        font-size: 14px;
    }

    .row-item span:first-child {
        color: var(--text-muted);
    }

    .row-item .value {
        font-weight: 600;
        text-align: right;
        color: var(--text-main);
    }

    .row-item:last-child {
        border-bottom: none;
    }

    .row-item:hover {
        background: #f8f9fa;
        background: var(--bg-hover);
    }

    .value {
        color: #212529;
        font-weight: 700;
    }
</style>
<div class="row mt-4">

    {{-- ================= LEFT COLUMN ================= --}}
    <div class="col-md-6">
        <div class="card shadow border-0 h-100">

            <div class="card-header {{ $toko == 'IDM' ? 'bg-idm' : 'bg-omi' }}">
                <h5 class="mb-0 fw-bold">
                    SERVICE LEVEL {{ $toko }} {{ strtoupper(\Carbon\Carbon::parse($data->tgl_upload)->translatedFormat('d F Y')) }}
                </h5>
            </div>

            <div class="card-body p-2">

                {{-- ===== JAM ===== --}}
                <div class="section-title">JAM</div>

                <div class="row-item"><span>TOTAL TOKO</span><span class="value">{{ number_format($data->toko) }}</span></div>
                <div class="row-item"><span>JAM REQUEST</span><span class="value">{{ $data->req }}</span></div>
                <div class="row-item"><span>MULAI PICKING</span><span class="value">{{ $data->start_pick }}</span></div>
                <div class="row-item"><span>SELESAI SCANNING</span><span class="value">{{ $data->end_scan }}</span></div>
                <div class="row-item"><span>SELESAI DSPB</span><span class="value">{{ $data->end_dspb }}</span></div>

                {{-- ===== REKAP ===== --}}
                <div class="section-title mt-2">LAPORAN SL {{ $toko }} {{ strtoupper(\Carbon\Carbon::parse($data->tgl_upload)->translatedFormat('d F Y')) }}</div>

                <div class="row-item"><span>PB {{ $toko }}</span><span class="value">{{ number_format($data->rp_pb_rupiah) }}</span></div>
                <div class="row-item"><span>TOLAKAN MD</span><span class="value">{{ number_format($data->rp_tolakan_md) }}</span></div>
                <div class="row-item"><span>TOLAKAN LJM</span><span class="value">{{ number_format($data->rp_tolakan_ljm) }}</span></div>
                <div class="row-item"><span>UPLOAD DPD</span><span class="value">{{ number_format($data->rp_upload_dpd) }}</span></div>
                <div class="row-item"><span>SL DSPB</span><span class="value">{{ number_format($data->rp_sl_dspb) }}</span></div>
                <div class="row-item"><span>SL PICKING & SCANNING</span><span class="value">{{ number_format($data->rp_sl_dspb) }}</span></div>

                <div class="section-title mt-2">REKAP</div>
                <div class="row-item"><span>TIDAK TERPICKING</span><span class="value">{{ number_format($data->rp_tidakpick) }}</span></div>
                <div class="row-item"><span>TERPICKING SEBAGIAN</span><span class="value">{{ number_format($data->rp_sebagian) }}</span></div>
                <div class="row-item"><span>STOCK EKONOMIS</span><span class="value">{{ number_format($data->qty_stockekonomis) }}</span></div>
                <div class="row-item"><span>AHOT</span><span class="value">{{ number_format($data->qty_ahot) }}</span></div>
                <div class="row-item"><span>% STOCK EKONOMIS</span><span class="value">{{ $data->persenqtyekonomis }}</span></div>
                <div class="row-item"><span>% AHOT</span><span class="value">{{ $data->persenqtyahot }}</span></div>

            </div>
        </div>
    </div>

    {{-- ================= RIGHT COLUMN ================= --}}
    <div class="col-md-6">
        <div class="card shadow border-0 h-100">

            <div class="card-header bg-dark">
                <h6 class="mb-0 fw-bold">
                    DETAIL 
                </h6>
            </div>

            <div class="card-body p-2">
                <div class="section-title mt-2">ITEM TAG T,V,O,N,A,H,X,I,G,U & ONE SHOT</div>
                <div class="row-item"><span>TAG MD</span><span class="value">{{ number_format($data->rp_tag_md) }}</span></div>
                <div class="row-item"><span>TAG LJM</span><span class="value">{{ number_format($data->rp_tag_ljm) }}</span></div>
                <div class="row-item"><span>TAG ALL</span><span class="value">{{ number_format($data->rp_tag_all) }}</span></div>
                
                <div class="section-title mt-2">PB {{ $toko }}</div>
                <div class="row-item"><span>ITEM</span><span class="value">{{ number_format($data->item_pb) }}</span></div>
                <div class="row-item"><span>QTY</span><span class="value">{{ number_format($data->qty_pb) }}</span></div>
                <div class="row-item"><span>RUPIAH</span><span class="value">{{ number_format($data->rp_pb_rumus) }}</span></div>
                
                <div class="section-title mt-2">TOLAKAN {{ $toko }}</div>
                <div class="row-item"><span>ITEM</span><span class="value">{{ number_format($data->item_tolakan) }}</span></div>
                <div class="row-item"><span>QTY</span><span class="value">{{ number_format($data->qty_tolakan) }}</span></div>
                <div class="row-item"><span>RUPIAH</span><span class="value">{{ number_format($data->rp_tolakan) }}</span></div>

                <div class="section-title mt-2">UPLOAD DPD</div>
                <div class="row-item"><span>ITEM</span><span class="value">{{ number_format($data->item_upload) }}</span></div>
                <div class="row-item"><span>QTY</span><span class="value">{{ number_format($data->qty_upload) }}</span></div>
                <div class="row-item"><span>RUPIAH</span><span class="value">{{ number_format($data->rp_upload) }}</span></div>

                <div class="section-title mt-2">DSPB</div>
                <div class="row-item"><span>ITEM</span><span class="value">{{ number_format($data->item_dspb) }}</span></div>
                <div class="row-item"><span>QTY</span><span class="value">{{ number_format($data->qty_dspb) }}</span></div>
                <div class="row-item"><span>RUPIAH</span><span class="value">{{ number_format($data->rp_dspb) }}</span></div>

            </div>
        </div>
    </div>

</div>