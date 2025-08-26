<?php
namespace App\Livewire\Servicios;

use Livewire\Component;
use App\Models\Producto;
use App\Models\DetalleServicio;
use App\Models\ServicioProducto;
use App\Models\ServicioTecnico;
use Illuminate\Support\Facades\Auth;

class ConfirmarServicio extends Component
{
    public $nota = '';
    public $productos = [];
    public $seleccionados = []; // <- aquí guardamos los productos elegidos
    public $servicioId;
    public $servicioSeleccionado;
    public $showModal = false;
    public $alertaActiva = false;
    public $search = ''; 

    protected $listeners = ['set-servicio-id' => 'cargarDatos', 'set-show-modal' => 'setShowModal'];

    public function setShowModal($value)
    {
        $this->showModal = $value;
    }

    public function cargarDatos($id)
    {
        $this->servicioId = $id;
        $this->servicioSeleccionado = ServicioTecnico::with(['tecnico:id,name'])->find($id);

        $this->productos = [];
        $this->seleccionados = [];
        $this->nota = '';
    }

    public function updatedSearch()
    {
        if (strlen($this->search) > 2) {
            $this->productos = Producto::where('estado', 'activo')
                ->where('stock', '>', 0)
                ->where('nombre', 'like', '%' . $this->search . '%')
                ->limit(10)
                ->get();
        } else {
            $this->productos = [];
        }
    }

    public function seleccionarProducto($productoId)
    {
        $producto = Producto::find($productoId);

        if ($producto && $producto->estado === 'activo' && $producto->stock > 0) {
            $this->seleccionados[$productoId] = [
                'nombre' => $producto->nombre,
                'stock' => $producto->stock,
                'cantidad' => ($this->seleccionados[$productoId]['cantidad'] ?? 1),
            ];
        }

        $this->search = '';
        $this->productos = [];
    }

    public function actualizarCantidad($id, $cantidad)
    {
        if (isset($this->seleccionados[$id])) {
            $cantidad = max(1, min($cantidad, $this->seleccionados[$id]['stock']));
            $this->seleccionados[$id]['cantidad'] = $cantidad;
        }
    }

    public function quitarProducto($id)
    {
        unset($this->seleccionados[$id]);
    }

    public function confirmar()
    {
        $this->validate([
            'nota' => 'required|string|min:5',
        ]);

        $servicio = ServicioTecnico::find($this->servicioId);
        $tecnicoAsignadoId = $servicio?->tecnico_id;
        // Validación: si no hay técnico asignado, mostrar alerta y salir
        if (!$tecnicoAsignadoId) {
        $this->showModal = false;
        $this->dispatch('swal', [
            'icon' => 'warning',
            'title' => 'No se puede confirmar el servicio',
            'text' => 'Por favor, asigne un técnico antes de confirmar el servicio.',
            'confirmButtonText' => 'OK',
            'delay' => 50, 
        ]);
        return; // Detiene la ejecución si no hay técnico
    }
        DetalleServicio::create([
            'id_servicio' => $this->servicioId,
            'user_id' => $tecnicoAsignadoId,
            'nota' => $this->nota,
            'productos_utilizados' => json_encode($this->seleccionados),
        ]);

        foreach ($this->seleccionados as $productoId => $item) {
            if ($item['cantidad'] > 0) {
                $producto = Producto::where('id', $productoId)
                    ->where('estado', 'activo')
                    ->first();

                if (!$producto) continue;

                ServicioProducto::create([
                    'id_servicio' => $this->servicioId,
                    'id_producto' => $productoId,
                    'cantidad' => $item['cantidad'],
                ]);

                $producto->stock -= $item['cantidad'];
                $producto->save();
            }
        }

        ServicioTecnico::where('id', $this->servicioId)->update(['estado' => 'confirmado']);

        $this->alertaActiva = true;
        $this->showModal = false;

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Servicio confirmado!',
            'text' => 'El servicio fue confirmado correctamente.',
            'confirmButtonText' => 'OK',
            'redirect' => route('servicios.agenda'),
            'delay' => 50, 
        ]);
    }

    public function render()
    {
        return view('livewire.servicios.confirmar-servicio');
    }
}