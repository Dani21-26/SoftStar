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
    public bool $showModal = false; 
    public $estado = 'activo';
    public $perPage = 4;

    protected $rules = [
        'nombre_empresa' => 'required|string|max:255|unique:proveedores,nombre_empresa',
        'contacto_nombre' => 'required|string|max:255',
        'telefono' => 'required|string|max:20',
        'correo' => 'required|email|unique:proveedores,correo',
        'direccion' => 'required|string|max:255'
    ];
   protected $messages = [
    'nombre_empresa.unique' => 'Este proveedor ya está registrado.',
];

public function save()
{
    $validated = $this->validate();

    DB::beginTransaction();
    try {
        logger()->debug('Intentando crear proveedor con:', [
            'data' => $validated,
            'memory' => memory_get_usage()
        ]);

        $proveedor = Proveedor::create($validated);

        if (!$proveedor->exists) {
            throw new \Exception('El objeto proveedor no se persistió correctamente');
        }

        DB::commit();

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Proveedor creado!',
            'text' => 'Proveedor agregado correctamente',
            'confirmButtonText' => 'OK',
            'redirect' => route('proveedores.index'),
        ]); 
        $this->reset();
    
    } catch (\Exception $e) {
        DB::rollBack();

        logger()->error('Fallo al crear proveedor:', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'input' => $validated ?? []
        ]);

        $this->dispatch('swal', [
            'icon' => 'error',
            'title' => 'Error',
            'text' => 'Error al guardar: ' . $e->getMessage(),
            'confirmButtonText' => 'OK',
        ]);

        if (app()->environment('local')) {
            dd([
                'error' => $e->getMessage(),
                'trace' => $e->getTrace(),
                'input_data' => $validated ?? [],
            ]);
        }
    }
}

    public function mount()
    {
        $this->authorize('crear proveedor');
    }
    
    public function render()
    {
        return view('livewire.proveedores.create');
    }
}