<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UserExport;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil semua data pengguna
        $users = User::all();
        return view('order.admin.user', compact('users'));
    }

    
    public function indexExcel()
    {
        return Excel::download(new UserExport, 'rekap-akun.xlsx');
    }

    /**
     * Show the login form.
     */
    public function showLogin()
    {
        return view('pages.login');
    }

    /**
     * Handle user login authentication.
     */
    public function loginAuth(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8', // Ensure password has a minimum length
        ]);

        // Attempt to authenticate the user
        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect()->route('landing_page')->with('success', 'Berhasil login');
        } else {
            return redirect()->back()->with('failed', 'Gagal login');
        }
    }

    /**
     * Logout the user.
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login.auth')->with('success', 'Berhasil logout');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('akun.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,cashier', // Ensure this is in the allowed values
            'password' => 'required|min:8',
        ]);

        // Proses penyimpanan pengguna baru
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => trim($request->role), // Sanitize the input
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Pengguna berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Mengambil data pengguna berdasarkan ID
        $user = User::findOrFail($id);
        return view('akun.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi input dari form edit
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,cashier',
            'password' => 'nullable|min:8', // Password opsional
        ]);

        // Mengambil data pengguna berdasarkan ID
        $user = User::findOrFail($id);

        // Update data pengguna
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        // Cek apakah password diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Simpan perubahan
        $user->save();

        return redirect()->route('akun.home')->with('success', 'Pengguna berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Hapus pengguna berdasarkan ID
        User::destroy($id); // Lebih efisien menggunakan destroy

        return redirect()->back()->with('deleted', 'Berhasil menghapus data!');
    }
}
