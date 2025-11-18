<?php

namespace App\Livewire\Shared;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class AlertMessage extends Component
{
  public string $type = 'success';
  public string $message = '';
  public bool $dismissible = true;

  public function render(): View
  {
    return view('livewire.shared.alert-message');
  }

  public function dismiss(): void
  {
    $this->message = '';
  }
}
