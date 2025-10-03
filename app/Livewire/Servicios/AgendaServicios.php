<?php

namespace App\Livewire\Servicios;
use Illuminate\Support\Facades\Auth;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ServicioTecnico;

class AgendaServicios extends Component
{
    use WithPagination;

    public $fechaFiltrada;
    public $search; 
    public $tecnico = '';
    protected $listeners = ['servicio-confirmado' => '$refresh'];
    public $perPage = 4;
    protected $paginationTheme = 'tailwind';


    public function abrirModal($id)
    {
        $this->dispatch('set-servicio-id', id: $id);
        // Abrir el modal usando la variable booleana del componente ConfirmarServicio
        $this->dispatch('set-show-modal', value: true);
    }
    
// revisar
    public function eliminar($id)
    {
        ServicioTecnico::findOrFail($id)->delete();
        session()->flash('success', 'Servicio eliminado correctamente.');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFechaFiltrada()
    {
        $this->resetPage();
    }

    public function updatedTecnico()
    {
        $this->resetPage();
    }
    
    public function render()
{
    $user = Auth::user();

    $query = ServicioTecnico::with('tecnico')
        ->where('estado', 'pendiente')
        ->orderBy('created_at', 'desc');

    // Si el usuario NO es Super-Admin → solo ve sus propios servicios
    if (!$user->hasRole('Super-Admin')) {
        $query->where('tecnico_id', $user->id);
    }

    if (!empty($this->fechaFiltrada)) {
        $query->whereDate('created_at', $this->fechaFiltrada);
    }

    if (!empty($this->search)) {
        $query->where('cliente', 'like', '%' . $this->search . '%');
    }

    if (!empty($this->tecnico)) {
        $query->whereHas('tecnico', function ($q) {
            $q->where('name', 'like', '%' . $this->tecnico . '%');
        });
    }

    $servicios = $query->paginate($this->perPage);

    return view('livewire.servicios.agenda-servicios', compact('servicios'));
}

}
