<?php

namespace App\Http\Controllers;

use App\Exports\SerahTerimaExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class Register_DSPB extends Controller
{
    public function index()
    {
        return view('layouts.navbar-sertim');
    }

    public function loadPage($page)
    {
        if ($page == 'input') {
            return view('register-dspb.input');
        }

        if ($page == 'report') {
            $idm = DB::connection('mysql')
                ->table('serah_terima_idm')
                ->select(DB::raw("'IDM' as tipe"), 
                    'id', 'tanggal', 'jam', 'kode_toko', 'no_sj', 'nama_checker',
                    'total_dspb', 'bronjong', 'kontainer', 'kardus', 'susu', 'rokok',
                    'detail_rokok', 'keterangan', 'create_dt', 'create_by', 'modify_dt', 'modify_by')
                ->get();
            $omi = DB::connection('mysql')
                ->table('serah_terima_omi')
                ->select(DB::raw("'OMI' as tipe"), 
                    'id', 'tanggal', 'jam', 'kode_toko', 'no_sj', 'nama_checker',
                    'total_dspb', 'bronjong', 'kontainer', 'kardus', 'susu', 'rokok',
                    'detail_rokok', 'keterangan', 'create_dt', 'create_by', 'modify_dt', 'modify_by')
                ->get();
            $register = $idm->concat($omi)->sortByDesc('id');
            return view('register-dspb.report', compact('register'));
        }

        abort(404);
    }

    /*
    |--------------------------------------------------------------------------
    | GET KODE TOKO BERDASARKAN TIPE (AJAX)
    |--------------------------------------------------------------------------
    */
    public function getKodeToko(Request $request)
    {
        $tipe = $request->query('tipe');

        if ($tipe == 'IDM') {
            $query = "
                SELECT DISTINCT tko_kodeomi as kode, tko_namaomi nama
                FROM igrpwt.tbmaster_tokoigr
                WHERE tko_kodesbu = 'I'
                  AND tko_tgltutup IS NULL
                ORDER BY tko_kodeomi
            ";
        } elseif ($tipe == 'OMI') {
            $query = "
                SELECT DISTINCT tko_kodeomi as kode, tko_namaomi nama
                FROM igrpwt.tbmaster_tokoigr
                WHERE tko_kodesbu = 'O'
                  AND tko_tgltutup IS NULL
                ORDER BY tko_kodeomi
            ";
        } else {
            return response()->json([]);
        }

        $data = DB::connection('pgsql')->select($query);
        return response()->json($data);
    }

    public function getNoSj(Request $request)
    {
        $kodeToko = $request->query('kode_toko');
        if (!$kodeToko) {
            return response()->json([]);
        }

        // Ganti dengan koneksi yang sesuai (pgsql)
        $data = DB::connection('pgsql')->select("
            SELECT no_sj, tanggal 
            FROM (
                SELECT DISTINCT ON (rpb_idsuratjalan) 
                    rpb_idsuratjalan as no_sj, 
                    to_char(rpb_create_dt, 'YYYY-MM-DD') as tanggal,
                    rpb_create_dt as tgl
                FROM igrpwt.tbtr_realpb
                WHERE rpb_kodeomi = ?
                AND rpb_create_dt >= NOW() - INTERVAL '14 days'
                ORDER BY rpb_idsuratjalan, rpb_create_dt DESC
            ) sub
            ORDER BY tgl DESC
        ", [$kodeToko]);

        $options = [];
        foreach ($data as $item) {
            $options[] = [
                'value' => $item->no_sj,
                'text'  => $item->no_sj . ' (' . $item->tanggal . ')'
            ];
        }

        return response()->json($options);
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN DATA KE MYSQL (BEDA TABEL BERDASARKAN TIPE)
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        // Validasi dasar
        $request->validate([
            'tipe_serah_terima' => 'required|in:IDM,OMI',
            'kode_toko'         => 'required',
            'no_sj'             => 'required',
            'nama_checker'      => 'required',
            'jam'               => 'nullable|date_format:H:i',
        ]);

        // Tentukan tabel berdasarkan tipe
        $table = ($request->tipe_serah_terima == 'IDM') ? 'serah_terima_idm' : 'serah_terima_omi';

        // VALIDASI DOUBLE NO SJ (cek apakah no_sj sudah ada di tabel yang sesuai)
        $exists = DB::connection('mysql')->table($table)
                    ->where('no_sj', $request->no_sj)
                    ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'No SJ "' . $request->no_sj . '" sudah terdaftar pada tipe ' . $request->tipe_serah_terima . '.'
            ], 422);
        }

        // Format jam
        $jam = $request->jam ? $request->jam . ':00' : now()->format('H:i:s');

        $data = [
            'tanggal'        => now()->format('Y-m-d'),
            'jam'            => $jam,
            'kode_toko'      => $request->kode_toko,
            'no_sj'          => $request->no_sj,
            'nama_checker'   => strtoupper($request->nama_checker),
            'total_dspb'     => $request->total_dspb ?? 0,
            'bronjong'       => $request->bronjong ?? 0,
            'kontainer'      => $request->kontainer ?? 0,
            'kardus'         => $request->kardus ?? 0,
            'susu'           => $request->susu ?? 0,
            'rokok'          => $request->rokok ?? 0,
            'detail_rokok'   => $request->detail_rokok,
            'keterangan'     => strtoupper($request->keterangan ?? ''),
            'create_dt'      => now(),
            'create_by'      => strtoupper($request->nama_checker),
            'modify_dt'      => now(),
            'modify_by'      => strtoupper($request->nama_checker),
        ];

        DB::connection('mysql')->table($table)->insert($data);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan ke ' . strtoupper($table)
        ]);
    }

    public function export(Request $request)
    {
        $tipe = $request->query('tipe');
        $tanggal = $request->query('tanggal');
        $search = $request->query('search');

        // IDM
        $idmData = DB::connection('mysql')
            ->table('serah_terima_idm')
            ->selectRaw("'IDM' as tipe, serah_terima_idm.*")
            ->get();

        // OMI
        $omiData = DB::connection('mysql')
            ->table('serah_terima_omi')
            ->selectRaw("'OMI' as tipe, serah_terima_omi.*")
            ->get();

        // Gabung data
        $data = $idmData->concat($omiData);

        // FILTER TIPE
        if ($tipe) {
            $data = $data->where('tipe', $tipe);
        }

        // FILTER TANGGAL
        if ($tanggal) {
            $data = $data->where('tanggal', $tanggal);
        }

        // FILTER SEARCH
        if ($search) {

            $searchLower = strtolower($search);

            $data = $data->filter(function ($item) use ($searchLower) {

                return stripos($item->kode_toko, $searchLower) !== false ||
                    stripos($item->no_sj, $searchLower) !== false ||
                    stripos($item->nama_checker, $searchLower) !== false;

            });

        }

        // SORT
        $data = $data->sortByDesc(function ($item) {
            return $item->tanggal . ' ' . $item->jam;
        })->values();

        // EXPORT
        return Excel::download(
            new SerahTerimaExport($data),
            'Serah_Terima_DSPB_' . now()->format('Ymd_His') . '.xlsx'
        );
    }

    public function update(Request $request, $id)
    {
        $table = $request->tipe == 'IDM'
            ? 'serah_terima_idm'
            : 'serah_terima_omi';

        DB::connection('mysql')
            ->table($table)
            ->where('id', $id)
            ->update([
                'total_dspb'    => $request->total_dspb,
                'bronjong'      => $request->bronjong,
                'kontainer'     => $request->kontainer,
                'kardus'        => $request->kardus,
                'susu'          => $request->susu,
                'rokok'         => $request->rokok,
                'detail_rokok'  => $request->detail_rokok,
                'keterangan'    => $request->keterangan,

                'modify_dt'     => now(),
                'modify_by'     => $request->modify_by,
            ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $tipe = $request->tipe;

        if (!$tipe) {
            return response()->json([
                'success' => false,
                'message' => 'Tipe tidak dikirim'
            ], 400);
        }

        $table = $tipe == 'IDM'
            ? 'serah_terima_idm'
            : 'serah_terima_omi';

        $deleted = DB::connection('mysql')
                        ->table($table)
                        ->where('id', $id)
                        ->delete();

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil dihapus'
        ]);
    }
}