<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthModal extends Component
{
    public $isOpen = false;
    public $isLoginMode = true;

    // Login properties
    public $loginEmail = '';
    public $loginPassword = '';
    public $remember = false;

    // Register properties
    public $name = '';
    public $email = '';
    public $username = '';
    public $password = '';
    public $password_confirmation = '';

    protected function rules()
    {
        return [
            'loginEmail' => 'required|email',
            'loginPassword' => 'required',

            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'username' => 'required|min:3|unique:users',
            'password' => 'required|min:6|confirmed',
        ];
    }

    public function toggleMode()
    {
        $this->isLoginMode = !$this->isLoginMode;
        $this->resetValidation();
        $this->reset(['loginEmail', 'loginPassword', 'name', 'email', 'username', 'password', 'password_confirmation']);
    }

    public function login()
    {
        $this->validateOnly('loginEmail', ['loginEmail' => 'required|email']);
        $this->validateOnly('loginPassword', ['loginPassword' => 'required']);

        $user = User::where('email', $this->loginEmail)->first();

        if (!$user) {
            $this->addError('loginEmail', 'Email tidak terdaftar');
            return;
        }

        if (!$user->is_active) {
            $this->addError('loginEmail', 'Akun Anda telah dinonaktifkan. Silakan hubungi admin.');
            return;
        }

        if (Auth::attempt(['email' => $this->loginEmail, 'password' => $this->loginPassword], $this->remember)) {
            session()->regenerate();
            $this->isOpen = false;
            return redirect()->intended(route('dashboard'));
        }

        $this->addError('loginPassword', 'Password salah');
    }

    public function register()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'username' => $this->username,
            'password' => Hash::make($this->password),
            'role' => 'USER'
        ]);

        Auth::login($user);

        $this->isOpen = false;
        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.auth.auth-modal');
    }
}
