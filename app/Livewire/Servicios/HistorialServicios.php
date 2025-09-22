<?php

namespace App\Livewire\Servicios;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\DetalleServicio;
use App\Models\ServicioTecnico;
use Carbon\Carbon;

class HistorialServicios extends Component
{
    use WithPagination;

    public $search = '';
    public $fechaInicio = '';
    public $fechaFin = '';


    public function mount()
    {
        $this->fechaInicio = now()->subMonth()->format('Y-m-d');
        $this->fechaFin = now()->format('Y-m-d');
    }

    public function render()
    {
        // Solo datos de la tabla detalle_servicio, pero mostrando el nombre del cliente según el id_servicio
        $query = DetalleServicio::query()
            ->whereHas('servicio', function ($q) {
                $q->where('estado', 'confirmado');
            })
            ->when($this->search, function ($query) {
                $query->whereHas('servicio', function ($serv) {
                    $serv->where('cliente', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->fechaInicio && $this->fechaFin, function ($query) {
                $query->whereBetween('created_at', [
                    Carbon::parse($this->fechaInicio)->startOfDay(),
                    Carbon::parse($this->fechaFin)->endOfDay()
                ]);
            })
            ->orderBy('created_at', 'desc');

        $detalles = $query->paginate(6);

        // Estadísticas
        $totalCompletados = DetalleServicio::whereHas('servicio', fn ($q) => $q->where('estado', 'confirmado'))->count();

        $completadosEsteMes = DetalleServicio::whereHas('servicio', fn ($q) => $q->where('estado', 'confirmado'))
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();

        // Obtener todos los detalles completados para sumar productos
        $detallesCompletados = DetalleServicio::whereHas('servicio', fn ($q) => $q->where('estado', 'confirmado'))->get();

        $totalProductosUtilizados = $detallesCompletados->sum(function ($detalle) {
            if (is_array($detalle->productos_utilizados)) {
                return array_sum($detalle->productos_utilizados);
            }
            return 0;
        });

        return view('livewire.servicios.historial-servicios', [
            'detalles' => $detalles,
            'totalCompletados' => $totalCompletados,
            'completadosEsteMes' => $completadosEsteMes,
            'totalProductosUtilizados' => $totalProductosUtilizados
        ]);
    }
}
