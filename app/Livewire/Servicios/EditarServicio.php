<?php
namespace App\Livewire\Servicios;

use Livewire\Component;
use App\Models\ServicioTecnico;
use App\Models\User;

class EditarServicio extends Component
{
    public $id; 
    public $servicio;
    public $cliente;
    public $router;
    public $litebean;
    public $direccion;
    public $falla_reportada;
    public $prioridad;
    public $tecnico_id;

    public function mount()
    {
        $this->servicio = ServicioTecnico::findOrFail($this->id);

        $this->cliente = $this->servicio->cliente;
        $this->router = $this->servicio->router;
        $this->litebean = $this->servicio->litebean;
        $this->direccion = $this->servicio->direccion;
        $this->falla_reportada = $this->servicio->falla_reportada;
        $this->prioridad = $this->servicio->prioridad;
        $this->tecnico_id = $this->servicio->tecnico_id;
    }

    public function actualizar()
    {
        $this->validate([
            'cliente' => 'required',
            'router' => 'required',
            'litebean' => 'required',
            'direccion' => 'required',
            'falla_reportada' => 'required',
            'prioridad' => 'required',
            'tecnico_id' => 'nullable|exists:users,id',
        ]);

        $this->servicio->update([
            'cliente' => $this->cliente,
            'router' => $this->router,
            'litebean' => $this->litebean,
            'direccion' => $this->direccion,
            'falla_reportada' => $this->falla_reportada,
            'prioridad' => $this->prioridad,
            'tecnico_id' => $this->tecnico_id,
        ]);

        session()->flash('success', 'Servicio actualizado correctamente.');
        return $this->redirectRoute('servicios.agenda');  
    }

    public function render()
    {
        return view('livewire.servicios.editar-servicio', [
            'tecnicos' => User::all()
        ]);
    }
}
