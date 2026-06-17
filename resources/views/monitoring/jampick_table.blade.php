<div class="card-body">
  <table id="example1" class="table table-bordered table-striped" style="width:100%">
    <thead class="bg-gradient-info">
      <tr>
        <th>No</th>
        <th>ZONA</th>
        <th>ALAMAT</th>
        <th>PLU</th>
        <th>DESKRIPSI</th>
        <th>QTY</th>
        <th>JAM PICK</th>
        <th>STATUS</th>
      </tr>
    </thead>

    <tbody>
        @foreach($data as $i => $row)
        <tr>
            <td class="text-center">{{ $i + 1 }}</td>
            <td class="text-center">{{ $row->zona }}</td>
            <td class="text-center">{{ $row->alamat }}</td>
            <td class="text-center">{{ $row->plu }}</td>
            <td class="text-center">{{ $row->deskripsi }}</td>
            <td class="text-center">{{ $row->qty }}</td>
            <td class="text-center">{{ $row->jam_pick }}</td>
            <td class="text-center">{{ $row->status }}</td>
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