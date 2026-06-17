<div class="table-responsive">
    <table class="table table-bordered table-hover table-modern mb-0">
        <thead>
            <tr>
                <th class="text-center" style="width:40px">#</th>
                <th class="text-center">Zona</th>
                <th class="text-center">Jalur</th>
                <th class="text-center">Pick</th>
                <th class="text-center">Scan</th>
                <th class="text-center">Status</th>
                <th class="text-center">Progress</th>
                <th class="text-center" style="width:70px">%</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($monitorPicking as $index => $item)
            <tr>
                <td class="text-center fw-semibold">{{ $index + 1 }}</td>
                <td class="text-center">{{ $item->zona }}</td>
                <td class="text-center">{{ $item->jalur }}</td>
                <td class="text-center">{{ $item->picking }}</td>
                <td class="text-center">{{ $item->scanning }}</td>

                {{-- STATUS --}}
                <td class="text-center">
                    <span class="badge rounded-pill badge-status
                        @if ($item->ket == 'CLOSE') bg-success
                        @elseif ($item->ket == 'SCANNING') bg-warning text-dark
                        @elseif ($item->ket == 'PICKING') bg-info
                        @else bg-danger @endif
                    ">
                        @if ($item->ket == 'CLOSE')
                            <i class="bi bi-check-circle me-1"></i>
                        @elseif ($item->ket == 'SCANNING')
                            <i class="bi bi-upc-scan me-1"></i>
                        @elseif ($item->ket == 'PICKING')
                            <i class="bi bi-box-seam me-1"></i>
                        @endif
                        {{ $item->ket }}
                    </span>
                </td>

                {{-- PROGRESS --}}
                <td class="align-middle progress-cell">
                <div class="progress-wrapper">

                    {{-- PROGRESS BAR --}}
                    <div class="progress progress-modern
                        @if($item->ket === 'CLOSE') progress-close
                        @elseif($item->ket === 'SCANNING') progress-scan
                        @elseif($item->ket === 'PICKING') progress-pick
                        @else progress-default
                        @endif
                    ">
                    <div class="progress-bar"
                        style="width: {{ $item->sl }}%">
                    </div>
                    </div>

                    {{-- ICON --}}
                    <div class="progress-icon
                        @if($item->ket === 'CLOSE') icon-close
                        @elseif($item->ket === 'PICKING') icon-pick
                        @elseif($item->ket === 'SCANNING') icon-scan
                        @endif
                    ">
                    @if($item->ket === 'CLOSE')
                        ✅
                    @elseif($item->ket === 'PICKING')
                        🛒
                    @elseif($item->ket === 'SCANNING')
                        📲📦
                    @endif
                    </div>

                </div>
                </td>





                {{-- PERCENT --}}
                <td class="text-center align-middle">
                    <span class="badge badge-percent
                        @if ($item->sl == 100) bg-success
                        @elseif ($item->sl >= 50) bg-warning
                        @else bg-danger @endif
                    ">
                        {{ $item->sl }}%
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>