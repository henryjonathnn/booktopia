<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class DataUser extends Component
{
    use WithPagination, WithFileUploads;

    // For pagination
    protected $paginationTheme = 'tailwind';
    
    // Search & Filter Properties
    public $search = '';
    public $role = '';
    public $active = 'ACTIVE';
    public $perPage = 10;
    
    // Modal control properties
    public $isModalOpen = false;
    public $isDetailModalOpen = false;
    public $confirmingUserDeletion = false;
    public $userIdToDelete = null;
    
    // Form properties
    public $userId = null;
    public $name = '';
    public $email = '';
    public $username = '';
    public $password = '';
    public $confPassword = '';
    public $userRole = 'USER';
    public $profileImage = null;
    public $existingProfileImage = null;
    public $originalPassword = '';
    
    // Selected users for batch operations
    public $selectedUsers = [];
    public $selectAll = false;
    
    // Computed property for all users on current page
    public $usersOnCurrentPage = [];
    
    // Cache selected user for detail view
    public $selectedUser = null;

    protected $listeners = ['refreshUsers' => '$refresh'];

    // Define validation rules
    protected function rules()
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'email' => [
                'required', 
                'email', 
                Rule::unique('users')->ignore($this->userId)
            ],
            'username' => [
                'required', 
                'string', 
                'min:3', 
                'max:25', 
                Rule::unique('users')->ignore($this->userId)
            ],
            'password' => $this->userId ? 'nullable|min:6' : 'required|min:6',
            'confPassword' => $this->userId ? 'nullable|same:password' : 'required|same:password',
            'userRole' => 'required|in:USER,STAFF,ADMIN',
            'profileImage' => $this->userId 
                ? 'nullable|image|max:2048' 
                : 'nullable|image|max:2048',
        ];
    }

    protected $validationAttributes = [
        'name' => 'nama',
        'email' => 'email',
        'username' => 'username',
        'password' => 'password',
        'confPassword' => 'konfirmasi password',
        'userRole' => 'role',
        'profileImage' => 'foto profil',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedRole()
    {
        $this->resetPage();
    }

    public function updatedActive()
    {
        $this->resetPage();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedUsers = $this->usersOnCurrentPage;
        } else {
            $this->selectedUsers = [];
        }
    }

    public function mount()
    {
        $this->resetPage();
    }

    // Get form configuration for the user form
    public function getUserFormConfig()
    {
        return [
            [
                'id' => 'name',
                'label' => 'Nama',
                'type' => 'text',
                'required' => true
            ],
            [
                'id' => 'email',
                'label' => 'Email',
                'type' => 'email',
                'required' => true
            ],
            [
                'id' => 'username',
                'label' => 'Username',
                'type' => 'text',
                'required' => true
            ],
            [
                'id' => 'password',
                'label' => 'Password',
                'type' => 'password',
                'required' => !$this->userId
            ],
            [
                'id' => 'confPassword',
                'label' => 'Konfirmasi Password',
                'type' => 'password',
                'required' => !$this->userId
            ],
            [
                'id' => 'userRole',
                'label' => 'Role',
                'type' => 'select',
                'required' => true,
                'options' => ['USER', 'STAFF', 'ADMIN']
            ]
        ];
    }

    public function render()
    {
        $users = User::search($this->search)
            ->role($this->role)
            ->when($this->active === 'ACTIVE', fn($q) => $q->where('is_active', true))
            ->when($this->active === 'INACTIVE', fn($q) => $q->where('is_active', false))
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);
            
        // Save IDs of users on current page for "Select All" functionality
        $this->usersOnCurrentPage = $users->pluck('id')->toArray();
            
        return view('livewire.admin.data-user', [
            'users' => $users,
            'formConfig' => $this->getUserFormConfig(),
            'currentUser' => $this->userId ? User::find($this->userId) : null
        ])->layout('layouts.admin', ['title' => 'Dashboard']);
    }

    // Open modal to create a new user
    public function createUser()
    {
        $this->resetValidation();
        $this->resetForm();
        $this->isModalOpen = true;
    }

    // Open modal to edit a user
    public function editUser($userId)
    {
        $this->resetValidation();
        $this->resetForm();
        
        $this->userId = $userId;
        $user = User::findOrFail($userId);
        
        $this->name = $user->name;
        $this->email = $user->email;
        $this->username = $user->username;
        $this->userRole = $user->role;
        $this->existingProfileImage = $user->profile_img;
        
        // Set password dan konfirmasi password dengan password asli
        $this->originalPassword = $user->original_password ?? '';
        $this->password = $this->originalPassword;
        $this->confPassword = $this->originalPassword;
        
        $this->isModalOpen = true;
    }

    // Open detail modal for a user
    public function viewUserDetails($userId)
    {
        $this->selectedUser = User::findOrFail($userId);
        $this->isDetailModalOpen = true;
    }

    // Close the form modal
    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    // Close the detail modal
    public function closeDetailModal()
    {
        $this->isDetailModalOpen = false;
        $this->selectedUser = null;
    }

    // Save the user (create or update)
    public function saveUser()
    {
        $this->validate();
        
        // Check if passwords match
        if ($this->password && $this->password !== $this->confPassword) {
            $this->addError('confPassword', 'Password dan konfirmasi password tidak sesuai');
            return;
        }
        
        // Creating or updating user
        $userData = [
            'name' => $this->name,
            'email' => $this->email,
            'username' => $this->username,
            'role' => $this->userRole,
        ];
        
        if ($this->userId) {
            // Update existing user
            // Hanya hash password jika password diubah dan berbeda dari password asli
            if ($this->password && $this->password !== $this->originalPassword) {
                $userData['password'] = Hash::make($this->password);
                $userData['original_password'] = $this->password;
            }
        } else {
            // Create new user
            // Selalu hash password untuk user baru dan simpan original password
            $userData['password'] = Hash::make($this->password);
            $userData['original_password'] = $this->password;
            $userData['is_active'] = true; // Set user baru sebagai aktif secara default
        }
        
        // Handle profile image upload
        if ($this->profileImage) {
            // Delete existing profile image if updating
            if ($this->userId && $this->existingProfileImage) {
                Storage::delete('public/' . $this->existingProfileImage);
            }
            
            // Store the new image
            $imagePath = $this->profileImage->store('profiles', 'public');
            $userData['profile_img'] = $imagePath;
        }
        
        if ($this->userId) {
            // Update existing user
            User::findOrFail($this->userId)->update($userData);
            $message = 'User berhasil diperbarui!';
        } else {
            // Create new user
            User::create($userData);
            $message = 'User berhasil ditambahkan!';
        }
        
        $this->resetForm();
        $this->isModalOpen = false;
        session()->flash('success', $message);
    }

    // Confirm user deletion 
    public function confirmUserDeletion($userId)
    {
        $this->userIdToDelete = $userId;
        $this->confirmingUserDeletion = true;
    }

    // Delete the user
    public function deleteUser($userId = null)
    {
        $idToDelete = $userId ?? $this->userIdToDelete;
        $user = User::findOrFail($idToDelete);
        
        // Delete profile image if exists
        if ($user->profile_img) {
            Storage::delete('public/' . $user->profile_img);
        }
        
        $user->delete();
        
        $this->confirmingUserDeletion = false;
        $this->userIdToDelete = null;
        
        session()->flash('alert', [
            'type' => 'success',
            'message' => 'User berhasil dihapus!'
        ]);
    }

    // Toggle user activation status
    public function toggleUserStatus($userId)
    {
        $user = User::findOrFail($userId);
        $user->is_active = !$user->is_active;
        $user->save();
        
        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        session()->flash('alert', [
            'type' => 'success',
            'message' => "User berhasil {$status}!"
        ]);
    }

    // Reset form fields
    private function resetForm()
    {
        $this->userId = null;
        $this->name = '';
        $this->email = '';
        $this->username = '';
        $this->password = '';
        $this->confPassword = '';
        $this->userRole = 'USER';
        $this->profileImage = null;
        $this->existingProfileImage = null;
        $this->originalPassword = '';
    }

    // Validate email or username in real-time
    public function validateField($field)
    {
        $this->validateOnly($field);
    }
}