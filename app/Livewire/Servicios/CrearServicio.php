<?php


namespace App\Livewire\Servicios;

use Livewire\Component;
use App\Models\User;
use App\Models\ServicioTecnico;
use Carbon\Carbon;

class CrearServicio extends Component
{
    public $cliente, $router, $litebean, $direccion, $falla_reportada, $prioridad = 'media', $tecnico_id;
    public $tecnicos = [];
    
    public function mount()
    {
        $this->tecnicos = User::all(); // lista de usuarios para el select
        
    }
    
    public function guardar()
{
    $this->dispatch('close-modal', name: 'crear-servicio');

    $this->validate([
        'cliente' => 'required',
        'router' => 'required',
        'litebean' => 'required',
        'direccion' => 'required',
        'falla_reportada' => 'required',
        'prioridad' => 'required',
        'tecnico_id' => 'nullable|exists:users,id',
    ]);

    ServicioTecnico::create([
        'cliente' => $this->cliente,
        'router' => $this->router,
        'litebean' => $this->litebean,
        'direccion' => $this->direccion,
        'falla_reportada' => $this->falla_reportada,
        'prioridad' => $this->prioridad,
        'estado' => 'pendiente',
        'tecnico_id' => $this->tecnico_id,
    ]);

    $this->reset([
        'cliente', 'router', 'litebean', 'direccion',
        'falla_reportada', 'prioridad', 'tecnico_id'
    ]);

    session()->flash('success', 'Servicio registrado exitosamente.');
    $this->dispatch('servicioCreado');
}

    public function render()
    {
        return view('livewire.servicios.crear-servicio');
    }
}
