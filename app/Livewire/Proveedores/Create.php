<?php

namespace App\Livewire\Proveedores;

use Livewire\Component;
use App\Models\Proveedor;
use Illuminate\Support\Facades\DB;

class Create extends Component
{
    public $nombre_empresa = '';
    public $contacto_nombre = '';
    public $telefono = '';
    public $correo = '';
    public $direccion = '';

    protected $rules = [
        'nombre_empresa' => 'required|string|max:255',
        'contacto_nombre' => 'required|string|max:255',
        'telefono' => 'required|string|max:20',
        'correo' => 'required|email|unique:proveedores,correo',
        'direccion' => 'required|string|max:255'
    ];

    public function save()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            // Debug avanzado
            logger()->debug('Intentando crear proveedor con:', [
                'data' => $this->all(),
                'memory' => memory_get_usage()
            ]);

            $proveedor = Proveedor::create([
                'nombre_empresa' => $this->nombre_empresa,
                'contacto_nombre' => $this->contacto_nombre,
                'telefono' => $this->telefono,
                'correo' => $this->correo,
                'direccion' => $this->direccion
                // 'estado' se asigna automáticamente por el modelo
            ]);

            DB::commit();

            // Verificación explícita
            if(!$proveedor->exists) {
                throw new \Exception('El objeto proveedor no se persistió correctamente');
            }

            logger()->info('Proveedor creado exitosamente', [
                'id' => $proveedor->id_proveedor,
                'correo' => $proveedor->correo
            ]);

            $this->reset();
            session()->flash('success', 'Proveedor creado correctamente');
            return redirect()->route('proveedores.index');

        } catch (\Exception $e) {
            DB::rollBack();
            
            logger()->error('Fallo al crear proveedor:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $this->all()
            ]);
            
            session()->flash('error', 'Error al crear proveedor: ' . $e->getMessage());
            
            // Debug detallado solo en local
            if (app()->environment('local')) {
                dd([
                    'error' => $e->getMessage(),
                    'trace' => $e->getTrace(),
                    'input_data' => $this->all(),
                    'last_query' => DB::getQueryLog()
                ]);
            }
        }
    }

    public function render()
    {
        return view('livewire.proveedores.create');
    }
}