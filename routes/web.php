<?php

use App\Http\Controllers\AllproductdsiController;
use App\Http\Controllers\AlokasiblmController;
use App\Http\Controllers\AlokasiController;
use App\Http\Controllers\BatokoController;
use App\Http\Controllers\BklomiController;
use App\Http\Controllers\BrokoliController;
use App\Http\Controllers\CekAcost_Controller;
use App\Http\Controllers\CekbpbrController;
use App\Http\Controllers\CekjampickController;
use App\Http\Controllers\CekpiutangreturController;
use App\Http\Controllers\CekreturController;
use App\Http\Controllers\CsvConverter;
// use App\Http\Controllers\Dashboard2Controller;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoubledspbController;
use App\Http\Controllers\DoublepiutangController;
use App\Http\Controllers\EDP_DataPBIDM;
use App\Http\Controllers\EDP_ItemCP1;
use App\Http\Controllers\EDP_TolakanIDM;
use App\Http\Controllers\GagalnpbController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\Jampickretur_Controller;
use App\Http\Controllers\KoliController;
use App\Http\Controllers\Lapbpbrtoday_Controller;
use App\Http\Controllers\Laporan_SLOMI;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LaporanseasonalController;
use App\Http\Controllers\ListKoliController;
use App\Http\Controllers\LppnolController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\NpbtdkterbentukController;
use App\Http\Controllers\OutstandingController;
use App\Http\Controllers\PajakomiController;
use App\Http\Controllers\PbklikmanualController;
use App\Http\Controllers\PBMentahIDM_Controller;
use App\Http\Controllers\PBMentahOMI_Controller;
use App\Http\Controllers\PickingHH_Controller;
use App\Http\Controllers\PlanoController;
use App\Http\Controllers\ProformaController;
use App\Http\Controllers\Rakdpdv1Controller;
use App\Http\Controllers\Rakdpdv2Controller;
use App\Http\Controllers\RekapnpbController;
use App\Http\Controllers\ReturidmController;
use App\Http\Controllers\SLController;
use App\Http\Controllers\SoissuingController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\TolakanpbController;
use App\Http\Controllers\DashboardV2Controller;
use App\Http\Controllers\Dimensi_null;
use App\Http\Controllers\EDP_ItemCP2;
use App\Http\Controllers\EDP_SLHarian;
use App\Http\Controllers\Hari_Kemarin;
use App\Http\Controllers\Loadingmobil;
use App\Http\Controllers\OMI_DBnull;
use App\Http\Controllers\Register_DSPB;
use App\Http\Controllers\Register_NRB;
use App\Http\Controllers\Stock_SPI;
use App\Http\Controllers\Userabsen_Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;

// use Illuminate\Support\Facades\DB;

// Login Routes
// Route::get('/', [LoginController::class, 'index'])->name('login');
// Route::get('/login', [LoginController::class, 'index']);
// Route::post('/login', [LoginController::class, 'handleLogin'])->name('login.submit');
// Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// Dashboard Route (pakai pengecekan session + panggil controller)
// Route::get('/dashboard', function () {
//     if (!session()->get('is_logged_in')) {
//         return redirect()->route('login')->withErrors(['login' => 'Silakan login terlebih dahulu.']);
//     }

//     // ✅ Panggil method index dari DashboardController
//     return app(DashboardController::class)->index();
// })->name('dashboard');

/*
|--------------------------------------------------------------------------
| SSO Login dari ISREPORT
|--------------------------------------------------------------------------
*/
Route::get('/sso-login', function (Request $request) {

    if (!$request->token) {
        return redirect('http://192.168.83.93:8081/login');
    }

    try {

        $json = Crypt::decryptString(
            urldecode($request->token)
        );

        $data = json_decode($json, true);

        if ($data['expired'] < time()) {
            return redirect('http://192.168.83.93:8081/login');
        }

        Session::put('userid', $data['userid']);
        Session::put('username', $data['username']);

        return redirect('/');

    } catch (\Exception $e) {

        dd($e->getMessage());

        return redirect('http://192.168.83.93:8081/login');
    }
});

/*
|--------------------------------------------------------------------------
| Dashboard Utama
|--------------------------------------------------------------------------
*/
Route::get('/', function () {

    if (!Session::has('userid')) {

        return redirect()
            ->route('session.expired')
            ->with('error', 'ANDA BELUM LOGIN ATAU SESSION HABIS');
    }

    return app(\App\Http\Controllers\DashboardV2Controller::class)
        ->index();

})->name('dashboard');


Route::get('/session-expired', function () {
    return view('session-expired');
})->name('session.expired');
/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/

Route::get('/logout', function (Request $request) {

    Session::forget('userid');
    Session::forget('username');

    Session::flush();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return redirect()->route('session.expired');

})->name('logout');
/*
|--------------------------------------------------------------------------
| Debug Session
|--------------------------------------------------------------------------
*/
Route::get('/cek-session', function () {

    dd(session()->all());

});

// Dashboard V2 Routes (prefix dashboardv2)
Route::prefix('dashboardv2')->group(function () {
    Route::get('/', [DashboardV2Controller::class, 'index'])->name('dashboardv2.index');
    Route::get('/refresh-stats', [DashboardV2Controller::class, 'refreshStats'])->name('dashboardv2.refresh-stats');
    Route::get('/sl-harian', [DashboardV2Controller::class, 'getSlHarian'])->name('dashboardv2.sl-harian');

    // PBMentah Routes
    Route::get('/pb-mentah/idm', [DashboardV2Controller::class, 'pbMentahIdm'])->name('dashboardv2.pbmentah.idm');
    Route::get('/pb-mentah/omi', [DashboardV2Controller::class, 'pbMentahOmi'])->name('dashboardv2.pbmentah.omi');

    // PBSL Routes
    Route::get('/pb-sl/idm', [DashboardV2Controller::class, 'pbSlIdm'])->name('dashboardv2.pbsl.idm');
    Route::get('/pb-sl/omi', [DashboardV2Controller::class, 'pbSlOmi'])->name('dashboardv2.pbsl.omi');
    
    // Rupiah Picking dengan sub tab
    Route::get('/rupiah-picking/all', [DashboardV2Controller::class, 'getRupiahPickingAll'])->name('dashboardv2.rupiah.all');
    Route::get('/rupiah-picking/idm', [DashboardV2Controller::class, 'getRupiahPickingIdm'])->name('dashboardv2.rupiah.idm');
    Route::get('/rupiah-picking/omi', [DashboardV2Controller::class, 'getRupiahPickingOmi'])->name('dashboardv2.rupiah.omi');
    
    // Monitoring
    Route::get('/monitor-picking', [DashboardV2Controller::class, 'getMonitorPicking'])->name('dashboardv2.picking');
    Route::get('/monitor-loading', [DashboardV2Controller::class, 'getMonitorLoading'])->name('dashboardv2.loading');
});

// ==================== DASHBOARD V1 (LAMA) ====================
// Dashboard V1 diakses melalui /dashboard
Route::prefix('dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.v1.index');
    Route::get('/get-rupiah-picking-data', [DashboardController::class, 'getRupiahPickingData']);
    Route::get('/get-monitor-picking-data', [DashboardController::class, 'getMonitorPickingData']);
    Route::get('/get-monitor-loading-data', [DashboardController::class, 'getMonitorLoadingData']);
    Route::get('/get-sl-harian-data', [DashboardController::class, 'getSlHarianData'])->name('dashboard.partials.slharian');
    Route::get('/get-pbidm-count', [DashboardController::class, 'getPbIdmCount']);
    Route::get('/get-pbomi-count', [DashboardController::class, 'getPbOmiCount']);
    Route::get('/get-wt-count', [DashboardController::class, 'getWTCount']);
    Route::get('/get-sph-count', [DashboardController::class, 'getSPHCount']);
});


// Hari Kemarin Routes (prefix dashboardv2)
Route::prefix('kemarin')->group(function () {
    Route::get('/', [Hari_Kemarin::class, 'index'])->name('kemarin.index');
    Route::get('/refresh-stats', [Hari_Kemarin::class, 'refreshStats'])->name('kemarin.refresh-stats');
    Route::get('/sl-harian', [Hari_Kemarin::class, 'getSlHarian'])->name('kemarin.sl-harian');

    // PBMentah Routes
    Route::get('/pb-mentah/idm', [Hari_Kemarin::class, 'pbMentahIdm'])->name('kemarin.pbmentah.idm');
    Route::get('/pb-mentah/omi', [Hari_Kemarin::class, 'pbMentahOmi'])->name('kemarin.pbmentah.omi');

    // PBSL Routes
    Route::get('/pb-sl/idm', [Hari_Kemarin::class, 'pbSlIdm'])->name('kemarin.pbsl.idm');
    Route::get('/pb-sl/omi', [Hari_Kemarin::class, 'pbSlOmi'])->name('kemarin.pbsl.omi');
    
    // Rupiah Picking dengan sub tab
    Route::get('/rupiah-picking/all', [Hari_Kemarin::class, 'getRupiahPickingAll'])->name('kemarin.rupiah.all');
    Route::get('/rupiah-picking/idm', [Hari_Kemarin::class, 'getRupiahPickingIdm'])->name('kemarin.rupiah.idm');
    Route::get('/rupiah-picking/omi', [Hari_Kemarin::class, 'getRupiahPickingOmi'])->name('kemarin.rupiah.omi');
    
    // Monitoring
    Route::get('/monitor-picking', [Hari_Kemarin::class, 'getMonitorPicking'])->name('kemarin.picking');
    Route::get('/monitor-loading', [Hari_Kemarin::class, 'getMonitorLoading'])->name('kemarin.loading');
});

//Testing
Route::get('/test', [TestController::class, 'index']);
Route::get('/test/result', [TestController::class, 'result'])->name('test.result');

// Master Route
Route::get('/list_idm', [MasterController::class, 'tokoIdm'])->name('toko.idm');
Route::get('/list_omi', [MasterController::class, 'tokoOmi'])->name('toko.omi');
// Route::get('/jadwal_idm', [MasterController::class, 'jadwalIdm'])->name('jadwal.idm');
Route::get('/plu_gudang', [MasterController::class, 'pludpd'])->name('plu.dpd');
Route::get('/plu_omi', [MasterController::class, 'pluomi'])->name('plu.omi');
Route::get('/so_issuing', [SoissuingController::class, 'soissuing'])->name('master.soissuing');
Route::get('/plu_dpdv2', [Rakdpdv2Controller::class, 'rakdpdv2'])->name('master.pludpdv2');
Route::get('/lpp_nol', [LppnolController::class, 'lppnol'])->name('master.lppnol');
Route::get('/plu_dpd', [Rakdpdv1Controller::class, 'rakdpdv1'])->name('master.pludpdv1');
Route::get('/pb_mentahidm', [PBMentahIDM_Controller::class, 'pbmentahidm'])->name('master.pbmentahidm');
Route::get('/pb_mentahomi', [PBMentahOMI_Controller::class, 'pbmentahomi'])->name('master.pbmentahomi');
Route::get('/stockspi', [Stock_SPI::class, 'stockspi'])->name('master.stockspi');
Route::get('/item_tidakretur', [CekreturController::class, 'itemretur'])->name('retur.itemretur');
Route::get('/dsiall', [AllproductdsiController::class, 'dsiall'])->name('master.dsiall');


//Laporan Route
Route::get('/sl_pot', [LaporanController::class, 'slpot'])->name('laporan.slpot');
Route::post('/sl_pot/fetch', [LaporanController::class, 'slpot_h'])->name('slpot.fetch');
Route::get('/wt_idm', [LaporanController::class, 'wtidm'])->name('laporan.wtidm');
Route::post('/wt_idm/fetch', [LaporanController::class, 'wtidm_h'])->name('wtidm.fetch');
Route::get('/sph_omi', [LaporanController::class, 'sphomi'])->name('laporan.sphomi');
Route::get('/awb_ipp_omi', [LaporanController::class, 'awbomi'])->name('laporan.awbomi');
Route::post('/awb_ipp_omi/fetch', [LaporanController::class, 'awbomi_h'])->name('awbomi.fetch');
Route::get('/upload_eproc', [LaporanController::class, 'eproc'])->name('laporan.eproc');
Route::post('/upload_eproc/fetch', [LaporanController::class, 'eproc_h'])->name('eproc.fetch');
Route::get('/sarana_picking', [LaporanController::class, 'sarana'])->name('laporan.sarana');
Route::post('/sarana_picking/fetch', [LaporanController::class, 'sarana_h'])->name('sarana.fetch');
Route::get('/stock_eko', [LaporanController::class, 'stock_eko'])->name('laporan.stock_eko');
Route::get('/stock_pot', [LaporanController::class, 'stock_pot'])->name('laporan.stock_pot');
Route::get('/stock_50', [LaporanController::class, 'stock50'])->name('laporan.stock50');
Route::get('/dsi_eproc', [LaporanController::class, 'dsieproc'])->name('laporan.dsieproc');
Route::get('rekap_npb', [RekapnpbController::class, 'rekapnpb'])->name('laporan.rekapnpb');
Route::post('rekap_npb/table', [RekapnpbController::class, 'getData'])->name('rekapnpb.table');
Route::get('pajak_omi', [PajakomiController::class, 'pajakomi'])->name('laporan.pajakomi');
Route::post('pajak_omi/table', [PajakomiController::class, 'getData'])->name('pajakomi.table');
Route::get('bkl_omi', [BklomiController::class, 'bklomi'])->name('laporan.bklomi');
Route::post('bkl_omi/table', [BklomiController::class, 'getData'])->name('bklomi.table');
Route::get('tolakan_pb', [TolakanpbController::class, 'tolakanpb'])->name('laporan.tolakanpb');
Route::post('tolakan_pb/table', [TolakanpbController::class, 'getData'])->name('tolakanpb.table');
Route::get('/brokoli', [BrokoliController::class, 'brokoli'])->name('monitoring.brokoli');
Route::post('/brokoli/fetch', [BrokoliController::class, 'brokoli_h'])->name('brokoli.table');
Route::get('sl_omi', [Laporan_SLOMI::class, 'slomi'])->name('laporan.slomi');
Route::post('sl_omi/table', [Laporan_SLOMI::class, 'getData'])->name('slomi.table');
Route::get('/dimensinull', [Dimensi_null::class, 'dimensinull'])->name('laporan.dimensinull');

//Monitoring Route
Route::get('/pb_idm', [MonitoringController::class, 'pbisli'])->name('monitoring.pbi');
Route::get('/pb_idm', [MonitoringController::class, 'pbisli'])->name('monitoring.sli');
Route::get('/pb_omi', [MonitoringController::class, 'pboslo'])->name('monitoring.pbo');
Route::get('/pb_omi', [MonitoringController::class, 'pboslo'])->name('monitoring.slo');
Route::get('/time_picking', [MonitoringController::class, 'timepicking'])->name('monitoring.timepicking');
Route::post('/time_picking/fetch', [MonitoringController::class, 'timepicking_h'])->name('timepicking.fetch');
Route::get('/m_picking', [MonitoringController::class, 'monitoringpicking'])->name('monitoring.monitoringpicking');
Route::post('/m_picking/show-item', [MonitoringController::class, 'monitoring_showItem'])->name('monitoringpicking.fetch');
Route::get('/jampick', [CekjampickController::class, 'jampick'])->name('monitoring.jampick');
Route::post('jampick/table', [CekjampickController::class, 'getData'])->name('jampick.table');
Route::get('/pickinghh', [PickingHH_Controller::class, 'pickinghh'])->name('monitoring.pickinghh');
Route::post('/pickinghh/fetch', [PickingHH_Controller::class, 'pickinghh_h'])->name('pickinghh.fetch');
Route::get('/dbnull', [OMI_DBnull::class, 'dbnull'])->name('monitoring.dbnull');
Route::post('dbnull/table', [OMI_DBnull::class, 'getData'])->name('dbnull.table');
Route::get('loadingmobil', [Loadingmobil::class, 'loadingmobil'])->name('monitoring.loadingmobil');
Route::post('loadingmobil/table', [Loadingmobil::class, 'getData'])->name('loadingmobil.table');



//Listkoli / Monitoring DSPB
Route::get('m_dspb', [ListKoliController::class, 'index'])->name('listkoli.index');
Route::post('m_dspb/table', [ListKoliController::class, 'getTableData'])->name('listkoli.table');
Route::post('m_dspb/items', [ListKoliController::class, 'getItemsData'])->name('listkoli.items');
Route::post('/m_dspb/cetak', [ListKoliController::class, 'cetak'])->name('listkoli.cetak');
Route::get('koli', [KoliController::class, 'koli'])->name('monitoring.koli');
Route::post('koli/table', [KoliController::class, 'getData'])->name('koli.table');




//RETUR
Route::get('bpbr', [CekbpbrController::class, 'index'])->name('cekbpbr.index');
Route::post('bpbr/table', [CekbpbrController::class, 'getData'])->name('bpbr.table');
Route::post('/bpbr/export', [CekbpbrController::class, 'export'])->name('bpbr.export');
Route::get('returidm', [ReturidmController::class, 'returidm'])->name('retur.returidm');
Route::post('returidm/table', [ReturidmController::class, 'getData'])->name('returidm.table');
Route::get('outstanding_retur', [OutstandingController::class, 'outstandingretur'])->name('retur.outstandingretur');
Route::post('outstanding_retur/table', [OutstandingController::class, 'getData'])->name('outstanding.table');
Route::get('retur_proforma', [ProformaController::class, 'proforma'])->name('retur.proforma');
Route::post('retur_proforma/table', [ProformaController::class, 'getData'])->name('proforma.table');
Route::get('ba_toko', [BatokoController::class, 'batoko'])->name('retur.batoko');
Route::post('ba_toko/table', [BatokoController::class, 'getData'])->name('batoko.table');
Route::get('piutang_retur', [CekpiutangreturController::class, 'piutangretur'])->name('retur.piutangretur');
Route::post('piutang_retur/table', [CekpiutangreturController::class, 'getData'])->name('piutangretur.table');
Route::get('/cekacost', [CekAcost_Controller::class, 'cekacost'])->name('retur.cekacost');
Route::get('/cekjampick', [Jampickretur_Controller::class, 'cekjampick'])->name('retur.cekjampick');
Route::post('cekjampick/table', [Jampickretur_Controller::class, 'getData'])->name('cekjampick.table');
Route::get('/bpbrtoday', [Lapbpbrtoday_Controller::class, 'bpbrtoday'])->name('retur.bpbrtoday');
Route::get('/cekabsenretur', [Userabsen_Controller::class, 'cekuserabsen'])->name('retur.cekabsenretur');
Route::post('cekabsenretur/table', [Userabsen_Controller::class, 'getData'])->name('cekabsenretur.table');


//PROBLEM
Route::get('/double_dspb', [DoubledspbController::class, 'doubledspb'])->name('problem.doubledspb');
Route::get('/double_piutang', [DoublepiutangController::class, 'doublepiutang'])->name('problem.doublepiutang');
Route::get('/gagal_npb', [GagalnpbController::class, 'gagalnpb'])->name('problem.gagalnpb');
Route::get('/npb_tidakterbentuk', [NpbtdkterbentukController::class, 'npbtidakterbentuk'])->name('problem.npbtidakterbentuk');

//EDP
Route::get('data_pbidm', [EDP_DataPBIDM::class, 'datapbidm'])->name('edp.datapbidm');
Route::post('data_pbidm/table', [EDP_DataPBIDM::class, 'getData'])->name('datapbidm.table');
Route::get('tolakan_idm', [EDP_TolakanIDM::class, 'index'])->name('edp.tolakanidm');
Route::post('tolakan_idm/table', [EDP_TolakanIDM::class, 'getData2'])->name('tolakanidm.table');
Route::get('/sl', [SLController::class, 'sl']);
Route::get('/sl/result', [SLController::class, 'result'])->name('sl.result');
Route::get('itemcp1', [EDP_ItemCP1::class, 'itemcp1'])->name('edp.itemcp1');
Route::post('itemcp1/table', [EDP_ItemCP1::class, 'getData'])->name('itemcp1.table');
Route::get('itemcp2', [EDP_ItemCP2::class, 'itemcp2'])->name('edp.itemcp2');
Route::post('itemcp2/table', [EDP_ItemCP2::class, 'getData'])->name('itemcp2.table');
Route::get('servicelevelharian', [EDP_SLHarian::class, 'slharian'])->name('edp.slharian');
Route::post('servicelevelharian/table', [EDP_SLHarian::class, 'getData'])->name('slharian.table');

//SEASONAL
Route::get('/alokasi_seasonal', [AlokasiController::class, 'alokasiseasonal'])->name('seasonal.alokasi');
Route::get('/laporan_seasonal', [LaporanseasonalController::class, 'laporanseasonal'])->name('seasonal.laporan');
Route::post('laporan_seasonal/table', [LaporanseasonalController::class, 'getData'])->name('laporan.table');
Route::get('/alokasi_belum', [AlokasiblmController::class, 'alokasiblm'])->name('seasonal.alokasiblm');

//KLIK
Route::get('picking_manual', [PbklikmanualController::class, 'pickingmanual'])->name('klik.pickingmanual');
Route::post('picking_manual/table', [PbklikmanualController::class, 'getData'])->name('pickingmanual.table');

//INVENTORY
Route::get('/inquiry', [InquiryController::class, 'inquiry']);
Route::get('/inquiry/result', [InquiryController::class, 'result'])->name('inquiry.result');
Route::get('/plano', [PlanoController::class, 'plano']);
Route::get('/plano/result', [PlanoController::class, 'result'])->name('plano.result');

//Converter
Route::get('/pbconverter/', [CsvConverter::class, 'converter']);
Route::post('/pbconverter/process', [CsvConverter::class, 'process'])->name('csv.process');
Route::get('/sync-nama-toko', [Register_NRB::class, 'syncNamaToko']);

//Mysql
Route::prefix('register-nrb')->group(function () {

    Route::get('/', [Register_NRB::class, 'index'])
        ->name('register-nrb.index');

    Route::get('/load-page/{page}', [Register_NRB::class, 'loadPage'])
        ->name('register-nrb.loadpage');

    Route::post('/store', [Register_NRB::class, 'store'])
        ->name('register-nrb.store');

    Route::get('/edit/{id}', [Register_NRB::class, 'edit'])
        ->name('register-nrb.edit');

    Route::post('/update/{id}', [Register_NRB::class, 'update'])
        ->name('register-nrb.update');

    Route::delete('/delete/{id}', [Register_NRB::class, 'destroy'])
        ->name('register-nrb.destroy');

    Route::get('/export', [Register_NRB::class, 'export'])
        ->name('register-nrb.export');


});

Route::prefix('register-dspb')->group(function () {

    Route::get('/', [Register_DSPB::class, 'index'])
        ->name('register-dspb.index');

    Route::get('/load-page/{page}', [Register_DSPB::class, 'loadPage'])
        ->name('register-dspb.loadpage');

    Route::post('/store', [Register_DSPB::class, 'store'])
        ->name('register-dspb.store');

    Route::get('/get-kode-toko', [Register_DSPB::class, 'getKodeToko'])
        ->name('register-dspb.getKodeToko');

    Route::get('/get-no-sj', [Register_DSPB::class, 'getNoSj'])
        ->name('register-dspb.getNoSj');

    Route::get('/export', [Register_DSPB::class, 'export'])
        ->name('register-dspb.export');

    Route::post('/update/{id}', [Register_DSPB::class, 'update'])
        ->name('register-dspb.update');
    Route::delete('/delete/{id}', [Register_DSPB::class, 'destroy'])
        ->name('register-dspb.destroy');    

});