<?php

namespace App\Livewire\Pages;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Login extends Component
{
    #[Validate('required|string|max:255')]
    public string $username = '';

    #[Validate('required')]
    public string $password = '';

    public bool $remember = false;

    public function login(): void
    {
        $this->validate();

        if (! Auth::attempt(['name' => $this->username, 'password' => $this->password], $this->remember)) {
            $this->addError('username', 'Le credenziali non sono corrette.');

            return;
        }

        session()->regenerate();

        $this->redirect(route('home'), navigate: true);
    }

    public function render()
    {
        return view('livewire.pages.login');
    }
}
