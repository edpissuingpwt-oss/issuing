
<div class="card-body">
    <table id="example1" class="table table-bordered table-striped" style="width:100%">
    @if($flag == 'belumscan')
        <thead class="bg-gradient-info">
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">TANGGAL</th>
                <th class="text-center">TOKO</th>
                <th class="text-center">NAMA</th>
                <th class="text-center">NO SJ</th>
                <th class="text-center">NO DSPB</th>
                <th class="text-center">NO URUT</th>
                <th class="text-center">TOTAL KOLI</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 0; @endphp
            @foreach($result as $row)
            <tr>
                <td>{{ ++$no }}</td>
                <td>{{ $row->tanggal }}</td>
                <td>{{ $row->lmi_kodetoko }}</td>
                <td>{{ $row->lmi_namatoko }}</td>
                <td>{{ $row->lmi_nosj }}</td>
                <td>{{ $row->nodspb }}</td>
                <td>{{ $row->lmi_nourut }}</td>
                <td>{{ $row->totalkoli }}</td>
            </tr>
            @endforeach
        </tbody>
    @elseif($flag == 'belumabsen')
        <thead class="bg-gradient-info">
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">TANGGAL</th>
                <th class="text-center">TOKO</th>
                <th class="text-center">NAMA</th>
                <th class="text-center">NO SJ</th>
                <th class="text-center">NO DSPB</th>
                <th class="text-center">NO URUT</th>
                <th class="text-center">DOLLY</th>     
                <th class="text-center">TOTAL KOLI</th>
                <th class="text-center">TOTAL KUBIKASI</th>
                <th class="text-center">KODE MOBIL</th>
                <th class="text-center">PLAT</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 0; @endphp
            @foreach($result as $row)
            <tr>
                <td class="text-center">{{ ++$no }}</td>
                <td class="text-center">{{ $row->tanggal }}</td>
                <td class="text-center">{{ $row->lmi_kodetoko }}</td>
                <td class="text-center">{{ $row->lmi_namatoko }}</td>
                <td class="text-center">{{ $row->lmi_nosj }}</td>
                <td class="text-center">{{ $row->nodspb }}</td>
                <td class="text-center">{{ $row->lmi_nourut }}</td>
                <td class="text-center">{{ $row->lmi_dolly }}</td>
                <td class="text-right">{{ number_format($row->totalkoli) }}</td>
                <td class="text-center">{{ $row->totalkubikasi }}</td>
                <td class="text-center">{{ $row->lmi_kodemobil }}</td>
                <td class="text-center">{{ $row->lmi_nopolisi }}</td>
            </tr>
            @endforeach
        </tbody>
    @elseif($flag == 'belumpulang')
        <thead class="bg-gradient-info">
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">TOKO</th>
                <th class="text-center">NAMA</th>
                <th class="text-center">NO SJ</th>
                <th class="text-center">NO DSPB</th>
                <th class="text-center">NO URUT</th>
                <th class="text-center">JENIS</th>
                <th class="text-center">DOLLY</th>     
                <th class="text-center">TOTAL KOLI</th>
                <th class="text-center">TOTAL KUBIKASI</th>
                <th class="text-center">KODE MOBIL</th>
                <th class="text-center">PLAT</th>
                <th class="text-center">NAMA SUPIR</th>
                <th class="text-center">BERANGKAT</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 0; @endphp
            @foreach($result as $row)
            <tr>
                <td class="text-center">{{ ++$no }}</td>
                <td class="text-center">{{ $row->lmi_kodetoko }}</td>
                <td class="text-center">{{ $row->lmi_namatoko }}</td>
                <td class="text-center">{{ $row->lmi_nosj }}</td>
                <td class="text-center">{{ $row->nodspb }}</td>
                <td class="text-center">{{ $row->lmi_nourut }}</td>
                <td class="text-center">{{ $row->lmi_jenispengiriman }}</td>
                <td class="text-center">{{ $row->lmi_dolly }}</td>
                <td class="text-right">{{ number_format($row->totalkoli) }}</td>
                <td class="text-center">{{ $row->totalkubikasi }}</td>
                <td class="text-center">{{ $row->lmi_kodemobil }}</td>
                <td class="text-center">{{ $row->lmi_nopolisi }}</td>
                <td class="text-center">{{ $row->lmi_namasupir }}</td>
                <td class="text-center">{{ $row->lmi_berangkat }}</td>

            </tr>
            @endforeach
        </tbody>
    @elseif($flag == 'sudahpulang')
        <thead class="bg-gradient-info">
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">TOKO</th>
                <th class="text-center">NAMA</th>
                <th class="text-center">NO SJ</th>
                <th class="text-center">NO DSPB</th>
                <th class="text-center">NO URUT</th>
                <th class="text-center">JENIS</th>
                <th class="text-center">DOLLY</th>     
                <th class="text-center">TOTAL KOLI</th>
                <th class="text-center">TOTAL KUBIKASI</th>
                <th class="text-center">KODE MOBIL</th>
                <th class="text-center">PLAT</th>
                <th class="text-center">NAMA SUPIR</th>
                <th class="text-center">BERANGKAT</th>
                <th class="text-center">PULANG</th>

            </tr>
        </thead>
        <tbody>
            @php $no = 0; @endphp
                @foreach ($result as $row)
            <tr>
                <td class="text-center">{{ ++$no }}</td>
                <td class="text-center">{{ $row->lmi_kodetoko }}</td>
                <td class="text-center">{{ $row->lmi_namatoko }}</td>
                <td class="text-center">{{ $row->lmi_nosj }}</td>
                <td class="text-center">{{ $row->nodspb }}</td>
                <td class="text-center">{{ $row->lmi_nourut }}</td>
                <td class="text-center">{{ $row->lmi_jenispengiriman }}</td>
                <td class="text-center">{{ $row->lmi_dolly }}</td>
                <td class="text-right">{{ number_format($row->totalkoli) }}</td>
                <td class="text-center">{{ $row->totalkubikasi }}</td>
                <td class="text-center">{{ $row->lmi_kodemobil }}</td>
                <td class="text-center">{{ $row->lmi_nopolisi }}</td>
                <td class="text-center">{{ $row->lmi_namasupir }}</td>
                <td class="text-center">{{ $row->lmi_berangkat }}</td>
                <td class="text-center">{{ $row->lmi_pulang }}</td>
            </tr>
            @endforeach
        </tbody>
    @endif
    </table>
</div>
