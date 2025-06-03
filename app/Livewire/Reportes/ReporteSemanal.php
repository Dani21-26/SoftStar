<?php

namespace App\Livewire\Reportes;

use Livewire\Component;
use App\Models\ServicioTecnico;
use App\Models\Producto;
use Carbon\Carbon;
use Livewire\WithPagination;

class ReporteSemanal extends Component
{
    use WithPagination;

    public $fechaInicio;
    public $fechaFin;
    public $detalleServicio = null;

    public function mount()
    {
        $this->fechaInicio = now()->startOfWeek()->format('Y-m-d');
        $this->fechaFin = now()->endOfWeek()->format('Y-m-d');
    }

    public function render()
    {
        $reporte = $this->generarReporte();
        
        return view('livewire.reportes.reporte-semanal', [
            'reporte' => $reporte
        ]);
    }

    protected function generarReporte()
    {
        $servicios = ServicioTecnico::with(['tecnico', 'detalle', 'productos.producto'])
            ->whereBetween('created_at', [$this->fechaInicio, $this->fechaFin])
            ->get();

        $productosUtilizados = [];
        $tecnicosData = [];

        foreach ($servicios as $servicio) {
            // Estadísticas por técnico
            if ($servicio->tecnico) {
                $tecnicoId = $servicio->tecnico->id;
                if (!isset($tecnicosData[$tecnicoId])) {
                    $tecnicosData[$tecnicoId] = [
                        'tecnico' => $servicio->tecnico,
                        'total' => 0,
                        'completados' => 0,
                        'productos' => []
                    ];
                }

                $tecnicosData[$tecnicoId]['total']++;
                if ($servicio->estado == 'completado') {
                    $tecnicosData[$tecnicoId]['completados']++;
                }

                // Productos utilizados por técnico
                if ($servicio->detalle && $servicio->detalle->productos_utilizados) {
                    foreach ($servicio->detalle->productos_utilizados as $productoId => $cantidad) {
                        if (!isset($tecnicosData[$tecnicoId]['productos'][$productoId])) {
                            $producto = Producto::find($productoId);
                            if ($producto) {
                                $tecnicosData[$tecnicoId]['productos'][$productoId] = [
                                    'nombre' => $producto->nombre,
                                    'cantidad' => 0
                                ];
                            }
                        }
                        $tecnicosData[$tecnicoId]['productos'][$productoId]['cantidad'] += $cantidad;
                    }
                }
            }

            // Productos utilizados en general
            if ($servicio->productos->isNotEmpty()) {
                foreach ($servicio->productos as $servicioProducto) {
                    $productoId = $servicioProducto->id_producto;
                    if (!isset($productosUtilizados[$productoId])) {
                        $productosUtilizados[$productoId] = [
                            'producto' => $servicioProducto->producto,
                            'cantidad' => 0
                        ];
                    }
                    $productosUtilizados[$productoId]['cantidad'] += $servicioProducto->cantidad;
                }
            }
        }

        return [
            'fecha_inicio' => $this->fechaInicio,
            'fecha_fin' => $this->fechaFin,
            'total_servicios' => $servicios->count(),
            'servicios_completados' => $servicios->where('estado', 'completado')->count(),
            'servicios_pendientes' => $servicios->where('estado', '!=', 'completado')->count(),
            'tecnicos' => $tecnicosData,
            'productos_utilizados' => $productosUtilizados,
            'servicios' => $servicios
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

    public function exportarExcel()
    {
        $reporte = $this->generarReporte();
        return Excel::download(new ReporteSemanalExport($reporte), 'reporte-semanal-'.now()->format('Y-m-d').'.xlsx');
    }
}