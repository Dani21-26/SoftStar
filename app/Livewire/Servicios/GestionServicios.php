<?php

namespace App\Livewire\Servicios;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ServicioTecnico;
use App\Models\Empleado;
use App\Models\Producto;


class GestionServicios extends Component
{
    use WithPagination;
    
    public $search = '';
    public $estadoFiltro = 'por_tomar';
    
    // Datos del formulario
    public $cliente = '';
    public $direccion = '';
    public $fallaReportada = '';
    public $observaciones = '';
    
    // Reglas de validación
    protected $rules = [
        'cliente' => 'required|min:3|max:100',
        'direccion' => 'required|min:10|max:255',
        'fallaReportada' => 'required|min:10|max:500',
        'observaciones' => 'nullable|max:500',
    ];
    
    public function render()
    {
        $servicios = ServicioTecnico::with(['detalle.tecnico'])
            ->when($this->search, function($query) {
                $query->where('codigo', 'like', '%'.$this->search.'%')
                    ->orWhere('cliente', 'like', '%'.$this->search.'%');
            })
            ->where('estado', $this->estadoFiltro)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('livewire.servicios.gestion-servicios', [
            'servicios' => $servicios
        ]);
    }
    
    public function crearServicio()
    {
        $this->validate();
        
        // Generar código automático
        $latestId = ServicioTecnico::max('id_servicio') ?? 0;
        $codigo = 'ST-' . str_pad($latestId + 1, 4, '0', STR_PAD_LEFT);
        
        ServicioTecnico::create([
            'codigo' => $codigo,
            'cliente' => $this->cliente,
            'direccion' => $this->direccion,
            'falla_reportada' => $this->fallaReportada,
            'observaciones' => $this->observaciones,
            'estado' => 'por_tomar'
        ]);
        
        $this->reset([
            'cliente', 'direccion', 'fallaReportada',
            'observaciones'
        ]);
        
        session()->flash('success', 'Solicitud de servicio creada correctamente');
    }
    
    public function cancelarServicio($idServicio)
    {
        $servicio = ServicioTecnico::findOrFail($idServicio);
        $servicio->update(['estado' => 'cancelado']);
        
        session()->flash('info', 'Servicio cancelado correctamente');
    }
    
    public function verDetalle($idServicio)
    {
        return redirect()->route('servicios.detalle', $idServicio);
    }
}

