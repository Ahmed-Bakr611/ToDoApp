<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Register extends Component
{
  public string $name = '';
  public string $email = '';
  public string $password = '';
  public string $password_confirmation = '';

  public function render(): View
  {
    return view('livewire.auth.register');
  }

  public function register()
  {
    $this->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|email|unique:users',
      'password' => 'required|string|min:6|confirmed',
    ]);

    $user = User::create([
      'name' => $this->name,
      'email' => $this->email,
      'password' => Hash::make($this->password),
    ]);

    auth()->login($user);
    session()->regenerate();

    return redirect()->intended(route('tasks.index'));
  }
}
