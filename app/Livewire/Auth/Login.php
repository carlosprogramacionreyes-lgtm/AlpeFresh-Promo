<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.auth')]
#[Title('Iniciar sesión')]
class Login extends Component
{
    public string $identifier = '';
    public string $password = '';
    public bool $remember = false;
    public bool $showPassword = false;

    public function mount()
    {
        if (Auth::check()) {
            return redirect()->intended(route('dashboard'));
        }
    }

    public function authenticate()
    {
        $validated = $this->validate([
            'identifier' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'identifier.required' => 'Ingresa tu usuario o correo.',
            'password.required' => 'Ingresa tu contraseña.',
        ]);

        $field = filter_var($validated['identifier'], FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';

        if (! Auth::attempt([$field => $validated['identifier'], 'password' => $validated['password']], $this->remember)) {
            throw ValidationException::withMessages([
                'identifier' => __('Credenciales inválidas. Verifica tus datos e inténtalo nuevamente.'),
            ]);
        }

        $user = Auth::user();

        if (! $user->is_active) {
            Auth::logout();

            throw ValidationException::withMessages([
                'identifier' => __('Tu cuenta está desactivada. Contacta al administrador.'),
            ]);
        }

        $user->forceFill([
            'last_login_at' => now(),
        ])->save();

        session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function togglePassword(): void
    {
        $this->showPassword = ! $this->showPassword;
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
