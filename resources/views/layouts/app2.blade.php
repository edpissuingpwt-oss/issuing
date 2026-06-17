<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'ISSUING | IGRPWT')</title>
  
  <!-- Favicon -->
  <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('icons8-dungeons-and-dragons-48.png') }}">

  <script src="{{ asset('js/countup.js') }}"></script>

  {{-- <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet"> --}}
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('adminlte') }}/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="{{ asset('css/base.css') }}">
  @stack('styles')

</head>
<body>
@include('sweetalert::alert')
<div class="wrapper">

<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-light navbar-modern">
  <!-- kiri -->
  <ul class="navbar-nav">
    <li class="nav-item issuing-brand">
      <a href="{{ url('/') }}" class="nav-link d-flex align-items-center">
        <span class="brand-text">ISSUING</span>
        <span class="separator">|</span>
        <span class="brand-sub">IGRPWT</span>
      </a>
    </li>

    {{-- Menu Laporan --}}
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle font-weight-bold text-white" href="#" id="laporanDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        LAPORAN
      </a>
      <div class="dropdown-menu dropdown-menu-right shadow-lg border-0 rounded-lg p-2 modern-dropdown" aria-labelledby="laporanDropdown">
        <div class="dropdown-scroll">
          <a class="dropdown-item" href="{{ url('awb_ipp_omi') }}">
            <img src="{{ asset('icons8-report-card-64.png') }}" alt="laporan" width="20" height="20">Laporan AWB OMI</a>
          <a class="dropdown-item" href="{{ url('bkl_omi') }}">
            <img src="{{ asset('icons8-report-card-64.png') }}" alt="laporan" width="20" height="20">LAPORAN BKL OMI</a>
          <a class="dropdown-item" href="{{ url('tolakan_pb') }}">
            <img src="{{ asset('icons8-report-card-64.png') }}" alt="laporan" width="20" height="20">Laporan Tolakan PB</a>
          <a class="dropdown-item" href="{{ url('sarana_picking') }}">
            <img src="{{ asset('icons8-report-card-64.png') }}" alt="laporan" width="20" height="20">Laporan Sarana</a>
          <a class="dropdown-item" href="{{ url('sl_pot') }}">
            <img src="{{ asset('icons8-report-card-64.png') }}" alt="laporan" width="20" height="20">Laporan SL POT</a>
          <a class="dropdown-item" href="{{ url('sl_omi') }}">
            <img src="{{ asset('icons8-report-card-64.png') }}" alt="laporan" width="20" height="20">Laporan SL OMI</a>
          <a class="dropdown-item" href="{{ url('pajak_omi') }}">
            <img src="{{ asset('icons8-report-card-64.png') }}" alt="laporan" width="20" height="20">Laporan Pajak OMI</a>
          <a class="dropdown-item" href="{{ url('rekap_npb') }}">
            <img src="{{ asset('icons8-report-card-64.png') }}" alt="laporan" width="20" height="20">Rekap NPB/NPT</a>
          <a class="dropdown-item" href="{{ url('upload_eproc') }}">
            <img src="{{ asset('icons8-upload-64.png') }}" alt="upload" width="20" height="20">Upload PB e-Proc</a>
          <a class="dropdown-item" href="{{ url('brokoli') }}">
            <img src="{{ asset('icons8-basket-48.png') }}" alt="cont" width="20" height="20">Jumlah Bronjong</a>
        </div>
      </div>
    </li>

    {{-- Menu Master --}}
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle font-weight-bold text-white" href="#" id="masterDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        MASTER
      </a>
      <div class="dropdown-menu dropdown-menu-right shadow-lg border-0 rounded-lg p-2 modern-dropdown" aria-labelledby="masterDropdown">
        <div class="dropdown-scroll">
          <h6 class="dropdown-header text-muted text-center border-bottom pb-2">TOKO</h6>
          <a class="dropdown-item" href="{{ url('list_idm') }}">
            <img src="{{ asset('icons8-store-100.png') }}" alt="IDM" width="20" height="20">IDM</a>
          <a class="dropdown-item" href="{{ url('list_omi') }}">
            <img src="{{ asset('icons8-store-1002.png') }}" alt="OMI" width="20" height="20">OMI</a>
          <h6 class="dropdown-header text-muted text-center border-bottom pb-2">ALAMAT</h6>
          <a class="dropdown-item" href="{{ url('plu_dpd') }}">
            <img src="{{ asset('icons8-rack-48.png') }}" alt="rak" width="20" height="20">RAK DPD</a> 
          <a class="dropdown-item" href="{{ url('plu_dpdv2') }}">
            <img src="{{ asset('icons8-rack-48.png') }}" alt="rak" width="20" height="20">RAK DPD V2</a> 
          <a class="dropdown-item" href="{{ url('plu_omi') }}">
            <img src="{{ asset('icons8-rack-48.png') }}" alt="rak" width="20" height="20">DPD OMI</a>
          <a class="dropdown-item" href="{{ url('plu_gudang') }}">
            <img src="{{ asset('icons8-rack-48.png') }}" alt="rak" width="20" height="20">PLU GUDANG</a>
          <h6 class="dropdown-header text-muted text-center border-bottom pb-2">STOCK</h6>
          <a class="dropdown-item" href="{{ url('stock_eko') }}">
            <img src="{{ asset('icons8-growing-money-48.png') }}" alt="eko" width="20" height="20">Stock Ekonomis</a>
          <a class="dropdown-item" href="{{ url('stock_pot') }}">
            <img src="{{ asset('icons8-stock-48.png') }}" alt="stok" width="20" height="20">Stock POT</a>
          <a class="dropdown-item" href="{{ url('stock_50') }}">
            <img src="{{ asset('icons8-stock-48.png') }}" alt="stok" width="20" height="20">Stock < 50 %</a>
          <a class="dropdown-item" href="{{ url('so_issuing') }}">
            <img src="{{ asset('icons8-edit-property-48.png') }}" alt="so" width="20" height="20">SO Issuing</a>
          <a class="dropdown-item" href="{{ url('dsi_eproc') }}">
            <img src="{{ asset('icons8-d-67.png') }}" alt="dsi" width="20" height="20">DSI e-Proc</a>
          <a class="dropdown-item" href="{{ url('item_tidakretur') }}">
            <img src="{{ asset('icons8-reject-48.png') }}" alt="reject" width="20" height="20">Item Tidak Bisa Retur IDM</a>
          <a class="dropdown-item" href="{{ url('lpp_nol') }}">
            <img src="{{ asset('icons8-stock-48.png') }}" alt="stok" width="20" height="20">LPP Nol Ada Plano</a>
          <a class="dropdown-item" href="{{ url('pb_mentahidm') }}">
            <img src="{{ asset('icons8-stock-96.png') }}" alt="stok" width="20" height="20">PB MENTAH IDM (SEBELUM UPLOAD)</a>
          <a class="dropdown-item" href="{{ url('pb_mentahomi') }}">
            <img src="{{ asset('icons8-stock-96.png') }}" alt="stok" width="20" height="20">PB MENTAH OMI (SEBELUM UPLOAD)</a>
        </div>
      </div>
    </li>

    {{-- Menu Monitoring --}}
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle font-weight-bold text-white" href="#" id="monitoringDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        MONITORING
      </a>
      <div class="dropdown-menu dropdown-menu-right shadow-lg border-0 rounded-lg p-2 modern-dropdown" aria-labelledby="monitoringDropdown">
        <div class="dropdown-scroll">
          <h6 class="dropdown-header text-muted text-center border-bottom pb-2">PBSL TODAY</h6>
          <a class="dropdown-item" href="{{ url('pb_idm') }}">
            <img src="{{ asset('icons8-sales-64.png') }}" alt="SL" width="20" height="20">IDM</a>
          <a class="dropdown-item" href="{{ url('pb_omi') }}">
            <img src="{{ asset('icons8-sales-64.png') }}" alt="SL" width="20" height="20">OMI</a>
          <h6 class="dropdown-header text-muted text-center border-bottom pb-2">WT & SPH</h6>
          <a class="dropdown-item" href="{{ url('wt_idm') }}">
            <img src="{{ asset('icons8-sales-100.png') }}" alt="sales" width="20" height="20">WT IDM</a>
          <a class="dropdown-item" href="{{ url('sph_omi') }}">
            <img src="{{ asset('icons8-sales-100.png') }}" alt="sales" width="20" height="20">SPH OMI</a>
          <h6 class="dropdown-header text-muted text-center border-bottom pb-2">PICKING, SCANNING & LOADING</h6>
          <a class="dropdown-item" href="{{ url('time_picking') }}">
            <img src="{{ asset('icons8-time-48.png') }}" alt="time" width="20" height="20">Time Picking</a>
          <a class="dropdown-item" href="{{ url('m_picking') }}">
            <img src="{{ asset('icons8-monitoring-48.png') }}" alt="monitoring" width="20" height="20">Monitoring Picking</a>
          <a class="dropdown-item" href="{{ url('m_dspb') }}">
            <img src="{{ asset('icons8-monitoring-48.png') }}" alt="monitoring" width="20" height="20">Monitoring DSPB</a>
          {{-- <a class="dropdown-item" href="{{ url('rph_rit_nopick') }}">
            <i class="fas fa-clipboard-list"></i>Rph, Rit & Nopick</a>
          <a class="dropdown-item" href="{{ url('dspbs') }}">
            <i class="fas fa-file-invoice"></i>DSPB Sementara</a> --}}
          <a class="dropdown-item" href="{{ url('jampick') }}">
            <img src="{{ asset('icons8-clock-120.png') }}" alt="clock" width="20" height="20">Jam Picking per Zona</a>
          <a class="dropdown-item" href="{{ url('koli') }}">
            <img src="{{ asset('icons8-basket-48.png') }}" alt="cont" width="20" height="20">Item & Status Barcode Koli</a>
          <a class="dropdown-item" href="{{ url('pickinghh') }}">
            <img src="{{ asset('icons8-basket-48.png') }}" alt="cont" width="20" height="20">PICKING HH ALDI</a>
          <a class="dropdown-item" href="{{ url('dbnull') }}">
            <img src="{{ asset('icons8-report-card-64.png') }}" alt="time" width="20" height="20">Cek DBNULL OMI (HARGA JUAL)</a>
        </div>
      </div>
    </li>

    {{-- Menu Seasonal --}}
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle font-weight-bold text-white" href="#" id="inventoryDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        INVENTORY
      </a>
      <div class="dropdown-menu dropdown-menu-right shadow-lg border-0 rounded-lg p-2 modern-dropdown" aria-labelledby="inventoryDropdown">
        <div class="dropdown-scroll">
          <a class="dropdown-item" href="{{ url('inquiry') }}">
            <img src="{{ asset('icons8-sakura-48.png') }}" alt="Inventory" width="20" height="20">Inquiry Product</a>
          <a class="dropdown-item" href="{{ url('plano') }}">
            <img src="{{ asset('icons8-sakura-48.png') }}" alt="Plano" width="20" height="20">Plano (Minus & Plus)</a>
        </div>
      </div>
    </li>

    {{-- Menu Retur --}}
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle font-weight-bold text-white" href="#" id="returDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        RETUR
      </a>
      <div class="dropdown-menu dropdown-menu-right shadow-lg border-0 rounded-lg p-2 modern-dropdown" aria-labelledby="returDropdown">
        <div class="dropdown-scroll">
          <a class="dropdown-item" href="{{ url('bpbr') }}">
            <img src="{{ asset('icons8-report-card-64.png') }}" alt="laporan" width="20" height="20">Laporan BPBR</a>
          <a class="dropdown-item" href="{{ url('returidm') }}">
            <img src="{{ asset('icons8-report-card-64.png') }}" alt="laporan" width="20" height="20">Retur IDM</a>
          <a class="dropdown-item" href="{{ url('outstanding_retur') }}">
            <img src="{{ asset('icons8-historical-64.png') }}" alt="pasir" width="20" height="20">Outstanding Retur</a>
          <a class="dropdown-item" href="{{ url('retur_proforma') }}">
            <img src="{{ asset('icons8-proforma-invoice-64.png') }}" alt="proforma" width="20" height="20">Retur Proforma</a>
          <a class="dropdown-item" href="{{ url('ba_toko') }}">
            <img src="{{ asset('icons8-report-100.png') }}" alt="report" width="20" height="20">BA Toko</a>
          <a class="dropdown-item" href="{{ url('piutang_retur') }}">
           <img src="{{ asset('icons8-payment-64.png') }}" alt="piutang" width="20" height="20">Cek Piutang Retur</a>
          <a class="dropdown-item" href="{{ url('cekacost') }}">
           <img src="{{ asset('icons8-report-card-64.png') }}" alt="cekacost" width="20" height="20">Cek Acost 0</a>
          <a class="dropdown-item" href="{{ url('cekjampick') }}">
            <img src="{{ asset('icons8-time-48.png') }}" alt="time" width="20" height="20">Cek Jam Picking</a>
          <a class="dropdown-item" href="{{ url('cekabsenretur') }}">
            <img src="{{ asset('icons8-report-card-64.png') }}" alt="time" width="20" height="20">Cek User Absen</a>
          <a class="dropdown-item" href="{{ url('bpbrtoday') }}">
           <img src="{{ asset('icons8-report-card-64.png') }}" alt="cekacost" width="20" height="20">Laporan Harian BPBR</a>
        </div>
      </div>
    </li>

    {{-- Menu Seasonal --}}
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle font-weight-bold text-white" href="#" id="problemDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        SEASONAL
      </a>
      <div class="dropdown-menu dropdown-menu-right shadow-lg border-0 rounded-lg p-2 modern-dropdown" aria-labelledby="problemDropdown">
        <div class="dropdown-scroll">
          <a class="dropdown-item" href="{{ url('laporan_seasonal') }}">
            <img src="{{ asset('icons8-sakura-48.png') }}" alt="Seasonal" width="20" height="20">Laporan Seasonal</a>
          <a class="dropdown-item" href="{{ url('alokasi_seasonal') }}">
            <img src="{{ asset('icons8-sakura-48.png') }}" alt="Seasonal" width="20" height="20">Alokasi Besok</a>
          <a class="dropdown-item" href="{{ url('alokasi_belum') }}">
            <img src="{{ asset('icons8-sakura-48.png') }}" alt="Seasonal" width="20" height="20">Alokasi Belum Terpenuhi</a>
        </div>
      </div>
    </li>

    {{-- Menu Problem --}}
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle font-weight-bold text-white" href="#" id="problemDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        PROBLEM
      </a>
      <div class="dropdown-menu dropdown-menu-right shadow-lg border-0 rounded-lg p-2 modern-dropdown" aria-labelledby="problemDropdown">

        <div class="dropdown-scroll">
          <a class="dropdown-item" href="{{ url('https://drive.google.com/drive/folders/1cZtw0qtAog6hUn1fhgl7p5UEQaD-KB1a') }}">
            <img src="{{ asset('icons8-problem-48.png') }}" alt="problem" width="20" height="20">List Masalah</a>
          <a class="dropdown-item" href="{{ url('double_dspb') }}">
            <img src="{{ asset('icons8-problem-48.png') }}" alt="problem" width="20" height="20">Cek Double DSPB</a>
          <a class="dropdown-item" href="{{ url('double_piutang') }}">
            <img src="{{ asset('icons8-problem-48.png') }}" alt="problem" width="20" height="20">Cek Double Piutang</a>
          <a class="dropdown-item" href="{{ url('gagal_npb') }}">
            <img src="{{ asset('icons8-problem-48.png') }}" alt="problem" width="20" height="20">Cek Gagal NPB/NPT</a>
          <a class="dropdown-item" href="{{ url('npb_tidakterbentuk') }}">
            <img src="{{ asset('icons8-problem-48.png') }}" alt="problem" width="20" height="20">Cek NPB/NPT tidak terbentuk</a>
        </div>

      </div>
    </li>

    {{-- Menu EDP --}}
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle font-weight-bold text-white" href="#" id="EDPDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        EDP
      </a>
      <div class="dropdown-menu dropdown-menu-right shadow-lg border-0 rounded-lg p-2 modern-dropdown" aria-labelledby="EDPDropdown">

        <div class="dropdown-scroll">
          <h6 class="dropdown-header text-muted text-center border-bottom pb-2">LAPORAN PAGI</h6>
          <a class="dropdown-item" href="{{ url('data_pbidm') }}">LAPORAN DATA PB IDM</a>
          <a class="dropdown-item" href="{{ url('itemcp1') }}">LAPORAN ITEM CP1</a>
          <h6 class="dropdown-header text-muted text-center border-bottom pb-2">LAPORAN MALAM</h6>
          <a class="dropdown-item" href="{{ url('tolakan_idm') }}">LAPORAN TOLAKAN PB HARIAN</a>
          {{-- <a class="dropdown-item" href="{{ url('pbconverter') }}">PB IDM ENCRYPT/DECRYPT</a> --}}
        </div>

      </div>
    </li>

    {{-- Menu WEB HO --}}
    <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle font-weight-bold text-white" href="#" id="externalDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      WEB EXTERNAL
    </a>
    <div class="dropdown-menu dropdown-menu-right shadow-lg border-0 rounded-lg p-2 modern-dropdown" aria-labelledby="externalDropdown">
      

      <div class="dropdown-scroll">
        <h6 class="dropdown-header text-muted text-center border-bottom pb-2">HO</h6>
        <a class="dropdown-item" href="http://172.20.30.3/ess/homeportal/login"><i class="fas fa-house-user text-primary mr-2"></i> ESS</a>
        <a class="dropdown-item" href="https://portal.hrindomaret.com/"><i class="fas fa-house-user text-success mr-2"></i> PORTAL HR</a>
        <a class="dropdown-item" href="http://172.20.30.3/tsm/"><i class="fas fa-house-user text-info mr-2"></i> TSM</a>
        <a class="dropdown-item" href="https://172.20.30.36:4001/auth/login"><i class="fas fa-house-user text-danger mr-2"></i> WEB FINGER</a>
        <h6 class="dropdown-header text-muted text-center border-bottom pb-2">WEB UTAMA</h6>
        <a class="dropdown-item" href="http://192.168.83.190:81/login"><i class="fas fa-house-user text-warning mr-2"></i> IAS</a>
        <a class="dropdown-item" href="http://172.20.30.36/select-postgre/public/select-postgre/login"><i class="fas fa-house-user text-secondary mr-2"></i> OTP</a>
        <h6 class="dropdown-header text-muted text-center border-bottom pb-2">WEBSERVICE</h6>
        <a class="dropdown-item" href="http://192.168.83.197/HHWS/servicehandheld.asmx"><i class="fas fa-house-user text-primary mr-2"></i> HHWS</a>
        <a class="dropdown-item" href="http://192.168.83.197/PBROWebService/Service.asmx"><i class="fas fa-house-user text-success mr-2"></i> PBRO</a>
        <a class="dropdown-item" href="http://192.168.83.197/WTWebService/ServiceWT.asmx"><i class="fas fa-house-user text-warning mr-2"></i> WT</a>
        <a class="dropdown-item" href="http://172.31.69.134:7109/"><i class="fas fa-house-user text-dark mr-2"></i> RO API</a>
        <a class="dropdown-item" href="http://172.31.68.33:1414/service.asmx"><i class="fas fa-house-user text-primary mr-2"></i> NPB</a>
        <a class="dropdown-item" href="http://192.168.83.198:5000/IGRHHWS/index.html"><i class="fas fa-house-user text-danger mr-2"></i> SWAGGER / LOADING MOBIL IDM</a>
        <a class="dropdown-item" href="http://appapi1.indomaret.lan:8800/api/Master/GET_DATA_TOKO_WS%7CGI47%7CGI47"><i class="fas fa-house-user text-warning mr-2"></i> MASTER SUPPLY IDM</a>
        <a class="dropdown-item" href="https://apipartner.indopaket.co.id/ServiceExt.asmx"><i class="fas fa-house-user text-primary mr-2"></i> INDOPAKET</a>
        <h6 class="dropdown-header text-muted text-center border-bottom pb-2">IDM</h6>
        <a class="dropdown-item" href="http://192.168.83.198/multirates-idm/public/"><i class="fas fa-house-user text-success mr-2"></i> MULTIRATES IDM</a>
        <a class="dropdown-item" href="http://192.168.83.198/seasonal-idm/public/"><i class="fas fa-house-user text-info mr-2"></i> SEASONAL IDM</a>
        <a class="dropdown-item" href="http://172.20.28.34:3000/monitoring-dspb-igr"><i class="fas fa-house-user text-danger mr-2"></i> MONITORING DSPB</a>
        <h6 class="dropdown-header text-muted text-center border-bottom pb-2">OMI</h6>
        <a class="dropdown-item" href="http://192.168.83.198/monitoring-bakp/public/"><i class="fas fa-house-user text-secondary mr-2"></i> BAKP OMI</a>
        <a class="dropdown-item" href="http://pareto-omi.omifranchise.co.id:8004/login"><i class="fas fa-house-user text-success mr-2"></i> PARETO OMI</a>
      </div>
    </div>
  </li>



  </ul>

  <!-- kanan -->
  <ul class="navbar-nav ml-auto d-flex align-items-center">
    <li class="nav-item">
      <a class="nav-link" href="#" id="themeToggle" title="Toggle Dark Mode">
        <img id="themeIcon" src="{{ asset('moon.png') }}" alt="Theme Icon" width="24" height="24" />
      </a>
    </li>
    {{-- <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <div class="user-avatar rounded-circle bg-primary text-white d-flex justify-content-center align-items-center mr-2"
             style="width: 36px; height: 36px; font-size: 14px;">
          <i class="fas fa-user"></i>
        </div>
        <span class="font-weight-bold text-uppercase">
          {{ session('username') ?? 'Guest' }}
        </span>
      </a>
      <div class="dropdown-menu dropdown-menu-right shadow-lg border-0 mt-2" aria-labelledby="userDropdown" style="border-radius: 12px; min-width: 200px; z-index: 1050;">
        <div class="dropdown-divider my-0"></div>
        <a href="{{ route('logout') }}" class="dropdown-item text-danger font-weight-bold d-flex align-items-center py-2">
          <i class="fas fa-door-open mr-2"></i> Logout
        </a>
      </div>
    </li> --}}
    <li class="nav-item">
        <a href="http://192.168.83.93:8081/login" class="nav-link text-danger font-weight-bold d-flex align-items-center py-2">
          <i class="fas fa-door-open mr-2"></i> Logout
        </a>
    </li>
  </ul>
</nav>

<!-- Content Wrapper -->
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <h1 class="m-0">@yield('content_title')</h1>
    </div>
  </div>
  <div class="content">
    <div class="container-fluid">
      @yield('content')
    </div>
  </div>
</div>

<!-- Footer -->
<footer class="main-footer">
  <strong>Copyright &copy;2025 by EDP-ISSUING.</strong> All rights reserved.
</footer>
</div>

{{-- In your layouts/app.blade.php file --}}
<script src="{{ asset('adminlte') }}/plugins/jquery/jquery.min.js"></script>
<script src="{{ asset('adminlte') }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('adminlte') }}/dist/js/adminlte.min.js"></script>

@stack('lib_scripts')  {{-- New stack for library scripts --}}

<!-- GANTI SCRIPT DI BAGIAN AKHIR SEBELUM </body> -->
<script>
  function setTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);
    updateThemeIcon(theme);
  }

  function updateThemeIcon(theme) {
    const icon = document.getElementById('themeIcon');
    if (!icon) return;
    if (theme === 'dark') {
      icon.src = 'sun.png';  // ikon untuk mode gelap
      icon.alt = 'Light Mode';
    } else {
      icon.src = 'moon.png'; // ikon untuk mode terang
      icon.alt = 'Dark Mode';
    }
  }

  function initTheme() {
    const savedTheme = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const theme = savedTheme || (prefersDark ? 'dark' : 'light');
    document.documentElement.setAttribute('data-theme', theme);
    updateThemeIcon(theme);
  }

  document.getElementById('themeToggle').addEventListener('click', function (e) {
    e.preventDefault();
    const currentTheme = document.documentElement.getAttribute('data-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    setTheme(newTheme);
  });

  initTheme();

  window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
    if (!localStorage.getItem('theme')) {
      setTheme(e.matches ? 'dark' : 'light');
    }
  });
</script>

@stack('scripts')   {{-- Old stack for custom page scripts --}}
</body>
</html>
