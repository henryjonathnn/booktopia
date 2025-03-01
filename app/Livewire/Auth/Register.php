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
        'password_confirmation.required' => 'Konfirmasi password wajib diisi',
        'password_confirmation.same' => 'Konfirmasi password tidak cocok dengan password'
    ];
    
    public $name = '';
    public $email = '';
    public $username = '';
    public $password = '';
    public $password_confirmation = '';
    
    // Validation status flags
    public $isCheckingEmail = false;
    public $isCheckingUsername = false;
    
    // Previous values for comparison
    private $previousEmail = '';
    private $previousUsername = '';

    // Definisikan rules terpisah untuk validasi real-time
    protected function rules()
    {
        return [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'username' => 'required|min:3|unique:users',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password'
        ];
    }

    // Rules untuk validasi real-time per field
    protected function getRealTimeValidationRules($field)
    {
        $rules = [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'username' => 'required|min:3|unique:users',
            'password' => 'required|min:6',
            'password_confirmation' => [
                'required',
                function($attribute, $value, $fail) {
                    if (!empty($value) && $value !== $this->password) {
                        $fail('Konfirmasi password tidak cocok dengan password');
                    }
                }
            ]
        ];

        return [$field => $rules[$field]];
    }
    
    public function updated($field)
    {
        // Khusus untuk password_confirmation
        if ($field === 'password_confirmation') {
            // Jika password_confirmation kosong, reset error
            if (empty($this->password_confirmation)) {
                $this->resetValidation('password_confirmation');
                return;
            }
            
            // Jika password cocok, reset error
            if ($this->password_confirmation === $this->password) {
                $this->resetValidation('password_confirmation');
                return;
            }
            
            // Jika tidak cocok, validasi
            $this->validateOnly($field, $this->getRealTimeValidationRules($field), $this->messages);
            return;
        }

        // Khusus untuk email dan username, gunakan debounce
        if ($field === 'email') {
            $this->isCheckingEmail = true;
            if ($this->email !== $this->previousEmail) {
                $this->previousEmail = $this->email;
                $this->validateOnly($field, $this->getRealTimeValidationRules($field), $this->messages);
            }
            $this->isCheckingEmail = false;
        } 
        elseif ($field === 'username') {
            $this->isCheckingUsername = true;
            if ($this->username !== $this->previousUsername) {
                $this->previousUsername = $this->username;
                $this->validateOnly($field, $this->getRealTimeValidationRules($field), $this->messages);
            }
            $this->isCheckingUsername = false;
        }
        // Untuk field lain, validasi langsung
        else {
            $this->validateOnly($field, $this->getRealTimeValidationRules($field), $this->messages);
        }
    }
    
    public function register()
    {
        // Validasi semua field saat submit
        $this->validate($this->rules(), $this->messages);

        try {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'username' => $this->username,
                'password' => Hash::make($this->password),
                'original_password' => $this->password,
                'role' => 'USER',
                'is_active' => true
            ]);

            Auth::login($user);
            
            return redirect()->route('home');
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