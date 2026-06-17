@extends('layouts.app')
@section('content')
@push('styles')
<link rel="stylesheet" href="{{ asset('adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

<style>
/* Gaya asli (tidak diubah) */
body {
  background-color: #f8f9fa;
  color: #212529;
  display: flex;
  flex-direction: column;
}

.card, .card-body, .card-header, .main-footer, .main-sidebar, .navbar, .content-wrapper {
  background-color: #ffffff;
  color: #212529;
}

.sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active {
  background-color: #e9ecef;
  color: #000;
}

.card {
  border: 1px solid #333 !important;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.card-header {
  background-color: #f8f9fa;
  font-weight: bold;
  border-bottom: 1px solid #333 !important;
}

.card-body {
  border-top: 1px solid #e0e0e0;
}

.nav-tabs .nav-link.active {
  font-weight: bold;
  border: 1px solid #333 !important;
  border-bottom-color: #fff !important;
}

.nav-tabs .nav-link {
  border: 1px solid #dee2e6;
  margin-right: 4px;
}

.tab-content {
  border: 1px solid #333 !important;
  padding: 1rem;
  border-top: none;
}

.small-box {
  position: relative;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  min-height: 340px;
  font-size: 1.2rem;
  padding: 25px 15px; /* tambahkan sedikit padding */
  text-align: center; /* agar konten rata tengah */
}

.small-box h3 {
  font-size: 1.9rem;      /* sedikit lebih kecil agar proporsional */
  font-weight: bold;
  margin-bottom: 0.5rem;
}

.small-box h4 {
  font-size: 1.4rem;      /* untuk angka toko */
  font-weight: 600;
  margin-bottom: 1rem;
}

.small-box .icon {
  font-size: 3.5rem;      /* besar tapi tidak melebihi box */
  margin: 10px 0;         /* ruang atas-bawah */
  opacity: 1;
}

.small-box-footer {
  font-size: 1rem;
  font-weight: 500;
  padding-top: 8px;
  padding-bottom: 4px;
  color: #fff;
}

.small-box-red-black {
  background: linear-gradient(135deg, #cb2d3e, #000000);
  border-radius: 12px;
  color: #fff;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.small-box-midnight-blue {
  background: linear-gradient(135deg, rgb(0, 170, 255), hsl(0, 0%, 0%));
  border-radius: 12px;
  color: #fff;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.small-box-rose-soft {
  background: linear-gradient(135deg, #fbc2eb, #a6c1ee);
  color: #212529;
}


@media (max-width: 768px) {
  .small-box {
    min-height: 220px;
    font-size: 1rem;
    padding: 15px 10px;
  }

  .small-box h3 {
    font-size: 1.4rem;
  }

  .small-box h4 {
    font-size: 1.1rem;
  }

  .small-box .icon {
    font-size: 2.5rem;
  }
}

.tanggal-besar {
  font-size: 1.25rem;
  font-weight: bold;
  color: #007bff;
  padding-top: 5px;        /* kurangin padding atas */
  margin-top: -4px;        /* atau naikkan pakai margin negatif */
  position: relative;
}
/* ======= SLIDE STYLE ======= */
.slider-container {
  overflow: hidden;
  width: 100%;
  transition: height 0.3s ease; /* animasi halus saat ganti slide */
}

.slider-wrapper {
  display: flex;
  transition: transform 0.5s ease-in-out;
  width: 200%;
}

.slide {
  flex: 0 0 100%;
  padding: 10px;
  box-sizing: border-box;
}

/* STICKY NAV BUTTONS */
#navButtons {
  position: fixed;
  bottom: 20px;       /* jarak dari bawah layar */
  left: 50%;
  transform: translateX(-50%);
  background: rgba(255,255,255,0.9);
  padding: 8px 15px;
  border-radius: 12px;
  box-shadow: 0 4px 8px rgba(0,0,0,0.15);
  z-index: 1050; /* pastikan di atas konten lain */
}

.card-body .table td, .card-body .table th {
    font-size: 1.1rem; /* Adjust this value as needed, e.g., 14px, 16px, 1rem */
}



/*Refresh */
  .refresh-card .refresh-btn {
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
    pointer-events: none;
  }
  .refresh-card:hover .refresh-btn {
    opacity: 1;
    pointer-events: auto;
  }
  /* Glow effect saat hover tombol */
  .refresh-btn:hover {
    box-shadow: 0 0 12px rgba(0, 123, 255, 0.8);
    transform: scale(1.05);
  }

  /* General pill style */
  .nav-pills .nav-link {
    color: #fff;
    border-radius: 50rem;
    padding: 0.5rem 1.2rem;
    font-weight: 500;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
  }

  /* Underline effect with glow */
  .nav-pills .nav-link::after {
    content: "";
    position: absolute;
    left: 50%;
    bottom: 6px;
    transform: translateX(-50%) scaleX(0);
    width: 60%;
    height: 3px;
    border-radius: 3px;
    background: rgba(255, 255, 255, 0.9);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  .nav-pills .nav-link:hover::after,
  .nav-pills .nav-link.active::after {
    transform: translateX(-50%) scaleX(1);
  }

  /* Glow color per tab */
  .nav-pills .nav-link.tab-all.active::after {
    background: #ff4d4d;
    box-shadow: 0 0 8px #ff4d4d, 0 0 16px #b30000;
  }
  .nav-pills .nav-link.tab-idm.active::after {
    background: #4da6ff;
    box-shadow: 0 0 8px #4da6ff, 0 0 16px #0052cc;
  }
  .nav-pills .nav-link.tab-omi.active::after {
    background: #33cc33;
    box-shadow: 0 0 8px #33cc33, 0 0 16px #006600;
  }

  /* All = Gradient Merah */
  .nav-pills .nav-link.tab-all {
    background: linear-gradient(135deg, #ff4d4d, #b30000);
  }
  .nav-pills .nav-link.tab-all.active {
    background: linear-gradient(135deg, #e60000, #660000);
    box-shadow: 0 4px 12px rgba(0,0,0,0.25);
  }

  /* IDM = Gradient Biru */
  .nav-pills .nav-link.tab-idm {
    background: linear-gradient(135deg, #4da6ff, #0052cc);
  }
  .nav-pills .nav-link.tab-idm.active {
    background: linear-gradient(135deg, #003d99, #001f4d);
    box-shadow: 0 4px 12px rgba(0,0,0,0.25);
  }

  /* OMI = Gradient Hijau */
  .nav-pills .nav-link.tab-omi {
    background: linear-gradient(135deg, #33cc33, #006600);
  }
  .nav-pills .nav-link.tab-omi.active {
    background: linear-gradient(135deg, #004d00, #001a00);
    box-shadow: 0 4px 12px rgba(0,0,0,0.25);
  }

  /* Hover effect */
  .nav-pills .nav-link:hover {
    opacity: 0.9;
    transform: translateY(-2px);
  }

  /* General button style */
  .btn-slide {
    color: #fff;
    border: none;
    border-radius: 50rem;
    padding: 0.6rem 1.6rem;
    margin: 0.3rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    cursor: pointer;
    background: linear-gradient(135deg, #00c6ff, #0072ff);
    box-shadow: 0 4px 12px rgba(0, 114, 255, 0.35);

  }

  /* Active & Hover */
  .btn-slide.active,
  .btn-slide:hover {
    background: linear-gradient(135deg, #2c5364, #203a43, #0f2027);
    transform: translateY(-3px) scale(1.02);
    box-shadow: 0 6px 16px rgba(32, 58, 67, 0.45);
    opacity: 0.98;
  }



</style>
@endpush


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

        <span class="nav-link d-flex align-items-center tanggal-besar">
          <i class="fas fa-calendar-alt mr-1"></i>
          <span>{{ $hariIni }}, {{ $tanggalLengkap }}</span>
        </span>
      </li>

<!-- === SLIDER CONTAINER === -->
<div class="slider-container">
  <div class="slider-wrapper" id="sliderWrapper">
    
    <!-- === SLIDE 1: DASHBOARD === -->
<div class="slide" data-loaded="true">
  <div class="container-fluid">
    <!-- === ROW 1: BOX STATUS & SL HARIAN === -->
    <div class="row">
      <!-- Kolom kiri: kotak status -->
      <div class="col-md-2">
        <div class="row">

          <!-- PB IDM -->
          <div class="col-md-6 mb-3">
            <div class="small-box small-box-midnight-blue h-100 d-flex flex-column justify-content-between text-center p-3">
              <div>
                <img src="{{ asset('icons8-store-100.png') }}" alt="IDM" width="120" height="120">
                <h3 class="mb-1 fw-bold">PB IDM</h3>
                <p class="mb-3" style="font-size: 1.2rem;">{{ $pbIdm->tkopbidm }} TOKO</p>
              </div>
              <a href="{{ url('pb_idm') }}" class="small-box-footer text-white fw-semibold">
                More info <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>

          <!-- PB OMI -->
          <div class="col-md-6 mb-3">
            <div class="small-box small-box-midnight-blue h-100 d-flex flex-column justify-content-between text-center p-3">
                <div>
                <img src="{{ asset('icons8-store-1002.png') }}" alt="IDM" width="120" height="120">
                <h3 class="mb-1 fw-bold">PB OMI</h3>
                <p class="mb-3" style="font-size: 1.2rem;">{{ $pbOmi->tkopbomi }} TOKO</p>
              </div>
              <a href="{{ url('pb_omi') }}" class="small-box-footer text-white fw-semibold">
                More info <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>
          
          <!-- WT INTRANSIT -->
          <div class="col-md-6 mb-3">
            <div class="small-box small-box-red-black h-100 d-flex flex-column justify-content-between text-center p-3">
                <div>
                <img src="{{ asset('icons8-sales-100.png') }}" alt="sales" width="120" height="120">
                <h3 class="mb-1 fw-bold">WT INTRANSIT</h3>
                <p class="mb-3" style="font-size: 1.2rem;">{{ $wtSales->wtpending }} WT</p>
              </div>
              <a href="{{ url('wt_idm') }}" class="small-box-footer text-white fw-semibold">
                More info <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>
          <!-- SPH PENDING -->
          <div class="col-md-6 mb-3">
            <div class="small-box small-box-red-black h-100 d-flex flex-column justify-content-between text-white text-center p-3">
              <div>
                <img src="{{ asset('icons8-sales-100.png') }}" alt="sales" width="120" height="120">
                <h3 class="mb-1 fw-bold">SPH INTRANSIT</h3>
                <p class="mb-3" style="font-size: 1.2rem;">{{ $sphPending->sphpending }} SPH</p>
              </div>
              <a href="{{ url('sph_omi') }}" class="small-box-footer text-white fw-semibold">
                More info <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>

        </div>
      </div>

      <!-- Kolom kanan: SL Harian -->
      <div class="col-md-4">
        <div class="card border-dark w-100">
          <div class="card-header small-box-issuing-header text-white rounded-top">
            <h5 class="mb-0"><img src="{{ asset('icons8-sales-1020.png') }}" alt="SL" width="30" height="30"> SERVICE LEVEL HARIAN</h5>
          </div>

           <!-- Floating Refresh Button -->
          <button class="btn btn-primary btn-sm rounded-circle shadow position-absolute refresh-btn"
                  style="top:10px; right:10px; z-index:10;"
                  data-target="slharian"
                  title="Refresh SL Harian">
            <i class="fas fa-sync-alt"></i>
            <div class="spinner-border spinner-border-sm text-light d-none" role="status"></div>
          </button>


          <div class="card-body p-0" id="slharian-container">
            @include('dashboard.partials.slharian', [
                'servicelevelall' => $servicelevelall,
                'servicelevelidm' => $servicelevelidm,
                'servicelevelomi' => $servicelevelomi
            ])
          </div>

        </div>
      </div>
    </div> <!-- End Row -->

  </div> <!-- End Container -->
</div> <!-- End Slide 1 -->


    <!-- === SLIDE 2: RUPIAH === -->
<div class="slide" data-loaded="false">
  <div class="container-fluid">
    <div class="row">

      <div class="col-md-6">
        <div class="card border-dark w-100">
          <div class="card-header small-box-issuing-header text-white rounded-top">
            <h5 class="mb-0"><img src="{{ asset('icons8-dollar-100.png') }}" alt="SL" width="35" height="35"> RUPIAH PICKING</h5></div>
            <!-- Floating Refresh Button -->
            <button class="btn btn-primary btn-sm rounded-circle shadow position-absolute refresh-btn"
                    style="top:10px; right:10px; z-index:10;"
                    data-target="rupiahPicking"
                    title="Refresh Rupiah Picking">
              <i class="fas fa-sync-alt"></i>
              <div class="spinner-border spinner-border-sm text-light d-none" role="status"></div>
            </button>
          <div class="card-body p-0" id="rupiah-table-container">
            <ul class="nav nav-pills mb-3" role="tablist">
              <li class="nav-item me-2">
                <a class="nav-link active tab-all" data-toggle="tab" href="#rphall" role="tab">ALL</a>
              </li>
              <li class="nav-item me-2">
                <a class="nav-link tab-idm" data-toggle="tab" href="#rphidm" role="tab">IDM</a>
              </li>
              <li class="nav-item">
                <a class="nav-link tab-omi" data-toggle="tab" href="#rphomi" role="tab">OMI</a>
              </li>
            </ul>

            <div class="tab-content" id="rupiahTabContent">

            </div>
          </div>
        </div>
      </div>
    </div> <!-- End Row Rupiah Picking -->
      
  </div> <!-- container-fluid -->
</div> <!-- End Slide 2 -->

<!-- === SLIDE 3: MONITORING PICKING === -->
<div class="slide" data-loaded="false">
  <div class="container-fluid">
    <div class="row">
      
      <div class="col-md-6">
        <div class="card border-dark h-100">
          <div class="card-header small-box-issuing-header text-white rounded-top">
            <h5 class="mb-0"><img src="{{ asset('icons8-monitoring-48.png') }}" alt="monitoring" width="40" height="40"> MONITORING PICKING & SCANNING</h5>
          </div>
          <!-- Floating Refresh Button -->
          <button class="btn btn-primary btn-sm rounded-circle shadow position-absolute refresh-btn"
                  style="top:10px; right:10px; z-index:10;"
                  data-target="monitoringPicking"
                  title="Refresh Monitoring Picking">
            <i class="fas fa-sync-alt"></i>
            <div class="spinner-border spinner-border-sm text-light d-none" role="status"></div>
          </button>
          <div class="card-body p-0" id="picking-table-container">
            {{-- Konten akan dimuat di sini --}}
          </div>
        </div>
      </div>
    </div> <!-- row -->
  </div> <!-- container-fluid -->
</div> <!-- End Slide 3 -->

<!-- === SLIDE 4: MONITORING LOADING === -->
<div class="slide" data-loaded="false">
  <div class="container-fluid">
    <div class="row">
      
      <div class="col-md-6">
        <div class="card border-dark w-100">
          <div class="card-header small-box-issuing-header text-white rounded-top">
            <h5 class="mb-0"><img src="{{ asset('icons8-monitoring-48.png') }}" alt="monitoring" width="40" height="40"> MONITORING LOADING</h5>
          </div>
          <!-- Floating Refresh Button -->
          <button class="btn btn-primary btn-sm rounded-circle shadow position-absolute refresh-btn"
                  style="top:10px; right:10px; z-index:10;"
                  data-target="monitoringLoading"
                  title="Refresh Monitoring Loading">
            <i class="fas fa-sync-alt"></i>
            <div class="spinner-border spinner-border-sm text-light d-none" role="status"></div>
          </button>
          <div class="card-body p-0" id="loading-table-container">
            {{-- Konten akan dimuat di sini --}}
        </div>
      </div>
    </div> <!-- row -->
  </div> <!-- container-fluid -->
</div>

  </div> <!-- End slider-wrapper -->
</div> <!-- End slider-container -->

<!-- === BUTTON UNTUK SLIDE === -->
<div id="navButtons" class="text-center mt-4">
  <button id="btnDashboard" class="btn-slide active" onclick="goToSlide(0)">DASHBOARD</button>
  <button id="btnRupiah" class="btn-slide" onclick="goToSlide(1)">RUPIAH PICKING</button>
  <button id="btnPicking" class="btn-slide" onclick="goToSlide(2)">MONITORING PICKING & SCANNING</button>
  <button id="btnLoading" class="btn-slide" onclick="goToSlide(3)">MONITORING LOADING</button>
</div>

@push('scripts')
<script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/moment/locale/id.js') }}"></script>
<script src="{{ asset('adminlte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>


<script>
function goToSlide(index) {
  const slider = document.getElementById('sliderWrapper');
  const slides = document.querySelectorAll('#sliderWrapper .slide');
  const container = document.querySelector('.slider-container');

  slider.style.transform = `translateX(-${index * 100}%)`;

  // Update tombol aktif
  const buttons = [
    document.getElementById('btnDashboard'),
    document.getElementById('btnRupiah'),
    document.getElementById('btnPicking'),
    document.getElementById('btnLoading'),
  ];
  buttons.forEach((btn, i) => {
    btn.classList.toggle('active', i === index);
  });

  const activeSlide = slides[index];
  container.style.height = activeSlide.scrollHeight + "px";

  // Lazy load
  if (activeSlide.dataset.loaded === 'false') {
    loadSlideData(index, activeSlide);
    activeSlide.dataset.loaded = 'true';
  }
}

function loadSlideData(index, slideElement) {
  let url;
  let targetId;

  if (index === 1) { // Rupiah Picking
    url = "{{ url('kemarin/get-rupiah-picking2-data') }}";
    targetId = '#rupiahTabContent';
  } else if (index === 2) { // Monitoring Picking
    url = "{{ url('kemarin/get-monitor-picking2-data') }}";
    targetId = '#picking-table-container';
  } else if (index === 3) { // Monitoring Loading
    url = "{{ url('kemarin/get-monitor-loading2-data') }}";
    targetId = '#loading-table-container';
  } else {
    return;
  }

  $.ajax({
    url: url,
    method: 'GET',
    beforeSend: function() {
      $(targetId).html('<div class="text-center p-5"><i class="fa fa-spinner fa-spin fa-3x"></i><br>Loading...</div>');
    },
    success: function(response) {
      $(targetId).html(response.html);

      // Inisialisasi ulang tabel sesuai target
      if (index === 1) initRupiahTables();
      if (index === 0) initSlHarianTables();

      const newHeight = slideElement.scrollHeight + "px";
      $('.slider-container').css('height', newHeight);
    },
    error: function(xhr) {
      console.error("Error loading data:", xhr);
      $(targetId).html('<div class="text-center text-danger p-5">Gagal memuat data.</div>');
    }
  });
}

// === Fungsi init DataTables ===

// SL Harian
function initSlHarianTables() {
  $('#datatable_all, #datatable_idm, #datatable_omi').each(function () {
    if ($.fn.DataTable.isDataTable(this)) {
      $(this).DataTable().destroy(); // hancurkan dulu
    }
    $(this).DataTable({
      columnDefs: [
        { targets: 0, visible: false }, // kolom tanggal_sort hidden
        { targets: 1, orderData: 0 }    // kolom tanggal ikut urut dari kolom 0
      ],
      order: [[0, 'desc']],
      pageLength: 7,
      lengthChange: false,
      language: {
        url: "{{ asset('adminlte/plugins/datatables/i18n/id.json') }}"
      },
      dom: '<"d-flex justify-content-start align-items-center mb-2"B>rtip',
      buttons: [
        {
          extend: 'copy',
          text: '<i class="fas fa-copy"></i> Copy',
          className: 'btn btn-light btn-sm border-primary text-primary'
        }
      ]
    });
  });
}

// Rupiah Picking
function initRupiahTables() {
  $('#rupiahTabContent table').each(function () {
    if ($.fn.DataTable.isDataTable(this)) {
      $(this).DataTable().destroy();
    }
    $(this).DataTable({
      order: [[0, 'asc']],
      paging: false,
      searching: true,
      info: false,
      language: {
        url: "{{ asset('adminlte/plugins/datatables/i18n/id.json') }}"
      },
      dom: '<"d-flex justify-content-start align-items-center mb-2"B>rtip',
      buttons: [
        {
          extend: 'copy',
          text: '<i class="fas fa-copy"></i> Copy',
          className: 'btn btn-light btn-sm border-primary text-primary',
          exportOptions: {
            columns: ':visible',
            footer: true   // ✅ ikut sertakan tfoot
          }
        }
      ]
    });
  });
}

$(document).ready(function () {
  // Set slide awal
  goToSlide(0);

  // Resize responsif
  window.addEventListener('resize', () => {
    const activeIndex = [...document.querySelectorAll('#navButtons button')]
      .findIndex(btn => btn.classList.contains('btn-primary'));
    goToSlide(activeIndex >= 0 ? activeIndex : 0);
  });

  // Floating Refresh Button
  $('.refresh-btn').on('click', function () {
    let $btn = $(this);
    let target = $btn.data('target');
    let $spinner = $btn.find('.spinner-border');
    let $icon = $btn.find('.fa-sync-alt');

    $spinner.removeClass('d-none');
    $icon.addClass('d-none');
    $btn.prop('disabled', true);

    if (target === "slharian") {
      $.ajax({
        url: "{{ url('/kemarin/get-sl-harian2-data') }}",
        type: "GET",
        success: function(response) {
          $('#slharian-container').html(response.html);
          initSlHarianTables(); // init ulang setelah reload
        },
        complete: function() {
          $spinner.addClass('d-none');
          $icon.removeClass('d-none');
          $btn.prop('disabled', false);
        }
      });

    } else {
      // Untuk target lainnya
      let url = "";
      if (target === "monitoringPicking") url = "{{ url('/kemarin/get-monitor-picking2-data') }}";
      if (target === "rupiahPicking") url = "{{ url('/kemarin/get-rupiah-picking2-data') }}";
      if (target === "monitoringLoading") url = "{{ url('/kemarin/get-monitor-loading2-data') }}";

      $.ajax({
        url: url,
        type: "GET",
        success: function (response) {
          if (target === "monitoringPicking") $('#picking-table-container').html(response.html);
          if (target === "rupiahPicking") {
            $('#rupiahTabContent').html(response.html);
            initRupiahTables();
          }
          if (target === "monitoringLoading") $('#loading-table-container').html(response.html);
        },
        error: function () {
          alert("Gagal refresh data " + target);
        },
        complete: function () {
          $spinner.addClass('d-none');
          $icon.removeClass('d-none');
          $btn.prop('disabled', false);
        }
      });
    }
  });

  // Init DataTable untuk SL Harian pertama kali
  initSlHarianTables();
});

</script>


@endpush

@endsection
