<?php

namespace App\Livewire\Auth;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class Login extends Component
{
  public string $email = '';
  public string $password = '';
  public bool $remember = false;

  public function render(): View
  {
    return view('livewire.auth.login');
  }

  public function login()
  {
    $this->validate([
      'email' => 'required|email',
      'password' => 'required|string',
    ]);

    if (auth()->attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
      session()->regenerate();
      return redirect()->intended(route('tasks.index'));
    }

    $this->addError('email', 'The provided credentials do not match our records.');
  }
}
