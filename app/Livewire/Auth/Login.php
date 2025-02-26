<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $user = Auth::user();
            
            if (!$user->is_active) {
                Auth::logout();
                $this->addError('email', 'Akun Anda telah dinonaktifkan. Silakan hubungi admin.');
                return;
            }

            session()->regenerate();

            if (in_array($user->role, ['ADMIN', 'STAFF'])) {
                return redirect()->intended(route('admin.dashboard'));
            }
            return redirect()->intended(route('dashboard'));
        }

        $this->addError('password', 'Email atau password salah');
    }

    public function render()
    {
        return view('livewire.auth.login')
            ->layout('layouts.auth');
    }
} 