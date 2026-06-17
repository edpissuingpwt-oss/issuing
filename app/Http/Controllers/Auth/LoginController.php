<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    // Tampilkan halaman login
    public function index()
    {
        return view('auth.login');
    }

    // Proses login
    public function handleLogin(Request $request)
    {
        $credentials = $request->validate([
            'userid'   => 'required|string',
            'password' => 'required',
        ], [
            'userid.required'   => 'UserID harus diisi.',
            'password.required' => 'Password harus diisi.',
        ]);

        // Ambil user dari database
        $user = DB::table('igrpwt.tbmaster_user')
            ->where('userid', $credentials['userid'])
            ->first();

        // Cek apakah user ditemukan dan password cocok (plaintext)
        if ($user && $credentials['password'] === $user->userpassword) {
            // Simpan session login manual
            Session::put('is_logged_in', true);
            Session::put('userid', $user->userid);
            Session::put('username', $user->username ?? '');

            return redirect()->intended('/dashboard');
        }

        // Jika gagal login
        return back()->withErrors([
            'userid' => 'UserID atau password salah.',
        ])->onlyInput('userid');
    }

    // Logout user
    public function logout(Request $request)
    {
        Session::flush(); // Hapus semua session
        return redirect('/login')->with('success', 'Anda telah logout.');
    }
}