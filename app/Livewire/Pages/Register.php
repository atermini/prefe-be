<?php

namespace App\Livewire\Pages;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Register extends Component
{
    #[Validate('required|string|max:255|unique:users,name')]
    public string $name = '';

    #[Validate('required|string|min:8|confirmed')]
    public string $password = '';

    public string $password_confirmation = '';

    public function register(): void
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'password' => Hash::make($this->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        $this->redirect(route('home'), navigate: true);
    }

    public function render()
    {
        return view('livewire.pages.register');
    }
}
