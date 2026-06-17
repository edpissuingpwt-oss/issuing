<div class="card-body">
    <table id="example2" class="table table-bordered table-striped">
        <thead class="bg-gradient-info">
            <tr>
                <th>No</th>
                <th>TANGGAL</th>
                <th>TOKO</th>
                <th>NO PB</th>
                <th>KODE TOKO</th>
                <th>NAMA TOKO</th>
                <th>NO SJ</th>
                <th>NO PICKING</th>
                <th>ACTION</th>
            </tr>
        </thead>
        <tbody>
            @foreach($hasil as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->hpbi_create_dt)->format('d-m-Y') }}</td>
                    <td>{{ $row->tko_namasbu }}</td>
                    <td>{{ $row->hpbi_nopb }}</td>
                    <td>{{ $row->hpbi_kodetoko }}</td>
                    <td>{{ $row->tko_namaomi }}</td>
                    <td>{{ $row->hpbi_nosj }}</td>
                    <td>{{ $row->hpbi_nopicking }}</td>
                    <td class="project-actions">
                        <button type="button" class="btn btn-primary btn-sm printer" data-toggle="modal" data-target="#modal-xl"
                                data-tanggal="{{ \Carbon\Carbon::parse($row->hpbi_create_dt)->format('d-m-Y') }}"
                                data-stt="{{ $row->tko_namasbu }}"
                                data-nopb="{{ $row->hpbi_nopb }}"
                                data-kdtoko="{{ $row->hpbi_kodetoko }}"
                                data-nmtoko="{{ $row->tko_namaomi }}"
                                data-nosj="{{ $row->hpbi_nosj }}"
                                data-nopick="{{ $row->hpbi_nopicking }}">
                            <i class="fas fa-eye"></i> Lihat Item
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="modal fade" id="modal-xl">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">List Koli Toko</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="modal-content-data"></div>
            </div>
            <form action="{{ route('listkoli.cetak') }}" method="post" target="_blank"></form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#example2').DataTable({
            "responsive": true,
            "autoWidth": false,
            "searching": true,
            "ordering": true,
            "pageLength": 10,
            "lengthChange": true,
        }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
    });

    $('.printer').click(function() {
        let tanggal = $(this).data('tanggal');
        let stt = $(this).data('stt');
        let nopb = $(this).data('nopb');
        let kdtoko = $(this).data('kdtoko');
        let nmtoko = $(this).data('nmtoko');
        let nosj = $(this).data('nosj');
        let noPick = $(this).data('nopick');

        $.ajax({
            url: "{{ route('listkoli.items') }}",
            type: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                tanggal: tanggal,
                stt: stt,
                kdtoko: kdtoko,
                nmtoko: nmtoko,
                noPick: noPick,
                noSJ: nosj,
            },
            success: function(data) {
                $('#modal-content-data').html(data);
                $('#table-in-modal').DataTable({
                    "responsive": true,
                    "autoWidth": false,
                    "searching": true,
                    "buttons": ["copy"]
                }).buttons().container().appendTo('#table-in-modal_wrapper .col-md-6:eq(0)');
            }
        });
    });
</script>