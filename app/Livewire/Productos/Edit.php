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
    public $stock = 0;
    public $id_categoria = null;
    public $ubicacion = '';
    public $precio = 0;
    public $id_proveedor = null;
    public $estado = 'activo';
    public $showModal = false;

    // Corrección: Elimina el parámetro $event si no es necesario
    #[On('abrir-modal-edicion')]
    public function cargarProducto($id = null)
    {
        if (!$id) return;
        
        $producto = Producto::findOrFail($id);
        
        $this->productoId = $id;
        $this->nombre = $producto->nombre;
        $this->detalle = $producto->detalle;
        $this->stock = $producto->stock;
        $this->id_categoria = $producto->id_categoria;
        $this->ubicacion = $producto->ubicacion;
        $this->precio = $producto->precio;
        $this->id_proveedor = $producto->id_proveedor;
        $this->estado = $producto->estado;
        
        $this->showModal = true;
    }
    public function cerrarModal()
    {
        $this->showModal = false;
    }

    public function guardar()
    {
        $validated = $this->validate([
            'nombre' => 'required|string|max:255',
            'detalle' => 'required|string',
            'stock' => 'required|integer|min:0',
            'id_categoria' => 'required|exists:categorias,id_categoria',
            'ubicacion' => 'required|string|max:100',
            'precio' => 'required|numeric|min:0',
            'id_proveedor' => 'required|exists:proveedores,id_proveedor',
            'estado' => 'required|in:activo,inactivo'
        ]);
        
        $producto = Producto::findOrFail($this->productoId);
        $producto->update($validated);
        
        $this->cerrarModal();
        $this->dispatch('producto-actualizado');
        
        session()->flash('success', 'Producto actualizado correctamente');
    }

    public function render()
    {
        return view('livewire.productos.edit', [
            'categorias' => Categoria::all(),
            'proveedores' => Proveedor::orderBy('nombre_empresa')->get()
        ]);
    }
}