<?php

namespace App\Livewire\Empleado;

use Livewire\Component;
use App\Models\Empleado;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Access\AuthorizationException;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'tailwind';
    public $search = '';
    public $perPage = 4;
    public $sortField = 'nombre';
    public $sortDirection = 'asc';
    public $estado = 'activo';
    public $ubicacion = '';
    public $ubicacionesUnicas = [];
    

    
    protected $queryString = [
        'search' => ['except' => '', 'as' => 'q'],
        'perPage' => ['except' => 10, 'as' => 'pp'],
        'sortField' => ['as' => 'sort'],
        'sortDirection' => ['as' => 'dir'],
        'estado' => ['except' => 'activo', 'as' => 'st'],
        'ubicacion' => ['except' => '', 'as' => 'loc']
    ];
    
    protected $listeners = [
        'empleado-creado' => '$refresh',
        'empleado-actualizado' => '$refresh',
        'abrir-modal-flux' => '$refresh',
        'filtro-cambiado' => 'aplicarFiltros'
    ];

    public function mount()
    {   
        try {
        $this->authorize('ver empleado');
        $this->ubicacionesUnicas = Empleado::select('ubicacion')
            ->distinct()
            ->whereNotNull('ubicacion')
            ->orderBy('ubicacion')
            ->pluck('ubicacion')
            ->toArray();

            
    } catch (AuthorizationException $e) {
        abort(403, 'No tienes permiso para ver esta información');
    }
            
    }

    public function aplicarFiltros()
    {
        $this->resetPage();
    }

    public function updated($property)
    {
        if (in_array($property, ['search', 'estado', 'ubicacion', 'perPage'])) {
            $this->resetPage();
        }
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        
        $this->sortField = $field;
    }

    public function toggleStatus($id_empleado)
{
    try {
        Log::info("Intentando cambiar estado para empleado ID: {$id_empleado}");
        
        $empleado = Empleado::where('id_empleado', $id_empleado)->first();
        
        if (!$empleado) {
            throw new \Exception("No se encontró empleado con ID: {$id_empleado}");
        }

        // Solo permitir desactivar
        if ($empleado->estado === 'activo') {
            $empleado->estado = 'inactivo';
            $empleado->save();

            Log::info("Empleado desactivado: {$empleado->nombre} (ID: {$empleado->id_empleado})");

            $this->dispatch('empleado-actualizado');
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => '¡Empleado desactivado!',
                'text' => "El empleado fue desactivado correctamente.",
                'confirmButtonText' => 'OK',
            ]);
        } else {
            // Intento de activar desde aquí -> bloqueamos
            $this->dispatch('swal', [
                'icon' => 'warning',
                'title' => 'Acción no permitida',
                'text' => 'Un empleado inactivo solo puede ser activado desde el modal de edición.',
            ]);
        }
    } catch (\Exception $e) {
        Log::error("Error en toggleStatus: " . $e->getMessage());

        $this->dispatch('swal', [
            'icon' => 'error',
            'title' => 'Error',
            'text' => 'Ocurrió un error al cambiar el estado del empleado.',
        ]);
    }
}



    public function edit($id_empleado)
    {
    $this->dispatch('abrirModalEdicion', id: $id_empleado)->to(Edit::class);
    }
    

    public function render()
    {
        $empleados = Empleado::query()
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('nombre', 'like', '%'.$this->search.'%')
                    ->orWhere('cargo', 'like', '%'.$this->search.'%')
                    ->orWhere('correo', 'like', '%'.$this->search.'%')
                    ->orWhere('telefono', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->estado, function($query) {
                $query->where('estado', $this->estado);
            })
            ->when($this->ubicacion, function($query) {
                $query->where('ubicacion', $this->ubicacion);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.empleado.index', [
            'empleados' => $empleados,
            'ubicacionesUnicas' => $this->ubicacionesUnicas
        ]);
    }
}