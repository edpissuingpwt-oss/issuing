@extends('layouts.app')

@section('content')
<li class="nav-item d-none d-sm-inline-block">
    @php
        $hari = [
          'Monday' => 'Senin',
          'Tuesday' => 'Selasa',
          'Wednesday' => 'Rabu',
          'Thursday' => 'Kamis',
          'Friday' => "Jum'at",
          'Saturday' => 'Sabtu',
          'Sunday' => 'Minggu'
        ];

        $hariIni = $hari[\Carbon\Carbon::now()->format('l')];
        $tanggalLengkap = \Carbon\Carbon::now()->translatedFormat('d F Y');
    @endphp

    <span class="nav-link d-flex align-items-center tanggal-live">

        <i class="far fa-calendar-alt mr-2 text-primary"></i>
        <span class="mr-3">
            {{ $hariIni }}, {{ $tanggalLengkap }}
        </span>

        <i class="far fa-clock mr-2 text-success"></i>
        <span id="live-clock" class="mr-3"></span>

        <i class="fas fa-map-marker-alt mr-2 text-danger"></i>
        <span>INDOGROSIR PURWOKERTO</span>

    </span>
</li>

<div class="container-fluid px-4 py-3">
    
    <!-- Stats Cards Row -->
    <div class="row mb-3">
        <div class="col-md-3 col-6">
            <div class="modern-stat blue">
                <div class="inner">
                    <div class="stats">
                        <h3 id="stat-pbidm">{{ $stats['pbidm'] ?? 0 }}</h3>
                        <p>PB IDM</p>
                    </div>
                    <div class="icon">
                        <img src="{{ asset('icons8-store-100.png') }}" alt="IDM" width="120" height="120">
                    </div>
                </div>
                <a href="{{ url('pb_idm') }}" class="modern-stat-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        
        <div class="col-md-3 col-6">
            <div class="modern-stat green">
                <div class="inner">
                    <div class="stats">
                        <h3 id="stat-pbomi">{{ $stats['pbomi'] ?? 0 }}</h3>
                        <p>PB OMI</p>
                    </div>
                    <div class="icon">
                        <img src="{{ asset('icons8-store-1002.png') }}" alt="IDM" width="120" height="120">
                    </div>
                </div>
                <a href="{{ url('pb_omi') }}" class="modern-stat-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        
        <div class="col-md-3 col-6">
            <div class="modern-stat red">
                <div class="inner">
                    <div class="stats">
                        <h3 id="stat-wt">{{ $stats['wt'] ?? 0 }}</h3>
                        <p>WT PENDING</p>
                    </div>
                    <div class="icon">
                        <img src="{{ asset('icons8-sales-100.png') }}" alt="sales" width="120" height="120">
                    </div>
                </div>
                <a href="{{ url('wt_idm') }}" class="modern-stat-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        
        <div class="col-md-3 col-6">
            <div class="modern-stat red">
                <div class="inner">
                    <div class="stats">
                        <h3 id="stat-sph">{{ $stats['sph'] ?? 0 }}</h3>
                        <p>SPH PENDING</p>
                    </div>
                    <div class="icon">
                        <img src="{{ asset('icons8-sales-100.png') }}" alt="sales" width="120" height="120">
                    </div>
                </div>
                <a href="{{ url('sph_omi') }}" class="modern-stat-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Tabs -->
    <div class="card card-primary card-outline">
        <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" id="mainTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="sl-tab" data-toggle="tab" href="#sl" role="tab">
                        <i class="fas fa-chart-line mr-1"></i> SERVICE LEVEL HARIAN
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pbmentah-tab" data-toggle="tab" href="#pbmentah" role="tab">
                        {{-- <img src="{{ asset('icons8-dollar-100.png') }}" alt="SL" width="30" height="30"></i> --}}
                        <i class="fas fa-shopping-cart mr-1"></i>
                        PB MENTAH
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pbsl-tab" data-toggle="tab" href="#pbsl" role="tab">
                        {{-- <img src="{{ asset('icons8-dollar-100.png') }}" alt="SL" width="30" height="30"></i> --}}
                        <i class="fas fa-dolly-flatbed mr-1"></i>PB & SL TODAY
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="rupiah-tab" data-toggle="tab" href="#rupiah" role="tab">
                        <i class="fas fa-money-bill-wave mr-1"></i> RUPIAH PICKING
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="picking-tab" data-toggle="tab" href="#picking" role="tab">
                        <i class="fas fa-boxes mr-1"></i> MONITORING PICKING
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="loading-tab" data-toggle="tab" href="#loading" role="tab">
                        <i class="fas fa-truck-loading mr-1"></i> MONITORING LOADING
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="card-body">
            <div class="tab-content">
                
                <!-- TAB 1: SL HARIAN -->
                <div class="tab-pane fade show active" id="sl" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                        <ul class="nav nav-pills" id="slSubTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active bg-danger" data-toggle="pill" href="#sl-all" role="tab">ALL</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link bg-primary" data-toggle="pill" href="#sl-idm" role="tab">IDM</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link bg-success" data-toggle="pill" href="#sl-omi" role="tab">OMI</a>
                            </li>
                        </ul>
                        <button class="btn btn-sm btn-outline-primary refresh-btn" data-tab="sl">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                    
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="sl-all" role="tabpanel">
                            @include('dashboardv2.partials.sl-harian', ['data' => $slHarian['all'] ?? [], 'title' => 'ALL'])
                        </div>
                        <div class="tab-pane fade" id="sl-idm" role="tabpanel">
                            @include('dashboardv2.partials.sl-harian', ['data' => $slHarian['idm'] ?? [], 'title' => 'IDM'])
                        </div>
                        <div class="tab-pane fade" id="sl-omi" role="tabpanel">
                            @include('dashboardv2.partials.sl-harian', ['data' => $slHarian['omi'] ?? [], 'title' => 'OMI'])
                        </div>
                    </div>
                </div>
                
                <div class="tab-pane fade" id="pbmentah" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                        <ul class="nav nav-pills" id="pbmentahSubTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active bg-primary" data-toggle="pill" href="#pbmentah-idm" role="tab">IDM</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link bg-success" data-toggle="pill" href="#pbmentah-omi" role="tab">OMI</a>
                            </li>
                        </ul>
                        <button class="btn btn-sm btn-outline-primary refresh-btn" data-tab="pbmentah">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                    
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="pbmentah-idm" role="tabpanel">
                            <div id="pbmentah-idm-container">
                                <div class="text-center p-5">
                                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                                    <p class="mt-2">Memuat data...</p>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pbmentah-omi" role="tabpanel">
                            <div id="pbmentah-omi-container">
                                <div class="text-center p-5">
                                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                                    <p class="mt-2">Memuat data...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 2: PBSL (PB & SL) -->
                <div class="tab-pane fade" id="pbsl" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                        <ul class="nav nav-pills" id="pbslSubTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active bg-primary" data-toggle="pill" href="#pbsl-idm" role="tab">IDM</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link bg-success" data-toggle="pill" href="#pbsl-omi" role="tab">OMI</a>
                            </li>
                        </ul>
                        <button class="btn btn-sm btn-outline-primary refresh-btn" data-tab="pbsl">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                    
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="pbsl-idm" role="tabpanel">
                            <div id="pbsl-idm-container">
                                <div class="text-center p-5">
                                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                                    <p class="mt-2">Memuat data...</p>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pbsl-omi" role="tabpanel">
                            <div id="pbsl-omi-container">
                                <div class="text-center p-5">
                                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                                    <p class="mt-2">Memuat data...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 3: RUPIAH PICKING -->
                <div class="tab-pane fade" id="rupiah" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                        <ul class="nav nav-pills" id="rupiahSubTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active bg-danger" data-toggle="pill" href="#rupiah-all" role="tab">ALL</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link bg-primary" data-toggle="pill" href="#rupiah-idm" role="tab">IDM</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link bg-success" data-toggle="pill" href="#rupiah-omi" role="tab">OMI</a>
                            </li>
                        </ul>
                        <button class="btn btn-sm btn-outline-primary refresh-btn" data-tab="rupiah">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                    
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="rupiah-all" role="tabpanel">
                            <div id="rupiah-all-container">
                                <div class="text-center p-5">
                                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                                    <p class="mt-2">Memuat data...</p>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="rupiah-idm" role="tabpanel">
                            <div id="rupiah-idm-container">
                                <div class="text-center p-5">
                                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                                    <p class="mt-2">Memuat data...</p>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="rupiah-omi" role="tabpanel">
                            <div id="rupiah-omi-container">
                                <div class="text-center p-5">
                                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                                    <p class="mt-2">Memuat data...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- TAB 4: MONITORING PICKING -->
                <div class="tab-pane fade" id="picking" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0"><i class="fas fa-boxes mr-2"></i> Monitoring Picking & Scanning</h5>
                        <button class="btn btn-sm btn-outline-primary refresh-btn" data-tab="picking">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                    <div id="picking-container">
                        <div class="text-center p-5">
                            <i class="fas fa-spinner fa-spin fa-2x"></i>
                            <p class="mt-2">Memuat data...</p>
                        </div>
                    </div>
                </div>
                
                <!-- TAB 5: MONITORING LOADING -->
                <div class="tab-pane fade" id="loading" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0"><i class="fas fa-truck-loading mr-2"></i> Monitoring Loading</h5>
                        <button class="btn btn-sm btn-outline-primary refresh-btn" data-tab="loading">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                    <div id="loading-container">
                        <div class="text-center p-5">
                            <i class="fas fa-spinner fa-spin fa-2x"></i>
                            <p class="mt-2">Memuat data...</p>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/xlsx.full.min.js') }}"></script>
<script src="{{ asset('js/dashboard.js') }}"></script>
<script>
    window.sunIcon = "{{ asset('sun.png') }}";
    window.moonIcon = "{{ asset('moon.png') }}";
</script>
@endpush
@endsection