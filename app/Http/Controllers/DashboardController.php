<?php

namespace App\Http\Controllers;

use App\Models\ServicioTecnico;
use App\Models\DetalleServicio;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function __invoke()
    {
        // 1. Servicios esta semana (total)
        $serviciosSemana = ServicioTecnico::whereBetween('created_at', [
            now()->startOfWeek(), 
            now()->endOfWeek()
        ])->count();

        // 2. Técnicos activos (usuarios que han confirmado servicios esta semana)
        $tecnicosActivos = User::role('tecnico')
            ->whereHas('detallesServicios', function($query) {
                $query->whereBetween('created_at', [
                    now()->startOfWeek(), 
                    now()->endOfWeek()
                ]);
            })->count();

        // 3. Productos utilizados esta semana
        $detallesConProductos = DetalleServicio::with('servicio')
            ->whereBetween('created_at', [
                now()->startOfWeek(), 
                now()->endOfWeek()
            ])
            ->get();

        // Procesamiento de productos utilizados
        $productosUtilizados = 0;
        $productosData = [];
        
        foreach ($detallesConProductos as $detalle) {
            if (!empty($detalle->productos_utilizados)) {
                foreach ($detalle->productos_utilizados as $producto) {
                    $cantidad = $producto['cantidad'] ?? 0;
                    $productosUtilizados += $cantidad;
                    
                    // Preparación para productos más usados
                    $nombre = $producto['nombre'] ?? 'Desconocido';
                    if (!isset($productosData[$nombre])) {
                        $productosData[$nombre] = 0;
                    }
                    $productosData[$nombre] += $cantidad;
                }
            }
        }

        // 4. Servicios por día
        $diasSemana = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'];
        $serviciosPorDia = [];
        
        foreach(range(0, 6) as $day) {
            $date = now()->startOfWeek()->addDays($day);
            $serviciosPorDia[] = ServicioTecnico::whereDate('created_at', $date)->count();
        }

        // 5. Top técnicos (que más servicios han confirmado)
        $topTecnicos = User::role('tecnico')
            ->withCount(['detallesServicios' => function($query) {
                $query->whereBetween('created_at', [
                    now()->startOfWeek(), 
                    now()->endOfWeek()
                ]);
            }])
            ->orderByDesc('detalles_servicios_count')
            ->limit(5)
            ->get();

        // 6. Productos más usados esta semana (versión optimizada)
        $productosMasUsados = collect($productosData)
            ->map(function ($total, $nombre) {
                return [
                    'nombre' => $nombre,
                    'total' => $total
                ];
            })
            ->sortByDesc('total')
            ->take(5)
            ->values()
            ->all();

        return view('dashboard', compact(
            'serviciosSemana',
            'tecnicosActivos',
            'productosUtilizados',
            'diasSemana',
            'serviciosPorDia',
            'topTecnicos',
            'productosMasUsados'
        ));
    }
}