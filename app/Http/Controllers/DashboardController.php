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

        // 3. Productos utilizados esta semana con nombres
        $productosUtilizados = DetalleServicio::whereBetween('created_at', [
            now()->startOfWeek(), 
            now()->endOfWeek()
        ])
        ->get()
        ->flatMap(function($detalle) {
            // Decodificar si viene como string JSON
            $productos = is_string($detalle->productos_utilizados)
                ? json_decode($detalle->productos_utilizados, true)
                : $detalle->productos_utilizados;
            if (!empty($productos) && is_array($productos)) {
                return collect($productos)->map(function($cantidad, $idProducto) {
                    $producto = Producto::find($idProducto);
                    return [
                        'id' => $idProducto,
                        'nombre' => $producto ? $producto->nombre : 'Producto desconocido',
                        'cantidad' => (int)$cantidad,
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

        // 5. Revisión de stock de productos
        $umbralStock = 5;
        $productosBajoStock = Producto::where('estado', 'activo')
            ->where('stock', '<=', $umbralStock)
            ->get();
        
        if ($productosBajoStock->count() > 0) {
            $mensajeStock = "Algunos productos tienen stock bajo";
            $stockEstado = 'bajo';
        } else {
            $mensajeStock = "Stock estable en todos los productos";
            $stockEstado = 'estable';
        }
        return view('dashboard', compact(
            'serviciosSemana',
            'productosUtilizados',
            'diasSemana',
            'serviciosPorDia',
            'productosBajoStock',
            'mensajeStock',
            'stockEstado'
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