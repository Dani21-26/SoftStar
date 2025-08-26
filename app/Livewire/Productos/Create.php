<?php

namespace App\Livewire\Productos;

use Livewire\Component;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Proveedor;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Create extends Component
{
    use AuthorizesRequests;
    public $nombre = '';
    public $detalle = '';
    public $stock_cantidad = 0;
    public $stock_unidad = 'unidades';
    public $id_categoria = null;
    public $ubicacion = '';
    public $precio_cantidad = 0;
    public $precio_unidad = 1; 
    public $id_proveedor = null;
    public bool $showModal = false;

    protected $rules = [
        'nombre' => 'required|string|max:255|unique:productos,nombre',
        'detalle' => 'required|string',
        'stock_cantidad' => 'required|numeric|min:0',
        'stock_unidad' => 'required|in:unidades,metros,rollos,juegos',
        'id_categoria' => 'required|exists:categorias,id_categoria',
        'ubicacion' => 'required|string|max:255',
        'precio_cantidad' => 'required|numeric|min:0',
        'precio_unidad' => 'required|in:1,1000,1000000',
        'id_proveedor' => 'required|exists:proveedores,id_proveedor'
    ];

    protected $messages = [
        'nombre.unique' => 'Este producto ya está registrado.',
        'precio_cantidad.numeric' => 'El precio debe ser un número válido',
        'stock_cantidad.numeric' => 'La cantidad debe ser un número válido',
        '*.required' => 'Este campo es obligatorio',
    ];

    public function updatedIdCategoria($value)
    {
        $categoriaCable = Categoria::where('nombre', 'like', '%cable%')->first();
        
        if ($categoriaCable && $value == $categoriaCable->id_categoria) {
            $this->stock_unidad = 'metros';
        } else {
            $this->stock_unidad = 'unidades';
        }
    }

    public function save()
    {
        $this->validate();

          // Verificar que el proveedor seleccionado esté activo
        $proveedor = Proveedor::where('id_proveedor', $this->id_proveedor)
            ->where('estado', 'activo')
            ->first();

        if (!$proveedor) {
            $this->dispatch('swal', [
            'icon' => 'error',
            'title' => 'Proveedor inválido',
            'text' => 'El proveedor seleccionado está inactivo o no existe.',
            'confirmButtonText' => 'OK',
        ]);
        return;
        }

        try {
            
            // Combina cantidad y unidad para el stock
          
            
            // Calcula el precio final
            $precio = $this->precio_cantidad * $this->precio_unidad;
            $producto = Producto::create([
                'nombre' => $this->nombre,
                'detalle' => $this->detalle,
                'stock' => $this->stock_cantidad,      
                'stock_unidad' => $this->stock_unidad,        
                'id_categoria' => (int)$this->id_categoria,
                'ubicacion' => $this->ubicacion,
                'precio' => $this->precio_cantidad * $this->precio_unidad,
                'id_proveedor' => (int)$this->id_proveedor,
                'estado' => 'activo'
]);

        

            $this->showModal = false;
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => '¡Creado!',
                'text' => 'producto agregado correctamente',
                'confirmButtonText' => 'OK',
                'redirect' => route('productos.index'),
            

            ]); 
        $this->reset(['nombre','detalle','stock_cantidad','stock_unidad','id_categoria','ubicacion','precio_cantidad','precio_unidad','id_proveedor']);

        } catch (\Exception $e) {
            logger()->error('Error al guardar:', ['error' => $e->getMessage()]);

            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Error al guardar: ' . $e->getMessage(),
                'confirmButtonText' => 'OK',
            ]);
            
            if (app()->environment('local')) {
                dd($e->getMessage(), $e->getTrace());
            }
        }
    }

    public function mount()
    {
        $this->authorize('crear producto');
    }

    public function render()
    {
        return view('livewire.productos.create', [
            'categorias' => Categoria::all(),
            'proveedores' => Proveedor::where('estado', 'activo')
            ->orderBy('nombre_empresa')
            ->get()
        ]);
    }
}