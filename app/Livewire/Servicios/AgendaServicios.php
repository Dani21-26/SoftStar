<?php

namespace App\Livewire\Servicios;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ServicioTecnico;

class AgendaServicios extends Component
{
    use WithPagination;

    public $fechaFiltrada;
    public $search; 
    public $tecnico = '';

    protected $paginationTheme = 'tailwind';

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
        $query = ServicioTecnico::with('tecnico')->orderBy('created_at', 'desc');

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

        $servicios = $query->paginate(5);

        return view('livewire.servicios.agenda-servicios', compact('servicios'));
    }
}
