<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserRoleAssignment extends Component
{
    public User $user;
    public array $selectedRoleNames = [];
    public $availableRoles;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->availableRoles = Role::where('guard_name', 'web')->get();
        $this->selectedRoleNames = $this->user->roles->pluck('name')->toArray();
    }

    
    public function updateRoles()
    {
        $this->validate([
            'selectedRoleNames' => 'required|array',
            'selectedRoleNames.*' => 'exists:roles,name'
        ]);

        // Verificar permisos para asignar roles
        if (in_array('Super-Admin', $this->selectedRoleNames) && !auth()->user()->hasRole('Super-Admin')) {
            $this->addError('selectedRoleNames', 'No tienes permiso para asignar el rol Super-Admin');
            return;
        }

        DB::transaction(function () {
            // Sincronizar usando los nombres directamente
            $this->user->syncRoles($this->selectedRoleNames);

            if (!in_array('Super-Admin', $this->selectedRoleNames)) {
                $this->user->syncPermissions([]);
            }
        });

        session()->flash('success', 'Roles actualizados correctamente');
    }
    public function getRoleDescription($roleName)
    {
    $descriptions = [
        'tecnico' => 'Acceso a funciones técnicas',
        'servicio-cliente' => 'Atención al cliente y soporte básico',
    ];

    return $descriptions[$roleName] ?? 'Rol de usuario';
    }

    public function render()
    {
        return view('livewire.admin.user-role-assignment')
            ->layout('layouts.app');
    }
}