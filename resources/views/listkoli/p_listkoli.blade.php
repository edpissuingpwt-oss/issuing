<div class='card-body'>
    <h6>Tanggal : {{ $tanggal }}</h6>
    <h6>TOKO : ({{ $kdtoko }}) {{ $nmtoko }}</h6>
    <h6>No PICK : {{ $noPick }}</h6>
    <h6>No SJ : {{ $noSJ }}</h6>
    <table id="table-in-modal" class="table table-bordered table-striped">
        <thead class="bg-gradient-info">
            <tr>
                <th>NO</th>
                <th>KOLI</th>
                <th>ZONA</th>
                <th>ITEM</th>
                <th>TOKO</th>
                <th>NO PICK</th>
            </tr>
        </thead>
        <tbody>
            @foreach($allData as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $row->no_koli }}</td>
                    <td>{{ $row->zona }}</td>
                    <td>{{ $row->jml_plu }}</td>
                    <td>{{ $row->kode_toko }}</td>
                    <td>{{ $row->no_pick }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="modal-footer">
    <!-- Form untuk mengirim data ke halaman cetak -->
    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
    <form id="print-form" action="{{ route('listkoli.cetak') }}" method="post" target="_blank">
        @csrf
        <!-- Mengirim data utama sebagai input tersembunyi -->
        <input type="hidden" name="tanggal" value="{{ $tanggal }}">
        <input type="hidden" name="kdtoko" value="{{ $kdtoko }}">
        <input type="hidden" name="nmtoko" value="{{ $nmtoko }}">
        <input type="hidden" name="noPick" value="{{ $noPick }}">
        <input type="hidden" name="noSJ" value="{{ $noSJ }}">
        <!-- Mengirim data tabel (allData) setelah dienkripsi menjadi JSON -->
        <input type="hidden" name="allData" value="{{ json_encode($allData) }}">
        <button type="submit" name="cetak" class="btn btn-success">Print</button>
    </form>
</div>

<!-- Menghapus JavaScript yang menggunakan sessionStorage karena tidak lagi diperlukan -->
