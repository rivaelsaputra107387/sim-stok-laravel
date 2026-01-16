<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    // Menampilkan profil pengguna yang sedang login
    public function show()
    {
        $user = Auth::user(); // Mendapatkan pengguna yang sedang login
        return view('admin.profile.show', compact('user'));
    }

    // Menampilkan form edit profil pengguna
    public function edit()
    {
        $user = Auth::user(); // Mendapatkan pengguna yang sedang login
        return view('admin.profile.edit', compact('user'));
    }

    // Memperbarui profil pengguna
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        // Update data pengguna
        $user->update($validatedData);

        return redirect()->route('admin.profile.show')->with('success', 'Profil berhasil diperbarui!');
    }

    // Menampilkan form ubah password
    public function editPassword()
    {
        return view('admin.profile.edit-password');
    }

    // Memperbarui password pengguna
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $validatedData = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Periksa apakah password lama sesuai
        if (Hash::check($validatedData['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
        }

        // Update password
        $user->update(['password' => Hash::make($validatedData['password'])]);

        return redirect()->route('admin.profile.show')->with('success', 'Password berhasil diperbarui!');
    }
}
