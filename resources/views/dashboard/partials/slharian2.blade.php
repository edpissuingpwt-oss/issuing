<ul class="nav nav-pills mb-3" role="tablist">
    <li class="nav-item me-2">
        <a class="nav-link active tab-all" data-toggle="tab" href="#all" role="tab">ALL</a>
    </li>
    <li class="nav-item me-2">
        <a class="nav-link tab-idm" data-toggle="tab" href="#idm" role="tab">IDM</a>
    </li>
    <li class="nav-item">
        <a class="nav-link tab-omi" data-toggle="tab" href="#omi" role="tab">OMI</a>
    </li>
</ul>

<div class="tab-content">
    @foreach (['all' => $servicelevelall, 'idm' => $servicelevelidm, 'omi' => $servicelevelomi] as $key => $data)
        <div class="tab-pane fade {{ $key == 'all' ? 'show active' : '' }}" id="{{ $key }}" role="tabpanel">
            <div class="table-responsive">
                <table id="datatable_{{ $key }}" class="table table-bordered table-hover w-100">
                    <thead class="small-box-coba1">
                        <tr>
                            <th hidden>TANGGAL_SORT</th>
                            <th class="text-center">TANGGAL</th>
                            <th class="text-center">RPH ORDER</th>
                            <th class="text-center">RPH REALISASI</th>
                            <th class="text-center">%SL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalOrder = 0; $totalRealisasi = 0; @endphp
                        @foreach ($data as $row)
                            @php
                                $totalOrder += $row->rphorder;
                                $totalRealisasi += $row->rphrealisasi;
                            @endphp
                            <tr>
                                <td hidden>{{ $row->tanggal_sort }}</td>
                                <td data-order="{{ $row->tanggal_sort }}">{{ $row->tanggal }}</td>
                                <td class="text-right">{{ number_format($row->rphorder) }}</td>
                                <td class="text-right">{{ number_format($row->rphrealisasi) }}</td>
                                <td class="text-right">{{ $row->slharian }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        @php
                            $slTotal = $totalOrder > 0 ? ($totalRealisasi / $totalOrder * 100) : 0;
                            $slTotalFormatted = $slTotal == 100
                                ? '100'
                                : number_format($slTotal, 2);
                        @endphp
                        <tr class="font-weight-bold bg-light">
                            <td colspan="2" class="text-center">TOTAL</td>
                            <td class="text-right">{{ number_format($totalOrder) }}</td>
                            <td class="text-right">{{ number_format($totalRealisasi) }}</td>
                            <td class="text-right">{{ $slTotalFormatted }}%</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    @endforeach
</div>

@push('scripts')
<script>
    function initDataTable(selector) {
        return $(selector).DataTable({
            columnDefs: [
                { targets: 0, visible: false },   // hide kolom tanggal_sort
                { targets: 1, orderData: 0 }      // urut tanggal pakai kolom 0
            ],
            order: [[0, 'desc']],
            pageLength: 7,
            lengthChange: false,
            autoWidth: false,
            responsive: true,
            language: {
                url: "{{ asset('adminlte/plugins/datatables/i18n/id.json') }}"
            },
            dom: '<"d-flex justify-content-start align-items-center mb-2"B>rtip',
            buttons: [
                {
                    extend: 'copy',
                    text: '<i class="fas fa-copy"></i> Copy',
                    className: 'btn btn-light btn-sm border-primary text-primary'
                }
            ]
        });
    }

    // init semua DataTables
    var tableAll = initDataTable('#datatable_all');
    var tableIdm = initDataTable('#datatable_idm');
    var tableOmi = initDataTable('#datatable_omi');

    // saat tab dibuka, adjust + redraw ulang
    $('a[data-toggle="tab"]').on('shown.bs.tab', function () {
        $.fn.dataTable
            .tables({ visible: true, api: true })
            .columns.adjust()
            .responsive.recalc()
            .draw(false);
    });
</script>
@endpush
