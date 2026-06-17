<li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle" href="#"
     data-toggle="dropdown">
    Retur
  </a>

  <div class="dropdown-menu dropdown-menu-right">
    
    <a class="dropdown-item" href="{{ url('bpbrtoday') }}">
      1. Laporan Harian BPBR
    </a>
    <a class="dropdown-item" href="{{ url('register-nrb') }}">
      2. Register NRB IDM
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
    <a class="dropdown-item" href="{{ url('bpbr') }}">
      3. Laporan BPBR
    </a>
    <a class="dropdown-item" href="{{ url('retur_proforma') }}">
      4. Retur Proforma
    </a>
    <a class="dropdown-item" href="{{ url('returidm') }}">
      5. Retur IDM
    </a>
    <a class="dropdown-item" href="{{ url('outstanding_retur') }}">
      6. Outstanding Retur
    </a>
    <a class="dropdown-item" href="{{ url('ba_toko') }}">
      7. BA Toko
    </a>
    <a class="dropdown-item" href="{{ url('piutang_retur') }}">
      8. Cek Piutang Retur
    </a>
    <a class="dropdown-item" href="{{ url('double_piutang') }}">
      9. Cek Double Piutang
    </a>
    <a class="dropdown-item" href="{{ url('cekacost') }}">
      10. Cek Acost 0
    </a>
    <a class="dropdown-item" href="{{ url('cekjampick') }}">
      11. Cek Jam Picking
    </a>
    <a class="dropdown-item" href="{{ url('cekabsenretur') }}">
      12. Cek User Absen
    </a>

  </div>
</li>