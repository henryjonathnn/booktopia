<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Register extends Component
{
    public $name = '';
    public $email = '';
    public $username = '';
    public $password = '';
    public $password_confirmation = '';

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email|unique:users',
        'username' => 'required|min:3|unique:users',
        'password' => 'required|min:6|confirmed',
    ];

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