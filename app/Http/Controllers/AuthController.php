<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    // public function store(Request $request)
    // {
    //     $credentials = $request->validate([
    //         'name' => ['required', 'string', 'max:255'],
    //         'email' => ['required', 'email', 'unique:users,email'],
    //         'coveruser' => ['nullable', 'file', 'image', 'mimes:jpeg,png,jpg', 'max:5000'],
    //         'password' => ['required', 'string', 'confirmed', 'min:8', 'max:255'],
    //     ]);

    //     // Set role default sebagai 'Admin'
    //     $credentials['role'] = 'Admin';

    //     // Hash password
    //     $credentials['password'] = Hash::make($credentials['password']);

    //     // Proses penyimpanan file coveruser
    //     if ($request->hasFile('coveruser')) {
    //         $credentials['coveruser'] = $request->file('coveruser')->store('coverusers');
    //     }

    //     // Buat user baru
    //     $user = User::create($credentials);

    //     // Langsung login setelah register
    //     Auth::login($user);

    //     return redirect()->route('admin.dashboard');
    // }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Redirect sesuai role
            if ($user->role === 'Admin') {
                return redirect()->route('admin.dashboard'); // ganti sesuai nama rute admin-mu
            } else {
                return redirect()->route('admin.dashboard'); // ganti sesuai nama rute user-mu
            }
        }

        return back()->withErrors([
            'email' => 'email tidak terdaftar.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
