<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Eloquent\EloquentGenericCrudRepository;

class UserController extends Controller
{
    private EloquentGenericCrudRepository $repo;

    public function __construct()
    {
        $this->repo = new EloquentGenericCrudRepository(new User());
    }

    // Show login form
    public function showLoginForm()
    {
        return view('auth.login'); // create resources/views/auth/login.blade.php
    }

    // Handle login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('tasks.index'));
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }

    // Show register form
    public function showRegisterForm()
    {
        return view('auth.register'); // create resources/views/auth/register.blade.php
    }

    // Handle registration
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:6',
        ]);

        $user = $this->repo->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);

        return redirect('/');
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
