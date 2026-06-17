@push('styles')
@endpush

<div class="card modern-card shadow-lg border-0 mt-4">
    <div class="card-body modern-body">
            <div id="exportButtons"></div>
        <div class="table-responsive">
            <table id="example1" class="table table-bordered table-striped" style="width:100%">
                <thead class="bg-gradient-info">
                    <tr>
                        <th class="text-center">NO</th>
                        <th class="text-center">NAMA FILE</th>
                        <th class="text-center">KODE OMI</th>
                        <th class="text-center">TGL BUKTI</th>
                        <th class="text-center">NO BUKTI</th>
                        <th class="text-center">NO DOC</th>
                        <th class="text-center">TGL STRUK</th>
                        <th class="text-center">STATION</th>
                        <th class="text-center">KASIR</th>
                        <th class="text-center">NO STRUK</th>
                        <th class="text-center">KODE SUPPLIER</th>
                        <th class="text-center">NAMA SUPPLIER</th>
                        <th class="text-center">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $index => $row)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">{{ $row->bkl_idfile }}</td>
                            <td class="text-center">{{ $row->bkl_kodeomi }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($row->bkl_tglbukti)->format('d-M-Y') }}</td>
                            <td class="text-center">{{ $row->bkl_nobukti }}</td>
                            <td class="text-center">{{ $row->bkl_nodoc }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($row->bkl_tglstruk)->format('d-M-Y') }}</td>
                            <td class="text-center">{{ $row->bkl_kodestation }}</td>
                            <td class="text-center">{{ $row->bkl_kodekasir }}</td>
                            <td class="text-center">{{ $row->bkl_nostruk }}</td>
                            <td class="text-center">{{ $row->sup_kodesupplier }}</td>
                            <td class="text-center">{{ $row->sup_namasupplier }}</td>
                            <td style="text-align: right">{{ number_format(round($row->bkl_total), 0, ".", ",") }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@stack('styles')
