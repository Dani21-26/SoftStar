<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserCreateForm extends Component
{
    
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $selectedRoleNames = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'selectedRoleNames' => 'required|array|min:1',
        'selectedRoleNames.*' => 'exists:roles,name'
    ];
    public function render()
    {
        return view('livewire.admin.user-create-form', [
            'roles' => Role::all()
        ]);
    }
    public function submit()
    {
        $this->validate();
    
        if (in_array('Super-Admin', $this->selectedRoleNames) && !auth()->user()->hasRole('Super-Admin')) {
            $this->addError('selectedRoleNames', 'No tienes permiso para asignar el rol Super-Admin');
            return;
        }
    
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'email_verified_at' => now()
        ]);
    
        $user->syncRoles($this->selectedRoleNames);
    
        session()->flash('message', 'Usuario creado exitosamente con roles: ' . implode(', ', $this->selectedRoleNames));
    
        return redirect()->route('users.create'); 
    }
}    