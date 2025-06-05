<?php

namespace App\Http\Livewire\Reportes;

use Livewire\Component;
use App\Models\User;
use App\Models\ServicioTecnico;
use Carbon\Carbon;

class HistorialTecnicos extends Component
{
    public $tecnicoId;
    public $fechaInicio;
    public $fechaFin;

    public function mount($tecnico = null)
    {
        $this->fechaInicio = now()->subWeeks(4)->startOfWeek()->format('Y-m-d');
        $this->fechaFin = now()->endOfWeek()->format('Y-m-d');
        $this->tecnicoId = $tecnico;
    }

    public function render()
    {
        $tecnicos = User::role('tecnico')->get();
        $servicios = [];
        
        if ($this->tecnicoId) {
            $servicios = ServicioTecnico::where('id_tecnico', $this->tecnicoId)
                ->whereBetween('created_at', [$this->fechaInicio, $this->fechaFin])
                ->get();
        }

        return view('livewire.reportes.historial-tecnicos', [
            'tecnicos' => $tecnicos,
            'servicios' => $servicios
        ]);
    }
}