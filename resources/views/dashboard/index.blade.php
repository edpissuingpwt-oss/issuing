@extends('layouts.app')
@section('content')
@push('styles')
<link rel="stylesheet" href="{{ asset('adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
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
                <p><h3 class="animated-title mb-1 fw-bold">PB IDM</h3></p>
                <p class="mb-3" style="font-size: 1.2rem;">
                  <span id="pbidm-count">{{ $pbIdm->tkopbidm }}</span>
                </p>
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
                <p><h3 class="animated-title mb-1 fw-bold">PB OMI</h3></p>
                <p class="mb-3" style="font-size: 1.2rem;"><span id="pbomi-count">{{ $pbOmi->tkopbomi }}</span></p>
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
                <p><h3 class="animated-title mb-1 fw-bold">WT INTRANSIT</h3></p>
                <p class="mb-3" style="font-size: 1.2rem;"><span id="wt-count">{{ $wtSales->wtpending }}</span></p>
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
                <p><h3 class="animated-title mb-1 fw-bold">SPH INTRANSIT</h3></p>
                <p class="mb-3" style="font-size: 1.2rem;"><span id="sph-count">{{ $sphPending->sphpending }} </span></p>
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
            <h5 class="mb-0"><img src="{{ asset('icons8-monitoring-48.png') }}" class="icon-monitoring pulse" alt="monitoring" width="40" height="40"> MONITORING PICKING & SCANNING</h5>
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
    url = "{{ url('dashboard/get-rupiah-picking-data') }}";
    targetId = '#rupiahTabContent';
  } else if (index === 2) { // Monitoring Picking
    url = "{{ url('dashboard/get-monitor-picking-data') }}";
    targetId = '#picking-table-container';
  } else if (index === 3) { // Monitoring Loading
    url = "{{ url('dashboard/get-monitor-loading-data') }}";
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
        url: "{{ url('/dashboard/get-sl-harian-data') }}",
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
      if (target === "monitoringPicking") url = "{{ url('/dashboard/get-monitor-picking-data') }}";
      if (target === "rupiahPicking") url = "{{ url('/dashboard/get-rupiah-picking-data') }}";
      if (target === "monitoringLoading") url = "{{ url('/dashboard/get-monitor-loading-data') }}";

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

    // =========================
  function getActiveSlide(){

    let buttons = document.querySelectorAll('#navButtons .btn-slide');

    for(let i=0;i<buttons.length;i++){

      if(buttons[i].classList.contains('active')){
        return i;
      }

    }

    return 0;

  }


  // =========================
  // AUTO REFRESH FUNCTIONS
  // =========================

  // function autoRefreshSLHarian(){

  //   $.ajax({

  //     url: "{{ url('/dashboard/get-sl-harian-data') }}",
  //     type: "GET",

  //     success: function(response){

  //       $('#slharian-container').html(response.html);
  //       initSlHarianTables();

  //     }

  //   });

  // }


  // function autoRefreshRupiah(){

  //   $.ajax({

  //     url: "{{ url('/dashboard/get-rupiah-picking-data') }}",
  //     type: "GET",

  //     success: function(response){

  //       $('#rupiahTabContent').html(response.html);
  //       initRupiahTables();

  //     }

  //   });

  // }


  function autoRefreshMonitoringPicking(){

    $.ajax({

      url: "{{ url('/dashboard/get-monitor-picking-data') }}",
      type: "GET",

      success: function(response){

        $('#picking-table-container').html(response.html);

      }

    });

  }


  function autoRefreshMonitoringLoading(){

    $.ajax({

      url: "{{ url('/dashboard/get-monitor-loading-data') }}",
      type: "GET",

      success: function(response){

        $('#loading-table-container').html(response.html);

      }

    });

  }


  function startAutoRefresh() {
  // Fungsi utama yang akan dijalankan berulang
    async function refreshCycle() {
      const activeSlide = getActiveSlide();

      try {
        // if (activeSlide === 0) {
        //   await autoRefreshSLHarian();   // tambah await jika fungsinya async
        // } else if (activeSlide === 1) {
        //   await autoRefreshRupiah();
        if (activeSlide === 2) {
          await autoRefreshMonitoringPicking();
        } else if (activeSlide === 3) {
          await autoRefreshMonitoringLoading();
        }
      } catch (err) {
        console.error("Error di auto refresh:", err);
        // opsional: bisa kirim ke monitoring / sentry di sini
      }

      // Jadwalkan ulang SETELAH selesai (termasuk jika error)
      setTimeout(refreshCycle, 5000);
    }

    // Mulai loop pertama
    refreshCycle();
  }

  // panggil sekali saja (misal saat halaman load atau slide pertama muncul)
  startAutoRefresh();

  //dashboard

  // Fungsi helper untuk animasi count-up + highlight
  function animateCountUp($element, newValue, duration = 1200) {
      let oldValue = parseInt($element.text().replace(/[^0-9]/g, '')) || 0;
      newValue = parseInt(newValue) || 0;

      if (oldValue === newValue) {
          $element.text(newValue.toLocaleString('id-ID'));
          return;
      }

      $({ counter: oldValue }).animate(
          { counter: newValue },
          {
              duration: duration,
              easing: 'swing',
              step: function (now) {
                  $element.text(Math.round(now).toLocaleString('id-ID'));
              },
              complete: function () {
                  $element.text(newValue.toLocaleString('id-ID'));

                  // Highlight pakai class (akan otomatis fade balik karena CSS transition)
                  $element.addClass('highlight-flash');

                  // Hapus class setelah animasi selesai (biar bisa trigger ulang)
                  setTimeout(() => {
                      $element.removeClass('highlight-flash');
                  }, 1200);  // sesuaikan dengan durasi transition di CSS
              }
          }
      );
  }

// Ubah fungsi autoRefresh masing-masing
function autoRefreshPBIDM() {
    $.ajax({
        url: "{{ url('/dashboard/get-pbidm-count') }}",
        type: "GET",
        success: function(data) {
            let $el = $('#pbidm-count');
            animateCountUp($el, data.tkopbidm);
        },
        complete: function() {
            setTimeout(autoRefreshPBIDM, 5000); // jalan lagi setelah selesai
        },
        error: function() {
            console.log("Gagal refresh PB IDM");
        }
    });
}

function autoRefreshPBOMI() {
    $.ajax({
        url: "{{ url('/dashboard/get-pbomi-count') }}",
        type: "GET",
        success: function(data) {
            let $el = $('#pbomi-count');
            animateCountUp($el, data.tkopbomi);
        },
        complete: function() {
            setTimeout(autoRefreshPBOMI, 5000);
        }, 
        error: function() {
            console.log("Gagal refresh PB OMI");
        }
    });
}

function autoRefreshWT() {
    $.ajax({
        url: "{{ url('/dashboard/get-wt-count') }}",
        type: "GET",
        success: function(data) {
            let $el = $('#wt-count');
            animateCountUp($el, data.wtpending);
        },
        complete: function() {
            setTimeout(autoRefreshWT, 5000);
        }, 
        error: function() {
            console.log("Gagal refresh WT pending");
        }
    });
}

function autoRefreshSPH() {
    $.ajax({
        url: "{{ url('/dashboard/get-sph-count') }}",
        type: "GET",
        success: function(data) {
            let $el = $('#sph-count');
            animateCountUp($el, data.sphpending);
        },
        complete: function() {
            setTimeout(autoRefreshSPH, 5000);
        }, 
        error: function() {
            console.log("Gagal refresh SPH pending");
        }
    });
}

autoRefreshPBIDM();
autoRefreshPBOMI();
autoRefreshWT();
autoRefreshSPH();

});

</script>


@endpush

@endsection
