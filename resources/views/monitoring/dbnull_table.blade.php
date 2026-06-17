<div class="card-body">
  <table id="example1" class="table table-bordered table-striped" style="width:100%">
    <thead class="bg-gradient-info">
      <tr>
        <th>NO.</th>
        <th>KODE TOKO</th>
        <th>NO PB</th>
        <th>PLU</th>
        <th>DESKRIPSI</th>
        <th>HARGA JUAL</th>
        <th>AVG COST</th>
        <th>FLAG OMI</th>
        <th>FLAG IDM</th>
      </tr>
    </thead>

    <tbody>
        @foreach($data as $i => $row)
        <tr>
            <td class="text-center">{{ $i + 1 }}</td>
            <td class="text-center">{{ $row->pbo_kodeomi }}</td>
            <td class="text-center">{{ $row->pbo_nopb }}</td>
            <td class="text-center">{{ $row->pbo_pluigr }}</td>
            <td class="text-center">{{ $row->prd_deskripsipendek }}</td>
            <td class="text-center">{{ $row->prd_hrgjual }}</td>
            <td class="text-center">{{ $row->prd_avgcost }}</td>
            <td class="text-center">{{ $row->prd_flagomi }}</td>
            <td class="text-center">{{ $row->prd_flagidm }}</td>
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