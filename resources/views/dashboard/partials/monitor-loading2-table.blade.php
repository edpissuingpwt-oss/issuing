<div class="table-responsive">
    <table class="table table-bordered table-hover table-modern mb-0">
        <thead>
            <tr>
                <th class="text-center">Tipe DSPB</th>
                <th class="text-center">Toko</th>
                <th class="text-center">Surat Jalan</th>
                <th class="text-center">Status</th>
                <th class="text-center" style="width:30%">Progress</th>
                <th class="text-center" style="width:70px">%</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($monitorLoading as $row)
            <tr>
                {{-- TIPE --}}
                <td class="text-center align-middle">
                    <span class="badge badge-tipe
                        @if ($row->tipe_toko == 'IDM') bg-info
                        @elseif ($row->tipe_toko == 'OMI') bg-success
                        @else bg-warning text-dark
                        @endif
                    ">
                        {{ $row->tipe_toko }}
                    </span>
                </td>

                {{-- TOTAL --}}
                <td class="text-center fw-semibold">
                    {{ $row->totaltoko }}
                </td>

                {{-- SJ --}}
                <td class="text-center fw-semibold">
                    {{ $row->totalsj }}
                </td>

                {{-- STATUS --}}
                <td class="text-center align-middle">
                    <span class="badge rounded-pill badge-loading
                        @if ($row->status == 'DONE/PRAGAT') bg-success
                        @elseif ($row->status == 'ON PROCESS') bg-warning text-dark
                        @else bg-danger
                        @endif
                    ">
                        @if ($row->status == 'DONE/PRAGAT')
                            <i class="bi bi-check-circle me-1"></i>
                        @else
                            <i class="bi bi-hourglass-split me-1"></i>
                        @endif
                        {{ $row->status }}
                    </span>
                </td>

                {{-- PROGRESS --}}
                <td class="align-middle">
                    <div class="progress progress-modern">
                        <div class="progress-bar
                            @if ($row->status == 'DONE/PRAGAT') bg-success
                            @elseif ($row->status == 'ON PROCESS') bg-warning
                            @else bg-danger
                            @endif
                        "
                        style="width: {{ $row->slsj }}%">
                        </div>
                    </div>
                </td>

                {{-- PERCENT --}}
                <td class="text-center align-middle">
                    <span class="badge badge-percent
                        @if ($row->slsj == 100) bg-success
                        @elseif ($row->slsj >= 50) bg-warning text-dark
                        @else bg-danger
                        @endif
                    ">
                        {{ $row->slsj }}%
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>