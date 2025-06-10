<?php

namespace App\Livewire\Servicios;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ServicioTecnico;
use Illuminate\Support\Facades\Auth;
use App\Models\Producto;
use App\Models\ServicioProducto;
use App\Models\DetalleServicio;
use Illuminate\Support\Facades\DB;

class GestionServicios extends Component
{
    use WithPagination;
    
    public $search = '';
    public $estadoFiltro = '';
    
    // Datos del formulario
    public $cliente = '';
    public $router = ''; 
    public $litebean = '';
    public $direccion = '';
    public $fallaReportada = '';
    public $observaciones = '';
    public $esTecnico = false;
    public $mostrarFormulario = true;
    
    // Para confirmación de servicio
    public $servicioSeleccionado = null;
    public $modalConfirmar = false;
    public $solucionAplicada = '';
    public $productosSeleccionados = [];
    public $todosProductos;
    


    protected $rules = [
        'cliente' => 'required|min:3|max:100',
        'router' => 'required|string|max:50',
        'litebean' => 'required|string|max:50', 
        'direccion' => 'required|min:10|max:255',
        'fallaReportada' => 'required|min:10|max:500',
        'observaciones' => 'nullable|max:500',
    ];
    
    public function mount()
    {
        $this->authorize('ver servicioTecnico');
        $this->esTecnico = Auth::user()->hasRole('tecnico');
        $this->cargarProductos();
        $this->mostrarFormulario = !$this->esTecnico;
        
        if($this->esTecnico) {
            $this->estadoFiltro = 'por_tomar';
        }
    }
    
    protected function cargarProductos()
    {
        $this->todosProductos = Producto::where('stock', '>', 0)->get();
        $this->inicializarProductos();
    }
    
    public function inicializarProductos()
    {
        $this->productosSeleccionados = $this->todosProductos->mapWithKeys(function($producto) {
            return [$producto->id => 0];
        })->toArray();
    }
    
    public function render()
    {
        $query = ServicioTecnico::query()
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('codigo', 'like', '%'.$this->search.'%')
                    ->orWhere('cliente', 'like', '%'.$this->search.'%')
                    ->orWhere('direccion', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->estadoFiltro != '', function($query) { // Cambia esta condición
                $query->where('estado', $this->estadoFiltro);
            })
            ->orderBy('created_at', 'desc');
    
        $servicios = $query->paginate(10);
    
        return view('livewire.servicios.gestion-servicios', [
            'servicios' => $servicios
        ]);
    }
    

    public function crearServicio()
{
    $this->validate();
    
    try {
        DB::transaction(function () {
            $latestId = ServicioTecnico::max('id_servicio') ?? 0;
            $codigo = 'ST-' . str_pad($latestId + 1, 4, '0', STR_PAD_LEFT);
            
            ServicioTecnico::create([
                'codigo' => $codigo,
                'cliente' => $this->cliente,
                'router' => $this->router,
                'litebean' => $this->litebean,
                'direccion' => $this->direccion,
                'falla_reportada' => $this->fallaReportada,
                'observaciones' => $this->observaciones,
                'estado' => 'pendiente',
                'id_empleado' => auth()->id() // Asignar el empleado que crea el servicio
            ]);
        });
        
        $this->reset([
            'cliente', 'router', 'litebean', 'direccion', 
            'fallaReportada', 'observaciones'
        ]);
        
        $this->dispatch('notify-success', message: 'Solicitud de servicio creada correctamente');
        
    } catch (\Exception $e) {
        $this->dispatch('notify-error', message: 'Error al crear servicio: '.$e->getMessage());
    }
}
    
    public function cancelarServicio($idServicio)
    {
        $servicio = ServicioTecnico::findOrFail($idServicio);
        
        // Validación básica del estado
        if (!in_array($servicio->estado, ['pendiente', 'en_proceso'])) {
            $this->dispatch('notify-error', message: 'No se puede cancelar este servicio');
            return;
        }
        
        $servicio->update([
            'estado' => 'cancelado',
            'id_empleado_confirma' => auth()->id()
        ]);
        
        $this->dispatch('notify-success', message: 'Servicio cancelado correctamente');
        $this->dispatch('servicio-actualizado'); // Para refrescar la vista si es necesario
    }
    
    public function verDetalle($idServicio)
    {
        return redirect()->route('servicios.detalle', $idServicio);
    }
    
    
    public function abrirModalConfirmar($idServicio)
    {
        $this->servicioSeleccionado = ServicioTecnico::with('productos')->find($idServicio);
        
        if (!$this->servicioSeleccionado) {
            $this->dispatch('notify-error', message: 'Servicio no encontrado');
            return;
        }
        
        // Recargar productos y reinicializar selección
        $this->cargarProductos();
        
        // Opcional: Si quieres cargar productos ya usados previamente
        if ($this->servicioSeleccionado->productos->isNotEmpty()) {
            foreach ($this->servicioSeleccionado->productos as $productoUsado) {
                if (isset($this->productosSeleccionados[$productoUsado->id_producto])) {
                    $this->productosSeleccionados[$productoUsado->id_producto] = $productoUsado->cantidad;
                }
            }
        }
        
        $this->modalConfirmar = true;
    }
    public function tomarServicio($idServicio)
    {
        $servicio = ServicioTecnico::findOrFail($idServicio);
        
        $servicio->update([
            'id_empleado_toma' => auth()->id(),
            'estado' => 'en_proceso'
        ]);
        
        $this->dispatch('servicio-actualizado');
    }

    public function confirmarServicio()
{
    $this->validate([
        'solucionAplicada' => 'required|min:10',
        'productosSeleccionados' => 'array',
        'productosSeleccionados.*' => 'numeric|min:0'
    ]);

    try {
        DB::transaction(function () {
            $productosUtilizados = collect($this->productosSeleccionados)
                ->filter(fn($cantidad) => $cantidad > 0)
                ->toArray();

            // Verificar stock antes de proceder
            foreach ($productosUtilizados as $idProducto => $cantidad) {
                $producto = Producto::findOrFail($idProducto);
                if ($producto->stock < $cantidad) {
                    throw new \Exception("No hay suficiente stock para {$producto->nombre}");
                }
            }

            // Crear detalle del servicio
            $detalle = DetalleServicio::create([
                'id_servicio' => $this->servicioSeleccionado->id_servicio,
                'user_id' => auth()->id(),
                'nota' => $this->solucionAplicada,
            ]);

            // Registrar productos utilizados
            foreach ($productosUtilizados as $idProducto => $cantidad) {
                ServicioProducto::create([
                    'id_servicio' => $this->servicioSeleccionado->id_servicio,
                    'id_producto' => $idProducto,
                    'cantidad' => $cantidad,
                    'id_detalle_servicio' => $detalle->id
                ]);

                Producto::where('id', $idProducto)->decrement('stock', $cantidad);
            }

            $this->servicioSeleccionado->update([
                'estado' => 'completado',
                'id_empleado_confirma' => auth()->id()
            ]);
        });

        $this->modalConfirmar = false;
        $this->dispatch('notify-success', message: 'Servicio confirmado correctamente');
        $this->dispatch('servicio-actualizado');

    } catch (\Exception $e) {
        $this->dispatch('notify-error', message: 'Error al confirmar servicio: '.$e->getMessage());
    }
}
    public function incrementarProducto($idProducto)
    {
        $producto = Producto::find($idProducto);
        if ($producto && $this->productosSeleccionados[$idProducto] < $producto->stock) {
            $this->productosSeleccionados[$idProducto]++;
        }
    }

    public function decrementarProducto($idProducto)
    {
        if ($this->productosSeleccionados[$idProducto] > 0) {
            $this->productosSeleccionados[$idProducto]--;
        }
    }
    public function eliminarServicio($idServicio)
{
    try {
        $servicio = ServicioTecnico::findOrFail($idServicio);
        
        if ($servicio->estado !== 'completado') {
            throw new \Exception('Solo se pueden eliminar servicios completados');
        }

        DB::transaction(function () use ($servicio) {
            // Eliminar productos asociados
            ServicioProducto::where('id_servicio', $servicio->id_servicio)->delete();
            
            // Eliminar detalles
            DetalleServicio::where('id_servicio', $servicio->id_servicio)->delete();
            
            // Eliminar servicio
            $servicio->delete();
        });

        session()->flash('success', 'Servicio eliminado correctamente');
        $this->dispatch('servicio-eliminado');

    } catch (\Exception $e) {
        session()->flash('error', 'Error al eliminar: '.$e->getMessage());
    }
}
    
}
