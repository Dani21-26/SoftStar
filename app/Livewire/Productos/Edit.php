<?php

namespace App\Livewire\Productos;

use Livewire\Component;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Proveedor;
use Livewire\Attributes\On;

class Edit extends Component
{
    public $productoId;
    public $nombre = '';
    public $detalle = '';
    public $stock_cantidad = 0;
    public $stock_unidad = 'unidades';
    public $id_categoria = null;
    public $ubicacion = '';
    public $precio_cantidad = 0;
    public $precio_unidad = 1; // 1=COP, 1000=miles, 1000000=millones
    public $id_proveedor = null;
    public $estado = 'activo';
    public $showModal = false;

    #[On('abrir-modal-edicion')]
    public function cargarProducto($id = null)
    {
        if (!$id) return;
        
        $producto = Producto::findOrFail($id);
        
        // Separar stock en cantidad y unidad
        $stockParts = explode(' ', $producto->stock);
        $this->stock_cantidad = $stockParts[0];
        $this->stock_unidad = $stockParts[1] ?? 'unidades';
        
        // Determinar precio base y unidad
        $this->determinarPrecioBase($producto->precio);
        
        // Asignar el resto de propiedades
        $this->productoId = $id;
        $this->nombre = $producto->nombre;
        $this->detalle = $producto->detalle;
        $this->id_categoria = $producto->id_categoria;
        $this->ubicacion = $producto->ubicacion;
        $this->id_proveedor = $producto->id_proveedor;
        $this->estado = $producto->estado;
        
        $this->showModal = true;
    }

    protected function determinarPrecioBase($precio)
    {
        if ($precio >= 1000000 && $precio % 1000000 == 0) {
            $this->precio_unidad = 1000000;
            $this->precio_cantidad = $precio / 1000000;
        } elseif ($precio >= 1000 && $precio % 1000 == 0) {
            $this->precio_unidad = 1000;
            $this->precio_cantidad = $precio / 1000;
        } else {
            $this->precio_unidad = 1;
            $this->precio_cantidad = $precio;
        }
    }

    public function updatedIdCategoria($value)
    {
        // Si la categoría seleccionada es "Cable" 
        $categoriaCable = Categoria::where('nombre', 'like', '%cable%')->first();
        
        if ($categoriaCable && $value == $categoriaCable->id_categoria) {
            $this->stock_unidad = 'metros';
        }
    }

    public function cerrarModal()
    {
        $this->resetExcept('productoId');
        $this->showModal = false;
    }

    public function guardar()
    {
        $validated = $this->validate([
            'nombre' => 'required|string|max:255',
            'detalle' => 'required|string',
            'stock_cantidad' => 'required|numeric|min:0',
            'stock_unidad' => 'required|in:unidades,metros,rollos,juegos',
            'id_categoria' => 'required|exists:categorias,id_categoria',
            'ubicacion' => 'required|string|max:100',
            'precio_cantidad' => 'required|numeric|min:0',
            'precio_unidad' => 'required|in:1,1000,1000000',
            'id_proveedor' => 'required|exists:proveedores,id_proveedor',
            'estado' => 'required|in:activo,inactivo'
        ]);
        
        $producto = Producto::findOrFail($this->productoId);
        
        $producto->update([
            'nombre' => $this->nombre,
            'detalle' => $this->detalle,
            'stock' => $this->stock_cantidad . ' ' . $this->stock_unidad,
            'id_categoria' => $this->id_categoria,
            'ubicacion' => $this->ubicacion,
            'precio' => $this->precio_cantidad * $this->precio_unidad,
            'id_proveedor' => $this->id_proveedor,
            'estado' => $this->estado
        ]);
        
        $this->cerrarModal();
        $this->dispatch('producto-actualizado');
        
        session()->flash('success', 'Producto actualizado correctamente');
    }

    public function mount()
    {
        $this->authorize('editar producto');
    }

    public function render()
    {
        return view('livewire.productos.edit', [
            'categorias' => Categoria::all(),
            'proveedores' => Proveedor::orderBy('nombre_empresa')->get()
        ]);
    }
}