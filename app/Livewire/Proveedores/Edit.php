<?php

namespace App\Livewire\Proveedores;

use Livewire\Component;
use App\Models\Proveedor;

class Edit extends Component
{ 
    public $showModal = false;
    public $id_proveedor;
    public $proveedor;
    
    // Propiedades del formulario
    public $nombre_empresa;
    public $contacto_nombre;
    public $telefono;
    public $correo;
    public $direccion;
    public $estado = 'activo';
    
    protected $listeners = ['abrirModalEdicion' => 'abrirModal'];

    public function abrirModal($id)
    {
        $this->id_proveedor = $id;
        $this->cargarProveedor();
        $this->showModal = true;
    }
    
    public function cargarProveedor()
    {
        $this->proveedor = Proveedor::findOrFail($this->id_proveedor);
        
        // Asignar valores a las propiedades
        $this->nombre_empresa = $this->proveedor->nombre_empresa;
        $this->contacto_nombre = $this->proveedor->contacto_nombre;
        $this->telefono = $this->proveedor->telefono;
        $this->correo = $this->proveedor->correo;
        $this->direccion = $this->proveedor->direccion;
        $this->estado = $this->proveedor->estado;
    }
    
    public function cerrarModal()
    {
        $this->reset();
        $this->showModal = false;
    }
    
    public function guardar()
    {
        $validated = $this->validate([
            'nombre_empresa' => 'required|string|max:255',
            'contacto_nombre' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'correo' => 'required|email|max:255',
            'direccion' => 'required|string|max:255',
            'estado' => 'required|in:activo,inactivo',
        ]);
        
        Proveedor::findOrFail($this->id_proveedor)->update($validated);
        
        $this->dispatch('proveedor-actualizado');
        $this->cerrarModal();
        
        session()->flash('success', 'Proveedor actualizado correctamente');
    }

    public function render()
    {
        return view('livewire.proveedores.edit');
    }
}