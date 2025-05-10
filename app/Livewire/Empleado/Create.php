<?php

namespace App\Livewire\Empleado;

use Livewire\Component;
use App\Models\Empleado;

class Create extends Component
{
    public $nombre = '';
    public $cargo = '';
    public $ubicacion = '';
    public $telefono = '';
    public $correo = '';
    public $estado = 'activo';

    protected $rules = [
        'nombre' => 'required|string|max:255',
        'cargo' => 'required|string|max:255',
        'ubicacion' => 'required|string|max:255',
        'telefono' => 'required|string|max:20',
        'correo' => 'required|email|unique:empleados,correo',
        'estado' => 'required|in:activo,inactivo'
    ];

    protected $messages = [
        'correo.unique' => 'Este correo electrónico ya está registrado',
        'correo.email' => 'Debe ingresar un correo electrónico válido',
        'required' => 'Este campo es obligatorio',
    ];

    public function save()
    {
        $this->validate();

        try {
            // Debug: Verificar valores antes de guardar
            logger()->info('Datos a guardar:', [
                'nombre' => $this->nombre,
                'cargo' => $this->cargo,
                'ubicacion' => $this->ubicacion,
                'telefono' => $this->telefono,
                'correo' => $this->correo
            ]);

            $empleado = Empleado::create([
                'nombre' => trim($this->nombre),
                'cargo' => trim($this->cargo),
                'ubicacion' => trim($this->ubicacion),
                'telefono' => trim($this->telefono),
                'correo' => trim($this->correo),
                'estado' => $this->estado
            ]);

            // Debug: Verificar empleado creado
            logger()->info('Empleado creado:', $empleado->toArray());

            $this->reset();
            session()->flash('success', 'Empleado creado correctamente');
            $this->redirect(route('empleado.index'));

        } catch (\Exception $e) {
            logger()->error('Error al guardar empleado:', ['error' => $e->getMessage()]);
            session()->flash('error', 'Error al guardar: '.$e->getMessage());
            
            if (app()->environment('local')) {
                dd($e->getMessage(), $e->getTrace());
            }
        }
    }

    public function render()
    {
        return view('livewire.empleado.create', [
            'cargosDisponibles' => [
                'Administrador',
                'Ingeniero en Redes',
                'Servicio al Cliente',
                'Servicio Técnico', 
                'Auxiliar'
            ],
            'ubicacionesDisponibles' => [
                'Oficina',
                'Campo'
            ]
        ]);
    }
}