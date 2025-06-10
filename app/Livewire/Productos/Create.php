<?php

namespace App\Livewire\Productos;

use Livewire\Component;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Proveedor;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Create extends Component
{
    public $nombre = '';
    public $detalle = '';
    public $stock_cantidad = 0;
    public $stock_unidad = 'unidades';
    public $id_categoria = null;
    public $ubicacion = '';
    public $precio_cantidad = 0;
    public $precio_unidad = 1; // 1=COP, 1000=miles, 1000000=millones
    public $id_proveedor = null;

    protected $rules = [
        'nombre' => 'required|string|max:255',
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
        'precio_cantidad.numeric' => 'El precio debe ser un número válido',
        'stock_cantidad.numeric' => 'La cantidad debe ser un número válido',
        '*.required' => 'Este campo es obligatorio',
    ];

    public function updatedIdCategoria($value)
    {
        // Si la categoría seleccionada es "Cable" (ajusta el ID según tu base de datos)
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

        try {
            // Combina cantidad y unidad para el stock
            $stock = $this->stock_cantidad . ' ' . $this->stock_unidad;
            
            // Calcula el precio final
            $precio = $this->precio_cantidad * $this->precio_unidad;

            $producto = Producto::create([
                'nombre' => $this->nombre,
                'detalle' => $this->detalle,
                'stock' => $stock,
                'id_categoria' => (int)$this->id_categoria,
                'ubicacion' => $this->ubicacion,
                'precio' => $precio,
                'id_proveedor' => (int)$this->id_proveedor,
                'estado' => 'activo'
            ]);

            session()->flash('success', 'Producto creado correctamente');
            $this->redirect(route('productos.index'));

        } catch (\Exception $e) {
            logger()->error('Error al guardar:', ['error' => $e->getMessage()]);
            session()->flash('error', 'Error al guardar: '.$e->getMessage());
            
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
            'proveedores' => Proveedor::orderBy('nombre_empresa')->get()
        ]);
    }
}