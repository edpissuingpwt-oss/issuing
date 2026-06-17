@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <h3 class="text-center">Inquiry Produk</h3>

    <form action="{{ route('inquiry.result') }}" method="get" class="mt-3">

        <label for="kodePLU" class="fw-bold">Masukkan PLU</label>

        <!-- Input + Tombol Search -->
        <div class="input-group">
            <input type="text" id="kodePLU" name="plu" class="form-control" placeholder="PLU" autofocus>

            <button type="button" id="btnSearchPLU" class="btn btn-secondary"
                    data-bs-toggle="modal" data-bs-target="#searchModal">
                <i class="fa fa-search"></i> Search
            </button>
        </div>

        <input type="hidden" id="desk" name="desk">

        <button type="submit" class="btn btn-primary mt-3 w-100">Submit</button>

    </form>


    <!-- Modal Search -->
    <div class="modal fade" id="searchModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Daftar PLU</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <table id="tabelPLU" class="table table-sm table-bordered table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>PLU</th>
                                <th>TAG</th>
                                <th>DESKRIPSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($listplu as $row)
                                <tr class="pilih"
                                    data-plu="{{ $row->prd_prdcd }}"
                                    data-desk="{{ $row->prd_deskripsipanjang }}">
                                    
                                    <td>{{ $row->prd_prdcd }}</td>
                                    <td>{{ $row->prd_kodetag }}</td>
                                    <td>{{ $row->prd_deskripsipanjang }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>

            </div>
        </div>
    </div>

</div>
@endsection

@push('styles')
{{-- DataTables Bootstrap 4 + Buttons CSS --}}
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush

@push('scripts')
{{-- DataTables + Buttons --}}
<script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

<script src="{{ asset('adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>

<!-- JSZip & PDFMake -->
<script src="{{ asset('adminlte/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/pdfmake/vfs_fonts.js') }}"></script>

<!-- Buttons Extensions -->
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

<script>
$(document).ready(function () {

    // --- Aktifkan DataTables ---
    $("#tabelPLU").DataTable({
        pageLength: 10
    });

    // --- Saat tombol Search ditekan (modal show manual) ---
    $("#btnSearchPLU").on("click", function () {
        $("#searchModal").modal("show");
    });

    // --- Jika klik baris PLU ---
    $(document).on("click", ".pilih", function () {

        // Set nilai ke input
        $("#kodePLU").val($(this).data("plu"));
        $("#desk").val($(this).data("desk"));

        // Tutup modal
        $("#searchModal").modal("hide");
    });

    // --- Fokus ketika modal terbuka ---
    $('#searchModal').on('shown.bs.modal', function () {
        $("#kodePLU").focus();
    });

});
</script>



@endpush