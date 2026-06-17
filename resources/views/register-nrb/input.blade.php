<div class="page-card">

    <div class="page-title">
        Input Register NRB IDM
    </div>

    <form id="formRegister"
          method="POST"
          action="{{ route('register-nrb.store') }}">

        @csrf

        {{-- TOKO --}}
        <div class="form-group">

            <label class="form-label">
                Toko
            </label>

            <select name="kode_toko"
                    id="kode_toko"
                    required>

                <option value="">
                    Cari kode toko...
                </option>

                @foreach($toko as $item)

                    <option 
                        value="{{ $item->tko_kodeomi }}"
                        data-nama="{{ $item->tko_namaomi }}">

                        {{ $item->tko_kodeomi }} - {{ $item->tko_namaomi }}

                    </option>

                @endforeach

            </select>
            
            {{-- HIDDEN INPUT --}}
            <input type="hidden"
                name="nama_toko"
                id="nama_toko">

        </div>
        
        {{-- NO NRB --}}
        <div class="form-group">

            <label class="form-label">
                No NRB
            </label>

            <input type="text"
                   name="no_nrb"
                   class="form-control number-only"
                   placeholder="Masukkan nomor NRB"
                   maxlength="20"
                   required>

        </div>

        {{-- KOLI --}}
        <div class="form-group">

            <label class="form-label">
                Koli
            </label>

            <input type="text"
                   name="koli"
                   class="form-control number-only"
                   placeholder="Masukkan jumlah koli"
                   maxlength="10"
                   required>

        </div>

        {{-- PALET --}}
        <div class="form-group">

            <label class="form-label">
                Palet
            </label>

            <select name="palet"
                    id="palet"
                    required>

                <option value="">
                    Pilih Palet
                </option>

                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
                <option value="D">D</option>
                <option value="E">E</option>
                <option value="F">F</option>
                <option value="G">G</option>
                <option value="H">H</option>
                <option value="I">I</option>
                <option value="J">J</option>
                <option value="K">K</option>
                <option value="L">L</option>
                <option value="M">M</option>
                <option value="N">N</option>
                <option value="O">O</option>
                <option value="P">P</option>
                <option value="Q">Q</option>
                <option value="R">R</option>
                <option value="S">S</option>
                <option value="T">T</option>
                <option value="U">U</option>
                <option value="V">V</option>
                <option value="W">W</option>
                <option value="X">X</option>
                <option value="Y">Y</option>
                <option value="Z">Z</option>

            </select>

        </div>

        {{-- NAMA REGISTER --}}
        <div class="form-group">

            <label class="form-label">
                Nama Register
            </label>

            <input type="text"
                   name="nama_register"
                   class="form-control"
                   placeholder="Masukkan nama register"
                   required>

        </div>

        {{-- KETERANGAN --}}
        <div class="form-group">

            <label class="form-label">
                Keterangan
            </label>

            <select name="keterangan"
                    id="keterangan"
                    required>

                <option value="">
                    Pilih Keterangan
                </option>

                <option value="CONTAINER">
                    CONTAINER
                </option>

                <option value="ROKOK">
                    ROKOK
                </option>

                <option value="REGULER">
                    REGULER
                </option>

            </select>

        </div>

        {{-- BUTTON --}}
        <button type="submit"
                class="btn-submit">

            Simpan

        </button>

        {{-- LOADING --}}
        <div id="loadingSave"
             style="
                display:none;
                margin-top:15px;
                color:#00d5a3;
                font-weight:600;
             ">

            <i class="fas fa-spinner fa-spin"></i>
            Menyimpan data...

        </div>

        {{-- SUCCESS --}}
        <div id="successMessage"
             style="
                display:none;
                margin-top:15px;
                background:#00a884;
                color:white;
                padding:14px 16px;
                border-radius:12px;
                font-weight:600;
             ">

            <i class="fas fa-check-circle"></i>
            Data berhasil disimpan

        </div>

        {{-- ERROR --}}
        <div id="errorMessage"
             style="
                display:none;
                margin-top:15px;
                background:#dc3545;
                color:white;
                padding:14px 16px;
                border-radius:12px;
                font-weight:600;
             ">

            <i class="fas fa-times-circle"></i>
            Gagal menyimpan data

        </div>

    </form>

</div>

<script>

    /*
    |--------------------------------------------------------------------------
    | TOM SELECT
    |--------------------------------------------------------------------------
    */

    new TomSelect('#kode_toko',{

        create:false,

        sortField:{
            field:"text",
            direction:"asc"
        },

        placeholder:'Cari toko...',

        maxOptions:5000,

    });

    /*
    |--------------------------------------------------------------------------
    | AUTO SET NAMA TOKO
    |--------------------------------------------------------------------------
    */

    document.getElementById('kode_toko').tomselect.on('change', function(value) {

        let option = $('#kode_toko option[value="' + value + '"]');

        let namaToko = option.data('nama');

        $('#nama_toko').val(namaToko || '');

    });

    new TomSelect('#palet',{

        create:false,

        placeholder:'Pilih palet...',

    });

    new TomSelect('#keterangan',{

        create:false,

        placeholder:'Pilih keterangan...',

    });

    /*
    |--------------------------------------------------------------------------
    | NUMBER ONLY
    |--------------------------------------------------------------------------
    */

    $(document).on('input', '.number-only', function () {

        this.value = this.value.replace(/[^0-9]/g, '');

    });

    /*
    |--------------------------------------------------------------------------
    | AJAX SUBMIT
    |--------------------------------------------------------------------------
    */

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

                // Reset form setelah sukses (opsional, aktifkan jika diperlukan)
                // $('#formRegister')[0].reset();
                // if ($('#kode_toko')[0].tomselect) $('#kode_toko')[0].tomselect.clear();
                // if ($('#palet')[0].tomselect) $('#palet')[0].tomselect.clear();
                // if ($('#keterangan')[0].tomselect) $('#keterangan')[0].tomselect.clear();

                setTimeout(function(){
                    $('#successMessage').fadeOut();
                }, 3000);
            },
            error: function(xhr){
                $('#loadingSave').hide();
                btn.prop('disabled', false).html('Simpan');

                let errorMsg = 'Gagal menyimpan data';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }

                // Tampilkan pesan error di div #errorMessage
                $('#errorMessage').html('<i class="fas fa-times-circle"></i> ' + errorMsg).fadeIn();
                setTimeout(function(){
                    $('#errorMessage').fadeOut();
                }, 4000);
            }
        });
    });

</script>