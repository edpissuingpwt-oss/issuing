<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RegisterNrbExport;

class Register_NRB extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | MAIN PAGE
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        return view('layouts.navbar-register');
    }

    /*
    |--------------------------------------------------------------------------
    | LOAD AJAX PAGE
    |--------------------------------------------------------------------------
    */

    public function loadPage($page)
    {
        /*
        |--------------------------------------------------------------------------
        | INPUT PAGE
        |--------------------------------------------------------------------------
        */

        if ($page == 'input') {

            $toko = DB::connection('pgsql')->select("
                SELECT DISTINCT
                    tko_kodeomi,
                    tko_namaomi
                FROM
                    igrpwt.tbmaster_tokoigr
                WHERE
                    tko_kodesbu = 'I'
                    AND tko_tgltutup IS NULL
                ORDER BY
                    tko_kodeomi
            ");

            return view('register-nrb.input', compact('toko'));
        }

        /*
        |--------------------------------------------------------------------------
        | REPORT PAGE
        |--------------------------------------------------------------------------
        */

        if ($page == 'report') {

            /*
            |--------------------------------------------------------------------------
            | DATA MYSQL
            |--------------------------------------------------------------------------
            */

            $register = DB::connection('mysql')
                ->table('register_nrb')
                ->orderBy('id', 'desc')
                ->get();

            return view('register-nrb.report', compact('register'));
        }


        abort(404);
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN DATA KE MYSQL
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([
            'kode_toko'     => 'required',
            'nama_toko'     => 'required',
            'nama_register' => 'required',
            'no_nrb'        => 'required|numeric',
            'koli'          => 'required|numeric',
            'palet'         => 'required',
            'keterangan'    => 'required',
        ]);

        // Cek duplikat kombinasi toko + nrb
        $exists = DB::connection('mysql')
                    ->table('register_nrb')
                    ->where('toko', $request->kode_toko)
                    ->where('nrb', $request->no_nrb)
                    ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Kombinasi Kode Toko "' . $request->kode_toko . '" dan No NRB "' . $request->no_nrb . '" sudah ada.'
            ], 422);
        }

        // Jika tidak ada duplikat, lanjut insert
        DB::connection('mysql')
            ->table('register_nrb')
            ->insert([
                'tanggal'       => now()->format('Y-m-d'),
                'jam'           => now()->format('H:i:s'),
                'toko'          => $request->kode_toko,
                'nama_toko' => strtoupper($request->nama_toko),
                'nrb'           => $request->no_nrb,
                'koli'          => $request->koli,
                'palet'         => $request->palet,
                'nama_register' => strtoupper($request->nama_register),
                'keterangan'    => strtoupper($request->keterangan),
                'create_dt'     => now(),
                'create_by'     => strtoupper($request->nama_register),
                'modify_dt'     => now(),
                'modify_by'     => strtoupper($request->nama_register),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan'
        ]);
    }
    
    public function edit($id)
    {
        $data = DB::connection('mysql')
            ->table('register_nrb')
            ->where('id', $id)
            ->first();

        return response()->json($data);
    }

    public function update(Request $request, $id)
    {   
        $request->validate([
            'modify_by' => 'required'
        ]);
        
        DB::connection('mysql')
            ->table('register_nrb')
            ->where('id', $id)
            ->update([

                'toko'          => $request->kode_toko,
                'nama_toko'     => $request->nama_toko,
                'nrb'           => $request->no_nrb,
                'koli'          => $request->koli,
                'palet'         => $request->palet,

                'nama_register' => strtoupper($request->nama_register),

                'keterangan'    => strtoupper($request->keterangan),

                'modify_dt'     => now(),

                'modify_by'     => strtoupper($request->modify_by),

            ]);

        return response()->json([

            'success' => true,
            'message' => 'Data berhasil diupdate'

        ]);
    }

    public function destroy($id)
    {
        $data = DB::connection('mysql')
            ->table('register_nrb')
            ->where('id', $id)
            ->first();

        if ($data) {
            // Ubah object ke array dan format jam agar tanpa mikrodetik
            $backup = (array) $data;
            
            // Jika jam berisi mikrodetik, bersihkan
            if (isset($backup['jam'])) {
                $backup['jam'] = date('H:i:s', strtotime($backup['jam']));
            }
            
            $backup['deleted_at'] = now()->format('Y-m-d H:i:s');
            $backup['deleted_by'] = $data->modify_by ?? 'system';

            DB::connection('mysql')
                ->table('register_nrb_backup')
                ->insert($backup);

            DB::connection('mysql')
                ->table('register_nrb')
                ->where('id', $id)
                ->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus'

        ]);
    }

    public function export(Request $request)
    {
        $tanggal = $request->tanggal;
        $search  = $request->search;

        $query = DB::connection('mysql')
                    ->table('register_nrb');

        // FILTER TANGGAL
        if (!empty($tanggal)) {

            $query->whereDate('tanggal', $tanggal);

        }

        // FILTER SEARCH
        if (!empty($search)) {

            $search = strtolower($search);

            $query->where(function ($q) use ($search) {

                $q->whereRaw('LOWER(toko) LIKE ?', ["%{$search}%"])
                ->orWhereRaw('LOWER(nrb) LIKE ?', ["%{$search}%"])
                ->orWhereRaw('LOWER(nama_register) LIKE ?', ["%{$search}%"]);

            });

        }

        $data = $query
            ->orderBy('tanggal', 'desc')
            ->get();

        $filename = 'REGISTER_NRB_' . now()->format('d-m-Y') . '.xlsx';

        return Excel::download(
            new RegisterNrbExport($data),
            $filename
        );
    }
}