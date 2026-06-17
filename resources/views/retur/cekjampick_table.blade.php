<div class="card-body">
  <table id="example1" class="table table-bordered table-striped" style="width:100%">
    <thead class="bg-gradient-info">
      <tr>
        <th>NO.</th>
        <th>NO KOLI</th>
        <th>TANGGAL</th>
        <th>NPB</th>
        <th>STRUK</th>
        <th>KODE TOKO</th>
        <th>PLU</th>
        <th>DESKRIPSI</th>
        <th>QTY ORDER</th>
        <th>REALISASI</th>
        <th>CHECKER</th>
        <th>JAM PICKING</th>
      </tr>
    </thead>

    <tbody>
        @foreach($data as $i => $row)
        <tr>
            <td class="text-center">{{ $i + 1 }}</td>
            <td class="text-center">{{ $row->nokoli }}</td>
            <td class="text-center">{{ $row->tanggal }}</td>
            <td class="text-center">{{ $row->npb }}</td>
            <td class="text-center">{{ $row->struk }}</td>
            <td class="text-center">{{ $row->kodetoko }}</td>
            <td class="text-center">{{ $row->plu }}</td>
            <td class="text-center">{{ $row->deskripsi }}</td>
            <td class="text-center">{{ $row->qtyorder }}</td>
            <td class="text-center">{{ $row->realisasi }}</td>
            <td class="text-center">{{ $row->checker }}</td>
            <td class="text-center">{{ $row->jam_picking }}</td>
        </tr>
        @endforeach
    </tbody>
  </table>
</div>
<!-- /.card-body -->


<!-- Page specific script -->
<script>
  $(function() {
    $("#example1").DataTable({
      "responsive": true,
      "lengthChange": false,
      "autoWidth": false,
      "buttons": ["copy"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>