<?php

namespace App\Http\Livewire\Reportes;

use Livewire\Component;
use App\Models\ServicioTecnico;
use Carbon\Carbon;

class ReporteSemanal extends Component
{
    public $fechaInicio;
    public $fechaFin;

    public function mount()
    {
        $this->fechaInicio = now()->startOfWeek()->format('Y-m-d');
        $this->fechaFin = now()->endOfWeek()->format('Y-m-d');
    }

    public function render()
    {
        $servicios = ServicioTecnico::with('tecnico')
            ->whereBetween('created_at', [$this->fechaInicio, $this->fechaFin])
            ->get();

        return view('livewire.reportes.reporte-semanal', [
            'servicios' => $servicios
        ]);
    }
}