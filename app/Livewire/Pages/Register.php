<?php

namespace App\Livewire\Pages;

use App\Models\InviteCode;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

    #[Validate('required|string')]
    public string $inviteCode = '';

    public function register(): void
    {
        $this->validate();

        $invite = InviteCode::query()
            ->where('code', strtoupper(trim($this->inviteCode)))
            ->available()
            ->first();

        if (! $invite) {
            $this->addError('inviteCode', 'Codice invito non valido o esaurito.');

            return;
        }

        DB::transaction(function () use ($invite): void {
            $invite->lockForUpdate()->first();

            if ($invite->isExhausted()) {
                $this->addError('inviteCode', 'Codice invito esaurito.');

                return;
            }

            $invite->increment('uses_count');

            $user = User::create([
                'name' => $this->name,
                'password' => Hash::make($this->password),
            ]);

            event(new Registered($user));

            Auth::login($user);
        });

        if (Auth::check()) {
            $this->redirect(route('home'), navigate: true);
        }
    }

    public function render()
    {
        return view('livewire.pages.register');
    }
}
