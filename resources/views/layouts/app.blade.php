<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'ISSUING | IGRPWT')</title>

  <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('icons8-skyatlas-48.png') }}">
  <script src="{{ asset('js/countup.js') }}"></script>

  <link rel="stylesheet" href="{{ asset('css/navbar2.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-f72/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/bas3.css') }}">
  @stack('styles')
<style>
/* ==========================================
   USER NAVBAR
========================================== */

.user-pill{
    background: #ffffff;
    border: 1px solid #d8dee9;
    border-radius: 18px;
    padding: 5px 12px !important;
    min-height: 38px;

    display: flex;
    align-items: center;

    color: #374151 !important;
    text-decoration: none !important;

    transition: all .25s ease;
}

.user-pill:hover{
    background: #f8fafc;
    color: #111827 !important;
}

.user-avatar{
    width: 26px;
    height: 26px;
    min-width: 26px;

    border-radius: 50%;
    background: #4b6fff;
    border: 2px solid rgba(255,255,255,.15);

    color: #fff;
    font-size: 12px;
    font-weight: 700;

    display: flex;
    align-items: center;
    justify-content: center;
}

.user-avatar{
    width: 26px;
    height: 26px;
    min-width: 26px;
    border-radius: 50%;
    background: #4b6fff;
    border: 2px solid rgba(255,255,255,.15);

    color: #fff;
    font-size: 12px;
    font-weight: 700;

    display: flex;
    align-items: center;
    justify-content: center;
}

.user-avatar2{
    width: 26px;
    height: 26px;
    min-width: 26px;
    margin-right: 8px;
    border-radius: 50%;
    background: #4b6fff;
    border: 2px solid rgba(255,255,255,.15);

    color: #fff;
    font-size: 12px;
    font-weight: 700;

    display: flex;
    align-items: center;
    justify-content: center;
}

.user-name{
    margin-left: 8px;

    font-size: 12px;
    font-weight: 600;

    color: #2563eb;

    max-width: 100px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.issuing-dropdown{
    min-width: 190px;
    right: 0 !important;
    left: auto !important;
    transform: translateX(-50px) !important;
    border-radius: 12px;
}

/* ==========================================
   DARK MODE
========================================== */

[data-theme="dark"] .user-pill{
    background: #56637f;
    border: 1px solid rgba(255,255,255,.15);
    color: #e8edf8 !important;
}

[data-theme="dark"] .user-pill:hover{
    background: #647390;
}

[data-theme="dark"] .user-name{
    color: #ffffff;
}

[data-theme="dark"] .user-avatar{
    background: #4b6fff;
    border: 2px solid rgba(255,255,255,.2);
}

[data-theme="dark"] .issuing-dropdown{
    background: #2d3748;
    color: #fff;
}

[data-theme="dark"] .issuing-dropdown .dropdown-item{
    color: #fff;
}

[data-theme="dark"] .issuing-dropdown .dropdown-item:hover{
    background: rgba(255,255,255,.08);
}

[data-theme="dark"] .issuing-dropdown .dropdown-divider{
    border-color: rgba(255,255,255,.1);
}
</style>

</head>

<body class="hold-transition">
@include('sweetalert::alert')

<nav class="main-header navbar navbar-expand-xl navbar-modern navbar-light py-0" id="mainNavbarModern">
  <div class="container-fluid px-3">

    <a href="{{ url('/') }}" class="navbar-brand d-flex align-items-center me-3">
      <span class="brand-text">ISSUING</span>
      <span class="text-muted fw-light mx-1">|</span>
      <span class="brand-sub d-none d-md-inline">IGRPWT</span>
    </a>

    <button class="navbar-toggler border-0 p-1" type="button"
            data-toggle="collapse" data-target="#mainNavbar"
            aria-controls="mainNavbar" aria-expanded="false">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainNavbar">
      {{-- overflow visible penting agar dropdown tidak terpotong --}}
      <ul class="navbar-nav me-auto" style="flex-wrap:nowrap;gap:2px;scrollbar-width:none;overflow:visible;">
        @include('layouts.partials.nav-laporan')
        @include('layouts.partials.nav-master')
        @include('layouts.partials.nav-monitoring')
        @include('layouts.partials.nav-inventory')
        @include('layouts.partials.nav-retur')
        @include('layouts.partials.nav-seasonal')
        @include('layouts.partials.nav-problem')
        @include('layouts.partials.nav-edp')
        @include('layouts.partials.nav-web')
      </ul>
    </div>

    <ul class="navbar-nav align-items-center flex-shrink-0 ms-2" style="gap:6px;">
      <li class="nav-item dropdown position-relative">
        <a class="nav-link theme-toggle p-1" href="#" id="themeToggle">
          <img id="themeIcon" src="{{ asset('moon.png') }}" width="22" height="22" alt="Theme">
        </a>
      </li>
      <li class="nav-item dropdown">
          <a class="nav-link user-pill"
            href="#"
            data-bs-toggle="dropdown">

              <div class="user-avatar">
                  {{ strtoupper(substr(session('username', 'U'),0,1)) }}
              </div>

              <span class="user-name">
                  {{ strtoupper(session('userid')) }}
              </span>

              <i class="fas fa-caret-down ms-2"></i>
          </a>

          <ul class="dropdown-menu issuing-dropdown shadow border-0">
              <li class="px-3 py-2">
                  <div class="d-flex align-items-center gap-2">

                      <div class="user-avatar2">
                          {{ strtoupper(substr(session('username', 'U'),0,1)) }}
                      </div>

                      <div>
                          <strong>{{ strtoupper(session('username')) }}</strong>
                      </div>

                  </div>
              </li>

              <li><hr class="dropdown-divider"></li>

              <li>
                  <a href="{{ route('logout') }}"
                    class="dropdown-item text-danger">
                      <i class="fas fa-door-open me-2"></i>
                      Log Out
                  </a>
              </li>
          </ul>
      </li>
    </ul>
  </div>
</nav>

<div class="wrapper" style="min-height:calc(100vh - 56px);">
  <div class="content-wrapper" style="min-height:unset;">
    <div class="content-header pt-3 pb-1">
      <div class="container-fluid">
        <h1 class="m-0 h4">@yield('content_title')</h1>
      </div>
    </div>
    <div class="content pb-4">
      <div class="container-fluid">
        @yield('content')
      </div>
    </div>
  </div>
  <footer class="main-footer">
    <strong>Copyright &copy; 2025 - 2026 by EDP-ISSUING.</strong> All rights reserved.
  </footer>
</div>

{{-- URUTAN BENAR: jQuery → Bootstrap → lib_scripts → scripts --}}
<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/navbar2.js') }}"></script>
<script src="{{ asset('js/navbar.js') }}"></script>
@stack('lib_scripts')
@stack('scripts')
</body>
</html>