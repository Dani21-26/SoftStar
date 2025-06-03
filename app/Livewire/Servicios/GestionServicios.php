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
    public $estadoFiltro = 'por_tomar';
    
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
        ->when($this->estadoFiltro, function($query) {
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
            'id_empleado'
        ]);
        
        $this->reset([
            'cliente', 'router', 'litebean', 'direccion', 
            'fallaReportada', 'observaciones'
        ]);
        
        session()->flash('success', 'Solicitud de servicio creada correctamente');
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
        'productosSeleccionados' => [
            'required',
            function ($attribute, $value, $fail) {
                if (!collect($value)->some(fn($qty) => $qty > 0)) {
                    $fail('Debe seleccionar al menos un material utilizado');
                }
            }
        ]
    ]);

    DB::transaction(function () {
        // Filtrar productos con cantidad > 0
        $productosUtilizados = array_filter($this->productosSeleccionados, fn($q) => $q > 0);
        
        // Crear detalle del servicio
        DetalleServicio::create([
            'id_servicio' => $this->servicioSeleccionado->id_servicio,
            'user_id' => auth()->id(),
            'nota' => $this->solucionAplicada,
            'productos_utilizados' => $productosUtilizados
        ]);
        
        // Actualizar stock de productos
        foreach ($productosUtilizados as $idProducto => $cantidad) {
            Producto::where('id', $idProducto)->decrement('stock', $cantidad);
        }
        
        // Actualizar solo el estado del servicio (sin id_empleado_confirma)
        $this->servicioSeleccionado->update([
            'estado' => 'completado'
        ]);
    });
    
    $this->modalConfirmar = false;
    $this->dispatch('servicio-actualizado');
    session()->flash('success', 'Servicio confirmado correctamente');
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
    
}
