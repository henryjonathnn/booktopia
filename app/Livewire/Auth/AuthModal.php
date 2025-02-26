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
    public $registerStep = 1;

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

    // Tambahkan properti untuk validasi real-time
    public $isValidating = [];
    public $validationStates = [];

    public function mount($isOpen = false)
    {
        $this->isOpen = $isOpen;
    }

    protected $listeners = ['toggleAuthModal'];

    public function toggleAuthModal()
    {
        $this->isOpen = !$this->isOpen;
    }

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

    // Validasi real-time untuk email dan username
    public function validateField($field)
    {
        $this->isValidating[$field] = true;

        if ($field === 'email') {
            $this->validateOnly('email', [
                'email' => 'required|email|unique:users'
            ]);
        } elseif ($field === 'username') {
            $this->validateOnly('username', [
                'username' => 'required|min:3|unique:users'
            ]);
        }

        $this->isValidating[$field] = false;
    }

    public function updated($field)
    {
        if (in_array($field, ['email', 'username'])) {
            $this->validateField($field);
        }
    }

    public function login()
    {
        $this->validate([
            'loginEmail' => 'required|email',
            'loginPassword' => 'required'
        ]);

        if (Auth::attempt(['email' => $this->loginEmail, 'password' => $this->loginPassword], $this->remember)) {
            $user = Auth::user();
            
            if (!$user->is_active) {
                Auth::logout();
                $this->addError('loginEmail', 'Akun Anda telah dinonaktifkan. Silakan hubungi admin.');
                return;
            }

            session()->regenerate();
            $this->isOpen = false;

            // Redirect berdasarkan role
            if (in_array($user->role, ['ADMIN', 'STAFF'])) {
                return redirect()->intended(route('admin.dashboard'));
            }
            return redirect()->intended(route('dashboard'));
        }

        $this->addError('loginPassword', 'Email atau password salah');
    }

    public function register()
    {
        $this->validate();

        try {
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
        } catch (\Exception $e) {
            $this->addError('email', 'Terjadi kesalahan saat registrasi');
        }
    }

    public function getIsFormValidProperty()
    {
        if ($this->isLoginMode) {
            return !empty($this->loginEmail) && !empty($this->loginPassword);
        } else {
            if ($this->registerStep === 1) {
                return !empty($this->name) && 
                       !empty($this->email) && 
                       !empty($this->password) && 
                       !empty($this->password_confirmation) &&
                       $this->password === $this->password_confirmation;
            } else {
                return !empty($this->username);
            }
        }
    }

    public function render()
    {
        return view('livewire.auth.auth-modal');
    }
}
