<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserEditForm extends Component
{
    public $user;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $selectedRoleNames = [];
    public $roles;
    public $estado;


    public function mount(User $user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->estado = $user->estado;
        $this->selectedRoleNames = $user->roles->pluck('name')->toArray();
        $this->roles = Role::all();
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:users,email,' . $this->user->id,
            'password' => 'nullable|confirmed|min:6',
            'selectedRoleNames' => 'required|array|min:1',
            'estado' => 'required|in:activo,inactivo',
        ]);

        $this->user->name = $this->name;
        $this->user->email = $this->email;
        $this->user->estado = $this->estado;


        if (!empty($this->password)) {
            $this->user->password = Hash::make($this->password);
        }

        $this->user->save();
        $this->user->syncRoles($this->selectedRoleNames);

        // SweetAlert notificación
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Usuario actualizado!',
            'text' => 'Los datos del usuario han sido actualizados correctamente.',
            'confirmButtonText' => 'OK',
            'redirect' => route('users.index'),
        ]);
    }


    public function render()
    {
        return view('livewire.admin.user-edit-form');

    }
}
