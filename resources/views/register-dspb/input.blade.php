<div class="page-card">

    <div class="page-title">
        Input Serah Terima
    </div>

    <form id="formRegister"
          method="POST"
          action="{{ route('register-dspb.store') }}">

        @csrf

        {{-- TIPE SERAH TERIMA (SELECT) --}}
        <div class="form-group">
            <label class="form-label">Tipe Serah Terima</label>
            <select name="tipe_serah_terima" id="tipe_serah_terima" required>
                <option value="">Pilih Tipe</option>
                <option value="IDM">IDM</option>
                <option value="OMI">OMI</option>
            </select>
        </div>

        {{-- BARIS 1: Kode Toko, No SJ, Jam, Nama Checker (4 kolom) --}}
        <div class="form-row four-cols">
            <div class="form-group">
                <label class="form-label">Kode Toko *</label>
                <select name="kode_toko" id="kode_toko" required>
                    <option value="">Pilih kode toko...</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">No SJ *</label>
                <select name="no_sj" id="no_sj_select" required>
                    <option value="">Pilih No SJ...</option>
                </select>
                <small class="form-text text-muted">Pilih nomor surat jalan sesuai kode toko</small>
            </div>
            <div class="form-group">
                <label class="form-label">Jam</label>
                <input type="time" name="jam" class="form-control" step="60" value="{{ date('H:i') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Nama Checker *</label>
                <input type="text" name="nama_checker" class="form-control" placeholder="Nama Checker" required>
            </div>
        </div>

        {{-- BARIS 2: Total DSPB, Bronjong, Kontainer, Kardus, Susu, Rokok (6 kolom) --}}
        <div class="form-row six-cols">
            <div class="form-group">
                <label class="form-label">Total DSPB</label>
                <input type="text" name="total_dspb" class="form-control" placeholder="0">
            </div>
            <div class="form-group">
                <label class="form-label">Bronjong</label>
                <input type="text" name="bronjong" class="form-control" placeholder="0">
            </div>
            <div class="form-group">
                <label class="form-label">Kontainer</label>
                <input type="text" name="kontainer" class="form-control" placeholder="0">
            </div>
            <div class="form-group">
                <label class="form-label">Kardus</label>
                <input type="text" name="kardus" class="form-control" placeholder="0">
            </div>
            <div class="form-group">
                <label class="form-label">Susu</label>
                <input type="text" name="susu" class="form-control" placeholder="0">
            </div>
            <div class="form-group">
                <label class="form-label">Rokok</label>
                <input type="text" name="rokok" class="form-control" placeholder="0">
            </div>
        </div>

        {{-- BARIS 3: Detail Rokok & Keterangan (2 kolom) --}}
        <div class="form-row two-cols">
            <div class="form-group">
                <label class="form-label">Detail Rokok</label>
                <input type="text" name="detail_rokok" class="form-control" placeholder="Contoh: 15+78">
                <small class="form-text text-muted">Format: jumlah_merk1+jumlah_merk2</small>
            </div>
            <div class="form-group">
                <label class="form-label">Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="2" placeholder="Catatan tambahan..."></textarea>
            </div>
        </div>

        {{-- BUTTON --}}
        <button type="submit" class="btn-submit">Simpan</button>

        {{-- LOADING, SUCCESS, ERROR --}}
        <div id="loadingSave" style="display:none; margin-top:15px; color:#00d5a3; font-weight:600;">
            <i class="fas fa-spinner fa-spin"></i> Menyimpan data...
        </div>
        <div id="successMessage" style="display:none; margin-top:15px; background:#00a884; color:white; padding:14px 16px; border-radius:12px; font-weight:600;">
            <i class="fas fa-check-circle"></i> Data berhasil disimpan
        </div>
        <div id="errorMessage" style="display:none; margin-top:15px; background:#dc3545; color:white; padding:14px 16px; border-radius:12px; font-weight:600;">
            <i class="fas fa-times-circle"></i> Gagal menyimpan data
        </div>

    </form>
</div>

<style>
    /* Form row default 3 kolom */
    .form-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 16px;
    }
    /* Kustom untuk 4 kolom (baris pertama) */
    .form-row.four-cols {
        grid-template-columns: repeat(4, 1fr);
    }
    .form-row.six-cols {
        grid-template-columns: repeat(6, 1fr);
    }
    .form-row.two-cols {
        grid-template-columns: repeat(2, 1fr);
    }
    .form-row .form-group {
        margin-bottom: 0;
    }
    /* Responsive */
    @media (max-width: 768px) {
        .form-row,
        .form-row.four-cols,
        .form-row.six-cols,
        .form-row.two-cols {
            grid-template-columns: 1fr;
            gap: 12px;
        }
        .form-row .form-group {
            margin-bottom: 12px;
        }
    }
    .form-text.text-muted {
        font-size: 12px;
        color: #6c757d;
        margin-top: 4px;
    }
    /* Agar style input time konsisten dengan input lain */
    input[type="time"].form-control {
        background: #1f2c34;
        border: 1px solid #2f3e46;
        color: white;
        border-radius: 12px;
        padding: 10px 14px;
        width: 100%;
    }
</style>

<script>
$(document).ready(function() {
    // TomSelect untuk Tipe Serah Terima
    const tipeSelect = new TomSelect('#tipe_serah_terima', {
        create: false,
        placeholder: 'Pilih tipe...',
    });

    let kodeTokoSelect = null;
    let noSjSelect = null;

    function initKodeToko(options = []) {
        if (kodeTokoSelect) kodeTokoSelect.destroy();
        kodeTokoSelect = new TomSelect('#kode_toko', {
            create: false,
            placeholder: 'Cari toko...',
            sortField: { field: "text", direction: "asc" },
            options: options,
            items: []
        });
    }

    function initNoSj(options = []) {
        if (noSjSelect) noSjSelect.destroy();
        noSjSelect = new TomSelect('#no_sj_select', {
            create: false,
            placeholder: 'Pilih No SJ...',
            options: options,
            items: []
        });
    }

    // Load kode toko berdasarkan tipe
    async function loadKodeToko(tipe) {
        if (!tipe) {
            initKodeToko([]);
            return;
        }
        try {
            const url = "{{ route('register-dspb.getKodeToko') }}?tipe=" + encodeURIComponent(tipe);
            const response = await fetch(url);
            const data = await response.json();
            const options = data.map(item => ({
                value: item.kode,
                text: item.kode + ' - ' + item.nama
            }));
            initKodeToko(options);
        } catch (error) {
            console.error('Gagal load kode toko:', error);
            initKodeToko([]);
        }
    }

    // Load No SJ berdasarkan kode toko
    async function loadNoSj(kodeToko) {
        if (!kodeToko) {
            initNoSj([]);
            return;
        }
        try {
            const url = "{{ route('register-dspb.getNoSj') }}?kode_toko=" + encodeURIComponent(kodeToko);
            const response = await fetch(url);
            const options = await response.json(); // array of {value, text}
            initNoSj(options);
        } catch (error) {
            console.error('Gagal load No SJ:', error);
            initNoSj([]);
        }
    }

    // Event tipe berubah
    tipeSelect.on('change', (value) => {
        loadKodeToko(value);
        initNoSj([]); // reset No SJ saat tipe berubah
    });

    // Event kode toko berubah
    $('#kode_toko').on('change', function() {
        const kodeToko = $(this).val();
        loadNoSj(kodeToko);
    });

    // Inisialisasi awal
    const initialTipe = $('#tipe_serah_terima').val();
    if (initialTipe) {
        loadKodeToko(initialTipe);
    } else {
        initKodeToko([]);
    }
    initNoSj([]); // kosong awal

    // AJAX SUBMIT
    $('#formRegister').on('submit', function(e){
        e.preventDefault();

        $('#loadingSave').show();
        $('#successMessage, #errorMessage').hide();

        const btn = $('.btn-submit');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');

        $.ajax({
            url: $(this).attr('action'),
            type: "POST",
            data: $(this).serialize(),
            success: function(response){
                $('#loadingSave').hide();
                $('#successMessage').fadeIn();
                btn.prop('disabled', false).html('Simpan');
                setTimeout(function(){
                    $('#successMessage').fadeOut();
                }, 3000);
            },
            error: function(xhr) {
                $('#loadingSave').hide();
                let errorMsg = 'Gagal menyimpan data';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                $('#errorMessage').html('<i class="fas fa-times-circle"></i> ' + errorMsg).fadeIn();
                btn.prop('disabled', false).html('Simpan');
                setTimeout(function(){
                    $('#errorMessage').fadeOut();
                }, 4000);
            }
        });
    });
});
</script>