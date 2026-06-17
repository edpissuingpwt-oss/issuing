<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CsvConverter extends Controller
{
    // Fungsi untuk membuat Key/IV 16-byte persis logika WinForm
    private function getKeyIv()
    {
        $pass = "idm123";
        $bytes = utf8_encode($pass);
        $array = array_fill(0, 16, 0); // Membuat array 16 byte isi nol
        
        $chars = str_split($pass);
        foreach ($chars as $index => $char) {
            if ($index < 16) {
                $array[$index] = ord($char);
            }
        }
        
        // Mengubah array menjadi string biner
        return pack('C*', ...$array);
    }

    public function converter()
    {
        return view('converter.index');
    }

    public function process(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
            'action' => 'required|in:encrypt,decrypt'
        ]);

        $file = $request->file('file');
        $action = $request->action;
        $keyIv = $this->getKeyIv();

        // Membaca file baris demi baris
        $lines = file($file->getRealPath(), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $output = "";

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;

            if ($action === 'decrypt') {
                $decoded = base64_decode($line);
                // Menggunakan AES-128-CBC dengan Key dan IV yang sama
                $decrypted = openssl_decrypt($decoded, 'AES-128-CBC', $keyIv, OPENSSL_RAW_DATA, $keyIv);
                
                if ($decrypted !== false) {
                    $output .= $decrypted . "\r\n";
                }
            } else {
                $encrypted = openssl_encrypt($line, 'AES-128-CBC', $keyIv, OPENSSL_RAW_DATA, $keyIv);
                $output .= base64_encode($encrypted) . "\r\n";
            }
        }

        if (empty($output)) {
            return back()->with('error', 'Proses gagal. Cek apakah file benar-benar terenkripsi dengan pass idm123.');
        }

        // Bersihkan output buffer
        if (ob_get_length()) ob_end_clean();

        // Ambil nama asli file (contoh: PBSL012383.csv)
        $originalName = $file->getClientOriginalName();

        // Ambil ekstensi file (contoh: csv)
        $extension = $file->getClientOriginalExtension();

        // Ambil nama file tanpa ekstensi (contoh: PBSL012383)
        $fileNameOnly = pathinfo($originalName, PATHINFO_FILENAME);

        // Tentukan suffix berdasarkan aksi
        $suffix = ($action == 'encrypt' ? '_ENC' : '_DEC');

        // Gabungkan menjadi nama baru (contoh: PBSL012383_ENC.csv)
        $newFileName = $fileNameOnly . $suffix . '.' . $extension;

        // Kembalikan sebagai download
        return response($output)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $newFileName . '"');
    }
}