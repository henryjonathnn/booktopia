<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class Profile extends Component
{
    use WithFileUploads;

    public $user;
    public $name;
    public $email;
    public $username;
    public $current_password;
    public $new_password;
    public $new_password_confirmation;
    public $profile_image;

    public function mount()
    {
        $this->user = Auth::user();
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->username = $this->user->username;
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$this->user->id,
            'username' => 'required|string|unique:users,username,'.$this->user->id,
            'profile_image' => 'nullable|image|max:1024', // max 1MB
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ];
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$this->user->id,
            'username' => 'required|string|unique:users,username,'.$this->user->id,
            'profile_image' => 'nullable|image|max:1024',
        ]);

        // Update profile image if uploaded
        if ($this->profile_image) {
            if ($this->user->profile_img) {
                Storage::delete($this->user->profile_img);
            }
            $path = $this->profile_image->store('profile-images', 'public');
            $this->user->profile_img = $path;
        }

        $this->user->name = $this->name;
        $this->user->email = $this->email;
        $this->user->username = $this->username;
        $this->user->save();

        session()->flash('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($this->current_password, $this->user->password)) {
            $this->addError('current_password', 'Password saat ini tidak sesuai.');
            return;
        }

        $this->user->password = Hash::make($this->new_password);
        $this->user->save();

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        session()->flash('success', 'Password berhasil diperbarui!');
    }

    public function removeProfileImage()
    {
        if ($this->user->profile_img) {
            Storage::delete($this->user->profile_img);
            $this->user->profile_img = null;
            $this->user->save();
            session()->flash('success', 'Foto profil berhasil dihapus!');
        }
    }

    public function render()
    {
        return view('livewire.user.profile')->layout('layouts.user');
    }
} 