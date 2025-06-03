<?php

namespace App\Livewire\Reportes;

use Livewire\Component;
use App\Models\ServicioTecnico;
use App\Models\User;
use App\Models\Producto;
use Carbon\Carbon;

class HistorialTecnicos extends Component
{
    public $tecnicoId;
    public $fechaInicio;
    public $fechaFin;
    public $tecnico;
    public $detalleServicio = null;

    public function mount($tecnico = null)
    {
        $this->fechaInicio = now()->subWeeks(4)->startOfWeek()->format('Y-m-d');
        $this->fechaFin = now()->endOfWeek()->format('Y-m-d');
        
        if ($tecnico) {
            $this->tecnicoId = $tecnico;
            $this->tecnico = User::find($tecnico);
        }
    }

    public function render()
    {
        $tecnicos = User::role('tecnico')->get();
        $historial = $this->generarHistorial();
        
        return view('livewire.reportes.historial-tecnicos', [
            'historial' => $historial,
            'tecnicos' => $tecnicos
        ]);
    }

    protected function generarHistorial()
    {
        if (!$this->tecnicoId) {
            return null;
        }

        $tecnico = User::find($this->tecnicoId);
        if (!$tecnico) {
            return null;
        }

        $servicios = ServicioTecnico::with(['detalle', 'productos.producto'])
            ->where('id_tecnico', $this->tecnicoId)
            ->whereBetween('created_at', [$this->fechaInicio, $this->fechaFin])
            ->get();

        $semanas = [];
        $fechaInicio = Carbon::parse($this->fechaInicio);
        $fechaFin = Carbon::parse($this->fechaFin);

        for ($fecha = $fechaInicio->copy(); $fecha->lte($fechaFin); $fecha->addWeek()) {
            $inicioSemana = $fecha->copy()->startOfWeek();
            $finSemana = $fecha->copy()->endOfWeek();

            $serviciosSemana = $servicios->filter(function($servicio) use ($inicioSemana, $finSemana) {
                return $servicio->created_at->between($inicioSemana, $finSemana);
            });

            $productosSemana = [];
            foreach ($serviciosSemana as $servicio) {
                if ($servicio->detalle && $servicio->detalle->productos_utilizados) {
                    foreach ($servicio->detalle->productos_utilizados as $productoId => $cantidad) {
                        if (!isset($productosSemana[$productoId])) {
                            $producto = Producto::find($productoId);
                            if ($producto) {
                                $productosSemana[$productoId] = [
                                    'nombre' => $producto->nombre,
                                    'cantidad' => 0
                                ];
                            }
                        }
                        $productosSemana[$productoId]['cantidad'] += $cantidad;
                    }
                }
            }

            $semanas[] = [
                'semana' => $inicioSemana->format('W/Y'),
                'inicio' => $inicioSemana->format('d/m/Y'),
                'fin' => $finSemana->format('d/m/Y'),
                'total_servicios' => $serviciosSemana->count(),
                'servicios_completados' => $serviciosSemana->where('estado', 'completado')->count(),
                'productos_utilizados' => $productosSemana,
                'servicios' => $serviciosSemana
            ];
        }

        return [
            'tecnico' => $tecnico,
            'fecha_inicio' => $this->fechaInicio,
            'fecha_fin' => $this->fechaFin,
            'semanas' => $semanas,
            'total_servicios' => $servicios->count(),
            'servicios_completados' => $servicios->where('estado', 'completado')->count()
        ];
    }

    public function verDetalleServicio($servicioId)
    {
        $this->detalleServicio = ServicioTecnico::with(['tecnico', 'detalle', 'productos.producto'])
            ->find($servicioId);
    }

    public function resetDetalle()
    {
        $this->detalleServicio = null;
    }

    public function seleccionarTecnico()
    {
        $this->tecnico = User::find($this->tecnicoId);
        $this->historial = $this->generarHistorial();
    }

    public function exportarExcel()
    {
        $historial = $this->generarHistorial();
        return Excel::download(new HistorialTecnicoExport($historial), 'historial-tecnico-'.$this->tecnicoId.'-'.now()->format('Y-m-d').'.xlsx');
    }
}