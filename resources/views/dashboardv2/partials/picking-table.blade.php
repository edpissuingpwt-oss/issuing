<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover" id="pickingTable">
        <thead>
            <tr class="bg-danger text-white">
                <th class="text-center" style="width:40px">#</th>
                <th class="text-center">ZONA</th>
                <th class="text-center">JALUR</th>
                <th class="text-center">PICKING</th>
                <th class="text-center">SCANNING</th>
                <th class="text-center">STATUS</th>
                <th class="text-center">PROGRESS</th>
                <th class="text-center">%</th>
            </tr>
        </thead>
        <tbody>
            @forelse($monitorPicking as $index => $item)
            <tr>
                <td class="text-center fw-semibold">{{ $index + 1 }}</td>
                <td class="text-center">{{ $item->zona ?? '-' }}</td>
                <td class="text-center">{{ $item->jalur ?? '-' }}</td>
                <td class="text-center">{{ $item->picking ?? '0/0' }}</td>
                <td class="text-center">{{ $item->scanning ?? '0/0' }}</td>

                {{-- STATUS BADGE (Style Lama) --}}
                <td class="text-center">
                    <span class="badge rounded-pill badge-status
                        @if ($item->ket == 'CLOSE') bg-success
                        @elseif ($item->ket == 'SCANNING') bg-warning text-dark
                        @elseif ($item->ket == 'PICKING') bg-info
                        @else bg-danger @endif
                        px-3 py-2"
                        style="font-size: 0.8rem; min-width: 100px;">
                        @if ($item->ket == 'CLOSE')
                            <i class="fas fa-check-circle me-1"></i>
                        @elseif ($item->ket == 'SCANNING')
                            <i class="fas fa-qrcode me-1"></i>
                        @elseif ($item->ket == 'PICKING')
                            <i class="fas fa-box me-1"></i>
                        @endif
                        {{ $item->ket ?? 'BLM MULAI' }}
                    </span>
                </td>

                {{-- PROGRESS BAR (Style Lama) --}}
                <td class="align-middle progress-cell">
                    <div class="progress-wrapper" style="display: flex; align-items: center; gap: 10px;">
                        
                        {{-- PROGRESS BAR --}}
                        <div class="progress progress-modern" style="flex: 1; height: 12px; background: #e9ecef; border-radius: 20px; overflow: hidden;">
                            <div class="progress-bar 
                                @if($item->ket === 'CLOSE') bg-success
                                @elseif($item->ket === 'SCANNING') bg-warning
                                @elseif($item->ket === 'PICKING') bg-info
                                @else bg-danger
                                @endif
                            " 
                            style="width: {{ $item->sl ?? 0 }}%; border-radius: 20px; transition: width 0.8s cubic-bezier(.22,1,.36,1);">
                            </div>
                        </div>

                        {{-- ICON --}}
                        <div class="progress-icon" style="font-size: 18px; flex-shrink: 0;">
                            @if($item->ket === 'CLOSE')
                                ✅
                            @elseif($item->ket === 'PICKING')
                                🛒
                            @elseif($item->ket === 'SCANNING')
                                📲📦
                            @else
                                ⏳
                            @endif
                        </div>
                    </div>
                </td>

                {{-- PERCENT BADGE --}}
                <td class="text-center align-middle">
                    <span class="badge rounded-pill px-3 py-2
                        @if (($item->sl ?? 0) == 100) bg-success
                        @elseif (($item->sl ?? 0) >= 50) bg-warning text-dark
                        @else bg-danger
                        @endif
                    " style="font-size: 0.75rem; font-weight: 600; min-width: 60px;">
                        {{ $item->sl ?? 0 }}%
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    if ($.fn.DataTable) {
        $('#pickingTable').DataTable({
            paging: true,
            pageLength: 15,
            lengthMenu: [[5, 10, 15, 25, 50, -1], [5, 10, 15, 25, 50, "Semua"]],
            lengthChange: true,
            searching: true,
            ordering: true,
            info: true,
            language: {
                url: "{{ asset('adminlte/plugins/datatables/i18n/id.json') }}"
            }
        });
    }
});
</script>
@endpush