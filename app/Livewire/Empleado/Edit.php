<?php

namespace App\Livewire\Empleado;

use Livewire\Component;
use App\Models\Empleado;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Edit extends Component
{
    public $showModal = false;
    public $empleadoId;
    public $nombre = '';
    public $cargo = '';
    public $ubicacion = '';
    public $telefono = '';
    public $correo = '';
    public $estado = 'activo';

    protected $listeners = ['abrirModalEdicion' => 'abrirModal'];

    protected function rules()
    {
        return [
            'nombre' => 'required|string|max:255',
            'cargo' => 'required|string|max:255',
            'ubicacion' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'correo' => 'required|email|unique:empleados,correo,'.$this->empleadoId.',id_empleado',
            'estado' => 'required|in:activo,inactivo'
        ];
    }

    protected $messages = [
        'correo.unique' => 'Este correo ya está registrado por otro empleado',
        'required' => 'Este campo es obligatorio',
        'email' => 'Debe ingresar un correo válido'
    ];

    public function abrirModal($id)
    {
        try {
            $empleado = Empleado::findOrFail($id);
            
            $this->empleadoId = $id;
            $this->nombre = $empleado->nombre;
            $this->cargo = $empleado->cargo;
            $this->ubicacion = $empleado->ubicacion;
            $this->telefono = $empleado->telefono;
            $this->correo = $empleado->correo;
            $this->estado = $empleado->estado;
            
            $this->showModal = true;
            
        } catch (ModelNotFoundException $e) {
            $this->dispatch('notify', 
                type: 'error',
                message: 'Empleado no encontrado'
            );
            $this->cerrarModal();
        }
    }

    public function guardar()
    {
        $this->validate();

        try {
            $empleado = Empleado::findOrFail($this->empleadoId);
            
            $empleado->update([
                'nombre' => $this->nombre,
                'cargo' => $this->cargo,
                'ubicacion' => $this->ubicacion,
                'telefono' => $this->telefono,
                'correo' => $this->correo,
                'estado' => $this->estado
            ]);

            $this->dispatch('notify', 
                type: 'success',
                message: 'Empleado actualizado correctamente'
            );
            
            $this->cerrarModal();
            $this->dispatch('empleado-actualizado');

        } catch (\Exception $e) {
            $this->dispatch('notify', 
                type: 'error',
                message: 'Error al actualizar: '.$e->getMessage()
            );
        }
    }

    public function cerrarModal()
    {
        $this->resetExcept('empleadoId');
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.empleado.edit', [
            'cargosDisponibles' => $this->getCargos(),
            'ubicacionesDisponibles' => $this->getUbicaciones()
        ]);
    }

    protected function getCargos()
    {
        return [
            'Administrador',
            'Ingeniero en Redes',
            'Servicio al Cliente', 
            'Servicio Técnico',
            'Auxiliar'
        ];
    }

    protected function getUbicaciones()
    {
        return [
            'Oficina',
            'Campo'
        ];
    }
}