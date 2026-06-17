<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover" id="loadingTable">
        <thead>
            <tr class="bg-danger text-white">
                <th>Tipe</th>
                <th>Total Toko</th>
                <th>Total SJ</th>
                <th>Status</th>
                <th>Progress SJ</th>
                <th>SL SJ</th>
            </tr>
        </thead>
        <tbody>
            @forelse($monitorLoading as $row)
            <tr>
                {{-- TIPE BADGE --}}
                <td class="text-center align-middle">
                    <span class="badge rounded-pill px-3 py-2
                        @if ($row->tipe_toko == 'IDM') bg-info
                        @elseif ($row->tipe_toko == 'OMI') bg-success
                        @else bg-warning text-dark
                        @endif
                    " style="font-size: 0.75rem; font-weight: 600; min-width: 70px;">
                        {{ $row->tipe_toko ?? '-' }}
                    </span>
                </td>

                {{-- TOTAL TOKO --}}
                <td class="text-center fw-semibold align-middle">
                    {{ $row->totaltoko ?? '0/0' }}
                </td>

                {{-- TOTAL SJ --}}
                <td class="text-center fw-semibold align-middle">
                    {{ $row->totalsj ?? '0/0' }}
                </td>

                {{-- STATUS BADGE --}}
                <td class="text-center align-middle">
                    <span class="badge rounded-pill px-3 py-2
                        @if ($row->status == 'DONE/PRAGAT') bg-success
                        @elseif ($row->status == 'ON PROCESS') bg-warning text-dark
                        @else bg-danger
                        @endif
                    " style="font-size: 0.75rem; font-weight: 600; min-width: 120px;">
                        @if ($row->status == 'DONE/PRAGAT')
                            <i class="fas fa-check-circle me-1"></i>
                        @else
                            <i class="fas fa-hourglass-half me-1"></i>
                        @endif
                        {{ $row->status ?? 'BLM MULAI' }}
                    </span>
                </td>

                {{-- PROGRESS BAR SJ --}}
                <td class="align-middle progress-cell">
                    <div class="progress-wrapper" style="display: flex; align-items: center; gap: 10px;">
                        <div class="progress progress-modern" style="flex: 1; height: 12px; background: #e9ecef; border-radius: 20px; overflow: hidden; width: 120px;">
                            <div class="progress-bar 
                                @if($row->status == 'DONE/PRAGAT') bg-success
                                @elseif($row->status == 'ON PROCESS') bg-warning
                                @else bg-danger
                                @endif
                            " 
                            style="width: {{ min($row->slsj ?? 0, 100) }}%; border-radius: 20px; transition: width 0.8s cubic-bezier(.22,1,.36,1);">
                            </div>
                        </div>
                        <div class="progress-icon" style="font-size: 14px;">
                            @if($row->status == 'DONE/PRAGAT')
                                ✅
                            @elseif($row->status == 'ON PROCESS')
                                📦
                            @else
                                ⏳
                            @endif
                        </div>
                    </div>
                </td>

                {{-- PERCENT BADGE SJ --}}
                <td class="text-center align-middle">
                    <span class="badge rounded-pill px-3 py-2
                        @if (($row->slsj ?? 0) >= 100) bg-success
                        @elseif (($row->slsj ?? 0) >= 50) bg-warning text-dark
                        @else bg-danger
                        @endif
                    " style="font-size: 0.75rem; font-weight: 600; min-width: 60px;">
                        {{ $row->slsj ?? 0 }}%
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    if ($.fn.DataTable) {
        $('#loadingTable').DataTable({
            paging: true,
            pageLength: 10,
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