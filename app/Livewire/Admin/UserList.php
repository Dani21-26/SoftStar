<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

class UserList extends Component
{
    use WithPagination;

    public $search = '';

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        // Reinicia la paginación cuando cambia el término de búsqueda
        $this->resetPage();
    }
public function toggleStatus($id)
   {
    try {
        $user = User::findOrFail($id);

        $user->estado = $user->estado === 'activo' ? 'inactivo' : 'activo';
        $user->save();

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Actualizado!',
            'text' => "El usuario fue " . ($user->estado === 'activo' ? 'activado' : 'desactivado') . " correctamente",
        ]);

    } catch (\Exception $e) {
        $this->dispatch('swal', [
            'icon' => 'error',
            'title' => 'Error',
            'text' => 'No se pudo actualizar el usuario: ' . $e->getMessage(),
        ]);
    }
}

    public function render()
    {
        $users = User::with('roles')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.user-list', [
            'users' => $users
        ]);
    }
}
