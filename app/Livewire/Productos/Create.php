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
    public $stock = 0;
    public $id_categoria = null;
    public $ubicacion = '';
    public $precio = 0;
    public $id_proveedor = null;


protected $rules = [
    'nombre' => 'required|string|max:255',
    'detalle' => 'required|string',
    'stock' => 'required|integer|min:0',
    'id_categoria' => 'required|exists:categorias,id_categoria',
    'ubicacion' => 'required|string|max:255',
    'precio' => ['required', 'regex:/^\d+(\.\d{1,2})?$/'], // Acepta números con hasta 2 decimales
    'id_proveedor' => 'required|exists:proveedores,id_proveedor'
];

protected $messages = [
    'precio.regex' => 'El precio debe ser un número válido con hasta dos decimales',
    'precio.required' => 'El precio es obligatorio',
];
public function save()
{
    $this->validate();

    try {
        // Depuración: Ver datos antes de guardar
        logger()->info('Datos validados:', $this->all());
        
        // Conversión explícita de tipos
        $producto = Producto::create([
            'nombre' => $this->nombre,
            'detalle' => $this->detalle,
            'stock' => (int)$this->stock,
            'id_categoria' => (int)$this->id_categoria,
            'ubicacion' => $this->ubicacion,
            'precio' => (float)$this->precio,
            'id_proveedor' => (int)$this->id_proveedor,
            'estado' => 'activo'
        ]);

        // Depuración: Ver producto creado
        logger()->info('Producto creado:', $producto->toArray());
        
        $this->reset();
        session()->flash('success', 'Producto creado correctamente');
        
        $this->redirect(route('productos.index'));

        
    } catch (\Exception $e) {
        logger()->error('Error al guardar:', ['error' => $e->getMessage()]);
        session()->flash('error', 'Error al guardar: '.$e->getMessage());
        
        // Depuración adicional
        if (app()->environment('local')) {
            dd($e->getMessage(), $e->getTrace());
        }
    }
}

    

    public function render()
    {
        return view('livewire.productos.create', [
            'categorias' => Categoria::all(),
            'proveedores' => Proveedor::orderBy('nombre_empresa')->get()
        ]);
    }
}