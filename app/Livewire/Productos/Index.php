<?php
namespace App\Livewire\Productos;

use Livewire\Component;
use App\Models\Producto; 
use Livewire\WithPagination; 

class Index extends Component
{
    use WithPagination;
    
    public $search = '';
    public $perPage = 10;
    public $sortField = 'nombre';
    public $sortDirection = 'asc';
    public $estado = 'activo'; 
    
    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'sortField',
        'sortDirection',
        'estado' => ['except' => 'activo']
    ];
    
    protected $listeners = [
        'producto-creado' => '$refresh',
        'producto-actualizado' => '$refresh',
        'abrir-modal-flux' => '$refresh'
    ];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        
        $this->sortField = $field;
    }

    // Eliminación lógica (cambia estado a inactivo)
    public function delete($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->update(['estado' => 'inactivo']);
        
        session()->flash('success', 'Producto desactivado correctamente');
        $this->dispatch('producto-actualizado');
    }

    // Cambiar estado (activo/inactivo)
    public function toggleStatus($id)
    {
        $producto = Producto::findOrFail($id);
        $newStatus = $producto->estado == 'activo' ? 'inactivo' : 'activo';
        $producto->update(['estado' => $newStatus]);
        
        $message = $newStatus == 'activo' ? 'Producto activado' : 'Producto desactivado';
        session()->flash('success', $message . ' correctamente');
    }

    public function edit($id)
    {
        $this->dispatch('abrir-modal-edicion', id: $id)->to(Edit::class);
    }
    public function mount()
    {
        $this->authorize('ver producto');
    }
    
    public function render()
    {
        return view('livewire.productos.index', [
            'productos' => Producto::query()
                ->with(['proveedor', 'categoria'])
                ->when($this->search, function($query) {
                    $query->where('nombre', 'like', '%'.$this->search.'%')
                        ->orWhere('detalle', 'like', '%'.$this->search.'%');
                })
                ->when($this->estado, function($query) {
                    $query->where('estado', $this->estado);
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->perPage)
        ]);
    }
}