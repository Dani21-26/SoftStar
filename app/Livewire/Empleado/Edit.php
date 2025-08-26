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
            'nombre'    => 'required|string|max:255',
            'cargo'     => 'required|string|max:255',
            'ubicacion' => 'required|string|max:255',
            'telefono'  => 'required|string|max:20',
            'correo'    => 'required|email|unique:empleados,correo,' . $this->empleadoId . ',id_empleado',
            'estado'    => 'required|in:activo,inactivo',
        ];
    }

    protected $messages = [
        'correo.unique' => 'Este correo ya está registrado por otro empleado',
        'required'      => 'Este campo es obligatorio',
        'email'         => 'Debe ingresar un correo válido',
    ];

    public function abrirModal($id)
    {
        try {
            $empleado = Empleado::findOrFail($id);

            $this->empleadoId = $empleado->id_empleado;
            $this->nombre     = $empleado->nombre;
            $this->cargo      = $empleado->cargo;
            $this->ubicacion  = $empleado->ubicacion;
            $this->telefono   = $empleado->telefono;
            $this->correo     = $empleado->correo;
            $this->estado     = $empleado->estado;

            $this->showModal = true;

        } catch (ModelNotFoundException $e) {
            $this->dispatch('swal', [
                'icon'  => 'error',
                'title' => 'Error',
                'text'  => 'Empleado no encontrado.',
            ]);
            $this->cerrarModal();
        }
    }

    public function guardar()
    {
        $this->validate();

        try {
            $empleado = Empleado::findOrFail($this->empleadoId);

            $empleado->update([
                'nombre'    => $this->nombre,
                'cargo'     => $this->cargo,
                'ubicacion' => $this->ubicacion,
                'telefono'  => $this->telefono,
                'correo'    => $this->correo,
                'estado'    => $this->estado,
            ]);

            $this->dispatch('swal', [
                'icon'  => 'success',
                'title' => '¡Empleado actualizado!',
                'text'  => 'Los datos fueron guardados correctamente.',
            ]);

            // Refrescar la lista del padre
            $this->dispatch('empleado-actualizado');

            // Cerrar modal
            $this->cerrarModal();

        } catch (\Exception $e) {
            logger()->error($e->getMessage());

            $this->dispatch('swal', [
                'icon'  => 'error',
                'title' => 'Error',
                'text'  => 'Error al actualizar el empleado.',
            ]);
        }
    }

    public function cerrarModal()
    {
        $this->reset([
            'empleadoId',
            'nombre',
            'cargo',
            'ubicacion',
            'telefono',
            'correo',
            'estado',
        ]);

        // al cerrar el modal lo volvemos a false
        $this->showModal = false;
    }

    public function mount()
    {
        $this->authorize('editar empleado');
    }

    public function render()
    {
        return view('livewire.empleado.edit', [
            'cargosDisponibles'      => $this->getCargos(),
            'ubicacionesDisponibles' => $this->getUbicaciones(),
        ]);
    }

    protected function getCargos()
    {
        return [
            'Administrador',
            'Ingeniero en Redes',
            'Servicio al Cliente',
            'Servicio Técnico',
            'Auxiliar',
        ];
    }

    protected function getUbicaciones()
    {
        return [
            'Oficina',
            'Campo',
        ];
    }
}
