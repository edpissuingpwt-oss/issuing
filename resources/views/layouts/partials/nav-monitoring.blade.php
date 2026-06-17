<li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle" href="#"
     data-toggle="dropdown">
    Monitoring
  </a>

  <div class="dropdown-menu dropdown-menu-right">
    
    {{-- <a class="dropdown-item" href="{{ url('pb_idm') }}">
      1. PB & SL IDM
    </a>
    <a class="dropdown-item" href="{{ url('pb_omi') }}">
      2. PB & SL OMI
    </a>
    <a class="dropdown-item" href="{{ url('pb_mentahidm') }}">
      1. PB Mentah IDM
    </a>
    <a class="dropdown-item" href="{{ url('pb_mentahomi') }}">
      2. PB Mentah OMI
    </a>
    <a class="dropdown-item" href="{{ url('wt_idm') }}">
      1. WT IDM
    </a>
    <a class="dropdown-item" href="{{ url('sph_omi') }}">
      2. SPH OMI
    </a> --}}
    <a class="dropdown-item" href="{{ url('register-dspb') }}">
      1. Register Sertim
      {{-- <span style="
          background:#22c55e;
          color:white;
          padding:2px 8px;
          border-radius:999px;
          font-size:10px;
          font-weight:700;
      ">
          NEW
      </span> --}}
    </a>
    <a class="dropdown-item" href="{{ url('pickinghh') }}">
      2. Picking Handheld
      {{-- <span style="
          background:#22c55e;
          color:white;
          padding:2px 8px;
          border-radius:999px;
          font-size:10px;
          font-weight:700;
      ">
          NEW
      </span> --}}
    </a>
    <a class="dropdown-item" href="{{ url('time_picking') }}">
      3. Time Picking
    </a>
    <a class="dropdown-item" href="{{ url('m_picking') }}">
      4. Monitoring Picking
    </a>
    <a class="dropdown-item" href="{{ url('m_dspb') }}">
      5. Monitoring DSPB
    </a>
    <a class="dropdown-item" href="{{ url('jampick') }}">
      6. Jam Picking (per Zona)
    </a>
    <a class="dropdown-item" href="{{ url('koli') }}">
      7. Item & Status Barcode
    </a>
    <a class="dropdown-item" href="{{ url('dbnull') }}">
      8. Cek DB Null OMI
    </a>

  </div>
</li>