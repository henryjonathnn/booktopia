<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Register extends Component
{
    // Custom validation messages in Indonesian
    public $messages = [
        'name.required' => 'Nama lengkap wajib diisi',
        'name.min' => 'Nama lengkap minimal 3 karakter',
        'email.required' => 'Email wajib diisi',
        'email.email' => 'Format email tidak valid',
        'email.unique' => 'Email sudah terdaftar',
        'username.required' => 'Username wajib diisi',
        'username.min' => 'Username minimal 3 karakter',
        'username.unique' => 'Username sudah digunakan',
        'password.required' => 'Password wajib diisi',
        'password.min' => 'Password minimal 6 karakter',
        'password.confirmed' => 'Konfirmasi password tidak cocok'
    ];
    
    #[Validate('required|min:3')]
    public $name = '';
    
    #[Validate('required|email|unique:users')]
    public $email = '';
    
    #[Validate('required|min:3|unique:users')]
    public $username = '';
    
    #[Validate('required|min:6|confirmed')]
    public $password = '';
    
    public $password_confirmation = '';
    
    // Validation status flags
    public $isCheckingEmail = false;
    public $isCheckingUsername = false;
    
    // Previous values for comparison
    private $previousEmail = '';
    private $previousUsername = '';
    
    public function updated($propertyName)
    {
        if ($propertyName === 'email') {
            $this->isCheckingEmail = true;
            $this->validateEmail();
        } elseif ($propertyName === 'username') {
            $this->isCheckingUsername = true;
            $this->validateUsername();
        } else {
            $this->validateOnly($propertyName, null, $this->messages);
        }
    }
    
    public function validateEmail()
    {
        // Prevent infinite loops by checking if the value has changed
        if ($this->email !== $this->previousEmail && !empty($this->email)) {
            $this->previousEmail = $this->email;
            $this->validateOnly('email', null, $this->messages);
        }
        $this->isCheckingEmail = false;
    }
    
    public function validateUsername()
    {
        // Prevent infinite loops by checking if the value has changed
        if ($this->username !== $this->previousUsername && !empty($this->username)) {
            $this->previousUsername = $this->username;
            $this->validateOnly('username', null, $this->messages);
        }
        $this->isCheckingUsername = false;
    }
    
    public function register()
    {
        $this->validate(null, $this->messages);

        try {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'username' => $this->username,
                'password' => Hash::make($this->password),
                'role' => 'USER'
            ]);

            Auth::login($user);
            
            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            $this->addError('email', 'Terjadi kesalahan saat registrasi');
        }
    }

    public function render()
    {
        return view('livewire.auth.register')
            ->layout('layouts.auth');
    }
}