<div class="card-body">
  <table id="example1" class="table table-bordered table-striped" style="width:100%">
    <thead class="bg-gradient-info">
      <tr>
        <th>NO.</th>
        <th>TANGGAL NRB</th>
        <th>TANGGAL ABSEN</th>
        <th>TANGGAL SORTASI</th>
        <th>USER ABSEN</th>
        <th>USER SORTASI</th>
        <th>TOKO</th>
        <th>NO NRB</th>
        <th>PLU</th>
        <th>QTY NRB</th>
        <th>QTY FISIK</th>
        <th>QTY BA KURANG</th>
        <th>QTY BAIK</th>
        <th>QTY LAYAK RETUR</th>
        <th>QTY BA TOLAK</th>
      </tr>
    </thead>

    <tbody>
        @foreach($data as $i => $row)
        <tr>
            <td class="text-center">{{ $i + 1 }}</td>
            <td class="text-center">{{ $row->tglnrb }}</td>
            <td class="text-center">{{ $row->tglabsen }}</td>
            <td class="text-center">{{ $row->tglsortasi }}</td>
            <td class="text-center">{{ $row->user_absen }}</td>
            <td class="text-center">{{ $row->user_sortasi }}</td>
            <td class="text-center">{{ $row->kodetoko }}</td>
            <td class="text-center">{{ $row->nonrb }}</td>
            <td class="text-center">{{ $row->plu }}</td>
            <td class="text-center">{{ $row->qtynrb }}</td>
            <td class="text-center">{{ $row->qtyfisik }}</td>
            <td class="text-center">{{ $row->qtybakurang }}</td>
            <td class="text-center">{{ $row->qtybaik }}</td>
            <td class="text-center">{{ $row->qtylayakretur }}</td>
            <td class="text-center">{{ $row->qtybatolak }}</td>
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