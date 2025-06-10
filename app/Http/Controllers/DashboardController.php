<?php

namespace App\Http\Controllers;

use App\Models\ServicioTecnico;
use App\Models\DetalleServicio;
use App\Models\Producto;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function __invoke()
    {
        // 1. Servicios esta semana (total)
        $serviciosSemana = ServicioTecnico::whereBetween('created_at', [
            now()->startOfWeek(), 
            now()->endOfWeek()
        ])->count();

        // 2. Servicios pendientes con más de 5 días (excluyendo cancelados)
        $serviciosAtrasados = ServicioTecnico::whereIn('estado', ['por_tomar', 'en_proceso']) // Solo estados pendientes
        ->where('created_at', '<', now()->subDays(5))
        ->select('id_servicio', 'codigo', 'cliente', 'estado', 'created_at')
        ->orderBy('created_at', 'asc')
        ->get()
        ->map(function ($servicio) {
            $servicio->dias_atraso = Carbon::now()->diffInDays($servicio->created_at);
            return $servicio;
        });

        // 3. Productos utilizados esta semana con nombres
        $productosUtilizados = DetalleServicio::whereBetween('created_at', [
            now()->startOfWeek(), 
            now()->endOfWeek()
        ])
        ->get()
        ->flatMap(function($detalle) {
            if (!empty($detalle->productos_utilizados)) {
                return collect($detalle->productos_utilizados)->map(function($cantidad, $idProducto) {
                    $producto = Producto::find($idProducto);
                    return [
                        'id' => $idProducto,
                        'nombre' => $producto ? $producto->nombre : 'Producto desconocido',
                        'cantidad' => $cantidad,
                        'unidad' => $producto ? $this->determinarUnidad($producto->nombre) : 'unid.'
                    ];
                });
            }
            return [];
        })
        ->groupBy('id')
        ->map(function($items, $id) {
            return [
                'id' => $id,
                'nombre' => $items->first()['nombre'],
                'total' => $items->sum('cantidad'),
                'unidad' => $items->first()['unidad']
            ];
        })
        ->sortByDesc('total')
        ->values();

        // 4. Servicios por día
        $diasSemana = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'];
        $serviciosPorDia = [];
        
        foreach(range(0, 6) as $day) {
            $date = now()->startOfWeek()->addDays($day);
            $serviciosPorDia[] = ServicioTecnico::whereDate('created_at', $date)->count();
        }

        return view('dashboard', compact(
            'serviciosSemana',
            'serviciosAtrasados',
            'productosUtilizados',
            'diasSemana',
            'serviciosPorDia'
        ));
    }

    private function determinarUnidad($nombreProducto)
    {
        if (str_contains(strtolower($nombreProducto), 'cable') || 
            str_contains(strtolower($nombreProducto), 'metro')) {
            return 'm';
        }
        return 'unid.';
    }
}   